<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // تنظيف الجدول أولاً عشان لو شغلتي الـ Seeder أكتر من مرة ما تتكرر البيانات في قاعدة البيانات
        Resource::truncate();

        Resource::create([
            'title' => 'Introduction to Loops (Unplugged Activity)',
            'description' => 'Printable arrow cards and loop grids to teach children repetition concepts physically without devices.',
            'track_type' => 'unplugged',
            'resource_type' => 'worksheet',
            'file_path' => '/uploads/loops.pdf', // 🌟 المسار الحقيقي الأول
        ]);

        Resource::create([
            'title' => 'My First If-Condition Game',
            'description' => 'A comprehensive teaching guide containing interactive classroom games for conditional logic.',
            'track_type' => 'unplugged',
            'resource_type' => 'guide',
            'file_path' => '/uploads/conditions.pdf', // 🌟 المسار الحقيقي الثاني
        ]);

        Resource::create([
            'title' => 'Variables & Memory Boxes Lesson Plan',
            'description' => 'Step-by-step teacher instructions to explain how computer memory works using tangible physical boxes.',
            'track_type' => 'plugged',
            'resource_type' => 'lesson_plan',
            'file_path' => '/uploads/variables.pdf', // 🌟 المسار الحقيقي الثالث
        ]);
    }
}
