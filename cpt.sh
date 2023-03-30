#!/bin/bash

clear

read -p "What's the name of the CPT (max 20 characters)? " full_name
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $full_name =~ ['!@#$%^&*()+'] ]]; then
    echo "CPT name can only have: letters, spaces, dashes, floors"
    exit
fi

#CREATING A SLUG
variable_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')
variable_name=$(echo $variable_name | tr ' ' '_')

lowercase_full_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')

slug_name=$(echo $lowercase_full_name | awk '{for(i=1;i<=NF;i++){$i=toupper(substr($i,1,1)) substr($i,2)}}1')
slug_name=${slug_name// /}

rewrite_name=$(echo $lowercase_full_name | tr ' ' '-')

#CHECK IF A CPT ALREADY EXISTS
[ -f "./web/app/themes/osom-theme/inc/Cpt/${slug_name}.php" ] && echo "A CPT with this name already exists" && exit


cp "dev/cpt.txt" "./web/app/themes/osom-theme/inc/Cpt/${slug_name}.php"

#GET TO CPT FOLDER
cd ./web/app/themes/osom-theme/inc/Cpt/


searchSlug="replace_cpt_slug"
searchName="replace_cpt_name"
searchRewrite="replace_rewrite_name"
searchClassName="replace_cpt_slugCPT"
replaceClassName="${slug_name}"

sed -i'' -e "s/$searchSlug/$slug_name/" ${slug_name}.php
sed -i'' -e "s/$searchName/$full_name/" ${slug_name}.php
sed -i'' -e "s/$searchRewrite/$rewrite_name/" ${slug_name}.php

rm ${slug_name}.php-e

phpcbf "${slug_name}.php"

cd ..

composer dump-autoload -o

echo '$osom_cpt_replace_cpt_slug = new \Osom\Cpt\replace_cpt_slugCPT();' >> include.php

sed -i'' -e "s/$searchSlug/$variable_name/" include.php
rm include.php-e

sed -i'' -e "s/$searchClassName/$replaceClassName/" include.php
rm include.php-e

phpcbf "include.php"
