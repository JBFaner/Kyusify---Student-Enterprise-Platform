<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0 || $users->count() === 0) {
            $this->command->info('No products or users found. Skipping ReviewSeeder.');
            return;
        }

        $comments = [
            'Great quality! The material is really nice and it fits perfectly.',
            'Good product for the price. Delivery was quick on campus.',
            'Genuinely one of the best I\'ve ever seen. I was looking for exactly this kind of item.',
            'I love it!',
            'Average quality, but acceptable given the price point.',
            'Not what I expected based on the photos. A bit disappointed.',
            'Highly recommended! Support local student enterprises!',
            'Perfect for my needs. Great communication from the seller.',
            'Five stars! Quick response and hassle-free transaction.',
            'A bit pricey for the quality, but it\'s ok.',
        ];

        $replies = [
            'Thank you so much for your support! We are glad you liked it.',
            'We appreciate your feedback and hope to serve you again soon.',
            'Thanks for purchasing from our store! Have a great semester!',
            'Sorry to hear it didn\'t meet your expectations. Please contact us so we can resolve this.',
        ];

        foreach ($products as $product) {
            // Decide to give this product anywhere from 0 to 8 reviews
            $numReviews = rand(0, min(8, $users->count()));
            
            if ($numReviews === 0) continue;

            $shuffledUsers = $users->shuffle()->take($numReviews);

            foreach ($shuffledUsers as $user) {
                // Bias towards higher ratings for a positive feel
                $rating = rand(1, 10) > 8 ? rand(2, 4) : rand(4, 5); 
                
                $hasComment = rand(1, 10) > 2; // 80% chance of comment
                $hasReply = $hasComment && rand(1, 10) > 6; // 40% chance of reply if commented

                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'comment' => $hasComment ? $comments[array_rand($comments)] : null,
                    'seller_reply' => $hasReply ? $replies[array_rand($replies)] : null,
                    'is_reported' => rand(1, 100) > 95, // 5% chance of being reported
                    'created_at' => now()->subDays(rand(1, 60))->subHours(rand(1, 24)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
