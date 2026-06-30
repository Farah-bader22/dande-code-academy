<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    // الحقول المسموح بحفظها وتعديلها في قاعدة البيانات
    protected $fillable = [
        'title',
        'classroom_id',
        'teacher_id',
        'type',
        'scheduled_at'
    ];

    // الـ Casts السحري عشان دالة الـ calendar() تشتغل في الكنترولر بدون إيرور وقت
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * العلاقة المفقودة: الحصة أو الجلسة تنتمي إلى صف معين
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * علاقة الجلسة بالمعلمة التي تديرها
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
