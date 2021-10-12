<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->date('leave_from_date');
            $table->date('leave_till_date');
            $table->string('leave_type');
            $table->mediumText('details');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
