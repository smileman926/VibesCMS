<?php the_header(); the_sidebar(); 
the_sidebar(); ?>
<div class="row-fluid">
<div class="span10 nomargin">
  <div class="row-fluid">
 <div id="DashContent" class="span8"> 
<?php do_action('dash-top'); 
echo '<div class="row-fluid oboxed odet"><a class="text-center" href="'.profile_url(user_id(), user_name()).'"><h2><img class="img-circle" style="width:25px;height:25px;margin-right:10px;" src="'.thumb_fix(user_avatar(), true, 25,25).'" />'.user_name().'</h2></a>'; ?>
<ul class="user-stats">
<li><i class="icon-pencil"></i> <a href="<?php echo site_url(); ?>dashboard/&sk=edit" title="<?php echo _lang("Edit your details");?>"><?php echo _lang("Edit");?></a></li>
<?php echo '<li><i class="icon-eye-open"></i> <a href="'.profile_url(user_id(), user_name()).'">'._lang("View profile").'</a></li>';?>
<li><i class="icon-list"></i> <?php echo u_k($vd->nr);?> <?php echo _lang("Videos"); ?>  </li>
<li><i class="icon-bell"></i> <?php echo u_k($ad->nr);?> <?php echo _lang("Activities"); ?> </li>
<li> <i class="icon-youtube-play"></i> <?php echo u_k($vvd->nr);?> <?php echo _lang("Video views"); ?> </li>
</ul>
</div>
<?php if((_get('sk') == "edit") || isset($_POST['changeavatar']) ||isset($_POST['changeuser'])  ) {
include_once(TPL.'/profile/edit.php');		
} ?>	
<div class="row-fluid oboxed odet mtop20">
<h3 style="text-align:center"><?php echo _lang("Activity on your videos"); ?></h3>
<?php if(isset($note) && !nullval($note)) {
	echo '<ul id="notes">';
	foreach ($note as $n) {
		echo '<li><div class="aInner"><img src="'.$n['image'].'" style="width:15px; height:15px;"/>'.$n['text'].' <br style="clear:both"/></div></li>';
		
	}
	echo '</ul>';
}?>	
</div>
<?php 
$users = $db->get_results($vq);

if ($users) {
echo '<div class="row-fluid oboxed odet mtop20"><h3 style="text-align:center">'._lang("Your latest fans").'</h3>'; 
foreach ($users as $user) {
			$title = stripslashes(_cut($user->name, 46));
			$full_title = stripslashes(str_replace("\"", "",$user->name));			
			$url = profile_url($user->id , $user->name);
			
		
echo '
<div id="user-'.$user->id.'" class="user">
<div class="user-thumb">
		<a class="clip-link" data-id="'.$user->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($user->avatar, true, 101, 101).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>					
			
		</a>		
	</div>	
	<div class="user-title">
			<a href="'.$url.'" title="'.$full_title.'">'.$title.'</a>
			</div>';
			subscribe_box($user->id,"btn btn-small btn-danger block", false);
echo '			
	</div>
';
}
echo '<br style="clear:both;"/></div>';
}

 do_action('dash-bottom');
?>
</div>
<div id="DashSidebar" class="span4 oboxed">
<?php   do_action('dashSide-top');
echo '<h3 class="text-center">'._lang("From followers").'</h3>';
$options = DB_PREFIX."videos.id as vid,".DB_PREFIX."videos.title,".DB_PREFIX."videos.views,".DB_PREFIX."videos.user_id as owner, ".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
$uresult =$db->get_results("select ".$options." , ".DB_PREFIX."users.name as name FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.user_id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') and ".DB_PREFIX."videos.pub > 0 ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset(get_option('related-nr')));

 if ($uresult) {
	 echo '<div class="related video-related top10">
<ul>';
	foreach ($uresult as $uvids) {
$duration = ($uvids->duration > 0) ? video_time($uvids->duration) : '<i class="icon-picture"></i>';		
echo '
					<li data-id="'.$uvids->vid.'" class="item-post">
				<div class="inner">
					
	<div class="thumb">
		<a class="clip-link" data-id="'.$uvids->vid.'" title="'._html($uvids->title).'" href="'.video_url($uvids->vid , $uvids->title).'">
			<span class="clip">
				<img src="'.thumb_fix($uvids->thumb, true, 100, 56).'" alt="'._html($uvids->title).'" /><span class="vertical-align"></span>
			</span>
		<span class="timer">'.$duration.'</span>					
			<span class="overlay"></span>
		</a>
	</div>			
					<div class="data">
						<span class="title"><a href="'.video_url($uvids->vid , $uvids->title).'" rel="bookmark" title="'._html($uvids->title).'">'._cut(_html($uvids->title),124 ).'</a></span>
			
						<span class="usermeta">
							'._lang('by').' <a href="'.profile_url($uvids->owner, $uvids->name).'"> '._html($uvids->name).' </a>
							
						</span>
					</div>
				</div>
				</li>
		
	';
	}
	echo '</ul></div>';
}

do_action('dashSide-bottom'); ?>
</span>
</div>
</div>
</div>
