<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('service_id');
            $table->unsignedSmallInteger('mail_id');
            $table->unsignedTinyInteger('interval_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mail_id')->references('id')->on('emails')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('interval_id')->references('id')->on('intervals')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailables');
    }
}
