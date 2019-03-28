@extends('layouts.app')

@section('title')
  Agenda Teléfonos
@endsection

@section('buttons')
  <a href="{{ env('APP_URL') . '/agenda/telefono/new' }}" class="btn btn-app bg-blue"><li class="fa fa-id-card-o"></li>Nuevo Contacto</a>
  <button class="btn btn-primary btn-app btn-import bg-green pull-right"><i class="fa fa-download"></i>Importar</button>
  <form id="import" action="{{ action('\App\Modules\PhoneBook\Controllers\PhoneController@postImport') }}" method="post" class="hidden form-horizontal" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input id='csv' name='csv' type='file'>
  </form>

  @foreach (App\Modules\PhoneBook\Models\PBModule::all() as $module)
    @if ($module->active)
      <a href="{{ env('APP_URL') . '/api/pb/' . $module->token . '/' . $module->module . "."  . $module->format }}" class="btn btn-app bg-green pull-right"><li class="fa fa-upload"></li>{{ $module->module }}</a>
    @endif
  @endforeach
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/home/agenda/telefonos') }}"><i class="fa fa-address-book-o"></i> Agenda</a></li>
  <li class="active"><i class="fa fa-phone"></i> Teléfonos</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">

  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'id', 'title' => 'ID', 'width' => '25px');
      $columns[] = array('data' => 'name_string', 'title' => 'Nombre');
      $columns[] = array('data' => 'company_string', 'title' => 'Empresa');
      $columns[] = array('data' => 'address_string', 'title' => 'Dirección');
      $columns[] = array('data' => 'phone1_string', 'title' => 'Teléfono 1');
      $columns[] = array('data' => 'phone2_string', 'title' => 'Teléfono 2');
      $columns[] = array('data' => 'phone3_string', 'title' => 'Teléfono 3');
      $columns[] = array('data' => 'email_string', 'title' => 'Email');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Contactos", $columns)
    @endphp
  </div>
  <!-- /.row -->
</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, env('APP_URL') . "/agenda/telefonos/datatable");
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("agenda") }}
  {{ AdminLTE::Menu_Active("agenda_telefonos") }}

  $('body').on('click', '.telephone', function() {
    var tel = $(this).data('number');
    $.get('{{ action('\App\Modules\PhoneBook\Controllers\PhoneController@callPhone') }}', {number: tel})
      .done(function(e) {
        //alert(e);
      })
      .fail(function(jqXHR, textStatus, error) {
        alert("Error: " + error);
      });
  });

  $('.btn-import').on('click', function() {
    $('input[name=csv]').trigger('click');
  });
  $('input[name=csv]').on('change', function() {
    $('form[id=import]').submit();
  });

  $(document).on('click', '.btn-call', function() {
    var number = $(this).data('number');
    $.get('{{ action('\App\Modules\PhoneBook\Controllers\PhoneController@callPhone') }}', {number: number})
      .done(function(e) {
      })
      .fail(function(jqXHR, textStatus, error) {
        alert("Error: " + error);
      });
  });
@endsection
