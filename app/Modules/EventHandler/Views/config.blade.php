{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/EventHandler/Views
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
  Configuración
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found);
  @endphp
  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#config'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ (!Session::has('hidden') ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/eventhandler/config') }}"><i class="fa fa-commenting"></i> Event Handler</a></li>
  <li><a href="#"><i class="fa fa-info-circle"></i> Configuración</a></li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-6">
    @if ($isRunning == FALSE)
      {{ AdminLTE::CallOut("Información del Proceso", "NodeJS NO está ejecutándose", "warning") }}
    @else
      {{ AdminLTE::CallOut("Información del Proceso", "NodeJS está ejecutándose. <div><strong><a id='restart' href='#'>Recargar Configuración</a></strong></div>", "success") }}
    @endif
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Paquetes Necesarios</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table no-margin">
              <thead>
              <tr>
                <th>Paquete</th>
                <th>Versión</th>
                <th>Estado</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($modules as $key => $module)
                <tr>
                  <td>{{ $key }}</td>
                  <td>{{ $module }}</td>
                  <td><span class='label label-{{ ($module ? "success" : "danger") }}'>{{ ($module ? "Instalado" : "No Instalado") }}</span></td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-6">
      <form id="config" action="{{ action('\App\Modules\EventHandler\Controllers\EventHandlerController@putConfig') }}" method="post" class="form-horizontal" enctype="multipart/form-data"  onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Modules\EventHandler\Controllers\EventHandlerController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#express" data-toggle="tab">Express</a></li>
            <li{{ $group == 1 ? " class=active" : "" }}><a href="#mysql" data-toggle="tab">MySql</a></li>
            <li{{ $group == 2 ? " class=active" : "" }}><a href="#asterisk" data-toggle="tab">Asterisk</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="express">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
            <div class="tab-pane{{ $group == 1 ? " active" : ""}}" id="mysql">
              @foreach ($inputs as $input)
                @if ($input->group == 1)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
            <div class="tab-pane{{ $group == 2 ? " active" : ""}}" id="asterisk">
              @foreach ($inputs as $input)
                @if ($input->group == 2)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
              <div style="border-top: 1px solid #f4f4f4;"><br></div>
              @foreach ($inputs as $input)
                @if ($input->group == 3)
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
</section>
@endsection

@section('script')
  {{ AdminLTE::checkSubmit() }}
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("eventhandler") }}
  {{ AdminLTE::Menu_Active("eventhandler_config") }}

  $("#restart").on('click', function(e) {
    e.preventDefault();
    var notify = $.notify({ icon: 'fa fa-hourglass', message:'<strong>Recargando Configuración</strong> Por favor, espere...'}, {
      allow_dismiss: false,
      showProgressbar: true
    });

    var socket = io.connect('http://{{ $_SERVER['SERVER_ADDR'] }}:{{ $inputs['websocket_port']->value }}', { 'forceNew': true, reconnection: false });

    // Add a connect listener
    socket.on('connect', function() {
      notify.update({'icon': 'fa fa-ok', 'type': 'success', 'message': '<strong>Terminado</strong> La configuración se ha recargado!', 'progress': 25});

      socket.emit('restart', { });
    });
    socket.on('connect_error', function(e){
      notify.update({'icon': 'fa fa-times', 'type': 'danger', 'message': 'Error conectando con el servidor <strong>' + e.message + '</strong>', 'progress': 25});
    });
    socket.on('disconnect', function() {
      notify.update({'icon': 'fa fa-times', 'type': 'warning', 'message': '<strong>Desconectado</strong> Acceso denegado', 'progress': 25});
    });
  });
@endsection
