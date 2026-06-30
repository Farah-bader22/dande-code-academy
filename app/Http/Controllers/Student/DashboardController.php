<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Assignment;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض الصفحة الرئيسية لداشبورد الطالب
     */
    public function index()
    {
        // 1. جلب بيانات الطفل الحالي
        $student = auth()->user();

        // 2. جلب المسارات والتحديات
        $tracks = Track::orderBy('id', 'asc')->get();
        $assignments = Assignment::latest()->get();

        // 3. جلب الجلسات المجدولة من الموديل الصحيح المربوط بـ لوحة المعلمة
        $sessions = \App\Models\Session::orderBy('id', 'desc')
            ->get()
            ->map(function($session) {
                return [
                    'id'       => $session->id,
                    'title'    => $session->title,
                    'type'     => $session->track_type ?? $session->type ?? 'plugged',
                    'time'     => $session->scheduled_at ? \Carbon\Carbon::parse($session->scheduled_at)->format('Y-m-d g:i a') : 'مجدولة قريباً',
                    'location' => $session->location_or_link ?? $session->location ?? 'قاعة الأنشطة المنطقية',
                ];
            })->toArray();

        // 4. جلب آخر تقييمات وملاحظات مخصصة لهذا الطالب الحالي من جدول الـ feedback
        $feedbacks = \App\Models\Feedback::with('teacher')
            ->where('student_id', $student->id)
            ->latest()
            ->get()
            ->map(function($fb) {
                return [
                    'id'           => $fb->id,
                    'teacher_name' => $fb->teacher->name ?? 'المعلمة المبدعة',
                    'comment'      => $fb->comment,
                    'date'         => $fb->created_at ? $fb->created_at->diffForHumans() : 'منذ فترة',
                ];
            })->toArray();

        // 5. الإحصائيات وجلب المصادر التعليمية للأطفال
        $stats = [
            'completed_lessons' => (int) $tracks->sum('lessons_completed_count'),
            'total_lessons'     => (int) $tracks->sum('total_lessons'),
            'challenges_solved' => 24,
            'coding_hours'      => 12.5,
        ];

        // جلب المصادر التعليمية الحقيقية من قاعدة البيانات لتعرض للطفل
        $resources = \App\Models\Resource::latest()->get();

        // 6. الـ Return النهائي للـ Vue ممرر إليه كل الـ Props كاملة وجاهزة
        return Inertia::render('Dashboard/StudentDashboard', [
            'auth' => [
                'user' => $student
            ],
            'tracks'      => $tracks,
            'assignments' => $assignments,
            'sessions'    => $sessions,
            'stats'       => $stats,
            'feedbacks'   => $feedbacks,
            'resources'   => $resources
        ]);
    }

    /**
     * تشغيل اللعبة
     */
    public function playAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);

        return Inertia::render('Student/PlayGame', [
            'assignment' => $assignment
        ]);
    }


    /**
     * دالة حفظ حل الطالب وزيادة نقاطه تلقائياً
     */
    public function submitAssignment(Request $request, $id)
    {
        // 1. التحقق من النتيجة (بافتراض أنكِ ترسلين الـ score من الـ Vue)
        $request->validate([
            'score' => 'required|numeric'
        ]);

        // 2. حفظ التسليم في قاعدة البيانات
        \App\Models\AssignmentSubmission::create([
            'assignment_id' => $id,
            'student_id'    => auth()->id(),
            'score'         => $request->score,
            'status'        => 'completed',
        ]);

  
        $student = auth()->user();
        $student->increment('points', 20); // زيادة 20 نقطة كهدية على الحل

        // 4. العودة للداشبورد مع رسالة نجاح
        return redirect()->route('student.dashboard')->with('message', 'أحسنت! تم إضافة 20 نقطة لرصيدك! 🎉');
    }
}
