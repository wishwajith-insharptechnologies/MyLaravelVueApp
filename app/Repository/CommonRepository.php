<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;


class CommonRepository
{
    public static function getAllEnvironmentTypes()
    {
        $environmentTypes = DB::table('environment_types')->get();

        return response()->json($environmentTypes);
    }

    public static function getAllProjectTypes()
    {
        $environmentTypes = DB::table('project_types')->get();

        return response()->json($environmentTypes);
    }
}
