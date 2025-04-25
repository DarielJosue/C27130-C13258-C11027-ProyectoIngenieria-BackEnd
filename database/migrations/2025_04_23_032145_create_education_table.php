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
        Schema::create('education', function (Blueprint $table) {
            $table->id('EducationId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->string('Institution');
            $table->string('Degree');
            $table->string('Discipline');
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
            $table->text('Activities')->nullable();
            $table->text('Description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};