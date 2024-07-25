<?php

namespace App\Http\Controllers;

use App\Models\Limitations;
use Illuminate\Http\Request;

class LimitationController extends Controller
{
    public function defineProjectLimitation($project, $limitation)
    {
        $limitation = new Limitations;

        $limitation->project_id = $projectId;
        $limitation->package_id = $packageId;
        $limitation->defined = $defined;
        $limitation->status = $status;
        $limitation->limitation = $limitationValue;

        $limitation->save();

    }
}
