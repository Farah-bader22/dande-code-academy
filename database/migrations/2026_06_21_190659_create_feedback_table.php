<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            // المعلمة اللي كتبت التقييم (مربوطة بجدول الـ users)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            // الطالب المستلم للتقييم مثل ميرا (مربوطة بجدول الـ users)
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            // الصف المربوط فيه التقييم (مربوط بجدول الـ classrooms)
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            // نص التقييم والرسالة الفخمة اللي حتكتبها المعلمة
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
