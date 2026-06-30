<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            // إضافة حقل teacher_id بعد حقل الـ id مباشرة ويكون قابل للحذف المتتالي
            $table->foreignId('teacher_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            // حذف العلاقة والعمود في حال التراجع
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
