<?php
define('WP_CACHE', true); // By Speed Optimizer by SiteGround

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dbh4ddzjuqh2cy');

/** Database username */
define('DB_USER', 'uggg0fgus0mxy');

/** Database password */
define('DB_PASSWORD', '5yawffl6tesh');

/** Database hostname */
define('DB_HOST', '127.0.0.1');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',          '8o.k!iA+d7?B3{JW#&`4_iYRk-mBEM@4#W8j4#qQ?Nz47r{cV._/].}AG``/?3cQ');
define('SECURE_AUTH_KEY',   'c@c*{MYJN@i?{g9^7sluQsv(] |0mONubZB>o$!D>|3a+kV*rMgzQNGx$$^ P i>');
define('LOGGED_IN_KEY',     'H:.WBXs|3PtzjSP1>,{/G9$f/uSlB),4/fBK);LNF|~8kJ#/GP~+TsnYR<:.3{*|');
define('NONCE_KEY',         '8C$$$e4~Z4!Ff<tVL=EpE]oJfXP,GB:8<8!jaWpf:ph.L>ZcZ{dFu3Y}3ZrIWjr8');
define('AUTH_SALT',         'im*S#y?HauEoRhm]K:L!$6V_@/ByuQOk[.hc+XGieFv3}Lzs#0uI-7eZ#C3B{Kz3');
define('SECURE_AUTH_SALT',  '+A66^,^HSsJ~e!)1C0]rOa}MbP.{/:9u& A@1Cqf}E(#]Qri=8j1sW@MByN9kM!{');
define('LOGGED_IN_SALT',    'a7u8BI:7Ur<{OeJV!6S&nN,+{Z4cNlk2_cj!:^G{Oq_%u=Vh.yT/O7nQX?1pP-Fk');
define('NONCE_SALT',        'qci,<&:Qw y&`DSe+m*icC>`Ydr*bs,A&MA}JfDEL-cYsjw;!--:bDxGMt_>`j#J');
define('WP_CACHE_KEY_SALT', 'z wFYVj8`k42BY}1qB3NTWHo@`,nEt,(fD?eieS-/EJ|I%b>)Ur[![rk0pc0M&~f');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'hna_';

define('WP_ALLOW_REPAIR', true);

/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
