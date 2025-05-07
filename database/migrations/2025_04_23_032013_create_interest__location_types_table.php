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
        Schema::create('interest_location_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interest_id')->constrained('interests', 'interest_id')->onDelete('cascade');
            $table->foreignId('location_type_id')->constrained('location_types', 'location_type_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_location_types');
    }
};