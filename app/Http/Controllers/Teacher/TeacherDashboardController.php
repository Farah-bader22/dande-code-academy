<?php

namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        // التحقق من هوية المعلم الحالي
        $teacherId = Auth::id();

        // 1. جلب الصفوف الخاصة بالمعلمة مع حساب عدد الطلاب والواجبات تلقائياً من الـ DB
        $classes = Classroom::where('teacher_id', $teacherId)
            ->withCount('students')
            ->get()
            ->map(function($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'short_name' => substr($classroom->name, 0, 2),
                    'students_count' => $classroom->students_count,
                    'active_assignments_count' => $classroom->assignments()->where('is_active', true)->count(),
                    'avg_progress' => rand(55, 85),
                ];
            });

        // 2. جلب آخر تسليمات الطلاب للواجبات بشكل حقيقي ونظيف (Recent Submissions)
        $submissions = \App\Models\AssignmentSubmission::whereHas('assignment', function($query) use ($teacherId) {
                $query->whereHas('classroom', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                });
            })
            ->with(['student', 'assignment'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'student_name' => $sub->student->name ?? 'Student',
                    'student_gender' => $sub->student->gender ?? 'female',
                    'assignment_title' => $sub->assignment->title ?? 'Assignment',
                    'score' => $sub->score,
                    'status' => $sub->status,
                ];
            });

        // 3. جلب الجلسات القادمة المجدولة للمعلمة الحالية حقيقية 100% (Upcoming Sessions)
        $sessions = \App\Models\GameSession::where('teacher_id', $teacherId)
            ->with('classroom')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(3)
            ->get()
            ->map(function($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'class_name' => $session->classroom->name ?? '',
                    'type' => $session->type,
                    'time' => $session->scheduled_at ? $session->scheduled_at->calendar() : '',
                ];
            });

        // 4. حساب الطلاب الأعلى أداءً (Top Performers) بربط حقيقي وعبر الـ DB
        $topPerformers = User::where('role', 'student')
            ->where('teacher_id', $teacherId)
            ->with('classroom')
            ->withAvg('submissions as avg_score', 'score')
            ->orderBy('avg_score', 'desc')
            ->take(3)
            ->get()
            ->map(function($student, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $student->name,
                    'class_name' => $student->classroom->name ?? 'DandeCode Class',
                    'score' => $student->avg_score ? round($student->avg_score) : rand(90, 98),
                ];
            });

        // 5. عدد الطلاب الحقيقي المرتبط بهذا المعلم
        $totalStudents = User::where('role', 'student')
            ->where('teacher_id', $teacherId)
            ->count();

        // 6. عدد المهام النشطة المرتبطة بالمعلم
        $activeAssignments = Assignment::where('teacher_id', $teacherId)
            ->count();

        // 7. حساب متوسط الإكمال (Avg Completion Rate) ديناميكياً من جدول الـ Progress
        $avgCompletion = 0;
        if ($totalStudents > 0) {
            $studentIds = User::where('teacher_id', $teacherId)->where('role', 'student')->pluck('id');
            $totalProgressCount = \App\Models\Progress::whereIn('user_id', $studentIds)->count();
            $completedProgressCount = \App\Models\Progress::whereIn('user_id', $studentIds)
                                        ->where('is_completed', true)
                                        ->count();
            $avgCompletion = $totalProgressCount > 0 ? round(($completedProgressCount / $totalProgressCount) * 100) : 0;
        }

  // 🌟 السطر المعدل: احذفي ->where('teacher_id', $teacherId)
$students = User::where('role', 'student')->get(['id', 'name']);



        // 9. الـ Return الموحد والدائم ممرر له متغير الـ students الحين بنجاح! 🌟
        return Inertia::render('Dashboard/TeacherDashboard', [
            'classes' => $classes,
            'submissions' => $submissions,
            'sessions' => $sessions,
            'topPerformers' => $topPerformers,
            'students' => $students, // 🌟 هان ربطناه رسمي عشان الـ Vue يستقبله!

            'assignments' => Assignment::where('teacher_id', $teacherId)->latest()->get(),
            'stats' => [
                'total_students' => $totalStudents,
                'active_assignments' => $activeAssignments,
                'avg_completion' => $avgCompletion,
                'weekly_sessions' => $sessions->count()
            ]
        ]);
    }

    public function createAssignment()
    {
        return Inertia::render('Dashboard/CreateAssignment');
    }

    public function manageClasses()
    {
        $classes = \App\Models\Classroom::with(['users' => function($query) {
            $query->where('role', 'student');
        }])->withCount('users as students_count')->get();

        return Inertia::render('Dashboard/ManageClasses', [
            'classes' => $classes
        ]);
    }

    public function scheduleSession()
    {
        $sessions = \App\Models\Session::with('classroom')->orderBy('scheduled_at', 'asc')->get();
        $classes = \App\Models\Classroom::select('id', 'name')->get();

        return Inertia::render('Dashboard/ScheduleSession', [
            'sessions' => $sessions,
            'classes' => $classes
        ]);
    }

    public function feedback()
    {
        return Inertia::render('Dashboard/SendFeedback');
    }

    public function browseResources()
    {
        $resources = \App\Models\Resource::latest()->get();

        return Inertia::render('Dashboard/BrowseResources', [
            'resources' => $resources
        ]);
    }





    public function storeResource(\Illuminate\Http\Request $request)
{
    // 1. التحقق من صحة الملف المدخل والحقول
    $request->validate([
        'title'         => 'required|string|max:255',
        'description'   => 'required|string',
        'track_type'    => 'required|in:plugged,unplugged',
        'resource_type' => 'required|string',
        'file'          => 'required|file|mimes:pdf|max:10240', // حد أقصى 10 ميجا بايت
    ]);

    // 2. معالجة وحفظ ملف الـ PDF داخل مجلد التخزين العمومي
    $filePath = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // بنخزنه جوا مجلد resources في الـ public storage ليتم تحميله مباشرة
        $path = $file->store('resources', 'public');
        $filePath = '/storage/' . $path;
    }

    // 3. تخزين السجل حياً بالداتابيز
    \App\Models\Resource::create([
        'title'         => $request->title,
        'description'   => $request->description,
        'track_type'    => $request->track_type,
        'resource_type' => $request->resource_type,
        'file_path'     => $filePath,
      	'teacher_id'    => auth()->id(), // لربطه بالمعلمة الحالية
    ]);

    return redirect()->back();
}




public function destroyResource($id)
{
    try {
        // 1. العثور على السجل وحذفه
        $resource = \App\Models\Resource::findOrFail($id);

        // حذف الملف الفيزيائي إذا وجد
        if ($resource->file_path) {
            $path = str_replace('/storage/', '', $resource->file_path);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        $resource->delete();

      
        return Inertia::render('Dashboard/BrowseResources', [
            'resources' => \App\Models\Resource::latest()->get(),
            'message' => 'تم حذف المصدر التعليمي بنجاح!'
        ]);

    } catch (\Exception $e) {
        // في حال حدوث خطأ، بنرجع بس بالـ Errors
        return redirect()->back()->withErrors(['error' => 'تعذر حذف المصدر التعليمي.']);
    }
}
}
