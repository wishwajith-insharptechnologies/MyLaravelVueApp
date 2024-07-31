<?php

namespace App\Services;

use App\Repository\LimitationRepository;

class LimitationService
{
    public static function create( $limitationFeatureData, $defined  = 1)
    {
        $limitationData = [];

        $limitationData['defined'] = $defined;
        $limitationData['status'] = 1;
        $limitationData['limitation'] = json_encode($limitationFeatureData);

        $limitation = LimitationRepository::store( $limitationData);
        return $limitation;
    }

    public static function update( $limitationFeatureData, $limitaionId, $defined  = 1)
    {
        $limitationData = [];

        $limitationData['defined'] = $defined;
        $limitationData['status'] = 1;
        $limitationData['limitation'] = json_encode($limitationFeatureData);

        $limitation = LimitationRepository::update( $limitationData, $limitaionId);
        return $limitation;
    }
}
