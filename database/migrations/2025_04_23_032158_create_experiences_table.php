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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id('ExperienceId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->string('EmploymentType');
            $table->string('Company');
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
            $table->string('Location');
            $table->string('LocationType');
            $table->text('Description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};