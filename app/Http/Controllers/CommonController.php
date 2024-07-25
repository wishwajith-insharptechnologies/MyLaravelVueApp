<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Services\CommonService;
use Symfony\Component\HttpFoundation\Response;

class CommonController extends Controller
{
    public function getProjectTypes()
    {
        try{
            $projectTypes = CommonService::getProjectTypes();

            return ApiResponse::success($projectTypes);
        }catch(\Exception $e){

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function getEnvironmentTypes()
    {
        try{
            $projectTypes = CommonService::getEnvironmentTypes();

            return ApiResponse::success($projectTypes);
        }catch(\Exception $e){

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
