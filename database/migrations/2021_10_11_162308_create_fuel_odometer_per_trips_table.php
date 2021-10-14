<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelOdometerPerTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_odometer_values_per_trip', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vehicle_id')->nullable();
            $table->unsignedInteger('fleet_request_id')->nullable();
            $table->float('odometer_before_trip');
            $table->float('odometer_after_trip');
            $table->float('fuel_before_trip');
            $table->float('fuel_after_trip');
            $table->timestamps();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('fleet_request_id')->references('id')->on('fleet_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_odometer_per_trips');
    }
}
