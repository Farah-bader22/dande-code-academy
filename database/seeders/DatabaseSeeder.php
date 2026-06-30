<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * 1. بنشغل الـ UserSeeder أولاً عشان ينشئ حساب المعلمة والطلاب
         * (تعديل: لازم نخلي الـ UserSeeder ينشئ اليوزرات بدون شرط الـ classroom_id أولاً أو نخليه nullable)
         */

        // لمنع التعارض.. تعالي نزرع المعلمة فوراً هان، ومنها بناخد الـ id تبعها
        $teacher = \App\Models\User::create([
            'name' => 'المعلمة فرح',
            'username' => 'farah_teacher',
            'email' => 'teacher@dandecode.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'teacher',
        ]);

        // 2. هلقيت بننشئ الصف وبنمرر له الـ teacher_id الحقيقي اللي طلبته الداتابيز!
        $classroom = \App\Models\Classroom::create([
            'id' => 1,
            'name' => 'DandeCode Smart Kids',
            'teacher_id' => $teacher->id, // 🌟 هان الحل! اعطيناه الـ teacher_id
        ]);

        // 3. بنحدث حساب المعلمة والطلاب ونربطهم بالصف اللي اتأنشأ
        $teacher->update(['classroom_id' => $classroom->id]);

        // زرع حساب الطفل وتأمين ربطه بالصف
        \App\Models\User::create([
            'name' => 'أحمد المبرمج الصغير',
            'username' => 'ahmed_hero',
            'email' => 'student@dandecode.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'student',
            'classroom_id' => $classroom->id,
        ]);

        \App\Models\User::create([
            'name' => 'ولي الأمر محمد',
            'username' => 'parent_account',
            'email' => 'parent@dandecode.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'parent',
        ]);

        /**
         * 4. استدعاء باقي الـ Seeders للمسارات والمواد التعليمية
         */
        $this->call([
            TrackSeeder::class,
            ResourceSeeder::class,
        ]);
    }
}
