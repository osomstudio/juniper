#!/bin/bash

clear

directoryexists=0
directorypath=./web/app/themes/osom-theme/views/blocks

echo "Checking if default block directory exists."
sleep 2

if [ -d "./web/app/themes/osom-theme/views/blocks" ]
then
    echo "Directory exists."
    directoryexists=1
else
    echo "Error: Directory /web/app/themes/osom-theme/views/blocks does not exists."
    read -p "Type directory of your ACF Timber Blocks. (Default: ./web/app/themes/osom-theme/views/blocks)" blocksdirectory

    if [ $blocksdirectory == '' ]
      then
      echo "You did not type any other directory."
      sleep 5
      directoryexists=0
    else
      if [ -d $blocksdirectory ]
      then
        echo "Directory exists."
        directoryexists=1
        directorypath=blocksdirectory
      fi
    fi
fi


if [ $directoryexists == 1 ]
  then
  read -p "Type block title:" blocktitle
  read -p "Type block description:" blockdescription
  read -p "Type block category:" blockcategory
  read -p "Type block icon:" blockicon
  read -p "Type block keywords:" blockkeywords

  # first, strip underscores
  CLEANTITLE=${blocktitle//_/}
  # next, replace spaces with underscores
  CLEANTITLE=${CLEANTITLE// /_}
  # now, clean out anything that's not alphanumeric or an underscore
  CLEANTITLE=${CLEANTITLE//[^a-zA-Z0-9_]/}
  # finally, lowercase with TR
  CLEANTITLE=`echo -n $CLEANTITLE | tr A-Z a-z`

  echo "{#" >> "$directorypath/$CLEANTITLE.twig"
  echo "Title: $blocktitle" >> "$directorypath/$CLEANTITLE.twig"
  echo "Description: $blockdescription" >> "$directorypath/$CLEANTITLE.twig"
  echo "Category: $blockcategory" >> "$directorypath/$CLEANTITLE.twig"
  echo "Icon: $blockicon" >> "$directorypath/$CLEANTITLE.twig"
  echo "Keywords: $blockkeywords" >> "$directorypath/$CLEANTITLE.twig"
  echo "Mode: edit" >> "$directorypath/$CLEANTITLE.twig"
  echo "Align: left" >> "$directorypath/$CLEANTITLE.twig"
  echo "SupportsAlign: left right" >> "$directorypath/$CLEANTITLE.twig"
  echo "SupportsMode: true" >> "$directorypath/$CLEANTITLE.twig"
  echo "SupportsMultiple: true" >> "$directorypath/$CLEANTITLE.twig"
  echo "#}" >> "$directorypath/$CLEANTITLE.twig"

  echo "Block was created successfully."
elif [ $directoryexists == 0 ]
  then
  echo 'There was a problem with localizing your directory. Try again.';
fi


