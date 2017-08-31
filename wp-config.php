<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost:8889');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'e791bc815abdfa7c001fc4208fb8b14eb237b1fb34d24610882f7e03e2d76bd4');
define('SECURE_AUTH_KEY',  '817d258854d7f5701445f3632fda6cfe09f96cdd5b38b10caa84b800796b15da');
define('LOGGED_IN_KEY',    '9247e8df817a3c53f19ad5fe25dd9d934a4753fa978b39d84110bc781b3b9fbf');
define('NONCE_KEY',        '4c699734fe1ea039fc7c284ddaffc459b3c6bb5a89d25c32f84ea7f581e1d76e');
define('AUTH_SALT',        'd213bfe1fead26c5ba0ba6070f60503d76d25ece1641b4b6515fe01370d6b12f');
define('SECURE_AUTH_SALT', '971058483f1ce775b179462dcdeaaaa7257451b26f64a50dc5f91fe2de87685b');
define('LOGGED_IN_SALT',   '4655d2022fafbbece9839f4e17f88fea7098d6cfb11cbdd1239f057ee85e6a86');
define('NONCE_SALT',       'a67bd432488836ac60896ce828ee980c79be2bb3a1b5dbd8a6c03085a87e683a');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
*/

//define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wordpress');
//define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/wordpress');

// define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
// define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// define('WP_TEMP_DIR', 'C:/xampp/apps/wordpress/tmp');

