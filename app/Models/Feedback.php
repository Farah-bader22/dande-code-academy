<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    // حماية الحقول وتحديد المسموح بحفظه في جدول الـ feedback بالداتابيز
    protected $fillable = [
        'teacher_id',
        'student_id',
        'classroom_id',
        'comment'
    ];

    /**
     * علاقة التقييم بالمعلمة التي كتبته
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * علاقة التقييم بالطفل أو الطالب المستلم له
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * علاقة التقييم بالصف المربوط فيه
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
