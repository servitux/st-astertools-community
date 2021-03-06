<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Migrations
 * @author      Servitux Servicios Informáticos, S.L.
 * @copyright   (C) 2017 - Servitux Servicios Informáticos, S.L.
 * @license     http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * @link        https://www.servitux.es - https://www.servitux-app.com - https://www.servitux-voip.com
 *
 * This file is part of ST-AsterTools.
 *
 * ST-AsterTools is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * ST-AsterTools is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ST-AsterTools. If not, see http://www.gnu.org/licenses/.
 */
 
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallbillingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callbilling_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group')->default('');
            $table->char('extension', 25);
            $table->integer('invoice');
            $table->dateTime('creationDate');
            $table->dateTime('fromDate');
            $table->dateTime('toDate');
            $table->dateTime('callDate');
            $table->string('destination');
            $table->string('type');
            $table->integer('billSec');
            $table->double('price', 7, 5);
            $table->double('total', 7, 2);

            $table->index('extension', 'ix_extension');
            $table->index('invoice', 'ix_invoice');
            $table->index('creationDate', 'ix_creationDate');
            $table->index(['fromDate', 'toDate'], 'ix_dates');

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
        Schema::dropIfExists('callbilling_invoices');
    }
}
