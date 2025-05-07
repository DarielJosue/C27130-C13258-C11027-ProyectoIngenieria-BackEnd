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
        Schema::create('conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_one_id');
            $table->unsignedBigInteger('user_two_id');
            $table->timestamps();

            
            $table->foreign('user_one_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('user_id')->on('users')->onDelete('cascade');

            // Unicidad: asegura solo una conversación por par de usuarios
            $table->unique(['user_one_id', 'user_two_id'], 'convo_unique_pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};