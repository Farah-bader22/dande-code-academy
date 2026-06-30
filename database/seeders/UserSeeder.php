<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. حساب المعلم (المدرب) - مربوط بالصف 1
        User::create([
            'name' => 'المعلمة فرح',
            'username' => 'farah_teacher',
            'email' => 'teacher@dandecode.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'classroom_id' => 1, // 🌟 ربط ذكي بالصف الأساسي
        ]);

        // 2. حساب الطالب (الطفل) - مربوط بالصف 1
        User::create([
            'name' => 'أحمد المبرمج الصغير',
            'username' => 'ahmed_hero',
            'email' => 'student@dandecode.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'classroom_id' => 1, // 🌟 ربط ذكي بالصف الأساسي
        ]);

        // 3. حساب ولي الأمر
        User::create([
            'name' => 'ولي الأمر محمد',
            'username' => 'parent_account',
            'email' => 'parent@dandecode.com',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            // ولي الأمر عادة ما بتبعش لصف دراسي، فبنترك الـ classroom_id تبعه null أو حسب تصميم الجداول عندك
        ]);
    }
}
