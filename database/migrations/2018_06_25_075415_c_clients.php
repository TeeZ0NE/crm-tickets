<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',130)->charset('utf8')->collation('utf8_unicode_ci')->comment('user name')->index();
            $table->unsignedTinyInteger('compl')->default(1)->comment('complication, dificulty');
            $table->string('comment')->charset('utf8')->nullable();
            $table->unsignedInteger('userid')->comment('outer user id')->default(0);
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
