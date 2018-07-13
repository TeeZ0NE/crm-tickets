<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysadminTableNiks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysadmin_niks', function (Blueprint $table) {
//        	$table->unsignedSmallInteger('sysadmin_id')->default(0)->comment('using  sysadmins table. At first time we don\'t know who take that');
        	$table->unsignedTinyInteger('service_id')->comment('service id');
        	$table->string('admin_nik',100)->charset('utf8')->collation('utf8_unicode_ci')->comment('nick on this ticket service');
        	$table->smallIncrements('admin_nik_id')->comment('real admins id');
        	$table->smallInteger('admin_id')->default(0)->comment('real admins id');

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
        Schema::dropIfExists('admin_niks');
    }
}
