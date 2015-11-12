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
        Schema::create('event_logs', function (Blueprint $table) {
            $table->timestamp('updated_at');
            $table->integer(  'event_id'  )->references('id')->on('events');;
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
        Schema::drop('event_logs');
    }
}
