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
        Schema::create('companies', function (Blueprint $table) {
            $table->id('CompanyId');
            $table->string('CompanyName');
            $table->text('Description')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Location')->nullable();
            $table->string('Website')->nullable();
            $table->string('CompanySize')->nullable();
            $table->string('Specialties')->nullable();
            $table->timestamp('RegistrationDate')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};