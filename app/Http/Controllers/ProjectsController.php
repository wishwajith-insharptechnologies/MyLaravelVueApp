<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
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

            return response()->json([
                'projects' => $projects,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createProject(CreateProjectRequest $request)
    {
        try {
            $project = ProjectService::create($request);
            $limitation = LimitationService::create(json_decode($request->limitation));

            $projectLimitation = ProjectRepository::createProjectLimitation($project, $limitation);

            return response()->json([
                'project' => $project,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message'=> $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProject(Request $request, $projectId)
    {
        try {
            $project = ProjectService::update($request, $projectId);


            return response()->json([
                'project' => $project,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProject($projectId)
    {
        try {
            $project = ProjectService::delete($projectId);

            //$project = $this->projectRepository->delete( $project);
            return response()->json([
                'project' => $project,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProjectList()
    {
        try {
            $projects = ProjectService::getAllProjects();

            return response()->json([
                'projects' => $projects,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProjectById($projectId)
    {
        try {
            $projectObject = ProjectService::getProject($projectId);
            $project = new ProjectResource($projectObject);

            return response()->json([
                'project' => $project,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'error' =>  $e,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
