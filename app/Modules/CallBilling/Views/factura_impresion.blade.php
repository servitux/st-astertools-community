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

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura Nº {{ $invoice->first()->invoice }} -
      @if (isset($room))
        Extensión Nº {{ $room->room }}
      @endif
      @if (isset($group))
        Grupo {{ $group->group }}
      @endif
    </title>

    <style>
    .invoice-box{
        max-width:800px;
        margin:auto;
        padding:30px;
        font-size:12px;
        line-height:12px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;
    }

    .invoice-box table{
        width:100%;
        text-align:left;
    }

    .invoice-box table td{
        padding:5px;
        vertical-align:top;
    }

    .invoice-box table tr td:nth-child(2){
        text-align:right;
    }

    .invoice-box table tr.top table td{
        padding-bottom:20px;
    }

    .invoice-box table tr.top table td.title{
        font-size:45px;
        line-height:45px;
        color:#333;
    }

    .invoice-box table tr.information table td{
        padding-bottom:40px;
    }

    .invoice-box table tr.heading td{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
        text-align: left;
    }

    .invoice-box table tr.heading td.text-right{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
        text-align: right;
    }

    .invoice-box table tr.details td{
        padding-bottom:20px;
    }

    .invoice-box table tr.item td{
        border-bottom:1px solid #eee;
        text-align: left;
    }

    .invoice-box table tr.item td.text-right{
        border-bottom:1px solid #eee;
        text-align: right;
    }

    .invoice-box table tr.item.last td{
        border-bottom:none;
    }

    .invoice-box table tr.total td:nth-child(4) {
        border-top:2px solid #eee;
        text-align:right;
        font-weight:bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }

        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="3">
                    <table>
                        <tr>
                            <td>
                              {{ $information->name }}<br>
                              {{ $information->address1 }}<br>
                              {{ $information->address2 }}<br>
                              {{ $information->postCode }} {{ $information->city }} ({{ $information->province }})<br>
                              C.I.F.: {{ $information->cif }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="text-align: right">
                                Informe de Llamadas #: {{ $invoice->first()->invoice }}<br>
                                Fecha Emisión: {{ \Carbon\Carbon::parse($invoice->first()->creationDate)->format("d/m/Y") }}<br>
                                Desde Fecha: {{ \Carbon\Carbon::parse($invoice->first()->fromDate)->format("d/m/Y") }}<br>
                                Hasta Fecha: {{ \Carbon\Carbon::parse($invoice->first()->toDate)->format("d/m/Y") }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="5">
                    <table>
                        <tr>
                            <td>
                                @if (isset($room))
                                  {{ $room->name }}<br>
                                @endif
                                @if (isset($group))
                                  {{ $group->name }}<br>
                                @endif
                                {{ $invoice->count() }} llamadas realizadas
                            </td>

                            <td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Fecha/Hora</td>
                <td>Destino</td>
                <td>Tipo</td>
                <td>Duración</td>
                <td class="text-right">Total</td>
            </tr>
            @foreach ($items as $item)
              <tr class="item">
                <td>{{ \Carbon\Carbon::parse($item->callDate)->format("d/m/Y H:i:s") }}</td>
                <td>{{ $item->destination }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ gmdate("H:i:s", $item->billSec) }}</td>
                <td class="text-right">{{ number_format($item->total, 2) }}</td>
              </tr>
            @endforeach
            <tr class="item last">
                <td colspan="5">
                </td>
            </tr>

            <tr class="total">
              <td></td>
              <td></td>
              <td></td>

                <td colspan="2">
                  Base Imponible: {{ number_format($invoice->sum('total'), 2) }}€<br>
                  %IVA 21%: {{ number_format($invoice->sum('total') * 21 / 100, 2) }}€<br>
                  Total: {{ number_format($invoice->sum('total') * 1.21, 2) }}€
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr class="bottom">
                <td colspan="5">
                    <table>
                        <tr>
                            <td>{{ $information->auxiliar }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
