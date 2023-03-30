#!/bin/bash

clear

read -p "What's the name of the CPT ? " full_name
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $full_name =~ ['!@#$%^&*()+'] ]]; then
    echo "CPT name can only have: letters, spaces, dashes, floors"
    exit
fi

#CREATING A SLUG
lowercase_full_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')
slug_name=$(echo $lowercase_full_name | tr ' ' '-')

#CHECK IF A CPT ALREADY EXISTS
[ -f "./web/app/themes/osom-theme/inc/cpt/class-${slug_name}.php" ] && echo "A CPT with this name already exists" && exit


cp "dev/cpt.txt" "./web/app/themes/osom-theme/inc/cpt/class-${slug_name}.php"

#GET TO CPT FOLDER
cd ./web/app/themes/osom-theme/inc/cpt/


searchSlug="replace_cpt_slug"
searchName="replace_cpt_name"
searchClassName="replace_cpt_slugCPT"
replaceClassName="${slug_name}CPT"

sed -i'' -e "s/$searchSlug/$slug_name/" class-${slug_name}.php
sed -i'' -e "s/$searchName/$full_name/" class-${slug_name}.php

rm class-${slug_name}.php-e

phpcbf "class-${slug_name}.php"

cd ..

composer dump-autoload -o

echo '$osom_cpt_replace_cpt_slug = new \Osom\replace_cpt_slugCPT();' >> include.php

sed -i'' -e "s/$searchSlug/$slug_name/" include.php
rm include.php-e

sed -i'' -e "s/$searchClassName/$replaceClassName/" include.php
rm include.php-e

phpcbf "include.php"
