<?php the_sidebar(); do_action('profile-start'); ?>
<div id="profile-cover" class="row-fluid" style="<?php if($profile->cover) { ?>background-image: url('<?php echo thumb_fix($profile->cover, true,'', 220);?>')!important; background-repeat: no-repeat; background-size: 100% 220px; <?php } ?>">
<div class="profile-avatar">
<a rel="lightbox" href="<?php echo thumb_fix($profile->avatar);?>"><img class="img-shadow" src="<?php echo thumb_fix($profile->avatar, true, 210, 210);?>" alt="<?php echo $profile->name;?>" /></a>
<?php if(is_powner()) { ?>
<div class="btn-avatar btn-group">
<a href="<?php echo site_url(); ?>dashboard/&sk=edit" class="btn btn-small"><i class="icon-pencil"></i> <?php echo _lang("change");?></a>
</div>
			
<?php } ?>				
</div>

<?php if(is_powner()) { ?>
<div class="cgcover"><a href="javascript:void(0)"><i class="icon-pencil"></i></a></div>
<div class="box bottom20 top-up upcover hide">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang("Change cover <em>(height: 220px)</em>");?></h4>
</div>
<div class="box-body" style="padding:10px;">
<form class="styled " action="<?php echo canonical();?>/" enctype="multipart/form-data" method="post">
<input type="hidden" id="changecover" name="changecover" value="yes" />
<input type="file" id="cover" name="cover" class="styled" />
<button class="btn btn-small" type="submit"><?php echo _lang("Upload cover"); ?></button>
</form>
</div>
</div>
<?php } ?>

<div class="clearfix"></div>
</div>
<div id="profile-head">
<div class="row-fluid inner-head">
<div class="span7">
<a href="<?php echo $canonical; ?>"><h1 class="profile-heading"> <?php 
if(date('d-m-Y', strtotime($profile->lastlogin)) != date('d-m-Y')) {
echo '<i class="icon-circle-thin offline" style="margin-right:7px;"></i>';
} else {
echo '<i class="icon-circle-thin online" style="margin-right:7px;"></i>';
} echo _html($profile->name);  ?>
</h1></a>
<?php 
$vd = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."videos where user_id='".$profile->id."'");
$vvd = $cachedb->get_row("SELECT sum(views) as nr FROM ".DB_PREFIX."videos where user_id='".$profile->id."'");
$ad = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."activity where user='".$profile->id."'");
?>
<ul class="user-stats">
<li><a class="tipS" href="<?php echo $canonical;?>" title="<?php echo _lang("See all video shared"); ?>"><i class="icon-youtube-play"></i> <?php echo u_k($vd->nr);?> <?php echo _lang("Videos"); ?></a>  </li>
<li> <a class="tipS" href="<?php echo $canonical.'&sk=activity';?>" title="<?php echo _lang("See all activity"); ?>"><i class="icon-bell"></i> <?php echo u_k($ad->nr);?> <?php echo _lang("Activities"); ?></a>  </li>
<li> <i class="icon-eye-open"></i> <?php echo u_k($vvd->nr);?> <?php echo _lang("Video views"); ?> </li>
<li> <i class="icon-eye-open"></i> <?php echo u_k($profile->views);?> <?php echo _lang("profile views"); ?></li>
</ul>
</div>
<div class="span5">
<div class="pull-right"><?php subscribe_box($profile->id); ?></div>
<div class="clearfix"></div>
<ul class="psocial">
<?php if($profile->fblink) { ?> <li> <a href="https://facebook.com/<?php echo $profile->fblink;?>" rel="nofollow" target="_blank"><i class="icon-facebook-sign" style="color:#4964a1;"></i></a> </li><?php } ?>
<?php if($profile->glink) { ?> <li> <a href="https://plus.google.com/<?php echo $profile->glink;?>" rel="nofollow" target="_blank"><i class="icon-google-plus" style="color:#d94b3f;"></i></a> </li><?php } ?>
<?php if($profile->twlink) { ?> <li> <a href="https://twitter.com/<?php echo $profile->twlink;?>" rel="nofollow" target="_blank"><i class="icon-twitter" style="color: #59adeb;"></i></a> </li><?php } ?>

</ul>
<div class="clearfix"></div>
</div>
</div>
</div>
<div class="row-fluid block main-holder top10" style="position:relative;">

<div id="profile-content">

<?php do_action('profile-precontent');
switch(_get('sk')){
case 'subscribed':
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select uid from ".DB_PREFIX."users_friends where fid ='".$profile->id."')");
$vq = "select id,avatar,name from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select uid from ".DB_PREFIX."users_friends where fid ='".$profile->id."') ORDER BY ".DB_PREFIX."users.views DESC ".this_offset(18);
include_once(TPL.'/profile/users.php');	
$pagestructure = $canonical.'&sk=subscribed&p=';
$bp = bpp();	
break;
case 'subscribers':
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."')");
$vq = "select id,avatar,name from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') ORDER BY ".DB_PREFIX."users.views DESC ".this_offset(18);
include_once(TPL.'/profile/users.php');	
$pagestructure = $canonical.'&sk=subscribers&p=';
$bp = bpp();
break;
case 'activity':
echo '
<div class="block sorts">
<div class="btn-group sort-it">
<a data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toogle"><i class="icon-eye-open"></i></a>	
<a class="btn btn-small btn-primary">'._lang("Filter activity").' </a>
<ul class="dropdown-menu pad-icons">
			<li title="'._lang("All activity").'"><a href="'.$canonical.'"><i class="icon-list"></i>'._lang("All activity").'</a></li>
			<li title="'._lang("Videos shared").'"><a href="'.$canonical.'&sort=4"><i class="icon-play"></i>'._lang("Videos shared").'</a></li>
			<li title="'._lang("Liked videos").'"><a href="'.$canonical.'&sort=1"><i class="icon-heart"></i>'._lang("Liked videos").'</a></li>
			<li title="'._lang("Comments").'"><a href="'.$canonical.'&sort=6"><i class="icon-comments"></i>'._lang("Comments").'</a></li>	
            <li title="'._lang("Watched").'"><a href="'.$canonical.'&sort=3"><i class="icon-eye-open"></i>'._lang("Watched videos").'</a></li>			
			</ul>
 		
		</div>
	<div class="clearfix"></div>	
	</div>
