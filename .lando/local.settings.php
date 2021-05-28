<?php

/**
 * Configure connection to Lando's database service.
 */
$LANDO_INFO = json_decode(getenv('LANDO_INFO'), TRUE);
$db_info = $LANDO_INFO['database'];

$databases = array();
$databases['default']['default'] = array(
 'driver' => $db_info['type'],
 'database' => $db_info['creds']['database'],
 'username' => $db_info['creds']['user'],
 'password' => $db_info['creds']['password'],
 'host' => $db_info['internal_connection']['host'],
 'port' => $db_info['internal_connection']['port'],
 'prefix' => '',
);

// Set drush $base_url so e.g. user-login links work correctly.
if (php_sapi_name() == "cli") {
  $base_url = 'http://uwdug.lndo.site/uwdrupal';
}

/**
 * Below are default values from example.settings.php.
 */
$update_free_access = FALSE;

$drupal_hash_salt = '';

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

ini_set('session.gc_maxlifetime', 200000);

ini_set('session.cookie_lifetime', 2000000);

$conf['404_fast_paths_exclude'] = '/\/(?:styles)|(?:system\/files)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

$conf['file_scan_ignore_directories'] = array(
  'node_modules',
  'bower_components',
);
