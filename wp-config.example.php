<?php
/**
 * Local WordPress configuration template.
 *
 * Copy this file to wp-config.php and replace the placeholder values below.
 * Never commit wp-config.php.
 */

define( 'DB_NAME', 'nextcore_website' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/* Generate new values at https://api.wordpress.org/secret-key/1.1/salt/ */
define( 'AUTH_KEY',         'replace-with-a-unique-value' );
define( 'SECURE_AUTH_KEY',  'replace-with-a-unique-value' );
define( 'LOGGED_IN_KEY',    'replace-with-a-unique-value' );
define( 'NONCE_KEY',        'replace-with-a-unique-value' );
define( 'AUTH_SALT',        'replace-with-a-unique-value' );
define( 'SECURE_AUTH_SALT', 'replace-with-a-unique-value' );
define( 'LOGGED_IN_SALT',   'replace-with-a-unique-value' );
define( 'NONCE_SALT',       'replace-with-a-unique-value' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

/* Optional: add a new random value locally if Duplicator Pro requires it. */
define( 'DUPLICATOR_AUTH_KEY', 'replace-with-a-new-random-value' );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';

