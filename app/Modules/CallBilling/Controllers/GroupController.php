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
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

use App\Modules\CallBilling\Models\Group;
use App\Models\Extension;
use App\Modules\CallBilling\Models\Price;
use App\Modules\CallBilling\Models\Invoice;
use App\Modules\CallBilling\Models\Information;

use App\Servitux\BaseController;
use App\Servitux\AdminLTE;
use App\Servitux\Servitux;
use App\Servitux\STInput;

class GroupController extends BaseController
{
  function __construct()
  {
      parent::__construct(new Group, "CallBilling::grupos", "tarificador/grupos", "CallBilling::grupo", "tarificador/grupo");

      //inputs en pantalla
      //****************************************
      $this->inputs['group'] = STInput::init("group", "Grupo", "text", 4)
                    ->addValidator("required")
                    ->addValidator("unique", "callbilling_groups", "", "id")
                    ->setImage('fa-object-group')
                    ->setGroup(0);
      $this->inputs['name'] = STInput::init("name", "Nombre", "text", 8)
                    ->addValidator("required")
                    ->addValidator("max", 255)
                    ->setGroup(0);
      //****************************************
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

        $entity->group_string = "<strong>" . $entity->group . "</strong>";
        $entity->name_string = "<a href='$url'><strong>" . $entity->name . "</strong></a>";
        if ($entity->getExtensions()->count() > 0)
        {
          $entity->extensions_string = "<strong>" . $entity->getExtensions()->count() . "</strong>";
          $entity->extensions_string .= " (";
          foreach ($entity->getExtensions() as $extension) {
            $entity->extensions_string .= $extension->extension . ", ";
          }
          $entity->extensions_string = substr($entity->extensions_string, 0, strlen($entity->extensions_string) - 2);
          if (strlen($entity->extensions_string) > 50) $entity->extensions_string = Str::limit($entity->extensions_string, 50, "...");
          $entity->extensions_string .= ")";
        }
        else
        {
          $entity->extensions_string = "No contiene extensiones";
        }
        $entity->consumption_string = "<div class='text-red'>" . $entity->getCDRConsumption() . "</div>";

        $entity->options = AdminLTE::Button_Group($buttons);
      }
      $data = array('data' => $entities);
      return $data;
  }

  //quitar del grupo las extensoines antes de borrarlo
  public function beforeDelete($model)
  {
    $extensions = $model->getExtensions();
    foreach ($extensions as $extension) {
      $extension->group = '-';
      $extension->save();
    }
  }

  //resetar habitación
  public function resetGroup($id)
  {
    //obtener el registro a modificar
    $group = Group::find($id);
    if (!$group) {
      abort(404);
    }

    $extensions = $group->getExtensions();
    foreach ($extensions as $extension)
    {
      $extension->lastOper = date('Y-m-d H:i:s');
      $extension->save();
    }

    return back();
  }

  //devuelve los datos necesarios para rellenar el datatable
  public function getExtensionsDataTable($id)
  {
    $group = Group::find($id);
    $extensions = Extension::where('group', $group->group)->get();

    foreach ($extensions as $extension)
    {
      $url = url("/tarificador/extension/" . $extension->id);
      $extension->extension_string = "<a href='$url'><strong>" . $extension->extension . "</strong></a>";
      $extension->consumption_string = "<div class='text-red'>" . $extension->getCDRConsumption() . "</div>";
    }
    $data = array('data' => $extensions);
    return $data;
  }

  public function getInvoicesDataTable($id)
  {
    $group = Group::find($id);
    $invoices = DB::table('callbilling_invoices')
                 ->select('invoice', 'group', 'creationDate', DB::raw('sum(total) as total'))
                 ->groupBy('invoice', 'group', 'creationDate')
                 ->having('group', '=', $group->group)
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

  public function invoiceGroup($id)
  {
    $group = Group::find($id);
    if (!$group)
      abort(404);

    $invoice = Invoice::max('invoice');
    if (!$invoice) $invoice = 0;

    $creationDate = date("Y-m-d H:i:s");

    $extensions = $group->getExtensions();
    foreach ($extensions as $extension)
    {
      if ($extension->CDR()->count() == 0)
        continue;

      $fromDate = $extension->CDR()->orderBy('calldate')->first()->calldate;
      $toDate = $extension->CDR()->orderBy('calldate', 'desc')->first()->calldate;

      $cdrs = $extension->CDR()->orderBy('calldate')->get();

      foreach ($cdrs as $cdr)
      {
        $price = self::getPriceData($cdr->dst);
        if ($price)
        {
          $item = new Invoice();
          $item->group = $group->group;
          $item->extension = $extension->extension;
          $item->invoice = $invoice + 1;
          $item->creationDate = $creationDate;
          $item->fromDate = $fromDate;
          $item->toDate = $toDate;

          $item->callDate = $cdr->calldate;
          $item->destination = (Str::startsWith($cdr->dst, '0') ? substr($cdr->dst, 1) : $cdr->dst);
          $item->type = $price->name;
          $item->billSec = $cdr->billsec;
          $item->price = $price->price;
          $item->total = number_format($price->callSetup + ($item->billSec * $item->price), 2);
          $item->save();
        }
        else
        {
          //abort(403, "no ha encontrado el valor " . $cdr->dst);
        }
      }

      $extension->lastOper = $creationDate;
      $extension->save();
    }

    $information = Information::find(1);
    if (!$information)
      $information = new Information;

    $result = Invoice::where('invoice', $invoice + 1);
    if ($result->count() == 0)
      return back()->with('zero_results', true);

    // instantiate and use the dompdf class
    $pdf = app()->make('dompdf.wrapper');
    $pdf = $pdf->loadView('tarificador.factura_impresion', array('group' => $group, 'information' => $information, 'invoice' => $result, 'items' => $result->get()));
    return $pdf->stream('invoice.pdf');
  }

  public function getInvoice($id, $invoice)
  {
    $group = Group::find($id);
    if (!$group) {
      abort(404, 'Grupo no encontrado');
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
    $pdf = $pdf->loadView('tarificador.factura_impresion', array('group' => $group, 'information' => $information, 'invoice' => $result, 'items' => $result->get()));
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
}
