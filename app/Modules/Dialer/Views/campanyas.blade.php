{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Dialer/Views
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
  Campañas de Marketing
@endsection

@section('buttons')
  <a href='{{ url('dialer/campanya/new') }}' class="btn btn-app bg-blue"><li class="fa fa-object-group"></li> Nueva Campaña</a>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/dialer/campanyas') }}"><i class="fa fa-headphones"></i> Dialer</a></li>
  <li class="active"><i class="fa fa-object-group"></i> Campañas</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'id_string', 'title' => 'ID', 'width' => '25px');
      $columns[] = array('data' => 'name_string', 'title' => 'Nombre');
      $columns[] = array('data' => 'extensions_string', 'title' =>  'Extensiones');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Campañas", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/dialer/campanyas/datatable"), 50);
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("dialer") }}
  {{ AdminLTE::Menu_Active("dialer_campañas") }}
@endsection
