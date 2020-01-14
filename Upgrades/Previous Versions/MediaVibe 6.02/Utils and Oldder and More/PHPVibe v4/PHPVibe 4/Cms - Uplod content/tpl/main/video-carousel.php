<?php if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}
if(!isset($st)){ $st = ''; }
if(!isset($blockclass)){ $blockclass = ''; }
if(!isset($blockextra)){ $blockextra = ''; }
if(isset($heading) && !empty($heading)) { echo '<h3 class="loop-heading loop-carousel"><span>'._html($heading).'</span>'.$st.'</h3>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($videos) {

echo $blockextra.'<div class="loop-content owl-carousel '.$blockclass.'">'; 
foreach ($videos as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="icon-heart"></i></a>';
            $wlater = (is_user()) ? '<a class="laterit" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="icon-clock-o"></i></a>' : '';
			echo '
<div id="video-'.$video->id.'" class="video">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$video->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	<span class="overlay"></span>
			</a>'.$liked.$watched.$wlater;
if($video->duration > 0) { echo '   <span class="timer">'.video_time($video->duration).'</span>'; }
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
<ul class="stats">	
<li>		'._lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.'</a></li>
 <li>'.number_format($video->views).' '._lang('views').'</li>';
if(isset($video->date)) { echo '<li>'.time_ago($video->date).'</li>';}
echo '</ul>
</div>	
	</div>
';
}
echo _ad('0','after-video-carousel');
echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}
?>