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
    $titleSize = $titleLen > 40 ? 'text-lg' : ($titleLen > 20 ? 'text-xl' : 'text-2xl');

    // Construct main container classes
    $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col px-8 py-12 overflow-hidden transition-all duration-300 {$palette['bg']} {$palette['text']} {$font}";

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
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col px-6 pt-24 pb-6 overflow-hidden bg-gradient-to-b from-teal-900 via-gray-900 to-rose-900 text-white font-sans";
            break;
        case 'modern-split':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col pt-16 overflow-hidden bg-white text-black font-sans";
            break;
        case 'cyber-grid':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col px-8 pt-12 pb-40 overflow-hidden bg-zinc-900 text-green-400 font-mono";
            break;
        case 'bold-typography':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col justify-center px-6 pt-24 overflow-hidden bg-neutral-900 text-white font-sans";
            break;
        case 'neobrutalism':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col p-3 pt-12 overflow-hidden bg-[#FDF5E6] text-black font-sans";
            break;
        case 'retro-synth':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col p-3 pt-12 overflow-hidden bg-slate-900 text-cyan-300 font-mono";
            break;
        case 'glass-modern':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col px-8 pt-12 justify-center items-center overflow-hidden bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white font-sans";
            break;
        case 'meme-design':
            $containerClasses = "absolute top-0 left-0 right-0 bottom-0 flex flex-col p-4 pt-28 overflow-hidden bg-white text-black font-serif";
            break;
    }
@endphp

