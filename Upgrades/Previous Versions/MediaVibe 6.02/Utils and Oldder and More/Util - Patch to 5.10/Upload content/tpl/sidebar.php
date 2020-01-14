<div id="sidebar" class="<?php if(com() !== "home") {echo 'hide';} ?> animated zoomInLeft"> 
<div class="sidescroll">
<?php do_action('sidebar-start');
//The menu	
echo '<div class="sidebar-nav blc"><ul>';
echo '<li class="lihead"><a href="'.site_url().'"><span class="iconed"><i class="icon-home"></i></span> '._lang('Home').'</a></li>';
if (is_user()) {
echo '<li class="lihead"><a href="'.profile_url(user_id(), user_name()).'"><span class="iconed"><i class="icon-street-view"></i></span> '._lang('My Channel').'</a></h4>
';
}
echo '<li class="lihead"><a href="'.list_url(browse).'"><span class="iconed"><i class="icon-play-circle"></i></span> '._lang('Videos').'</a></li>';
if(get_option('musicmenu') == 1 ) {
echo '<li class="lihead"><a href="'.music_url(browse).'"><span class="iconed"><i class="icon-soundcloud"></i></span> '._lang('Music').'</a></li>';	
}
if(get_option('imagesmenu') == 1 ) {
echo '<li class="lihead"><a href="'.images_url(browse).'"><span class="iconed"><i class="icon-eye"></i></span> '._lang('Pictures').'</a></li>';
}
if(get_option('showplaylists','1') == 1 ) {
echo '<li class="lihead"><a href="'.site_url().playlists.'/"><span class="iconed"><i class="icon-mixcloud"></i></span>'._lang('Collections').'</a></li>';
}
if(get_option('showusers','1') == 1 ) {
echo '<li class="lihead"><a href="'.site_url().members.'/"><span class="iconed"><i class="icon-forward"></i></span>'._lang('Browse Channels').'</a></li>';
}
echo '<li class="lihead"><a href="'.site_url().blog.'/"><span class="iconed"><i class="icon-newspaper-o"></i></span>'._lang('Blog').'</a></li>';
echo '</ul></div>';
/* End of menu */
?>
<?php	
if (is_user()) {
/* start my playlists */	
?>
<h4 class="li-heading"><?php echo _lang('My collections'); ?></h4>
<div class="sidebar-nav blc"><ul>
<?php 
echo '<li><a href="'.site_url().me.'/&sk=likes"><span class="iconed"><i class="icon-thumbs-up"></i></span> '. _lang('Likes').'</a> </li>
<li><a href="'.site_url().me.'/&sk=history"><span class="iconed"><i class="icon-hourglass-1"></i></span> '. _lang('History').'</a> </li>
<li><a href="'.site_url().me.'/&sk=later"><span class="iconed"><i class="icon-history"></i></span> '. _lang('Watch Later').'</a> </li>
';
$plays = $cachedb->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype < 2 order by title asc limit 0,100");
if($plays) { 
$plnr = $db->num_rows;
foreach ($plays as $play) {
echo '<li>
<a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'">
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
}
echo '</ul>
</div>';
/* end my playlists */
/* start my albums */
$albums = $cachedb->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype > 1 order by title asc limit 0,100");
if($albums) { 
echo '<h4 class="li-heading">'._lang('My albums').'</h4>
<div class="sidebar-nav blc"><ul>';
$plnr = $db->num_rows;
foreach ($albums as $play) {
echo '<li>
<a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'">
'._html(_cut($play->title, 24)).'
</a>
</li>';
}
echo '</ul>
</div>';
}
/* end my albums */	
/* start my  subscriptions */ 
$followings = $cachedb->get_results("SELECT id,avatar,name,lastNoty from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') order by lastlogin desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>
<h4 class="li-heading">
<a style="color: #797E89;" href="<?php echo profile_url(user_id(), user_name()); ?>&sk=subscribed" title="<?php echo _("View all"); ?>"><?php echo _lang('My subscriptions'); ?> </a>
</h4>
<div class="sidebar-nav blc"><ul>
<?php
foreach ($followings as $following) {
echo '
<li>
<a title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">
<img src="'.thumb_fix($following->avatar, true, 27, 27).'" alt="'.$following->name.'" />'._html(_cut($following->name, 25)).'';
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
echo '<div class="blc mtop20 odet">';	
echo _lang('Join and share videos, music and pictures, follow friends and collect media!');
echo '<p class="small"><a href="javascript:showLogin()" class="btn btn-primary btn-small btn-block mtop20">		
		<i class="icon icon-sign-in"></i>			'._lang("Sign in").'				
					</a> </p>';	
echo '</div>';
do_action('guest-sidebar');					
}
do_action('sidebar-end');
?>	
<div class="blc" style="height:300px">&nbsp;</div>
</div>
</div>