';
$sort =(isset($_GET['sort']) && (intval($_GET['sort']) > 0) ) ? "and type='".intval($_GET['sort'])."'" : "";
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort);
$vq = "Select * from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort." ORDER BY id DESC ".this_offset(15);
include_once(TPL.'/profile/activity.php');	
$pagestructure = $canonical.'&sk=activity&p=';
$bp = 15;
break;	
default:
$pagestructure = $canonical.'&p=';
include_once(TPL.'/profile/user_videos.php');
break;		
}
if(isset($bp) && $pagestructure) {
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page($bp);
$a->set_values($count->nr);
$a->show_pages($pagestructure);
}
do_action('profile-postcontent');
?>
</div>
<div class="profile-right pull-right"> 
<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang("About"); ?></h4>
<?php if(is_powner()) { ?>
<a href="<?php echo site_url(); ?>dashboard/&sk=edit" class="btn btn-small pull-right tipS" title="<?php echo _lang("Edit your details");?>"><i class="icon-pencil"></i></a>
<?php } ?>	
</div>
<div class="box-body list">
<ul>
<li class="from">
<i class="icon-home"></i> 
<?php echo _("Lives in"); ?><?php if($profile->local) { ?>  <?php echo _html($profile->local);?>, <?php } ?> <?php if($profile->country) { ?> <?php echo _html($profile->country);?> <?php } ?>
</li>
<?php if($profile->bio) { ?><li class="bio"><?php echo _html($profile->bio);?> </li><?php } ?>
<?php if($profile->lastlogin) { ?> <li> <?php echo _lang("Last login:");?> <?php echo time_ago($profile->lastlogin);?> </li><?php } ?>
<?php if($profile->date_registered) { ?> <li> <?php echo _lang("Registered:");?> <?php echo time_ago($profile->date_registered);?> </li><?php } ?>

</ul>
</div>
</div>
<?php $plays = $cachedb->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".$profile->id."' and picture not in ('[likes]','[history]','[later]') order by title asc limit 0,100");
if($plays) { 
$plnr = $cachedb->num_rows;
?>
<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('Playlists'); ?></h4>
</div>
<div class="box-body">
<?php 
if($plnr > 12) {
echo '<div class="scroll-items">';
}
foreach ($plays as $play) {
echo '<div class="media-list">
<a class="tipW pull-left" href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><img src="'.thumb_fix($play->picture, true, 103, 154).'"></a>
<span class="pop-title"><a title="'.$play->title.'" href="'.playlist_url($play->id, $play->title).'">'._cut(stripslashes($play->title), 15).'</a></span>
<div class="clearfix"></div>
</div>';
}
if($plnr > 10) {
echo '</div>';
}
echo '</div>
</div>';
}	?>

<?php 
if(_get('sk') !== "subscribers") {
 $followers = $cachedb->get_results("SELECT id,avatar,name,lastlogin from ".DB_PREFIX."users where id in (select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') order by lastlogin desc limit 0,9");
if($followers) {
$fnr = $cachedb->num_rows;
?>
<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('Followed by'); ?></h4><a class="pull-right" href="<?php echo $canonical; ?>&sk=subscribers"><?php echo _lang("View all"); ?></a>
</div>
<div class="box-body">
<?php
foreach ($followers as $follower) {
echo '
<div class="populars">
<a class="tipW pull-left" title="'.$follower->name.'" href="'.profile_url($follower->id , $follower->name).'"><img src="'.thumb_fix($follower->avatar, true, 96, 96).'" alt="'.$follower->name.'" /></a>
<span class="pop-title"><a title="'.$follower->name.'" href="'.profile_url($follower->id , $follower->name).'">'._html(_cut($follower->name), 12).'</a></span>';
if(date('d-m-Y', strtotime($follower->lastlogin)) == date('d-m-Y')) {
echo '<i class="icon-circle online"></i>';
}
echo '
</div>
';
}
echo '</div>
</div>
';
}
}
if(_get('sk') !== "subscribed") {
$followings = $cachedb->get_results("SELECT id,avatar,name,lastlogin from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".$profile->id."') order by lastlogin desc limit 0,9");
if($followings) {
$snr = $cachedb->num_rows;
?>

<div class="box">
<div class="box-head">
<h4 class="box-heading"><?php echo _lang('Following'); ?></h4><a class="pull-right" href="<?php echo $canonical; ?>&sk=subscribed"><?php echo _lang("View all"); ?></a>
</div>
<div class="box-body">
<?php
foreach ($followings as $following) {
echo '
<div class="populars">
<a class="tipW pull-left" title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'"><img src="'.thumb_fix($following->avatar, true, 96, 96).'" alt="'.$following->name.'" /></a>
<span class="pop-title"><a title="'.$following->name.'" href="'.profile_url($following->id , $following->name).'">'._html(_cut($following->name), 12).'</a></span>';
if(date('d-m-Y', strtotime($following->lastlogin)) == date('d-m-Y')) {
echo '<i class="icon-circle online"></i>';
}
echo '
</div>';
}
echo '</div>
</div>
';

}
}
?>
<?php echo _ad('0','profile-right'); 
do_action('profile-sidebarend');
?>
</div>
