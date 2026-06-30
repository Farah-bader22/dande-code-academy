<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\GameSession;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. إنشاء صفوف تجريبية مرتبطة بالمعلمة (تأكدي إن الـ ID = 1 هو حساب المعلمة)
        $class1 = Classroom::updateOrCreate(
            ['name' => '3A'],
            ['teacher_id' => 1]
        );

        $class2 = Classroom::updateOrCreate(
            ['name' => '3B'],
            ['teacher_id' => 1]
        );

        // 2. ربط أول 3 طلاب عندك في الـ DB بالصف الأول تلقائياً
        User::where('role', 'student')->limit(3)->update([
            'classroom_id' => $class1->id,
            'teacher_id' => 1
        ]);

        // جلب الطلاب الثلاثة المربوطين عشان نعملهم تسليمات حقيقية
        $students = User::where('classroom_id', $class1->id)->take(3)->get();

        // 3. إنشاء واجبات حقيقية مع إضافة حقل game_data الافتراضي لحل الإيرور
        $assignment1 = Assignment::updateOrCreate(
            ['title' => 'Loop Adventures', 'classroom_id' => $class1->id],
            [
                'teacher_id' => 1,
                'is_active' => true,
                'game_data' => json_encode([]) // حل الإيرور: قيمة افتراضية للعبة فارغة كـ JSON
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['title' => 'Pattern Cards', 'classroom_id' => $class1->id],
            [
                'teacher_id' => 1,
                'is_active' => true,
                'game_data' => json_encode([]) // حل الإيرور
            ]
        );

        $assignment3 = Assignment::updateOrCreate(
            ['title' => 'Sequence Challenge', 'classroom_id' => $class2->id],
            [
                'teacher_id' => 1,
                'is_active' => true,
                'game_data' => json_encode([]) // حل الإيرور
            ]
        );

        // 4. إنشاء تسليمات (Submissions) حقيقية للطلاب عشان تظهر في قسم Recent Submissions
        if ($students->count() >= 3) {
            AssignmentSubmission::updateOrCreate(
                ['student_id' => $students[0]->id, 'assignment_id' => $assignment1->id],
                ['score' => 95, 'status' => 'graded']
            );

            AssignmentSubmission::updateOrCreate(
                ['student_id' => $students[1]->id, 'assignment_id' => $assignment2->id],
                ['score' => 88, 'status' => 'graded']
            );

            AssignmentSubmission::updateOrCreate(
                ['student_id' => $students[2]->id, 'assignment_id' => $assignment3->id],
                ['score' => null, 'status' => 'pending']
            );
        }

        // 5. إنشاء حصص وجلسات قادمة حقيقية (Upcoming Sessions) في جدول الـ DB
        GameSession::updateOrCreate(
            ['title' => 'Introduction to Loops', 'classroom_id' => $class1->id],
            ['teacher_id' => 1, 'type' => 'Live Session', 'scheduled_at' => now()->addDay()->setTime(10, 0)]
        );

        GameSession::updateOrCreate(
            ['title' => 'Pattern Recognition', 'classroom_id' => $class2->id],
            ['teacher_id' => 1, 'type' => 'Workshop', 'scheduled_at' => now()->addDay()->setTime(14, 0)]
        );

        GameSession::updateOrCreate(
            ['title' => 'Functions & Logic', 'classroom_id' => $class1->id],
            ['teacher_id' => 1, 'type' => 'Live Session', 'scheduled_at' => now()->addDays(2)->setTime(11, 0)]
        );
    }
}
