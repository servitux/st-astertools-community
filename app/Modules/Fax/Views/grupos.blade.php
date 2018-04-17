{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Views
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
  Grupos de Fax
@endsection

@section('buttons')
  <a href='{{ url('fax/grupo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-object-group"></li> Nuevo Grupo</a>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/fax/grupos') }}"><i class="fa fa-fax"></i> Fax</a></li>
  <li class="active"><i class="fa fa-object-group"></i> Grupos</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'name_string', 'title' => 'Nombre');
      $columns[] = array('data' => 'email_string', 'title' => 'Email');
      $columns[] = array('data' => 'telephones_string', 'title' =>  'Teléfonos');
      $columns[] = array('data' => 'recieved_string', 'title' => 'Recibidos', 'type' => 'num');
      $columns[] = array('data' => 'sent_string', 'title' => 'Enviados', 'type' => 'num');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Grupos", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/fax/grupos/datatable"), 50);
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("fax") }}
  {{ AdminLTE::Menu_Active("fax_grupos") }}
@endsection
