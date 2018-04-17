{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/Fax/Views
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
  Documentación ST-AsterTools - Fax
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-commenting"></i> Ayuda Fax</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Fax</strong> gestiona el envío y recepción de sus faxes, sin necesidad de un Fax físico. Consule el historial de faxes y reenvíelos por correo, si fuera necesario.<br><br>
              Especialmente útil para la gestión de faxes por departamentos y extensiones<br><br>
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Se necesita la librería<strong>libtiff-tools</strong>, que incluye la herramienta <strong>tiff2pdf</strong></li>
                <li>Modificar el DIALPLAN de Asterisk para incluir el fichero dialplan.conf que hay en la ruta app/Modules/Fax/dialplan.conf</li>
              </ol>
            </p>
            <h3>Faxes Recibidos</h3>
            <p>
              <a href='{{ asset('assets/images/help/fax/recibidos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/recibidos.png') }}" width="950"></a>
            </p>
            <p>
              En esta sección podrá comprobar los faxes recibidos, guardarlos en formato PDF o eliminarlos.<br><br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>ID</strong>: ID del Fax Recibido</li>
                <li><strong>Fecha/Hora</strong>: Fecha/Hora del Fax</li>
                <li><strong>Origen</strong>: Número de teléfono origen desde el que se envío el Fax</li>
                <li><strong>Destino</strong>: Número de teléfono destino al que ha llegado el Fax</li>
                <li><strong>Páginas</strong>: Número de páginas recibidas</li>
              </ul>
              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-send"></i> Enviar Fax</span>: Envía un nuevo Fax</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Descargar</span>: Descarga el Fax seleccionado</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o"></i> Ver</span>: Muestra el Fax seleccionado en pantalla</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Eliminar</span>: Elimina el Fax recibido seleccionado</li>
              </ul>
            </p>
            <h3>Faxes Enviados</h3>
            <p>
              <a href='{{ asset('assets/images/help/fax/enviados.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/enviados.png') }}" width="950"></a>
            </p>
            <p>
              En esta sección podrá comprobar los faxes enviados, guardarlos en formato PDF o eliminarlos.<br><br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>ID</strong>: ID del Fax Recibido</li>
                <li><strong>Fecha/Hora</strong>: Fecha/Hora del Fax</li>
                <li><strong>Origen</strong>: Número de teléfono origen desde el que se envío el Fax</li>
                <li><strong>Destino</strong>: Número de teléfono destino al que ha llegado el Fax</li>
                <li><strong>Páginas</strong>: Número de páginas enviadas</li>
                <li><strong>Intentos</strong>: Número de intentos de envío y/o posible error en el envío</li>
              </ul>
              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-send"></i> Enviar Fax</span>: Envía un nuevo Fax</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Descargar</span>: Descarga el Fax seleccionado</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o"></i> Ver</span>: Muestra el Fax seleccionado en pantalla</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Eliminar</span>: Elimina el Fax recibido seleccionado</li>
              </ul>
            </p>
            <h3>Enviar Fax</h3>
            <p>
              <a href='{{ asset('assets/images/help/fax/enviar.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/enviar.png') }}" width="950"></a>
            </p>
            <p>
              Desde esta sección podrá enviar cualquier fax, utilizando los números de Fax registrados en el sistema, y adjuntando los documentos en formato
              PDF que sean necesarios (hasta un máximo de 5 ficheros)<br><br>
              Los campos a rellenar para realizar un envío son los siguientes:
              <ul>
                <li><strong>Nº Fax</strong>: Fax Origen. Se dan de alta y se agrupan su respectivo mantenimiento</li>
                <li><strong>Nº Fax Destino</strong>: Nº de Fax de destino</li>
                <li><strong>Ficheros PDF</strong>: Un máximo de 5 ficheros PDF para enviar por Fax</li>
              </ul>
            </p>
            <p>
              Al pulsar el botón <span class="btn btn-primary btn-xs">Enviar</span> comenzará el envío del Fax. Puede comprobar el estado del envío desde
              la sección de <strong>Faxes Enviados</strong>
            </p>

            <h3>Grupos</h3>
            <p>
              <a href='{{ asset('assets/images/help/fax/grupos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/grupos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Grupos, tendrá acceso a ver los grupos de números de fax creados actualmente, pudiendo navegar o buscar entre ellos,
              así como crear grupos nuevos.<br>
              Estos grupos pueden ser despachos, departamentos, plantas, o lo que considere en cada momento.<br><br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Nombre</strong>: Nombre asignado al grupo</li>
                <li><strong>Email</strong>: Email asignado al grupo donde recibirán las notificaciones de faxes enviados o recibidos</li>
                <li><strong>Teléfonos</strong>: Pequeña representación de los teléfonos contenidos en un grupo, así como su número total</li>
                <li><strong>Recibidos</strong>: Número de Faxes totales recibidos en el grupo</li>
                <li><strong>Enviados</strong>: Número de Faxes totales enviados en el grupo</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un grupo concreto para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Grupo</span> podrá crear nuevos grupos y asignarles teléfonos.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/fax/grupo.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/grupo.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Grupo concreto, podemos ver sus datos básicos, así como los teléfonos asociados al grupo.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del grupo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina el grupo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todos los grupos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Grupo</span>: Crea un nuevo grupo</li>
              </ul>
            </p>

            <h3>Teléfonos</h3>
            <p>
              <a href='{{ asset('assets/images/help/fax/telefonos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/telefonos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Teléfonos, tendrá acceso a ver los números de Fax creados actualmente, pudiendo navegar o buscar entre ellos,
              así como crear nuevos teléfonos.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Nº Teléfono</strong>: Número de Fax</li>
                <li><strong>Grupo</strong>: Grupo de números de teléfono al que pertenece</li>
                <li><strong>Recibidos</strong>: Número de Faxes totales recibidos en el teléfono</li>
                <li><strong>Enviados</strong>: Número de Faxes totales enviados en el teléfono</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un teléfono concreto para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nuevo Teléfono</span> podrá crear nuevas extensiones.<br><br>
            </p>
            <p>
              <a href='{{ asset('assets/images/help/fax/telefono.png') }}' target="_blank"><img src="{{ asset('assets/images/help/fax/telefono.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Teléfono concreto, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del teléfono actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina el teléfono actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas los teléfonos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nuevo Teléfono</span>: Crea un nuevo teléfono</li>
                <li><span class="btn btn-success btn-xs"><i class="fa fa-send"></i> Enviar Fax</span>: Envía un fax usando el teléfono actual</li>
              </ul>
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
