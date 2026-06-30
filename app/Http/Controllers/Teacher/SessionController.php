<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionController extends Controller
{
    /**
     * عرض صفحة الجلسات مع جلب البيانات الحقيقية من الداتابيز
     */
  /**
     * عرض صفحة الجلسات مع جلب البيانات الحقيقية من الداتابيز
     */
    public function index()
    {
        // 🌟 المسار المحدث المتوافق تماماً مع مجلداتك الفردية
        return Inertia::render('Dashboard/ScheduleSession', [
            'sessions' => Session::with('classroom')->orderBy('scheduled_at', 'asc')->get(),
            'classes' => \App\Models\Classroom::all()
        ]);
    }

    /**
     * حفظ الجلسة الجديدة حقيقياً في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // 1. التحقق من البيانات المرسلة من فورم الـ Vue
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'track_type' => 'required|in:plugged,unplugged',
            'scheduled_at' => 'required|date',
            'location_or_link' => 'nullable|string|max:255',
        ]);

        // 2. الإنشاء الحقيقي في جدول learning_sessions
        Session::create($validated);

        // 3. التوجيه العكسي الفوري لتحديث الواجهة تلقائياً وبدون ريفريش
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $session = \App\Models\Session::findOrFail($id);
            $session->delete();

          
            return Inertia::render('Dashboard/ScheduleSession', [
                'sessions' => \App\Models\Session::with('classroom')->orderBy('scheduled_at', 'asc')->get(),
                'classes'  => \App\Models\Classroom::all(),
                'flash'    => ['message' => 'تم حذف الجلسة بنجاح!']
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'تعذر حذف الجلسة.']);
        }
    }
}
