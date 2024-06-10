<?php

namespace App\Repository;

use App\Models\Limitations;
use App\Models\Projects;


class ProjectRepository
{
    public static function get($per)
    {
        return Projects::with('limitation')->paginate($per);
    }

    public static function find($projectId)
    {
        return Projects::findOrFail($projectId);
    }

    public static function getProjectWithLimitation($projectId)
    {
        return Projects::with('limitation')->findOrFail($projectId);
    }

    public static function store($request)
    {
        return Projects::create($request);
    }

    public function create($request)
    {
        $project = new Projects();

        $project->project_name = $request->project_name;
        $project->project_type = $request->project_type;
        $project->description = $request->description;
        $project->image = '';
        $project->link = $request->link;
        $project->secret_code = $request->secret_code;
        // $project->limitations_id = null;

        $project->save();

        return $project;
    }

    public static function update($projectData, $projectId)
    {
        $project = Projects::findOrFail($projectId);

        $project->update($projectData);

        return $project;
    }

    public static function delete($projectId)
    {
        $project = Projects::findOrFail($projectId);
        return $project->delete();
    }

    public static function createProjectLimitation($project, $limitation)
    {
        return $project->limitation()->save($limitation);
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

    public function getProjectList()
    {
        return Projects::where('status',1)->select('id', 'project_name')->get();
    }

}
