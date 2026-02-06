<div class="w-full h-full relative" x-data="homeFeed('{{ $currentCategories ?? '' }}')">
    <!-- MOBILE TOP HEADER (FIXED, Only for Home) -->
    <!-- MOBILE TOP HEADER (FIXED, Only for Home) -->
    <div class="absolute top-0 left-0 w-full pt-8 pb-4 px-4 h-24 flex justify-between items-start z-[100] lg:hidden transition-colors duration-300"
        :class="showSearch ? 'bg-black' : 'bg-transparent'"
        style="background: linear-gradient(to bottom, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0.7) 40%, rgba(0, 0, 0, 0) 100%); pointer-events: none;"
        x-data="{ showSearch: false, query: {{ Js::from(request('search') ?? '') }} }">

        <!-- TV Icon -->
        <i class="fa-solid fa-tv text-lg text-white opacity-80 pointer-events-auto shadow-md drop-shadow-md"
            x-show="!showSearch"></i>

        <!-- Tabs (Hide when searching) -->
        <div class="flex gap-4 text-white text-base font-bold text-shadow pointer-events-auto transition duration-300"
            x-show="!showSearch" x-data="{ currentTab: 'foryou' }" @tab-changed.window="currentTab = $event.detail">
            <div class="relative cursor-pointer"
                @click="$dispatch('switch-tab', 'following'); currentTab = 'following'">
                <span :class="currentTab === 'following' ? 'opacity-100' : 'opacity-60'"
                    class="transition drop-shadow-md">Following</span>
                <div x-show="currentTab === 'following'"
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-white rounded-full shadow-sm">
                </div>
            </div>
            <div class="relative cursor-pointer" @click="$dispatch('switch-tab', 'foryou'); currentTab = 'foryou'">
                <span :class="currentTab === 'foryou' ? 'opacity-100' : 'opacity-60'"
                    class="transition drop-shadow-md">For You</span>
                <div x-show="currentTab === 'foryou'"
                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-white rounded-full shadow-sm">
                </div>
            </div>
        </div>

        <!-- Search input overlay -->
        <div x-show="showSearch" x-transition x-cloak
            class="absolute top-0 left-0 w-full h-full pointer-events-auto flex items-center px-4 gap-2 bg-black z-50">
            <div class="relative flex-1">
                <input type="text" x-model="query" @keydown.enter="showSearch=false; $dispatch('search-jobs', query)"
                    placeholder="Search jobs..." autofocus
                    class="w-full bg-[#1F1F1F] rounded-md py-2 pl-10 pr-8 text-sm text-white border-none focus:ring-1 focus:ring-gray-600 placeholder-gray-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-400 text-xs"></i>
                <button @click="showSearch = false; query = ''" class="absolute right-3 top-2.5 text-gray-400">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
            <button @click="showSearch=false; $dispatch('search-jobs', query)"
                class="px-3 py-1.5 text-sm font-bold text-[#FE2C55]">
                Search
            </button>
        </div>

        <!-- Search Trigger Icon -->
        <button @click="showSearch = true; $nextTick(() => $el.parentNode.querySelector('input').focus())"
            class="pointer-events-auto text-white" x-show="!showSearch">
            <i class="fa-solid fa-magnifying-glass text-lg opacity-80 shadow-md drop-shadow-md"></i>
        </button>
    </div>

    <!-- FOR YOU FEED -->
    <!-- Horizontal Slider Container -->
    <div x-ref="slider"
        class="w-full h-full flex overflow-x-auto snap-x snap-mandatory snap-always no-scrollbar lg:overflow-hidden lg:snap-none"
        @scroll.debounce.10ms="handleHorizontalScroll($el)">

        <!-- FOLLOWING FEED (Left) -->
        <div class="w-full h-full shrink-0 snap-center overflow-y-scroll snap-y snap-mandatory relative scroll-smooth no-scrollbar"
            @scroll.debounce.100ms="checkFollowingScroll($el)" @touchstart="handleTouchStart($event, 'following')"
            @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd($event)">

            <!-- Pull to Refresh Indicator (Following) -->
            <div class="w-full flex items-center justify-center -mt-16 h-16 transition-transform duration-200"
                :style="`transform: translateY(${pullContext === 'following' ? pullY * 0.5 : 0}px)`"
                x-show="(pulling || refreshing) && pullContext === 'following'">
                <div class="flex items-center gap-2 text-white">
                    <i class="fa-solid fa-rotate-right transition-transform duration-200"
                        :class="refreshing ? 'animate-spin' : ''" :style="`transform: rotate(${pullY * 2}deg)`"></i>
                    <span x-text="refreshing ? 'Refreshing...' : 'Pull to refresh'"></span>
                </div>
            </div>

            <div id="following-feed-container" class="w-full transition-transform duration-200"
                :style="`transform: translateY(${pullContext === 'following' ? pullY * 0.5 : 0}px)`"></div>

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
                    <div @click="$dispatch('navigate-profile')"
                        class="mt-4 px-4 py-2 bg-[#FE2C55] text-white rounded-full text-sm font-bold inline-block cursor-pointer">
                        Manage
                        Categories</div>
                </div>
            </div>

            <div class="h-20 lg:hidden snap-align-none bg-black shrink-0"></div>
        </div>

        <!-- FOR YOU FEED (Right) -->
        <div class="w-full h-full shrink-0 snap-center overflow-y-scroll snap-y snap-mandatory relative scroll-smooth no-scrollbar"
            @scroll.debounce.100ms="checkForYouScroll($el)" @touchstart="handleTouchStart($event, 'foryou')"
            @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd($event)">

            <!-- Pull to Refresh Indicator (For You) -->
            <div class="w-full flex items-center justify-center -mt-16 h-16 transition-transform duration-200"
                :style="`transform: translateY(${pullContext === 'foryou' ? pullY * 0.5 : 0}px)`"
                x-show="(pulling || refreshing) && pullContext === 'foryou'">
                <div class="flex items-center gap-2 text-white">
                    <i class="fa-solid fa-rotate-right transition-transform duration-200"
                        :class="refreshing ? 'animate-spin' : ''" :style="`transform: rotate(${pullY * 2}deg)`"></i>
                    <span x-text="refreshing ? 'Refreshing...' : 'Pull to refresh'"></span>
                </div>
            </div>

            <div id="foryou-feed-container"
                :style="`transform: translateY(${pullContext === 'foryou' ? pullY * 0.5 : 0}px)`"
                class="transition-transform duration-200">
                @if(isset($jobs['data']))
                    @foreach($jobs['data'] as $index => $job)
                        <div class="snap-center relative w-full post-feed">
                            <x-feed-item :job="$job" :index="$index" wire:key="job-{{ $job['id'] }}" />
                        </div>
                    @endforeach
                @endif
            </div>

            <div id="foryou-feed-loader" x-show="foryouLoading"
                class="w-full h-24 flex items-center justify-center snap-center text-white bg-black shrink-0">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
                    <span class="text-sm font-medium text-gray-400">Loading more jobs...</span>
                </div>
            </div>
            <div class="h-20 lg:hidden snap-align-none bg-black shrink-0"></div>
        </div>

    </div>
