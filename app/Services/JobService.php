<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobService
{
    public function getJobs($page, $categories = null, $search = null)
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

    public function getExploreJobs($search = null)
    {
        try {
            $params = [];
            if ($search) {
                $params['search'] = $search;
            }

            $response = Http::timeout(10)->get('https://tikwork-api.potadev.com/api/jobs/explore', $params);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['data']) ? $data['data'] : $data;
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return [];
    }

    public function getJobById($id)
    {
        try {
            $response = Http::timeout(10)->get("https://tikwork-api.potadev.com/api/jobs/{$id}");

            if ($response->successful()) {
                $specificJob = $response->json();
                // APIs often wrap single resource in 'data', check just in case
                if (isset($specificJob['data'])) {
                    $specificJob = $specificJob['data'];
                }
                return $specificJob;
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return null;
    }
}
