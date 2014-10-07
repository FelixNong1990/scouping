<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache







/**

 * The base configurations of the WordPress.

 *

 * This file has the following configurations: MySQL settings, Table Prefix,

 * Secret Keys, WordPress Language, and ABSPATH. You can find more information

 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing

 * wp-config.php} Codex page. You can get the MySQL settings from your web host.

 *

 * This file is used by the wp-config.php creation script during the

 * installation. You don't have to use the web site, you can just copy this file

 * to "wp-config.php" and fill in the values.

 *

 * @package WordPress

 */



// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', 'scouping' );



/** MySQL database username */

define( 'DB_USER', 'root' );



/** MySQL database password */

define( 'DB_PASSWORD', '' );



/** MySQL hostname */

define( 'DB_HOST', 'localhost' );



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

define('AUTH_KEY',         'KMa/lye1;y,Lo}.*mefN/4@DonsJljoDWztg^]TXmecG|%&kK3quvYR+Hji>6 zl');

define('SECURE_AUTH_KEY',  'QqkpAn)ZF<KIKZ}4Ys|z!IG- mB1uo^dr25<CKbR v_M<9v3m|-s3thjsE(d}yGa');

define('LOGGED_IN_KEY',    'UT-!3W!pB|+F[@H|ah(uhRWhm!EG[O6Oz@Z|6#V;n>HLZc+o-8Tk-X6&N|lm|1wk');

define('NONCE_KEY',        'eet=I?##)!TMB{QQJCpc o!$Y_|aP(v3A!?&QjsrCPsnYH1A.VTIk}0DuCu{uH&F');

define('AUTH_SALT',        '/~1z2%.L>U{SOP8+OV|>jJH$MrL+L(U+?a0;f|SEY|k1BHh(W!FK.<^M@g/ fM-%');

define('SECURE_AUTH_SALT', 'hUcduMLd 1bz?A8C-=ZE%MtW1eP-%-ffb`yd$@*>cra_1muD?0-u3+-KQeB-.8R-');

define('LOGGED_IN_SALT',   '?!R#:X/b++v`Lx{-RF(*+ka<(Tu%2tai/o<`V0KMeK|;az-V>_G_I3z]-nU#.vQE');

define('NONCE_SALT',       ' =}ko6}7X9WC` z;h(9$t[]$7#y|W BQ2n{-0,m+V`Q-fO.-}6:Yhlhjv3T~T%)h');



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each a unique

 * prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'rnp4ad_';



/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 */

define('WP_DEBUG', false);



/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

//Disable File Edits

define('DISALLOW_FILE_EDIT', true);

define('EMPTY_TRASH_DAYS', 1 );