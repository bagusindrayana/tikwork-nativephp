@extends('layouts.app')

@section('content')
    <main class="flex-1 w-full bg-black h-[100dvh] overflow-y-auto no-scrollbar pt-14 lg:pt-4 px-4 pb-24"
        x-data="profilePage({{ Js::from($profile) }})">

        <div class="max-w-2xl mx-auto lg:mt-10">
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

            <div class="flex justify-end sticky bottom-1 lg:bottom-2">
                <button @click="saveProfile()"
                    class="bg-[#FE2C55] text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-[#ef2950] active:scale-95 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
            </div>
        </div>
        <div class="h-20 lg:hidden shrink-0"></div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profilePage', (initialProfile) => ({
                profile: {
                    name: initialProfile?.name || 'User',
                    categories: initialProfile?.categories || []
                },
                availableCategories: ['Technology', 'Design', 'Marketing', 'Sales', 'Finance', 'Engineering', 'HR'],

                init() {},

                async saveProfile() {
                    const params = {
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
                            body: JSON.stringify(params)
                        });
                        
                        if(response.ok) {
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
@endsection