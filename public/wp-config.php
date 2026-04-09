<?php
//Begin Really Simple Security key
define('RSSSL_KEY', 'tdIUBFwj2dk9QCA7eBDrr4qw8W0doudw5duH1FgR4Qy6EK5WPyNwViSwwUt199m3');
//END Really Simple Security key
// Define WP_DEBUG before wp-config-ddev.php loads, so its `defined()||define()`
// pattern does not override this value. Change to true when you need to debug.
define( 'WP_DEBUG', false );

// Load DDEV local settings (overrides DB credentials and site URL for local development).
// This must be included BEFORE the DB constants below are defined.
if ( file_exists( __DIR__ . '/wp-config-ddev.php' ) ) {
	require __DIR__ . '/wp-config-ddev.php';
}

define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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




 @ini_set( 'upload_max_filesize' , '128M' );
 @ini_set( 'post_max_size', '128M');
 @ini_set( 'memory_limit', '256M' );
 @ini_set( 'max_execution_time', '300' );
 @ini_set( 'max_input_time', '300' );

// ** MySQL settings - You can get this info from your web host ** //
// These are the production credentials. When running locally via DDEV,
// wp-config-ddev.php (included above) overrides these automatically.
/** The name of the database for WordPress */
defined( 'DB_NAME' )     || define( 'DB_NAME', 'chmbr_db' );

/** MySQL database username */
defined( 'DB_USER' )     || define( 'DB_USER', 'chmbr_usr' );

/** MySQL database password */
defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', 'MostynLaburn12' );

/** MySQL hostname */
defined( 'DB_HOST' )     || define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'MzMdJS#8^5&wSyoYOd)kEpzeV.gWp0onk]Z1GcX^}9wJJ4<cT;N:BBhL5{T9yqlY' );
define( 'SECURE_AUTH_KEY',  '&0[z`NOVi_?OO:`+Ow*<t6Wjbr7yr7pyhH&WQ7c>}98VfF#VA-9S-t&vY_Aw^(^1' );
define( 'LOGGED_IN_KEY',    'Fu6h{09ekk[n]C]<G`e]i`Jw_y+$04@WA2g%2CiNGgDRmO6 y7IrNuD:eW<Ts`.e' );
define( 'NONCE_KEY',        'P][|Pex%=!tBXXVL{A7;u5W!&4l9~xek2V{40oJ$nKZR;f[gD2z399gJv}WAYe:o' );
define( 'AUTH_SALT',        '4Fmh@p==2D;s1dQz;L8s_i|1n)}lc5eG_E9V5,;)7`BeG!Oe/KqcEEg2V)@2/;:>' );
define( 'SECURE_AUTH_SALT', '|Suk#&n>CCjLezt:9*$)lV/>KH9`.6)$&E7a-27Iz&H}5subBu]^w5[m_jpz<$~M' );
define( 'LOGGED_IN_SALT',   '~Fc$^NP=0 ~Tq=d;KCg+qg`m:m0CE;5}@`EV)q,cc}N_){j6`a{1_cBi0T_5{!gt' );
define( 'NONCE_SALT',       ';tW.$:FB|m3pePM&4}rTy2N!Q(rG+D;~<+C.Q?>-ByB=*>rsLBLh&i*V:uVfuJ3]' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
// WP_DEBUG is defined at the top of this file (before wp-config-ddev.php loads).

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
