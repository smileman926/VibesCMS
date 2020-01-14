<?php /* Player embeds */
 function vjsplayer($file, $thumb, $logo = null, $type = '', $extra = '')
    {
    global $video;
    /* Render VideoJs Player */
    $ads   = _vjsads();
    $embed = '<video id="currentvideo" class="video-js vjs-default-skin" controls preload="auto" poster="' . $thumb . '" data-setup="">';
	
	  if(!empty($extra)) {
	/* Unset sd/hd */	  
	if(isset($extra['sd'])) {unset($extra['sd']);}	
	if(isset($extra['hd'])) {unset($extra['hd']);}
	foreach ($extra as $size=>$link) {
	$embed .= '<source src="' . $link . '" type=\'video/mp4\' label =\''._lang($size.'p').'\' res=\''.$size.'\'/>';	
	}
	  } else {
	  /* Singular source */	
      $embed .= '<source src="' . $file . '" type=\'video/mp4\'/>';	  
	  }
	  if ($video->srt)
       {
       $embed .= '<track kind="subtitles" src="' . site_url() . get_option('mediafolder') . '/' . $video->srt . '" srclang="en-US" label="English" default></track>';
       }
    $embed .= '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			</video>
			<script src="'.site_url().'lib/players/vjs/plugins/hd.js"></script>
			<script type="text/javascript">
			var myPlayer = videojs("#currentvideo");
            myPlayer.videoJsResolutionSwitcher()			
			myPlayer.ready(function(){			
			$(".bAd").detach().appendTo(".video-js");
			$(".plAd").detach().appendTo(".video-js");	
			function resizeVideoJS(){
			var containerWidth = $(\'.video-player\').width();
			var videoHeight = Math.round((containerWidth/16)*9);
			// Set width to fill parent element, Set height
			myPlayer.width(containerWidth).height( videoHeight );
			}
			resizeVideoJS();
			window.onresize = resizeVideoJS;
			';
    if (_get('list'))
       {
       $embed .= '
			myPlayer.on("ended", function(){ window.location = next_url;});';
       }
    $embed .= '});';
    $embed .= $ads['js'] . ' </script>' . $ads['html'];
    return $embed;
    }
 function _jpcustom($file, $thumb, $extra = array())
    {
    /* Render jPlayer */
    global $video;
    $ads = _jads();
    $ext = substr($file, strrpos($file, '.') + 1);
    /* Overwrite default music cover if null/default */
    if ((($ext == "mp3") && nullval($thumb)) || (strpos($thumb, 'xmp3.jpg') !== false))
       {
       $thumb = get_option('musicthumb', 'http://37.media.tumblr.com/c87921eefd315482e66706d51a05054e/tumblr_n71ifjJAwU1tchrkco1_500.gif');
       }
    $embed = "<script type=\"text/javascript\">
			$(document).ready(function() {
			var containerWidth = $('.video-player').width();
			var videoHeight = Math.round((containerWidth/16)*9);
			$('.mediaPlayer').mediaPlayer({
			media: {";
	if ($ext == "mp3")
       { /* Music */
       $embed .= "mp3: '" . $file . "',
	   poster: '" . $thumb."'";
       }
    else
       { /* Video */
	if(isset($extra['hd'])) {	   
	   $embed .= 'sd: {';	   
       $embed .= "m4v: '" . $file . "',
	   poster: '" . $thumb ."' }";	   
	   $embed .= ', hd: {';	   
       $embed .= "m4v: '" . $extra['hd'] . "',
	   poster: '" . $thumb ."' }";
	   } else {
	    $embed .= "m4v: '" . $file . "',
	   poster: '" . $thumb ."'";	   
	   }
       }
    $embed .= "
			},
			playerlogo : '" . thumb_fix(get_option('player-logo')) . "',
			playerlink : '" . canonical() . "',
			playerlogopos : '" . get_option('jp-logo', 'bright') . "',
			solution: 'html,flash',";
    if ($ext == "mp3")
       {
       $embed .= "supplied: 'mp3',";
       }
    $embed .= "swfPath: '" . site_url() . "lib/players/customJP/js/jplayer.swf',
			size: {
			width: '100%',
			height: videoHeight
			},
			autoplay:true,
			loadstart: function() { },
			nativeVideoControls: {  ipad: /ipad/,   iphone: /iphone/,   android: /android/,   blackberry: /blackberry/,   iemobile: /iemobile/ },
			playing: function() { $('div.screenAd').addClass('hide');  }
			});
			var cpJP  = \"#\" + $(this).find('.Player').attr('id');
			" . $ads['js'] . "
			</script>
			<div id=\"uniquePlayer-1\" class=\"mediaPlayer darkskin \">
			<div id=\"uniqueContainer-1\" class=\"Player\">	</div>" . $ads['html'] . "
			</div>
			";
    return $embed;
    }
 function _jwplayer($file, $thumb, $logo = null, $type = null, $extra = array())
    {
    /* Render jwPlayer 7 */
    global $video;
    $ads   = _jwads();
	$media = 'file: "' . $file . '"';
	if(!empty($extra)) {
	if(isset($extra['sd'])) {unset($extra['sd']);}	
	if(isset($extra['hd'])) {unset($extra['hd']);}	
	krsort($extra);
	$media = 'sources: [';	
    foreach ($extra as $size=>$link) {		
    $media .= '{ file: "'.$link.'", type: "mp4",
        label: "'._lang($size.'p').'"
      },';
	}
	$media = rtrim($media, ',');
	$media .= ']';
	}
    $embed = '<div id="video-setup" class="full">' . _lang("Loading the player...") . '</div>';
    $embed .= ' <script type="text/javascript">
			jwplayer("video-setup").setup({ '.$media.',    image: "' . $thumb . '",primary : "html5", stretching: "fill", "controlbar": "bottom",  width: "100%",aspectratio:"16:9", autostart:"true"';
    if ($type)
       {
       $embed .= ', type: "' . strtolower($type) . '" ';
       }
    if ($video->srt)
       {
       $embed .= ', tracks: [ { file: "' . site_url() . get_option('mediafolder') . '/' . $video->srt . '" } ] ';
       }
    if ($logo && !nullval($logo))
       {
       $embed .= ',	logo: { file: "' . $logo . '",  position: "bottom-right",  link: "' . site_url() . '"    }';
       }
       //Sharing plugin
       $embed .= ',	sharing: { link: "' . canonical() . '",  "sites": ["facebook", "twitter","linkedin","pinterest","tumblr","googleplus","reddit"]}';
   
    $embed .= '  });';
    if (_get('list'))
       {
       $embed .= '
              jwplayer().onComplete( function(){window.location = next_url;});
        ';
       }
    $embed .= $ads['js'] . ' </script>' . $ads['html'];
    return $embed;
    }
 function flowplayer($file, $thumb, $logo = null, $type = null, $extra=array())
    { global $video;
	if (!isIOS() && (get_option('hide-mp4', 0) > 0))
        {
			/* Get hidden by php urls */
			$link = site_url().'stream.php?sk='.$video->token.'&q=xxx';
			 } else {
			$link = site_url().get_option('mediafolder').'/'.$video->token.'-xxx.mp4'; 
	    }
    $ads   = _flowads();
    $embed = ' <link rel="stylesheet" type="text/css"href="' . site_url() . 'lib/players/fplayer/skin/functional.css">
	<script>	  var thelogo = "' . $logo . '"; var thelink = "' . site_url() . '";</script>';
	
    $embed .= '			
			<script src="' . site_url() . 'lib/players/fplayer/flowplayer.min.js"></script>
			<script src="' . site_url() . 'lib/players/fplayer/flowplayer.quality-selector.min.js"></script>';
			$embed .= '<div data-swf="' . site_url() . 'lib/players/fplayer/flowplayer.swf"  class="flowplayer color-alt no-background in-phpvibe" data-flashfit="true" data-logo="' . $logo . '" data-scaling="scale" data-embed="false" data-analytics="' . get_option("googletracking") . '">
			';
	if(!empty($extra)) {
	/* Unset sd/hd */
    if(isset($extra['sd'])) {unset($extra['sd']);}	
	if(isset($extra['hd'])) {unset($extra['hd']);}	
	$qu = array_keys($extra);
	arsort($qu);
	$qu = implode(",",$qu);
	$embed .= '<video poster="' . $thumb . '" data-defaultlink="'.$link.'" data-qualities="'.$qu.'">';	
	  } else {
	  /* Singular source */	
      $embed .= '<video poster="' . $thumb . '" >';
	  }
	$embed .= ' <source type="video/' . str_replace("ogv", "ogg", $type) . '" src="' . $file . '"/>';
			$embed .= '</video>  
			 <div class="fp-context-menu">
				 <ul>
				   <li><a href="' . site_url() . '">&copy; ' . _html(get_option('site-logo-text')) . '</a></li>
				   <li><a target="_blank" href="http://www.phpvibe.com">PHPVibe</a></li>
				 </ul>
			   </div>
			 </div>';
    $embed .= '<script>' . $ads['js'] . '</script>' . $ads['html'];
    return $embed;
    }
?>