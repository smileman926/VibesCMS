<?php the_sidebar(); 
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i> <i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';

?>
<div class="row">
<div class="col-md-10">
  <div class="row">
 <div id="videolist-content" class="oboxed col-md-8"> 
<?php echo _ad('0','search-top');

if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}
if(!isset($st)){ $st = ''; }

if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h1>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($videos) {

echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($videos as $video) {
			$title = _html(_cut($video->title, 70));
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit  pv_tip" data-toggle="tooltip" data-placement="left" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="icon-heart"></i></a>';
            $wlater = (is_user()) ? '<a class="laterit pv_tip" data-toggle="tooltip" data-placement="right" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="icon-plus-square-o"></i></a>' : '';
			$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($video->description));
            $description = _cut(trim($description),240);
			if(empty($description)) {$description = $full_title;} 
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
	<p class="small">'.$description.'</p>
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
 echo '<nav id="page_nav"><a href="'.$canonical.'?ajax&p='.next_page().'"></a></nav>';
echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}

 echo _ad('0','search-bottom');
?>
</div>
<?php if(!is_ajax_call() && not_empty($key)) { ?>
<div id="SearchSidebar" class="col-md-3 col-md-offset-1">
<h4 class="li-heading"><?php echo _lang('Playlists'); ?></h4>
<?php echo _ad('0','search-sidebar-top');
/* start playlists */	
$plays = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where (title like '%".$key."%' or title like '".$key."%') and ptype < 2 and picture not in ('[likes]','[history]','[later]') order by views desc limit 0,100");
if($plays) { 
$plnr = $db->num_rows;
?>
<div class="sidebar-nav block top10 bot10"><ul>
<?php 
foreach ($plays as $play) {
echo '<li class="row mtop10">
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
/* start channels */
?>
<h4 class="li-heading"><?php echo _lang('Channels'); ?></h4>
<?php
$followings = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where name like '%".$key."%' or name like '".$key."%'  order by lastlogin desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>
<div class="sidebar-nav block top10 bot10"><ul>
<?php

foreach ($followings as $following) {
echo '
<li class="row mtop10">
<a class="tipW pull-left" title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">
<img src="'.thumb_fix($following->avatar, true, 27, 27).'" alt="'.$following->name.'" />'._html(_cut($following->name, 25)).'';
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
<?php } ?>
</div>
</div>
