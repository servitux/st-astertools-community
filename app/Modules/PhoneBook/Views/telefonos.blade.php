{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/PhoneBook/Views
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
  Agenda Teléfonos
@endsection

@section('buttons')
  <a href="{{ env('APP_URL') . '/agenda/telefono/new' }}" class="btn btn-app bg-blue"><li class="fa fa-id-card-o"></li>Nuevo Contacto</a>

  @foreach (App\Modules\PhoneBook\Models\PBModule::all() as $module)
    @if ($module->active)
      <a href="{{ env('APP_URL') . '/api/pb/' . $module->token . '/' . $module->module . "."  . $module->format }}" class="btn btn-app bg-green pull-right"><li class="fa fa-upload"></li>{{ $module->module }}</a>
    @endif
  @endforeach
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/home/agenda/telefonos') }}"><i class="fa fa-address-book-o"></i> Agenda</a></li>
  <li class="active"><i class="fa fa-phone"></i> Teléfonos</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'id', 'title' => 'ID', 'width' => '25px');
      $columns[] = array('data' => 'name_string', 'title' => 'Nombre');
      $columns[] = array('data' => 'phone1_string', 'title' => 'Teléfono 1');
      $columns[] = array('data' => 'phone2_string', 'title' => 'Teléfono 2');
      $columns[] = array('data' => 'phone3_string', 'title' => 'Teléfono 3');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Contactos", $columns)
    @endphp
  </div>
  <!-- /.row -->

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, env('APP_URL') . "/agenda/telefonos/datatable");
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("agenda") }}
  {{ AdminLTE::Menu_Active("agenda_telefonos") }}
@endsection
