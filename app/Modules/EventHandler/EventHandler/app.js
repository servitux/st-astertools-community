/*
 * @package     ST-AsterTools
 * @subpackage  app/Modules/EventHandler/EventHandler
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

process.chdir(__dirname);

var os = require('os');
var config = require('./config.json');

var express = require('express');
var app = express();

//WEB SOCKET SERVER
var web_server = require('http').Server(app);
var io = require('socket.io')(web_server);

//SOCKET SERVER
var server = require('net');

//MYSQL CLIENT
var mysqlobj = require('mysql');

//MANAGER CLIENT
var ami = null;

//FILESYSTEM PARA EL LOG
var fs = require('fs');
var logfile = "";

//carga la configuración
function loadConfig()
{
  delete require.cache[require.resolve('./config.json')];
  config = require('./config.json');
  log("Configuración Cargada");

  ami = new require('asterisk-manager')(
    config.asterisk.port,
    config.asterisk.host,
    config.asterisk.user,
    config.asterisk.password,
    true
  );

  var mysql = mysqlobj.createConnection({
    host     : config.mysql.host,
    user     : config.mysql.user,
    password : config.mysql.password,
    database : config.mysql.database,
    port     : config.mysql.port
  });

  events = [];
  for(var myKey in config.events)
  {
    if (config.events[myKey])
     events.push(myKey);
  }

  if (config.logpath != "")
  {
    logfile = config.logpath + 'astertools.log';
    if (!fs.existsSync(config.logpath))
    {
      fs.mkdirSync(config.logpath);
      fs.createWriteStream(logfile, {flags : 'w'});
    }
  }
}

//imprime mensaje consola
function log(message)
{
  var now = new Date();
  message = "EVENTHANDLER [" + now + "] " + message;
  console.log(message);

  if (config.logpath != "")
  {
    fs.appendFile(logfile, message + "\n", function (err) {
      //if (err) throw err;
    });
  }
}

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    return out;
}

//comprueba la whitelist
function checkConnection(address)
{
  var whitelist = config.sockets.whitelist.split(" ");
  var ip = address.split(":");
  return (config.sockets.whitelist == "" || whitelist.indexOf(ip[ip.length-1]) >= 0)
}

function json2xml(o, tab) {
   var toXml = function(v, name, ind) {
      var xml = "";
      if (v instanceof Array) {
         for (var i=0, n=v.length; i<n; i++)
            xml += ind + toXml(v[i], name, ind+"\t") + "\n";
      }
      else if (typeof(v) == "object") {
         var hasChild = false;
         xml += ind + "<" + name;
         for (var m in v) {
            if (m.charAt(0) == "@")
               xml += " " + m.substr(1) + "=\"" + v[m].toString() + "\"";
            else
               hasChild = true;
         }
         xml += hasChild ? ">" : "/>";
         if (hasChild) {
            for (var m in v) {
               if (m == "#text")
                  xml += v[m];
               else if (m == "#cdata")
                  xml += "<![CDATA[" + v[m] + "]]>";
               else if (m.charAt(0) != "@")
                  xml += toXml(v[m], m, ind+"\t");
            }
            xml += (xml.charAt(xml.length-1)=="\n"?ind:"") + "</" + name + ">";
         }
      }
      else {
         xml += ind + "<" + name + ">" + v.toString() +  "</" + name + ">";
      }
      return xml;
   }, xml="";
   for (var m in o)
      xml += toXml(o[m], m, "");
   return tab ? xml.replace(/\t/g, tab) : xml.replace(/\t|\n/g, "");
 }

var web_sockets = [];
var sockets = [];

var events = [];

loadConfig();

//WEB SOCKETS
//--------------------------------------------------------------------------------
web_server.listen(config.sockets.websocket_port, function() {
    log("Servidor web corriendo en http://localhost:" + config.sockets.websocket_port);
    /*mysql.connect(function(err) {
      if (err) {
        console.error('error connecting: ' + err.stack);
        return;
      }

      log('Conectado a MySql con el id ' + mysql.threadId);
    });

    mysql.query("SELECT * FROM cdr WHERE src = '108' AND calldate >= '2016-10-01 00:00:00'", function(err, rows, fields) {
      if (err) {
        console.error('error connecting: ' + err.stack);
        return;
      }
      log("Filas encontradas: " + rows.length);
      for (i = 0; i <= rows.length-1; i++) {
        log(i + ": " + JSON.stringify(rows[i]));
      }
    });

    mysql.end();*/
});

web_server.on('connection', function(socket) {
  var check = true;

  //var socket_address = socket.address().address;
  //var ip_socket = socket_address.split(":");
  //ip_socket = ip_socket[ip_socket.length-1];
  var ip_socket = socket.remoteAddress;

  //comprobar si es una ip interna
  var interfaces = os.networkInterfaces();
  Object.keys(interfaces).forEach(function (ifname) {
    interfaces[ifname].forEach(function (iface) {
      var ip_local = iface.address.split(":");
      ip_local = ip_local[ip_local.length-1];
      if (ip_socket == ip_local)
      {
        check = false;
        return;
      }
    });
  });

  //comprobar si está en el whitelist
  if (check && !checkConnection(ip_socket))
  {
    log('Conexión denegada: ' + ip_socket);
    socket.end();
    return;
  }
});

