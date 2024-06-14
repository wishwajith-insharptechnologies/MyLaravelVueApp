<?php

namespace App\Services;

use App\Mail\SendProductKeyMail;
use Stripe\Stripe;
use App\Models\Packages;
use Stripe\StripeClient;
use AWS\CRT\HTTP\Request;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repository\PackagesRepository;

class PaymentServices
{
    public static function sessionCreate($productId)
    {
        // Retrieve the product from the repository
        $product = PackagesRepository::getPackageByID($productId);

        // Check if the product is valid
        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Get product details
        $productTitle = $product->title ?? "title";
        $productTotalPrice = self::totalProductPrice($product);

        // Check if the total price is valid
        if (!$productTotalPrice || $productTotalPrice <= 0) {
            throw new \Exception('Invalid product price');
        }

        // Initialize Stripe client
        $stripe = new StripeClient(env('VITE_STRIPE_API_SECRET_KEY'));

        // Create a checkout session
        try {
            $checkout = $stripe->checkout->sessions->create([
                'success_url' => 'http://127.0.0.1:8000/success',
                'cancel_url' => 'http://127.0.0.1:8000/cancel',
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $productTotalPrice,
                            'product_data' => [
                                'name' => $productTitle
                            ]
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
            ]);

            // Return the checkout session object
            return $checkout;
        } catch (\Exception $e) {
            // Log and handle the error
            \Log::error('Stripe Checkout Session Creation Failed: ' . $e->getMessage());
            throw new \Exception('Failed to create Stripe checkout session');
        }
    }

    public static function createPaymentIntent($id)
    {
        try {
            $paymentToken = (string) Str::uuid();
            $product = PackagesRepository::getPackageByID($id);

            // Check if the product is valid
            if (!$product) {
                throw new \Exception('Product not found');
            }

            // Get product details
            $productTitle = $product->title ?? "title";
            $productTotalPrice = self::totalProductPrice($product);

            // Check if the total price is valid
            if (!$productTotalPrice || $productTotalPrice <= 0) {
                throw new \Exception('Invalid product price');
            }


            // Set your Stripe API key
            Stripe::setApiKey(env('VITE_STRIPE_API_SECRET_KEY'));

            // Create a PaymentIntent with amount and currency using the Stripe SDK
            $paymentIntent = PaymentIntent::create([
                'amount' => $productTotalPrice,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'payment_token' => $paymentToken,
                    'package_id' => $product->id,
                    'user_id' => Auth::user()->id,
                ],
            ]);

            // Return the client secret in the response
            // return response()->json(['clientSecret' => $paymentIntent->client_secret]);
            return $paymentIntent;
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function completePayment($request)
    {
        $stripe = new StripeClient(env('VITE_STRIPE_API_SECRET_KEY'));

        // Use the payment intent ID stored when initiating payment
        $paymentDetail = $stripe->paymentIntents->retrieve($request->payment_Intent_Id);

        $packageId = $paymentDetail->metadata->package_id;
        $userId = $paymentDetail->metadata->user_id;
        $paymentToken = $paymentDetail->metadata->payment_token;
        // return $paymentDetail->metadata->package_id;
        $key =  KeyGenerateServices::getFormattedProductKey($packageId, $userId);

        Mail::to('recipient@example.com')->send(new SendProductKeyMail($key));

        return $key;

        // if ($paymentDetail->status != 'succeeded') {
        //     // throw error
        // }
    }


    public static function totalProductPrice($product)
    {
        $price = $product->price;
        $discount = $product->discount;

        $price = (float) $price;
        $discount = (float) $discount;

        $discountedPrice = ($price - ($price * ($discount / 100))) * 100;

        $discountedPrice = round($discountedPrice, 2);

        return $discountedPrice;
    }

    public static function stripeWebhook()
    {
        Log::info("listen hook");
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }
        Log::info($event);
        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                log::info('charge succeeded done');
            case 'checkout.session.completed':

                Log::info("checkout session completed done");
                // $session = $event->data->object;

                // $order = Order::where('session_id', $session->id)->first();
                // if ($order && $order->status === 'unpaid') {
                //     $order->status = 'paid';
                //     $order->save();
                //     // Send email to customer
                // }

                // ... handle other event types
            default:
                Log::info("Received unknown event type");
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
