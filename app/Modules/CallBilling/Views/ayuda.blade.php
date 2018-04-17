{{--
/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Views
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
  Documentación ST-AsterTools - Call Billing
@endsection

@section('breadcrumb')
  <li><i class="fa fa-dashboard"></i> <a href='{{ url('/home')}}'>Home</a></li>
  <li><i class="fa fa-book"></i> <a href='{{ url('/documentacion')}}'>Documentación</a></li>
  <li class="active"><i class="fa fa-money"></i> Ayuda Call Billing</li>
@endsection

@section('content')
  <div class="content container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <section class="content-max-width">
          <section id="installation">
            <h3>Presentación</h3>
            <p>
              <strong>Call Billing</strong> es un Tarificador que te ayudará a calcular y monetizar los gastos asociados a las llamadas realizadas
              desde las extensiones registradas, así como agruparlos por departamentos, y generando facturas en caso de ser necesario.<br><br>
              Esta herramienta es especialmente útil para controlar el gasto por habitación en Hoteles o por departamentos en una Empresa.
            </p>
            <h3>Requisitos</h3>
            <p>
              <ol>
                <li>Base de Datos <strong>CDR</strong> instalada.</li>
                <li>Indicar en el fichero <strong>config/database.php</strong>, en la sección <strong>connections.asterisk</strong> la ubicación y tipo de la base de datos <strong>CDR</strong>.</li>
              </ol>
            </p>
            <h3>Configuración</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/config.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/config.png') }}" width="950"></a>
            </p>
            <p>
              En este apartado deberá rellenar los datos de su empresa en caso de querer facturar el gasto por extensión o departamento. Estos
              datos aparecerán impresos en facturas e informes.
            </p>

            <h3>Grupos</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/grupos.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/grupos.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Grupos, tendrá acceso a ver los grupos de extensiones creados actualmente, pudiendo navegar o buscar entre ellos,
              así como crear grupos nuevos.<br>
              Estos grupos pueden ser despachos, departamentos, plantas, o lo que considere en cada momento.<br><br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Grupo</strong>: ID del grupo</li>
                <li><strong>Nombre</strong>: Nombre asignado al grupo</li>
                <li><strong>Extensiones</strong>: Pequeña representación de las extensiones contenidas en un grupo, así como su número total</li>
                <li><strong>Tiempo a Facturar</strong>: Tiempo total usado por las extensiones asignadas al grupo</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a un grupo concreto para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Grupo</span> podrá crear nuevos grupos y asignarles extensiones.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/grupo.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/grupo.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de un Grupo concreto, podemos ver sus datos básicos, así como las extensiones asociadas al grupo y sus facturas recientes.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos del grupo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina el grupo actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todos los grupos</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-object-group"></i> Nuevo Grupo</span>: Crea un nuevo grupo</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-file-text-o"></i> Facturar</span>: Factura el grupo completo, con la información de consumo de todas las extensiones</li>
                <li><span class="btn btn-warning btn-xs"><i class="fa fa-eraser"></i> Resetear</span>: Elimina el consumo que las extensiones contenidas en el grupo tengan hasta ese momento. Este opción se ejecuta sóla al facturar.</li>
              </ul>
            </p>

            <h3>Extensiones</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/extensiones.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/extensiones.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Extensiones, tendrá acceso a ver las extensiones creadas actualmente, pudiendo navegar o buscar entre ellas,
              así como crear nuevas extensiones.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Nº</strong>: Número de la extensión</li>
                <li><strong>Nombre</strong>: Nombre de la persona asociada a la extensión</li>
                <li><strong>Activa</strong>: Indica si la extensión está activa, y por lo tanto, recibe llamadas</li>
                <li><strong>Grupo</strong>: Grupo de extensiones al que pertenece la extensión</li>
                <li><strong>Tiempo a Facturar</strong>: Tiempo total usado por las extensiones asignadas al grupo</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a una extensión concreta para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span> podrá crear nuevas extensiones.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/extension.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/extension.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de una Extensión concreta, podemos ver sus datos básicos, así como un detalle de las llamadas realizadas y sus facturas recientes.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos de la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina la extensión actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas las extensiones</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-phone"></i> Nueva Extensión</span>: Crea una nueva extensión</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-file-text-o"></i> Facturar</span>: Factura la extensión actual (sin incluir las extensiones incluídas en su mismo grupo)</li>
                <li><span class="btn btn-warning btn-xs"><i class="fa fa-eraser"></i> Resetear</span>: Elimina el consumo que la extensión que tenga hasta ese momento. Este opción se ejecuta sóla al facturar.</li>
              </ul>
            </p>

            <h3>Tarifas</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/tarifas.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/tarifas.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Tarifas, tendrá acceso a ver las tarifas creadas actualmente, desde las cuales se obtendrán los importes para poder monetizar las extensiones,
              y así poder facturarlas. Desde esta sección puede navegar o buscar entre ellas, así como crear nuevas tarifas.<br>
              Entre la información visible se encuentran los siguientes campos:
              <ul>
                <li><strong>Prefijo</strong>: Prefijo del número destino</li>
                <li><strong>Nombre</strong>: Nombre/Descripción que identifica el prefijo</li>
                <li><strong>Precio</strong>: Precio por minuto</li>
                <li><strong>Establecimiento de Llamada</strong>: Precio del establecimiento de llamada</li>
              </ul>
            </p>
            <p>
              Pulsando sobre el botón <span class="btn btn-primary btn-xs"><i class="fa fa-id-card-o"></i> Ver</span> o <span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>, podrá acceder a una tarifa concreta para comprobar o editar su información. <br><br>
              Mediante el botón <span class="btn btn-primary btn-xs"><i class="fa fa-money"></i> Nueva Tarifa</span> podrá crear nuevas tarifas.
            </p>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/tarifa.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/tarifa.png') }}" width="950"></a>
            </p>
            <p>
              Una vez en la ficha de una Tarifa concreta, podemos ver sus datos básicos.

              Las opciones disponibles desde esta ficha son:
              <ul>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</span>: Editar los datos de la tarifa actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Eliminar</span>: Elimina la tarifa actual</li>
                <li><span class="btn btn-default btn-xs"><i class="fa fa-list"></i> Volver</span>: Vuelve a la ventana de detalle de todas las tarifas</li>
                <li><span class="btn btn-primary btn-xs"><i class="fa fa-money"></i> Nueva Tarifa</span>: Crea una nueva tarifa</li>
              </ul>
            </p>

            <h3>Listado</h3>
            <p>
              <a href='{{ asset('assets/images/help/call_billing/listado.png') }}' target="_blank"><img src="{{ asset('assets/images/help/call_billing/listado.png') }}" width="950"></a>
            </p>
            <p>
              Desde la sección de Listado, podrá obtener un listado de todas las facturas realizadas entre unas fechas concretas.
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
