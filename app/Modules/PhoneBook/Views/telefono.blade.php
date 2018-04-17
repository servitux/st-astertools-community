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
  {{ ($new == 1 ? "Nuevo Contacto" : $entity->first_name . " " . $entity->last_name) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('agenda/telefono/new') }}' class="btn btn-app bg-blue"><li class="fa fa-id-card-o"></li> Nuevo Contacto</a>
  @endif
  <div class="pull-right">
    <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
    <a class="btn btn-app btn-delete{{ ($state ? ' hidden' : '') }}" href='#registro'><i class="fa fa-trash-o"></i> Eliminar</a>
    <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#registro'><i class="fa fa-save"></i> Guardar</a>
    <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
    <a class="btn btn-app" href='{{ env('APP_URL') . '/agenda/telefonos' }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
  </div>
@endsection

@section('breadcrumb')
  <li><a href="{{ env('APP_URL') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ env('APP_URL') . '/agendas/telefonos' }}"><i class="fa fa-address-book-o"></i> Agenda</a></li>
  <li><a href="{{ env('APP_URL') . '/agendas/telefonos' }}"><i class="fa fa-phone"></i> Teléfonos</a></li>
  <li class="active">{{ $entity->id }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-xs-12">
      <form id="registro" action="{{ ($new == 1? action('\App\Modules\PhoneBook\Controllers\PhoneController@postEntity') : action('\App\Modules\PhoneBook\Controllers\PhoneController@putEntity', $entity->id)) }}" method="post" class="form-horizontal" enctype="multipart/form-data" onkeypress="return checkSubmit(event, this)">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Modules\PhoneBook\Controllers\PhoneController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos del Contacto</a></li>
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
  {{ AdminLTE::Menu_Active("agenda") }}
  {{ AdminLTE::Menu_Active("agenda_telefonos") }}
  @if ($new)
    $('form[id=registro]').val('POST');
    $('form[id=registro]').find('input[type=text],textarea,select').filter(':visible:first').focus();
  @endif
@endsection
