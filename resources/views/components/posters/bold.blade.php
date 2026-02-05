@props(['job', 'index'])

@php
    $gradients = [
        'bg-[#F59E0B]',
        'bg-[#EC4899]',
        'bg-[#3B82F6]',
        'bg-[#10B981]'
    ];
    $bg = $gradients[$index % count($gradients)];
@endphp

<div
    class="absolute top-0 left-0 right-0 bottom-[var(--nav-height)] flex flex-col p-6 justify-between {{ $bg }} text-white overflow-hidden font-mono">
    <!-- Big Watermark -->
    <div class="absolute -right-10 -bottom-10 opacity-10 transform rotate-12">
        @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
            <img src="{{ $job['job_company_logo'] }}" class="w-96 h-96 object-contain grayscale">
        @else
            <i class="fa-solid fa-briefcase text-[400px]"></i>
        @endif
    </div>

    <!-- Top Left -->
    <div
        class="z-10 bg-black text-white px-4 py-2 inline-block self-start transform -rotate-2 font-bold text-xl shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]">
        WE ARE HIRING
    </div>

    <!-- Center Content -->
    <div class="z-10 flex flex-col justify-center h-full relative">
        <h1 class="text-4xl md:text-5xl font-black uppercase leading-none mb-1 shadow-black drop-shadow-md break-words">
            <span
                class="bg-white text-black px-2 decoration-clone box-decoration-clone leading-[1.3]">{{ $job['job_title'] }}</span>
        </h1>

        <div class="mt-6 flex items-center gap-4">
            <div class="w-16 h-16 bg-white border-2 border-black flex items-center justify-center shrink-0">
                @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                    <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-contain p-1">
                @else
                    <span
                        class="text-2xl font-bold text-black">{{ strtoupper(substr($job['job_company_name'], 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <p class="font-bold text-xl text-black">{{ $job['job_company_name'] }}</p>
                <p class="text-sm font-medium text-black/80"><i class="fa-solid fa-location-arrow"></i>
                    {{ $job['job_location'] ?? 'Remote' }}</p>
            </div>
        </div>

        <div class="mt-8">
            <p class="text-3xl font-black italic text-stroke">
                {{ isset($job['job_salary']) && $job['job_salary'] !== 'N/A' ? $job['job_salary'] : 'NEGOTIABLE' }}
            </p>
        </div>
    </div>

    <!-- Bottom -->
    <div class="z-10 border-t-2 border-black pt-4 mt-auto">
        <div class="flex flex-wrap gap-2 text-black font-bold text-sm uppercase">
            @foreach(array_slice($job['job_category'] ?? [], 0, 4) as $cat)
                <span>#{{ str_replace(' ', '', $cat) }}</span>
            @endforeach
        </div>
    </div>
</div>

<style>
    .text-stroke {
        -webkit-text-stroke: 1px black;
        color: transparent;
    }
</style>