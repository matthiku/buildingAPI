<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventLogs', function (Blueprint $table) {
            $table->timestamp('timestamp' );
            $table->integer(  'event_id'  );
            $table->time(     'eventStart');
            $table->time(     'estimateOn');
            $table->time(     'actualOn'  );
            $table->time(     'actualOff' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('eventLogs');
    }
}
