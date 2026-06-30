<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Assignment;
use App\Models\Progress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. إنشاء حساب المعلمة (فرح بدر)
        // نستخدم updateOrCreate لتجنب تكرار البيانات إذا شغلتِ السيرفر مرتين
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@dandecode.com'],
            [
                'name' => 'Farah Bader',
                'username' => 'farah_engineer',
                'password' => Hash::make('password123'), // كلمة المرور
                'role' => 'teacher',
                'avatar' => 'https://ui-avatars.com/api/?name=Farah+Bader&background=2e7d32&color=fff',
            ]
        );

        // 2. مصفوفة بأسماء طلاب وهميين للورشة
        $students = [
            ['name' => 'ليان زكي', 'username' => 'layan_coder'],
            ['name' => 'أحمد علي', 'username' => 'ahmed_hero'],
            ['name' => 'سارة محمد', 'username' => 'sara_star'],
            ['name' => 'عمر خالد', 'username' => 'omar_tech'],
            ['name' => 'جنى يوسف', 'username' => 'jana_dev'],
        ];

        foreach ($students as $studentData) {
            User::updateOrCreate(
                ['username' => $studentData['username']],
                [
                    'name' => $studentData['name'],
                    'email' => $studentData['username'] . '@dandecode.com',
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'teacher_id' => $teacher->id, // ربط الطالب بالمعلمة فرح
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($studentData['name']) . '&background=random',
                ]
            );
        }

        // 3. إنشاء تحدي برمجي (Assignment) خاص بالمعلمة فرح
        $assignment = Assignment::updateOrCreate(
            ['title' => 'تحدي الروبوت الذكي'],
            [
                'description' => 'استخدم مربعات البرمجة لمساعدة الروبوت في الوصول للنجمة المضيئة.',
                'game_data' => [
                    'robot_start' => ['x' => 0, 'y' => 4],
                    'star_position' => ['x' => 2, 'y' => 2],
                    'difficulty' => 'easy'
                ],
                'teacher_id' => $teacher->id,
            ]
        );

        // 4. تسجيل تقدم لـ 3 طلاب (لإظهار نسبة إكمال 60% في الداشبورد)
        $selectedStudents = User::where('role', 'student')
                                ->where('teacher_id', $teacher->id)
                                ->take(3)
                                ->get();

        foreach ($selectedStudents as $student) {
            Progress::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'assignment_id' => $assignment->id
                ],
                [
                    'is_completed' => true,
                    'stars_earned' => 3,
                    'completed_at' => now(),
                ]
            );
        }
    }
}
