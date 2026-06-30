<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول المسموح بتعبئتها (Mass Assignable)
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'teacher_id',
        'parent_id', // أضفنا هذا ليسمح النظام بحفظ ربط ولي الأمر
'classroom_id', // أضفنا هذا أيضاً لتكتمل العلاقات
'points', // أضفناها هنا!
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- العلاقات البرمجية (Eloquent Relationships) ---

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'teacher_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'user_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    // --- علاقات ولي الأمر (Parental Relationships) ---

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function feedbacks() {
        return $this->hasMany(Feedback::class, 'student_id');
    }

}
