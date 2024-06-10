<?php

namespace App\Repository;

use App\Models\ProductKey;


class ProductKeyRepository
{

    public static function findKeyByReferenceId($referenceKey)
    {
        return ProductKey::where('reference_key', $referenceKey)->first();
    }
    public static function store($productKeyData)
    {
        return ProductKey::create($productKeyData);
    }


    public static function isAlreadyExist($key)
    {
        return ProductKey::where('reference_key', $key)->exists();
    }

    public static function getPermissionKeyReferenceId(String $referenceKey)
    {
        return ProductKey::where('reference_key', $referenceKey)->first()->permission_key;
    }

    public static function update(ProductKey $productKey)
    {
        return $productKey->save();
    }
}
