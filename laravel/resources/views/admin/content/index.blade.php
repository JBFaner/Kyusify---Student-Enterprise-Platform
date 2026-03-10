<x-admin-layout>
    <x-slot name="header">
        Content Management
    </x-slot>

    @include('admin.content.tabs')

    <div class="mb-6 mt-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Homepage Content</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update the text displayed on the public landing page.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-8" x-data="{
        heroTitle: '{{ addslashes(old('hero_title', $settings['hero_title'] ?? 'The Marketplace for QCU Entrepreneurs')) }}',
        heroSubtitle: '{{ addslashes(old('hero_subtitle', $settings['hero_subtitle'] ?? 'Discover unique products, services, and innovations crafted by Quezon City University students.')) }}',
        aboutText: '{{ addslashes(old('about_text', $settings['about_text'] ?? 'Support the local QCU student community by purchasing directly from student-run enterprises.')) }}',
        announcement: '{{ addslashes(old('homepage_announcement', $settings['homepage_announcement'] ?? '')) }}',
        ctaText: '{{ addslashes(old('homepage_banner_cta_text', $settings['homepage_banner_cta_text'] ?? '')) }}'
    }">

        <!-- Left Column: Form -->
        <div class="w-full xl:w-1/2">
            <div class="bg-white dark:bg-[#13111C] p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-800/60">
                <form method="POST" action="{{ route('admin.content.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Title <span class="text-red-500">*</span></label>
                        <input type="text" id="hero_title" name="hero_title" x-model="heroTitle" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('hero_title') border-red-500 @enderror">
                        <p class="mt-1.5 text-xs text-gray-500">The main prominent text displayed at the top of the landing page.</p>
                        @error('hero_title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Subtitle <span class="text-red-500">*</span></label>
                        <textarea id="hero_subtitle" name="hero_subtitle" x-model="heroSubtitle" rows="3" required class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('hero_subtitle') border-red-500 @enderror"></textarea>
                        <p class="mt-1.5 text-xs text-gray-500">The supporting text below the main title.</p>
                        @error('hero_subtitle')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="about_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">About Section Text</label>
                        <textarea id="about_text" name="about_text" x-model="aboutText" rows="4" class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow resize-none @error('about_text') border-red-500 @enderror"></textarea>
                        <p class="mt-1.5 text-xs text-gray-500">Optional text for an 'About' or 'Why Buy from Us' section.</p>
                        @error('about_text')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

            <hr class="border-gray-200 dark:border-gray-800 my-8">

            <div class="mb-6">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Announcements & Banners</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">Add an announcement bar or a promotional banner to the homepage.</p>
            </div>

                    <div>
                        <label for="homepage_announcement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Homepage Announcement (Top Bar)</label>
                        <input type="text" id="homepage_announcement" name="homepage_announcement" x-model="announcement" placeholder="e.g. Free shipping on all student enterprise orders this week!" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow @error('homepage_announcement') border-red-500 @enderror">
                        <p class="mt-1.5 text-xs text-gray-500">Displays a small alert bar at the very top of the site. Leave empty to hide.</p>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Promotional Banner Image</label>
                        @if(isset($settings['homepage_banner_image']) && $settings['homepage_banner_image'])
                            <div class="mb-3 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 max-w-sm relative group">
                                <img src="{{ Storage::url($settings['homepage_banner_image']) }}" alt="Current Banner" class="w-full h-auto object-cover">
                                <button type="button" onclick="if(confirm('Delete banner image?')) { document.getElementById('delete-banner-form').submit(); }" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        @else
                            <div class="mb-3 rounded-xl overflow-hidden border border-dashed border-gray-300 dark:border-gray-700 max-w-sm h-32 flex items-center justify-center bg-gray-50 dark:bg-gray-800/50">
                                <span class="text-sm font-medium text-gray-400">No Image Currently</span>
                            </div>
                        @endif
                        <input type="file" id="banner_input" name="homepage_banner_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
                        <p class="mt-1.5 text-xs text-gray-500">Upload an image to display a promo banner above the Discover section.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="homepage_banner_cta_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banner CTA Button Text</label>
                            <input type="text" id="homepage_banner_cta_text" name="homepage_banner_cta_text" x-model="ctaText" placeholder="e.g. Shop the Sale" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow">
                        </div>
                <div>
                    <label for="homepage_banner_cta_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banner CTA Link</label>
                    <input type="url" id="homepage_banner_cta_link" name="homepage_banner_cta_link" value="{{ old('homepage_banner_cta_link', $settings['homepage_banner_cta_link'] ?? '') }}" placeholder="https://..." class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#0B0A0F] border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white transition-shadow">
                </div>
            </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-medium shadow-lg shadow-violet-500/30 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Live Previews -->
        <div class="w-full xl:w-1/2 mt-8 xl:mt-0 flex flex-col gap-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Live Previews
            </h3>

            <!-- Browser Mockup Window -->
            <div class="bg-gray-200 dark:bg-gray-800 rounded-t-xl p-3 flex gap-2 items-center border-b border-gray-300 dark:border-gray-700">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                <div class="ml-4 bg-white/50 dark:bg-black/20 text-xs px-3 py-1 rounded text-gray-600 dark:text-gray-400 font-medium">kyusify.com</div>
            </div>

            <!-- Previews Container (Scrollable Sandbox) -->
            <div class="bg-[#000] text-white border-l border-r border-b border-gray-200 dark:border-gray-800 rounded-b-xl overflow-hidden shadow-2xl h-[600px] overflow-y-auto relative font-['Outfit']">
                
                <!-- Navbar Mock -->
                <nav class="sticky top-0 z-50 w-full bg-black/80 backdrop-blur-md border-b border-white/10 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white p-0.5"><img src="{{ asset('images/kyusify-logo.png') }}" class="w-full h-full object-contain"></div>
                        <span class="font-bold text-xl">Kyusify</span>
                    </div>
                </nav>

                <!-- Announcement Mock -->
                <div x-show="announcement !== ''" class="bg-violet-600 text-white text-xs font-medium py-1.5 px-4 text-center" x-text="announcement"></div>

                <!-- Homepage Hero Mock -->
                <div class="relative py-20 bg-violet-950 text-center px-6 overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(rgba(124,58,237,0.15)_1px,transparent_1px)] bg-[size:32px_32px] opacity-40"></div>
                    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-3/4 max-w-xl h-48 bg-violet-600/30 blur-[80px] rounded-full z-0"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-[1.1] mb-4" x-text="heroTitle || 'Heading'"></h1>
                        <p class="text-sm sm:text-base text-gray-400 max-w-xl mx-auto mb-6" x-text="heroSubtitle || 'Subtitle'"></p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <div class="bg-violet-600 text-white font-bold px-6 py-2.5 rounded-lg border-2 border-black shadow-[2px_2px_0px_black] text-sm">Start selling today</div>
                        </div>

                        <div x-show="aboutText !== ''" class="mt-10 inline-block bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-md max-w-lg mx-auto text-left">
                            <p class="text-xs text-gray-300" x-text="aboutText"></p>
                        </div>
                    </div>
                </div>

                <!-- Discover Banner Mock -->
                <div class="bg-[#FAFAFA] dark:bg-black p-6">
                    <div class="max-w-3xl mx-auto">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Discover</h2>
                            <p class="text-xs text-gray-500">Promotional Banner Preview</p>
                        </div>
                        
                        <div class="rounded-2xl overflow-hidden relative h-48 bg-gray-900 shadow-xl border border-gray-800">
                            @if(isset($settings['homepage_banner_image']) && $settings['homepage_banner_image'])
                                <img id="mock_banner_img" src="{{ Storage::url($settings['homepage_banner_image']) }}" alt="Promo" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div id="mock_banner_placeholder" class="absolute inset-0 flex items-center justify-center text-gray-600">
                                    <span>[ Image Placeholder ]</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <template x-if="ctaText !== ''">
                                    <div class="inline-flex items-center gap-2 bg-white text-black font-bold px-4 py-2 rounded-lg text-sm">
                                        <span x-text="ctaText"></span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8">
                            <div class="h-32 bg-gray-200 dark:bg-gray-900 rounded-xl animate-pulse"></div>
                            <div class="h-32 bg-gray-200 dark:bg-gray-900 rounded-xl animate-pulse"></div>
                            <div class="h-32 bg-gray-200 dark:bg-gray-900 rounded-xl animate-pulse"></div>
                            <div class="h-32 bg-gray-200 dark:bg-gray-900 rounded-xl animate-pulse"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Hidden form to delete the banner image -->
    <form id="delete-banner-form" action="{{ route('admin.content.banner.destroy') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Update mock banner live if file selected
        document.getElementById('banner_input').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let mockImg = document.getElementById('mock_banner_img');
                    if(mockImg) {
                        mockImg.src = e.target.result;
                    } else {
                        const placeholder = document.getElementById('mock_banner_placeholder');
                        if(placeholder) {
                            placeholder.outerHTML = `<img id="mock_banner_img" src="${e.target.result}" alt="Promo" class="absolute inset-0 w-full h-full object-cover">`;
                        }
                    }
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</x-admin-layout>
