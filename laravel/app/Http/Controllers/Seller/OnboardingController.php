<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function complete(Request $request): JsonResponse
    {
        $enterprise = $request->user()?->enterprise;

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise profile not found.'], 404);
        }

        $enterprise->update([
            'onboarding_tour_completed' => true,
        ]);

        return response()->json(['message' => 'Onboarding tour marked as completed.']);
    }
}
