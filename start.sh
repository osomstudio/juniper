#!/bin/bash

clear

echo "Checking if you have required packages."
sleep 2

phpcs_not_installed=$(which phpcs)
if [[ $phpcs_not_installed == "" ]]; then
  echo "PHPCS is not installed"
  exit
fi

phpcbf_not_installed=$(which phpcbf)
if [[ $phpcbf_not_installed == "" ]]; then
  echo "PHPCBF is not installed"
  exit
fi

standard_not_installed=$(phpcbf -i | grep "WordPress-Extra")
if [[ -z $standard_not_installed ]]; then
  echo "WordPress-Extra standard is not installed"
  exit
fi

echo "Checking if it's fresh install of project."
sleep 2

FILE=composer.json

if [ ! -f "$FILE" ]; then
  echo "No composer.json found."
  exit
fi

echo "I found composer.json. Let's begin downloading required libraries."
sleep 2

echo "# DATABASE" >> .env;
read -p "Enter db name: " dbname
echo "DB_NAME=$dbname" >> .env;
read -p "Enter db username: " dbuser
echo "DB_USER=$dbuser" >> .env;
read -p "Enter db password: " dbpwd
echo "DB_PASSWORD=$dbpwd" >> .env;
read -e -p "Enter db host (default: localhost): " -i "localhost" dbhost
echo "DB_HOST=$dbhost" >> .env;
read -e -p "Enter db prefix (default: wp_): " -i "wp_" dbprefix
echo "DB_PREFIX=$dbprefix" >> .env;
echo "#/ DATABASE" >> .env;
echo >> .env;
echo >> .env;
echo "# ENV" >> .env;
echo "WP_ENV=development" >> .env
read -p "Enter local domain name (eg. wordpress.local): " domain
echo "WP_HOME=http://$domain" >> .env;
echo 'WP_SITEURL=${WP_HOME}/wp' >> .env;
read -p "Enter ACF PRO KEY: " acfprokey
echo "ACF_PRO_KEY=$acfprokey" >> .env
echo "#/ ENV" >> .env;
echo >> .env;
echo >> .env;

htaccess="$(cat '.htaccess')"
> '.htaccess'
echo "$htaccess" | sed -r "s/juniper.local/$domain/g" >> '.htaccess'

echo "# SALTS" >> .env;
wget -qO - https://api.wordpress.org/secret-key/1.1/salt/ \
  | tr "'" '"' \
  | sed -n -e 's#^define("\([A-Z_]\+\)",\s\+\("[^"]\+"\));$#\1=\2#p' >> .env
echo "#/ SALTS" >> .env;

composer install --ignore-platform-reqs

cd web/app/themes/juniper-theme
npm install

composer install --ignore-platform-reqs

echo "That's all. If you set up your DB and htaccess correctly environment should be ready."
