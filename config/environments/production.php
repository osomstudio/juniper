<?php
/**
 * Configuration overrides for WP_ENV === 'staging'
 */

use Roots\WPConfig\Config;

/**
 * You should try to keep staging as close to production as possible. 
 */

Config::define('WP_DEBUG', false);
Config::define('DISALLOW_FILE_MODS', true);