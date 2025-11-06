<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function created(User $user)
    {
        Log::info('UserObserver: New user created with ID ' . $user->id);
        $this->updateReports();
    }

    private function updateReports()
    {
        Log::info('UserObserver: Updating reports');

        // update users report
        $usersReport = Report::where('type', 'users')->first();
        if ($usersReport) {
            $usersReport->total = User::count();
            $usersReport->generated_at = now();
            $usersReport->save();
            Log::info('UserObserver: Users report updated to total ' . $usersReport->total);
        } else {
            Log::info('UserObserver: Users report not found');
        }

        // update activity report
        $activityReport = Report::where('type', 'activity')->first();
        if ($activityReport) {
            $activityReport->total = \App\Models\Post::count() + \App\Models\Comment::count() + \App\Models\Like::count();
            $activityReport->generated_at = now();
            $activityReport->save();
            Log::info('UserObserver: Activity report updated to total ' . $activityReport->total);
        } else {
            Log::info('UserObserver: Activity report not found');
        }
    }
}
