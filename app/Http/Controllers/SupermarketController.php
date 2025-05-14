<?php

namespace App\Http\Controllers;

use App\Models\Supermarket;
use Illuminate\Http\Request;

class SupermarketController extends Controller
{
    public function index()
    {
        $supermarkets = Supermarket::all();
        return view('map', compact('supermarkets'));
    }

    public function report(Request $request, $id)
    {
        $supermarket = Supermarket::findOrFail($id);
        $supermarket->reports()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        return back()->with('success', 'Report submitted successfully!');
    }
}
