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
        Schema::create('interest_job_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('InterestId')->constrained('interests', 'InterestId')->onDelete('cascade'); // Relación con interests
            $table->foreignId('JobTypeId')->constrained('job_types', 'JobTypeId')->onDelete('cascade'); // Relación con job_types
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_job_types');
    }
};