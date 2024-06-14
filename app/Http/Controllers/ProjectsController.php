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
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectsController extends Controller
{
    private $projectRepository;
    private $limitationRepository;

    public function __construct(ProjectRepository $projectRepository, LimitationRepository $limitationRepository)
    {

        // $this->projectRepository = $projectRepository;
        // $this->limitationRepository = $limitationRepository;
        // $this->middleware('auth:sanctum');
        // $this->middleware('role:super.admin');

        // try {
        //     ob_start('ob_gzhandler');
        // } catch (\Exception $e) {
        //     //
        // }
    }

    public function getProjects(Request $request)
    {
        $perPage = $request->input('per', 10);
        $projects = ProjectService::getProjects($perPage);
        $transformedProjects = ProjectResource::collection($projects);
        $projects->data = $transformedProjects;

        return response()->json($projects);
    }

    public function createProject(CreateProjectRequest $request)
    {
        // try {
            $project = ProjectService::create($request);
            $limitation = LimitationService::create(json_decode($request->limitation));

            $projectLimitation = ProjectRepository::createProjectLimitation($project, $limitation);

            return response()->json([
                'project'  => $project,
            ]);
        // } catch (\Exception $e) {
        //     // Log the exception or handle it in some way
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }

    public function updateProject(Request $request, $projectId)
    {
        $project = ProjectService::update($request, $projectId);

        return response()->json([
            'project'  => $project,
        ]);
    }

    public function deleteProject($projectId)
    {
        $project = ProjectService::delete($projectId);

        //$project = $this->projectRepository->delete( $project);
        return response()->json($project);
    }

    public function getProjectList()
    {
        $projects = $this->projectRepository->getProjectList();

        return response()->json([
            'project'  => $projects,
        ]);
    }

    public function getProjectById($projectId)
    {
       $projectObject = ProjectService::getProject($projectId);
       $project = new ProjectResource($projectObject);
        return response()->json([
            'project'  => $project,
        ]);
    }

}
