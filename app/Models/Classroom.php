<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    // الحقول المسموح بتعبئتها تلقائياً في قاعدة البيانات
    protected $fillable = ['name'];

    /**
     * علاقة الصف مع الطلاب: الصف الواحد يحتوي على العديد من الطلاب
     */
    public function students(): HasMany
    {
        // تم ربطها بجدول الـ users باستخدام الحقل المشترك classroom_id
        return $this->hasMany(User::class, 'classroom_id');
    }

    /**
     * علاقة الصف مع الواجبات: الصف الواحد يتم تعيين واجبات كثيرة له
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'classroom_id');
    }

    /**
     * 💡 ملاحظة هندسية ذكية: عشان الكنترولر يشتغل بدون أي تعديل وبدون إيرور،
     * عملت لك دالة مستعارة (Alias) باسم users تشير إلى نفس دالة students.
     */
    public function users(): HasMany
    {
        return $this->students();
    }
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
