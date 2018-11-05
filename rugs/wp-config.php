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
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', '$8dFw191943w');

/** MySQL hostname */
define('DB_HOST', 'rothwp.cs9tu0y4nwdu.us-west-2.rds.amazonaws.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ']PKEU%}D*_Wd]jz^A3OQ-gm+(WgxW8X^D:.V$;wj;wF^HuL%Gd++@szle+.<W ^s');
define('SECURE_AUTH_KEY',  'tf^2/hcVNSbr]y5(;F9R)Si[uXXIC;[*3psWxaqCD)7/lU1F|d*,eR$a;sSnMh%C');
define('LOGGED_IN_KEY',    'VOrT{so?|qGx-zm|:).c[p_2Q$UVESo+E,3trndUMl]jv++.vkG|/?b|Qi8S^aGS');
define('NONCE_KEY',        '>c2o^joGu7`KP~-@:i1s^quC*o_U{Ix@hgFcnG2va%>VO(i?lo#hhq=Y+[:f~sE}');
define('AUTH_SALT',        'c=I1uLcy3 `CoiB:B-Dxq6/ jTIyd4m(`p[,M2KQEgLYmJ+S++@=CZ%cO}]O^j0F');
define('SECURE_AUTH_SALT', '}[ N83j+2,H+$l3)>{j!V1.y.^Qe!ARLA8th1ZvtRq+?h~/o+E6p1n;T*Sg2@=8s');
define('LOGGED_IN_SALT',   '0y-tv7:*v-#i|N9&M`c+tqr^BcP(5iEwX8-5?U2_$,PbKC+EO<B7JZ{`I47zGyfN');
define('NONCE_SALT',       '+=0j;GG$!ArK|]ZEC#~9=X)AO4a0S|d/8[Kyb+k#Z<;BG0D?+|5<=4@vtz}_}X`N');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'rg_';

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
