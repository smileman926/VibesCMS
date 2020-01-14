<?php do_action('videoloop-start');
if(!nullval($vq)) { $images = $db->get_results($vq); } else {$images = false;}
if(!isset($st)){ $st = ''; }
if(!isset($blockclass)){ $blockclass = ''; }
if(!isset($blockextra)){ $blockextra = ''; }
if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h1>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if(isset($heading_plus) && !empty($heading_plus)) { echo '<small class="videod">'.$heading_plus.'</small>';}
if ($images) {

echo $blockextra.'<div class="row text-center"><div class="col-md-12 col-xs-12 gfluid '.$blockclass.'">'; 
foreach ($images as $image) {
	if(isset($image->nsfw) && ($image->nsfw > 0) ) { $image->thumb = tpl().'images/nsfw.jpg';}
			$title = _html(_cut($image->title, 370));
			$full_title = _html(str_replace("\"", "",$image->title));			
			$url = image_url($image->id , $image->title);
			echo '
		<div class="image-item item">
        <div class="image-content">
		<a class="clip-link" data-id="'.$image->id.'" title="'.$full_title.'" href="'.$url.'">
		<img src="'.thumb_fix($image->thumb, true, 500, 'auto').'"/>
        </a>		
        </div>
	    <div class="image-footer text-left">
		<div class="image-title text-left">
		<a href="'.$url.'" title="'.$full_title.'">'.$title.'</a>
		</div>
		<div class="cute-line"></div>
		<a href="'.profile_url($image->user_id, $image->owner).'" class="text-left owner-avatar"><img class="owner-avatar" src="'.thumb_fix($image->avatar, true, 25, 25).'"/>
		<span class="owner-name">@'.$image->owner.'</span>
		</a>
		</div>
    </div>
';
}
echo _ad('0','after-video-loop');
/* Kill for home if several blocks */
if(!isset($kill_infinite) || !$kill_infinite) { 
if(!_contains($canonical,"?")) {
echo '
<nav id="page_nav"><a href="'.$canonical.'?ajax=1&p='.next_page().'"></a></nav>
'; 
} else {
echo '
<nav id="page_nav"><a href="'.$canonical.'&ajax=1&p='.next_page().'"></a></nav>
'; 	
}
}

echo '</div></div>';
} else {
echo '<p class="empty-content">'._lang('Nothing here so far.').'</p>';
}
do_action('videoloop-end');
?>