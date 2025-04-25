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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id('JobPostId');
            $table->foreignId('CompanyId')->constrained('companies', 'CompanyId')->onDelete('cascade');
            $table->string('Title');
            $table->text('Description');
            $table->text('Requirements');
            $table->date('PublicationDate');
            $table->decimal('Salary', 10, 2)->nullable();
            $table->string('Location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};