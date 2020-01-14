<?php error_reporting(E_ALL); 
//Vital file include
require_once("load.php");
$tp = ABSPATH.'/'.get_option('tmp-folder','rawmedia')."/";
$fp = ABSPATH.'/'.get_option('mediafolder')."/";
$ip = ABSPATH.'/'.get_option('mediafolder').'/thumbs/';	;
//Run conversions
$crons = $db->get_results("select id,tmp_source,token from ".DB_PREFIX."videos where tmp_source != '' and source = '' limit 0,100000");
if($crons) {
foreach ($crons as $cron) {
$db->query("UPDATE  ".DB_PREFIX."videos SET tmp_source='' WHERE id = '".intval($cron->id)."'");
$fftheme ="{ffmpeg-cmd} -i {input} -vcodec libx264 -s {ffmpeg-vsize} -threads 4 -movflags faststart {output}.mp4";
$output = get_option('fftheme',$fftheme);
$input = $tp.$cron->tmp_source;
$final = $fp.$cron->token;
$check = $fp.$cron->token.'.mp4';
$source= 'localfile/'.$cron->token.'.mp4';
if (file_exists($input)) {  
//Start video conversion
$out = str_replace(array('{ffmpeg-cmd}','{input}','{ffmpeg-vsize}','{ffmpeg-bitrate}','{output}'),array(get_option('ffmpeg-cmd','ffmpeg'), $input, get_option('ffmpeg-vsize','640x360'), get_option('ffmpeg-bitrate','1750'),$final), $output);
shell_exec($out);

//Extract thumbnail
$imgout = "{ffmpeg-cmd} -i {input} -y -f image2  -ss ".get_option('ffmpeg-thumb-time','00:00:03')." -vframes 1 -s 500x300 {output}";
$imgfinal = $ip.$cron->token.'.jpg';
$thumb = str_replace(ABSPATH.'/' ,'',$ip.$cron->token.'.jpg');
$imgout = str_replace(array('{ffmpeg-cmd}','{input}','{output}'),array(get_option('ffmpeg-cmd','ffmpeg'), $input,$imgfinal), $imgout);
shell_exec ( $imgout);
// Update so far
$db->query("UPDATE  ".DB_PREFIX."videos SET thumb='".$thumb."', source='".$source."', pub = '".intval(get_option('videos-initial'))."'  WHERE id = '".intval($cron->id)."'");
//Extract Duration

$cmd = get_option('ffmpeg-cmd','ffmpeg')." -i ".$check;
exec ( "$cmd 2>&1", $output );
$text = implode ( "\r", $output );
if (preg_match ( '!Duration: ([0-9:.]*)[, ]!', $text, $matches )) {
			list ( $v_hours, $v_minutes, $v_seconds ) = explode ( ":", $matches [1] );
			// duration in time format
			$d = $v_hours * 3600 + $v_minutes * 60 + $v_seconds;			
		}
if(isset($d)) {		
list ( $duration, $trash ) = explode ( ".", $d );
}		
if(isset($duration)) {
$db->query("UPDATE  ".DB_PREFIX."videos SET duration='".$duration."' WHERE id = '".intval($cron->id)."'");
}
 add_activity('4', $cron->id); 
/* End this loops item */
}
}
$db->clean_cache();
} 

?>