<div class="{{ $containerClasses }} page-{{ request()->page ?? 0 }}">

    <!-- ==================== PATTERNS & DECORATIONS ==================== -->
    @if(!in_array($layout, ['tiktok-modern', 'modern-split', 'cyber-grid', 'bold-typography', 'neobrutalism', 'retro-synth', 'glass-modern', 'meme-design']))
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
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-[80%] opacity-[0.03] pointer-events-none flex items-center justify-center">
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
        <div class="relative z-10 w-full h-[80%] flex flex-col justify-center">

            <!-- Top Hashtags -->
            <!-- <div class="mt-24 mb-2 text-sm font-bold opacity-90 leading-relaxed shadow-black drop-shadow-md">
                <span>{{ '@' . strtolower(str_replace(' ', '', $job['job_company_name'])) }}</span>
                <span class="text-cyan-400">#hiring #tikwork</span>
                @foreach(array_slice($job['job_category'] ?? [], 0, 2) as $cat)
                    <span>#{{ str_replace(' ', '', $cat) }}</span>
                @endforeach
            </div> -->

            <!-- Glitch Badge -->
            <div class="mt-26 mb-6 transform -rotate-1">
                <div
                    class="inline-block bg-[#FF0050] text-white px-4 py-1 text-lg font-black italic tracking-wider border-2 border-[#00F2EA] shadow-[3px_3px_0px_#00F2EA]">
                    #HIRING • TIKWORK
                </div>
            </div>

            <!-- Main Glitch Title -->
            <h1 class="{{ strlen($job['job_title']) > 30 ? 'text-2xl' : 'text-4xl' }} font-black uppercase leading-tight mb-8 relative">
                <span
                    class="block absolute top-0 left-0 -ml-[2px] text-red-500 opacity-70 custom-glitch-1">{{ $job['job_title'] }}</span>
                <span
                    class="block absolute top-0 left-0 ml-[2px] text-cyan-500 opacity-70 custom-glitch-2">{{ $job['job_title'] }}</span>
                <span class="relative block bg-clip-text text-white drop-shadow-lg">{{ $job['job_title'] }}</span>
            </h1>

            <!-- Info Card -->
            <div class="bg-[#161823] p-5 rounded-xl border-l-4 border-[#00F2EA] shadow-2xl w-full max-w-sm">
                <div class="flex items-start gap-2 mb-2">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center shrink-0 overflow-hidden">
                        @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                            <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-cover">
                        @else
                            <div class="w-full h-[80%] bg-pink-400"></div>
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

                <div class="text-2xl font-black text-[#00F2EA]">
                    {{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : ucfirst($job['job_source']) }}
                </div>
            </div>

            <!-- Apply Button -->
            <a href="{{ $job['job_link'] ?? '#' }}"
                class="apply mt-auto mb-52 bg-[#FE2C55] text-white font-bold text-lg px-6 py-3 rounded-full w-fit flex items-center gap-2 hover:bg-[#E02449] transition shadow-[0_0_20px_rgba(254,44,85,0.5)] animate-bounce self-start z-50 pointer-events-auto">
                Apply Sekarang <i class="fa-solid fa-arrow-right"></i>
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

    @elseif($layout === 'modern-split')
        <!-- MODERN SPLIT LAYOUT -->
        <div class="flex-1 flex flex-col">
            <!-- Top Half: Image/Brand -->
            <div class="h-[45%] bg-black relative flex items-center justify-center p-6 pt-12 overflow-hidden group">
                <div class="absolute inset-0 opacity-40 bg-[url('https://source.unsplash.com/random/800x800/?office,work')] bg-cover bg-center grayscale transition-transform duration-700 group-hover:scale-110"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>

                <div class="relative z-10 w-full">
                    <div class="flex items-center gap-3 mb-4">
                         @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                            <div class="w-16 h-16 bg-white rounded-full p-1">
                                <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-contain rounded-full">
                            </div>
                        @else
                            <div class="text-3xl font-bold text-white tracking-tighter">{{ substr($job['job_company_name'], 0, 2) }}</div>
                        @endif
                        <div>
                             <h3 class="text-white font-bold text-xl leading-none">{{ $job['job_company_name'] }}</h3>
                             <p class="text-gray-400 text-sm">is hiring</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(array_slice($job['job_category'] ?? [], 0, 2) as $cat)
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-sm">#{{ str_replace(' ', '', $cat) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bottom Half: Details -->
            <div class="h-[45%] bg-white p-6 pb-32 flex flex-col justify-between relative">
                <div class="absolute -top-12 left-6 bg-[#FE2C55] text-white px-4 py-2 font-bold text-sm uppercase tracking-wider shadow-lg transform rotate-2">
                    {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'Full Time' }}
                </div>

                <div>
                    <h1 class="text-3xl font-black leading-tight mb-2 text-black line-clamp-3">
                        {{ $job['job_title'] }}
                    </h1>
                    
                </div>

                <div class="flex items-end justify-between mr-10">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase mb-1">Salary</p>
                        <p class="text-2xl font-black text-black">{{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : 'Competitive' }}</p>
                    </div>
                    <a href="{{ $job['job_link'] ?? '#' }}" class="apply w-14 h-14 bg-black text-white rounded-full flex items-center justify-center hover:bg-[#FE2C55] transition-colors duration-300">
                        <i class="fa-solid fa-arrow-right -rotate-45"></i>
                    </a>
                </div>
            </div>
        </div>

    @elseif($layout === 'cyber-grid')
        <!-- CYBER GRID LAYOUT -->
        <div class="relative z-10 w-full h-[80%] flex flex-col justify-between border-2 border-green-500/30 p-4">
             <!-- Corner Decorations -->
            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-green-500"></div>
            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-green-500"></div>
            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-green-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-green-500"></div>

            <!-- <div class="grid grid-cols-4 gap-2 mb-4 opacity-50 text-[10px]">
                <div>SYS.OP.23</div>
                <div class="col-span-2 text-center">/// SECURE CONNECTION ///</div>
                <div class="text-right">VOL.99</div>
            </div> -->
            
            <marquee behavior="scroll" direction="left" class="grid grid-cols-4 gap-2 mb-4 opacity-50 text-[10px]">
                @foreach(array_slice($job['job_category'] ?? [], 0, 4) as $index => $cat)
                    <span class="border border-green-500/50 px-2 py-1 text-green-300">[{{ sprintf('%02d', $index + 1) }}] {{ strtoupper($cat) }}</span>
                @endforeach
            </marquee>

            <div class="flex-1 flex flex-col justify-center">
                 <div class="flex items-center gap-2 mb-4">
                    <div class="w-16 h-16 border border-green-500 flex items-center justify-center bg-green-500/10 backdrop-blur-sm">
                         @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                            <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-contain p-1">
                        @else
                               <span class="text-2xl font-bold font-mono">{{ substr($job['job_company_name'], 0, 2) }}</span>
                        @endif
                    </div>
                     <div>
                        <p class="text-green-400 text-xs tracking-[0.2em] mb-1">> EMPLOYER_DETECTED</p>
                        <h2 class="text-white text-xl font-bold tracking-tight uppercase">{{ $job['job_company_name'] }}</h2>
                    </div>
                </div>

                <div class="border-l-2 border-green-500 pl-6 mb-8 relative">
                     <div class="absolute -left-[5px] top-0 w-2 h-2 bg-green-500"></div>
                    <div class="absolute -left-[5px] bottom-0 w-2 h-2 bg-black border border-green-500"></div>

                    <h1 class="{{ count(explode(" ",$job['job_title'])) > 3 ? 'text-lg' : 'text-2xl' }} font-mono font-bold leading-none mb-4 text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600">
                         {{ $job['job_title'] }}
                    </h1>
                     <!-- <div class="flex flex-wrap gap-2 text-xs font-mono">
                        @foreach(array_slice($job['job_category'] ?? [], 0, 4) as $index => $cat)
                            <span class="border border-green-500/50 px-2 py-1 text-green-300">[{{ sprintf('%02d', $index + 1) }}] {{ strtoupper($cat) }}</span>
                        @endforeach
                    </div> -->
                </div>

                 <div class="bg-black/50 border border-green-500/30 p-4 font-mono text-sm grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-[10px] uppercase">Comp.Scale</p>
                         <p class="text-white">{{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : 'Negotiable' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-[10px] uppercase">Loc.Data</p>
                         <p class="text-white">{{ $job['job_location'] ?? 'Remote' }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ $job['job_link'] ?? '#' }}" class="apply mt-4 py-4 block w-full bg-green-600 text-black font-mono font-bold flex justify-center items-center text-center hover:bg-green-500 transition-colors uppercase tracking-widest relative overflow-hidden group">
                 <span class="relative z-10">Initialize Application >></span>
                 <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500"></div>
            </a>
        </div>

    @elseif($layout === 'bold-typography')
        <!-- BOLD TYPOGRAPHY LAYOUT -->
         <div class="relative z-10 w-full h-[80%] flex flex-col justify-center items-center text-center">

            <div class="mb-4">
                 @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                    <div class="w-24 h-24 mx-auto bg-white rounded-full p-2 mb-6 shadow-2xl">
                        <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-contain rounded-full">
                    </div>
                @else
                       <div class="text-2xl font-bold mb-6 tracking-widest uppercase opacity-70">{{ $job['job_company_name'] }}</div>
                @endif

                 <div class="inline-block border border-white/30 px-4 py-1 rounded-full backdrop-blur-md text-sm font-medium tracking-wide">
                     {{ $job['job_company_name'] }} IS HIRING
                </div>
            </div>

            <h1 class="{{ count(explode(" ",$job['job_title'])) > 4 ? 'text-lg' : 'text-2xl' }} font-black leading-[0.85] tracking-tighter mb-8  w-full" >
                {{ $job['job_title'] }}
            </h1>

            <div class="flex flex-wrap justify-center gap-3 mb-6">
                 @if(isset($job['job_salary']) && $job['job_salary'] !== 'N/A')
                    <span class="bg-white text-black font-bold px-6 py-2 text-xl rounded-full transform -rotate-3 hover:rotate-0 transition-transform">
                        {{ $job['job_salary'] }}
                    </span>
                @endif
                 <span class="bg-transparent border-2 border-white text-white font-bold px-6 py-2 text-xl rounded-full">
                    {{ $job['job_location'] ?? 'Remote' }}
                </span>
            </div>

            <a href="{{ $job['job_link'] ?? '#' }}" class="apply mt-auto mb-40 bg-white text-black font-black text-2xl px-10 py-5 rounded-full hover:scale-105 transition-transform flex items-center gap-4 shadow-xl">
                 APPLY NOW <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>

            <style>
                 .text-outline-white {
                    -webkit-text-stroke: 2px white;
                    color: transparent;
                }
            </style>
        </div>

    @elseif($layout === 'neobrutalism')
        <!-- NEOBRUTALISM LAYOUT -->
        <div class="relative z-10 w-full h-[80%] flex flex-col border-4 border-black bg-[#FDF5E6] p-6 shadow-[8px_8px_0px_#000000]">
             <!-- Top Badge -->
            <div class="self-start bg-black text-white px-4 py-2 font-black text-xl uppercase transform -rotate-2 shadow-[4px_4px_0px_#888] mb-4">
                {{ $job['job_type'] != null && $job['job_type'] != "" ? str_replace("_", " ", $job['job_type']) : 'HIRING' }}
            </div>

            <div class="flex-1 flex flex-col justify-center">
                 <h1 class="{{ count(explode(" ", $job['job_title'])) > 2 ? 'text-2xl' : 'text-3xl' }} md:text-6xl font-black leading-none uppercase mb-3 drop-shadow-md">
                    {{ $job['job_title'] }}
                </h1>

                 <div class="bg-white border-4 border-black p-4 shadow-[6px_6px_0px_#000000] mb-4 transform rotate-1">
                    <div class="font-bold text-lg border-b-4 border-black pb-2 mb-2 uppercase tracking-wide flex justify-between">
                        
                        <span class="bg-[#FF6B6B] border-4 border-black px-2 py-1 font-bold text-white">
                            {{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : 'PAID' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                         @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                             <img src="{{ $job['job_company_logo'] }}" class="w-12 h-12 object-contain border-2 border-black rounded-full">
                         @endif
                        <span class="{{ count(explode(" ", $job['job_company_name'])) > 2 ? 'text-lg' : 'text-2xl' }} font-black">{{ $job['job_company_name'] }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    
                     <span class="bg-[#4ECDC4] border-4 border-black px-3 py-1 font-bold text-black shadow-[3px_3px_0px_#000]">
                        {{ $job['job_location'] ?? 'Remote' }}
                    </span>
                </div>
            </div>

             <a href="{{ $job['job_link'] ?? '#' }}" class="apply bg-black text-white text-center font-black text-2xl py-4 border-4 border-white shadow-[5px_5px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all mt-auto">
                APPLY NOW!!!
            </a>
        </div>

    @elseif($layout === 'retro-synth')
        <!-- RETRO SYNTHWAVE LAYOUT -->
        <div class="absolute inset-0 z-0 bg-cover bg-center opacity-40 pointer-events-none mix-blend-screen" style="background-image: url('https://media.giphy.com/media/l41lFw057lAJQMwg0/giphy.gif');"></div>
        <div class="absolute inset-0 z-0 bg-gradient-to-t from-purple-900/80 to-transparent pointer-events-none"></div>

         <!-- Sun -->
        <div class="absolute bottom-20 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full bg-gradient-to-t from-yellow-400 via-orange-500 to-pink-500 blur-sm pointer-events-none z-0"></div>

         <!-- Grid bottom -->
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-[linear-gradient(to_bottom,transparent_0%,rgba(167,139,250,0.2)_100%),linear-gradient(rgba(139,92,246,0.5)_2px,transparent_2px),linear-gradient(90deg,rgba(139,92,246,0.5)_2px,transparent_2px)] z-0" style="background-size: 100% 100%, 40px 40px, 40px 40px; transform: perspective(500px) rotateX(60deg); transform-origin: bottom;"></div>

        <div class="relative z-10 w-full h-[80%] flex flex-col items-center justify-between py-12 px-6">
             <div class="text-center">
                <h3 class="text-pink-400 font-bold tracking-[0.3em] uppercase mb-2 drop-shadow-[0_0_10px_rgba(236,72,153,0.8)]">{{ $job['job_company_name'] }}</h3>
            </div>

            <div class="text-center relative">
                 <h1 class="{{ count(explode(' ',$job['job_title'])) > 2 ? 'text-lg' : 'text-2xl' }} font-black text-transparent bg-clip-text bg-gradient-to-b from-cyan-300 to-blue-600 drop-shadow-[0_0_20px_rgba(34,211,238,0.6)]" style="-webkit-text-stroke: 1px rgba(255,255,255,0.3);">
                    {{ $job['job_title'] }}
                </h1>
                <div class="text-yellow-300 font-script text-4xl transform -rotate-6 mt-4 drop-shadow-[2px_2px_0px_#B91C1C]">
                     We Are Hiring
                </div>
            </div>

             <div class="grid grid-cols-2 gap-8 w-full max-w-sm">
                <div class="bg-black/50 border border-purple-500/50 p-4 rounded-lg backdrop-blur-sm text-center shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                    <i class="fa-solid fa-money-bill-wave text-cyan-400 text-2xl mb-2"></i>
                    <p class="text-purple-200 font-bold">{{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : '$$$' }}</p>
                </div>
                 <div class="bg-black/50 border border-pink-500/50 p-4 rounded-lg backdrop-blur-sm text-center shadow-[0_0_15px_rgba(236,72,153,0.4)]">
                    <i class="fa-solid fa-location-dot text-pink-400 text-2xl mb-2"></i>
                    <p class="text-purple-200 font-bold">{{ $job['job_location'] ?? 'Remote' }}</p>
                </div>
            </div>

             <a href="{{ $job['job_link'] ?? '#' }}" class="apply mt-8 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-xl px-12 py-4 rounded-full shadow-[0_0_25px_rgba(236,72,153,0.6)] hover:scale-110 transition-transform uppercase tracking-wider relative overflow-hidden group">
                <span class="relative z-10">Start Game</span>
            </a>
        </div>

    @elseif($layout === 'glass-modern')
        <!-- GLASS MODERN LAYOUT -->
        <div class="relative z-10 w-full h-[80%] ">
            <div class="absolute top-[-20%] left-[-20%] w-[60%] h-[60%] rounded-full bg-purple-400 mix-blend-multiply blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-[20%] right-[-20%] w-[60%] h-[60%] rounded-full bg-cyan-400 mix-blend-multiply blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[60%] h-[60%] rounded-full bg-pink-400 mix-blend-multiply blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

            <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-[20px] border border-white/20 rounded-3xl p-8 shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] flex flex-col items-center text-center">
                @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                    <div class="w-24 h-24 mb-6 rounded-2xl bg-white/20 p-2 shadow-inner backdrop-blur-sm">
                        <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-contain drop-shadow-md">
                    </div>
                @else
                    <div class="mb-4 text-white text-opacity-80 font-bold uppercase tracking-widest">{{ $job['job_company_name'] }}</div>
                @endif

                <h1 class="{{ strlen($job['job_title']) > 20 ? (strlen($job['job_title']) > 50 ? 'text-lg' : 'text-xl') : 'text-2xl' }} font-bold text-white mb-2 leading-tight drop-shadow-sm">
                    {{ $job['job_title'] }}
                </h1>

                <!-- <div class="flex gap-2 flex-wrap justify-center mb-8">
                    @foreach(array_slice($job['job_category'] ?? [], 0, 2) as $cat)
                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-white/90 text-xs backdrop-blur-md">{{ $cat }}</span>
                    @endforeach
                </div> -->

                <div class="w-full bg-white/5 rounded-xl p-4 mb-8 border border-white/5 flex justify-between items-center">
                    <div class="text-left">
                        <p class="text-white/50 text-xs uppercase">Location</p>
                        <p class="text-white font-semibold">{{ $job['job_location'] ?? 'Remote' }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div class="text-right">
                        <p class="text-white/50 text-xs uppercase">Offer</p>
                        <p class="text-white font-semibold">{{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : 'Competitive' }}</p>
                    </div>
                </div>

                <a href="{{ $job['job_link'] ?? '#' }}" class="apply w-full bg-white/20 hover:bg-white/30 text-white font-bold py-4 rounded-xl backdrop-blur-md border border-white/10 transition-all shadow-lg flex items-center justify-center gap-2">
                    Apply Now <i class="fa-solid fa-arrow-right opacity-70"></i>
                </a>
            </div>
        </div>

    @elseif($layout === 'meme-design')
        <!-- MEME DESIGN LAYOUT -->
        <div class="relative z-10 w-full h-[80%] bg-white overflow-hidden pointer-events-none">
             <!-- WordArt Style Title -->
            <div class="absolute top-12 left-1/2 -translate-x-1/2 w-full text-center z-20">
                 <h1 class="text-3xl font-comic text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-green-500 to-blue-500 font-bold drop-shadow-[2px_2px_0_rgba(0,0,0,1)] scale-y-150 transform skew-x-12">
                     {{ $job['job_title'] }}
                 </h1>
            </div>

             <!-- Random Images Logic usually handles via backend but here fixed for meme -->

             <!-- Company Name -->
             <div class="absolute top-6 left-4 font-comic text-green-600 text-xl font-bold bg-yellow-200 px-2 rotate-12 border-2 border-red-500 shadow-lg">
                {{ $job['job_company_name'] }}
             </div>

             <!-- Graphic Design Badge -->
            <div class="absolute top-32 left-4 w-32 h-32 bg-gray-200 rounded-lg flex flex-col items-center justify-center p-2 shadow-xl transform rotate-6 border border-gray-400">
                <span class="text-transparent bg-clip-text bg-gradient-to-br from-gray-600 to-gray-900 font-bold text-2xl leading-none">graphic</span>
                <span class="text-red-500 font-serif italic text-lg">design</span>
                <span class="text-sm">is my</span>
                <span class="font-black text-xl bg-black text-white px-1">passion</span>
            </div>

             <!-- Frog or Meme Image (Placeholder) -->
            <!-- <img src="https://i.imgflip.com/4/1hijw2.jpg" class="absolute bottom-20 left-4 w-24 h-24 object-cover rounded-full border-4 border-lime-500 opacity-80" alt="Frog"> -->

             <!-- Comic Sans Details -->
            <div class="absolute top-1/3 right-8 font-comic text-blue-700 text-lg max-w-[150px] leading-tight">
                 wow salary: <br>
                 <span class="bg-red-500 text-white text-2xl font-bold">{{ (isset($job['job_salary']) && $job['job_salary'] !== 'N/A') ? $job['job_salary'] : 'LOTS OF MONEY' }}</span>
            </div>

             <div class="absolute bottom-1/3 left-8 font-comic text-purple-600 text-lg rotate-[-5deg]">
                 much location: <br>
                 <span class="bg-blue-300 text-black px-2">{{ $job['job_location'] ?? 'Everywhere' }}</span>
            </div>

             <a href="{{ $job['job_link'] ?? '#' }}" class="apply absolute bottom-10 left-1/2 -translate-x-1/2 pointer-events-auto bg-gradient-to-r from-pink-500 to-yellow-500 text-white font-comic font-bold text-2xl px-8 py-4 rounded-3xl shadow-[5px_5px_0px_#000] hover:scale-125 transition-transform border-4 border-white animate-pulse">
                CLICK HERE PLS
            </a>
        </div>

    @elseif($layout === 'card-center')
        <!-- Card Style Content -->
        <div
            class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-xl w-full h-[90%] max-w-sm flex flex-col items-center gap-2 relative z-10 transition-transform duration-500 hover:scale-[1.02]">
    @else
        <!-- Standard Container -->
        <div
            class="relative z-10 w-full h-[90%] max-w-md flex flex-col gap-2 {{ $layout === 'left-aligned' ? 'items-start' : 'items-center' }}">
    @endif

            @if(!in_array($layout, ['tiktok-modern', 'modern-split', 'cyber-grid', 'bold-typography', 'neobrutalism', 'retro-synth', 'glass-modern', 'meme-design']))
                <!-- 1. Logo -->
                <div class="mb-2">
                    @if(!empty($job['job_company_logo']) && $job['job_company_logo'] !== 'N/A')
                        <div
                            class="w-20 h-20 bg-white rounded-2xl p-2 shadow-lg flex items-center justify-center overflow-hidden transition-transform hover:rotate-6">
                            <img src="{{ $job['job_company_logo'] }}" class="w-full h-[80%] object-contain">
                        </div>
                    @else
                        <div class="w-20 h-20 {{ $palette['accent'] }} rounded-2xl flex items-center justify-center shadow-lg">
                            <span
                                class="text-2xl font-black opacity-80 uppercase">{{ substr($job['job_company_name'], 0, 2) }}</span>
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
                    <div class="mt-2">
                        <span
                            class="text-2xl font-bold {{ $palette['text'] === 'text-white' ? 'bg-white text-black' : 'bg-black text-white' }} px-4 py-2 transform -rotate-1 inline-block shadow-lg">
                            {{ $job['job_salary'] }}
                        </span>
                    </div>
                @endif

           

                @if((empty($job['job_salary']) || $job['job_salary'] === 'N/A') || (empty($job['job_company_logo']) || $job['job_company_logo'] === 'N/A'))
                    <!-- Spacer for missing data to push button down -->
                    <div class="h-2"></div>

                    <a href="{{ route('open.external', ['url' => $job['job_link'] ?? '#']) }}"
                        class="mt-2 z-50 pointer-events-auto {{ $layout === 'left-aligned' ? 'self-start ml-1' : 'self-center' }} transition-transform duration-300 hover:scale-105">

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

                        @elseif($buttonStyle === 'retro-windows')
                            <!-- Retro Windows 95 Button -->
                             <div class="px-6 py-2 bg-[#c0c0c0] text-black font-bold uppercase border-t-2 border-l-2 border-white border-b-2 border-r-2 border-black text-sm active:border-t-black active:border-l-black active:border-b-white active:border-r-white font-mono">
                                Start Application
                            </div>

                        @elseif($buttonStyle === 'pixel-art')
                            <!-- Pixel Art Button -->
                            <div class="px-6 py-3 bg-indigo-600 text-white font-mono text-xs uppercase tracking-widest border-4 border-black shadow-[4px_4px_0px_#000] hover:translate-y-1 hover:shadow-[2px_2px_0px_#000] transition-all">
                                > APPLY_JOB
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

            @if(!in_array($layout, ['tiktok-modern', 'modern-split', 'cyber-grid', 'bold-typography', 'neobrutalism', 'retro-synth', 'glass-modern', 'meme-design']))
                </div> <!-- End Inner Content -->
            @endif

        @if($layout === 'card-center')
            <!-- spacer or bottom decor if needed -->
        @endif

    </div>