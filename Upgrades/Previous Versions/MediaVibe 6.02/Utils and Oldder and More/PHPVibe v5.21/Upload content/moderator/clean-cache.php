<?php
$cInc = ABSPATH;
if( !defined( 'vSTATIC_FOLD' ) )
define('vSTATIC_FOLD',	'/cache/html/');
require_once( INC.'/fullcache.php' );	
$debug1 =FullCache::ClearAll();
foreach ($debug1 as $d1) {
echo str_replace($cInc,'', $d1);
}
$debug = $db->clean_cache();
foreach ($debug as $d) {
echo str_replace($cInc,'', $d);
}
$debug2 = $db->clean_cache(true);
foreach ($debug2 as $d2) {
echo str_replace($cInc,'', $d2);;
}
echo '<div class="msg-win">Cache cleared</div>';

?>