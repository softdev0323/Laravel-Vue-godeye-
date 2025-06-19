<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $album = Album::all();
        return Inertia::render('Albums', [
            'albums' => $album,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('AlbumIntake');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer',
            'completed' => 'required|boolean'
        ]);

        $album = Album::create($request->all());

        return response()->json([
            'message' => 'Album created successfully',
            'album' => $album

        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $album = Album::find($id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        return response()->json($album);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $album->update($request->all());

        return response()->json([
            'message' => 'Album updated successfully',
            'album' => $album
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response()->json([
            'message' => 'Album deleted successfully'
        ]);
    }
}
