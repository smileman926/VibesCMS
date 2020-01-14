<?php  error_reporting(E_ERROR);
//Vital file include
require_once("../load.php");
ob_start();
// physical path of admin
if( !defined( 'ADM' ) )
	define( 'ADM', ABSPATH.'/'.ADMINCP);
define( 'in_admin', 'true' );
require_once( ADM.'/adm-functions.php' );
require_once( ADM.'/adm-hooks.php' );
$php_version = phpversion() . " (" . php_sapi_name() . ")";
$php_flavour = substr($php_version,0,3);
if($php_flavour < 5.3) {
echo "<h2>Error: Your php version is $php_flavour </h2><h3>Switch/upgrade your php to at least php 5.3</h3>";
}
if($php_flavour == 5.3) {
include_once( ADM.'/main53.php' );
}
if($php_flavour == 5.4) {
include_once( ADM.'/main54.php' );
}
if($php_flavour > 5.4) {
include_once( ADM.'/main55.php' );
}
ob_end_flush();
//That's all folks!
?>