<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLostcallsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lostcalls_temp', function (Blueprint $table) {
          $table->increments('id');
          $table->string('phone', 25);
          $table->string('phone_unique', 25);
          $table->boolean('last')->default(false);
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
        Schema::dropIfExists('lostcalls_temp');
    }
}
