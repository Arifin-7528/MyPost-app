<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->withCount('likes')->latest()->get();

        return view('home.index', compact('posts'));
    }
}
