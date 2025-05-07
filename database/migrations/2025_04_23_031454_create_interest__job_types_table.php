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
            $table->foreignId('interest_id')->constrained('interests', 'interest_id')->onDelete('cascade'); // Relación con interests
            $table->foreignId('job_type_id')->constrained('job_types', 'job_type_id')->onDelete('cascade'); // Relación con job_types
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