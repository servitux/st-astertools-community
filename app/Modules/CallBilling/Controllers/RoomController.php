<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Modules/CallBilling/Controllers
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

namespace App\Modules\CallBilling\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF;
use App\Http\Controllers\Controller;

use App\Modules\CallBilling\Models\Group;
use App\Models\Extension;
use App\Modules\CallBilling\Models\Price;
use App\Modules\CallBilling\Models\Invoice;
use App\Modules\CallBilling\Models\Information;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class RoomController extends BaseController
{
  protected $lInputs = array();

  function __construct()
  {
      parent::__construct(new Extension, "CallBilling::extensiones", "tarificador/extensiones", "CallBilling::extension", "tarificador/extension");

      //inputs en pantalla
      //****************************************
      $this->inputs['extension'] = STInput::init("extension", "Nº Extensión", "text", 2)
                    ->addValidator("required")
                    ->addValidator("unique", "extensions", "", "id")
                    ->addValidator("numeric")
                    ->setImage('fa-bed')
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      $this->inputs['active'] = STInput::init("active", "Activo", "checkbox")
                    ->setDefaultValue(true)
                    ->setGroup(0);

      $this->inputs['group'] = STInput::init("group", "Grupo", "select", 4)
                    ->setSelectValues(Group::getSelect())
                    ->setDefaultValue('-')
                    ->setGroup(0);
      //****************************************

      //inputs listado
      //****************************************
      $this->lInputs['fromDate'] = STInput::init("fromDate", "Desde", "date", 4)
                    ->addValidator("required")
                    ->addValidator("date")
                    ->setImage('fa-calendar')
                    ->setGroup(0);

      $this->lInputs['toDate'] = STInput::init("toDate", "Hasta", "date", 4)
                    ->addValidator("date")
                    ->setImage('fa-calendar')
                    ->setGroup(0);
      //****************************************
  }

  function setValues($model)
  {
      parent::setValues($model);

      $this->inputs['active']->setVisibleValue($model->getHTMLState());
      $this->inputs['group']->setVisibleValue($model->getGroupDescription());
  }

  public function getEntitiesDataTable()
  {
      $entities = $this->base_model->all();
      foreach ($entities as $entity)
      {
        $url = url($this->url . "/" . $entity->id);
        $buttons = array();
        $buttons[] = array('class' => 'btn-primary', 'url' => $url, 'icon' => 'id-card-o', 'title' => 'Ver');
        $buttons[] = array('class' => 'btn-default', 'url' => $url . "/edit", 'icon' => 'pencil', 'title' => '');

        $entity->extension_string = "<strong>" . $entity->extension . "</strong>";
        $entity->name_string = "<strong>" . Servitux::Url($url, $entity->name) . "</strong>";
        $entity->active_string = $entity->getHTMLState();
        $entity->consumption_string =  "<div style='color: red'>" . $entity->getCDRConsumption() . "</div>";

        $group = Group::where('group', $entity->group)->first();
        if ($group)
          $entity->group_string = "<a href='" . url("/tarificador/grupo/" . $group->id) . "'>" . $entity->getGroupDescription() . "</a>";
        else
          $entity->group_string = $entity->getGroupDescription();

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  public function beforeSave($inputs, $model)
  {
    parent::beforeSave($inputs, $model);

    $model->active = (isset($inputs['active']) ? 1 : 0);
  }

  //resetar habitación
  public function resetRoom($id)
  {
    //obtener el registro a modificar
    $room = Extension::find($id);
    if (!$room) {
      abort(404);
    }

    $room->lastOper = date('Y-m-d H:i:s');
    $room->save();
    return back();
  }

  //habilitar habitación
  public function allowRoom($id)
  {
    //obtener el registro a modificar
    $room = Extension::find($id);
    if (!$room) {
      abort(404);
    }

    $active = Input::get('active');

    $room->active = $active;
    $room->save();
    return back();
  }

  //devuelve los datos necesarios para rellenar el datatable
  public function getCallsDataTable($id)
  {
    $room = Extension::find($id);
    $cdrs = $room->CDR()->get();

    foreach ($cdrs as $cdr)
    {
      if (Str::startsWith($cdr->dst, '0')) $cdr->dst = substr($cdr->dst, 1);
      $cdr->callDate = Carbon::parse($cdr->calldate)->format("Y-m-d H:i:s");
      $cdr->billsec = gmdate("H:i:s", $cdr->billsec);
    }
    $data = array('data' => $cdrs);
    return $data;
  }

  public function getInvoicesDataTable($id)
  {
    $room = Extension::find($id);
    $invoices = DB::table('callbilling_invoices')
                 ->select('invoice', 'extension', 'creationDate', DB::raw('sum(total) as total'))
                 ->groupBy('invoice', 'extension', 'creationDate')
                 ->having('extension', '=', $room->extension)
                 ->orderBy('invoice')
                 ->take(50)
                 ->get();

     foreach ($invoices as $invoice)
     {
       $invoice->creationDate = Carbon::parse($invoice->creationDate)->format("Y-m-d H:i:s");
       $invoice->total = number_format($invoice->total * 1.21, 2) . "€";

       $url = url($this->url . "/" . $id . "/invoice/" . $invoice->invoice);
       $buttons = array();
       $buttons[] = array('class' => 'btn-primary', 'url' => $url, 'icon' => 'print', 'title' => 'Imprimir', 'target' => 'blank_');

       $invoice->options = AdminLTE::Button_Group($buttons);
     }

    $data = array('data' => $invoices);
    return $data;
  }

  public function invoiceRoom($id)
  {
    $room = Extension::find($id);
    if ($room->CDR()->count() == 0)
      return back()->with('zero_results', true);

    $invoice = Invoice::max('invoice');
    if (!$invoice) $invoice = 0;
    $creationDate = Carbon::createFromTimestamp(time(), config('servitux.datetime_zone'))->toDateTimeString();
    $fromDate = $room->CDR()->orderBy('calldate')->first()->calldate;
    $toDate = $room->CDR()->orderBy('calldate', 'desc')->first()->calldate;

    $cdrs = $room->CDR()->orderBy('calldate')->get();

    foreach ($cdrs as $cdr)
    {
      $price = self::getPriceData($cdr->dst);
      if ($price)
      {
        $item = new Invoice();
        $item->group = ""; //sin grupo
        $item->extension = $room->extension;
        $item->invoice = $invoice + 1;
        $item->creationDate = $creationDate;
        $item->fromDate = $fromDate;
        $item->toDate = $toDate;

        $item->callDate = $cdr->calldate;
        $item->destination = (Str::startsWith($cdr->dst, '0') ? substr($cdr->dst, 1) : $cdr->dst);
        $item->type = $price->name;
        $item->billSec = $cdr->billsec;
        $item->price = $price->price;
        $item->total = number_format($price->callSetup + ($item->billSec * ($item->price / 60)), 2);
        $item->save();
      }
      else
      {
        //abort(403, "no ha encontrado el valor " . $cdr->dst);
      }
    }

    $room->lastOper = $creationDate;
    $room->save();

    $information = Information::find(1);
    if (!$information)
      $information = new Information;

    $result = Invoice::where('invoice', $invoice + 1);

    // instantiate and use the dompdf class
    $pdf = app()->make('dompdf.wrapper');
    $pdf = $pdf->loadView('CallBilling::factura_impresion', array('room' => $room, 'information' => $information, 'invoice' => $result, 'items' => $result->get()));
    return $pdf->stream('invoice.pdf');
  }

  public function getInvoice($id, $invoice)
  {
    $room = Extension::find($id);
    if (!$room) {
      abort(404, 'Habitación no encontrada');
      return;
    }
    $result = Invoice::where('invoice', $invoice);
    if ($result->count() == 0)
    {
      abort(404, 'Factura no encontrada');
      return;
    }

    $information = Information::find(1);
    if (!$information)
      $information = new Information;

    // instantiate and use the dompdf class
    $pdf = app()->make('dompdf.wrapper');
    $pdf = $pdf->loadView('CallBilling::factura_impresion', array('room' => $room, 'information' => $information, 'invoice' => $result, 'items' => $result->get()));
    return $pdf->stream('invoice.pdf');
  }

  private function getPriceData($dst)
  {
    if (Str::startsWith($dst, '0')) $dst = substr($dst, 1);
    while (!$price = Price::where('prefix', $dst)->first())
    {
      $dst = substr($dst, 0, strlen($dst)-1);
      if (empty($dst)) return null;
    }
    return $price;
  }

  public function getReport()
  {
    return view('CallBilling::listado', array('inputs' => $this->lInputs));
  }

  public function postReport(Request $request)
  {
    $this->createValidator($this->lInputs, null);

    //validar y guardar
    $validator = null;
    $inputs = $this->validateModel($request, $validator);
    if (!$inputs)
      return back()->withErrors($validator)->withInput($request->input)->with('errors_found', true);

    $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
    if ($request->toDate == "")
      $toDate = date("Y-m-d");
    else
      $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

    $invoices = DB::table('callbilling_invoices')
                 ->select('invoice', 'extension', 'creationDate', DB::raw('sum(total) as total'))
                 ->groupBy('invoice', 'extension', 'creationDate')
                 ->havingRaw("creationDate >= '" . $fromDate . " 00:00:00' AND creationDate <= '" . $toDate . " 23:59:59'")
                 ->orderBy('invoice')
                 ->get();

    // instantiate and use the dompdf class
    $pdf = app()->make('dompdf.wrapper');
    $pdf = $pdf->loadView('CallBilling::listado_impresion', array('invoices' => $invoices, 'fromDate' => $fromDate, 'toDate' => $toDate));
    return $pdf->stream('report.pdf');

    return back();
  }
}
