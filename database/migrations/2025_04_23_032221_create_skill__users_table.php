<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('SkillId')->constrained('skills', 'SkillId')->onDelete('cascade');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill__users');
    }
};