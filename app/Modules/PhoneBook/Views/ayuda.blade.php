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
  Documentación ST-AsterTools - Phone Book
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-address-book-o"></i> Ayuda Phone Book</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Phone Book</strong> es un directorio compartido de contactos telefónicos entre todos los teléfonos físicos de su empresa,
              con sincronización automática, que le permitirá ordenar y buscar contactos más eficientemente.<br>
              Especialmente útil para Empresas con varios departamentos.<br><br>
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Se necesitan tener teléfonos de las marcas relacionadas con los módulos instalados.</li>
              </ol>
            </p>
            <h3>Contactos</h3>
            <p>
              <a href='{{ asset('assets/images/help/phone_book/telefonos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/phone_book/telefonos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Contactos, tendrá acceso a un directorio de números de teléfono que se sincronizará con sus teléfonos físicos.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>ID</strong>: ID del Teléfono</li>
                <li><strong>Nombre</strong>: Nombre asignado Teléfono</li>
                <li><strong>Teléfono 1</strong>: Nº de Teléfono de la primera posición del directorio</li>
                <li><strong>Teléfono 2</strong>: Nº de Teléfono de la segunda posición del directorio</li>
                <li><strong>Teléfono 3</strong>: Nº de Teléfono de la tercera posición del directorio</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un teléfono concreto para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Contacto</span> podrá crear nuevos contactos y asignarles teléfonos.<br>
              Mediante los botones de la parte superior derecha <span class="btn btn-success btn-xs"><i class="fa fa-upload"></i> Módulo</span> podrá exportar los directorios a formato XML o JSON según la configuración de cada módulo.<br>
            </p>
            <p>
              <a href='{{ asset('assets/images/help/phone_book/telefono.png') }}' target="_blank"><img src="{{ asset('assets/images/help/phone_book/telefono.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Contacto concreto, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del contacto actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina el contacto actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todos los contactos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Contacto</span>: Crea un nuevo contacto</li>
              </ul>
            </p>

            <h3>Módulos</h3>
            <p>
              <a href='{{ asset('assets/images/help/phone_book/modulos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/phone_book/modulos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Módulos, tendrá acceso a ver los módulos instalados según la marca de sus teléfonos físicos creados actualmente, pudiendo navegar o buscar entre ellos,
              así como crear nuevos módulos.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Módulo</strong>: Nombre del Módulo, normalmente una Marca</li>
                <li><strong>Descripción</strong>: Descripción del Módulo</li>
                <li><strong>Activo</strong>: Indica si el módulo está en funcionamiento</li>
                <li><strong>Versión</strong>: Versión del módulo</li>
                <li><strong>Formato</strong>: Formato de la comunicación/sincronización. XML o JSON</li>
                <li><strong>Peticiones</strong>: Número total de peticiones registradas</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un módulo concreto para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nuevo Módulo</span> podrá crear nuevos módulos.<br><br>
            </p>
            <p>
              <a href='{{ asset('assets/images/help/phone_book/modulo.png') }}' target="_blank"><img src="{{ asset('assets/images/help/phone_book/modulo.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Módulo concreto, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del módulo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas los módulos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nuevo Módulo</span>: Crea un nuevo módulo</li>
                <li><span class="btn btn-danger btn-xs"><i class="fa fa-toggle-off"></i> Desactivar Módulo</span>: Activa/Desactiva el módulo actual</li>
                <li><span class="btn btn-warning btn-xs"><i class="fa fa-asterisk"></i> Cambiar Token</span>: Genera un Token aleatorio necesario para la comunicación/sincronización con los teléfonos</li>
              </ul>
            </p>
            <p>
              El enlace de comunicación/sincronización con los teléfonos dependerá del Módulo / Marca, pero tendrá la siguiente sintaxis:
              <pre>http://www.dominio.com/api/pb/<i><strong>token</strong></i>/<i><strong>Modulo</strong></i>.xml</pre>
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
