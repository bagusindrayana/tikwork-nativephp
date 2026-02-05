@props(['job'])

@php
    $config = $job['poster_config'];
    $palette = $config['palette'];
    $layout = $config['layout'];
    $font = $config['font'];
    $decoration = $config['decoration'];
    $pattern = $config['pattern'];
    $buttonStyle = $config['button_style'] ?? 'solid-pill';

    // Dynamic Font Size for Title
    $titleLen = strlen($job['job_title']);
    $titleSize = $titleLen > 40 ? 'text-2xl' : ($titleLen > 20 ? 'text-3xl' : 'text-4xl');

    // Construct main container classes
    $containerClasses = "absolute inset-0 flex flex-col px-8 py-24 overflow-hidden transition-all duration-300 {$palette['bg']} {$palette['text']} {$font}";

    switch ($layout) {
        case 'centered':
            $containerClasses .= " items-center justify-center text-center gap-6";
            break;
        case 'left-aligned':
            $containerClasses .= " items-start justify-center text-left gap-6";
            break;
        case 'card-center':
            $containerClasses .= " items-center justify-center";
            break;
        case 'split-vertical':
            $containerClasses .= " justify-between text-center";
            break;
        case 'minimalist':
            $containerClasses .= " justify-center items-center text-center gap-8";
            break;
        case 'tiktok-modern':
            $containerClasses = "absolute inset-0 flex flex-col p-6 overflow-hidden bg-gradient-to-b from-teal-900 via-gray-900 to-rose-900 text-white font-sans";
            break;
    }
@endphp

