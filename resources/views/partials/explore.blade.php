<div class="h-full w-full bg-black overflow-y-auto no-scrollbar pt-24 lg:pt-4 px-2" x-data="exploreFeed()"
    @scroll.debounce.100ms="checkScroll($el)" @touchstart="handleTouchStart($event)"
    @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd($event)">

    <!-- Pull to Refresh Indicator -->
    <div class="w-full flex items-center justify-center -mt-16 h-16 transition-transform duration-200"
        :style="`transform: translateY(${pullY * 0.5}px)`" x-show="pulling || refreshing">
        <div class="flex items-center gap-2 text-white">
            <i class="fa-solid fa-rotate-right transition-transform duration-200"
                :class="refreshing ? 'animate-spin' : ''" :style="`transform: rotate(${pullY * 2}deg)`"></i>
            <span x-text="refreshing ? 'Refreshing...' : 'Pull to refresh'"></span>
        </div>
    </div>

    <!-- MOBILE TOP HEADER (Explore) -->
    <div x-data="{
                query: '',
                performSearch() {
                    // Navigate to home with search because Explore is just random or categorized, 
                    // usually users expect 'Search' to be global. 
                    // Or we can filter the explore grid. Let's redirect to Home search for consistency with current flow.
                     window.location.href = '/?search=' + encodeURIComponent(this.query);
                }
            }"
        class="absolute top-0 left-0 w-full pt-8 pb-4 px-4 flex items-center gap-4 z-[100] text-white lg:hidden bg-gradient-to-b from-black via-black/80 to-transparent pointer-events-auto">

        <div class="relative flex-1">
            <input type="text" x-model="query" @keydown.enter="performSearch()" placeholder="Search jobs..."
                class="w-full bg-white/20 backdrop-blur-md rounded-full py-2 pl-10 pr-4 text-sm text-white placeholder-gray-300 border-none outline-none focus:ring-1 focus:ring-white/50">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-300 text-sm"></i>
        </div>

        <button @click="performSearch()" class="text-white">
            <span class="font-semibold text-sm">Search</span>
        </button>
    </div>

    <div id="explore-grid"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 pb-4 transition-transform duration-200"
        :style="`transform: translateY(${pullY * 0.5}px)`">
        <!-- Initial data might be empty if fetched client-side, or pre-rendered if we pass it. 
             Ideally we fetch on mount. -->
    </div>

    <!-- Loader -->
    <div x-show="loading" class="w-full h-24 flex items-center justify-center text-white shrink-0 mb-20">
        <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
    </div>

    <div class="h-20 lg:hidden shrink-0"></div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('exploreFeed', () => ({
            loading: false,
            page: 1,
            loaded: false,

            // Pull to Refresh State
            pulling: false,
            refreshing: false,
            startY: 0,
            pullY: 0,

            init() {
                // Fetch initial data if not loaded
                if (!this.loaded) {
                    this.loadMore();
                }
            },

            checkScroll(el) {
                // If close to bottom
                if (Math.ceil(el.scrollHeight - el.scrollTop) <= el.clientHeight + 200) {
                    this.loadMore();
                }
            },

            async loadMore() {
                if (this.loading) return;
                this.loading = true;

                try {
                    const res = await fetch(`{{ route("api.jobs.explore") }}?page=${this.page}`);
                    const data = await res.json();

                    if (data.html) {
                        const grid = document.getElementById('explore-grid');
                        if (grid) {
                            grid.insertAdjacentHTML('beforeend', data.html);
                            this.page++;
                            this.loaded = true;
                        }
                    }
                } catch (e) {
                    console.error("Failed to load more explore items", e);
                } finally {
                    this.loading = false;
                }
            },

            // --- PULL TO REFRESH LOGIC ---
            handleTouchStart(e) {
                // Prevent pull-to-refresh if touching an input
                if (e.target.closest('input, button, a')) return;

                if (this.$el.scrollTop === 0) {
                    this.startY = e.touches[0].clientY;
                    this.pulling = true;
                }
            },

            handleTouchMove(e) {
                if (!this.pulling) return;
                const currentY = e.touches[0].clientY;
                const diff = currentY - this.startY;

                if (diff > 0 && this.$el.scrollTop === 0) {
                    this.pullY = diff;
                } else {
                    this.pullY = 0;
                }
            },

            async handleTouchEnd(e) {
                if (!this.pulling) return;
                this.pulling = false;

                if (this.pullY > 100) {
                    this.refreshing = true;
                    this.pullY = 60;
                    await this.refresh();
                    this.refreshing = false;
                }

                this.pullY = 0;
            },

            async refresh() {
                try {
                    this.page = 1;
                    const grid = document.getElementById('explore-grid');
                    if (grid) grid.innerHTML = '';
                    await this.loadMore();
                } catch (e) {
                    console.error('Refresh failed', e);
                }
            }
        }));
    });
</script>