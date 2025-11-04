<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'total',
        'status',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $report->calculateTotal();
        });

        static::updating(function ($report) {
            if ($report->isDirty('type') || $report->isDirty('status')) {
                $report->calculateTotal();
            }
        });
    }

    public function calculateTotal()
    {
        switch ($this->type) {
            case 'posts':
                $this->total = Post::count();
                break;
            case 'users':
                $this->total = User::count();
                break;
            case 'comments':
                $this->total = Comment::count();
                break;
            case 'activity':
                $this->total = Post::count() + Comment::count() + Like::count();
                break;
            default:
                $this->total = 0;
        }
    }
}
