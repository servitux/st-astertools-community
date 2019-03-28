@extends('layouts.app')

@section('title')
  {{ ($new == 1 ? "Nuevo Módulo" : $entity->module) }}
@endsection

@section('buttons')
  @php
    $errors_found = Session::has('errors_found');
    $state = ($errors_found || $new);
  @endphp
  @if ($new != 1)
    <a href='{{ url('agenda/modulo/new') }}' class="btn btn-app bg-blue"><li class="fa fa-puzzle-piece"></li> Nuevo Módulo</a>
    <a href='#' class="btn btn-app btn-allow bg-{{ $entity->active ? 'red' : 'green' }}"><i class="fa {{ $entity->active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>{{ $entity->active ? "Desactivar Módulo" : "Activar Módulo" }}</a>
    <form id="allow" action="{{ action('\App\Modules\PhoneBook\Controllers\ModuleController@allowModule', array('id' => $entity->id, 'active' => !$entity->active)) }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
    <a href='#' class="btn btn-app btn-token bg-orange"><i class="fa fa-asterisk"></i>Cambiar Token</a>
    <form id="token" action="{{ action('\App\Modules\PhoneBook\Controllers\ModuleController@changeToken', array('id' => $entity->id)) }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  @endif
  <div class="pull-right">
    @if (Auth::check())
      <a class="btn btn-app btn-edit{{ ($state ? ' hidden' : '') }}" href='#'><i class="fa fa-pencil"></i> Editar</a>
      <a class="btn btn-app btn-save{{ (!$state ? ' hidden' : '') }}" href='#module'><i class="fa fa-save"></i> Guardar</a>
      <a class="btn btn-app btn-cancel{{ ($new != 2 ? ' hidden' : '') }}" href='#'><i class="fa fa-undo"></i> Deshacer</a>
      <a class="btn btn-app" href='{{ url('/agenda/modulos') }}'><span class="badge bg-purple">{{ $entity::all()->count() }}</span><i class="fa fa-list"></i> Volver</a>
    @endif
  </div>

@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/agenda/telefonos') }}"><i class="fa fa-address-book-o"></i> Agenda</a></li>
  <li><a href="{{ url('/agenda/modulos') }}"><i class="fa fa-puzzle-piece"></i> Módulos</a></li>
  <li class="active">{{ $entity->module }}</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <form id="module" action="{{ $new == 1 ? action('\App\Modules\PhoneBook\Controllers\ModuleController@postEntity') : action('\App\Modules\PhoneBook\Controllers\ModuleController@putEntity', $entity->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            @php
              $group = Session::has('group') ? Session::get('group') : 0;
              if ($errors_found)
                $group = App\Modules\PhoneBook\Controllers\ModuleController::groupHasErrors($inputs, $errors);
            @endphp
            <li{{ $group == 0 ? " class=active" : "" }}><a href="#principal" data-toggle="tab">Datos del Módulo</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane{{ $group == 0 ? " active" : ""}}" id="principal">
              @foreach ($inputs as $input)
                @if ($input->group == 0)
                  {{ $input->Render($errors->first($input->name), $state) }}
                @endif
              @endforeach

              @if ($new != 1)
                <div class='form-group'>
                  <label class='col-sm-2 control-label'>Peticiones</label>
                  <label class='col-sm-2 registry-label'>{{ $entity->requests ? $entity->requests : "No tiene peticiones" }}</label>
                </div>
              @endif
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
@endsection

@section('jquery_onload')
  {{ AdminLTE::Command_Buttons(true, false) }}
  {{ AdminLTE::iCheckBox_Script() }}
  {{ AdminLTE::Menu_Active("agenda") }}
  {{ AdminLTE::Menu_Active("agenda_modulos") }}
  @if ($new)
    $('form[id=module]').val('POST');
    $('form[id=module]').find('input,textarea,select').filter(':visible:first').select().focus();
  @else
    $('.btn-allow').on('click', function() {
      var btn = this;
      var title = $(this).data('title');
      swal({
            title: '¿ {{ $entity->active ? 'Desactivar este Módulo' : 'Activar este Módulo' }} ?',
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

    $('.btn-token').on('click', function() {
      var btn = this;
      var title = $(this).data('title');
      swal({
            title: '¿ Cambiar token ?',
            text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
          }).then(function () {
            $('form[id=token]').submit();
          });
    });
  @endif
@endsection
