<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function created(Post $post)
    {
        Log::info('PostObserver: New post created with ID ' . $post->id);
        $this->updateReports();
    }

    private function updateReports()
    {
        Log::info('PostObserver: Updating reports');

        // update posts report
        $postsReport = Report::where('type', 'posts')->first();
        if ($postsReport) {
            $postsReport->total = Post::count();
            $postsReport->generated_at = now();
            $postsReport->save();
            Log::info('PostObserver: Posts report updated to total ' . $postsReport->total);
        } else {
            Log::info('PostObserver: Posts report not found');
        }

        // update activity report
        $activityReport = Report::where('type', 'activity')->first();
        if ($activityReport) {
            $activityReport->total = Post::count() + \App\Models\Comment::count() + \App\Models\Like::count();
            $activityReport->generated_at = now();
            $activityReport->save();
            Log::info('PostObserver: Activity report updated to total ' . $activityReport->total);
        } else {
            Log::info('PostObserver: Activity report not found');
        }
    }
}
