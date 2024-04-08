<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('Auth.purchase');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        try {
            $payment = $user->charge(
                1000, // Amount in cents. $10.00
                $request->paymentMethodId
            );

            // Optionally, save $payment info to your database

            return back()->with('success', 'Payment successful!');
        } catch (IncompletePayment $exception) {
            // Redirect the customer to Stripe's payment page to complete payment steps.
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('some.route.after.payment')]
            );
        } catch (\Exception $exception) {
            // Handle other potential exceptions (e.g., network issues)
            return back()->withErrors('Payment failed. Please try again.');
        }
    }
}
