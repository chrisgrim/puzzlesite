<?php
namespace App\Http\Controllers;

use App\Actions\Bitcoin\BitcoinActions;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\RedirectifUserHasPaid;

class BitcoinController extends Controller
{
    protected $bitcoinActions;

    public function __construct(BitcoinActions $bitcoinActions)
    {
        $this->middleware(['auth', RedirectifUserHasPaid::class])->except('checkPaymentStatus');
        $this->bitcoinActions = $bitcoinActions;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bitcoin_address' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        try {
            // Check if the user already has an unpaid Bitcoin transaction
            $user = auth()->user();
            $existingTransaction = $user->orders()->where('payment_method', 'bitcoin')->where('status', 'pending')->first();

            if ($existingTransaction) {
                // Update the existing transaction with the new amount
                $existingTransaction->amount = $validated['amount'];
                $existingTransaction->description = $validated['description'];
                $existingTransaction->bitcoinTransaction()->update([
                    'amount' => $validated['amount'],
                    'status' => 'pending'
                ]);
            } else {
                // Create a new order and transaction
                $order = $user->orders()->create([
                    'amount' => $validated['amount'],
                    'description' => $validated['description'],
                    'payment_method' => 'bitcoin',
                    'status' => 'pending'
                ]);

                $order->bitcoinTransaction()->create([
                    'bitcoin_address' => $validated['bitcoin_address'],
                    'amount' => $validated['amount'],
                    'status' => 'pending'
                ]);
            }
            Log::info("order and transaction created");

            $webhookController = new BlockCypherWebhookController();
            $webhookResponse = $webhookController->registerWebhook(new Request([
                'url' => url('/api/blockcypher/webhook'),
                'bitcoin_address' => $validated['bitcoin_address'],
            ]));

            Log::info("Webhook {$webhookResponse}");

            $webhookData = json_decode($webhookResponse->getContent(), true);

            $bitcoinTransaction->update([
                'webhook_id' => $webhookData['id'],
                'webhook_url' => $webhookData['url'],
            ]);

            Log::info("webhook added");

            return response()->json([
                'order' => $order->load('bitcoinTransaction')
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create or update order: ' . $e->getMessage()], 500);
        }
    }

    public function checkPaymentStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        $bitcoinTransaction = $order->bitcoinTransaction;

        return response()->json([
            'status' => $bitcoinTransaction->status,
            'confirmed' => $bitcoinTransaction->status === 'completed',
            'confirmations' => $bitcoinTransaction->confirmations,
        ]);
    }

    public function getPendingOrder()
    {
        try {
            $user = auth()->user();
            $existingOrder = $user->orders()->where('payment_method', 'bitcoin')->where('status', 'pending')->first();

            if ($existingOrder) {
                return response()->json([
                    'order' => $existingOrder->load('bitcoinTransaction')
                ], 200);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch existing order: ' . $e->getMessage()], 500);
        }
    }

    public function generateAddress()
    {
        $address = $this->bitcoinActions->generateAddress();
        if (!$address) {
            return response()->json(['error' => 'Unable to generate Bitcoin address'], 500);
        }
        return response()->json([
            'bitcoin_address' => $address
        ]);
    }

    public function getAddressBalance()
    {
        return $this->bitcoinActions->getAddressBalance("CBk2XdZVCFVnmup6UATwkEsH1ugwYTwWBa");

        // $address = $request->input('address');
        // $balance = $this->bitcoinActions->getAddressBalance($address);
        // if ($balance === null) {
        //     return response()->json(['error' => 'Unable to fetch balance'], 500);
        // }
        // return response()->json([
        //     'address' => $address,
        //     'balance' => $balance
        // ]);
    }

    public function createTransaction(Request $request)
    {
        $fromAddress = $request->input('from_address');
        $toAddress = $request->input('to_address');
        $amount = $request->input('amount');
        $transaction = $this->bitcoinActions->createTransaction($fromAddress, $toAddress, $amount);
        if (!$transaction) {
            return response()->json(['error' => 'Unable to create transaction'], 500);
        }
        return response()->json($transaction);
    }

    public function sendTransaction(Request $request)
    {
        $signedTx = $request->input('signed_transaction');
        $result = $this->bitcoinActions->sendTransaction($signedTx);
        if (!$result) {
            return response()->json(['error' => 'Unable to send transaction'], 500);
        }
        return response()->json($result);
    }

    public function fundAddress(Request $request)
    {
        $address = $request->input('address', "BxHWsdJkYFTx3bvwBQDxBdGgqWH1SQFZMz");
        $amount = $request->input('amount', 46660);
        
        $result = $this->bitcoinActions->fundAddress($address, $amount);
        return response()->json($result);
    }

    public function price(Request $request)
    {
        $priceInUsd = $this->fetchFromCoinGecko();

        if (!$priceInUsd) {
            $priceInUsd = $this->fetchFromCoinCap();
        }

        if ($priceInUsd) {
            return response()->json(['bitcoin' => ['usd' => $priceInUsd]]);
        } else {
            return response()->json(['error' => 'Failed to fetch Bitcoin price from both CoinGecko and CoinCap'], 500);
        }
    }

    private function fetchFromCoinCap()
    {
        $response = Http::get('https://api.coincap.io/v2/rates/bitcoin');
        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['rateUsd'] ?? null;
        }
    }

    private function fetchFromCoinGecko()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
            'ids' => 'bitcoin',
            'vs_currencies' => 'usd'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['bitcoin']['usd'] ?? null;
        }
        
    }
}
