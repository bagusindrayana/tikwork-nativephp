<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PosterGenerator;
use App\Models\Favorite;
use App\Models\Profile;
use Log;

class JobController extends Controller
{
    private function getFavoriteIds()
    {
        return Favorite::where('user_id', 1)->pluck('job_id')->map(fn($id) => (string) $id)->toArray();
    }

    public function index(Request $request)
    {
        // Initial load
        $search = $request->input('search');
        $jobs = $this->getJobs(1, null, $search);

        // Enrich with poster config
        if (isset($jobs['data'])) {
            foreach ($jobs['data'] as &$job) {
                $job['poster_config'] = PosterGenerator::generateConfig($job);
            }
        }

        return view('welcome', [
            'jobs' => $jobs,
            'favoriteIds' => $this->getFavoriteIds()
        ]);
    }

    public function fetchJobs(Request $request)
    {
        $page = $request->input('page', 1);
        $search = $request->input('search');
        $categories = $request->input('categories');
        $data = $this->getJobs($page, $categories, $search);

        $html = '';
        if (isset($data['data'])) {
            foreach ($data['data'] as $index => $job) {
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                // Adjust index for continuous randomization across pages
                $globalIndex = ($page - 1) * 10 + $index;
                $html .= view('components.feed-item', ['job' => $job, 'index' => $globalIndex])->render();
            }
        }

        return response()->json([
            'html' => $html,
            'page' => $data['page'] ?? $page,
            'totalPages' => $data['totalPages'] ?? 1
        ]);
    }

