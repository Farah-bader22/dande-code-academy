<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    // 🌟 إجبار الموديل على قراءة الجدول الجديد المحدث لمنع التعارض مع جدول جِلسات السيستم الافتراضي
    protected $table = 'learning_sessions';

    // تحديد الحقول المسموح بتعبئتها تلقائياً لحماية قاعدة البيانات
    protected $fillable = [
        'classroom_id',
        'title',
        'scheduled_at',
        'track_type',
        'location_or_link'
    ];

    /**
     * العلاقة العكسية: الجلسة أو الحصة الواحدة تنتمي إلى صف واحد محدد
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
