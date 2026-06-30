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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // Device-based, Unplugged, etc.
            $table->integer('total_lessons')->default(0);
            $table->integer('lessons_completed_count')->default(0); // مهم لعرض التقدم الحقيقي
            $table->string('color'); // purple, blue, green
            $table->string('bg_color'); // Hex code
            $table->string('icon'); // font-awesome class
            $table->integer('stars_earned')->default(0); // النجوم اللي جمعها الطفل
            $table->boolean('is_unlocked')->default(true); // هل المسار مفتوح للطفل أم مقفل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
