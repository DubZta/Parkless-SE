<?php

namespace App\Http\Controllers;

use App\Models\Supermarket;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Menampilkan ulasan untuk supermarket tertentu
    public function index($supermarketId)
    {
        $supermarket = Supermarket::findOrFail($supermarketId);
        $reviews = $supermarket->reviews()->latest()->get();

        return view('reviews.index', compact('supermarket', 'reviews'));
    }

    public function show($id) {
        $userId = auth()->id();
        $supermarket = Supermarket::where('external_id', $id)->firstOrFail();

        $reviews = Review::where('supermarket_id', $supermarket->id)->with('user')->get();
        $votes = [];
        foreach($reviews as $review) {
            $voted = $review->voters()->where('user_id', $userId)->first()?->pivot;
            $votes[$review->id] = $voted->vote ?? 0;
        }

        return view('supermarket',compact('votes', 'reviews', 'supermarket'));
    }

    public function store(Request $request) {
        $userId = auth()->id();

        Review::create([
            'user_id' => $userId,
            'supermarket_id' => $request['supermarket_id'],
            'content' => $request['content'],
            'parent_id' => $request['parent_id']
        ]);

         return redirect()->back()->with('success', 'Review berhasil dibuat!');
    }

    public function destroy($id) {
        $review = Review::findOrFail($id);

        if(!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus!');
    }

    public function vote(Request $request, $reviewId) {
        $userId = auth()->id();

        $voteValue = $request->input('vote');

        $review = Review::findOrFail($reviewId);

        $review->voters()->syncWithoutDetaching([
            $userId => ['vote' => $voteValue]
        ]);

        return redirect()->back();
    }

}
