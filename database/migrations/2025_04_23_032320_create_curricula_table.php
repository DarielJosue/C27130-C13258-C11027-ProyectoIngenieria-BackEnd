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
        Schema::create('curricula', function (Blueprint $table) {
            $table->id('CvId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->string('FilePath');
            $table->timestamp('UploadDate')->useCurrent();
            $table->text('Description')->nullable();
            $table->boolean('IsDefault')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};