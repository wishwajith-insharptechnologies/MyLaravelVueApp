<?php

namespace App\Repository;

use App\Models\Packages;
use App\Models\Limitations;
use App\Contracts\PaymentRepositoryInterface;
// use App\Repository\PaymentRepository;


class PackagesRepository
{
    public static function get($id)
    {
        return Packages::with('limitation', 'project')->findOrFail($id);
    }

    public static function getAll($per)
    {
        return Packages::with('limitation')->paginate($per);
    }

    public static function getPackageByID($packageId)
    {
        return Packages::findOrFail($packageId);
    }

    public static function store($request)
    {
        $package = Packages::create($request);

        return $package;
    }

    public static function update($projectData, $peoguctId)
    {
        $package = Packages::findOrFail($peoguctId);

        $package->update($projectData);

        return $package;
    }

    public static function createPackageLimitation($package, $limitation)
    {
        return $package->limitation()->save($limitation);
    }

    public static function delete($packageId)
    {
        return Packages::find($packageId)->delete();
    }

    public function updateProjectLimitation($limitationData, $project )
    {
        if ($limitationData) {

            $limitation = $project->limitation ? $project->limitation : new Limitations();

            $limitation->limitation = $limitationData;

            $project->limitation()->save($limitation);
        }

        return $project->limitation();
    }

}
