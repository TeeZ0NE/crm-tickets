<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('c_id')->comment('client id');
	        $table->unsignedMediumInteger('ticketid')->index()->comment('outer ticket\'s id');
	        $table->string('subject')->charset('utf8')->collation('utf8_unicode_ci')->comment('subject from out');
	        $table->unsignedTinyInteger('service_id')->comment('secom etc');
	        $table->unsignedTinyInteger('status_id')->comment('status_id');
	        $table->unsignedTinyInteger('priority_id')->comment('priority');
	        $table->unsignedTinyInteger('reply_count')->default(0)->comment('summary replies on ticket');
	        $table->unsignedTinyInteger('compl')->default(1)->comment('difficulty');
	        $table->dateTime('lastreply')->comment('Is comming from out');
	        $table->boolean('lastreply_is_admin')->default(0)->comment('flag who last reply');
	        $table->unsignedTinyInteger('deadline_id')->nullable();
	        $table->timestamps();

        	$table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade');
	        $table->foreign('deadline_id')->references('id')->on('deadlines')->onUpdate('cascade')->onDelete('cascade');
        	$table->foreign('c_id')->references('id')->on('clients')->onUpdate('cascade');
	        $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade');
	        $table->foreign('priority_id')->references('id')->on('priorities')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
