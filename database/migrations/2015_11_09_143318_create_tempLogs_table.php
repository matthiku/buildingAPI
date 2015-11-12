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
        Schema::create('temp_logs', function (Blueprint $table) {
            $table->timestamp('updated_at'     );
            $table->decimal(  'mainroom',  3,1);
            $table->decimal(  'auxtemp',   3,1);
            $table->decimal(  'frontroom', 3,1);
            $table->decimal(  'heating_on',3,1);
            $table->integer(  'power'         );
            $table->decimal(  'outdoor',   3,1);
            $table->decimal(  'babyroom',  3,1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('temp_logs');
    }
}
