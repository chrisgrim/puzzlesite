<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BitcoinTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class BlockCypherWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook received', $request->all());

        $data = $request->json()->all();

        // Verify that this is a transaction confirmation
        if ($data['event'] !== 'tx-confirmation') {
            return response('Not a transaction confirmation', 200);
        }

        // Check the number of confirmations
        if ($data['confirmations'] < 3) { // Adjust the number of confirmations as needed
            return response('Not enough confirmations yet', 200);
        }

        // Find the relevant transaction in your database
        $transaction = BitcoinTransaction::where('bitcoin_address', $data['addresses'][0])->first();

        if (!$transaction) {
            Log::warning('Received confirmation for unknown transaction', $data);
            return response('Unknown transaction', 200);
        }

        // Update the transaction status
        $transaction->status = 'completed';
        $transaction->save();

        // Update the associated order
        $order = $transaction->order;
        $order->status = 'completed';
        $order->save();

        // Dispatch an event or job if needed
        event(new \App\Events\ProcessBitcoinPayment($order));

        return response('OK', 200);
    }

    public function registerWebhook(Request $request)
    {
        $bitcoinActions = new BitcoinActions();
        
        $url = $request->input('url') ?? url('/api/blockcypher/webhook');
        
        $response = $bitcoinActions->post('hooks', [
            'event' => 'tx-confirmation',
            'url' => $url
        ]);

        Log::info('Webhook registered', $response);
        
        return response()->json($response);
    }
}