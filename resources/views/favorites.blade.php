@extends('layouts.app')

@section('content')
    <main class="flex-1 w-full bg-black h-[100dvh] overflow-y-auto no-scrollbar pt-14 lg:pt-4 px-4 pb-24"
        x-data="favoritesPage({{ Js::from($favorites) }})">
        <h2 class="text-xl font-bold mb-4 px-2 lg:mt-5 text-white">Your Favorites</h2>

        <div x-show="favorites.length === 0" class="flex flex-col items-center justify-center h-[50vh] text-gray-500"
            x-cloak>
            <i class="fa-regular fa-heart text-6xl mb-4 opacity-50"></i>
            <p>No favorites yet.</p>
            <p class="text-sm">Tap the heart on any job to save it here.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 pb-4">
            @foreach($favorites as $job)
                <div @click="window.location.href = '/job/' + {{$job['id']}}"
                    class="relative aspect-[9/16] bg-gray-900 rounded-sm overflow-hidden group cursor-pointer border border-gray-800 hover:border-gray-600 transition-colors">

                    <!-- Poster Embed -->
                    <div class="w-full h-full pointer-events-none">
                        <div class="w-[200%] h-[200%] transform scale-50 origin-top-left border-0">
                            <x-dynamic-poster :job=" $job" />
                        </div>
                    </div>

                    <!-- Remove Button (z-index higher than overlay) -->
                    <div class="absolute top-2 right-2 z-50 pointer-events-auto">
                        <button type="button" @click.stop="removeFavorite({{$job['id']}})"
                            class="text-[#FE2C55] bg-black/40 p-2 rounded-full backdrop-blur-md hover:bg-black/60 transition shadow-sm border border-white/10">
                            <i class="fa-solid fa-heart text-sm"></i>
                        </button>
                    </div>

                    <!-- Info Overlay -->
                    <div
                        class="absolute inset-0 z-20 bg-transparent flex flex-col justify-end p-2 pointer-events-auto hover:bg-black/10 transition">
                        <div
                            class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/80 to-transparent pointer-events-none">
                        </div>
                        <div class="relative z-30 pointer-events-none">
                            <h4 class="font-bold text-xs truncate text-white drop-shadow-md">{{ $job['job_title'] ?? 'Title' }}
                            </h4>
                            <span
                                class="text-[10px] text-gray-300 truncate block">{{ $job['job_company_name'] ?? 'Company' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="h-20 lg:hidden shrink-0"></div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('favoritesPage', (initialFavorites) => ({
                favorites: initialFavorites || [],

                init() { },
                async removeFavorite(id) {
                    const index = this.favorites.findIndex(f => f.id === id);
                    if (index > -1) {
                        try {
                            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            await fetch('/favorite/toggle', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ id: id })
                            });
                            window.location.reload();
                        } catch (e) {
                            console.error('Failed to sync favorite', e);
                        }
                    }
                }
            }));
        });
    </script>
@endsection