<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح بتعبئتها (Mass Assignment)
     * لضمان حماية قاعدة البيانات وتسهيل تخزين البيانات
     */
    protected $fillable = [
        'title',
        'type',
        'total_lessons',
        'lessons_completed_count',
        'color',
        'bg_color',
        'icon',
        'stars_earned',
        'is_unlocked',
    ];

    /**
     * تحويل القيم عند جلبها من قاعدة البيانات
     * مثلاً: تحويل is_unlocked من 0/1 إلى true/false تلقائياً
     */
    protected $casts = [
        'is_unlocked' => 'boolean',
        'total_lessons' => 'integer',
        'lessons_completed_count' => 'integer',
        'stars_earned' => 'integer',
    ];

    /**
     * علاقة اختيارية: إذا كان لكل مسار دروس (Lessons) في جدول منفصل
     * يمكنكِ لاحقاً تفعيلها ليكون الشغل احترافي أكثر
     */
    /*
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    */
}
