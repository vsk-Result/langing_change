<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectVersion;
use App\UseCases\GalleryService;
use App\UseCases\ProjectVersionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProjectVersionController extends Controller
{
    private $service;
    private $galleryService;

    public function __construct(ProjectVersionService $service, GalleryService $galleryService)
    {
        $this->service = $service;
        $this->galleryService = $galleryService;
    }

    public function store(Project $project, ProjectVersion $version, Request $request)
    {
        $version = $this->service->createVersion($project, $request->html, $request->images);
        return redirect()->route('projects.versions.edit', [$project, $version]);
    }

    public function edit(Project $project, ProjectVersion $version)
    {
        $images = $this->galleryService->getImages();
        return view('projects.versions.edit', compact('project', 'version', 'images'));
    }

    public function update(Project $project, ProjectVersion $version, Request $request): RedirectResponse
    {
        $this->service->updateVersion($version, $request->html, $request->images);
        return redirect()->back();
    }

    public function destroy(Project $project, ProjectVersion $version)
    {
        $version = $this->service->destroyVersion($version);
        return redirect()->route('projects.versions.edit', compact('project', 'version'));
    }

    public function download(Project $project, ProjectVersion $version)
    {
        $this->service->reCreateVersionArchive($version);
        return response()->download($version->getArchiveStoragePath());
    }

    public function actual(Project $project, ProjectVersion $version)
    {
        $project->update(['actual_version_id' => $version->id]);
        return redirect()->back();
    }
}
