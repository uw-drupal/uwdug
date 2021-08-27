<?php

/**
 * Redirect requests to Lando into the subdirectory.
 * Not sure this is needed for Drupal 9
 */
$uri = strtolower($_SERVER['REQUEST_URI']);
if ( $uri == '/' && (php_sapi_name() != "cli") ) {
    echo 'https://'. $_SERVER['HTTP_HOST'] . '/uwdrupal/';
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: http://'. $_SERVER['HTTP_HOST'] . '/uwdrupal/');
    exit();
}

/**
 * Set drush $base_url so e.g. user-login links work correctly.
 * Not sure this is needed for Drupal 9
 */
if (php_sapi_name() == "cli") {
    $base_url = 'http://uwdug.lndo.site/uwdrupal';
}

/**
 * Configure connection to Lando's database service.
 */
$LANDO_INFO = json_decode(getenv('LANDO_INFO'), TRUE);
$db_info = $LANDO_INFO['database'];

$databases['default']['default'] = [
    'driver' => 'mysql',
    'database' => $db_info['creds']['database'],
    'username' => $db_info['creds']['user'],
    'password' => $db_info['creds']['password'],
    'host' => $db_info['internal_connection']['host'],
    'port' => $db_info['internal_connection']['port'],
    'prefix' => '',
];
$databases['migrate']['default'] = [
    'driver' => 'mysql',
    'database' => 'migrate',
    'username' => $db_info['creds']['user'],
    'password' => $db_info['creds']['password'],
    'host' => $db_info['internal_connection']['host'],
    'port' => $db_info['internal_connection']['port'],
    'prefix' => '',
];

/**
 * Override values in settings.php.
 */

$settings['trusted_host_patterns'] = ['.*'];
$settings['hash_salt'] = md5(getenv('LANDO_HOST_IP'));
$settings['config_sync_directory'] = './config/sync';
$config['stage_file_proxy.settings']['origin'] = 'https://depts.washington.edu/uwdrupal';

// This will be useful once config_split is setup
//$config['config_split.config_split.config_dev']['status'] = TRUE;
