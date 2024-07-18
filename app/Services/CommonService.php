<?php

namespace App\Services;

use App\Repository\CommonRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommonService
{

 public static function getProjectTypes()
{
    return CommonRepository::getAllProjectTypes();
}

 public static function getEnvironmentTypes()
{
    return CommonRepository::getAllEnvironmentTypes();
}

}
