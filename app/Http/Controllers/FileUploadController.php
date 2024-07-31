<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Services\FileUploadService;
use Symfony\Component\HttpFoundation\Response;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        try{

            $response =  FileUploadService::ImageUpload($request);

            return ApiResponse::uploadSuccess($response, 'Image uploaded successfully!');

        }catch(\Exception $e){

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
