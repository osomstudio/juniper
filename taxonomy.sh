#!/bin/bash

clear

read -p "What's the name of the Taxonomy ? " full_name
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $full_name =~ ['!@#$%^&*()+'] ]]; then
    echo "Taxonomy name can only have: letters, spaces, dashes, floors"
    exit
fi

#CREATING A SLUG
variable_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')
variable_name=$(echo $variable_name | tr ' ' '_')

lowercase_full_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')

slug_name=$(echo $lowercase_full_name | awk '{for(i=1;i<=NF;i++){$i=toupper(substr($i,1,1)) substr($i,2)}}1')
slug_name=${slug_name// /}

rewrite_name=$(echo $lowercase_full_name | tr ' ' '-')

#CHECK IF A Taxonomy ALREADY EXISTS
[ -f "./web/app/themes/osom-theme/inc/Taxonomies/${slug_name}.php" ] && echo "A Taxonomy with this name already exists" && exit

read -p "What's the name of the select post type ? " selected_post_type
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $selected_post_type =~ ['!@#$%^&*()+'] ]]; then
    echo "Selected post type name can only have: letters, spaces, dashes, floors"
    exit
fi

cp "dev/taxonomy.txt" "./web/app/themes/osom-theme/inc/Taxonomies/${slug_name}.php"

#GET TO CPT FOLDER
cd ./web/app/themes/osom-theme/inc/Taxonomies/


searchSlug="replace_taxonomy_slug"
searchName="replace_taxonomy_name"
searchRewrite="replace_rewrite_name"
searchPostSlug="selected_post_type"

sed -i'' -e "s/$searchSlug/$slug_name/" ${slug_name}.php
sed -i'' -e "s/$searchName/$full_name/" ${slug_name}.php
sed -i'' -e "s/$searchRewrite/$rewrite_name/" ${slug_name}.php
sed -i'' -e "s/$searchPostSlug/$selected_post_type/g" ${slug_name}.php

rm ${slug_name}.php-e

phpcbf "${slug_name}.php"

cd ..

composer dump-autoload -o

echo '$osom_taxonomy_replace_rewrite_name = new \Osom\Taxonomies\replace_taxonomy_slug();' >> include.php

sed -i'' -e "s/$searchSlug/$slug_name/" include.php
rm include.php-e

phpcbf "include.php"
