<?php the_sidebar(); ?>
<div class="row-fluid">
<div class="span10 nomargin">
  <div class="row-fluid">
 <div id="videolist-content" class="oboxed span8"> 
<?php echo _ad('0','search-top');

if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}
if(!isset($st)){ $st = ''; }

if(isset($heading) && !empty($heading)) { echo '<h3 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h3>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($videos) {

echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($videos as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="icon-heart"></i></a>';
            $wlater = (is_user()) ? '<a class="laterit" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="icon-clock-o"></i></a>' : '';

echo '
<div id="video-'.$video->id.'" class="video">
<div class="video-inner">
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
	<p style="font-size:11px">'._html(_cut($video->description,170)).'</p>
<ul class="stats">	
<li>		'._lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.'</a></li>
 <li>'.$video->views.' '._lang('views').'</li>';
if(isset($video->date)) { echo '<li>'.time_ago($video->date).'</li>';}
echo '</ul>
</div>	
	</div>
		</div>
';
}
 echo '<nav id="page_nav"><a href="'.$canonical.'&ajax&p='.next_page().'"></a></nav>';
echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}

 echo _ad('0','search-bottom');
?>
</div>
<div id="SearchSidebar" class="span4 oboxed">
<h4 class="li-heading"><?php echo _lang('Playlists'); ?></h4>
<?php echo _ad('0','search-sidebar-top');
/* start playlists */	
$plays = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where title like '%".$key."%' or title like '".$key."%'  order by views desc limit 0,100");
if($plays) { 
$plnr = $db->num_rows;
?>
<div class="sidebar-nav blc"><ul>
<?php 
foreach ($plays as $play) {
echo '<li>
<a class="tipW pull-left" href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'">
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
echo '</ul>
</div>';
} else {
echo _lang('No results');	
}
/* start users */
?>
<h4 class="li-heading"><?php echo _lang('Users'); ?></h4>
<?php
$followings = $cachedb->get_results("SELECT id,avatar,name,lastNoty from ".DB_PREFIX."users where name like '%".$key."%' or name like '".$key."%'  order by lastlogin desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>
<div class="sidebar-nav blc"><ul>
<?php

foreach ($followings as $following) {
echo '
<li>
<a class="tipW pull-left" title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">
<img src="'.thumb_fix($following->avatar, true, 27, 27).'" alt="'.$following->name.'" />'._html(_cut($following->name, 25)).'';
if(date('d-m-Y', strtotime($following->lastNoty)) != date('d-m-Y')) {
echo '<i class="icon-circle-thin offline pull-right"></i>';
} else {
echo '<i class="icon-circle-thin online pull-right"></i>';
}
echo '
</a></li>';
}
echo '</ul>
</div>
';
}
 else {
echo _lang('No results');	
}

 echo _ad('0','search-sidebar-bottom'); ?>
</span>
</div>
</div>
</div>
