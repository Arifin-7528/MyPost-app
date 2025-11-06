<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Report;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $this->updateReports();
    }

    private function updateReports()
    {
        // update comments report
        $commentsReport = Report::where('type', 'comments')->first();
        if ($commentsReport) {
            $commentsReport->total = Comment::count();
            $commentsReport->generated_at = now();
            $commentsReport->save();
        }

        // update activity report
        $activityReport = Report::where('type', 'activity')->first();
        if ($activityReport) {
            $activityReport->total = \App\Models\Post::count() + Comment::count() + \App\Models\Like::count();
            $activityReport->generated_at = now();
            $activityReport->save();
        }
    }
}
