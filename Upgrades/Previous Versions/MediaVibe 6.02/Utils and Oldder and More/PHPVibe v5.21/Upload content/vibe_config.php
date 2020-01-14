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
define( 'DB_USER', 'Database username' );

/** MySQL database password */
define( 'DB_PASS', 'Database password' );

/** The name of the database */
define( 'DB_NAME', 'Database name' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** MySQL tables prefix */
define( 'DB_PREFIX', 'vibe_' );

/** MySQL cache timeout */
/** For how many hours should queries be cached? **/
define( 'DB_CACHE', '12' );

/*
 ** Site options
 */
 /** License key (Can be created in the store, under "My Licenses" **/
define( 'phpVibeKey', 'License key' );

/** Site url (with end slash, ex: http://www.domain.com/ ) **/
define( 'SITE_URL', 'http://www.domain.com/' );

/** Admin folder, rename it and change it here **/
define( 'ADMINCP', 'moderator' );
/* Choose between mysqli (improved) (Note: beta wrapper in PHPVibe) and mysql */
 define( 'cacheEngine', 'mysqli' ); 
/** Timezone (set your own) **/
date_default_timezone_set('Europe/Bucharest');
 /*
 ** Custom settings would go after here.
 */
  $killcache = true; /* Enable/Disable full cache */
 ?>