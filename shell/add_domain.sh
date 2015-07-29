#!/bin/bash

VERBOSE=0
PLATFORM="default"

usage() {
	clear
	printf "Usage: %s: [-v] [-u user] [-p platform] domain\n" $(basename $0) >&2
	
	cat << EOF

OPTIONS:
	-h	Prints this help guide.
	-p	The platform to setup. Available options are drupal or wordpress.
	-v	Verbose mode.
	-u	The system user to use when setting up web directory.

EOF
}

logmsg() {
	if [ $VERBOSE ]; then
		echo $1
	fi
}

while getopts 'hvu:p:' OPTION
do
	case $OPTION in
		v)
			VERBOSE=1
			;;
		u)
			DOMAIN_USER="$OPTARG"
			;;
		p)
			PLATFORM="$OPTARG"
			if [ "$PLATFORM" != "drupal" ] && [ "$PLATFORM" != "wordpress" ]; then
				echo "Invalid platform - Expecting drupal or wordpress"
				exit 1
			fi
			;;
		h|?)
			usage
			exit 2
			;;
	esac
done
shift $(($OPTIND - 1))

DOMAIN=$1

if [ -z $DOMAIN ]; then
	echo "Must provide domain name"
fi

# create nginx config if it doesn't exist
logmsg "Checking nginx config..."
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"
if [ ! -f $NGINX_CONF ]; then
  /bin/sed "s/\$domain/$DOMAIN/g" /etc/nginx/sites-available/.skel-default > /etc/nginx/sites-available/$DOMAIN.tmp
  /bin/sed "s/\$platform/$PLATFORM/g" /etc/nginx/sites-available/$DOMAIN.tmp > /etc/nginx/sites-available/$DOMAIN
  rm /etc/nginx/sites-available/$DOMAIN.tmp
fi

# create web directory if it doesn't exist
logmsg "Checking web directory..."
WEB_PATH="/var/www/$DOMAIN"
if [ ! -d $WEB_PATH ]; then
  cp -Rf /var/www/.skel /var/www/$DOMAIN
  
  # set ownership on web directory
	if [ -z $DOMAIN_USER ]; then
	  chown -R www-data:www-data /var/www/$DOMAIN
	else
	  chown -R $DOMAIN_USER:www-data /var/www/$DOMAIN
	fi
fi

logmsg "Enabling site in nginx..."
if [ ! -e "/etc/nginx/sites-enabled/$DOMAIN" ]; then
  ln -s $NGINX_CONF /etc/nginx/sites-enabled/$DOMAIN
fi

logmsg "Restarting nginx..."
service nginx restart
