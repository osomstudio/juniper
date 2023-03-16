#!/bin/bash

clear

read -p "What's the name of the module ? " full_name
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $full_name =~ ['!@#$%^&*()+'] ]]; then
    echo "Module name can only have: letters, spaces, dashes, floors"
    exit
fi

#CREATING A SLUG
lowercase_full_name=$(echo $full_name | tr '[:upper:]' '[:lower:]')
slug_name=$(echo $lowercase_full_name | tr ' ' '_')

#CHECK IF A BLOCK ALREADY EXISTS
[ -d "./web/app/themes/osom-theme/views/blocks/${slug_name}" ] && echo "A block with this name already exists" && exit

read -p "What are the keywords ? " keywords
#VALIDATION -IT CAN ONLY HAVE BIG AND SMALL LETTERS, SPACES, DASHES, FLOORS
if [[ $keywords =~ ['!@#$%^&*()+'] ]]; then
    echo "Module name can only have: letters, spaces, dashes, floors"
    exit
fi

read -p "Description ? " description


#CREATING ADNOTATION
frontend="{#\n
\tTitle: ${full_name}\n
\tDescription: ${description}\n
\tCategory: formatting\n
\tIcon: admin-comments\n
\tKeywords: ${keywords}\n
\tMode: edit\n
\tAlign: left\n
\tPostTypes: page post\n
\tSupportsAlign: left right\n
\tSupportsMode: true\n
\tSupportsMultiple: true\n"\
"#}"

#CREATING STYLES
styles=""\
".${slug_name} {}\n\n"\
"body.wp-admin {\n"\
"\t.${slug_name} {}\n"\
"}"\

scripts=""

functions="<?php\n\n"\
"add_action('wp_enqueue_scripts', function() {\n"\
"\tif (has_block('acf/${slug_name}')) {\n"\
"\t\$time = time();\n"\
"\t\$theme_path = get_template_directory_uri();\n\n"\
"\t\twp_enqueue_style('${slug_name}-css', \$theme_path . '/dist/blocks/${slug_name}/style.css', array(), \$time, 'all');\n"\
"\t\twp_enqueue_script('${slug_name}-js', \$theme_path . '/dist/blocks/${slug_name}/script.js', array(), \$time, true);\n"\
"\t}\n"\
"});\n\n
add_filter(\n\n
\t'timber/acf-gutenberg-blocks-data/${slug_name}',\n\n
\tfunction( \$context ) {\n
\treturn \$context;\n
});"

#GET TO BLOCKS FOLDER
cd ./web/app/themes/osom-theme/views/blocks/

# shellcheck disable=SC2016
touch "${slug_name}.twig"
echo -e $frontend > "${slug_name}.twig"

cd ../../

cd blocks



#CREATE BLOCK FOLDER AND GO INTO IT
mkdir $slug_name
cd $slug_name

#FILE CREATION
echo -e $styles > style.scss #COMPILE THESE FILES SO THEY END UP IN THE ./dist FOLDER
echo -e $scripts > script.js  #AND REMOVE THAT FOLDER FROM GIT
echo -e $ajax > ajax.js  #AND REMOVE THAT FOLDER FROM GIT
echo -e $functions > functions.php

phpcbf functions.php