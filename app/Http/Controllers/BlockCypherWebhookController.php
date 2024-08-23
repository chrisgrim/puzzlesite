<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BitcoinTransaction;
use App\Models\Order;
use App\Actions\Bitcoin\BitcoinActions;
use App\Events\BitcoinPaymentConfirmed;
use Illuminate\Support\Facades\Log;

class BlockCypherWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook received', $request->all());
        $data = $request->json()->all();
        
        // Find the relevant transaction in your database
        $transaction = BitcoinTransaction::where('bitcoin_address', $data['addresses'][0])->first();
        if (!$transaction) {
            Log::warning('Received confirmation for unknown transaction', $data);
            return response('Unknown transaction', 200);
        }

        // Update the number of confirmations
        $transaction->confirmations = $data['confirmations'] ?? 0;
        $transaction->save();

        $requiredConfirmations = 2; 
        if ($transaction->confirmations >= $requiredConfirmations) {
            // Check if the amount paid matches the expected amount
            $paidAmount = $data['outputs'][0]['value'] / 100000000; // Convert from satoshis to BTC
            if ($paidAmount >= $transaction->amount) {
                // Update the transaction status
                $transaction->status = 'completed';
                $transaction->save();

                // Update the associated order
                $order = $transaction->order;
                $order->status = 'completed';
                $order->save();

                $user = $order->user;
                $user->has_paid = true;
                $user->save();

                // Delete the webhook
                $this->deleteWebhook($transaction->webhook_id);

            } else {
                Log::warning('Amount mismatch for transaction', $data);
            }
        }

        return response('OK', 200);
    }

    public function registerWebhook(Request $request)
    {
        $bitcoinActions = new BitcoinActions();
        
        $url = 'https://d4d0-38-77-204-66.ngrok-free.app/api/blockcypher/webhook';

        // $url = $request->input('url') ?? url('/api/blockcypher/webhook');
        $address = $request->input('bitcoin_address');
        
        $response = $bitcoinActions->registerWebhook($url, $address);

        // Store webhook information in the related transaction
        if (!empty($response['address'])) {
            BitcoinTransaction::where('bitcoin_address', $response['address'])
                ->update([
                    'webhook_id' => $response['id'],
                    'webhook_url' => $response['url'],
                ]);
        }

        Log::info('Webhook registered', $response);
        
        return response()->json($response);
    }

    public function deleteWebhook($hookId)
    {
        $bitcoinActions = new BitcoinActions();

        try {
            $bitcoinActions->deleteWebhook("{$hookId}");
            Log::info("Webhook {$hookId} deleted successfully.");
        } catch (\Exception $e) {
            Log::error("Failed to delete webhook {$hookId}: " . $e->getMessage());
        }
    }
}