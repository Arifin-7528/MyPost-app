<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Report::create([
            'title' => 'Laporan Total Posts',
            'description' => 'Total semua postingan',
            'type' => 'posts',
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        Report::create([
            'title' => 'Laporan Total Users',
            'description' => 'Total semua user terdaftar',
            'type' => 'users',
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        Report::create([
            'title' => 'Laporan Total Comments',
            'description' => 'Total semua komentar',
            'type' => 'comments',
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        Report::create([
            'title' => 'Laporan Total Activity',
            'description' => 'Total aktivitas keseluruhan (posts + comments + likes)',
            'type' => 'activity',
            'status' => 'completed',
            'generated_at' => now(),
        ]);
    }
}
