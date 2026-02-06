<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobService;
use App\Services\PosterGenerator;
use App\Models\Favorite;

class ExploreController extends Controller
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

    public function explore(Request $request)
    {
        // Reuse fetchExplore logic or just return view and let client fetch
        // To be fast, let's fetch server side
        $search = $request->input('search');
        $jobs = $this->jobService->getExploreJobs($search);

        return view('explore', [
            'jobs' => $jobs,
            'favoriteIds' => $this->getFavoriteIds()
        ]);
    }

    public function fetchExplore(Request $request)
    {
        // Forward search param if exists
        $search = $request->input('search');
        $jobs = $this->jobService->getExploreJobs($search);

        if (!empty($jobs)) {
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
    }
}
