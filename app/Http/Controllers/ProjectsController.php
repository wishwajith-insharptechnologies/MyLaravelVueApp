<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Services\ProjectService;
use App\Services\LimitationService;
use App\Repository\ProjectRepository;
use App\Http\Resources\ProjectResource;
use App\Repository\LimitationRepository;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Symfony\Component\HttpFoundation\Response;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectsController extends Controller
{
    public function getProjects(Request $request)
    {
        try {
            $perPage = $request->input('per', 10);
            $projects = ProjectService::getProjects($perPage);
            $transformedProjects = ProjectResource::collection($projects);
            $projects->data = $transformedProjects;

            return ApiResponse::success($projects, 'Projects retrieved successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createProject(CreateProjectRequest $request)
    {
        try {
            $project = ProjectService::create($request);
            $limitation = LimitationService::create(json_decode($request->limitation));

            $projectLimitation = ProjectRepository::createProjectLimitation($project, $limitation);

            return ApiResponse::created($project, 'Projects created successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function updateProject(Request $request, $projectId)
    {
        try {
            $project = ProjectService::update($request, $projectId);

            return ApiResponse::success($project, 'Projects updated successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function deleteProject($projectId)
    {
        try {
            $project = ProjectService::delete($projectId);

            return ApiResponse::success($project, 'Projects deleted successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function getProjectList()
    {
        try {
            $projects = ProjectService::getAllProjects();

            return ApiResponse::success($projects, 'Projects get successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProjectById($projectId)
    {
        try {
            $projectObject = ProjectService::getProject($projectId);
            $project = new ProjectResource($projectObject);

            return ApiResponse::success($project, 'Projects get successfully');

        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

}
