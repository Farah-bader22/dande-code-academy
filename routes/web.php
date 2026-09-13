<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\SessionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\ChatController;

// --- الصفحة الرئيسية ---
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// --- 3. داشبورد ولي الأمر (محمي بحارس خاص) ---
Route::middleware(['auth', \App\Http\Middleware\ParentMiddleware::class])->group(function () {
    Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
});

// --- الموجه الذكي (Smart Redirector) ---
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'teacher' => redirect()->route('teacher.dashboard'),
        'parent'  => redirect()->route('parent.dashboard'),
        default   => redirect()->route('student.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');




// --- الروابط المحمية (تسجيل الدخول مطلوب) ---
Route::middleware('auth')->group(function () {

    // 1. داشبورد الطالب واللعبة
    Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/play/{id}', [StudentDashboardController::class, 'playAssignment'])->name('play');
// داخل web.php
Route::post('/assignments/{id}/submit', [StudentDashboardController::class, 'submitAssignment'])->name('submit');
    });

 // 2. مجموعة روابط المعلم الموحدة والمحمية (Teacher Suite)
 Route::middleware([\App\Http\Middleware\TeacherMiddleware::class])
 ->prefix('teacher')
 ->name('teacher.')
 ->group(function () {

     // العرض (Pages) باستخدام الكنترولر المحدث داخل مجلد Teacher
     Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
     Route::get('/assignments/create', [TeacherDashboardController::class, 'createAssignment'])->name('assignments.create');
     Route::get('/classes', [TeacherDashboardController::class, 'manageClasses'])->name('classes.index');
     Route::get('/feedback', [TeacherDashboardController::class, 'feedback'])->name('feedback');
     Route::get('/resources', [TeacherDashboardController::class, 'browseResources'])->name('resources.index');

     // إدارة الجلسات (ربط حقيقي بالكنترولر الجديد والصفحة الملوكية)
     Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
     Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
     Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');

     // الأكشن (Actions) - نظيفة وبدون تكرار ✨
     Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');
     Route::post('/feedback/send', [FeedbackController::class, 'store'])->name('feedback.store');
     Route::delete('/assignments/{id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');

     // 🌟 السطر السحري الجديد والوحيد للمصادر
     Route::post('/resources/store', [TeacherDashboardController::class, 'storeResource'])->name('resources.store');

     // 🌟 السطر السحري الجديد للحذف (يُضاف بداخل مجموعة المعلم teacher)
Route::delete('/resources/{id}', [TeacherDashboardController::class, 'destroyResource'])->name('resources.destroy');
 });

    // 4. الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- صفحة تسجيل الدخول ---
Route::get('/login', function () {
    return Inertia::render('Auth/UserLogin');
})->name('login');

require __DIR__.'/auth.php';


// --- روابط المساعدة والتطوير السريع ---
Route::get('/fix-user', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'teacher@dandecode.com'],
        [
            'name' => 'Farah Bader',
            'username' => 'farah_engineer',
            'password' => bcrypt('password123'),
            'role' => 'teacher',
        ]
    );
    return "تم تأكيد حساب المعلمة بنجاح!";
});

// 🌟 روابط سحرية ذكية ومحدثة لمنع التداخل نهائياً أثناء التيست
Route::get('/go-teacher', function () {
    Auth::logout();
    Auth::loginUsingId(1); // تسجيل دخول كمعلمة (فرح أحمد)
    return redirect()->route('teacher.dashboard');
})->name('go.teacher');

Route::get('/go-student', function () {
    Auth::logout();
    Auth::loginUsingId(7); // 🔥 عَدّلنا الرقم هنا لـ 7 عشان يسجل دخول بـ ميرا الحقيقية اللي ربطناها!
    return redirect()->route('student.dashboard');
})->name('go.student');


// هذا الرابط سيقوم بتسجيل خروجك وتغيير كلمة سر ولي الأمر إلى "password"
Route::get('/force-fix-parent', function () {
    Auth::logout(); // تسجيل خروج إجباري

    $user = \App\Models\User::where('role', 'parent')->first();
    if ($user) {
        $user->password = bcrypt('password'); // كلمة سر جديدة سهلة: password
        $user->save();
        return "تم تسجيل الخروج وتغيير كلمة سر ولي الأمر إلى: password. الآن اذهب لصفحة /login وسجل دخول ببريد: " . $user->email;
    }
    return "لم يتم العثور على ولي أمر!";
});

Route::middleware(['auth'])->group(function () {
    // هذا المسار الواحد سيغطي كل شيء (بدون ID أو بـ ID)
    Route::get('/chat/{receiver_id?}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});



// أضيفي هذا في ملف web.php
Route::get('/logout-now', function () {
    Auth::logout();
    return redirect('/login');
});


Route::get('/go-parent', function () {
    Auth::logout();
    // تأكدي من وضع الـ ID الصحيح لولي الأمر عندك (مثلاً 12)
    $parent = \App\Models\User::where('role', 'parent')->first();
    if ($parent) {
        Auth::loginUsingId($parent->id);
        return redirect()->route('parent.dashboard');
    }
    return "لم يتم العثور على ولي أمر!";
})->name('go.parent');

Route::get('/run-migrations-now', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return "Done! Database tables created successfully.";
});
