@extends('layouts.app')

@section('title')
  Grabaciones de Llamadas
@endsection

@section('buttons')
  <button class="btn btn-app bg-red btn-clean pull-right"><i class="fa fa-eraser"></i>Limpiar</button>
  <form id="delete" action="{{ action('\App\Modules\CallRecords\Controllers\RecordController@deleteAudio') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input name="id" type="hidden" value="0">
  </form>
  <form id="clean" action="{{ action('\App\Modules\CallRecords\Controllers\RecordController@clean') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
  </form>
@endsection

@section('breadcrumb')
  <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Principal</a></li>
  <li><a href="{{ url('/grabaciones/llamadas') }}"><i class="fa fa-microphone"></i> Grabaciones</a></li>
  <li class="active"><i class="fa fa-phone"></i> Llamadas</li>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-3">
      <form name="year" method="get" action="{{url('callrecords/llamadas')}}">
        <select class="form-control" name="year" id="year">
          @php
            $yearNow = \Carbon\Carbon::now()->year;
            $year = Request::has('year') ? Request::get('year') : \Carbon\Carbon::now()->year;
          @endphp
          @for ($i = $yearNow - 10; $i <= $yearNow; $i++)
            <option value="{{$i}}" {{ $year==$i ? "selected" : ""}}>{{$i}}</option>
          @endfor
        </select>
      </form>
    </div>
    <div class="col-md-offset-3 col-md-6">
      <div class="progress-group">
        <span class="progress-text">Espacio Usado de Disco</span>
        <!-- <span class="progress-number"><b>{{ $disk['used'] }}</b>/{{ $disk['total'] }}</span>-->
        <span class="progress-number"><b>{{ $disk['percent'] }}</span>

        <div class="progress sm">
          <div class="progress-bar progress-bar-{{$disk['color']}}" style="width: {{ $disk['percent'] }}"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    @php
      //columnas del data table
      $columns = array();
      $columns[] = array('data' => 'uniqueId_string', 'title' => 'ID', 'width' => '120px');
      $columns[] = array('data' => 'type_string', 'title' => 'Tipo');
      $columns[] = array('data' => 'callDate_string', 'title' =>  'Fecha/Hora', 'type' => 'date');
      $columns[] = array('data' => 'source_string', 'title' => 'Origen');
      $columns[] = array('data' => 'destination_string', 'title' => 'Destino');
      $columns[] = array('data' => 'audio', 'title' => 'Grabación');
      $columns[] = array('data' => 'options', 'title' => 'Opciones', 'width' => '100px', 'sort' => false);
      //html5 datatable
      AdminLTE::DataTable_UI("dataTable", "Listado de Grabaciones", $columns);
    @endphp
  </div>

</section>
@endsection

@section('script')
  @php
    //script datatable
    AdminLTE::DataTable_Script("dataTable", $columns, url("/callrecords/llamadas/datatable?year=$year"), 50, 2, 'asc');
  @endphp
@endsection

@section('jquery_onload')
  {{ AdminLTE::DataTable_Focus() }}
  {{ AdminLTE::Menu_Active("callrecords") }}
  {{ AdminLTE::Menu_Active("callrecords_llamadas") }}

  $('body').on('click', '.btn-delete', function() {
    var btn = this;
    var code = $(this).data('code');
    swal({
      title: '¿ Eliminar Grabación ?',
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
      text: 'Esto limpia registros de la base de datos, y los rehace con los ficheros de las grabaciones',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '¡ Si, Limpiar !'
    }).then(function () {
      $('form[id=clean]').submit();
    });
  });

  $('select[name=year]').change(function() {
    $('form[name=year]').submit();
  });
@endsection
