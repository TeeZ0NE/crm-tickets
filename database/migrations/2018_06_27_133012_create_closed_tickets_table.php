<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosedTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closed_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('c_id')->comment('client id');
			$table->unsignedMediumInteger('ticketid')->index()->comment('outer ticket\'s id');
	        $table->string('subject')->charset('utf8')->collation('utf8_unicode_ci')->comment('subject from out');
	        $table->unsignedTinyInteger('priority_id')->comment('priority');
	        $table->unsignedTinyInteger('service_id')->comment('secom etc');
	        $table->unsignedTinyInteger('reply_count')->default(0)->comment('summary replies on ticket');
	        $table->unsignedTinyInteger('compl')->default(1)->comment('difficulty');
	        $table->dateTime('lastreply')->comment('Is comming from out');
            $table->timestamps();

	        $table->foreign('c_id')->references('id')->on('clients')->onUpdate('cascade');
	        $table->foreign('priority_id')->references('id')->on('priorities')->onUpdate('cascade');
	        $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('closed_tickets');
    }
}
