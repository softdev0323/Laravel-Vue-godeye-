<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        // Load the JSON file
        $jsonFilePath = storage_path('/app/social_media_data.json');
        $mediaData = [];

        if (file_exists($jsonFilePath)) {
            $mediaData = json_decode(file_get_contents($jsonFilePath), true);
        }
        shuffle($mediaData);
        $collection = collect($mediaData);
        $currentPage = $request->input('page', 1);

        $perPage = 20;

        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        foreach ($currentItems as &$item) {
            if ($item['source'] === 'instagram') {
                $item['image_url'] = $this->getFreshInstagramUrl($item['media_id']);
            }
        }

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
        return Inertia::render('Index', [
            'mediaData' => $paginatedData,
        ]);
    }

    /**
     * Fetches a fresh image URL from Instagram API using media ID.
     */
    private function getFreshInstagramUrl($mediaId)
    {
        $accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $url = "https://graph.instagram.com/{$mediaId}?fields=media_url&access_token={$accessToken}";

        $response = Http::get($url)->json();

        return $response['media_url'] ?? null;
    }
}
