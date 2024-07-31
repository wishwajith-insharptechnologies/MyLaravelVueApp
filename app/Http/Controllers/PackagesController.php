<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Services\PackageService;
use App\Services\LimitationService;
use App\Http\Resources\PackageResource;
use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\CreateProjectRequest;
use Symfony\Component\HttpFoundation\Response;

class PackagesController extends Controller
{

    public function createPackage(CreatePackageRequest $request)
    {
        try {
            $validated = $request->validated();

            $package = PackageService::create($request);

            return ApiResponse::success($package, 'package retrieved successfully');
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPackages(Request $request)
    {
        try {
            $packages = PackageService::getPackages();
            $setPackages = PackageResource::collection($packages);

            return ApiResponse::success($setPackages, 'packages retrieved successfully');
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePackage(Request $request, $packageId)
    {
        try {
            $package = PackageService::update($request, $packageId);

            return ApiResponse::success($package, 'package updated successfully');
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPackage($packageId)
    {
        try {
            $packageData = PackageService::getPackage($packageId);
            $package = new PackageResource($packageData);
            return ApiResponse::success($package, 'package retrieved successfully');
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePackage($packageId)
    {
        try {

            $package = PackageService::deletePackage($packageId);
            return ApiResponse::success($package, 'package deleted successfully');
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
