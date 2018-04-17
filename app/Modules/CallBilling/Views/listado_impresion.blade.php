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
    <title>Informe de llamadas</title>

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

    .invoice-box table tr.total td:nth-child(4){
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
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <!-- <img src="http://domain.com/images/logo.png" style="width:100%; max-width:300px;"> -->
                            </td>

                            <td>
                                Fecha Emisión: {{ \Carbon\Carbon::now()->format("d/m/Y") }}<br>
                                Desde Fecha: {{ \Carbon\Carbon::parse($fromDate)->format("d/m/Y") }}<br>
                                Hasta Fecha: {{ \Carbon\Carbon::parse($toDate)->format("d/m/Y") }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                {{ $invoices->count() }} facturas encontradas
                            </td>

                            <td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Nº Factura</td>
                <td>Extensión</td>
                <td>Fecha Emisión</td>
                <td class="text-right">Total</td>
            </tr>
            @php
              $total = 0;
            @endphp
            @foreach ($invoices as $invoice)
              <tr class="item">
                <td>{{ $invoice->invoice }}</td>
                <td>{{ $invoice->extension }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->creationDate)->format("d/m/Y H:i:s") }}</td>
                <td class="text-right">{{ number_format($invoice->total * 1.21, 2) }}</td>
              </tr>
              @php
                $total += $invoice->total * 1.21;
              @endphp
            @endforeach
            <tr class="item last">
                <td colspan="6">
                </td>
            </tr>

            <tr class="total">
              <td></td>
              <td></td>
              <td></td>
                <td>
                  Total Informe: {{ number_format($total, 2) }}€
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
