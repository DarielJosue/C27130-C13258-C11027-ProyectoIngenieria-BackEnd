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
        Schema::create('interests', function (Blueprint $table) {
            $table->id('InterestId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade');
            $table->enum('Visibility', ['public', 'company_only']);
            $table->timestamp('StartDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests');
    }
};