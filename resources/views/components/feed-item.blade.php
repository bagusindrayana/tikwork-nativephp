@props(['job', 'index'])

<!-- Video Item Container -->
<div class="w-full h-full snap-center relative flex justify-center bg-black lg:border-b lg:border-gray-800 lg:py-6">

    <!-- ========== MOBILE POSTER VARIATIONS ========== -->
    <div class="w-full relative lg:hidden block text-left">

        <x-dynamic-poster :job="$job" />

        <!-- Right Sidebar Actions (Overlay on top of poster) -->
        <div class="absolute right-2 bottom-14 z-40 flex flex-col items-center gap-5 pointer-events-auto">
            <!-- Avatar -->
            <div class="relative mb-3 group">
                <div class="w-12 h-12 rounded-full border border-white/50 p-0.5 overflow-hidden bg-white shadow-md">
                    @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                        <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-contain rounded-full">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-800 font-bold text-xs">
                            {{ strtoupper(substr($job['job_company_name'], 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div
                    class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 bg-[#FE2C55] rounded-full w-5 h-5 flex items-center justify-center scale-90 cursor-pointer shadow-sm group-hover:scale-110 transition">
                    <i class="fa-solid fa-plus text-white text-xs"></i>
                </div>
            </div>

            <!-- Love Button with Alpine Logic -->
            <div x-data="{
                isFavorite: false,
                jobData: {{ Js::from($job) }},
                init() {
                    // Check global favorites list passed from controller
                    if (window.userFavorites) {
                        this.isFavorite = window.userFavorites.includes(String(this.jobData.id));
                    }
                },
                async toggleFavorite() {
                    // Optimistic UI update
                    this.isFavorite = !this.isFavorite;
                    
                    try {
                        const csrf = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
                        const response = await fetch('/favorite/toggle', {
                            method: 'POST',
                             headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.jobData)
                        });
                        const res = await response.json();
                        // Sync state just in case
                        if (res.isFavorite !== undefined) {
                            this.isFavorite = res.isFavorite;
                        }
                    } catch (e) {
                        console.error('Failed to toggle favorite', e);
                        // Revert on error
                        this.isFavorite = !this.isFavorite;
                    }
                }
            }" class="flex flex-col items-center gap-1 group">
                <button @click="toggleFavorite()"
                    class="bg-black/20 p-2 rounded-full backdrop-blur-sm active:scale-90 transition hover:bg-black/40">
                    <i class="text-[28px] drop-shadow-md transition duration-300"
                        :class="isFavorite ? 'fa-solid fa-heart text-[#FE2C55]' : 'fa-solid fa-heart text-white'"></i>
                </button>
                <span class="text-xs font-semibold text-shadow">Love</span>
            </div>

            <a href="{{ route("open.external", ['url' => $job['job_link'] ?? '#']) }}"
                class="flex flex-col items-center gap-1 group">
                <div
                    class="bg-black/20 p-2 rounded-full backdrop-blur-sm group-active:scale-90 transition hover:bg-black/40">
                    <i class="fa-solid fa-paper-plane text-[28px] text-white drop-shadow-md"></i>
                </div>
                <span class="text-xs font-semibold text-shadow">Apply</span>
            </a>

            <a href="{{ $job['job_link'] ?? '#' }}" class="apply flex flex-col items-center gap-1 group">
                <div
                    class="bg-black/20 p-2 rounded-full backdrop-blur-sm group-active:scale-90 transition hover:bg-black/40">
                    <i class="fa-solid fa-share text-[28px] text-white drop-shadow-md"></i>
                </div>
                <span class="text-xs font-semibold text-shadow">Share</span>
            </a>
        </div>

        <!-- Bottom Info Area -->
        <div
            class="absolute bottom-4 left-0 w-[80%] pl-4 pb-12 z-30 text-white text-shadow text-left pointer-events-none">
            <h3 class="font-bold text-shadow text-lg mb-1 leading-snug drop-shadow-md max-w-[90%] overflow-hidden">
                {{ '@' . strtolower(str_replace(' ', '', $job['job_company_name'])) }}
            </h3>

            <!-- Expandable Description -->
            <div x-data="{ expanded: false }" @click="expanded = !expanded"
                class="w-full cursor-pointer mb-2 transition-all duration-300 pointer-events-auto">
                <p :class="expanded ? 'line-clamp-none bg-black/60' : 'line-clamp-2 bg-black/10'"
                    class="text-sm text-gray-100 transition-all duration-300 font-medium leading-relaxed drop-shadow-md p-2 rounded-lg backdrop-blur-sm hover:bg-black/40 break-all">
                    @if($job['job_description'] != "" && $job['job_description'] != "-")
                        {{ $job['job_description'] }}
                    @else
                        {{ $job['job_title'] }} <br>
                        {{ implode(", ", $job['job_category'] ?? []) }}
                    @endif
                </p>

                @foreach(array_slice($job['job_category'] ?? [], 0, 2) as $cat)
                    <span class="text-xs text-gray-300 mt-2 pl-2 font-medium" x-show="expanded"
                        x-transition>#{{ str_replace(' ', '', $cat) }}</span>
                @endforeach

                <p x-show="expanded" x-transition class="text-xs text-gray-300 mt-2 pl-2 font-medium">
                    Posted {{ \Carbon\Carbon::parse($job['job_created_date'])->diffForHumans() }}
                </p>
            </div>

            <div
                class="flex items-center gap-2 text-sm font-medium opacity-90 marquee-container bg-black/20 px-2 py-1 rounded-full w-fit backdrop-blur-sm">
                <i class="fa-solid fa-music text-xs animate-pulse"></i>
                <div class="whitespace-nowrap overflow-hidden w-40">
                    Original Sound - {{ $job['job_source'] ?? 'Unknown' }}
                </div>
            </div>
        </div>
    </div>

    <!-- ========== DESKTOP LAYOUT ========== -->
    <div class="hidden lg:flex w-[700px] h-full gap-4 relative pr-20 items-center justify-center">
        <div
            class="relative h-[calc(100vh-100px)] aspect-[9/16] rounded-xl overflow-hidden shadow-2xl border border-gray-800 bg-white">
            <!-- Reusing the dynamic poster component -->
            <div class="w-full h-full relative">
                <x-dynamic-poster :job="$job" />
            </div>

            <!-- Desktop Overlay functionality could adhere here -->
            <a href="{{ $job['job_link'] }}" target="_blank"
                class="absolute bottom-8 left-1/2 -translate-x-1/2 px-8 py-3 bg-white text-black font-bold rounded-full hover:scale-105 transition shadow-lg z-20">
                Apply Now
            </a>
        </div>
    </div>

</div>