<?php

/**
 * @package     ST-AsterTools
 * @subpackage  app/Console/Commands
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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Filesystem\Filesystem;

use App\Modules\Fax\Models\Fax;
use App\Modules\Fax\Models\Group;
use App\Modules\Fax\Models\Telephone;
use App\Modules\Fax\Mail\FaxReceivedMail;

class FaxReceivedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fax:received {--to=?} {--dest=} {--file=} {--callerid=} {--headerinfo=} {--remotestationid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificaciones de Fax Recibido';


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

        //recibidos
        define("PATH_RECEIVED", "/Modules/Fax/Faxes/Recibidos/");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $dest = $this->option('dest');
      $telephone = Telephone::where('phone', $dest)->first();
      if (!$telephone)
      {
        $this->error('Teléfono Desconocido. No se grabará un registro de Fax recibido');
        return;
      }

      if ($telephone->group == 0)
      {
        $this->error('Teléfono sin Grupo. No se grabará un registro de Fax recibido');
        return;
      }

      $group = Group::find($telephone->group);
      if (!$group)
      {
        $this->error('Grupo desconocido. No se grabará un registro de Fax recibido');
        return;
      }

      $this->info("Recibiendo Fax para $group->name");

      $tif_file = $this->option("file");
      if ($tif_file && !file_exists($tif_file))
      {
        $this->error('email-fax dying, file ' . $tif_file . ' not found!');
        return;
      }

      if ($telephone->group == 0)
      {
        $this->error('email-fax dying, no destination found (to is empty)');
        return;
      }

      $to = $this->option('to');
      if ($to == "?")
        $to = $group->email;

      $callerid = $this->option('callerid');
      $subject = '[ST-PBX Fax] Fax recibido';
      if ($callerid) $subject .= ' de ' . $callerid;

      $headerinfo = $this->option("headerinfo");
      $remotestationid = $this->option("remotestationid");
      if (!$headerinfo) $headerinfo = $callerid;

    	$body = "En este correo encontrará adjunto un fichero con el fax que acaba de recibir.<br><br>
              Nº remitente/Identificador: $headerinfo $remotestationid / $callerid";

      $Filesystem = new Filesystem;

      $pdf_file = str_replace("tif", "pdf", $tif_file);
    	$pdf_file = $this->fax_file_convert('tif2pdf', $tif_file, $pdf_file);

      if ($tif_file || $pdf_file)
      {
        $this->sendMail($to, $subject, $body, $pdf_file);
        $path = app_path().PATH_RECEIVED."/$dest/";
        $Filesystem->makeDirectory($path, 0755, true, true);
        $Filesystem->put($path . "index.html", "");
        if ($tif_file) $Filesystem->copy($tif_file, $path.basename($tif_file));
        if ($pdf_file) $Filesystem->copy($pdf_file, $path.basename($pdf_file));

        $fax = new Fax;
        $fax->type = "R"; //recibido
        $fax->user_id = 0;
        $fax->src = $callerid;
        $fax->dst = $dest;
        $fax->src_filename = basename($tif_file);
        $fax->dst_filename = basename($pdf_file);

      	$datostif = $this->fax_tiffinfo($tif_file);
      	$fax->pages = $datostif['Page Number'];
        $fax->attempts = 0;

      	$fax->save();
      }
    }

    function sendMail($email, $subject, $body, $file)
    {
      if (empty($email))
      {
        $this->info("El grupo de fax del usuario no tiene establecido email. No se mandará notificación!");
        return;
      }

      $this->info("Mandando correo de recepción de Fax a " . $email . " con el fichero adjunto " . $file);
      $notify = new FaxReceivedMail("Fax::notifyreceived", $subject, $body, $this->FROM_EMAIL, $this->CC_EMAIL, $this->CCO_EMAIL, $file);
      Mail::to($email)->send($notify);
    }

    /**
     * Converts a file to different format
     * @param string - conversion type in the format of 'from2to'
     * @param string - path to origional file
     * @param string - path to save new file
     * @param bool - wether to keep or delete the orgional file
     *
     * @return string - path to fresh pdf
     *
     * Supported conversions:
     *	- pdf2tif
     *	- tif2pdf
     *	- ps2tif
     */
    function fax_file_convert($type, $in, $out) {
    	global $amp_conf;
    	//ensure file exists
    	if (!is_file($in)) {
    		return false;
    	}

    	//if file exists, assume its been converted already
    	if (file_exists($out)) {
    		return $out;
    	}

    	//convert!
			$cmd = "/usr/bin/tiff2pdf"
					. ' -c "Servitux Servicios Informáticos"'
					. ' -o ' . $out . ' ' . $in;

    	exec($cmd, $ret, $status);

    	return $status === 0 ? $out : $in;
    }

    /**
     * Get info on a tiff file. Require tiffinfo
     * @param string - absolute path to file
     * @param string - specifc option to receive
     *
     * @return mixed - if $opt & exists returns a string, else bool false,
     * otherwise an array of details
     */
    function fax_tiffinfo($file, $opt = '') {
    	//ensure file exists
    	if (!is_file($file)) {
    		return false;
    	}

    	$tiffinfo	= '/usr/bin/tiffinfo';
    	$info		= array();

    	if (!$tiffinfo) {
    		return false;
    	}
    	exec($tiffinfo . ' ' . $file, $output);

    	if ($output && strpos($output[0], 'Not a TIFF or MDI file') === 0) {
    		return false;
    	}

    	foreach ($output as $out) {
    		$o = explode(':', $out, 2);
    		$info[trim($o[0])] = isset($o[1]) ? trim($o[1]) : '';
    	}

    	if (!$info) {
    		return false;
    	}

    	//special case prossesing
    	//Page Number: defualt format = 0-0. Use only first set of digits, increment by 1
    	$info['Page Number'] = explode('-', $info['Page Number']);
    	$info['Page Number'] = $info['Page Number'][0] + 1;

    	if ($opt) {
    		return isset($info[$opt]) ? $info[$opt] : false;
    	}

    	return $info;
    }

}
