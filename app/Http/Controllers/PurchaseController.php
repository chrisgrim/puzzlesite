<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Mail\PurchaseConfirmation;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class PurchaseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Shows the purchase page
        return view('Auth.purchase');
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();

        if ($user->has_paid) {
            return response()->json([
                'errors' => [
                    'payment' => ['Payment already made.'],
                ]
            ], 403);
        }

        $token = $request->input('token');
        $coupon = $request->input('coupon', false); // Optional coupon code

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        try {
            $finalAmount = 1000; // Example amount in cents ($10.00)
        if ($coupon) {
            $finalAmount = $finalAmount * 0.9; // Apply a 10% discount
        }

            // Create the charge
            $charge = $stripe->charges->create([
                'amount' => $finalAmount,
                'currency' => 'usd',
                'description' => 'Product Description',
                'source' => $token, // Use the token from the frontend
                'metadata' => ['user_id' => $user->id], // Optional metadata
            ]);

            $order = $user->orders()->create([
                'stripe_charge_id' => $charge->id,
                'amount' => $finalAmount,
                'description' => 'Your Product Description',
                // Any other fields you want to save
            ]);

            $user->update(['has_paid' => true]);

            // Send purchase confirmation email
            Mail::to($user->email)->send(new PurchaseConfirmation($order));

            // Handle post-payment logic, such as updating the order status

            return response()->json(['success' => true, 'message' => 'Payment successful']);

        } catch (ApiErrorException $e) {
            return response()->json([
                'errors' => [
                    'payment' => [$e->getError()->message],
                    // 'payment' => ['Payment processing failed. Please try again.'],
                ]
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'payment' => [$e->getMessage()],
                ]
            ], 500);
        } 
    }

   
}