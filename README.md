<p align="center">
  <a href="https://osomstudio.com">
    <img alt="OsomStudio" src="https://www.osomstudio.com/hubfs/raw_assets/public/osom-studio-hs/images/images/global/osom-logo.svg" height="100">
  </a>
</p>


<p align="center">
  <strong>Juniper - WordPress starter boilerplate + theme</strong>
</p>

## Overview

OsomTheme is symbiosis of <a href="https://github.com/roots/bedrock">Bedrock</a> boilerplate and <a href="https://github.com/timber/timber">Timber</a>.

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

## Installation

1. Create local database as you would do for normal WP instance
2. Map project main catalogue to domain on your localhost
3. Start a new project:
   ```sh
   $ bash start.sh
   ```
   and follow the instructions in the console.
   Type in details from step 1 and 2. .env file will
   be crated for you (all DB and site details sits there)
4. Fill correct domain details in .htaccess in main catalogue.
5. Check if /web/ directory has .htaccess file with default WP entries.
6. Run 
   ```sh
   $ bash work.sh
   ```
   in main project directory
7. Start coding your theme in /web/app/themes/osom-theme/ :)

## Composer dependencies

To maintain project correctly we insist you to use composer.json in main catalogue.

If you want to add new plugin to your project you can use [WPackagist](https://wpackagist.org/) - 
the only thing you need is plugin slug from the main WP repository.

You can update them with the same easy way by changing version in composer.json.

## 