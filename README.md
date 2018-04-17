# ST-AsterTools Community
[![Version](https://img.shields.io/badge/Version-v1.0-brightgreen.svg)]()
[![Dependecies](https://img.shields.io/badge/Laravel-v5.4.30-orange.svg)](https://laravel.com/)
[![Dependecies](https://img.shields.io/badge/AdminLTE-v2.3.7-orange.svg)](https://github.com/almasaeed2010/AdminLTE/releases)
[![Dependecies](https://img.shields.io/badge/NodeJS-v8.1.2-orange.svg)](https://nodejs.org/en/download/)
[![Dependecies](https://img.shields.io/badge/Servitux-Centralita_STPBX-yellow.svg)](https://www.servitux.es/servicios/operador-de-telefonia/centralita-asterisk-stpbx/)

Herramientas para Centralitas ST-PBX

## Centralita ST-PBX
Centralita Asterisk STPBX es un avanzado sistema de comunicación de alto rendimiento que le permitirá aprovechar al 100% el uso de sus lineas telefónicas y su conexión a Internet. El sistema Asterisk ST-PBX consta de 2 partes: el hardware de altas prestaciones (CPU, tarjetas de telefonía) y un software de última generación, el sistema operativo Linux y Asterisk PBX. ST-PBX es 100% híbrida, soporta tecnología analógica (RTB), digital (RDSI), GSM y VoIP.
El personal técnico de Servitux posee el certificado Digium dCAP que certifica los conocimientos y la práctica necesaria para implantar, configurar y mantener sistemas Asterisk de todo tipo, para cualquier tipo de empresa.

## Requisitos
```
Apache / lighttpd
MySQL / MariaDB
Git
Asterisk
php
```

## Instalación

### Instalar composer
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### Instalar ST-AsterTools
#### Desde Repositorio
```
composer create-project laravel/laravel st-astertools-community --prefer-dist
cd st-astertools-community
wget https://github.com/servitux/st-astertools-community/raw/master/servitux_init.php
php servitux_init.php
```
#### Con un Paquete tar.gz
```
composer create-project laravel/laravel st-astertools-community --prefer-dist
tar xzf st-astertools-community.tar.gz
cp -r st-astertools-community-[resto del nombre directorio]/* st-astertools-community/
cd st-astertools-community
php servitux_init.php
```

### Crear un usuario administrador
```
php artisan servitux:init
```

### Configuración Apache
```
Instalar mod2rewrite

Añadir la siguiente configuración al fichero de configuración de apache
(cambiar /var/www/html el path real):

<Directory /var/www/html>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
</Directory>
```

### Configuración lighttpd
```
Añadir la siguiente configuración al fichero de configuración lighttpd.conf
(cambiar st-astertools-community/public el path real):

server.modules = (
        "mod_access",
        "mod_alias",
        "mod_compress",
        "mod_redirect",
       "mod_rewrite",
)

# se supone que todo está en /servitux/
$HTTP["url"] =~ "servitux" {
        alias.url = ()
        url.redirect = ()
        url.rewrite-if-not-file = (
                "^/st-astertools-community/public/(css|img|js|fonts)/.*\.(jpg|jpeg|gif|png|swf|avi|mpg|mpeg|mp3|flv|ico|css|js|woff|ttf)$" => "$0",
                "^/st-astertools-community/public/(favicon\.ico|robots\.txt|sitemap\.xml)$" => "$0",
                "^/st-astertools-community/public/[^\?]*(\?.*)?$" => "/st-astertools-community/public/index.php/$1"
        )
        url.access-deny = ( "~", ".inc" )
}
```

### Ocultar Framework Laravel del resto de la Web (public)
```
mv st-astertools-community [path]/laravel
mv [path]/laravel/public st-astertools-community

Editar st-astertools-community/index.php:
  - Cambiar las dos rutas que existen para apuntar a [path]/laravel
Editar fichero [path]/laravel/.env:
  - Actualizar APP_URL quitando el public del final
```
