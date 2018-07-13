<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysadminActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysadmin_activities', function (Blueprint $table) {
        	$table->increments('id');
        	$table->unsignedSmallInteger('admin_nik_id');
        	$table->unsignedInteger('ticket_id')->comment('outer ticket id');
        	$table->unsignedMediumInteger('replies')->default(0)->comment('count of replies on this ticket');
        	$table->dateTime('lastreply')->comment('when last reply admins');

        	$table->foreign('admin_nik_id')->references('admin_nik_id')->on('sysadmin_niks')->onUpdate('cascade')->onDelete('cascade');
        	$table->foreign('ticket_id')->references('id')->on('tickets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sysadmin_activities');
    }
}
