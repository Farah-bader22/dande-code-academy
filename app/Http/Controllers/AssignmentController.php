<?php

namespace App\Http\Controllers;

use App\Models\Assignment; // ضروري جداً لاستخدام الموديل
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    /**
     * حفظ التحدي الجديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // 1. التحقق من البيانات (Validation) بطريقة صارمة
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'game_data'   => 'required|array', // مصفوفة الـ Grid التي أرسلناها من Vue
        ]);

        try {
            // 2. إنشاء التحدي وربطه بالمعلم المسجل حالياً
            $assignment = Assignment::create([
                'title'       => $validated['title'],
                'description' => $validated['description'],
                'game_data'   => $validated['game_data'],
                'teacher_id'  => Auth::id(), // تأكيد هوية المعلم
            ]);

            // 3. التوجيه لصفحة الداشبورد مع رسالة نجاح (Inertia Flash Message)
            return redirect()->route('teacher.dashboard')->with('message', 'Great job! The coding challenge has been published.');

        } catch (\Exception $e) {
            // تسجيل الخطأ في حال حدوث مشكلة تقنية (لمساعدتك في الـ Debugging)
            Log::error('Assignment Creation Failed: ' . $e->getMessage());

            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ التحدي، حاولي مرة أخرى.']);
        }
    }

    /**
     * 🌟 4. دالة حذف الواجب نهائياً (المضافة حديثاً للربط مع Vue)
     */
    public function destroy($id)
    {
        $assignment = \App\Models\Assignment::findOrFail($id);
        $assignment->delete();

        // 🌟 إجبار السيرفر على إعادة بناء صفحة الداشبورد فوراً بصيغة GET
        return Inertia::render('Dashboard/TeacherDashboard', [
            // مرري هنا نفس الـ props اللي بياخدها الداشبورد عندك (مثل الواجبات أو الاحصائيات)
        ]);
    }
}