io.on('connection', function(socket) {
    if (!checkConnection(socket.handshake.address))
    {
      log('Conexión denegada: ' + socket.handshake.address);
      socket.disconnect();
      return;
    }
    log('Nuevo cliente web: ' + socket.handshake.address);
    socket.name = socket.handshake.address;
    web_sockets.push(socket);

    socket.on('disconnect', function() {
      log('Cliente web desconectado: ' + socket.handshake.address);

      var i = web_sockets.indexOf(socket);
      web_sockets.splice(i, 1);
   });

   socket.on('call', function (data) {
     var socket = this;
     log("Llamando a " + data.phone + " desde " + socket.name);
     ami.action({
      'action':'Originate',
      'ActionID': data.extension,
      'Async': 'yes',
      'channel':'SIP/servitux/' + data.phone,
      'context':'from-internal',
      'exten': data.extension, // el cero sólo servitux, o clientes que salgan con cero
      'priority': 1,
      'callerid': "\"" + data.name + "\" <"+data.phone+">",
      'timeout': data.timeout,
      'variable':{
        'CNAME': data.name,
        'CNUMBER': data.phone
      }
    }, function(err, res) {
      if (err != null)
      {
        log("Error Originate de " + socket.name + ": " + err.message + " - " + "\"" + data.name + "\" <"+data.phone+">");
        socket.emit("originate_error", err);
      }
      if (res != null)
      {
        log("Respuesta Originate de " + socket.name + ": " + dump(res));
      }
    });
  });

  socket.on('hangup', function (data) {
    var socket = this;
    log("Llamando a " + data.phone + " desde " + socket.name);
    ami.action({
     'action': 'hangup',
     'channel': data.channel
   }, function(err, res) {
     if (err != null)
     {
       log("Error Hangup de " + socket.name + ": " + err.message);
       socket.emit("originate_error", err);
     }
     if (res != null)
     {
       log("Respuesta Hangup de " + socket.name + ": " + dump(res));
     }
   });
  });

  socket.on('calls', function() {
    log("Pidiendo Llamadas");
    mysql.connect();

    mysql.query('SELECT 1 + 1 AS solution', function(err, rows, fields) {
      if (err) return null;

      return JSON.parse(rows);
    });

    mysql.end();
  });

  socket.on('restart', function() {
    return loadConfig();
  });
});
//--------------------------------------------------------------------------------

//CLIENT SOCKETS
//--------------------------------------------------------------------------------
server.createServer(function (socket) {
  if (!checkConnection(socket.remoteAddress))
  {
    log('Conexión denegada: ' + socket.remoteAddress);
    socket.end();
    return;
  }

  log('Nuevo cliente: ' + socket.remoteAddress);

  // Identify this client
  socket.name = socket.remoteAddress + ":" + socket.remotePort

  // Put this new client in the list
  sockets.push(socket);

  // Send a nice welcome message and announce
  //socket.write("Welcome " + socket.name + "\n\r");
  //broadcast(socket.name + " joined the chat\n", socket);

  // Handle incoming messages from clients.
  /*socket.on('data', function (data) {
    broadcast(socket.name + "> " + data, socket);
  });*/

  // Remove the client from the list when it leaves
  socket.on('end', function () {
    log('Cliente desconectado: ' + socket.remoteAddress);
    sockets.splice(sockets.indexOf(socket), 1);
    //broadcast(socket.name + " left the chat.\n");
  });
}).listen(config.sockets.socket_port);

//ASTERISK MANAGER
//--------------------------------------------------------------------------------
ami.on('connect', function(e) {
  log('Asterisk Manager conectado en ' + config.asterisk.host + ":" + config.asterisk.port);
  ami.keepConnected();
});

ami.on('error', function(e) {
  log('Error conectando con Asterisk: ' + e.message);
});

ami.on('managerevent', function(evt) {
  var event = "";
  if (config.asterisk.format == "json")
    event = JSON.stringify(evt);
  else
    event = json2xml(evt, false);

  var send = (events.indexOf(evt.event) >= 0)
  if (send)
  {
    log("Evento " + evt.event + " capturado ");
    log("Evento: " + event);
  }

  sockets.forEach(function (socket) {
    if (send)
    {
      log("Enviado Evento " + evt.event +  " a " + socket.name);
      socket.write(event + "\n\r");
    }
  });
  web_sockets.forEach(function (socket) {
    if (send)
    {
      log("Enviado Evento " + evt.event +  " a " + socket.name);
      socket.emit("event", event);
    }
  });
});

//--------------------------------------------------------------------------------
