<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('vehicle_id')->nullable();
            $table->boolean('inspection_type'); //0 for safety inspection (requires a form) 1 for maintenance 
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspection_schedules');
    }
}
