<?php

/**
 * @package     STPBX
 * @subpackage  app/Console/Commands
 * @author      Servitux Servicios Informáticos, S.L.
 * @copyright   (C) 2017 - Servitux Servicios Informáticos, S.L.
 * @license     http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * @link        https://www.servitux.es - https://www.servitux-app.com - https://www.servitux-voip.com
 *
 * This file is part of STPBX.
 *
 * STPBX is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * STPBX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * STPBX. If not, see http://www.gnu.org/licenses/.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Modules\Fax\Models\Fax;
use App\Modules\Fax\Models\Group;
use App\Modules\Fax\Models\Telephone;
use App\Modules\Fax\Mail\FaxSendMail;
use App\Modules\Fax\Mail\FaxRecievedMail;

class FaxSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fax:send {arguments*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificaciones de Fax Enviado';

    private $FROM_EMAIL = "";
    private $CC_EMAIL = "";
    private $CCO_EMAIL = "";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $from = config('mail.from');
        $this->FROM_EMAIL = $from['address'];
        $cc = config('mail.cc');
        $this->CC_EMAIL = $cc['address'];
        $cco = config('mail.bcc');
        $this->CCO_EMAIL = $cco['address'];

        //message type
        define("ERROR_TYPE", "ERROR");
        define("NOTIFY_TYPE", "NOTIFY");

        //status
        define("SUCCESS_STATUS", "OK");
        define("NO_STATUS", "");
        define("NO_PAGES", 0);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $argv = $this->argument('arguments');

      if (count($argv) < 7 || count($argv) > 8)
      {
        $this->error('Argumentos incorrectos! [TYPE EMAIL DESTINATIO TIMESTAMP STATUS PAGES IDFAX [REASON]]');
        return;
      }

      $messtype   = $argv[0];
      $email      = $argv[1];
      $dest       = $argv[2];
      $timestamp  = $argv[3];
      $status     = $argv[4];
      $numpages   = $argv[5];
      $idfax	    = $argv[6];
      $reason     = "";
      if (count($argv) == 8) $reason = $argv[7];

      $fax = Fax::find($idfax);

      if($fax->attempts == 0 && $messtype == ERROR_TYPE)
      {
        $messtype = "INIT";
        $this->info('Primer envío con error, pasamos a ser INIT');
      }

      if ($messtype == "INIT")
      {
        $fax->attempts += 1;
        $fax->pages = -1;
        $fax->save();

        $subject = "[ST-PBX Fax] El Fax con destino $dest se está enviando...";
        $body = "El Fax con destino $dest procesado el $timestamp ha comenzado a enviarse.<br><br>
                Recibirá el resultado final en otro correo electronico cuando haya finalizado.<br><br>
                Le recordamos que tiene todos sus faxes disponibles en su panel de control ST-PBX";
        $this->sendMail($email, $fax, $subject, $body, $status);

        $this->info("Comienza el envío del fax a $dest");
        return;
      }

      if($messtype == ERROR_TYPE)
      {
        // error temporal (el numero comunicaba)
        $fax->attempts += 1;

        $this->info('Error temporal. El número de destino comunica. Intentos: ' . $fax->attempts);

        // marcamos como error temporal
        $fax->pages = -2;
        $fax->save();

        if($fax->attempts < 10)
          exit(0);

        $subject = "[ST-PBX Fax] El Fax con destino $dest NO se ha enviado correctamente";
        $body = "Hubo algun problema con el envío del Fax con destino $dest enviado el $timestamp";

        $this->sendMail($email, $fax, $subject, $body, $status, $reason);
        $this->info("Error definitivo a $dest: $status $numpages");

        $fax->pages = -3;
        $fax->save();
        return;
      }

      if ($messtype == NOTIFY_TYPE)
      {
        if($status == SUCCESS_STATUS)
        {
          $this->info("Envío de Fax OK a $dest: $status $numpages");
          $fax->pages = $numpages;
          $fax->save();

          $subject = "[ST-PBX Fax] El Fax con destino $dest se ha enviado correctamente";
          $body = "El Fax con destino $dest enviado el $timestamp se ha enviado correctamente.<br><br>
                    Le recordamos que tiene todos sus faxes disponibles en su panel de control ST-PBX";
          $this->sendMail($email, $fax, $subject, $body, $status);
          return;
        }

        // cuando sea el intento n 10, informamos del fallo definitivo
        $subject = "[ST-PBX Fax] El Fax con destino $dest NO se ha enviado correctamente";
        $body = "Hubo algun problema con el envío del Fax con destino $dest enviado el $timestamp";
        $this->sendMail($email, $fax, $subject, $body, $status);

        $this->info("Error definitivo a $dest: $status $numpages");

        $fax->pages = -3;
        $fax->save();
        return;
      }
    }

    function sendMail($email, $fax, $subject, $body, $status, $reason = "")
    {
      if (empty($email))
      {
        $this->info("El grupo de fax del usuario no tiene establecido email. No se mandará notificación!");
        return;
      }

      $notify = new FaxSendMail("Fax::notifysend", $fax, $subject, $body, $this->FROM_EMAIL, $this->CC_EMAIL, $this->CCO_EMAIL, $status, $reason);
      Mail::to($email)->send($notify);
    }
}
