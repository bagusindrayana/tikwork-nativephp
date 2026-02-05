@extends('layouts.app')

@section('content')
    <!-- MOBILE TOP HEADER (FIXED, Only for Home) -->
    <div class="fixed top-0 left-0 w-full pt-8 pb-4 px-4 flex justify-between items-start z-[100] text-white lg:hidden pointer-events-none"
        style="background: linear-gradient(to bottom, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0.7) 40%, rgba(0, 0, 0, 0) 100%);"
        x-data="{ showSearch: false, query: '{{ request('search') }}' }">

        <!-- TV Icon -->
        <i class="fa-solid fa-tv text-lg opacity-80 pointer-events-auto shadow-md" x-show="!showSearch"></i>

        <!-- Tabs (Hide when searching) -->
        <div class="flex gap-4 text-base font-bold text-shadow pointer-events-auto transition duration-300"
            x-show="!showSearch" x-data="{ currentTab: 'foryou' }">
            <div class="relative cursor-pointer" @click="$dispatch('switch-tab', 'following'); currentTab = 'following'">
                <span :class="currentTab === 'following' ? 'opacity-100' : 'opacity-60'" class="transition">Following</span>
                <div x-show="currentTab === 'following'"
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-white rounded-full"></div>
            </div>
            <div class="relative cursor-pointer" @click="$dispatch('switch-tab', 'foryou'); currentTab = 'foryou'">
                <span :class="currentTab === 'foryou' ? 'opacity-100' : 'opacity-60'" class="transition">For You</span>
                <div x-show="currentTab === 'foryou'"
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-white rounded-full"></div>
            </div>
        </div>

        <!-- Search input overlay -->
        <div x-show="showSearch" x-transition
            class="absolute top-6 left-4 right-4 pointer-events-auto flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text" x-model="query"
                    @keydown.enter="window.location.href = '{{ route('home') }}?search=' + encodeURIComponent(query)"
                    placeholder="Search..."
                    class="w-full bg-black/60 backdrop-blur-md rounded-full py-2 pl-10 pr-8 text-sm text-white border border-white/20 focus:ring-1 focus:ring-white">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-300 text-xs"></i>
                <button @click="showSearch = false" class="absolute right-3 top-2.5 text-gray-400">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <button @click="window.location.href = '{{ route('home') }}?search=' + encodeURIComponent(query)"
                class="bg-[#FE2C55] px-3 py-1.5 rounded-full text-xs font-bold text-white">
                GO
            </button>
        </div>

        <!-- Search Trigger Icon -->
        <button @click="showSearch = true; $nextTick(() => $el.parentNode.querySelector('input').focus())"
            class="pointer-events-auto" x-show="!showSearch">
            <i class="fa-solid fa-magnifying-glass text-lg opacity-80 shadow-md"></i>
        </button>
    </div>

    <main class="w-full h-full relative" x-data="homeFeed('{{ $currentCategories ?? '' }}')">

        <!-- FOR YOU FEED -->
        <div x-show="activeTab === 'foryou'"
            class="w-full h-[100dvh] overflow-y-scroll snap-y snap-mandatory relative scroll-smooth no-scrollbar"
            @scroll.debounce.100ms="checkForYouScroll($el)">

            <!-- Initial Server Render -->
            @foreach($jobs['data'] as $index => $job)
                <div class="snap-center relative h-full">
                    <x-feed-item :job="$job" :index="$index" wire:key="job-{{ $job['id'] }}" />
                </div>
            @endforeach

            <div id="foryou-feed-loader" x-show="foryouLoading"
                class="w-full h-24 flex items-center justify-center snap-center text-white bg-black shrink-0">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
                    <span class="text-sm font-medium text-gray-400">Loading more jobs...</span>
                </div>
            </div>
            <div class="h-20 lg:hidden snap-align-none bg-black shrink-0"></div>
        </div>

        <!-- FOLLOWING FEED -->
        <div x-show="activeTab === 'following'" x-cloak
            class="w-full h-[100dvh] overflow-y-scroll snap-y snap-mandatory relative scroll-smooth no-scrollbar"
            @scroll.debounce.100ms="checkFollowingScroll($el)">

            <div id="following-feed-container" class="w-full"></div>

            <div x-show="followingLoading"
                class="w-full h-24 flex items-center justify-center snap-center text-white bg-black shrink-0 m-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
                    <span class="text-sm font-medium text-gray-400">Loading following...</span>
                </div>
            </div>

            <div x-show="followingLoaded && !followingLoading && document.getElementById('following-feed-container')?.children.length === 0"
                class="flex items-center justify-center h-[50vh] text-gray-500">
                <div class="text-center">
                    <i class="fa-solid fa-users-slash text-4xl mb-2"></i>
                    <p>No jobs found for your categories.</p>
                    <a href="{{ route('profile') }}"
                        class="mt-4 px-4 py-2 bg-[#FE2C55] text-white rounded-full text-sm font-bold inline-block">Manage
                        Categories</a>
                </div>
            </div>

            <div class="h-20 lg:hidden snap-align-none bg-black shrink-0"></div>
        </div>

    </main>
