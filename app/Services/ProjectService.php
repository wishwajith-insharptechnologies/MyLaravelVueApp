<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repository\ProjectRepository;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    public static function getAllProjects()
    {
        return ProjectRepository::getAllByColumnNames(['id', 'project_name']);
    }

    public static function getProjects()
    {
        return ProjectRepository::getAll();
    }

    public static function find($projectId)
    {
        return ProjectRepository::find($projectId);
    }

    public static function getProject($projectId)
    {
        return ProjectRepository::getProjectWithLimitation($projectId);
    }

    public static function create($request)
    {

        $projectData = [
            'project_name' => $request->projectName,
            'project_type' => $request->projectType,
            'is_web_base' => $request->environmentType,
            'description'  => $request->description,
            'link'         => $request->link,
            'image'         => $request->image,
            'secret_code'  => $request->secretCode,
        ];

        return ProjectRepository::store($projectData);
    }

    public static function update($request, $projectId)
    {
        $projectData = [];

        $projectData['project_name'] = $request->projectName;
        $projectData['project_type'] = $request->projectType;
        $projectData['description'] = $request->description;
        $projectData['is_web_base'] = $request->environmentType;
        $projectData['image'] =  $request->image;
        $projectData['link'] = $request->link;
        $projectData['secret_code'] = $request->secretCode;

        $project = ProjectRepository::update($projectData ,$projectId);

        $projectLimitationId = $project->limitation->id;

        if($request->limitation){
            $limitation = LimitationService::update(json_decode($request->limitation), $projectLimitationId);
        }

        return $project;
    }

    public static function delete($projectId)
    {
        return ProjectRepository::delete($projectId);
    }

}
