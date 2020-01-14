<?php the_sidebar(); ?>
<div class="row-fluid">
<div class="span10 nomargin">
  <div class="row-fluid">
 <div id="videolist-content" class="oboxed span8"> 
<?php echo _ad('0','playlists-top');

if(!isset($st)){ $st = ''; }

if(isset($heading) && !empty($heading)) { echo '<h3 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h3>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($playlists) {

echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($playlists as $pl) {
			$title = _html(_cut($pl->title, 70));
			$full_title = _html(str_replace("\"", "",$pl->title));			
			$url = playlist_url($pl->id , $pl->title);
			$plays = 0;
			if(isset($entries[$pl->id])) {$plays = intval($entries[$pl->id]); }
echo '
<div id="video-'.$pl->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$pl->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($pl->picture, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	
		</a>';
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).' ('.$plays.' '._lang("videos").')</a></h4>
	<p style="font-size:11px">'._html(_cut($pl->description,170)).'</p>
<ul class="stats">';
echo '<li>'._lang("Watched").' '.intval($pl->views).' '._lang("times").'<li>
</ul>';
if($plays > 0 ){
echo '<a class="btn btn-default btn-small tipN" title="'._lang("Play all videos from").$title.'" href="'.site_url().'forward/'.$pl->id.'/"><i class="icon-forward" style="margin-right:3px;"></i>'._lang("Play").'</a>';
}
echo '</div>	
	</div>
		</div>
';
}
$a->show_pages($ps);
echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}

 echo _ad('0','playlists-bottom');
?>
</div>
<?php $ad = _ad('0','playlists-sidebar');
if(!empty($ad)) {
echo '<div id="SearchSidebar" class="span4 oboxed">'.$ad.'</div>';
}
?>
</div>
