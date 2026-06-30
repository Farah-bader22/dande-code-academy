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
        Schema::table('users', function (Blueprint $table) {
        // إضافة حقل يربط المستخدم (الطالب) بمعلم من نفس جدول المستخدمين
        $table->foreignId('teacher_id')
              ->nullable()
              ->after('role') // ليظهر بعد حقل الصلاحية الذي أضفتِه سابقاً
              ->constrained('users')
              ->onDelete('set null'); // في حال حذف المعلم، يبقى الطالب موجوداً
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
