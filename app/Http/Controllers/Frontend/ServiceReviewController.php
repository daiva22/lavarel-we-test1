<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceReviewController extends Controller
{
    public function store(Request $request, $serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $alreadyReviewed = ServiceReview::where('user_id', Auth::id())
            ->where('service_id', $service->id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'You have already reviewed this service.');
        }

        ServiceReview::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Service review submitted successfully.');
    }
}