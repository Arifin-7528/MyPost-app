<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480', // 20MB max
            'caption' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $type = $file->getMimeType();

        if (str_contains($type, 'image')) {
            $type = 'image';
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $path = 'images/' . $filename;
        } elseif (str_contains($type, 'video')) {
            $type = 'video';
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('videos'), $filename);
            $path = 'videos/' . $filename;
        } else {
            return back()->withErrors(['file' => 'File type not supported.']);
        }

        Post::create([
            'user_id' => auth()->user()->id,
            'type' => $type,
            'file_path' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    public function like(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function getComments(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get()->map(function ($comment) {
            return [
                'user_name' => $comment->user->name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ];
        });

        return response()->json(['comments' => $comments]);
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return response()->json([
            'comment' => [
                'user_name' => auth()->user()->name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
        ]);
    }
}
