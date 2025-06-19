<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $journal = Journal::orderBy('created_at', 'desc')->get();

        $collection = collect($journal);
        $currentPage = $request->input('page', 1);

        $perPage = 10;

        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Create a LengthAwarePaginator instance
        $paginatedData = new LengthAwarePaginator(
            $currentItems, // Items for this page
            $collection->count(), // Total items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Preserve URL parameters
        );

        // Pass the JSON data to the Inertia page

//        return response()->json($paginatedData);
        return Inertia::render('Journal', [
            'journals' => $journal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('JournalIntake');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Journal store method called', ['data' => $request->all()]);

        // More detailed logging to debug the text content
        Log::info('Text content details', [
            'text_content' => $request->input('text'),
            'text_type' => gettype($request->input('text')),
            'text_empty' => empty($request->input('text')),
            'text_length' => is_string($request->input('text')) ? strlen($request->input('text')) : 'not a string'
        ]);

        // Handle Quill content which might be a Delta object, JSON string, or HTML
        $textContent = $request->input('text');
        if (is_array($textContent)) {
            // If Quill sends a Delta object, encode it to JSON
            $request->merge(['text' => json_encode($textContent)]);
        } elseif (is_string($textContent) && (
                $textContent === '<p><br></p>' ||
                $textContent === '<p></p>' ||
                trim($textContent) === ''
            )) {
            // Handle "empty" HTML content from Quill
            return response()->json([
                'message' => 'Journal text cannot be empty',
                'errors' => ['text' => ['The journal content field is required.']]
            ], 422);
        }

        // Modified validation with more flexibility for text field
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'cover_image' => 'required|string',
            'text' => 'required' // No string type validation to accept various formats
        ]);

        $journal = Journal::create($validated);
        Log::info('Journal entry created successfully', ['entry' => $journal]);

        return response()->json([
            'message' => 'Journal entry created successfully',
            'entry' => $journal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $journal = Journal::findOrFail($id);

        return Inertia::render('Posts', [
            'post' => $journal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Journal $journal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        $journal->delete();

        return response()->json([
            'message' => 'Journal Entry deleted successfully'
        ]);
    }
}
