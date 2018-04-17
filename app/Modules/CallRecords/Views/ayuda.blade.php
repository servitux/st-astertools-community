@extends('layouts.app')

@section('title')
  Documentación ST-AsterTools - Call Records
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-microphone"></i> Ayuda Call Records</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Call Records</strong> le ayuda a visualizar, buscar y escuchar las llamadas grabadas desde su centralita, agrupadas por tipos,
              origen, destino y duración. Puede escuchar las grabaciones directamente desde el navegador sin aplicaciones externas..<br><br>
              Esta herramienta es especialmente útil para controlar la formación de sus empleados.
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li><strong>Asterisk</strong> debe tener activada la función de Grabar <strong>Llamadas</strong></li>
                <li>La sintaxis del fichero de grabaciones tiene que ser esta: <strong>type</strong>-<strong>source</strong>-<strong>destination</strong>-<strong>date</strong>-<strong>time</strong>-<strong>uniqueid</strong>.wav<br><strong>Ejemplo</strong>: exten-104-100-20170704-094112-1499154072.35962.wav</li>
              </ol>
            </p>
            <h3>Llamadas Grabadas</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_records/llamadas.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_records/llamadas.png') }}" width="950"></a>
            </p>
            <p>
              Desde esta sección podrá visualizar, buscar y escuchar las llamadas grabadas hasta ese momento. Los datos que se visualizan son los siguientes:
              <ul>
                <li><strong>ID</strong>: ID de la llamada</li>
                <li><strong>Tipo</strong>: Tipo de llamadas. Los valores disponibles son "Entrante", "Saliente" o "Interna"</li>
                <li><strong>Fecha/Hora</strong>: Fecha y Hora de la grabación</li>
                <li><strong>Origen</strong>: Nº de origen de la llamada</li>
                <li><strong>Destino</strong>: >Nº de destino de la llamada</li>
                <li><strong>Grabación</strong>: Desde este campo podrá escuchar la grabación desde el propio navegador pulsando <i class="fa fa-play"></i></li>
              </ul>
            </p>
            <p>
              Entre las opciones disponibles por cada grabación, se encuentran la opción de <span class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Guardar</span> en formato WAV,
              y la opción de <span class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Eliminar</span> la grabación
            </p>

          </section>
        </section>
      </div>
    </div>
  </div>
@endsection

@section('script')
@endsection

@section('jquery_onload')
@endsection
