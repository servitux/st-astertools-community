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
  {{ ($new == 1 ? "Nuevo Grupo" : $entity->name) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('fax/grupo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-object-group"></li> Nuevo Grupo</a>
  @endif

  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#group' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#group'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    <a class="btn btn-app" href='{{ url('/fax/grupos') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/fax') }}"><i class="fa fa-fax"></i> Fax</a></li>
  <li><a href="{{ url('/fax/grupos') }}"><i class="fa fa-object-group"></i> Grupos</a></li>
  <li class="active">{{ $entity->id }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="group" action="{{ $new == 1 ? action('\App\Modules\Fax\Controllers\GroupController@postEntity') : action('\App\Modules\Fax\Controllers\GroupController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = \App\Modules\Fax\Controllers\GroupController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos del Grupo</a></li>
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
      <div class="col-md-6">
      @php
        //columnas del data table
        $columns = array();
        $columns[] = array('data' => 'phone_string', 'title' => 'Extension');
        $columns[] = array('data' => 'recieved_string', 'title' => 'Recibidos');
        $columns[] = array('data' => 'sent_string', 'title' => 'Enviados');
        //html5 datatable
        AdminLTE::DataTable_UI("dataTableTelephones", "Teléfonos Contenidos", $columns);
      @endphp
      </div>
    </div>
  @endif

</section>
@endsection

@section('script')
  @if ($new != 1)
    @php
      //script datatable
      AdminLTE::DataTable_Script("dataTableTelephones", $columns, url("/fax/grupo/" . $entity->id . "/datatable"));
    @endphp
  @endif
  {{ AdminLTE::checkSubmit() }}
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("fax") }}
  {{ AdminLTE::Menu_Active("fax_grupos") }}
  @if ($new)
    $('form[id=group]').val('POST');
    $('form[id=group]').find('input,textarea,select').filter(':visible:first').select().focus();
  @endif
@endsection
