<?php

namespace App\Http\Controllers;

use App\Services\KeyGenerateServices;
use Illuminate\Http\Request;

class keyGenerateController extends Controller
{
    public function getPermissionKey(Request $request)
    {
        $permissionKey = KeyGenerateServices::getPermissionKey($request->referenceKey);
        return $permissionKey;
    }

    public function getProductKey(Request $request)
    {
        $packageId = 11;
        $userId = 1;
        return KeyGenerateServices::getFormattedProductKey($packageId, $userId);
    }

    public function keyActiveVerification(Request $request)
    {
        $activated = KeyGenerateServices::activatedProductKey($request->referenceKey, $request->activatedDateTime);

        return response()->json([
            'activated'  => $activated,
        ]);
    }
}
