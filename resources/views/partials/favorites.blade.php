<div class="h-full w-full bg-black overflow-y-auto no-scrollbar pt-14 lg:pt-4 px-4 pb-24" x-data="favoritesView()"
    @touchstart="handleTouchStart($event)" @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd($event)">

    <!-- Pull to Refresh Indicator -->
    <div class="w-full flex items-center justify-center -mt-16 h-16 transition-transform duration-200"
        :style="`transform: translateY(${pullY * 0.5}px)`" x-show="pulling || refreshing">
        <div class="flex items-center gap-2 text-white">
            <i class="fa-solid fa-rotate-right transition-transform duration-200"
                :class="refreshing ? 'animate-spin' : ''" :style="`transform: rotate(${pullY * 2}deg)`"></i>
            <span x-text="refreshing ? 'Refreshing...' : 'Pull to refresh'"></span>
        </div>
    </div>
    <h2 class="text-xl font-bold mb-4 px-2 lg:mt-5 text-white">Your Favorites</h2>

    <div x-show="favorites.length === 0 && loaded"
        class="flex flex-col items-center justify-center h-[50vh] text-gray-500" x-cloak>
        <i class="fa-regular fa-heart text-6xl mb-4 opacity-50"></i>
        <p>No favorites yet.</p>
        <p class="text-sm">Tap the heart on any job to save it here.</p>
    </div>

    <!-- Grid Container -->
    <div id="favorites-grid"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 pb-4 transition-transform duration-200"
        x-html="htmlContent" :style="`transform: translateY(${pullY * 0.5}px)`">
        <!-- Content injected via Alpine -->
    </div>

    <!-- Loader -->
    <div x-show="!loaded" class="w-full h-24 flex items-center justify-center text-white shrink-0 mb-20">
        <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
    </div>

    <div class="h-20 lg:hidden shrink-0"></div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('favoritesView', () => ({
            favorites: [],
            htmlContent: '',
            loaded: false,

            // Pull to Refresh State
            pulling: false,
            refreshing: false,
            startY: 0,
            pullY: 0,

            init() {
                this.loadFavorites();
                window.addEventListener('reload-favorites', () => {
                    this.loadFavorites();
                });
            },

            async loadFavorites() {
                try {
                    const res = await fetch('/api/favorites');
                    const data = await res.json();
                    this.htmlContent = data.html;
                    this.favorites = { length: data.count };
                    this.loaded = true;
                } catch (e) {
                    console.error('Failed to load favorites', e);
                    this.loaded = true;
                }
            },

            // --- PULL TO REFRESH LOGIC ---
            handleTouchStart(e) {
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
                await this.loadFavorites();
            }
        }));

        // Global function for handling clicks inside x-html
        window.removeFavorite = async (id) => {
            if (!confirm('Remove from favorites?')) return;

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/favorite/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });

                const res = await response.json();
                if (res.status === 'removed' || res.status === 'added') {
                    // Update global favorites list for Home feed
                    if (window.userFavorites) {
                        window.userFavorites = window.userFavorites.filter(favId => favId !== String(id));
                    }

                    // Dispatch event to reload the list
                    window.dispatchEvent(new Event('reload-favorites'));
                }
            } catch (e) {
                console.error('Failed to remove favorite', e);
                alert('An error occurred while removing.');
            }
        };

    });
</script>