<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repository\PackagesRepository;

class PackageService {


    public static function create($request)
    {
        $defined = 0;
        $packageData = [];

        $packageData['description'] = $request->description;
        $packageData['title'] = $request->title;
        $packageData['rank'] = $request->rank;
        $packageData['validity'] = $request->validity;
        $packageData['price'] = $request->price;
        $packageData['discount'] = $request->discount;
        $packageData['images'] = " image";
        $packageData['status'] = $request->status;
        $packageData['trial_period'] = $request->trial_period;
        $packageData['projects_id'] = $request->product_id;
        $packageData['category_id'] = $request->category_id;

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path() .'/public/images/product', $imageName);
                $packageData['images'] = $imageName;
            } catch (\Exception $e) {
                Log::error('Error uploading image: ' . $e->getMessage());
            }
        }

        $package = PackagesRepository::store($packageData);

        $limitation = LimitationService::create(json_decode($request->limitation) , $defined);

        $projectLimitation = PackagesRepository::createPackageLimitation($package, $limitation);

        return $package;
    }

    public static function update($request, $projectId)
    {
        $packageData = [];

        $packageData['description'] = $request->description;
        $packageData['title'] = $request->title;
        $packageData['rank'] = $request->rank;
        $packageData['validity'] = $request->validity;
        $packageData['price'] = $request->price;
        $packageData['discount'] = $request->discount;
        $packageData['images'] = " image";
        $packageData['status'] = $request->status;
        $packageData['trial_period'] = $request->trial_period;
        $packageData['projects_id'] = $request->product_id;
        $packageData['category_id'] = $request->category_id;

        $package = PackagesRepository::update($packageData ,$projectId);

        $projectLimitationId = $package->limitation->id;

        if($request->limitation){
            $limitation = LimitationService::update($request->limitation, $projectLimitationId);
        }

        return $package;
    }

    public static function getPackage($packageId)
    {
        return PackagesRepository::get($packageId);
    }
    public static function getPackages()
    {
        return PackagesRepository::getAll();
    }

    public static function deletePackage($packageId)
    {
        return PackagesRepository::delete($packageId);
    }
}
