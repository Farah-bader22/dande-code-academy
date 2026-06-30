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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الحصة
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade'); // ربط بالصف
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // ربط بالمعلمة
            $table->string('type'); // نوع الجلسة (Live Session أو Workshop)
            $table->dateTime('scheduled_at'); // وقت الجلسة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
