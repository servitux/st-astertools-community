{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/WebServices/Views
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
  Módulos
@endsection

@section('buttons')
  <a href='{{ url('webservices/modulo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-puzzle-piece"></li> Nuevo Módulo</a>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/webservices/extensiones') }}"><i class="fa fa-cloud"></i> WebServices</a></li>
  <li class="active"><i class="fa fa-puzzle-piece"></i> Módulos</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'group', 'title' => 'Grupo', 'width' => '25px');
      $columns[] = array('data' => 'module_string', 'title' => 'Módulo');
      $columns[] = array('data' => 'description', 'title' => 'Descripción');
      $columns[] = array('data' => 'active_string', 'title' => 'Activo');
      $columns[] = array('data' => 'version_string', 'title' => 'Versión', 'type' => 'num-fmt');
      $columns[] = array('data' => 'requests', 'title' => 'Peticiones');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '130px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Módulos", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/webservices/modulos/datatable"), 50);
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("webservices") }}
  {{ AdminLTE::Menu_Active("webservices_modulos") }}
@endsection
