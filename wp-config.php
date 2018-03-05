<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_vac');

/** MySQL database username */
define('DB_USER', 'wpvacsuser');

/** MySQL database password */
define('DB_PASSWORD', 'vac@!123!@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

define('FORCE_SSL_ADMIN', false);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'S.=XXPd]tY0yJf$Si+@ff)W)5*`v.#Z^/`y0d~z-7fQ3_k&`3T?)ap)+&l$Be><,');
define('SECURE_AUTH_KEY',  '-+:,|3 _|U|NCSY>krSJ@~`Q|%q|(:T-?EF;>eX9AIfY;HM*=I(BF7/B??S~I1(^');
define('LOGGED_IN_KEY',    'a=Q{8xoo81g<sWZ}ofzHiEs#F(_:9%Yz]WO!6=LfNY&2NF%^:-nIPd=aLMUd=B :');
define('NONCE_KEY',        '${J1v-o&Cg}Hwn*Apu6+7g]25=Q^IJP-.7c:HbtA2wO)mC eToy=S-zZ|J{,c0p2');
define('AUTH_SALT',        'n0O5Z cQ@ n!$,m2hYSh[g>af?HxTr=?W&-AY--g&)l-AQMW_q->=sA]p&+KGat{');
define('SECURE_AUTH_SALT', '/BX6.uIUVemf!:FdG~uXShZ@Y//1JkefkZ{wqH:1]=+ <m |+`ynUEWa>+Sp%*+m');
define('LOGGED_IN_SALT',   '_,E &2>.zx=Th _q`dDgb: Bq,85%ll3m*o?skm!4&/pa.zRX**ywEnzUb1a>W+Y');
define('NONCE_SALT',       '|~e^C5lZH}_9b,|AzWh0O_060fPxr+tM@B,mogmn9,fPYhE;AA0<V-qP4J-H56i<');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
ini_set('log_errors', 'On'); 
ini_set('error_log', '/home/evaflor/www/evaflor_vac/wordpress/debug_error.log'); 
error_reporting(E_ALL);
