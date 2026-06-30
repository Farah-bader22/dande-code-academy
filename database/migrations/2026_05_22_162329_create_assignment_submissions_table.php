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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade'); // ربط بالواجب
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // ربط بالطالب
            $table->integer('score')->nullable(); // العلامة (nullable عشان لو لسا مش مصلح)
            $table->string('status')->default('pending'); // حالة الواجب (pending أو graded)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
