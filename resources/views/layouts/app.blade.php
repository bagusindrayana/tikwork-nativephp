<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
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
        :root {
            --nav-height: 60px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: black;
            color: white;
            overscroll-behavior-y: none;
            /* Prevent pull-to-refresh on body */
        }

        /* Disable text selection for app-like feel */
        .select-none {
            user-select: none;
            -webkit-user-select: none;
        }

        [x-cloak] {
            display: none !important;
        }

        .post-feed {
            height: calc(100dvh - var(--nav-height));
        }
    </style>
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="overflow-hidden bg-black text-white nativephp-safe-area select-none" x-data="appShell()">

    <!-- App Container -->
    <div class="flex flex-col h-[100dvh] w-full relative">

        <!-- ==================== DESKTOP HEADER (Kept for desktop view compatibility) ==================== -->
        <header
            class="hidden lg:flex items-center justify-between px-4 py-3 border-b border-gray-800 bg-[#121212] z-50 fixed top-0 w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-1 cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-400 to-pink-500 animate-pulse"></div>
                <span class="text-2xl font-bold tracking-tighter text-white">TikWork</span>
            </a>
            <!-- Simplified Desktop Header -->
            <div class="flex items-center gap-4">
                <button class="bg-[#FE2C55] text-white px-6 py-1.5 rounded-sm font-bold text-sm">Log in</button>
            </div>
        </header>

        <!-- ==================== MAIN SHELL (Horizontal Swipe) ==================== -->
        <!-- Justify-start to ensure items stack left-to-right -->
        <main id="shell-container"
            class="flex flex-1 w-full h-full overflow-x-auto snap-x snap-mandatory overflow-y-hidden no-scrollbar lg:pt-[60px]"
            @scroll.debounce.50ms="onScroll($el)">

            <!-- SECTION 1: HOME -->
            <section id="section-home" class="w-full h-full shrink-0 snap-center overflow-hidden relative">
                @include('partials.home')
            </section>

            <!-- SECTION 2: EXPLORE -->
            <section id="section-explore" class="w-full h-full shrink-0 snap-center overflow-hidden relative">
                @include('partials.explore')
            </section>

            <!-- SECTION 3: FAVORITES -->
            <section id="section-favorites" class="w-full h-full shrink-0 snap-center overflow-hidden relative">
                @include('partials.favorites')
            </section>

            <!-- SECTION 4: PROFILE -->
            <section id="section-profile" class="w-full h-full shrink-0 snap-center overflow-hidden relative">
                @include('partials.profile')
            </section>

        </main>

        <!-- ==================== MOBILE BOTTOM NAV ==================== -->
        <!-- Note: We check activeTab === 'name' to style -->
        <nav id="navbar"
            class="lg:hidden fixed bottom-0 w-full bg-black border-t border-gray-800 flex justify-between items-end px-4 pt-2 z-50 text-[10px] text-gray-400 font-medium pl-[var(--inset-left)] pr-[var(--inset-right)] pb-[calc(var(--inset-bottom)+1rem)]">

            <button @click="scrollToTab('home')" class="flex flex-col items-center gap-1 flex-1 transition"
                :class="activeTab === 'home' ? 'text-white' : ''">
                <i class="fa-solid fa-house text-xl" :class="activeTab === 'home' ? 'text-white' : ''"></i>
                <span :class="activeTab === 'home' ? 'font-bold' : ''">Home</span>
            </button>

            <button @click="scrollToTab('explore')" class="flex flex-col items-center gap-1 flex-1 transition"
                :class="activeTab === 'explore' ? 'text-white' : ''">
                <i class="text-xl"
                    :class="activeTab === 'explore' ? 'fa-solid fa-compass' : 'fa-regular fa-compass'"></i>
                <span :class="activeTab === 'explore' ? 'font-bold' : ''">Explore</span>
            </button>

            <!-- Create Button (Placeholder) -->
            <button @click="alert('Coming soon!')"
                class="relative w-12 h-8 cursor-pointer hover:scale-105 transition flex-1 flex justify-center items-center">
                <div class="relative w-11 h-7">
                    <div class="absolute left-0 top-0 w-full h-full bg-cyan-400 rounded-lg translate-x-[-3px]"></div>
                    <div class="absolute right-0 top-0 w-full h-full bg-pink-500 rounded-lg translate-x-[3px]"></div>
                    <div
                        class="absolute top-0 left-0 right-0 h-full bg-white text-black rounded-lg flex items-center justify-center mx-[3px]">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                </div>
            </button>

            <button @click="scrollToTab('favorites')" class="flex flex-col items-center gap-1 flex-1 transition"
                :class="activeTab === 'favorites' ? 'text-white' : ''">
                <div class="relative">
                    <i class="text-xl"
                        :class="activeTab === 'favorites' ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
                </div>
                <span :class="activeTab === 'favorites' ? 'font-bold' : ''">Favorite</span>
            </button>

            <button @click="scrollToTab('profile')" class="flex flex-col items-center gap-1 flex-1 transition"
                :class="activeTab === 'profile' ? 'text-white' : ''">
                <i class="text-xl" :class="activeTab === 'profile' ? 'fa-solid fa-user' : 'fa-regular fa-user'"></i>
                <span :class="activeTab === 'profile' ? 'font-bold' : ''">Profile</span>
            </button>
        </nav>
    </div>

    <!-- Global Scripts -->
    <script>
        // Set nav height variable
        document.addEventListener('DOMContentLoaded', function () {
            const nav = document.getElementById("navbar");
            if (nav) {
                document.documentElement.style.setProperty('--nav-height', nav.offsetHeight + 'px');
            }
        });

        document.addEventListener('alpine:init', () => {
            // Define User Favorites global if not set
            if (!window.userFavorites) window.userFavorites = [];

            Alpine.data('appShell', () => ({
                activeTab: 'home',
                tabs: ['home', 'explore', 'favorites', 'profile'],

                init() {
                    // Check URL to set initial tab
                    const path = window.location.pathname;
                    if (path.includes('explore')) this.activeTab = 'explore';
                    else if (path.includes('favorites')) this.activeTab = 'favorites';
                    else if (path.includes('profile')) this.activeTab = 'profile';
                    else this.activeTab = 'home';

                    // Scroll to initial tab
                    this.$nextTick(() => {
                        this.scrollToTab(this.activeTab, 'auto');
                    });

                    window.addEventListener('navigate-profile', () => {
                        this.scrollToTab('profile');
                    });
                },

                scrollToTab(tab, behavior = 'smooth') {
                    this.activeTab = tab;
                    const el = document.getElementById('section-' + tab);
                    if (el) {
                        el.scrollIntoView({ behavior: behavior, inline: 'start' });
                    }
                    this.updateUrl(tab);
                },

                onScroll(el) {
                    const scrollLeft = el.scrollLeft;
                    const width = el.clientWidth;
                    const index = Math.round(scrollLeft / width);
                    const newTab = this.tabs[index];

                    if (newTab && newTab !== this.activeTab) {
                        this.activeTab = newTab;
                        this.updateUrl(newTab);

                        // Trigger lazy loads if needed via events
                        // window.dispatchEvent(new CustomEvent('tab-changed', { detail: newTab }));
                    }
                },

                updateUrl(tab) {
                    const url = tab === 'home' ? '/' : '/' + tab;
                    // Only push state if meaningful change and not already there
                    if (window.location.pathname !== url) {
                        // Use replaceState to avoid cluttering history with scroll events
                        // actually pushState is better for back button navigation between tabs
                        // But for swipe interfaces, usually history is distinct.
                        // Let's use replaceState for now to act like a true App, 
                        // or pushState if we want browser back to work.
                        // "Smart" approach: pushState if triggered by click, replace if by scroll? 
                        // Hard to distinguish in onScroll. Let's use replaceState to keep history clean for "App" feel.
                        history.replaceState(null, '', url);
                    }
                }
            }));
        });
    </script>

    @yield('scripts')
</body>

</html>