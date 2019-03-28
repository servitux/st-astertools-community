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
  {{ ($new == 1 ? "Nueva Campaña" : $entity->name) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('dialer/campanya/new') }}' class="btn btn-app bg-blue"><li class="fa fa-object-group"></li> Nueva Campaña</a>
    <a href='#' class="btn btn-primary btn-app btn-import bg-green"><i class="fa fa-download"></i>Importar</a>
    <form id="import" action="{{ action('\App\Modules\Dialer\Controllers\CampanyaController@postImport', array('id' => $entity->id)) }}" method="POST" enctype="multipart/form-data" style="display: none;">
      {{ csrf_field() }}
      <input id='csv' name='csv' type='file'>
    </form>
    <a href='{{ action('\App\Modules\Dialer\Controllers\CampanyaController@getExport', array("id" => $entity->id)) }}' class="btn btn-primary btn-app btn-export bg-green"><i class="fa fa-upload"></i>Exportar</a>
  @endif

  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#group' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#group'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    <a class="btn btn-app" href='{{ url('/dialer/campanyas') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/dialer') }}"><i class="fa fa-headphones"></i> Dialer</a></li>
  <li><a href="{{ url('/dialer/campanyas') }}"><i class="fa fa-object-group"></i> Campañas</a></li>
  <li class="active">{{ $entity->id }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="group" action="{{ $new == 1 ? action('\App\Modules\Dialer\Controllers\CampanyaController@postEntity') : action('\App\Modules\Dialer\Controllers\CampanyaController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = \App\Modules\Dialer\Controllers\CampanyaController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos de la Campaña</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
          </div>
        </div>

        <!-- /.nav-tabs-custom -->
      </form>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  @if ($new != 1)
    <div class="row">
      <div class="col-md-4">
      @php
        //columnas del data table
        $columns1 = array();
        $columns1[] = array('data' => 'extension_string', 'title' => 'Extension');
        $columns1[] = array('data' => 'name', 'title' => 'Nombre');
        //html5 datatable
        AdminLTE::DataTable_UI("dataTableExtensions", "Extensiones Contenidas", $columns1);
      @endphp
      </div>
      <div class="col-md-8">
        @php
          //columnas del data table
          $columns2 = array();
          $columns2[] = array('data' => 'extension_string', 'title' => 'Extension');
          $columns2[] = array('data' => 'phone_string', 'title' => 'Teléfono');
          $columns2[] = array('data' => 'name', 'title' => 'Nombre');
          $columns2[] = array('data' => 'city', 'title' => 'Ciudad');
          $columns2[] = array('data' => 'result_string', 'title' => 'Resultado');
          $columns2[] = array('data' => 'comments', 'title' => 'Comentarios');
          //html5 datatable
          AdminLTE::DataTable_UI("dataTableCalls", "Listado de Llamadas", $columns2);
        @endphp
      </div>
    </div>
  @endif
</section>
@endsection

@section('script_links')
  <script src="{{ config('adminlte.path') }}/plugins/ckeditor/ckeditor.js"></script>
@endsection

@section('script')
  CKEDITOR.replace('script');
  @if ($new != 1)
    @php
      //script datatable
      AdminLTE::DataTable_Script("dataTableExtensions", $columns1, url("/dialer/campanya/" . $entity->id . "/datatable"));
      AdminLTE::DataTable_Script("dataTableCalls", $columns2, url("/dialer/campanya/" . $entity->id . "/datatableCalls"));
    @endphp
  @endif
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::Menu_Active("dialer") }}
  {{ AdminLTE::Menu_Active("dialer_campañas") }}
  @if ($new)
    $('form[id=group]').val('POST');
    $('form[id=group]').find('input,textarea,select').filter(':visible:first').select().focus();
  @endif
  $('.btn-import').on('click', function() {
    $('input[name=csv]').trigger('click');
  });
  $('input[name=csv]').on('change', function() {
    $('form[id=import]').submit();
  });
@endsection
