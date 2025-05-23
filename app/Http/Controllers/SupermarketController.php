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
}
