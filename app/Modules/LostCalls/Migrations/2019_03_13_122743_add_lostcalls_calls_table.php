<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLostcallsCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lostcalls_calls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone', 25);
            $table->string('name')->nullable();
            $table->integer('queue')->default(0);
            $table->dateTime('date');
            $table->string('state', 6)->nullable();
            $table->dateTime('return_date')->nullable();
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
        Schema::dropIfExists('lostcalls_calls');
    }
}
