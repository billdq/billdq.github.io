<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->unique(['type', 'key'], 'sysconfig_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_config', function (Blueprint $table) {
            $table->dropUnique('sysconfig_pk');
        });

        Schema::dropIfExists('system_config');
    }
}
