<?php

namespace App\Http\Controllers;

use App\UseCases\GalleryService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    private $service;

    public function __construct(GalleryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $images = $this->service->getImages();
        return view('gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $this->service->uploadImages($request->images);
        return redirect()->back();
    }

    public function destroy($image)
    {
        $this->service->deleteImage($image);
        return redirect()->back();
    }
}
