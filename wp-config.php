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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_verticalgreen' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '10229909' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('DISABLE_WP_CRON', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'h333n96n5kfdfl2jsfgeufmfj6u2pwvoh3ygivtgbk9i0caaxm5zwstasryt62v1' );
define( 'SECURE_AUTH_KEY',  'tvaozzwt21vtm9jnvufln2udcc73wdzj6fqy816qzx9y3o4nl5m6nvcegwazxegi' );
define( 'LOGGED_IN_KEY',    'vrtjsize6vpb1k2cacyydqx1rfoygxytt8fzw88ijav5q9eoemm2bsnmhpkoa7oc' );
define( 'NONCE_KEY',        'uh7luvmsv1py9serhwh9lkkmpziy6ib6yqhnvpjzdvprjgwmid0ohgqm7hqzdscu' );
define( 'AUTH_SALT',        'p3jwuz8pej7gr4dxu9sh7enaejdbi7ykrqwn4nksfbt4czpvurq3pearp1ibmlsq' );
define( 'SECURE_AUTH_SALT', 'fr49n1enfux6zsvyjo87m25zmkoergwplfxgxmlul2uults6hxkeauowdyrf2eaw' );
define( 'LOGGED_IN_SALT',   'ddvjcfi3ik8bopciokefssrois627t9nxufozrtzbl6o73pzsurf4008rncdhkl0' );
define( 'NONCE_SALT',       'oz5irpzo9ica634kipdjwqp2niimhz5tb0autt0ht897lbepxon3ht0yk4pu7lne' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wper_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';