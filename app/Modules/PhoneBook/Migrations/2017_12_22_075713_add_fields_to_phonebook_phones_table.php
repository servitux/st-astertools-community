<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPhonebookPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phonebook_phones', function (Blueprint $table) {
            $table->string('company')->default('')->nullable();
            $table->string('email')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phonebook_phones', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('email');
        });
    }
}
