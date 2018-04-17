{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Views
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
--}}

@extends('layouts.app')

@section('title')
  Factura Habitación {{ $room->id }}
@endsection

@section('buttons')
  @if (Auth::check())
    <a href='#' class='btn btn-primary'>Facturar</a>
  @endif
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/tarificador') }}"><i class="fa fa-money"></i> Tarificador</a></li>
  <li><a href="{{ url('/tarificador/habitaciones') }}"><i class="fa fa-bed"></i> Habitaciones</a></li>
  <li class="active"><i class="fa fa-invoice"></i> Factura Habitación {{ $room->id }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'callDate', 'title' => 'Fecha/Hora');
      $columns[] = array('data' => 'destination', 'title' => 'Teléfono');
      $columns[] = array('data' => 'type', 'title' => 'Tipo');
      $columns[] = array('data' => 'billSecs', 'title' => 'Duración');
      $columns[] = array('data' => 'price', 'title' => 'Precio');
      //html5 datatable
      Servitux::DataTable_UI("dataTable", "Detalle de Llamadas", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    Servitux::DataTable_Script("dataTable", $columns, url("/tarificador/habitaciones/" . $room->id . "/datatable"));
  @endphp
@endsection

@section('jquery_onload')
  @php
    //foco a la búsqueda
    Servitux::DataTable_Focus();
  @endphp
  $('li[id=tarificador]').addClass("active");
@endsection
