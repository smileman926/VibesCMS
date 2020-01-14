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
			$title = _html(_cut($image->title, 370));
			$full_title = _html(str_replace("\"", "",$image->title));			
			$url = image_url($image->id , $image->title);
			echo '
			<div class="image-item item">
      <div class="image-content">
        <header>
		<a href="'.profile_url($image->user_id, $image->owner).'" class="owner-avatar"><img class="owner-avatar" src="'.thumb_fix($image->avatar, true, 35, 35).'"/>
		<span class="owner-name">@'.$image->owner.'</span>
		</a>
		<div class="sharer">
		<a target="_blank" class="social-facebook" href="http://www.facebook.com/sharer.php?u='.$url.'&amp;t='.$title.'"><i class="icon icon-facebook"></i></a>
		<a target="_blank" class="social-pinterest" href="http://pinterest.com/pin/create/button/?url='.$url.'&media='.thumb_fix($image->thumb, true, 500, 'auto').'&description='.$full_title.'"><i class="icon icon-pinterest"></i></a>
		<a target="_blank" class="social-twitter" href="http://twitter.com/home?status='.$url.'"><i class="icon icon-twitter"></i></a>
        </div>
		</header>
		<div class="image-title"><a href="'.$url.'" title="'.$full_title.'">'.$title.'</a></div>
		<a class="clip-link" data-id="'.$image->id.'" title="'.$full_title.'" href="'.$url.'">
		<img src="'.thumb_fix($image->thumb, true, 320, 'auto').'"/>
        </a>		
      </div>
    </div>
';
}
echo _ad('0','after-video-loop');
/* Kill for home if several blocks */
if(!isset($kill_infinite) || !$kill_infinite) { echo '<nav id="page_nav"><a href="'.$canonical.'&ajax&p='.next_page().'"></a></nav>'; }
echo '</div></div>';
} else {
echo '<p class="empty-content">'._lang('Nothing here so far.').'</p>';
}
do_action('videoloop-end');
?>