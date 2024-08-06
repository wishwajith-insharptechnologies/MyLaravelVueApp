<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\Packages;
use Stripe\StripeClient;
use AWS\CRT\HTTP\Request;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use App\Mail\SendProductKeyMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook as StripeWebhook;
use App\Repository\PackagesRepository;
use Illuminate\Support\Facades\Response;

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
                'metadata' => [
                    'user_id' => 1,
                    'oder_id' => 1,
                    'package_id' => 1,
                ],
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
                    'user_id' => 1,
                ],
            ]);


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


    public static function stripeWebhookListening()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = StripeWebhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return Response::make('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {

            Log::error('Invalid signature: ' . $e->getMessage());
            return Response::make('Invalid signature', 400);
        }

        Log::info('Received Stripe event: ' . $event->type);

        // Handle the event based on its type
        switch ($event->type) {
            case 'charge.succeeded':
                Log::info('Charge succeeded event received.');
                // self::handleChargeSucceeded($event->data->object);
                break;

            case 'checkout.session.completed':
                Log::info('Checkout session completed event received.');
                break;

            default:
                Log::info('Unhandled event type: ' . $event->type);
                return Response::make('Unhandled event type', 200);
        }

        return Response::make('Event processed', 200);
    }
}
