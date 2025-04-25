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
        Schema::create('company_users', function (Blueprint $table) {
            $table->id('CompanyUserId');
            $table->foreignId('CompanyId')->constrained('companies', 'CompanyId')->onDelete('cascade');
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            $table->string('Role');
            $table->boolean('IsActive')->default(true);
            $table->timestamp('RegistrationDate')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_users');
    }
};