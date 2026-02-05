<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TikWork</title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@400;600;700&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endif
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: black;
            color: white;
        }

        /* Custom Scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.4);
        }

        /* Text Shadow for overlay text */
        .text-shadow {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="overflow-hidden bg-black text-white nativephp-safe-area">

    <!-- App Container -->
    <div class="flex flex-col h-screen w-full relative">

        <!-- ==================== DESKTOP HEADER ==================== -->
        <header x-data="{
            query: '{{ request('search') }}',
            performSearch() {
                let route = '{{ request()->routeIs('explore') ? route('explore') : route('home') }}';
                window.location.href = route + '?search=' + encodeURIComponent(this.query);
            }
        }"
            class="hidden lg:flex items-center justify-between px-4 py-3 border-b border-gray-800 bg-[#121212] z-50 fixed top-0 w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-1 cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-400 to-pink-500 animate-pulse"></div>
                <span class="text-2xl font-bold tracking-tighter text-white">TikWork</span>
            </a>
            <div class="flex-1 max-w-md mx-8 relative">
                <input type="text" placeholder="Search jobs, companies..." x-model="query"
                    @keydown.enter="performSearch()"
                    class="w-full bg-[#2F2F2F] rounded-full py-3 px-4 pl-12 outline-none focus:ring-2 focus:ring-gray-600 transition text-sm text-white placeholder-gray-400">
                <span class="absolute left-4 top-3.5 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <button @click="performSearch()"
                    class="absolute right-3 top-2 text-gray-400 border-l border-gray-600 pl-3 hover:bg-gray-700/50 rounded-r-full p-1">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            <div class="flex items-center gap-4">
                <button
                    class="flex items-center gap-2 border border-gray-600 px-4 py-1.5 rounded-sm hover:bg-[#252525] transition font-semibold text-sm">
                    <i class="fa-solid fa-plus"></i> <span class="hidden xl:inline">Upload</span>
                </button>
                <button
                    class="bg-[#FE2C55] text-white px-6 py-1.5 rounded-sm font-bold hover:bg-[#ef2950] transition text-sm">Log
                    in</button>
                <button class="p-2"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
        </header>

        <!-- ==================== MAIN CONTENT AREA ==================== -->
        <div class="flex flex-1 lg:pt-[60px] pb-[calc(var(--inset-bottom)+1rem)] h-full w-full">

            <!-- ==================== DESKTOP SIDEBAR ==================== -->
            <aside
                class="hidden lg:flex w-[240px] xl:w-[340px] flex-col overflow-y-auto border-r border-gray-800 p-2 custom-scrollbar pb-20">
                <div class="flex flex-col gap-2 py-2 border-b border-gray-800 pb-4">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-3 p-3 rounded-md hover:bg-[#1F1F1F] {{ request()->routeIs('home') ? 'text-[#FE2C55]' : 'text-white' }}">
                        <i class="fa-solid fa-house text-xl w-6 text-center"></i>
                        <span class="font-bold text-lg">For You</span>
                    </a>

                    <!-- We can link following to home with query param just to separate it visually if we want, or keep it inside home -->
                    <!-- But for sidebar nav, usually it points to specific logic. Let's keep loop for now -->

                    <a href="{{ route('explore') }}"
                        class="flex items-center gap-3 p-3 rounded-md hover:bg-[#1F1F1F] {{ request()->routeIs('explore') ? 'text-[#FE2C55]' : 'text-white' }}">
                        <i class="fa-regular fa-compass text-xl w-6 text-center"></i>
                        <span class="font-semibold text-lg">Explore</span>
                    </a>
                </div>

                <div class="py-2 border-b border-gray-800 pb-4">
                    <a href="{{ route('favorites') }}"
                        class="flex items-center gap-3 p-3 rounded-md hover:bg-[#1F1F1F] {{ request()->routeIs('favorites') ? 'text-[#FE2C55]' : 'text-white' }}">
                        <i class="fa-solid fa-heart text-xl w-6 text-center"></i>
                        <span class="font-semibold text-lg">Favorites</span>
                    </a>
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 p-3 rounded-md hover:bg-[#1F1F1F] {{ request()->routeIs('profile') ? 'text-[#FE2C55]' : 'text-white' }}">
                        <i class="fa-solid fa-user text-xl w-6 text-center"></i>
                        <span class="font-semibold text-lg">Profile</span>
                    </a>
                </div>

                <div class="py-4">
                    <p class="text-gray-400 text-sm font-semibold mb-4 px-2">Suggested accounts</p>
                    @for($i = 0; $i < 5; $i++)
                        <a href="#" class="flex items-center gap-3 p-2 rounded-md hover:bg-[#1F1F1F]">
                            <div class="w-8 h-8 rounded-full bg-gray-700 shrink-0 overflow-hidden">
                                <img src="https://i.pravatar.cc/150?u={{ $i }}" alt="User"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="hidden xl:block">
                                <h3 class="font-bold text-sm truncate text-white">user_{{ $i }}</h3>
                                <p class="text-xs text-gray-400 truncate">Generic User {{ $i }}</p>
                            </div>
                        </a>
                    @endfor
                </div>
                <div class="mt-auto py-6 px-2 text-xs text-gray-500 border-t border-gray-800">
                    <p>© 2026 TikWork</p>
                </div>
            </aside>

            <!-- ==================== CONTENT YIELD ==================== -->
            @yield('content')

        </div>

        <!-- ==================== MOBILE BOTTOM NAV ==================== -->
        <nav
            class="lg:hidden fixed bottom-0 w-full bg-black border-t border-gray-800 flex justify-between items-end px-4 pt-2 z-50 text-[10px] text-gray-400 font-medium pl-[var(--inset-left)] pr-[var(--inset-right)] pb-[calc(var(--inset-bottom)+1rem)]">
            <a href="{{ route('home') }}"
                class="flex flex-col items-center gap-1 flex-1 transition {{ request()->routeIs('home') ? 'text-white' : '' }}">
                <i class="fa-solid fa-house text-xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i>
                <span class="{{ request()->routeIs('home') ? 'font-bold' : '' }}">Home</span>
            </a>
            <a href="{{ route('explore') }}"
                class="flex flex-col items-center gap-1 flex-1 transition {{ request()->routeIs('explore') ? 'text-white' : '' }}">
                <i
                    class="fa-regular fa-compass text-xl {{ request()->routeIs('explore') ? 'fa-solid' : 'fa-regular' }}"></i>
                <span class="{{ request()->routeIs('explore') ? 'font-bold' : '' }}">Explore</span>
            </a>
            <div
                class="relative w-12 h-8 cursor-pointer hover:scale-105 transition flex-1 flex justify-center items-center">
                <div class="relative w-11 h-7">
                    <div class="absolute left-0 top-0 w-full h-full bg-cyan-400 rounded-lg translate-x-[-3px]"></div>
                    <div class="absolute right-0 top-0 w-full h-full bg-pink-500 rounded-lg translate-x-[3px]"></div>
                    <div
                        class="absolute top-0 left-0 right-0 h-full bg-white text-black rounded-lg flex items-center justify-center mx-[3px]">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                </div>
            </div>
            <a href="{{ route('favorites') }}"
                class="flex flex-col items-center gap-1 flex-1 transition {{ request()->routeIs('favorites') ? 'text-white' : '' }}">
                <div class="relative">
                    <i
                        class="fa-regular fa-heart text-xl {{ request()->routeIs('favorites') ? 'fa-solid' : 'fa-regular' }}"></i>
                </div>
                <span class="{{ request()->routeIs('favorites') ? 'font-bold' : '' }}">Favorite</span>
            </a>
            <a href="{{ route('profile') }}"
                class="flex flex-col items-center gap-1 flex-1 transition {{ request()->routeIs('profile') ? 'text-white' : '' }}">
                <i
                    class="fa-regular fa-user text-xl {{ request()->routeIs('profile') ? 'fa-solid' : 'fa-regular' }}"></i>
                <span class="{{ request()->routeIs('profile') ? 'font-bold' : '' }}">Profile</span>
            </a>
        </nav>
    </div>

    @yield('scripts')
</body>

</html>