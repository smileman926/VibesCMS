<?php the_sidebar(); ?>
<div class="row">
<div class="col-md-10 nomargin">
  <div class="row">
 <div id="videolist-content" class="oboxed col-md-8"> 
<?php echo _ad('0','users-top');

if(!isset($st)){ $st = ''; }

if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._html($heading).'</span>'.$st.'</h1>';}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if ($users) {

echo '<div id="SearchResults" class="loop-content phpvibe-video-list ">'; 
foreach ($users as $user) {
			$title = _html(_cut($user->name, 70));
			$full_title = _html(str_replace("\"", "",$user->name));			
			$url = profile_url($user->id , $user->name);
			
echo '
<div id="video-'.$user->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$user->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($user->avatar, true, get_option('thumb-width'), get_option('thumb-height')).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>
          	
		</a>';
echo '</div>	
<div class="video-data">
	<h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'">'._html($title).'</a></h4>
	<p style="font-size:11px">'._html(_cut($user->bio,170)).'</p>
<ul class="stats">	';
if($user->country || $user->local) {
if(empty($user->local)) {$user->local = _lang('Unknown');}	
echo '<li>		'._lang("from").' '.$user->local.', '.$user->country.'</a></li>';
}
echo '<li>';
if($user->lastNoty) {
if(date('d-m-Y', strtotime($user->lastNoty)) != date('d-m-Y')) {
echo '<i class="icon-circle-thin offline" style="margin-right:9px;"></i>';
} else {
echo '<i class="icon-circle-thin online" style="margin-right:9px;"></i>';
}}
echo time_ago($user->lastlogin).'<li>
</ul>
</div>	
	</div>
		</div>
';
}
$a->show_pages($ps);
echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}

 echo _ad('0','users-bottom');
?>
</div>
<?php $ad = _ad('0','members-sidebar');
if(!empty($ad)) {
echo '<div id="SearchSidebar" class="col-md-4 oboxed">'.$ad.'</div>';
}
?>
</div>
