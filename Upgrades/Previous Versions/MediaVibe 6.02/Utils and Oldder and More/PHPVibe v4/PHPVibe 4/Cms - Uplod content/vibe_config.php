<?php //security check
if( !defined( 'in_phpvibe' ) || (in_phpvibe !== true) ) {
die();
}
/* This is your phpVibe config file.
 * Edit this file with your own settings following the comments next to each line
 */

/*
 ** MySQL settings - You can get this info from your web host
 */

/** MySQL database username */
define( 'DB_USER', 'database user' );

/** MySQL database password */
define( 'DB_PASS', 'password' );

/** The name of the database */
define( 'DB_NAME', 'database name' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** MySQL tables prefix */
define( 'DB_PREFIX', 'vibe_' );

/** MySQL cache timeout */
/** For how many hours should queries be cached? **/
define( 'DB_CACHE', '5' );

/*
 ** Site options
 */
 /** License key (Can be created in the store, under "My Licenses" **/
define( 'phpVibeKey', 'key goes here' );

/** Site url (with end slash, ex: http://www.domain.com/ ) **/
define( 'SITE_URL', 'http://www.mydomain.com/' );

/** Admin folder, rename it and change it here **/
define( 'ADMINCP', 'moderator' );

/*
 ** Custom settings would go after here.
 */
 ?>