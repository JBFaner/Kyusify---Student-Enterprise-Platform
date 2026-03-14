<x-seller-layout>
    <x-slot name="header">
        Feedback & Ratings
    </x-slot>

    <!-- Rating Overview Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Summary Card -->
        <div class="bg-white dark:bg-[#13111C] p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Average Rating</h3>
            <div class="text-6xl font-black text-gray-900 dark:text-white mb-4">{{ number_format($averageRating, 1) }}</div>
            <div class="flex text-yellow-400 text-2xl mb-3 gap-1">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating))
                        <i class="fa-solid fa-star"></i>
                    @elseif($i == ceil($averageRating) && fmod($averageRating, 1) !== 0.0)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @else
                        <i class="fa-regular fa-star text-gray-300 dark:text-gray-600"></i>
                    @endif
                @endfor
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Based on <strong>{{ $totalReviews }}</strong> reviews</p>
        </div>

        <!-- Breakdown Card (Span 2 cols on md) -->
        <div class="md:col-span-2 bg-white dark:bg-[#13111C] p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6 text-lg"><i class="fa-solid fa-chart-bar text-violet-500 mr-2"></i> Rating Distribution</h3>
            
            <div class="space-y-4">
                @for($star = 5; $star >= 1; $star--)
                    <div class="flex items-center gap-4 text-sm font-medium">
                        <span class="w-16 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $star }} Stars</span>
                        <div class="flex-1 h-3 bg-gray-100 dark:bg-gray-800/50 rounded-full overflow-hidden border border-gray-200/50 dark:border-gray-700/50">
                            <div class="h-full bg-violet-500 rounded-full" style="width: {{ $ratingPercentages[$star] }}%"></div>
                        </div>
                        <span class="w-12 text-right text-gray-500 dark:text-gray-400">{{ $ratingBreakdown[$star] }}</span>
                    </div>
                @endfor
            </div>
        </div>

    </div>

    <!-- Rating Analytics / Leaderboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Most Reviewed -->
        <div class="bg-white dark:bg-[#13111C] p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center justify-between">
                <span>Most Reviewed</span>
                <i class="fa-solid fa-fire text-orange-500"></i>
            </h3>
            <ul class="space-y-3">
                @forelse($mostReviewed as $item)
                    <li class="flex items-center justify-between group">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate pr-4 group-hover:text-violet-600 transition-colors cursor-pointer">{{ $item->name }}</span>
                        <span class="text-xs font-bold bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-lg text-gray-600 dark:text-gray-400 shrink-0">{{ $item->reviews_count }} reviews</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 italic text-center py-4">No data available</li>
                @endforelse
            </ul>
        </div>

        <!-- Highest Rated -->
        <div class="bg-white dark:bg-[#13111C] p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center justify-between">
                <span>Highest Rated</span>
                <i class="fa-solid fa-arrow-trend-up text-green-500"></i>
            </h3>
            <ul class="space-y-3">
                @forelse($highestRated as $item)
                    <li class="flex items-center justify-between group">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate pr-4 group-hover:text-violet-600 transition-colors cursor-pointer">{{ $item->name }}</span>
                        <span class="text-xs font-bold bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-lg flex items-center gap-1 shrink-0"><i class="fa-solid fa-star text-[10px]"></i> {{ number_format($item->reviews_avg_rating, 1) }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 italic text-center py-4">No data available</li>
                @endforelse
            </ul>
        </div>

        <!-- Lowest Rated -->
        <div class="bg-white dark:bg-[#13111C] p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center justify-between">
                <span>Lowest Rated</span>
                <i class="fa-solid fa-arrow-trend-down text-red-500"></i>
            </h3>
            <ul class="space-y-3">
                @forelse($lowestRated as $item)
                    <li class="flex items-center justify-between group">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate pr-4 group-hover:text-violet-600 transition-colors cursor-pointer">{{ $item->name }}</span>
                        <span class="text-xs font-bold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-lg flex items-center gap-1 shrink-0"><i class="fa-solid fa-star text-[10px]"></i> {{ number_format($item->reviews_avg_rating, 1) }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 italic text-center py-4">No data available</li>
                @endforelse
            </ul>
        </div>
    </div>


    <!-- Product Reviews Table -->
    <div class="bg-white dark:bg-[#13111C] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white shrink-0"><i class="fa-solid fa-comments text-violet-500 mr-2"></i> Recent Customer Feedback</h2>

            <form action="{{ route('seller.feedback.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..." class="w-full pl-9 pr-3 py-2 text-sm bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white">
                </div>
                <select name="rating_filter" class="py-2 pl-3 pr-8 text-sm bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-violet-500 focus:border-violet-500 text-gray-900 dark:text-white" onchange="this.form.submit()">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating_filter') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating_filter') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ request('rating_filter') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ request('rating_filter') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ request('rating_filter') == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
                <button type="submit" class="hidden sm:block bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm transition-colors cursor-pointer font-medium">Apply</button>
                @if(request()->filled('search') || request()->filled('rating_filter'))
                    <a href="{{ route('seller.feedback.index') }}" class="text-red-500 hover:text-red-600 text-sm font-medium shrink-0 ml-1">Clear</a>
                @endif
            </form>
        </div>

        @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Customer & Date</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Product</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">Rating</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 w-1/3">Feedback</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors {{ $review->is_reported ? 'opacity-60' : '' }}">
                                
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold mr-3 shrink-0">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="font-medium text-gray-800 dark:text-gray-200 line-clamp-1 max-w-[200px]" title="{{ $review->product->name }}">
                                        {{ $review->product->name }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star text-gray-300 dark:text-gray-600"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    @if($review->is_reported)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-bold rounded uppercase">Reported</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if($review->comment)
                                        <p class="text-gray-600 dark:text-gray-300 italic">"{{ $review->comment }}"</p>
                                    @else
                                        <span class="text-gray-400 italic text-xs">No comment left.</span>
                                    @endif

                                    @if($review->seller_reply)
                                        <!-- Reply Block -->
                                        <div class="mt-3 pl-3 border-l-2 border-violet-400 bg-violet-50 dark:bg-violet-900/10 p-2 rounded-r-lg">
                                            <div class="flex items-center gap-1 mb-1">
                                                <i class="fa-solid fa-reply text-violet-500 text-[10px]"></i>
                                                <span class="text-xs font-bold text-violet-700 dark:text-violet-400">Your Reply</span>
                                            </div>
                                            <p class="text-xs text-gray-700 dark:text-gray-300">{{ $review->seller_reply }}</p>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-5 whitespace-nowrap text-right" x-data="{ openReply: false }">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$review->seller_reply)
                                            <!-- Reply Toggle Button -->
                                            <button @click="openReply = !openReply" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 dark:bg-transparent dark:border-gray-700 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-lg transition-colors">
                                                Reply
                                            </button>
                                        @endif

                                        @if(!$review->is_reported)
                                            <!-- Report Form -->
                                            <form action="{{ route('seller.feedback.report', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to report this review to the admins?');">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Report Inappropriate">
                                                    <i class="fa-solid fa-flag"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Inline Reply Form (Alpine toggled) -->
                                    <div x-show="openReply" style="display: none;" class="mt-3 text-left w-full min-w-[250px] bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-200 dark:border-gray-700" x-transition>
                                        <form action="{{ route('seller.feedback.reply', $review->id) }}" method="POST">
                                            @csrf
                                            <textarea name="seller_reply" rows="2" class="w-full text-xs p-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#13111C] focus:ring-violet-500 focus:border-violet-500 mb-2" placeholder="Write a public reply..." required></textarea>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="openReply = false" class="px-2 py-1 text-xs text-gray-500 hover:text-gray-700 font-medium">Cancel</button>
                                                <button type="submit" class="px-3 py-1 text-xs font-bold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors shadow-sm">Post Reply</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="px-8 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $reviews->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4 tracking-tighter shadow-inner">
                    <span class="text-4xl">🌟</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No feedback yet</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">When customers purchase and rate your products, their reviews will appear here. Focus on delivering great items to get your first 5-star review!</p>
            </div>
        @endif
    </div>

</x-seller-layout>
