<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    // السماح بالحقول التي سيتم ملؤها في قاعدة البيانات
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    /**
     * علاقة المرسل (من الذي أرسل الرسالة؟)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * علاقة المستقبل (من الذي يستقبل الرسالة؟)
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // لكي يتم تنسيق الوقت تلقائياً عند عرض الرسائل
    protected $casts = [
        'is_read' => 'boolean',
    ];
}
