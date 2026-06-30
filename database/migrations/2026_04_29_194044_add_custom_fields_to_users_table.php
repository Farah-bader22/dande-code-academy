<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // فحص ما إذا كان عمود username غير موجود قبل إضافته
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('name');
            }

            // فحص ما إذا كان عمود avatar غير موجود قبل إضافته
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('password');
            }

            // فحص ما إذا كان عمود role غير موجود قبل إضافته
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('avatar');
            }
        });
    }
};
