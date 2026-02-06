<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobService;
use App\Services\PosterGenerator;
use App\Models\Favorite;
use App\Models\Profile;

class HomeController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    private function getFavoriteIds()
    {
        return Favorite::where('user_id', 1)->pluck('job_id')->map(fn($id) => (string) $id)->toArray();
    }

    public function index(Request $request)
    {
        // Initial load
        $search = $request->input('search');
        $jobs = $this->jobService->getJobs(1, null, $search);

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
        $data = $this->jobService->getJobs($page, $categories, $search);

        $html = '';
        if (isset($data['data'])) {
            foreach ($data['data'] as $index => $job) {
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                // Adjust index for continuous randomization across pages
                $globalIndex = ($page - 1) * 10 + $index;
                $html .= '<div class="snap-center relative w-full post-feed">' . view('components.feed-item', ['job' => $job, 'index' => $globalIndex])->render() . '</div>';
            }
        }

        return response()->json([
            'html' => $html,
            'page' => $data['page'] ?? $page,
            'totalPages' => $data['totalPages'] ?? 1
        ]);
    }

    public function fetchFollowing(Request $request)
    {
        $page = $request->input('page', 1);
        $profile = Profile::where('user_id', 1)->first();
        $categories = $profile && $profile->categories ? implode(',', $profile->categories) : null;
        $search = $request->input('search');

        $data = $this->jobService->getJobs($page, $categories, $search);

        $html = '';
        if (isset($data['data'])) {
            foreach ($data['data'] as $index => $job) {
                $job['poster_config'] = PosterGenerator::generateConfig($job);
                // Unique key suffix to avoid Alpine/Livewire conflicts
                $globalIndex = 'following-' . (($page - 1) * 10 + $index);
                $html .= '<div class="snap-center relative w-full post-feed">' . view('components.feed-item', ['job' => $job, 'index' => $globalIndex])->render() . '</div>';
            }
        }

        return response()->json([
            'html' => $html,
            'page' => $data['page'] ?? $page,
            'totalPages' => $data['totalPages'] ?? 1
        ]);
    }
}
