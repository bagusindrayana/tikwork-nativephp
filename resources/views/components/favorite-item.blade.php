@props(['job'])

<div onclick="window.location.href = '/job/' + {{$job['id']}}"
    class="relative aspect-[9/16] bg-gray-900 rounded-sm overflow-hidden group cursor-pointer border border-gray-800 hover:border-gray-600 transition-colors">

    <!-- Poster Embed -->
    <div class="w-full h-full pointer-events-none">
        <div class="w-[200%] h-[200%] transform scale-50 origin-top-left border-0">
            <x-dynamic-poster :job="$job" />
        </div>
    </div>

    <!-- Remove Button (z-index higher than overlay) -->
    <div class="absolute top-2 right-2 z-50 pointer-events-auto">
        <button type="button" onclick="window.removeFavorite({{$job['id']}}); event.stopPropagation();"
            class="text-[#FE2C55] bg-black/40 p-2 rounded-full backdrop-blur-md hover:bg-black/60 transition shadow-sm border border-white/10">
            <i class="fa-solid fa-heart text-sm"></i>
        </button>
    </div>

    <!-- Info Overlay -->
    <div
        class="absolute inset-0 z-20 bg-transparent flex flex-col justify-end p-2 pointer-events-auto hover:bg-black/10 transition">
        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/80 to-transparent pointer-events-none">
        </div>
        <div class="relative z-30 pointer-events-none">
            <h4 class="font-bold text-xs truncate text-white drop-shadow-md">{{ $job['job_title'] ?? 'Title' }}
            </h4>
            <span class="text-[10px] text-gray-300 truncate block">{{ $job['job_company_name'] ?? 'Company' }}</span>
        </div>
    </div>
</div>