    private function getJobs($page, $categories = null, $search = null)
    {
        try {
            $params = ['page' => $page];
            if ($categories) {
                $params['categories'] = $categories;
            }
            if ($search) {
                $params['search'] = $search; // Pass search query to API
            }

            // Attempt to fetch from the specified API
            $response = Http::timeout(10)->get('https://tikwork-api.potadev.com/api/jobs/for-you', $params);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
        return [];
    }

    public function fetchFollowing(Request $request)
    {
        $page = $request->input('page', 1);
        $categories = $request->input('categories');
        $search = $request->input('search');

        $data = $this->getJobs($page, $categories, $search);

        $html = '';
        if (isset($data['data'])) {
            foreach ($data['data'] as $index => $job) {
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                // Unique key suffix to avoid Alpine/Livewire conflicts
                $globalIndex = 'following-' . (($page - 1) * 10 + $index);
                $html .= view('components.feed-item', ['job' => $job, 'index' => $globalIndex])->render();
            }
        }

        return response()->json([
            'html' => $html,
            'page' => $data['page'] ?? $page,
            'totalPages' => $data['totalPages'] ?? 1
        ]);
    }

    public function fetchExplore(Request $request)
    {
        try {
            // The API doesn't seem to support pagination in the docs provided, 
            // but assuming standard conventions or just re-fetching randoms for "infinite" feel.
            // If the API is static, we just fetch more of the same. 
            // Let's assume we just want to load *more* items.

            // Forward search param if exists
            $params = [];
            if ($request->has('search')) {
                $params['search'] = $request->input('search');
            }

            $response = Http::timeout(10)->get('https://tikwork-api.potadev.com/api/jobs/explore', $params);

            if ($response->successful()) {
                $data = $response->json();
                $jobs = isset($data['data']) ? $data['data'] : $data;

                // Return HTML for these new items
                $html = '';
                foreach ($jobs as $job) {

                    $detailUrl = route('job.view', ['id' => $job['id'] ?? '']);
                    $job['poster_config'] = PosterGenerator::generateConfig($job);

                    $html .= '<div onclick="window.location.href=\'' . $detailUrl . '\'" 
                        class="relative aspect-[9/16] bg-gray-900 rounded-sm overflow-hidden group cursor-pointer border border-gray-800 hover:border-gray-600 transition-colors">
                    <div class="w-full h-full pointer-events-none">
                        <div class="w-[200%] h-[200%] transform scale-50 origin-top-left border-0">
                            ' . view('components.dynamic-poster', ['job' => $job]) . '
                        </div>
                    </div>
                    <div class="absolute inset-0 z-20 bg-transparent flex flex-col justify-end p-2 pointer-events-auto hover:bg-black/10 transition">
                         <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/80 to-transparent pointer-events-none"></div>
                         <div class="relative z-30 pointer-events-none">
                             <h4 class="font-bold text-xs truncate text-white drop-shadow-md">' . ($job['job_title'] ?? 'Title') . '</h4>
                             <span class="text-[10px] text-gray-300 truncate block">' . ($job['job_company_name'] ?? 'Company') . '</span>
                         </div>
                    </div>
                </div>';
                }

                return response()->json([
                    'html' => $html,
                    'status' => 'success'
                ]);
            }
            return response()->json(['html' => ''], 500);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['html' => ''], 500);
        }
    }
    public function explore(Request $request)
    {
        // Reuse fetchExplore logic or just return view and let client fetch
        // To be fast, let's fetch server side
        $jobs = [];
        try {
            $params = [];
            if ($request->has('search')) {
                $params['search'] = $request->input('search');
            }

            $response = Http::timeout(10)->get('https://tikwork-api.potadev.com/api/jobs/explore', $params);
            if ($response->successful()) {
                $jobs = $response->json();
                if (isset($jobs['data']))
                    $jobs = $jobs['data'];
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return view('explore', [
            'jobs' => $jobs,
            'favoriteIds' => $this->getFavoriteIds()
        ]);
    }

    public function favorites()
    {
        $favorites = Favorite::where('user_id', 1)
            ->latest()
            ->get()
            ->map(function ($fav) {
                $job = $fav->job_data;
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                return $job;
            });

        return view('favorites', ['favorites' => $favorites]);
    }

    public function profile()
    {
        $profile = Profile::firstOrCreate(['user_id' => 1]);
        return view('profile', ['profile' => $profile]);
    }

    public function toggleFavorite(Request $request)
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
                'job_data' => $request->all() // Store all job details sent from frontend
            ]);
            return response()->json(['status' => 'added', 'isFavorite' => true]);
        }
    }

    public function updateProfile(Request $request)
    {
        $profile = Profile::firstOrCreate(['user_id' => 1]);

        $data = $request->only(['name', 'title', 'bio', 'categories']);

        // Handle categories if sent as comma-separated string from a simplified form
        if (isset($data['categories']) && is_string($data['categories'])) {
            $data['categories'] = array_map('trim', explode(',', $data['categories']));
        }

        $profile->update($data);

        return response()->json(['status' => 'success', 'profile' => $profile]);
    }
    public function poster(Request $request)
    {
        // Construct job from request parameters
        // Example: /poster?title=Developer&company=TechCorp&location=Remote
        $job = [
            'id' => $request->input('id', uniqid()),
            'job_title' => $request->input('title', 'Job Title'),
            'job_company_name' => $request->input('company', 'Company Name'),
            'job_company_logo' => $request->input('logo', 'N/A'),
            'job_location' => $request->input('location', 'Remote'),
            'job_type' => $request->input('type', 'Full Time'),
            'job_salary' => $request->input('salary'),
            'job_category' => explode(',', $request->input('category', '')),
            'job_link' => $request->input('link', '#'),
            'job_source' => $request->input('source', 'TikWork'),
            'job_created_date' => now()->toISOString(),
            'job_description' => $request->input('description', 'Join our team...'),
        ];

        $job['poster_config'] = PosterGenerator::generateConfig($job);

        return view('poster-embed', ['job' => $job]);
    }

    public function viewJob($id)
    {
        // Fetch specific job details from API
        $specificJob = null;
        try {
            $response = Http::timeout(10)->get("https://tikwork-api.potadev.com/api/jobs/{$id}");

            if ($response->successful()) {
                $specificJob = $response->json();
                // APIs often wrap single resource in 'data', check just in case
                if (isset($specificJob['data'])) {
                    $specificJob = $specificJob['data'];
                }

                // Ensure we have necessary fields to avoid crashes
                if (!isset($specificJob['job_created_date'])) {
                    $specificJob['job_created_date'] = now()->toISOString();
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        if (!$specificJob) {
            // If fetch fails, we can't show the specific job. 
            // Redirect to home or show 404.
            // For resilience, let's just go to home.
            return redirect()->route('home');
        }

        // Generate config for this specific job
        $specificJob['poster_config'] = PosterGenerator::generateConfig($specificJob);

        // Extract categories for filtering related jobs
        $categoriesString = null;
        if (isset($specificJob['job_category']) && is_array($specificJob['job_category'])) {
            $categoriesString = implode(',', $specificJob['job_category']);
        }

        // Fetch regular feed for "next post" scrolling, filtered by categories
        $feed = $this->getJobs(1, $categoriesString);

        if (isset($feed['data'])) {
            // Generate poster config for all background items
            foreach ($feed['data'] as &$fJob) {
                $fJob['poster_config'] = PosterGenerator::generateConfig($fJob);
            }

            // Filter out the specific job if it exists in the feed to avoid duplicate
            $feed['data'] = array_filter($feed['data'], function ($j) use ($id) {
                return isset($j['id']) && $j['id'] != $id;
            });

            // Prepend the clicked job
            array_unshift($feed['data'], $specificJob);
            // Re-index array
            $feed['data'] = array_values($feed['data']);
        } else {
            $feed = ['data' => [$specificJob], 'page' => 1, 'totalPages' => 1];
        }

        return view('welcome', [
            'jobs' => $feed,
            'currentCategories' => $categoriesString,
            'favoriteIds' => $this->getFavoriteIds()
        ]);
    }
}
