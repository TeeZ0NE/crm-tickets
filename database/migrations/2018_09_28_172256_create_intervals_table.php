<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervals', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name',20)->collation('utf8_general_ci')->charset('utf8');
            $table->string('url_attr',22);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervals');
    }
}
