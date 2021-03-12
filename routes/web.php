<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectVersionController;
use App\Http\Controllers\GalleryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::redirect('/', '/projects');
    Route::redirect('/home', '/projects');

    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::get('projects/{project}/destroy', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::post('projects/{project}/versions/{version}', [ProjectVersionController::class, 'store'])->name('projects.versions.store');
    Route::get('projects/{project}/versions/{version}/edit', [ProjectVersionController::class, 'edit'])->name('projects.versions.edit');
    Route::post('projects/{project}/versions/{version}/edit', [ProjectVersionController::class, 'update'])->name('projects.versions.update');
    Route::post('projects/{project}/versions/{version}/destroy', [ProjectVersionController::class, 'destroy'])->name('projects.versions.destroy');
    Route::get('projects/{project}/versions/{version}/download', [ProjectVersionController::class, 'download'])->name('projects.versions.download');
    Route::get('projects/{project}/versions/{version}/actual', [ProjectVersionController::class, 'actual'])->name('projects.versions.actual');

    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('gallery/{image}/destroy', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});
