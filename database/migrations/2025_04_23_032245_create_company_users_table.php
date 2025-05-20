<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUsersTable extends Migration
{
    public function up()
    {
        Schema::create('company_users', function (Blueprint $table) {
            $table->id('company_user_id');
            $table->foreignId('company_id')->nullable()->constrained('companies', 'company_id')->onDelete('set null');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('lastname');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->boolean('active');
            $table->timestamp('register_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_users');
    }
}