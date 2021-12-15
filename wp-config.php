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
define('DB_NAME', 'budgettry');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '%YY(3+;,yd%&}U8k/;RrR%w^+YT-?B`Ar~n2xI]at`3?e*c$c!T@dp+*~tkX7~y^');
define('SECURE_AUTH_KEY',  '<2z5hKg B/u jcZ>#5#_i~agIyi;JJgRZ>.BMMPG0j{y_-(n:-^$wxs-5.{D:~#p');
define('LOGGED_IN_KEY',    'bt%4x}vW}/]O{Ovlj3TU9{x,^T$r@s]$#U;0)^hOxQT+D-Nyaf~&xGq[/NPF#{j9');
define('NONCE_KEY',        'ZLw#/bF>#P92Q084xyz8K9t87{4r&XB;ZK!^Tn7T$4Rg,nzpvST},Q si?3Sp&8&');
define('AUTH_SALT',        'ivp^[[:RtCPPN`WC*2}oy>cD8Z&e?$1Tij,&q|qJdoLE4@>nN=3L=yr1rw{}Cx /');
define('SECURE_AUTH_SALT', 'at -pY7fL!E./c2Y cNyL7Gb;`yIQPFvf<CNj56/2jkR&7Qi/`scRXe5?=u%-,5!');
define('LOGGED_IN_SALT',   'p AueWA_M%Xr6Nft-5Nq#<5pEZIf(M-B;:)+u1AbKUd<^-Eb.yL!+)8#p-o;Mue4');
define('NONCE_SALT',       'F1E*L 62LDTlw`M,x(PE6*qa=GT>r[B@Tfgze2!AH]TPFnVPM^%I_zDe1#6&+W|/');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
