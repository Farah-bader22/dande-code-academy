<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * هذا الملف يقوم بحقن المسارات التعليمية في قاعدة البيانات.
     * تم إضافة كلاس 'fas' قبل الأيقونات لضمان ظهورها عبر مكتبة Font Awesome.
     */
    public function run(): void
    {
        $tracks = [
            [
                'title' => 'Sequences & Patterns',
                'type' => 'Device-based',
                'total_lessons' => 12,
                'lessons_completed_count' => 9,
                'color' => '#8b5cf6', // اللون البنفسجي للأيقونة
                'bg_color' => '#f5f3ff', // خلفية فاتحة جداً
                'icon' => 'fas fa-code',
                'stars_earned' => 3,
                'is_unlocked' => true,
            ],
            [
                'title' => 'Loops & Repetition',
                'type' => 'Device-based',
                'total_lessons' => 10,
                'lessons_completed_count' => 4,
                'color' => '#3b82f6', // اللون الأزرق للأيقونة
                'bg_color' => '#eff6ff',
                'icon' => 'fas fa-bolt',
                'stars_earned' => 2,
                'is_unlocked' => true,
            ],
            [
                'title' => 'Sorting Games',
                'type' => 'Unplugged',
                'total_lessons' => 8,
                'lessons_completed_count' => 5,
                'color' => '#10b981', // اللون الأخضر للأيقونة
                'bg_color' => '#ecfdf5',
                'icon' => 'fas fa-book-open',
                'stars_earned' => 3,
                'is_unlocked' => true,
            ],
            [
                'title' => 'Functions & Logic',
                'type' => 'Advanced',
                'total_lessons' => 15,
                'lessons_completed_count' => 0,
                'color' => '#6b7280', // اللون الرمادي للمسارات المقفلة
                'bg_color' => '#f9fafb',
                'icon' => 'fas fa-lock',
                'stars_earned' => 0,
                'is_unlocked' => false,
            ],
        ];

        foreach ($tracks as $track) {
            Track::create($track);
        }
    }
}
