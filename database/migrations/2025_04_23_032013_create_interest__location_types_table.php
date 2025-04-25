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
            $table->foreignId('InterestId')->constrained('interests', 'InterestId')->onDelete('cascade');
            $table->foreignId('LocationTypeId')->constrained('location_types', 'LocationTypeId')->onDelete('cascade');
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