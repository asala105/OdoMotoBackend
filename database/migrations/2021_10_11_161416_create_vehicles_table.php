<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->string('category');
            $table->string('registration_code');
            $table->string('plate_number');
            $table->string('model');
            $table->float('weight');
            $table->float('odometer');
            $table->integer('fuel_level');
            $table->boolean('is_rented');
            $table->mediumText('driver_license_requirements')->nullable();
            $table->timestamps();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
