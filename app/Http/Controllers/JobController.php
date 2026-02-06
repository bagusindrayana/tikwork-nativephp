<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobService;
use App\Services\PosterGenerator;
use App\Models\Favorite;
use Native\Mobile\Facades\Share;
use Native\Mobile\Facades\Browser;

class JobController extends Controller
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

    public function viewJob($id)
    {
        // Fetch specific job details from API
        $specificJob = $this->jobService->getJobById($id);

        if (!$specificJob) {
            // If fetch fails, we can't show the specific job. 
            // For resilience, let's just go to home.
            return redirect()->route('home');
        }

        // Ensure we have necessary fields to avoid crashes
        if (!isset($specificJob['job_created_date'])) {
            $specificJob['job_created_date'] = now()->toISOString();
        }

        // Generate config for this specific job
        $specificJob['poster_config'] = PosterGenerator::generateConfig($specificJob);

        // Extract categories for filtering related jobs
        $categoriesString = null;
        if (isset($specificJob['job_category']) && is_array($specificJob['job_category'])) {
            $categoriesString = implode(',', $specificJob['job_category']);
        }

        // Fetch regular feed for "next post" scrolling, filtered by categories
        $feed = $this->jobService->getJobs(1, $categoriesString);

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

    public function openExternal(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);
        $url = $request->query('url');
        try {
            Browser::open($url);
        } catch (\Throwable $th) {
            Share::url('Apply Job!', 'Apply Job', $url);
        }
        return redirect()->route('home');
    }
}
