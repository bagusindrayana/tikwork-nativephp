@props(['job', 'index'])

@php
    $gradients = [
        'bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500',
        'bg-gradient-to-tl from-slate-900 via-zinc-800 to-stone-900',
        'bg-gradient-to-tr from-blue-700 via-sky-600 to-cyan-500',
    ];
    $bg = $gradients[$index % count($gradients)];
@endphp

<div
    class="absolute inset-0 flex flex-col p-8 justify-center items-center text-center {{ $bg }} text-white overflow-hidden">
    <!-- Abstract Shapes -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
    </div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
    </div>

    <!-- Content -->
    <div class="z-10 relative flex flex-col items-center max-w-sm">
        <div
            class="w-20 h-20 bg-white shadow-xl rounded-2xl flex items-center justify-center mb-6 p-2 transform rotate-3 hover:rotate-0 transition duration-300">
            @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-contain">
            @else
                <span
                    class="text-3xl font-black text-gray-900">{{ strtoupper(substr($job['job_company_name'], 0, 2)) }}</span>
            @endif
        </div>

        <div
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md text-xs font-bold uppercase tracking-wider mb-4">
            <i class="fa-solid fa-briefcase opacity-70"></i>
            {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'Full Time' }}
        </div>

        <h2 class="text-3xl font-bold mb-3 leading-tight drop-shadow-lg font-sans tracking-tight">
            {{ $job['job_title'] }}
        </h2>

        <p class="text-lg font-medium opacity-90 mb-6 flex items-center gap-2">
            {{ $job['job_company_name'] }}
        </p>

        @if(isset($job['job_salary']) && $job['job_salary'] !== 'N/A')
            <div class="mb-6">
                <span class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg font-bold shadow-lg">
                    {{ $job['job_salary'] }}
                </span>
            </div>
        @endif

        <div class="flex flex-wrap justify-center gap-2">
            @foreach(array_slice($job['job_category'] ?? [], 0, 3) as $cat)
                <span class="px-2.5 py-1 bg-black/20 text-xs rounded-md font-medium border border-white/5">{{ $cat }}</span>
            @endforeach
        </div>
    </div>
</div>