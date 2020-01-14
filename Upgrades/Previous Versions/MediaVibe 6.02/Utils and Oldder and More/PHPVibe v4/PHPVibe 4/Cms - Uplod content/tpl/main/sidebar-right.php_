<div class="span2 right-side hidden-phone hidden-tablet top10">
<div class="full" style="position:relative">
<div class="close-me visible-phone visible-tablet hidden-desktop">
<a id="mobi-hide-right-sidebar" class="topicon tipN" href="javascript:void(0)" title="<?php echo _lang('Hide'); ?>"><i class="icon-plus"></i></a>
</div>
<?php if(get_option('fb-fanpage') !== '') {?>
<ul class="statistics">
<li>
<div class="top-info">
<a href="http://facebook.com<?php echo get_option('fb-fanpage'); ?>" title="" class="dark-blue-square"><i class="icon-facebook-sign"></i></a>
<strong><?php echo _fb_count(get_option('fb-fanpage')); ?></strong>
</div>
<div class="progress progress-micro"><div class="bar" style="width: 40%;"></div></div>
<span><?php echo _lang('Facebook fans');?></span>
</li>
</ul>
<?php } ?>	
   <div class="box"> 
	<div class="box-body list">	
	<ul>
<?php echo'	<li><i class="icon-exchange"></i><a href="'.list_url(browse).'" title="'._lang('New Media').'"> '._lang('Recent').'</a></li>
	<li><i class="icon-list-ol"></i><a href="'.list_url(mostviewed).'" title="'._lang('Most Viewed').'"> '._lang('Most Viewed').'</a></li>
	<li><i class="icon-check"></i><a href="'.list_url(promoted).'" title="'._lang('Featured').'"> '._lang('Featured').'</a></li>
	<li><i class="icon-heart"></i><a href="'.list_url(mostliked).'" title="'._lang('Most Liked').'"> '._lang('Most Liked').'</a></li>	
	<li><i class="icon-comment"></i><a href="'.list_url(mostcom).'" title="'._lang('Most Commented').'"> '._lang('Most Commented').'</a></li>	
';?>	
    </ul>   
    </div>
    </div>
<?php	
if (is_user()) {
/* start my  subscriptions */ 
$followings = $cachedb->get_results("SELECT id,avatar,name,lastlogin from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') order by lastlogin desc limit 0,15");
if($followings) {
$snr = $cachedb->num_rows;
?>

<div class="box blc">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('My subscriptions'); ?></h4><a class="pull-right" href="<?php echo profile_url(user_id(), user_name()); ?>&sk=subscribed"><?php echo _("View all"); ?></a>
</div>
<div class="box-body">
<?php
if($snr > 10) {
echo '<div class="scroll-items">';
}
foreach ($followings as $following) {
echo '
<div class="populars">
<a class="tipW pull-left" title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'"><img src="'.thumb_fix($following->avatar, true, 27, 27).'" alt="'.$following->name.'" /></a>
<span class="pop-title"><a title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">'._cut(stripslashes($following->name), 13).'</a></span>';
if(date('d-m-Y', strtotime($following->lastlogin)) != date('d-m-Y')) {
echo '<i class="icon-circle offline pull-right"></i>';
} else {
echo '<i class="icon-circle online pull-right"></i>';
}
echo '
<div class="clearfix"></div>
</div>';
}
if($snr > 10) {
echo '</div>';
}
echo '</div>
</div>
';
}
/* end subscriptions */
/* start my playlists */	
$plays = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' order by views desc limit 0,100");
if($plays) { 
$plnr = $db->num_rows;
?>
<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('My Playlists'); ?></h4>
</div>
<div class="box-body">
<?php 
if($plnr > 10) {
echo '<div class="scroll-items">';
}
foreach ($plays as $play) {
echo '<div class="populars">
<a class="tipW pull-left" href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'"></a>
<span class="pop-title"><a title="'.$play->title.'" href="'.playlist_url($play->id, $play->title).'">'._cut(stripslashes($play->title), 20).'</a></span>
<div class="clearfix"></div>
</div>';
}
if($plnr > 10) {
echo '</div>';
}
echo '</div>
</div>';
}	
/* end my playlists */	
}

?>	
<?php $users = $cachedb->get_results("SELECT id,name,avatar FROM ".DB_PREFIX."users order by id desc limit 0,20");
if($users) {
$fnr = $cachedb->num_rows;
?>
<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('New users'); ?></h4>
</div>
<div class="box-body">
<?php
if($fnr > 10) {
echo '<div class="scroll-items">';
}
foreach ($users as $user) {
echo '
<div class="populars">
<a class="pull-left" title="'.$user->name.'" href="'.profile_url($user->id , $user->name).'"><img src="'.thumb_fix($user->avatar, true, 27, 27).'" alt="'.$user->name.'" /></a>
<span class="pop-title"><a title="'.$user->name.'" href="'.profile_url($user->id , $user->name).'">'._cut(stripslashes($user->name), 13).'</a></span>';
echo '
<div class="clearfix"></div>
</div>
';
}

if($fnr > 10) {
echo '</div>';
}
echo '</div>
</div>
';
}
?>								
<?php $plays = $cachedb->get_results("SELECT id,title,picture FROM ".DB_PREFIX."playlists where id in (SELECT distinct playlist FROM ".DB_PREFIX."playlist_data)order by views desc limit 0,20");
if($plays) { 
$plnr = $cachedb->num_rows;
?>
<div class="box" style="margin-top:10px;">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('Playlists'); ?></h4>
</div>
<div class="box-body">
<?php 
if($plnr > 10) {
echo '<div class="scroll-items">';
}
foreach ($plays as $play) {
echo '<div class="populars">
<a class="pull-left" href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 27, 27).'"></a>
<span class="pop-title"><a title="'.$play->title.'" href="'.playlist_url($play->id, $play->title).'">'._cut(stripslashes($play->title), 20).'</a></span>
<div class="clearfix"></div>
</div>';
}
if($plnr > 10) {
echo '</div>';
}
echo '</div>
</div>';
}	?>
	</div>
</div>