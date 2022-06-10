#!/bin/bash

clear

echo "Checking if it's fresh install of project."
sleep 2

FILE=composer.json

if [ ! -f "$FILE" ]; then
  echo "It's fresh install."
  sleep

  clear

  read -p "Is your site going to be WooCommerce shop? (y/n) ? " woocommerce

  case "$woocommerce" in
    y ) echo "yes";;
    n ) echo "no";;
    * ) echo "invalid";;
  esac

  clear

  if [ $woocommerce == y ]
    then
    predefined="sl-wc.json"
  elif [ $woocommerce == n ]
    then
    predefined="sl.json"
  fi

  echo "Your site is going to use" $predefined "predefined structure at the beginning."

  sleep 5

  cp "./dev/"$predefined "./composer.json"
else
  echo "I found composer.json. Let's begin downloading required libraries."
  sleep 2
fi

echo "# DATABASE" >> .env;
read -p "Enter db name: " dbname
echo "DB_NAME=$dbname" >> .env;
read -p "Enter db username: " dbuser
echo "DB_USER=$dbuser" >> .env;
read -p "Enter db password: " dbpwd
echo "DB_PASSWORD=$dbpwd" >> .env;
read -p "Enter db host (default: localhost): " dbhost
[ -z "${dbhost}" ] && dbhost='localhost'
echo "DB_HOST=$dbhost" >> .env;
read -p "Enter db prefix (default: wp_): " dbprefix
[ -z "${dbprefix}" ] && dbprefix='wp_'
echo "DB_PREFIX=$dbprefix" >> .env;
echo "#/ DATABASE" >> .env;
echo "" >> .env;
echo "" >> .env;


echo "# ENV" >> .env;
echo "WP_ENV=development" >> .env
read -p "Enter local domain name (eg. wordpress.local): " domain
echo "WP_HOME=http://$domain" >> .env;
echo 'WP_SITEURL=${WP_HOME}/wp' >> .env;
echo "#/ ENV" >> .env;
echo "" >> .env;
echo "" >> .env;


echo "# SALTS" >> .env;
wget -qO /tmp/wp.keys https://api.wordpress.org/secret-key/1.1/salt/
echo "AUTH_KEY=\"$(cat /tmp/wp.keys |grep -w AUTH_KEY | cut -d \' -f 4)\"" >>  .env
echo "SECURE_AUTH_KEY=\"$(cat /tmp/wp.keys |grep -w SECURE_AUTH_KEY | cut -d \' -f 4)\"" >>  .env
echo "LOGGED_IN_KEY=\"$(cat /tmp/wp.keys |grep -w LOGGED_IN_KEY | cut -d \' -f 4)\"" >>  .env
echo "NONCE_KEY=\"$(cat /tmp/wp.keys |grep -w NONCE_KEY | cut -d \' -f 4)\"" >>  .env
echo "AUTH_SALT=\"$(cat /tmp/wp.keys |grep -w AUTH_SALT | cut -d \' -f 4)\"" >>  .env
echo "SECURE_AUTH_SALT=\"$(cat /tmp/wp.keys |grep -w SECURE_AUTH_SALT | cut -d \' -f 4)\"" >>  .env
echo "LOGGED_IN_SALT=\"$(cat /tmp/wp.keys |grep -w LOGGED_IN_SALT | cut -d \' -f 4)\"" >>  .env
echo "NONCE_SALT=\"$(cat /tmp/wp.keys |grep -w NONCE_SALT | cut -d \' -f 4)\"" >>  .env
echo "#/ SALTS" >> .env;

read -p "ACF PRO Key: " acfpro
echo "ACF_PRO_KEY=$acfpro" >> .env;

composer install --ignore-platform-reqs

cd web/app/themes/osom-theme
npm install

composer install --ignore-platform-reqs

cd ../../../wp/wp-content
rm -rf themes/*

echo "That's all. If you set up your DB and htaccess correctly environment should be ready."
