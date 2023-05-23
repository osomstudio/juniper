<p align="center">
  <a href="https://wp-stars.com">
    <img alt="Juniper" src="https://5924544.fs1.hubspotusercontent-na1.net/hubfs/5924544/juniper/398672602-juniper-logo-01.png" height="300">
  </a>
</p>

<p align="center">
  <strong>Juniper - WordPress starter boilerplate + theme</strong>
</p>

## Overview

JuniperTheme is symbiosis of <a href="https://github.com/roots/bedrock">Bedrock</a> boilerplate and <a href="https://github.com/timber/timber">Timber</a>.

Bedrock is a modern WordPress stack that helps you get started with the best development tools and project structure.
Timber allows you to use twig templating system in your WP project.
With this approach you can create theme code with logic files separated from frontend.

## Features

- Easy WordPress configuration with environment specific files
- File structure which makes keeping and maintaining your project on a Git a lot easier
- Dependency management with [Composer](https://getcomposer.org)
- Twig templating system
- Bash console scripts to make creating project from the scratch much easier

## Requirements

- PHP >= 7.4
- Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Local Installation

1. Create local database as you would do for normal WP instance
2. Map project main catalogue to domain on your localhost
3. Start a new project:
   ```sh
   $ bash start.sh
   ```
   and follow the instructions in the console.
   Type in details from step 1 and 2. .env file will
   be crated for you (all DB and site details sits there)
4. Local url should be localhost:8888/juniper/web
5. Fill correct domain details in .htaccess in main catalogue.
6. Check if /web/ directory has .htaccess file with default WP entries.
7. Run 
   ```sh
   $ bash work.sh
   ```
   in main project directory
8. Start coding your theme in /web/app/themes/juniper-theme/ :)

## Remote Installation

Add ssh key from server to GitHub account

Clone repo

Run bash start.sh

Put this .htaccess in the public_html folder
```
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{HTTP_HOST} ^(www.)webdevw30.sg-host.com$
RewriteCond %{REQUEST_URI} !^/juniper/web/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /juniper/web/$1
RewriteCond %{HTTP_HOST} ^(www.)webdevw30.sg-host.com$
RewriteRule ^(/)?$ juniper/web/index.php [L]

RewriteEngine On
RewriteRule ^$ /juniper/web/ [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/juniper/web/
RewriteRule ^(.*)$ /juniper/web/$1

# This does not expose the internal URL.
RewriteCond %{SCRIPT_FILENAME} -d
RewriteRule ^juniper/web/(.*[^/])$ http://webdevw30.sg-host.com/$1/ [R=301]

</IfModule>
```
Folder structure ~/www.example.com/public_html/juniper/web/


## Deployment 

## Composer dependencies

To maintain project correctly we insist you to use composer.json in main catalogue.

If you want to add new plugin to your project you can use [WPackagist](https://wpackagist.org/) - 
the only thing you need is plugin slug from the main WP repository.

You can update them with the same easy way by changing version in composer.json.

## Bash scripts

The main operations that we automate have been handled by below scripts: :

1) start.sh - used for the initial configuration of the project. Through this process, we areable to enter the basic data to the database, define the main URL and ACF key. After providing those information, the installer will generate an .env file, which in our case will contain all configuration data (as in wp-config.php in a vanilla WordPress installation).

2) work.sh - used every time you work on a project. It compiles the styles in real time by calling a parcel script to listen for file changes.

Other scripts worth mentioning are:

1) block.sh - a set of commands that will create a custom Gutenberg block for the user. Juniper utilizes the ACF Timber Blocks solution, which requires only one .twig file with the appropriate comment to create a block.
2) cpt.sh - here the name speaks for itself. After going through the configuration, a CPT will be created. Its editing will of course be possible later, because this command will generate a file in the theme directory.

3) taxonomy.sh - similarly to the antecedent case, the answer to questions posed by a series of commands allows you to expeditiously create a taxonomy assigned to a given type of post.

## 