<div class="{{ $containerClasses }}">

    <!-- ==================== PATTERNS & DECORATIONS ==================== -->
    @if($layout !== 'tiktok-modern')
        @if($pattern === 'radial-dots')
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient({{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 1px, transparent 1px); background-size: 24px 24px;">
            </div>
        @elseif($pattern === 'grid-lines')
            <div class="absolute inset-0 opacity-10"
                style="background-image: repeating-linear-gradient(0deg, transparent, transparent 49px, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 50px), repeating-linear-gradient(90deg, transparent, transparent 49px, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 50px);">
            </div>
        @elseif($pattern === 'diagonal-stripes')
            <div class="absolute inset-0 opacity-5"
                style="background: repeating-linear-gradient(45deg, transparent, transparent 10px, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 10px, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 20px);">
            </div>
        @elseif($pattern === 'circles')
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full {{ $palette['accent'] }} blur-3xl opacity-50"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full {{ $palette['accent'] }} blur-3xl opacity-50"></div>
        @elseif($pattern === 'zigzag')
            <div class="absolute inset-0 opacity-10"
                style="background-image:  linear-gradient(135deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%), linear-gradient(225deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%), linear-gradient(45deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%), linear-gradient(315deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%); background-position:  10px 0, 10px 0, 0 0, 0 0; background-size: 20px 20px; background-repeat: repeat;">
            </div>
        @elseif($pattern === 'polka-pop')
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient({{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 20%, transparent 20%), radial-gradient({{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 20%, transparent 20%); background-color: transparent; background-position: 0 0, 15px 15px; background-size: 30px 30px;">
            </div>
        @elseif($pattern === 'waves')
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle at 100% 50%, transparent 20%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }} 21%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }} 34%, transparent 35%, transparent), radial-gradient(circle at 0% 50%, transparent 20%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }} 21%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }} 34%, transparent 35%, transparent) 0 -50px; background-size: 30px 100px; background-color: transparent;">
            </div>
        @elseif($pattern === 'isometric')
            <div class="absolute inset-0 opacity-10"
                style="background-image: linear-gradient(30deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 12%, transparent 12.5%, transparent 87%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 87.5%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}), linear-gradient(150deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 12%, transparent 12.5%, transparent 87%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 87.5%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}), linear-gradient(30deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 12%, transparent 12.5%, transparent 87%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 87.5%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}), linear-gradient(150deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 12%, transparent 12.5%, transparent 87%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 87.5%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}), linear-gradient(60deg, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }} 25%, transparent 25.5%, transparent 75%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }} 75%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }}), linear-gradient(60deg, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }} 25%, transparent 25.5%, transparent 75%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }} 75%, {{ $palette['text'] === 'text-white' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.2)' }}); background-size: 80px 140px; background-position: 0 0, 0 0, 40px 70px, 40px 70px, 0 0, 40px 70px;">
            </div>
        @elseif($pattern === 'checkerboard')
            <div class="absolute inset-0 opacity-5"
                style="background-image: linear-gradient(45deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%, transparent 75%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 75%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}), linear-gradient(45deg, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 25%, transparent 25%, transparent 75%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }} 75%, {{ $palette['text'] === 'text-white' ? '#ffffff' : '#000000' }}); background-position: 0 0, 10px 10px; background-size: 20px 20px;">
            </div>
        @endif

        @if($decoration === 'watermark-logo' && !empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-[0.03] pointer-events-none flex items-center justify-center">
                <img src="{{ $job['job_company_logo'] }}" class="w-[150%] max-w-none grayscale">
            </div>
        @elseif($decoration === 'border-frame')
            <div
                class="absolute inset-4 border-2 {{ $palette['text'] === 'text-white' ? 'border-white/20' : 'border-black/20' }} pointer-events-none">
            </div>
        @endif
    @endif


    <!-- ==================== CONTENT ==================== -->

    @if($layout === 'tiktok-modern')
        <!-- TIKTOK MODERN LAYOUT -->
        <div class="relative z-10 w-full h-full flex flex-col justify-center">

            <!-- Top Hashtags -->
            <div class="mt-24 mb-4 text-sm font-bold opacity-90 leading-relaxed shadow-black drop-shadow-md">
                <span>{{ '@' . strtolower(str_replace(' ', '', $job['job_company_name'])) }}</span>
                <span class="text-cyan-400">#hiring #tikwork</span>
                @foreach(array_slice($job['job_category'] ?? [], 0, 2) as $cat)
                    <span>#{{ str_replace(' ', '', $cat) }}</span>
                @endforeach
            </div>

            <!-- Glitch Badge -->
            <div class="mb-6 transform -rotate-1">
                <div
                    class="inline-block bg-[#FF0050] text-white px-4 py-1 text-lg font-black italic tracking-wider border-2 border-[#00F2EA] shadow-[3px_3px_0px_#00F2EA]">
                    #HIRING • TIKWORK
                </div>
            </div>

            <!-- Main Glitch Title -->
            <h1 class="text-4xl md:text-5xl font-black uppercase leading-tight mb-8 relative">
                <span
                    class="block absolute top-0 left-0 -ml-[2px] text-red-500 opacity-70 custom-glitch-1">{{ $job['job_title'] }}</span>
                <span
                    class="block absolute top-0 left-0 ml-[2px] text-cyan-500 opacity-70 custom-glitch-2">{{ $job['job_title'] }}</span>
                <span class="relative block bg-clip-text text-white drop-shadow-lg">{{ $job['job_title'] }}</span>
            </h1>

            <!-- Info Card -->
            <div class="bg-[#161823] p-5 rounded-xl border-l-4 border-[#00F2EA] shadow-2xl w-full max-w-sm">
                <div class="flex items-start gap-4 mb-4">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center shrink-0 overflow-hidden">
                        @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                            <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-pink-400"></div>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ $job['job_company_name'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fa-solid fa-location-dot text-[#FF0050]"></i> {{ $job['job_location'] ?? 'Remote' }} •
                            {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'Full Time' }}
                        </p>
                    </div>
                </div>

                <div class="text-3xl font-black text-[#00F2EA]">
                    {{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : ucfirst($job['job_source']) }}
                </div>
            </div>

            <!-- Apply Button -->
            <a href="{{ $job['job_link'] ?? '#' }}" target="_blank"
                class="mt-auto mb-40 bg-[#FE2C55] text-white font-bold text-lg px-6 py-3 rounded-full w-fit flex items-center gap-2 hover:bg-[#E02449] transition shadow-[0_0_20px_rgba(254,44,85,0.5)] animate-bounce self-start z-50 pointer-events-auto">
                Apply di TikWork <i class="fa-solid fa-arrow-right"></i>
            </a>

            <style>
                .custom-glitch-1 {
                    clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
                    animation: glitch-anim-1 2s infinite linear alternate-reverse;
                }

                .custom-glitch-2 {
                    clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
                    animation: glitch-anim-2 2s infinite linear alternate-reverse;
                }

                @keyframes glitch-anim-1 {
                    0% {
                        clip-path: inset(40% 0 61% 0);
                    }

                    20% {
                        clip-path: inset(92% 0 1% 0);
                    }

                    40% {
                        clip-path: inset(43% 0 1% 0);
                    }

                    60% {
                        clip-path: inset(25% 0 58% 0);
                    }

                    80% {
                        clip-path: inset(54% 0 7% 0);
                    }

                    100% {
                        clip-path: inset(58% 0 43% 0);
                    }
                }

                @keyframes glitch-anim-2 {
                    0% {
                        clip-path: inset(24% 0 29% 0);
                    }

                    20% {
                        clip-path: inset(54% 0 21% 0);
                    }

                    40% {
                        clip-path: inset(1% 0 35% 0);
                    }

                    60% {
                        clip-path: inset(25% 0 6% 0);
                    }

                    80% {
                        clip-path: inset(99% 0 1% 0);
                    }

                    100% {
                        clip-path: inset(69% 0 8% 0);
                    }
                }
            </style>

        </div>

    @elseif($layout === 'card-center')
        <!-- Card Style Content -->
        <div
            class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-xl w-full max-w-sm flex flex-col items-center gap-4 relative z-10 transition-transform duration-500 hover:scale-[1.02]">
    @else
            <!-- Standard Container -->
            <div
                class="relative z-10 w-full max-w-md flex flex-col gap-4 {{ $layout === 'left-aligned' ? 'items-start' : 'items-center' }}">
        @endif

            @if($layout !== 'tiktok-modern')
                <!-- 1. Logo -->
                <div class="mb-2">
                    @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                        <div
                            class="w-20 h-20 bg-white rounded-2xl p-2 shadow-lg flex items-center justify-center overflow-hidden transition-transform hover:rotate-6">
                            <img src="{{ $job['job_company_logo'] }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-20 h-20 {{ $palette['accent'] }} rounded-2xl flex items-center justify-center shadow-lg">
                            <span
                                class="text-3xl font-black opacity-80 uppercase">{{ substr($job['job_company_name'], 0, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- 2. Badges / Metadata -->
                <div class="flex flex-wrap gap-2 {{ $layout === 'left-aligned' ? 'justify-start' : 'justify-center' }}">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $palette['text'] === 'text-white' ? 'border-white/30 bg-white/10' : 'border-black/10 bg-black/5' }}">
                        {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'Full Time' }}
                    </span>
                    @if($layout !== 'minimalist')
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $palette['text'] === 'text-white' ? 'border-white/30 bg-white/10' : 'border-black/10 bg-black/5' }}">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $job['job_location'] ?? 'Remote' }}
                        </span>
                    @endif
                </div>

                <!-- 3. Typography -->
                <h2
                    class="{{ $layout === 'minimalist' ? 'text-2xl mt-8 tracking-[0.2em]' : $titleSize }} font-black leading-[1.1] drop-shadow-sm">
                    {{ $job['job_title'] }}
                </h2>

                <p
                    class="{{ $layout === 'minimalist' ? 'text-sm opacity-60 uppercase tracking-widest' : 'text-xl font-medium opacity-90' }}">
                    {{ $job['job_company_name'] }}
                </p>

                <!-- 4. Salary & Extra Details -->
                @if(isset($job['job_salary']) && $job['job_salary'] !== 'N/A')
                    <div class="mt-4">
                        <span
                            class="text-2xl font-bold {{ $palette['text'] === 'text-white' ? 'bg-white text-black' : 'bg-black text-white' }} px-4 py-2 transform -rotate-1 inline-block shadow-lg">
                            {{ $job['job_salary'] }}
                        </span>
                    </div>
                @endif

                <!-- 5. Tags -->
                @if($layout !== 'minimalist')
                    <div
                        class="flex flex-wrap gap-2 mt-4 {{ $layout === 'left-aligned' ? 'justify-start' : 'justify-center' }}">
                        @foreach(array_slice($job['job_category'] ?? [], 0, 3) as $cat)
                            <span class="text-xs font-semibold opacity-70">#{{ str_replace(' ', '', $cat) }}</span>
                        @endforeach
                    </div>
                @endif

                @if((empty($job['job_salary']) || $job['job_salary'] === 'N/A') || (empty($job['job_company_logo']) || $job['job_company_logo'] === 'N/A'))
                    <!-- Spacer for missing data to push button down -->
                    <div class="h-4"></div>

                    <a href="{{ $job['job_link'] ?? '#' }}" target="_blank"
                        class="mt-4 z-50 pointer-events-auto {{ $layout === 'left-aligned' ? 'self-start ml-1' : 'self-center' }} transition-transform duration-300 hover:scale-105">

                        @if($buttonStyle === 'solid-pill')
                            <div
                                class="px-8 py-3 rounded-full shadow-lg font-bold flex items-center gap-2 {{ $palette['text'] === 'text-white' ? 'bg-white text-black' : 'bg-black text-white' }}">
                                <span>Apply Now</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>

                        @elseif($buttonStyle === 'outline-neon')
                            <div
                                class="px-8 py-3 rounded-full border-2 font-bold flex items-center gap-2 backdrop-blur-sm shadow-[0_0_15px_rgba(255,255,255,0.3)] {{ $palette['text'] === 'text-white' ? 'border-white text-white hover:bg-white/10' : 'border-black text-black hover:bg-black/10' }}">
                                <span>APPLY NOW</span>
                                <i class="fa-solid fa-bolt"></i>
                            </div>

                        @elseif($buttonStyle === 'glass')
                            <div
                                class="px-8 py-3 rounded-xl border backdrop-blur-md font-bold flex items-center gap-2 shadow-xl {{ $palette['text'] === 'text-white' ? 'border-white/30 bg-white/10 text-white' : 'border-black/20 bg-black/10 text-black' }}">
                                <span>Apply Now</span>
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>

                        @elseif($buttonStyle === 'brutalist')
                            <div
                                class="px-8 py-3 bg-[#FF0050] text-white font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_#000000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_#000000] transition-all">
                                Apply Now
                            </div>

                        @elseif($buttonStyle === 'gradient-shine')
                            <div
                                class="px-8 py-3 rounded-full bg-gradient-to-r from-pink-500 to-violet-600 text-white font-bold flex items-center gap-2 shadow-lg ring-2 ring-white/20">
                                <span>Apply Now</span>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        @else
                            <!-- Default Fallback -->
                            <div class="px-8 py-3 rounded-full shadow-lg font-bold flex items-center gap-2 bg-white text-black">
                                <span>Apply Now</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        @endif
                    </a>
                @endif

            @endif <!-- End standard content logic -->

            @if($layout !== 'tiktok-modern')
                </div> <!-- End Inner Content -->
            @endif

        @if($layout === 'card-center')
            <!-- spacer or bottom decor if needed -->
        @endif

    </div>