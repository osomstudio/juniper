#!/bin/bash

clear

echo "Checking if everything is set up correctly."
sleep 2

FILE=composer.json
ENV=.env

if [ ! -f "$FILE" ] && [ ! -f "$ENV" ]; then
  echo "Please use start.sh first."
else
  echo "It seems that your project is already installed. You can begin editing scss and js files now."
  sleep 1
  cd web/app/themes/osom-theme/
  npm run dev
fi


