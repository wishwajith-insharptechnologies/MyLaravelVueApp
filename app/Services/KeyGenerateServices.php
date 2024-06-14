<?php

namespace App\Services;

use App\Models\Packages;
use Illuminate\Support\Str;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use App\Repository\ProjectRepository;
use Illuminate\Support\Facades\Crypt;
use App\Repository\PackagesRepository;
use App\Repository\ProductKeyRepository;

class KeyGenerateServices
{
    public static function keyGenerate($packageId, $userId ,$oderId = 1)
    {
        $package = PackagesRepository::get($packageId);
        $user = UserRepository::get($userId);
        $project = $package->project;
        $secretKey = $project->secret_code;
        // dd($secretKey);
        $limitations = $package->limitation;
        $isActivated = true;

        $permissionKey = self::generatePermissionKey($package, $project, $user);
        $referenceKey = self::generateReferenceKey(); //generate uuid

        $productKeyData = [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'order_id' => $oderId,
            'reference_key' => $referenceKey,
            'permission_key' => self::encryptData($permissionKey, $secretKey),
            'is_activated' => $isActivated,
        ];

        $registeredProductKey = ProductKeyRepository::store($productKeyData);

        return $registeredProductKey;
    }

    public static function generatePermissionKey($package, $project, $user)
    {
        $featuresData = json_decode($package->limitation->limitation, true);
        // dd($featuresData);
        // dd($featuresData);
        $limitations = [];
        // Iterate through the features data
        foreach ($featuresData as $feature) {
            // Check if the feature has a non-null value
            if ($feature['value'] !== null) {
                $limitations[] = [
                    'fid' => $feature['featureId'],
                    'ena' => ($feature['value']) ? true : false,
                    'val' => is_bool($feature['value']) ? null : $feature['value']
                ];
            }
        }
        $finalArray = [
            'id' => $package->id,
            'pid' => $project->id,
            'status' => $package->status,
            'duration' => $package->validity,
            'limit' => $limitations,
            'used_email' => $user->email,
        ];
        // Convert the final array to JSON format
        $jsonData = json_encode($finalArray);
        return $jsonData;
    }

    public static function encrypt($plainKey)
    {

        $keyMset = '{
            "id": 1234,
            "auth_id": 1,
            "project_id": 6,
            "status": "active",
            "active_date": "2024-03-19",
            "start_date": "2024-03-01",
            "expiration_date": "2025-03-01",
            "limitations": [
                {
                    "featureId": 9,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 13,
                    "IsEnabled": true,
                    "value": null
                },
                {
                    "featureId": 2,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 3,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 4,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 5,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 6,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 7,
                    "IsEnabled": true,
                    "value": null
                },
                {
                    "featureId": 8,
                    "IsEnabled": true,
                    "value": null
                },
                {
                    "featureId": 10,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 11,
                    "IsEnabled": true,
                    "value": 500
                },
                {
                    "featureId": 12,
                    "IsEnabled": true,
                    "value": null
                },
                {
                    "featureId": 15,
                    "IsEnabled": true,
                    "value": null
                }
            ]
        }';
        $key = 'ce0n4w2imGF5MboG';
        $iv = '3f6a4c8bijklmnop';

        // Encrypt the JSON content without padding
        $encrypted = openssl_encrypt($keyMset, 'aes-256-ecb', $key, OPENSSL_RAW_DATA);

        // Return the encrypted content as a Base64 string
        return base64_encode($encrypted);

    }
    public static function encryptData($plainKey, $secret_key)
{
    // Generate initialization vector (IV)
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_length);

    // Encrypt the compressed data using AES-256-CBC with PKCS7 padding
    $encrypted = openssl_encrypt($plainKey, 'aes-256-cbc', $secret_key, OPENSSL_RAW_DATA, $iv);
    $combinedData = base64_encode($iv . $encrypted);
    return $combinedData;
}

public static function generateReferenceKey()
{
    do {
        $key = strtoupper(bin2hex(random_bytes(16)));

        $lastPart = substr($key, -12);

        $containsLetter = preg_match('/[A-Z]/', $lastPart);
        $containsNumber = preg_match('/[0-9]/', $lastPart);
    } while ($containsLetter === 0 || $containsNumber === 0 ||  ProductKeyRepository::isAlreadyExist($key));


    return $key;
}

public static function getPermissionKey($referenceKey)
{
    $permissionKey = ProductKeyRepository::getPermissionKeyReferenceId($referenceKey);

    return $permissionKey;
}

public static function getFormattedProductKey($packageId, $userId ,$oderId = 1 )
{
    $productKeyData = self::keyGenerate($packageId, $userId ,$oderId);
    $formattedReferenceKey = self::formatReferenceKey($productKeyData->reference_key);

    return $formattedReferenceKey;
}

public static function formatReferenceKey($referenceKay)
{
    $chunks = str_split($referenceKay, 4);

    $formattedKey = implode('-', $chunks);

    return $formattedKey;
}

public static function activatedProductKey($referenceKey, $activatedDateTime)
{
    $key = ProductKeyRepository::findKeyByReferenceId($referenceKey);
    $updatedKey = null;
    if ($key) {
        $key->activated_date = $activatedDateTime;
        $key->is_activated = true;

        $updatedKey = ProductKeyRepository::update($key);
    }
    return !(is_null($updatedKey));

}

}
