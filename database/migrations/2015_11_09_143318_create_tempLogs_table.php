<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempLogs', function (Blueprint $table) {
            $table->timestamp('timestamp'     );
            $table->decimal(  'mainroom',  2,2);
            $table->decimal(  'auxtemp',   2,2);
            $table->decimal(  'frontroom', 2,2);
            $table->decimal(  'heating_on',2,2);
            $table->decimal(  'power',     4,0);
            $table->decimal(  'outdoor',   2,2);
            $table->decimal(  'babyroom',  2,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tempLogs');
    }
}
