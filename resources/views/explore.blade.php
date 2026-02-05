@extends('layouts.app')

@section('content')
    <!-- MOBILE TOP HEADER (Explore) -->
    <div x-data="{
                                                    query: '{{ request('search') }}',
                                                    performSearch() {
                                                        window.location.href = '{{ route('explore') }}' + '?search=' + encodeURIComponent(this.query);
                                                    }
                                                }"
        class="fixed top-0 left-0 w-full pt-8 pb-4 px-4 flex items-center gap-4 z-[100] text-white lg:hidden bg-gradient-to-b from-black via-black/80 to-transparent pointer-events-auto">

        <div class="relative flex-1">
            <input type="text" x-model="query" @keydown.enter="performSearch()" placeholder="Search jobs..."
                class="w-full bg-white/20 backdrop-blur-md rounded-full py-2 pl-10 pr-4 text-sm text-white placeholder-gray-300 border-none outline-none focus:ring-1 focus:ring-white/50">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-300 text-sm"></i>
        </div>

        <button @click="performSearch()" class="text-white">
            <span class="font-semibold text-sm">Search</span>
        </button>
    </div>

    <main class="flex-1 w-full bg-black h-[100dvh] overflow-y-auto no-scrollbar pt-24 lg:pt-4 px-2" x-data="exploreFeed()"
        @scroll.debounce.100ms="checkScroll($el)">

        <div id="explore-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 pb-4">
            @foreach($jobs as $job)
                @php
                    // Add all necessary details for the visual poster generation
                    $queryArray = [
                        'id' => $job['id'] ?? '', // Critical for consistent design seeding
                        'title' => $job['job_title'] ?? 'N/A',
                        'company' => $job['job_company_name'] ?? 'N/A',
                        'logo' => $job['job_company_logo'] ?? '',
                        'location' => $job['job_location'] ?? 'Remote',
                        'salary' => $job['job_salary'] ?? 'N/A',
                        'type' => $job['job_type'] ?? 'Full Time',
                        'category' => implode(',', $job['job_category'] ?? []),
                        // thumbnail, description etc not strictly needed for poster visual
                    ];
                    $queryString = http_build_query($queryArray);

                    // Route for detail view (now fetches from API, so clean URL)
                    $detailUrl = route('job.view', ['id' => $job['id']]);

                    // Route for poster embed (needs params for visual)
                    $posterUrl = route('poster.embed') . '?' . $queryString;
                @endphp

                <div onclick="window.location.href='{{ $detailUrl }}'"
                    class="relative aspect-[9/16] bg-gray-900 rounded-sm overflow-hidden group cursor-pointer border border-gray-800 hover:border-gray-600 transition-colors">

                    <!-- poster Wrapper -->
                    <div class="w-full h-full pointer-events-none">
                        @php
                            $job['poster_config'] = \App\Services\PosterGenerator::generateConfig($job);
                        @endphp

                        <div class="w-[200%] h-[200%] transform scale-50 origin-top-left border-0">
                            <x-dynamic-poster :job=" $job" />
                        </div>

                    </div>

                    <!-- Interactive Overlay -->
                    <div
                        class="absolute inset-0 z-20 bg-transparent flex flex-col justify-end p-2 pointer-events-auto hover:bg-black/10 transition">
                        <div
                            class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/80 to-transparent pointer-events-none">
                        </div>
                        <div class="relative z-30 pointer-events-none">
                            <h4 class="font-bold text-xs truncate text-white drop-shadow-md">{{ $job['job_title'] ?? 'Title' }}
                            </h4>
                            <span
                                class="text-[10px] text-gray-300 truncate block">{{ $job['job_company_name'] ?? 'Company' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Loader -->
        <div x-show="loading" class="w-full h-24 flex items-center justify-center text-white shrink-0 mb-20">
            <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
        </div>

        <div class="h-20 lg:hidden shrink-0"></div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('exploreFeed', () => ({
                loading: false,

                checkScroll(el) {
                    // If close to bottom
                    if (Math.ceil(el.scrollHeight - el.scrollTop) <= el.clientHeight + 200) {
                        this.loadMore();
                    }
                },

                async loadMore() {
                    if (this.loading) return;
                    this.loading = true;

                    try {
                        const urlParams = new URLSearchParams(window.location.search);
                        const search = urlParams.get('search') || '';

                        const res = await fetch(`{{ route("api.jobs.explore") }}?search=${encodeURIComponent(search)}`);
                        const data = await res.json();

                        if (data.html) {
                            const grid = document.getElementById('explore-grid');
                            if (grid) {
                                grid.insertAdjacentHTML('beforeend', data.html);
                            }
                        }
                    } catch (e) {
                        console.error("Failed to load more explore items", e);
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
@endsection