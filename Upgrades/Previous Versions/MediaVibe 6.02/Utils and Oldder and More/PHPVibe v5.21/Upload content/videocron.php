<?php error_reporting(E_ALL); 
//Vital file include
require_once("load.php");
$tp = ABSPATH.'/'.get_option('tmp-folder','rawmedia')."/";
$fp = ABSPATH.'/'.get_option('mediafolder')."/";
$ip = ABSPATH.'/'.get_option('mediafolder').'/thumbs/';	;
/* Get sizes */
$sizes = get_option('ffmeg-qualities','360');
$to = @explode(",", $sizes);

//Run conversions
$crons = $db->get_results("select id,tmp_source,token from ".DB_PREFIX."videos where tmp_source != '' and source = '' limit 0,100000");
if($crons) {
foreach ($crons as $cron) {
$input = $tp.$cron->tmp_source;
$final = $fp.$cron->token;
$check = $fp.$cron->token.'.mp4';
$source= 'up';

if (file_exists($input)) { 
$va = _get_va($input, get_option('ffmpeg-cmd','ffmpeg'));
$size = 	$va['height']; 
$duration = $va['hours'] *  3600 + 	$va['mins'] * 60 + $va['secs'];
if(is_empty($duration)) {$duration = 0;}
//If mp4 copy it
$ext = substr($input, strrpos($input, '.') + 1);
if($ext == "mp4") {
$double = $fp.$cron->token.'-'.$size.'hd.mp4';
copy($input, $double);	
}	
//Do not double
$db->query("UPDATE  ".DB_PREFIX."videos SET tmp_source='',duration='".intval($duration)."'  WHERE id = '".intval($cron->id)."'");
//Start video conversion

$command ='';
/* Loop qualities */
foreach ($to as $call) {
if($call <= ($size + 100)) {	
if(not_empty($call)) {	
$conv = get_option('fftheme-'.$call,'');
if(not_empty($conv)) {	
$out = str_replace(array('{ffmpeg-cmd}','{input}','{output}'),array(get_option('ffmpeg-cmd','ffmpeg'), $input,$final), $conv);
$command .=$out.';';
}
}
}
}
/* Silently exec chained ffmpeg commands*/
if(not_empty($command)) {	
$thisoutput = shell_exec("$command > /dev/null 2>/dev/null &");
vibe_log($thisoutput);
}
//Extract thumbnail
$imgout = "{ffmpeg-cmd} -i {input} -y -f image2  -ss ".get_option('ffmpeg-thumb-time','00:00:03')." -vframes 1 -s 500x300 {output}";
$imgfinal = $ip.$cron->token.'.jpg';
$thumb = str_replace(ABSPATH.'/' ,'',$ip.$cron->token.'.jpg');
$imgout = str_replace(array('{ffmpeg-cmd}','{input}','{output}'),array(get_option('ffmpeg-cmd','ffmpeg'), $input,$imgfinal), $imgout);
shell_exec ( $imgout);
// Update so far
$db->query("UPDATE  ".DB_PREFIX."videos SET thumb='".$thumb."', source='".$source."', pub = '".intval(get_option('videos-initial'))."'  WHERE id = '".intval($cron->id)."'");
add_activity('4', $cron->id); 
/* End this loops item */
/* Clean 0 size files */
$folder = $fp;
/* Get list of video files attached */
$pattern = "{*".$cron->token."*}";
$vl = glob($folder.$pattern, GLOB_BRACE);
foreach($vl as $videocheck) {
if(filesize($videocheck) < 1000000){
remove_file($videocheck);
vibe_log("Removed $videocheck for 0 filesize \n");
}	
}
}
/* End foreach cron */
}
/* Get list of video files attached */
$pattern = "{*}";
$vl = glob($folder.$pattern, GLOB_BRACE);
foreach($vl as $videocheck) {
if(filesize($videocheck) < 100){
remove_file($videocheck);
vibe_log("Removed $videocheck for 0 filesize \n");
}	
}
$db->clean_cache();
} 

?>