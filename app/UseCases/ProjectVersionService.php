<?php

namespace App\UseCases;

use App\Models\Project;
use App\Models\ProjectVersion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProjectVersionService
{
    public function createVersion(Project $project, string $html = null, string $images = null): ProjectVersion
    {
        $version = ProjectVersion::create([
            'project_id' => $project->id,
            'title' => $this->generateVersionTitle(),
        ]);

        $version->update([
            'storage_url' => $this->generateStorageUrl($version),
        ]);

        if (!is_null($html)) {
            $this->updateHTMLContent($version, $html, $images);
        }

        return $version;
    }

    public function updateVersion(ProjectVersion $version, string $html, string $images): ProjectVersion
    {
        $this->updateHTMLContent($version, $html, $images);
        $version->updated_at = Carbon::now();
        $version->update();

        return $version;
    }

    public function destroyVersion(ProjectVersion $version): ProjectVersion
    {
        $project = $version->project;
        if (($version->id === $project->actualVersion->id) && $project->versions->count() > 1) return $version;

        Storage::deleteDirectory($version->storage_url);
        $version->delete();

        return $project->actualVersion;
    }

    public function reCreateVersionArchive(ProjectVersion $version): void
    {
        $this->deleteArchive($version->getArchivePath());

        $zip = new ZipArchive();
        $zip->open($version->getArchiveStoragePath(), ZipArchive::CREATE|ZipArchive::OVERWRITE);
        $this->addFilesInArchive($zip, $version->storage_url);
        $zip->close();
    }

    private function deleteArchive($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    private function addFilesInArchive($zip, $dir): void
    {
        $files = Storage::Allfiles($dir);
        foreach ($files as $file) {
            $localFile = str_replace($dir, '', $file);
            $file = storage_path('app/public/' . $file);
            $zip->addFile($file, $localFile);
        }
    }

    private function generateVersionTitle(): string
    {
        return Carbon::now()->format('YmdHis');
    }

    private function generateStorageUrl(ProjectVersion $version): string
    {
        $versionPath = $this->generateStoragePath($version);
        $this->extractLandingArchive($version, $versionPath);

        return $versionPath;
    }

    private function updateHTMLContent(ProjectVersion $version, string $html, string $images = null): void
    {
        $html = str_replace('contenteditable="true"', '', $html);
        $html = str_replace(env('APP_URL') . '/', '', $html);

        if (!is_null($images) && !empty($images)) {
            $images = explode(';', $images);
            foreach ($images as $image) {
                if (!is_null($image) && !empty($image)) {
                    if (Storage::disk('public_images')->exists($image)) {
                        $content = Storage::disk('public_images')->get($image);
                        Storage::put($version->storage_url . 'images/' . $image, $content);
                    }
                }
            }
        }

        Storage::put($version->storage_url . 'index.html', $html);
    }

    private function extractLandingArchive(ProjectVersion $version, $versionPath): void
    {
        $landingArchive = public_path($version->project->landing->storage_url);
        $zip = new ZipArchive();
        if ($zip->open($landingArchive) === TRUE) {
            $zip->extractTo(storage_path('app/public/' . $versionPath));
            $zip->close();
        }
    }

    private function generateStoragePath(ProjectVersion $version): string
    {
        return "projects/$version->project_id/$version->title/";
    }
}
