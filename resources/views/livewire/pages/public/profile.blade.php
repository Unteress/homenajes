<?php

use App\Models\Deceased;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.public')] class extends Component {
    public Deceased $deceased;
    public $allPhotosJson = [];
    public $currentUrl;

    public function mount(Deceased $deceased)
    {
        if (!$deceased->is_public) {
            return redirect('/');
        }

        
        $this->deceased = $deceased->load('photos');
        $this->currentUrl = url()->current();
        
       
        $this->allPhotosJson = $deceased->photos->map(function($photo) {
            return asset('storage/' . $photo->path);
        })->values()->toArray();
    }

    public function with(): array
    {
        $photos = $this->deceased->photos;

        return [
            'coverPhoto' => $photos->where('type', 'cover')->first(),
            'profilePhoto' => $photos->where('type', 'profile')->first() ?? $photos->first(),
            'photoCount' => $photos->count(),
        ];
    }
}; ?>

<div class="min-h-screen bg-gray-100 font-sans pb-10 w-full"
     x-data="galleryComponent(@js($allPhotosJson))">
    
    <div class="max-w-4xl mx-auto bg-white shadow-2xl min-h-[calc(100vh-4rem)] md:border-x md:border-gray-200 relative z-10 w-full overflow-hidden">
        
        <div class="relative bg-white pb-2 w-full">
            <div class="relative h-48 md:h-80 bg-gray-300 overflow-hidden group w-full">
                @if($coverPhoto)
                    <img src="{{ asset('storage/' . $coverPhoto->path) }}" class="w-full h-full object-cover cursor-pointer" 
                         @click="openLightbox('{{ asset('storage/' . $coverPhoto->path) }}')" alt="Portada">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/30"></div>
            </div>

            <div class="-mt-24 md:-mt-32 mb-4 relative z-10 flex justify-center w-full px-4">
                <div class="h-40 w-40 md:h-64 md:w-64 rounded-full border-[6px] border-white shadow-2xl overflow-hidden bg-white cursor-pointer hover:scale-105 transition-transform duration-300 shrink-0"
                     @click="openLightbox('{{ $profilePhoto ? asset('storage/' . $profilePhoto->path) : '' }}')">
                    @if($profilePhoto)
                        <img src="{{ asset('storage/' . $profilePhoto->path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                            <svg class="w-20 md:w-24 h-20 md:h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center px-4 w-full">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 font-serif leading-tight break-words">{{ $deceased->name }}</h1>
                <div class="flex items-center justify-center mt-4 mb-2 w-full">
                    <div class="bg-gray-900 text-yellow-500 px-4 py-2 rounded-full shadow-md flex flex-wrap justify-center items-center gap-x-4 gap-y-1 text-xs md:text-sm font-bold border border-yellow-600/50 max-w-full">
                        
                        <span class="flex items-center gap-1 whitespace-nowrap">
                            <span class="text-[10px] uppercase text-gray-400 font-normal mr-1">Q.E.P.D </span>
                            {{ $deceased->death_date->format('d/m/Y') }}
                            <svg class="w-3 h-3 text-gray-400 mb-[1px]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 2h4v6h5v4h-5v10h-4V12H5V8h5V2z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <p class="text-xs md:text-sm text-yellow-700 mt-2 flex items-center justify-center gap-1 font-semibold break-words pb-4">
                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    {{ Str::limit($deceased->location ?? 'Ubicación reservada', 50) }}
                </p>
            </div>
        </div>

        <div class="sticky top-16 md:top-20 z-50 bg-white/95 backdrop-blur-md border-t border-b border-gray-100 shadow-md w-full">
            
            <div class="flex items-center justify-center px-3 md:px-8 py-0 h-14 w-full">
                
                <div class="flex h-full items-center mx-auto gap-4 md:gap-10 w-full justify-center"> 
                    
                    <button @click="activeTab = 'bio'" 
                            :class="activeTab === 'bio' ? 'text-yellow-700 border-b-4 border-yellow-600' : 'text-gray-400 hover:text-gray-600'"
                            class="h-full px-2 md:px-3 text-xs md:text-sm font-bold uppercase tracking-wider transition-all flex items-center">
                        Biografía
                    </button>
                    
                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'text-yellow-700 border-b-4 border-yellow-600' : 'text-gray-400 hover:text-gray-600'"
                            class="h-full px-2 md:px-3 text-xs md:text-sm font-bold uppercase tracking-wider transition-all flex items-center gap-1 md:gap-2">
                        Fotos
                        </button>

                    <button @click="activeTab = 'share'" 
                            :class="activeTab === 'share' ? 'text-yellow-700 border-b-4 border-yellow-600' : 'text-gray-400 hover:text-gray-600'"
                            class="h-full px-2 md:px-3 text-xs md:text-sm font-bold uppercase tracking-wider transition-all flex items-center gap-1">
                        <span class="hidden sm:inline">Compartir</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-10 bg-white min-h-[50vh] w-full">
            
            <div x-show="activeTab === 'bio'" x-transition:enter.duration.300ms>
                <div class="max-w-3xl mx-auto">
                    <div class="max-h-[60vh] overflow-y-auto custom-scrollbar pr-2">
                        <div class="prose prose-sm md:prose-lg prose-stone max-w-none text-gray-600 leading-relaxed text-justify font-light break-words">
                            <span class="text-5xl float-left mr-3 font-serif text-yellow-500 leading-none -mt-1">"</span>
                            {!! nl2br(e($deceased->biography ?: 'La biografía de este ser querido aún no ha sido añadida.')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'photos'" x-transition:enter.duration.300ms style="display: none;">
                <div class="max-h-[70vh] overflow-y-auto custom-scrollbar pr-1">
                    @if($deceased->photos->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach($deceased->photos as $index => $photo)
                                <div class="aspect-square relative overflow-hidden bg-gray-100 cursor-pointer group rounded-lg shadow-sm border border-gray-100"
                                     @click="openLightboxByIndex({{ $index }})">
                                    <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-300"><p>Álbum vacío.</p></div>
                    @endif
                </div>
            </div>

            <div x-show="activeTab === 'share'" x-transition:enter.duration.300ms style="display: none;" class="max-w-md mx-auto text-center" x-data="{ copied: false }">
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6">Compartir</h3>
                <div class="bg-white p-4 rounded-xl shadow-lg border border-gray-100 inline-block mb-6">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($currentUrl) }}&color=000000&bgcolor=ffffff" alt="QR" class="w-32 h-32 md:w-40 md:h-40 mx-auto">
                </div>
                <div class="relative w-full">
                    <div class="flex items-center gap-0 shadow-sm rounded-lg overflow-hidden border border-gray-200">
                        <input type="text" readonly value="{{ $currentUrl }}" class="w-full bg-gray-50 border-none text-gray-600 text-xs md:text-sm p-4 focus:ring-0 truncate">
                        <button @click="navigator.clipboard.writeText('{{ $currentUrl }}'); copied = true; setTimeout(() => copied = false, 2000);" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 transition-colors flex items-center gap-2 shrink-0 h-full">
                            <span x-show="!copied" class="text-xs md:text-sm">COPIAR</span>
                            <span x-show="copied"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="bg-gray-50 border-t border-gray-100 py-8 text-center px-4 w-full">
            <p class="text-xs text-gray-400 font-medium">&copy; {{ date('Y') }} Camposanto Jardín de los Recuerdos.</p>
        </div>
    </div>

    <div x-show="lightboxOpen" x-transition.opacity.duration.300ms class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center backdrop-blur-sm" style="display: none;" @keydown.window.escape="closeLightbox()">
        <button @click="closeLightbox()" class="absolute top-4 right-4 text-white/60 hover:text-white z-50 p-2"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        <button @click.stop="prevImage()" class="absolute left-2 top-1/2 -translate-y-1/2 text-white/60 p-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg></button>
        <div class="relative w-full h-full flex items-center justify-center p-2" @click.outside="closeLightbox()"><img :src="currentImage" class="max-h-full max-w-full object-contain shadow-2xl"></div>
        <button @click.stop="nextImage()" class="absolute right-2 top-1/2 -translate-y-1/2 text-white/60 p-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg></button>
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/80 text-sm font-bold bg-white/10 px-4 py-1.5 rounded-full"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f9fafb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #ca8a04; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('galleryComponent', (allImages) => ({
                activeTab: 'bio',
                lightboxOpen: false,
                images: allImages, 
                currentIndex: 0,
                get currentImage() { return this.images[this.currentIndex]; },
                openLightbox(src) { if(!src) return; const index = this.images.indexOf(src); this.currentIndex = index !== -1 ? index : 0; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
                openLightboxByIndex(index) { this.currentIndex = index; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
                closeLightbox() { this.lightboxOpen = false; document.body.style.overflow = ''; },
                nextImage() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
                prevImage() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
            }))
        })
    </script>
</div>