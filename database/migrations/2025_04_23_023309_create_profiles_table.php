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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('ProfileId');
            $table->foreignId('UserId')->constrained('users', 'UserId')->onDelete('cascade'); // Relación con users
            $table->text('Biography')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};