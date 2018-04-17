{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views/admin
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
  {{ ($new == 1 ? "Nuevo Usuario" : $entity->name) }}
@endsection

@section('buttons')
  @php
      $errors_found = Session::has('errors_found');
      $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('usuario/new') }}' class="btn btn-app bg-blue"><li class="fa fa-user"></li>Nuevo Usuario</a>
    <a href='#' class="btn btn-app btn-password bg-blue"><i class="fa fa-asterisk"></i>Establecer Contraseña</a>
    <form id="password" action="{{ action('UsuarioController@changePassword', array('id' => $entity->id)) }}" method="POST" style="display: none;">
      {{ csrf_field() }}
      <input name='id' type="hidden" value="{{ $entity->id }}">
      <input name='password' type="hidden" value="">
    </form>
  @endif
  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#user' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#user'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    <a class="btn btn-app" href='{{ url('/usuarios/') }}'><span class="badge bg-purple">{{ $entity->all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
  </div>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('usuarios') }}"><i class="fa fa-plus"></i> Usuarios</a></li>
  <li class="active">{{ $entity->id }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="user" action="{{ $new == 1 ? action('UsuarioController@postEntity') : action('UsuarioController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = \App\Http\Controllers\UsuarioController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">General</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach
              @if (\App\Models\Module::isEnabled('Fax'))
                @foreach ($inputs as $input)
                  @if ($input->group == 2)
                    {{ $input->Render($errors->first($input->name), $state) }}
                  @endif
                @endforeach
              @endif
              @if ($new == 1)
                {{ $inputs['password']->Render($errors->first($input->name), $state) }}
              @endif
            </div>
          </div>
        </div>
      </form>
      <form id="clear-photo" action="{{ action("UsuarioController@clearImage", array($entity->id, "photo")) }}" method="POST" style="display: none;">
          {{ csrf_field() }}
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
  {{ AdminLTE::Menu_Active("usuarios") }}

  @if ($new == 1)
    $('form[id=user]').val('POST');
    $('form[id=user]').find('input[type=checkbox]').attr('checked', 'checked');
    $('form[id=user]').find('input,textarea,select').filter(':visible:first').select().focus();
  @else
    $('.btn-password').on('click', function() {
      var btn = this;
      swal({
        title: 'Establecer Contraseña',
        input: 'password',
        showCancelButton: true,
        confirmButtonText: 'Establecer Contraseña',
        showLoaderOnConfirm: true,
        allowOutsideClick: false
      }).then(function (password) {
        $('form[id=password] input[name=password]').val(password);
        $('form[id=password]').submit();
      });
    });
  @endif
@endsection
