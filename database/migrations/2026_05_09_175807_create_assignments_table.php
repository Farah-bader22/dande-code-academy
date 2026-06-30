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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // الحقول الجديدة لزيادة الاحترافية
            $table->string('difficulty')->default('Beginner'); // Beginner, Medium, Advanced
            $table->integer('points')->default(100); // النقاط التي سيكسبها الطالب
            $table->boolean('is_active')->default(true); // هل التحدي متاح حالياً؟

            $table->json('game_data');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
