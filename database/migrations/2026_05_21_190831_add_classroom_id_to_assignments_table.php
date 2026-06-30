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
        Schema::table('assignments', function (Blueprint $table) {
            // 1. ربط الواجب بالصف (الكود الأصلي تبعك وهو ممتاز)
            $table->foreignId('classroom_id')->nullable()->after('teacher_id')->constrained('classrooms')->onDelete('cascade');

            // 2. تعديل حقل game_data ليصبح اختياري (nullable) عشان يحل مشكلة الـ Seeder بشكل جذري
            $table->json('game_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');

            // إرجاع الحقل كما كان عند التراجع عن الـ migration
            $table->json('game_data')->nullable(false)->change();
        });
    }
};
