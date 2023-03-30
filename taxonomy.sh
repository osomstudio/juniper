#!/bin/bash

clear

read -p "What's the name of the Taxonomy ? " full_name
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $full_name =~ ['!@#$%^&*()+'] ]]; then
    echo "Taxonomy name can only have: letters, spaces, dashes, floors"
    exit
fi

#CREATING A SLUG
lowercase_full_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')
slug_name=$(echo $lowercase_full_name | tr ' ' '-')

#CHECK IF A Taxonomy ALREADY EXISTS
[ -f "./web/app/themes/osom-theme/inc/taxonomies/class-${slug_name}.php" ] && echo "A Taxonomy with this name already exists" && exit

read -p "What's the name of the select post type ? " selected_post_type
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $selected_post_type =~ ['!@#$%^&*()+'] ]]; then
    echo "Selected post type name can only have: letters, spaces, dashes, floors"
    exit
fi

cp "dev/taxonomy.txt" "./web/app/themes/osom-theme/inc/taxonomies/class-${slug_name}.php"

#GET TO CPT FOLDER
cd ./web/app/themes/osom-theme/inc/taxonomies/


searchSlug="replace_taxonomy_slug"
searchName="replace_taxonomy_name"
searchClassName="replace_taxonomy_slugTaxonomy"
replaceClassName="${slug_name}Taxonomy"
searchPostSlug="selected_post_type"

sed -i'' -e "s/$searchSlug/$slug_name/" class-${slug_name}.php
sed -i'' -e "s/$searchName/$full_name/" class-${slug_name}.php
sed -i'' -e "s/$searchPostSlug/$selected_post_type/g" class-${slug_name}.php

rm class-${slug_name}.php-e

phpcbf "class-${slug_name}.php"

cd ..

composer dump-autoload -o

echo '$osom_taxonomy_replace_taxonomy_slug = new \Osom\replace_taxonomy_slugTaxonomy();' >> include.php

sed -i'' -e "s/$searchSlug/$slug_name/" include.php
rm include.php-e

sed -i'' -e "s/$searchClassName/$replaceClassName/" include.php
rm include.php-e

phpcbf "include.php"
