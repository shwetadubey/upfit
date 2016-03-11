<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'FORCE_SSL_LOGIN', true ); // Force SSL for Dashboard - Security > Settings > Secure Socket Layers (SSL) > SSL for Dashboard
define( 'FORCE_SSL_ADMIN', true ); // Force SSL for Dashboard - Security > Settings > Secure Socket Layers (SSL) > SSL for Dashboard
// END iThemes Security - Do not modify or remove this line

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
set_time_limit(-1);
ini_set('max_execution_time', 0); //300 seconds = 5 minutes 
ini_set('memory_limit', '-1');
ini_set("display_errors", "1");
  error_reporting(E_ALL);
//ini_set("max_file_uploads","20480");
//define( 'SCRIPT_DEBUG', true );
define( 'DIEONDBERROR', true );
error_reporting(E_ALL);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'upfit');

define('WP_MEMORY_LIMIT', '256M');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'kUGvX6vaA1BJkCQ3');
define('DISALLOW_FILE_EDIT', false);
/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'G^Tf].O$R|&9QrT;VL,}$e0ue3xQfj0Dh~/~W,5B8-8O#|?qwwz>qX`@]A)b&h S');
define('SECURE_AUTH_KEY',  '|xa0GR[[+ZP|Z5f~[VE2}j]iz%&)J_(rTrhCov]8WU[N%33wt]a@Q)PYRf!J|=|+');
define('LOGGED_IN_KEY',    '2W@_~oFV?b04XWy+xi8;/cO_S|n1v-3YZdHv~Ywl:gPj8n?q8-)+CA+RKb?@5D~)');
define('NONCE_KEY',        'w UWgukf<O003z/Coc>FQ?55(jC<yp4~tZPLsLV$wdFXP]1IF:mH/!XBDEpZ9rK4');
define('AUTH_SALT',        'mA*Vwt/-G|yQ1kL??z52B3PR:yb?2`_{}h:Cp#=m@#$1N^P-:kV||[ms!R}/XJks');
define('SECURE_AUTH_SALT', '%qE/N~bBwBHv>:-L1T43`c{fk;#?#/c^8fL2P+E-fSAY-+6>LHA!$V|Zf|>,bF[w');
define('LOGGED_IN_SALT',   'EdZvYR[UZ|+i%S6Ll,kq,6b4wQ~~DtzkY-C#5R|<<|c}-K>+c!{!C*,9<SYq2Q_%');
define('NONCE_SALT',       'z$[0MDD+ZZ_E#:fn1~t.d,ikXX9UlCUQGlL9n.(L>|K,&4cL1|j5y^pwaC9[0{is');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'up_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'upfit.de');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');
/* That's all, stop editing! Happy blogging. */
/* Multisite */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_ADMIN_DIR', 'partnerlogin');
define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . WP_ADMIN_DIR);
