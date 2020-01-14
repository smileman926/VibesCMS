<?php  error_reporting(E_ALL);
//Vital file include
require_once("../load.php");
ob_start();
// physical path of admin
if( !defined( 'ADM' ) )
	define( 'ADM', ABSPATH.'/'.ADMINCP);
define( 'in_admin', 'true' );
require_once( ADM.'/adm-functions.php' );
require_once( ADM.'/adm-hooks.php' );

include_once( ADM.'/main7.php' );

ob_end_flush();
//That's all folks!
?>