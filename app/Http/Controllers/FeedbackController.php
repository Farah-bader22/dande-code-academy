<?php

namespace App\Http\Controllers;

use App\Models\Feedback; // 🌟 استدعاء الموديل الجديد اللي أنشأناه
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * حفظ التقييم الجديد المرسل من المعلمة
     */
    public function store(Request $request)
    {
        // 1. التحقق من البيانات القادمة من الـ Vue (الحقول المتوقعة بالفورم)
        $validated = $request->validate([
            'student_id'   => 'required|exists:users,id',
            'classroom_id' => 'nullable|exists:classrooms,id', // جعلناه اختياري لعدم حصر الفورم
            'message'      => 'required|string|min:3',
        ]);

        // 2. الإنشاء الحقيقي داخل جدول الـ feedback بالأسماء الحقيقية
        Feedback::create([
            'teacher_id'   => auth()->id(),
            'student_id'   => $validated['student_id'],
            'classroom_id' => $validated['classroom_id'] ?? 1,
            'comment'      => $validated['message'],
        ]);

    
        return back()->with('message', 'تم إرسال الملاحظة للطالب بنجاح!');
    }
}
