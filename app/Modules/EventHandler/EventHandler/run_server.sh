#!/bin/sh

#/*
#    (c) 2019 ServiTux Servicios Informaticos S.L. <info@servitux.es>
#*/


DIREC=/usr/local/bin/

mknod /dev/tty9 c 4 9 2>/dev/null

TTY=9

if [ "$TTY" != "" ]; then
	if [ -c /dev/tty${TTY} ]; then
		TTY=tty${TTY}
	elif [ -c /dev/vc/${TTY} ]; then
		TTY=vc/${TTY}
	else
		echo "Cannot find your TTY (${TTY})" >&2
		exit 1
	fi
fi

while :; do
		if [ "$TTY" != "" ]; then
			cd ${DIREC}
			stty sane < /dev/${TTY}
			node /var/www/st-astertools-community/app/Modules/EventHandler/EventHandler/app.js >/dev/null 2>&1 #>& /dev/${TTY} < /dev/${TTY}
		fi
		sleep 5
done
