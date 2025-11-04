<?php

namespace App\Observers;

use App\Models\Like;
use App\Models\Report;

class LikeObserver
{
    public function created(Like $like)
    {
        $this->updateReports();
    }

    public function deleted(Like $like)
    {
        $this->updateReports();
    }

    private function updateReports()
    {
        // Update activity report (likes are part of activity)
        $activityReport = Report::where('type', 'activity')->first();
        if ($activityReport) {
            $activityReport->total = \App\Models\Post::count() + \App\Models\Comment::count() + Like::count();
            $activityReport->generated_at = now();
            $activityReport->save();
        }
    }
}
