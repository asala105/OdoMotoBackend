<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionsFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inspection_schedule_id')->nullable();
            $table->timestamps();
            $table->foreign('inspection_schedule_id')->references('id')->on('inspection_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspections_forms');
    }
}
