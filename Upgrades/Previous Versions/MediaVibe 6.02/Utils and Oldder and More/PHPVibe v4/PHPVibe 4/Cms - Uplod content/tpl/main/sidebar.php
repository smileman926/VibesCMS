<div id="sidebar" <?php if(com() == "video") {echo 'class="hide"';} ?> > 
<div class="sidescroll">
<?php do_action('sidebar-start');
//The menu	
echo '<div class="sidebar-nav blc"><ul>';
echo '<li class="lihead"><a href="'.list_url(browse).'"><i class="icon-youtube-play"></i> '._lang('Watch Videos').'</a></li>';
if(get_option('musicmenu') == 1 ) {
echo '<li class="lihead"><a href="'.music_url(browse).'"><i class="icon-music"></i> '._lang('Music').'</a></li>';	
}
if(get_option('imagesmenu') == 1 ) {
echo '<li class="lihead"><a href="'.images_url(browse).'"><i class="icon-picture"></i> '._lang('Images').'</a></li>';
}
echo '<li class="lihead"><a href="'.site_url().channels.'/"><i class="icon-indent"></i>'._lang('Browse Channels').'</a></li>';
if(get_option('showplaylists','1') == 1 ) {
echo '<li class="lihead"><a href="'.site_url().playlists.'/"><i class="icon-headphones"></i>'._lang('Browse Playlists').'</a></li>';
}
echo '<li class="lihead"><a href="'.site_url().blog.'/"><i class="icon-folder"></i>'._lang('Articles').'</a></li>';
if(get_option('showusers','1') == 1 ) {
echo '<li class="lihead"><a href="'.site_url().members.'/"><i class="icon-users"></i>'._lang('Members').'</a></li>';
}
echo '</ul></div>';
/* End of menu */
?>

<?php	
if (is_user()) {
	echo '<h4 class="li-heading">'._lang('Hi , ').' <a href="'.profile_url(user_id(), user_name()).'">'.user_name().'</a></h4>';
	echo '<div class="sidebar-nav blc"><ul>';
echo '
<li><a href="'.site_url().me.'/"><i class="icon-film"></i> '. _lang('My Media').'</a> </li>
<li><a href="'.site_url().me.'/&sk=likes"><i class="icon-thumbs-up"></i> '. _lang('Likes').'</a> </li>
<li><a href="'.site_url().me.'/&sk=history"><i class="icon-check-square"></i> '. _lang('History').'</a> </li>
<li><a href="'.site_url().me.'/&sk=later"><i class="icon-clock-o"></i> '. _lang('Watch Later').'</a> </li>
<li><a href="'.site_url().me.'/&sk=playlists"><i class="icon-edit"></i> '. _lang('My Playlists').'</a> </li>
<li><a href="'.profile_url(user_id(), user_name()).'"><i class="icon-eye-open"></i>'._lang("Profile").'</a></li>
<li><a href="'.site_url().buzz.'" title="'._lang('Notifications').'" class="nomargin"><i class="icon-bell"></i>'. _lang('Friend\'s Activity').'</a></li>
<li>&nbsp;</li>
<li><a href="'.site_url().'dashboard/&sk=edit"><i class="icon-cog"></i>'._lang("Settings").'</a></li>
<li><a href="'.site_url().'index.php?action=logout"><i class="icon-off"></i>'._lang("Logout").'</a></li>';
echo '</ul></div>';	
/* start my playlists */	
$plays = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') order by title asc limit 0,100");
if($plays) { 
$plnr = $db->num_rows;
?>
<h4 class="li-heading"><?php echo _lang('My Playlists'); ?></h4>

<div class="sidebar-nav blc"><ul>
<?php 
foreach ($plays as $play) {
echo '<li>
<a class="pull-left" href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'">
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
echo '</ul>
</div>';
}	
/* end my playlists */	
/* start my  subscriptions */ 
$followings = $cachedb->get_results("SELECT id,avatar,name,lastNoty from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') order by lastlogin desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>

<h4 class="li-heading"><?php echo _lang('My subscriptions'); ?> <a class="pull-right smallh" href="<?php echo profile_url(user_id(), user_name()); ?>&sk=subscribed" title="<?php echo _("View all"); ?>"><i class="icon-eye"></i></a></h4>

<div class="sidebar-nav blc"><ul>
<?php

foreach ($followings as $following) {
echo '
<li>
<a class="pull-left" title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">
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
/* end subscriptions */
do_action('user-sidebar-end');	
} else {
echo '<div class="blc mtop20">';	
echo _lang('Would you like to join our community and share videos, follow friends and collect media that interests you?');
echo '<p><a href="'.site_url().'login/" class="btn btn-primary btn-small" original-title="Login">		
					'._lang("Sign in").'				
					</a> </p>';	
echo '</div>';
do_action('guest-sidebar');					
}
echo lang_menu();
do_action('sidebar-end');
?>	
<div class="blc" style="height:300px">&nbsp;</div>
</div>
</div>