</div>

<script>
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

            // Pull to Refresh State
            pulling: false,
            refreshing: false,
            startY: 0,
            pullY: 0,
            pullContext: null, // 'following' or 'foryou'

            init() {
                // Start at "For You" (Right side), so scroll to end
                this.$nextTick(() => {
                    if (this.$refs.slider) {
                        this.$refs.slider.scrollLeft = this.$refs.slider.scrollWidth;
                    }
                });

                // Listen for tab switching from header
                window.addEventListener('switch-tab', (e) => {
                    const target = e.detail;
                    if (target === 'following') {
                        this.$refs.slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        this.$refs.slider.scrollTo({ left: this.$refs.slider.scrollWidth, behavior: 'smooth' });
                    }
                });

                window.addEventListener('search-jobs', (e) => {
                    const query = e.detail;
                    window.location.href = `/?search=${encodeURIComponent(query)}`;
                });
            },

            handleHorizontalScroll(el) {
                const width = el.clientWidth;
                const scrollLeft = el.scrollLeft;
                const threshold = width / 2;

                let newTab = 'following';
                if (scrollLeft > threshold) {
                    newTab = 'foryou';
                }

                if (this.activeTab !== newTab) {
                    this.activeTab = newTab;
                    window.dispatchEvent(new CustomEvent('tab-changed', { detail: newTab }));

                    if (this.activeTab === 'following' && !this.followingLoaded) {
                        this.loadFollowingJobs();
                    }
                }
            },

            // --- PULL TO REFRESH LOGIC ---
            handleTouchStart(e, context) {
                // Determine target element based on context if not directly passed (optional check)
                // But $el in Alpine refers to element with handler.
                // We just need to check if we are at top.
                if (e.target.closest('.overflow-y-scroll').scrollTop === 0) {
                    this.startY = e.touches[0].clientY;
                    this.pulling = true;
                    this.pullContext = context;
                }
            },

            handleTouchMove(e) {
                if (!this.pulling) return;
                const currentY = e.touches[0].clientY;
                const diff = currentY - this.startY;

                // Check again if we are still at top (important if user scrolls up then pulls)
                // Actually `startY` lock is usually enough for simple PTR.

                if (diff > 0) {
                    this.pullY = diff;
                } else {
                    this.pullY = 0;
                }
            },

            async handleTouchEnd(e) {
                if (!this.pulling) return;
                this.pulling = false;

                if (this.pullY > 100) { // Threshold
                    this.refreshing = true;
                    this.pullY = 60; // Snap

                    if (this.pullContext === 'following') {
                        await this.refreshFollowing();
                    } else {
                        await this.refreshForYou();
                    }

                    this.refreshing = false;
                }

                this.pullY = 0;
                this.pullContext = null;
            },

            async refreshForYou() {
                try {
                    this.foryouPage = 1;
                    const container = document.getElementById('foryou-feed-container');

                    const urlParams = new URLSearchParams(window.location.search);
                    const search = urlParams.get('search') || '';

                    let url = `/api/jobs?page=1&search=${encodeURIComponent(search)}`;
                    if (this.currentCategories) {
                        url += `&categories=${encodeURIComponent(this.currentCategories)}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.html && container) {
                        container.innerHTML = data.html;
                        this.foryouPage = parseInt(data.page);
                        this.foryouTotalPages = parseInt(data.totalPages);
                    }
                } catch (e) {
                    console.error('Refresh failed', e);
                }
            },

            async refreshFollowing() {
                try {
                    this.followingPage = 1;
                    const container = document.getElementById('following-feed-container');

                    const url = `/api/jobs/following?page=1`;
                    const res = await fetch(url);
                    const data = await res.json();

                    if (data.html && container) {
                        container.innerHTML = data.html;
                        this.followingPage = parseInt(data.page) + 1;
                        this.followingTotalPages = parseInt(data.totalPages);
                        this.followingLoaded = true;
                    }
                } catch (e) {
                    console.error('Refreh following failed', e);
                }
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
                    const loader = document.getElementById('foryou-feed-loader');
                    const container = document.getElementById('foryou-feed-container');

                    const urlParams = new URLSearchParams(window.location.search);
                    const search = urlParams.get('search') || '';

                    let url = `/api/jobs?page=${this.foryouPage + 1}&search=${encodeURIComponent(search)}`;
                    if (this.currentCategories) {
                        url += `&categories=${encodeURIComponent(this.currentCategories)}`;
                    }

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    if (data.html) {
                        if (container) {
                            container.insertAdjacentHTML('beforeend', data.html);
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

                // Categories are now handled server-side from the Profile model
                const url = `/api/jobs/following?page=${this.followingPage}`;

                const container = document.getElementById('following-feed-container');

                try {
                    const res = await fetch(url);
                    const data = await res.json();

                    if (data.html) {
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