@endsection

@section('scripts')
    <script>
        window.userFavorites = {{ Js::from($favoriteIds ?? []) }};

        document.addEventListener('alpine:init', () => {
            Alpine.data('homeFeed', (initialCategories = '') => ({
                activeTab: 'foryou',

                // For You State
                foryouPage: 1,
                foryouTotalPages: {{ $jobs['totalPages'] ?? 1000 }},
                foryouLoading: false,
                currentCategories: initialCategories,

                // Following State
                followingPage: 1,
                followingTotalPages: 1,
                followingLoading: false,
                followingLoaded: false,

                init() {
                    // Listen for tab switching from header
                    window.addEventListener('switch-tab', (e) => {
                        this.activeTab = e.detail;
                        if (this.activeTab === 'following' && !this.followingLoaded) {
                            this.loadFollowingJobs();
                        }
                    });

                    // Also check URL hash if we want deep linking, but current request is simple
                },

                // --- FOR YOU LOGIC ---
                checkForYouScroll(el) {
                    if (this.foryouPage >= this.foryouTotalPages) return;
                    if (Math.ceil(el.scrollHeight - el.scrollTop) <= el.clientHeight + 100) {
                        this.loadMoreForYou();
                    }
                },

                async loadMoreForYou() {
                    if (this.foryouLoading) return;
                    this.foryouLoading = true;
                    try {
                        const urlParams = new URLSearchParams(window.location.search);
                        const search = urlParams.get('search') || '';

                        let url = `/api/jobs?page=${this.foryouPage + 1}&search=${encodeURIComponent(search)}`;
                        // Append categories if they exist (from explore click)
                        if (this.currentCategories) {
                            url += `&categories=${encodeURIComponent(this.currentCategories)}`;
                        }

                        const response = await fetch(url);
                        if (!response.ok) throw new Error('Network response was not ok');
                        const data = await response.json();
                        if (data.html) {
                            const loader = document.getElementById('foryou-feed-loader');
                            if (loader) {
                                loader.insertAdjacentHTML('beforebegin', data.html);
                                this.foryouPage = parseInt(data.page);
                                this.foryouTotalPages = parseInt(data.totalPages);
                            }
                        }
                    } catch (error) {
                        console.error('Error loading jobs:', error);
                    } finally {
                        this.foryouLoading = false;
                    }
                },

                // --- FOLLOWING LOGIC ---
                async loadFollowingJobs() {
                    if (this.followingLoading) return;

                    this.followingLoading = true;

                    // get categories from local storage
                    let categories = '';
                    try {
                        const profile = JSON.parse(localStorage.getItem('tikwork_profile') || '{}');
                        if (profile.categories) {
                            categories = profile.categories.join(',');
                        }
                    } catch (e) { }

                    const url = `/api/jobs/following?page=${this.followingPage}&categories=${encodeURIComponent(categories)}`;

                    try {
                        const res = await fetch(url);
                        const data = await res.json();

                        if (data.html) {
                            const container = document.getElementById('following-feed-container');
                            if (container) {
                                container.insertAdjacentHTML('beforeend', data.html);
                                this.followingPage = parseInt(data.page) + 1;
                                this.followingTotalPages = parseInt(data.totalPages);
                                this.followingLoaded = true;
                            }
                        } else {
                            this.followingLoaded = true; // No more data
                        }
                    } catch (e) {
                        console.error("Failed to load following jobs", e);
                    } finally {
                        this.followingLoading = false;
                    }
                },

                checkFollowingScroll(el) {
                    if (this.followingPage > this.followingTotalPages) return;
                    if (Math.ceil(el.scrollHeight - el.scrollTop) <= el.clientHeight + 100) {
                        this.loadFollowingJobs();
                    }
                }
            }));
        });
    </script>
@endsection