<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\Project;
use App\UseCases\ProjectVersionService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $versionService;

    public function __construct(ProjectVersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    public function index()
    {
        $projects = Project::getByCurrentUser();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $landings = Landing::all();
        return view('projects.create', compact('landings'));
    }

    public function store(Request $request)
    {
        $project = Project::create([
            'user_id' => auth()->id(),
            'landing_id' => $request->landing_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $version = $this->versionService->createVersion($project);
        $project->update(['actual_version_id' => $version->id]);

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        return redirect()->route('projects.versions.edit', [$project, $project->actualVersion]);
    }
}
