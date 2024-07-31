<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Illuminate\Http\Request;
use App\Services\PackageService;
use App\Services\LimitationService;
use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Resources\PackageResource;

class PackagesController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');
        // $this->middleware('role:super.admin');

        // try {
        //     ob_start('ob_gzhandler');
        // } catch (\Exception $e) {
            //
        // }
    }

    public function createPackage(CreatePackageRequest $request)
    {
        $validated = $request->validated();

        $package = PackageService::create($request);

        return response()->json([
            'package'  => $package,
        ]);
    }

    public function getPackages(Request $request)
    {
        $packages = PackageService::getPackages();
        $setPackages = PackageResource::collection($packages);

        return response()->json($setPackages);
    }

    public function updatePackage(Request $request, $packageId){
        // return $request->input('title');
        $project = PackageService::update($request, $packageId);

        return response()->json([
            'project'  => $project,
        ]);
    }

    public function getPackage($packageId)
    {
        $packageData = PackageService::getPackage($packageId);
        $package = new PackageResource($packageData);
        return response()->json($package);
    }

    public function deletePackage($packageId)
    {
        $package = PackageService::deletePackage($packageId);
        return response()->json($package);
    }
}
