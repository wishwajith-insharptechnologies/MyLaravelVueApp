<?php

namespace App\Repository;

use App\Models\Limitations;
use App\Models\Projects;
use Illuminate\Support\Facades\DB;

class ProjectRepository
{
    public static function getAll()
    {
        return Projects::with('limitation')->orderBy('created_at', 'desc')->get();
    }

    public static function getAllByColumnNames($columns)
    {
        return Projects::where('status', 1)->select($columns)->get();
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
        DB::beginTransaction();

        try {
            $project = new Projects();

            $project->project_name = $request->project_name;
            $project->project_type = $request->project_type;
            $project->description = $request->description;
            $project->image = '';
            $project->link = $request->link;
            $project->secret_code = $request->secret_code;
            // $project->limitations_id = null;

            $project->save();

            DB::commit();

            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function update($projectData, $projectId)
    {
        DB::beginTransaction();

        try {
            $project = Projects::findOrFail($projectId);

            $project->update($projectData);

            DB::commit();

            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function delete($projectId)
    {
        DB::beginTransaction();

        try {
            $project = Projects::findOrFail($projectId);
            $result = $project->delete();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function createProjectLimitation($project, $limitation)
    {
        DB::beginTransaction();

        try {
            $result = $project->limitation()->save($limitation);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProjectLimitation($limitationData, $project)
    {
        DB::beginTransaction();

        try {
            if ($limitationData) {
                $limitation = $project->limitation ? $project->limitation : new Limitations();

                $limitation->limitation = $limitationData;

                $project->limitation()->save($limitation);
            }

            DB::commit();

            return $project->limitation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getProjectList()
    {
        return Projects::where('status', 1)->select('id', 'project_name')->get();
    }
}
