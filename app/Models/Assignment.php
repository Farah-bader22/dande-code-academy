<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    // حافظنا على كل الحقول القديمة وأضفنا الـ classroom_id في الـ fillable لضمان الأمان والحفظ
    protected $fillable = [
        'title',
        'description',
        'game_data',
        'teacher_id',
        'classroom_id', // تم الإضافة هان لربط الواجب بالصف
        'difficulty',
        'points',
        'is_active'
    ];

    // لنتعامل مع الـ JSON كأنه مصفوفة برمجية سهلة (الوظيفة القديمة تضل زي ما هي)
    protected $casts = [
        'game_data' => 'array',
    ];

    /**
     * العلاقة القديمة: الواجب ينتمي إلى معلم
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * العلاقة القديمة: تتبع تقدم الطلاب في الواجب
     */
    public function studentsProgress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * 🌟 العلاقة السحرية المضافة حديثاً: الواجب ينتمي إلى صف معين (تمنع إيرور لوحة التحكم)
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * 🌟 العلاقة المضافة حديثاً: الواجب يمتلك العديد من التسليمات (Submissions) من قبل الطلاب
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
