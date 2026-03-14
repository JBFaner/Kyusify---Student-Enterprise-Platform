<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Display the feedback dashboard and ratings analytics.
     */
    public function index(Request $request)
    {
        $enterprise = auth()->user()->enterprise;
        
        if (!$enterprise) {
            abort(404, 'Enterprise not found.');
        }

        // Base query for reviews belonging to this seller's products
        $productIds = $enterprise->products()->pluck('id');
        $baseQuery = Review::whereIn('product_id', $productIds);

        // 1. Rating Overview Statistics
        $totalReviews = $baseQuery->count();
        $averageRating = $totalReviews > 0 ? round((float) $baseQuery->avg('rating'), 1) : 0;

        // Breakdown (1 to 5 stars)
        $ratingBreakdown = (clone $baseQuery)->select('rating', DB::raw('count(*) as count'))
                                      ->groupBy('rating')
                                      ->pluck('count', 'rating')
                                      ->toArray();
        
        // Ensure all 1-5 keys exist
        $breakdownDefaults = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach($ratingBreakdown as $star => $count) {
            $breakdownDefaults[$star] = $count;
        }
        $ratingBreakdown = $breakdownDefaults;

        // Calculate percentages
        $ratingPercentages = [];
        foreach($ratingBreakdown as $star => $count) {
            $ratingPercentages[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
        }

        // 2. Product Reviews (Paginated listing with search & filters)
        $reviewsQuery = (clone $baseQuery)->with(['user', 'product']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $reviewsQuery->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('rating_filter')) {
            $reviewsQuery->where('rating', $request->get('rating_filter'));
        }

        $reviews = $reviewsQuery->latest()->paginate(10)->withQueryString();

        // 3. Rating Analytics
        // Most reviewed products
        $mostReviewed = Product::where('enterprise_id', $enterprise->id)
            ->withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take(3)
            ->get();

        // Highest rated products (min 1 review)
        $highestRated = Product::where('enterprise_id', $enterprise->id)
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>', 0)
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(3)
            ->get();

        // Lowest rated products (min 1 review)
        $lowestRated = Product::where('enterprise_id', $enterprise->id)
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>', 0)
            ->orderBy('reviews_avg_rating', 'asc')
            ->take(3)
            ->get();

        return view('seller.feedback.index', compact(
            'totalReviews', 
            'averageRating', 
            'ratingBreakdown', 
            'ratingPercentages',
            'reviews',
            'mostReviewed',
            'highestRated',
            'lowestRated'
        ));
    }

    /**
     * Seller replies to a product review.
     */
    public function reply(Request $request, Review $review)
    {
        // Authorize that the review belongs to the seller's product
        $enterpriseId = auth()->user()->enterprise->id;
        if ($review->product->enterprise_id !== $enterpriseId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'seller_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'seller_reply' => $request->seller_reply
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }

    /**
     * Report an inappropriate review.
     */
    public function report(Review $review)
    {
        // Authorize that the review belongs to the seller's product
        $enterpriseId = auth()->user()->enterprise->id;
        if ($review->product->enterprise_id !== $enterpriseId) {
            abort(403, 'Unauthorized action.');
        }

        $review->update([
            'is_reported' => true
        ]);

        return redirect()->back()->with('success', 'Review has been reported for moderation.');
    }
}
