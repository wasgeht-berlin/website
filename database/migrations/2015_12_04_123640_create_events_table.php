<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->integer('location_id', false, true)->nullable();
            $table->foreign('location_id')->references('locations')->on('id');

            $table->string('title');
            $table->string('description');

            $table->dateTimeTZ('starting_time');
            $table->dateTimeTZ('ending_time')->nullable();

            $table->string('hash', 40);

            $table->text('notes')->nullable();

            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
