{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views/users
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
  Configuración de Usuario
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found);
  @endphp
  @if (!$state)
    <a href='#' class="btn btn-app btn-password bg-blue"><i class="fa fa-asterisk"></i>Establecer Contraseña</a>
    <form id="password" action="{{ action('\App\Http\Controllers\Config\UserConfigController@changePassword') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
      <input name='id' type="hidden" value="{{ $entity->id }}">
      <input name='old_password' type="hidden" value="">
      <input name='password' type="hidden" value="">
      <input name='repeat' type="hidden" value="">
    </form>
  @endif
  <div class="pull-right">
    @if (Auth::check())
      <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
      <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#config'><i class="fa fa-save"></i> Guardar</a>
      <a class="btn btn-app btn-cancel{{ (!Session::has('hidden') ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    @endif
  </div>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li class="active">Perfil de Usuario</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="config" action="{{ action('Config\UserConfigController@putEntity') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Http\Controllers\Config\UserConfigController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#general" data-toggle="tab">General</a></li>
            <li{{ $group == 1 ? " class=active" : "" }}><a href="#asterisk" data-toggle="tab">Asterisk</a></li>
            @if (\App\Models\Module::isEnabled('Fax'))
              <li{{ $group == 2 ? " class=active" : "" }}><a href="#faxes" data-toggle="tab">Fax</a></li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="general">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
            <div class="tab-pane{{ $group == 1 ? " active" : ""}}" id="asterisk">
              @foreach ($inputs as $input)
                @if ($input->group == 1)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
            @if (\App\Models\Module::isEnabled('Fax'))
            <div class="tab-pane{{ $group == 2 ? " active" : ""}}" id="faxes">
              @foreach ($inputs as $input)
                @if ($input->group == 2)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
            </div>
            @endif
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
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::Menu_Active("inicio") }}

  $('.btn-password').on('click', function() {
    swal.setDefaults({
      input: 'password',
      confirmButtonText: 'Continuar',
      showCancelButton: true,
      animation: false,
      progressSteps: ['1', '2', '3']
    })

    var steps = [
      {
        title: 'Contraseña Actual',
        text: 'Introduce tu contraseña actual'
      },
      {
        title: 'Nueva contraseña',
        text: 'Introduce tu nueva contraseña'
      },
      {
        title: 'Repite tu nueva contraseña',
        text: 'Introduce tu nueva contraseña de nuevo'
      },
    ]

    swal.queue(steps).then(function (result) {
      $('form[id=password] input[name=old_password]').val(result[0]);
      $('form[id=password] input[name=password]').val(result[1]);
      $('form[id=password] input[name=repeat]').val(result[2]);
      $('form[id=password]').submit();
    }, function () {
      swal.resetDefaults()
    })
  });
@endsection
