<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularProducts = \App\Models\Product::where('is_available', true)
            ->with('category')
            ->take(6)
            ->get();

        return view('frontend.home', compact('popularProducts'));
    }
}