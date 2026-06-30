<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    // 🌟 السطر السحري لحل المشكلة والسماح بحفظ الحقول في قاعدة البيانات
    protected $fillable = [
        'title',
        'description',
        'track_type',
        'resource_type',
        'file_path',
        'teacher_id',
    ];

    /**
     * علاقة المصدر بالمعلمة التي قامت برفعه
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
