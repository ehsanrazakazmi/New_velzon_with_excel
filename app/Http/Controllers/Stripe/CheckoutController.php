<?php

namespace App\Http\Controllers\Stripe;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Charge;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function checkout(Product $product)
    {
        return view('stripe/checkout', compact('product'));
    }

    public function makePayment(Request $request, $productId)
    {
        $product = Product::find($productId);

        // Check if the user has a Stripe ID
        if ($request->user()->stripe_id) {
            // Get the default payment method
            $paymentMethod = $request->user()->defaultPaymentMethod();

            // Charge the user using the stored payment method
            $request->user()->charge($product->price * 100, $paymentMethod->id,
             [
                'currency' => $product->currency,
                'description' => $product->name,
             ]
        );

            // You may also want to update the product status, record the payment, etc.
            $product->update(['paid' => true]);

            // Redirect or respond as needed
            return redirect()->back()->with('success', 'Payment successful!');
        } else {
            // Handle the case where the user has no Stripe ID (no card details saved)
            return redirect()->back()->with('error', 'You need to add a payment method.');
        }

    }

}
