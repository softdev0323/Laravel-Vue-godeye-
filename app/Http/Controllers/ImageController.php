<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Image;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Image::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ImageIntake');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
           'album_id' => 'required|exists:albums,id',
           'caption' => 'nullable|string',
           'image_url' => 'required|string',
        ]);

        $image = Image::create($request->all());

        return response()->json([
            'message' => 'image created successfully',
            'image' => $image
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Image::find($id);

        if(!$image){
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image);
    }

    public function getByAlbum($albumid)
    {

        $album = Album::with('images')->find($albumid);
        return Inertia::render('Albums', [
            'albums' => $album,
        ]);

    }

    public function getAllByAlbum()
    {

        $album = Album::with('images')->get();
        return Inertia::render('Albums', [
            'album' => $album,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
