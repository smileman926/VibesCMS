<?php $options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.media,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw"; ?>

<div id="uvholder" class="oboxed">
<div class="box-body">
<?php
$tq= "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views > 0 and pub > 0 and ".DB_PREFIX."videos.user_id ='".$profile->id."' AND MONTH(date) = MONTH(CURDATE( )) ORDER BY ".DB_PREFIX."videos.views DESC ".this_offset(5);
$trending = $db->get_results($tq);
?>
<h3 class="loop-heading"><span><?php echo _lang('Trending'); ?></span></h3>
<div class="clearfix"></div>
<?php
if($trending) {
echo '<div class="block video-related profile-theone"><ul>'; 
$first = true;  
foreach ($trending as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
echo '
<li id="video-'.$video->id.'" class="item-post">
<div class="inner">
<div class="thumb">
		<a class="clip-link" data-id="'.$video->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($video->thumb, true, 410,300).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	<span class="overlay"></span>
		</a>
	
		';
if($video->duration > 0) { echo '   <span class="timer">'.video_time($video->duration).'</span>'; }
echo '</div>	
<div class="data">
	<span class="title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></span>						
	<span class="usermeta">
		'._lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.'</a> <span class="pull-right">'.$video->views.' '._lang('views').'</span>
	</span>
</div>	
</div>	
	</li>
';
if($first) {
echo '</ul></div><div class="block video-related profile-trending"><ul>'; 
}
$first = false;
}
echo '</ul><div class="clearfix"></div></div>';
}

?>
<div class="clearfix"></div>
<h3 class="loop-heading"><span><?php echo _lang('Uploads'); ?></span></h3>
<?php
$vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views > 0 and pub > 0 and ".DB_PREFIX."videos.user_id ='".$profile->id."' ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset(bpp());
include(TPL.'/video-loop.php');
?>
<div class="clearfix"></div>
</div>
</div>