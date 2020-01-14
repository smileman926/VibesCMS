<?php $v_id = intval(token());
//Global video weight & height
$width = get_option('video-width');  $height = get_option('video-height'); $embedCode = '';
if($v_id > 0) { 
//use cache
$cache_name = "embed-".$v_id;
$video = $cachedb->get_row("SELECT * FROM ".DB_PREFIX."videos WHERE id = '".$v_id."' limit 0,1");
unset($cache_name);
if($video) {
//Player support
//JwPlayer
add_filter( 'addplayers', 'jwplayersup' );  
//FlowPlayer
if((get_option('remote-player',1) == 2) || (get_option('choosen-player',1) == 2))	{					 
add_filter( 'addplayers', 'flowsup' );  
}
//jPlayer
add_filter( 'addplayers', 'jpsup' );  

//VideoJS
if((get_option('remote-player',1) == 6) || (get_option('choosen-player',1) == 6)|| (get_option('youtube-player',1) == 6))	{					 
add_filter( 'addplayers', 'vjsup' );  
}
//Check if it's processing
if(empty($video->source) && empty($video->embed) && empty($video->remote)) {
 $embedvideo = '<img src="'.site_url().'uploads/processing.png"/>';
} else {
//See what embed method to use
if($video->remote) {
	//Check if video is remote/link
   $vid = new Vibe_Providers($width, $height);    $embedvideo = $vid->remotevideo($video->remote);
   } elseif($video->embed) {
   //Check if has embed code
	$embedvideo	=  render_video(stripslashes($video->embed));
   } else {
   //Embed from external video url
   $vid = new Vibe_Providers($width, $height);    $embedvideo = $vid->getEmbedCode($video->source);
   }
 } 
$canonical = video_url($video->id , $video->title); 
 //Print iframe content
 echo '<!DOCTYPE html>  <html lang="en" dir="ltr"  data-cast-api-enabled="true">
<head>
<title>'._html($video->title).' - '.get_option('site-logo-text').'</title>
<link rel="canonical" href="'.video_url($video->id , $video->title).'">
<style>
.video-player iframe,.video-player embed{z-index:150!important; position:absolute;top:0;left:0;width:100% !important;height:100% !important;clear:both}
.hide {display:none!important}
.embeddedVP {padding:0.4%;}
.jwplayer.jw-state-idle,
.jwplayer .jw-controlbar, .vjs-control-bar{display: table; z-index:9999; }
</style>
<link rel="stylesheet" href="'.site_url().'lib/players/ads.jplayer.css"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
if((typeof jQuery == "undefined") || !window.jQuery )
{
   var script = document.createElement("script");
   script.type = "text/javascript";
   script.src = "'.tpl().'/styles/js/jquery.js";
   document.getElementsByTagName(\'head\')[0].appendChild(script);
}

</script>
<script type="text/javascript" src="'.tpl().'js/embedtrack.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".close-ad").click(function(){
 	$(this).closest(".adx").hide();	
    });		
 });		
</script>
'.players_js(); ?>
<script type="text/javascript">
var site_url = '<?php echo site_url(); ?>';
</script>
</head>
<body dir="ltr">
<div class="video-player embeddedVP">
<?php 
//Print the embed code
echo  $embedvideo; ?>
</div>
<script type="text/javascript">
$(document).ready(function(){
DOtrackview(<?php echo $video->id; ?>);
});
</script>
</body>
</html>
<?php } else {
//No video
echo _lang("Video was removed.");
}

}
?>