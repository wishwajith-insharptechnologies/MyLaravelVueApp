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

public static function generateStrongPassword($length = 12) {
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';

    $allChars = $lowercase . $uppercase . $numbers;

    $password = $lowercase[random_int(0, strlen($lowercase) - 1)];
    $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];

    for ($i = 3; $i < $length; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }

    return str_shuffle($password);
}

}
