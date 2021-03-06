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
	        $table->unsignedMediumInteger('ticketid')->index()->comment('outer ticket\'s id');
	        $table->string('subject')->charset('utf8')->collation('utf8_unicode_ci')->comment('subject from out');
	        $table->unsignedTinyInteger('service_id')->index()->comment('secom etc');
	        $table->unsignedTinyInteger('status_id')->nullable()->comment('status_id');
	        $table->unsignedTinyInteger('priority_id')->nullable()->comment('priority');
	        $table->dateTime('lastreply')->comment('Is comming from out');
	        $table->unsignedSmallInteger('last_replier_nik_id')->default(0)->comment('nik ID 4 last replier');
	        $table->unsignedInteger('user_assign_id')->nullable()->comment('user id which assign ticket');
	        $table->boolean('is_closed')->default(0);
	        $table->boolean('last_is_admin')->default(0)->comment('when sorted table using who last reply. Past in top time then sort by last replier');
	        $table->boolean('has_deadline')->default(1);
//	        $table->unsignedTinyInteger('deadline_id')->nullable();
	        $table->timestamps();
	        $table->index('is_closed','ticket_open');

        	$table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade');
//	        $table->foreign('deadline_id')->references('id')->on('deadlines')->onUpdate('cascade')->onDelete('cascade');
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
