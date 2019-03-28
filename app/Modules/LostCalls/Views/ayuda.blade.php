@extends('layouts.app')

@section('title')
  Documentación ST-AsterTools - Lost Calls
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-microphone"></i> Ayuda Lost Calls</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Lost Calls</strong> le ayuda a visualizar, buscar y devolver las llamadas llamadas perdidas, agrupadas año/mes,
              origen, fecha/hora y estado. Puede devolver las llamas, o ver si algún compañero la ha devuelto ya<br><br>
              Esta herramienta es especialmente útil para no perder ninguna llamada de sus clientes.
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Este módulo <strong>sólo es configurable por Servitux Servicios Informáticos</strong>, al utilizar scripts propios</li>
                <li>Póngase en contacto con <strong><a href='https://www.servitux.es/contactar/' target="_blank">Servitux</a></strong> si desea este módulo activo</li>
              </ol>
            </p>
            <h3>Llamadas Perdidas</h3>
            <p>
              <a href='{{ asset('assets/images/help/lost_calls/perdidas.png') }}' target="_blank"><img src="{{ asset('assets/images/help/lost_calls/perdidas.png') }}" width="950"></a>
            </p>
            <p>
              Desde esta sección podrá visualizar, buscar y devolver las llamadas llamadas perdidas. Los datos que se visualizan son los siguientes:
              <ul>
                <li><strong>ID</strong>: ID de la llamada</li>
                <li><strong>Teléfono</strong>: Nº de origen de la llamada. Si se tiene activado el módulo "<strong>Phone Book</strong>", puede añadir el número a la agenda</li>
                <li><strong>Nombre</strong>: Nombre de la persona origen de la llamada. Si se muestra el icono <i class='fa fa-address-book-o'></i> junto al nombre, indica que el Nombre
                lo ha sacado del módulo "<strong>Phone Book</strong>", en caso de estar activado.</li>
                <li><strong>Fecha/Hora</strong>: Fecha y Hora de la llamada</li>
                <li><strong>Estado</strong>: Estado de la llamada. Los valores disponibles son "Perdida" o "Devuelta"</li>
                <li><strong>Fecha/Hora Devolución</strong>: Fecha y Hora de la devolución de la llamada</li>
              </ul>
            </p>
            <p>
              Entre las opciones disponibles por cada llamada perdida, se encuentran la opción de <span class="btn btn-success btn-xs"><i class="fa fa-phone"></i></span> para devolver la llamada,
              y la opción de <span class="btn btn-primary btn-xs">Devuelta</span> para marcar la llamada como devuelta.

              Con permisos de Administrador, tienes la opción de eliminar llamadas, o vaciar la lista completa.
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
