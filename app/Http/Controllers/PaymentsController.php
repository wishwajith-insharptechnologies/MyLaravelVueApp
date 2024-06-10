<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentServices;
use Illuminate\Support\Facades\Log;
use App\Repository\PaymentRepository;
use App\Contracts\PaymentRepositoryInterface;
use App\Services\KeyGenerateServices;

class PaymentsController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    //     try {
    //         ob_start('ob_gzhandler');
    //     } catch (\Exception $e) {
    //         //
    //     }
    // }
    //keyGenerateController
    public function getSession(Request $request){
        try {
            $productId = $request->productId;
            $checkoutSession = PaymentServices::createPaymentIntent($productId);

            return response()->json($checkoutSession);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Session Creation Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create Stripe checkout session'], 500);
        }
    }

    public function paymentComplete(Request $request)
    {
        // try {
            $paymentStatus = PaymentServices::completePayment($request);

            return response()->json($paymentStatus);
        // } catch (\Exception $e) {
        //     Log::error('Stripe payment Session Creation get Failed: ' . $e->getMessage());
        //     return response()->json(['error' => 'Failed to Stripe payment Session Creation get'], 500);
        // }
    }

    public function listeningWebhook(){
        Log::info("listening hook call");
        // return "called";
        return PaymentServices::stripeWebhook();
    }

    public function testDecryptKey(Request $request){
        // dd($request);
        $manualsecretKey = "ce0n4w2imGF5MboG";
        $manualKey = "59099488b638aedd8b4f43115441a7f80ac4f715a633c0ac5dfc2ef422625252";
        $encriptKey = $request->key;
        $secretKey = $request->screct_key;

        return KeyGenerateServices::decryptData($encriptKey, $secretKey);

    }
    public function test(){
        return "test done";
    }
    public function testPublicUrl(){
        sleep(2);
        $data = [
            [
                "featureId" => 1,
                "name" => "Customers[API]",
                "description" => "Manage Customer Details",
                "module" => "CM",
                "parentId" => 0,
                "type" => "array",
                "required" => true,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 0
                    ],
                    [
                        "name" => "MaxValue",
                        "value" => 0
                    ]
                ]
            ],
            [
                "featureId" => 2,
                "name" => "Leads",
                "description" => "Manage Leads Details",
                "module" => "LM",
                "parentId" => 0,
                "type" => "int",
                "required" => true,
                "parameters" => []
            ],
            [
                "featureId" => 3,
                "name" => "Leads - Matching Properies",
                "description" => "View properties that match a lead",
                "module" => "LP",
                "parentId" => 0,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 0
                    ],
                    [
                        "name" => "MaxValue",
                        "value" => 0
                    ]
                ]
            ],
            [
                "featureId" => 4,
                "name" => "Properties",
                "description" => "Manage Properties and its details",
                "module" => "PM",
                "parentId" => 0,
                "type" => "int",
                "required" => true,
                "parameters" => []
            ],
            [
                "featureId" => 5,
                "name" => "Properties - Matching Customer",
                "description" => "View Customer/Lead that match a property",
                "module" => "PL",
                "parentId" => 0,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 0
                    ],
                    [
                        "name" => "MaxValue",
                        "value" => null
                    ]
                ]
            ],
            [
                "featureId" => 6,
                "name" => "Team Members",
                "description" => "Manage Team Members(Agents)",
                "module" => "MT",
                "parentId" => 0,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 1
                    ],
                    [
                        "name" => "MsxValue",
                        "value" => null
                    ]
                ]
            ],
            [
                "featureId" => 7,
                "name" => "Dashboard",
                "description" => "View Dashboard",
                "module" => "DV",
                "parentId" => 0,
                "type" => "bool",
                "required" => false,
                "parameters" => []
            ],
            [
                "featureId" => 8,
                "name" => "Facebook Imports",
                "description" => "Add Customers from FB Campaigns",
                "module" => "IFB",
                "parentId" => 0,
                "type" => "bool",
                "required" => false,
                "parameters" => []
            ],
            [
                "featureId" => 9,
                "name" => "Customer Imports",
                "description" => "Add Customers from CSV/Excel",
                "module" => "ICL",
                "parentId" => 1,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MaxValue",
                        "value" => 1000
                    ]
                ]
            ],
            [
                "featureId" => 10,
                "name" => "Spam Check",
                "description" => "Detect Invalid/Disposable Telephone Numbers/Email Address",
                "module" => "SP",
                "parentId" => 0,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 0
                    ],
                    [
                        "name" => "MaxValue",
                        "value" => 100
                    ]
                ]
            ],
            [
                "featureId" => 11,
                "name" => "Seating Capacity",
                "description" => "No of user acccounts",
                "module" => "CA",
                "parentId" => 0,
                "type" => "int",
                "required" => false,
                "parameters" => [
                    [
                        "name" => "MinValue",
                        "value" => 1
                    ],
                    [
                        "name" => "MaxValue",
                        "value" => null
                    ]
                ]
            ],
            [
                "featureId" => 12,
                "name" => "Branding",
                "description" => "Brand Clients, Logo and Name",
                "module" => "BR",
                "parentId" => 0,
                "type" => "bool",
                "required" => false,
                "parameters" => []
            ],
            [
                "featureId" => 13,
                "name" => "Custom Features",
                "description" => "Develop Customized features",
                "module" => "CF",
                "parentId" => 1,
                "type" => "bool",
                "required" => false,
                "parameters" => []
            ],
            [
                "featureId" => 15,
                "name" => "CITA Integration",
                "description" => "ONLILNE Scheduling app integration",
                "module" => "CITA",
                "parentId" => 0,
                "type" => "bool",
                "required" => false,
                "parameters" => []
            ]
        ];

        // Return the data as JSON response
        return response()->json($data);
    }
}
