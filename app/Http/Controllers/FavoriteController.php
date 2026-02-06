<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Services\PosterGenerator;

class FavoriteController extends Controller
{
    public function index()
    {
        // This is now an API endpoint for the initial state if needed, or we just rely on fetchFavorites
        // But since we are moving to a Shell, this index might not be used for full page load anymore
        // For now, let's keep it but also add fetchFavorites
        return redirect()->route('home');
    }

    public function fetchFavorites()
    {
        $favorites = Favorite::where('user_id', 1)
            ->latest()
            ->get()
            ->map(function ($fav) {
                $job = $fav->job_data;
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                return $job;
            });

        // We can return HTML to append or JSON.
        // For simplicity with the other feeds, let's return HTML of the items
        // But wait, the Favorites View structure is a Grid, slightly different from Feed.
        // Let's return JSON and let Alpine render it OR return the grid HTML.
        // Returning HTML partial is easier for now to keep logic on server.

        $html = '';
        foreach ($favorites as $job) {
            // We need a partial for the favorite item (Grid Item)
            // We can reuse the code from favorites.blade.php but inline it here for now or make a component
            $html .= view('components.favorite-item', ['job' => $job])->render();
        }

        return response()->json(['html' => $html, 'count' => $favorites->count()]);
    }

    public function toggle(Request $request)
    {
        $jobId = $request->input('id');
        $userId = 1; // Default user

        $fav = Favorite::where('user_id', $userId)->where('job_id', strval($jobId))->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['status' => 'removed', 'isFavorite' => false]);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'job_id' => strval($jobId),
                'job_data' => $request->all() // Store all job details sent from frotend
            ]);
            return response()->json(['status' => 'added', 'isFavorite' => true]);
        }
    }
}
