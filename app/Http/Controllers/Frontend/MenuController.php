<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::with(['products' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('frontend.menu', compact('categories'));
    }
}
