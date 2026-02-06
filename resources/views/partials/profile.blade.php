<div class="h-full w-full bg-black overflow-y-auto no-scrollbar pt-14 lg:pt-4 px-4 pb-24" x-data="profileView()"
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

    <div class="max-w-2xl mx-auto lg:mt-10 transition-transform duration-200"
        :style="`transform: translateY(${pullY * 0.5}px)`" x-show="loaded">
        <div class="flex flex-col items-center mb-8">
            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-cyan-400 to-pink-500 p-1 mb-4">
                <div class="w-full h-full rounded-full bg-black flex items-center justify-center overflow-hidden">
                    <i class="fa-solid fa-user text-4xl text-gray-400"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white" x-text="profile.name || 'Set Your Name'"></h2>
            <button class="text-[#FE2C55] text-sm font-semibold mt-2">Edit Profile</button>
        </div>

        <div class="bg-[#121212] rounded-lg p-6 mb-6 border border-gray-800">
            <h3 class="text-lg font-bold mb-4 text-white">Profile Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Display Name</label>
                    <input type="text" x-model="profile.name"
                        class="w-full bg-[#2F2F2F] rounded-md px-4 py-3 text-white focus:ring-2 focus:ring-[#FE2C55] outline-none border-none">
                </div>
            </div>
        </div>

        <div class="bg-[#121212] rounded-lg p-6 mb-6 border border-gray-800">
            <h3 class="text-lg font-bold mb-4 text-white">Following Categories</h3>
            <p class="text-sm text-gray-400 mb-4">Select categories to customize your "Following" feed.</p>

            <div class="grid grid-cols-2 gap-3">
                <template x-for="cat in availableCategories" :key="cat">
                    <label
                        class="flex items-center gap-3 p-3 bg-[#1F1F1F] rounded-md cursor-pointer hover:bg-[#2A2A2A] transition">
                        <input type="checkbox" :value="cat" x-model="profile.categories"
                            class="w-5 h-5 rounded border-gray-600 text-[#FE2C55] focus:ring-[#FE2C55] bg-gray-700">
                        <span class="text-sm font-medium text-white" x-text="cat"></span>
                    </label>
                </template>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div x-show="!loaded" class="w-full h-24 flex items-center justify-center text-white shrink-0 mb-20">
        <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400"></i>
    </div>

    <div class="flex justify-end sticky float-button px-0 pointer-events-none z-30" x-show="loaded">
        <button @click="saveProfile()"
            class="bg-[#FE2C55] text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-[#ef2950] active:scale-95 transition flex items-center gap-2 pointer-events-auto">
            <i class="fa-solid fa-check"></i> Save Changes
        </button>
    </div>
    <div class="h-20 lg:hidden shrink-0"></div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profileView', () => ({
            profile: {
                name: 'User',
                categories: []
            },
            availableCategories: ['Programmer', 'Technology', 'Design', 'Marketing', 'Sales', 'Finance', 'Engineering', 'HR', 'Developer'],
            loaded: false,

            // Pull to Refresh State
            pulling: false,
            refreshing: false,
            startY: 0,
            pullY: 0,

            init() {
                this.loadProfile();
            },

            async loadProfile() {
                try {
                    const res = await fetch('/api/profile');
                    const data = await res.json();
                    if (data.profile) {
                        this.profile.name = data.profile.name || '';
                        this.profile.categories = data.profile.categories || [];
                    }
                    this.loaded = true;
                } catch (e) {
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
                await this.loadProfile();
            },

            async saveProfile() {
                const params = {
                    name: this.profile.name,
                    categories: this.profile.categories.join(',') // API expects string or we updated controller to handle array?
                    // Controller: if (isset($data['categories']) && is_string($data['categories'])) ...
                    // It seems controller handles string separation. 
                    // Let's send it as array if Laravel handles casting, OR join it.
                    // The view sent comma separated string in original code?
                    // Original: categories: this.profile.categories.join(',')
                };

                // Wait, if I send JSON, Laravel receives array if I don't join. 
                // Creating simplified JSON payload is better.
                // Let's check controller again.
                // Controller says: if (isset($data['categories']) && is_string($data['categories'])) 
                // So it handles string. If I send array, $profile->update($data) might fail if column is string and Not cast in model.
                // Let's assume Profile model casts 'categories' => 'array'.
                // If so, I should send array.
                // But original code did join. Let's stick to original behavior to be safe, or check Model.
                // I will send as string to match previous behavior if I am unsure.
                // Actually, let's send object and let Laravel handle.

                // Re-reading controller:
                // $data['categories'] = array_map('trim', explode(',', $data['categories']));
                // This implies it EXPECTS a string. 

                const payload = {
                    name: this.profile.name,
                    categories: this.profile.categories.join(',')
                };

                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const response = await fetch('/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                        alert('Profile saved!');
                    } else {
                        alert('Failed to save.');
                    }
                } catch (e) {
                    console.error('Save failed', e);
                    alert('Error saving profile');
                }
            }
        }));
    });
</script>