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
        Schema::create('aplications', function (Blueprint $table) {
            $table->id('AplicationId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->foreignId('JobPostId')->constrained('job_posts', 'JobPostId')->onDelete('cascade');
            $table->foreignId('CvId')->constrained('curricula', 'CvId')->onDelete('cascade');
            $table->date('AplicationDate');
            $table->text('Message')->nullable();
            $table->string('Status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplications');
    }
};