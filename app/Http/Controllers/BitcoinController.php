<?php
namespace App\Http\Controllers;

use App\Actions\Bitcoin\BitcoinActions;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class BitcoinController extends Controller
{
    protected $bitcoinActions;

    public function __construct(BitcoinActions $bitcoinActions)
    {
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

            return response()->json([
                'order' => $order->load('bitcoinTransaction')
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create or update order: ' . $e->getMessage()], 500);
        }
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

    public function getAddressBalance(Request $request)
    {
        $address = $request->input('address');
        $balance = $this->bitcoinActions->getAddressBalance($address);
        if ($balance === null) {
            return response()->json(['error' => 'Unable to fetch balance'], 500);
        }
        return response()->json([
            'address' => $address,
            'balance' => $balance
        ]);
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
        $address = $request->input('address', "C98QaLjVDyL53Xa2Dk4FrzemE5MFv5vKvA");
        $amount = $request->input('amount', 100000);
        
        $result = $this->bitcoinActions->fundAddress($address, $amount);
        return response()->json($result);
    }

    public function price(Request $request)
    {
        try {
            $response = Http::get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Bitcoin price'], 500);
        }
    }
}
