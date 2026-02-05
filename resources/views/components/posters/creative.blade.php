@props(['job', 'index'])

<div
    class="absolute top-0 left-0 right-0 bottom-[var(--nav-height)] bg-[#0F172A] text-white overflow-hidden p-6 flex flex-col justify-center items-center">

    <!-- Background Particles -->
    <div class="absolute inset-0 opacity-20"
        style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>

    <!-- Card -->
    <div
        class="w-full max-w-[90%] bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 relative z-10 shadow-2xl flex flex-col items-center text-center">
        <!-- Logo -->
        <div
            class="absolute -top-8 left-1/2 -translate-x-1/2 w-20 h-20 bg-gradient-to-tr from-cyan-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg border-4 border-[#0F172A]">
            @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                <div class="bg-white w-full h-full rounded-xl overflow-hidden p-1">
                    <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-contain">
                </div>
            @else
                <i class="fa-solid fa-layer-group text-3xl text-white"></i>
            @endif
        </div>

        <div class="mt-12 w-full">
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-300 to-blue-300">
                {{ $job['job_company_name'] }}
            </h2>
            <div class="w-12 h-1 bg-blue-500 rounded-full mx-auto my-3"></div>

            <h1 class="text-3xl font-extrabold text-white mb-2 leading-tight">
                {{ $job['job_title'] }}
            </h1>

            <p class="text-sm text-gray-300 mb-6 flex items-center justify-center gap-2">
                <i class="fa-solid fa-map-pin text-red-400"></i> {{ $job['job_location'] ?? 'Remote' }}
            </p>

            <!-- Tags Grid -->
            <div class="grid grid-cols-2 gap-2 w-full mb-6">
                @if(isset($job['job_salary']) && $job['job_salary'] !== 'N/A')
                    <div class="bg-white/5 rounded-lg p-2 border border-white/10 col-span-2">
                        <p class="text-xs text-gray-400 uppercase">Salary</p>
                        <p class="font-bold text-green-400 text-sm">{{ $job['job_salary'] }}</p>
                    </div>
                @endif
                <div class="bg-white/5 rounded-lg p-2 border border-white/10">
                    <p class="text-xs text-gray-400 uppercase">Type</p>
                    <p class="font-bold text-white text-sm">
                        {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'Full Time' }}
                    </p>
                </div>
                <div class="bg-white/5 rounded-lg p-2 border border-white/10">
                    <p class="text-xs text-gray-400 uppercase">Source</p>
                    <p class="font-bold text-white text-sm">{{ ucfirst($job['job_source'] ?? 'Web') }}</p>
                </div>
            </div>

            <p class="text-xs text-center text-gray-400">
                Posted {{ \Carbon\Carbon::parse($job['job_created_date'])->diffForHumans() }}
            </p>
        </div>
    </div>
</div>