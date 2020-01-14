<?php $phpenv = false;
if (function_exists('version_compare')){
if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
$phpenv = true;
/* echo "Version compare is fine"; */
} 
} else {
if(function_exists('array_replace')) {
$phpenv = true;
}
}
// Login, maybe?
if (is_admin()) {
admin_header();
if(get_option('softc') !== "UG93ZXJlZCBieSA8YSByZWw9Im5vZm9sbG93IiBocmVmPSJodHRwOi8vd3d3LnBocHZpYmUuY29tIiB0YXJnZXQ9Il9ibGFuayIgdGl0bGU9InBocFZpYmUgVmlkZW8gQ01TIj5waHBWaWJlJnRyYWRlOzwvYT4uIA==")
{
 update_option("softc", "UG93ZXJlZCBieSA8YSByZWw9Im5vZm9sbG93IiBocmVmPSJodHRwOi8vd3d3LnBocHZpYmUuY29tIiB0YXJnZXQ9Il9ibGFuayIgdGl0bGU9InBocFZpYmUgVmlkZW8gQ01TIj5waHBWaWJlJnRyYWRlOzwvYT4uIA==");
}
if(!function_exists('main_dom')) {
function main_dom($url)
{
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return false;
}
}
if(!function_exists('is_licensed_true')) {
function is_licensed_true() {
if( !defined( 'phpVibeKey')) {
return false;
}
$key_info = array();
$key_info['key'] = phpVibeKey;
$key_info['domain'] = main_dom(SITE_URL);
$serverurl = "http://labs.phpvibe.com/server/server.php";
$ch = curl_init ($serverurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $key_info);
$result = curl_exec ($ch);
$result = json_decode($result, true);
if($result['valid'] == "true"){ 
return true; } else {
return false;}
}
}
$last_ch = get_option('lecheck');
if((time() - $last_ch) > 166400) {
if(!is_licensed_true()) {
echo '<div id="wrap">
<div class="container" style="text-align:center; padding-top:40px;">
<section class="panel panel-danger" style="width:88%;">
<div class="panel-heading">License key check temporary or permanent fail</div>
<div style="padding:25px;">
License key check with PHPVibe server has failed. <br /> Please add the correct license key for the domain '.main_dom(SITE_URL).' by editing the file vibe_config.php 
<p><a target="_blank" href="http://get.phpvibe.com.com/licenses" style="display:block; padding:20px;"><strong>Create a new key</strong> >></a></p>
<p>&copy; 2009-2014 <a target="_blank" href="http://www.phpvibe.com">PHPVibe &trade;</a></p>
</div>
</section>
</div>
</div>';
die();
} else {
update_option('lecheck', time());
}
}
if(!function_exists('phpvibeshutdown34324asdsccxc')) {
echo ' <div id="phpvibe-content">
<div class="container-fluid">';
apply_filters("admin-pre-body",false);
if(_get('sk')) {
//security
$file = ADM.'/'.str_replace(array("/",":","http","www"),array("","","",""),_get("sk")).'.php';
if(is_readable( $file )) {
require_once($file); 
} elseif(has_action('adm-'._get('sk'))){
do_action('adm-'._get('sk'));	
}else {
echo 'No page <strong>'._get("sk").'.php</strong> found<br />';
echo 'No action <strong>adm-'._get("sk").'</strong> found<br />';
}
} else {
require_once( ADM.'/dashboard.php' );
}
echo '</div></div>';
echo '</div></div>';
apply_filters("admin-post-body",false);
echo '</body></html>';
}

//admin wide included functions 
//could go here
} else {
echo admin_css();
echo '
<div id="wrap">
<div class="container" style="text-align:center; padding-top:40px;">
<section class="panel panel-danger" style="width:88%;">
<div class="panel-heading">No permission!</div>
<div style="padding:25px;">
You are not the administrator of this website. <p><a href="'.site_url().'login&return='.ADMINCP.'" style="display:block; padding:20px;"><strong>Login with the administrator account</strong> >></a></p>
<p>&copy; 2009-2014 <a target="_blank" href="http://www.phpvibe.com">PHPVibe &trade;</a></p>
</div>
</section>
</div>
</div>
';
die();
}

echo '
<div class="container-fluid" style="text-align:center;">
<div class="row-fluid">
<div class="span2 nomargin" style="padding: 20px">
'.show_logo().'
</div>
<div class="span8 footer-content">
<p style="display:block; padding-top:10px;">Powered by <a rel="nofollow" href="http://www.phpvibe.com" target="_blank" title="phpVibe Video CMS">PHPVibe™</a>.</p>
<p style="display:block; font-size:10px;">The Video software "PHPVibe" is licensed to Media Vibe Solutions S.R.L. and protected by international laws. Please do not redistribute this code!</p>
</div>
</div>
</div>';


?>