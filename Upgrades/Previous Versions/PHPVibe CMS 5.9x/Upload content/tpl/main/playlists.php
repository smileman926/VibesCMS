<?php the_sidebar(); ?>
<div class="row">
<div class="col-md-10 nomargin">
  <div class="row">
 <div id="videolist-content" class="oboxed col-md-8"> 
<?php echo _ad('0','playlists-top');
if(!isset($st)){ $st = ''; }
if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h1>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($playlists) {

echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($playlists as $pl) {
			$title = _html(_cut($pl->title, 170));
			$full_title = _html(str_replace("\"", "",$pl->title));			
			$url = playlist_url($pl->id , $pl->title);
			$plays = 0;
            $ov = '';			
			if($pl->ptype == 1) { $ov = _lang("Play all");} else { $ov = _lang("View all");}
			if($pl->ptype == 1) { $ol = site_url().'forward/'.$pl->id;} else { $ol = $url;}
			$ove = '<div class="playlists-overlay">
	     	<a title="'._lang($ov).'" href="'.$ol.'">
			'.$ov.'
			</a>			
			</div>
			';
			if(isset($entries[$pl->id])) {$plays = intval($entries[$pl->id]); }
echo '
<div id="video-'.$pl->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$pl->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($pl->picture, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" />
				'.$ove.'
				<div class="playlists-meta"><span>'.$plays.'</span><span><i class="icon icon-navicon"></i></span></div>
			</span>
          	
		</a>';
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
	<p style="font-size:11px">'._html(_cut($pl->description,270)).'</p>
<ul class="stats">';
echo '<li>'._lang("Watched").' '.intval($pl->views).' '._lang("times").'<li>
</ul>';
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
echo '<div id="SearchSidebar" class="col-md-4 oboxed">'.$ad.'</div>';
}
?>
</div>
