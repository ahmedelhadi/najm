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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'najm-db' );

/** MySQL database username */
define( 'DB_USER', 'shadi_root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ahmed123!@#' );

/** MySQL hostname */
define( 'DB_HOST', 'najm-db.database.windows.net' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'KF+(r(WBmUq79+l5/x(j3Vl#Sd8^97qTSE-xr(/b*p?SVzLf ]S-jXX&c/V *h(>' );
define( 'SECURE_AUTH_KEY',  '~0{8<Qj#T2mVr 8JIRfV6@2/S/<QUIQgl57?k<lAxos}vC,.wZB7?(p`)M;K7~1B' );
define( 'LOGGED_IN_KEY',    'o<Io.>TNSW){#Xn/B4v#i}O.D[(L:%SOmos<_5B]L4ku0&K/f7b5I(Eh6<a,d0c_' );
define( 'NONCE_KEY',        '36E `1//?3kg6X`2:bjgxBh1chwA$i2&xq7_j{8YYvm2sIfT5hsZfGa(z0;jQCC%' );
define( 'AUTH_SALT',        'rq%&4c++mo!<3>sgNnUdsjF=7gr/ulAAs+y8loq(QLG6m1cvqMV]ws*5=h@XEIk6' );
define( 'SECURE_AUTH_SALT', 'Bru@3_ylQT+/#mr{U_FzQi-17ZwP$ppv01N3VC!U5bmO-~qfR5Y`Ph`QaM?/=[{d' );
define( 'LOGGED_IN_SALT',   '~U=*?lC#?N/gX)|0ibEYZ-&jb!~r3b1D(8?CzGr.*s:IE%+A$4[riN|q]WOZS}8v' );
define( 'NONCE_SALT',       'VU=!jSp[_;n9MS=|RT+95dB!2n:OZd.dg|5/|u<5{KSiS=%}p_2g[@|/}zd^o+m9' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
