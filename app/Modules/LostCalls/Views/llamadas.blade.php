@extends('layouts.app')

@section('title')
  Llamadas Perdidas
@endsection

@section('css_links')
  <meta http-equiv='refresh' content='60'>
@endsection

@section('buttons')
  @if (Auth::user()->isAdmin())
    <button class="btn btn-app bg-red btn-clean pull-right"><i class="fa fa-eraser"></i>Limpiar</button>
    <form id="delete" action="{{ action('\App\Modules\LostCalls\Controllers\LostCallController@delete') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
      <input name="id" type="hidden" value="0">
    </form>
    <form id="clean" action="{{ action('\App\Modules\LostCalls\Controllers\LostCallController@clean') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  @endif
  <form id="call" action="{{ action('\App\Modules\LostCalls\Controllers\LostCallController@call') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input name="id" type="hidden" value="0">
  </form>
  <form id="state" action="{{ action('\App\Modules\LostCalls\Controllers\LostCallController@state') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input name="id" type="hidden" value="0">
  </form>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/perdidas/llamadas') }}"><i class="fa fa-microphone"></i> Perdidas</a></li>
  <li class="active"><i class="fa fa-phone"></i> Llamadas</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row" style="margin-bottom: 15px">
    <form name="filter" method="get" action="{{url('lostcalls/llamadas')}}">
      <div class="col-md-3">
        <select class="form-control" name="year" id="year">
          @for ($i = $yearNow - 10; $i <= $yearNow; $i++)
            <option value="{{$i}}" {{ $year==$i ? "selected" : ""}}>{{$i}}</option>
          @endfor
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-control" name="month" id="month">
          @for ($i = 1; $i <= 12; $i++)
            <option value="{{$i}}" {{ $month==$i ? "selected" : ""}}>{{$months[$i]}}</option>
          @endfor
        </select>
    </form>
    </div>
  </div>
  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'id_string', 'title' => 'ID', 'width' => '120px');
      $columns[] = array('data' => 'phone_string', 'title' => 'Teléfono');
      $columns[] = array('data' => 'name_string', 'title' => 'Nombre');
      $columns[] = array('data' => 'date_string', 'title' => 'Fecha/Hora', 'type' => 'date');
      $columns[] = array('data' => 'state_string', 'title' => 'Estado');
      $columns[] = array('data' => 'return_string', 'title' => 'Fecha/Hora Devolución', 'type' => 'date');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '140px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Llamadas Perdidas", $columns);
    @endphp
  </div>

  @if (\App\Models\Module::isEnabled("PHONE BOOK"))
    <div class="modal fade in" id="modal_phonebook">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Añadir a la Agenda</h4>
          </div>
          <div class="modal-body">
            <p>Introduce Nombre:</p>
            <form id="phonebook" action="{{ action('\App\Modules\PhoneBook\Controllers\PhoneController@postExternal') }}" method="POST">
              {{ csrf_field() }}
              <input name='phone' type="hidden" value="">
              <input class='form-control' name="name" type="text" max="255" value="">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/lostcalls/llamadas/datatable?year=$year&month=$month"), 50, 0, 'desc');
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("lostcalls") }}
  {{ AdminLTE::Menu_Active("lostcalls_llamadas") }}

  $('body').on('click', '.btn-state', function() {
    var btn = this;
    var code = $(this).data('code');
    $('form[id=state] input[name=id]').val(code);
    $('form[id=state]').submit();
  });
  $('body').on('click', '.btn-call', function() {
    var btn = this;
    var code = $(this).data('code');
    $('form[id=call] input[name=id]').val(code);
    $('form[id=call]').submit();
  });

  @if (Auth::user()->isAdmin())
    $('body').on('click', '.btn-delete', function() {
      var btn = this;
      var code = $(this).data('code');
      swal({
        title: '¿ Eliminar Perdida ?',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡ Si, Eliminar !'
      }).then(function () {
        $('form[id=delete] input[name=id]').val(code);
        $('form[id=delete]').submit();
      });
    });

    $('.btn-clean').click(function() {
      swal({
        title: '¿ Limpiar Registros ?',
        text: 'Esto limpia todos los registros de la base de datos',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡ Si, Limpiar !'
      }).then(function () {
        $('form[id=clean]').submit();
      });
    });
  @endif

  $('form[name=filter]').find('select').change(function() {
    $('form[name=filter]').submit();
  });

  $(document).on('click', '.btn-phonebook', function(e) {
    var phone = $(this).data('phone');
    $('#modal_phonebook').find('input[name=phone]').val(phone);
    $('#modal_phonebook').modal('show');
    setTimeout(function() {
      $('#modal_phonebook').find('input[name=name]').focus();
    }, 500);
  });
@endsection
