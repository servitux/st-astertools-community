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
  {{ ($new == 1 ? "Nueva Extensión" : "(" . $entity->extension . ") " . $entity->name) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('dialer/extension/new') }}' class="btn btn-app bg-blue"><li class="fa fa-phone"></li> Nueva Extensión</a>
    <a href='#' class="btn btn-app btn-allow bg-{{ $entity->active ? 'red' : 'green' }}"><i class="fa {{ $entity->active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>{{ $entity->active ? "Restringir Llamadas" : "Permitir Llamadas" }}</a>
    <form id="allow" action="{{ action('\App\Modules\Dialer\Controllers\ExtensionController@allowExtension', array('id' => $entity->id, 'active' => !$entity->active)) }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  @endif

  <div class="pull-right">
    @if (Auth::check())
      <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
      <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#extension' data-title='{{ $entity->name }}'><i class="fa fa-trash-o"></i> Eliminar</a>
      <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#extension'><i class="fa fa-save"></i> Guardar</a>
      <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
      <a class="btn btn-app" href='{{ url('/dialer/extensiones') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
    @endif
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/dialer') }}"><i class="fa fa-headphones"></i> Dialer</a></li>
  <li><a href="{{ url('/dialer/extensiones') }}"><i class="fa fa-phone"></i> Extensiones</a></li>
  <li class="active">{{ $entity->extension }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="extension" action="{{ $new == 1 ? action('\App\Modules\Dialer\Controllers\ExtensionController@postEntity') : action('\App\Modules\Dialer\Controllers\ExtensionController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Modules\Dialer\Controllers\ExtensionController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos de la Extensión</a></li>
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
</section>
@endsection

@section('script')
  {{ AdminLTE::checkSubmit() }}
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons() }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("dialer") }}
  {{ AdminLTE::Menu_Active("dialer_extensiones") }}
  @if ($new)
    $('form[id=extension]').val('POST');
    $('form[id=extension]').find('input,textarea,select').filter(':visible:first').select().focus();
  @else
    $('.btn-allow').on('click', function() {
      var btn = this;
      var title = $(this).data('title');
      swal({
            title: '¿ {{ $entity->active ? 'Restringir llamadas en esta extensión' : 'Permitir llamadas en esta extensión' }} ?',
            text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
          }).then(function () {
            $('form[id=allow]').submit();
          });
    });
  @endif
@endsection
