<?php

namespace App\Repository;

use App\Models\Limitations;


class LimitationRepository
{

    public static function store($limitationData)
    {
        return Limitations::create($limitationData);
    }

    public static function update($limitationData, $limitaionId)
    {
        $limitaions = Limitations::find($limitaionId);
        return $limitaions->update($limitationData);
    }

}
