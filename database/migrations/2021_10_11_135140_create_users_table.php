<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('user_type_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->unsignedInteger('rank');
            $table->date('date_of_birth');
            $table->string('phone_nb');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('first_login')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
