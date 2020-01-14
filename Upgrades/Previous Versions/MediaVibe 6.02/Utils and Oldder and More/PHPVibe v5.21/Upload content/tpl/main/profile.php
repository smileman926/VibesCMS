<?php the_sidebar(); 
$active = _get("sk"); if(is_null(_get("sk"))) { $active = "profile";}
do_action('profile-start');
$vd = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr, sum(views) as vnr , sum(liked) as lnr FROM ".DB_PREFIX."videos where user_id='".$profile->id."'");
$id = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr, sum(views) as vnr, sum(liked) as lnr FROM ".DB_PREFIX."images where user_id='".$profile->id."'");
$ad = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."activity where user='".$profile->id."'");
$md = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr FROM ".DB_PREFIX."videos where media > 1 and user_id='".$profile->id."'");
 ?>
<div class="row">
<div class="col-md-3">
<div class="widget widget-shadow text-center">
            <div class="widget-header">
              <div class="widget-header-content">
			  <div class="avatar-holder">
			    <a class="avatar avatar-lg" href="javascript:void(0)">
                  <img class="profile-image" src="<?php echo thumb_fix($profile->avatar, true, 160, 160);?>" alt="<?php echo _html($profile->name);  ?>">
                </a>
				</div>
                <h4 class="profile-user"><?php echo _html($profile->name);  ?></h4>
                <p class="profile-location"> <i class="icon standardico icon-map-marker"></i>  <?php if($profile->local) { ?>  <?php echo _html($profile->local);?>, <?php } ?> <?php if($profile->country) { ?> <?php echo _html($profile->country);?> <?php } else { echo _lang("Unknown");} ?></p>
                <p><?php echo _html($profile->bio);?></p>
           
				
<?php subscribe_box($profile->id); ?>     
<div class="profile-social">
<?php if($profile->twlink) { ?><a target="_blank" rel="nofollow" class="icon icon-twitter" href="<?php echo $profile->twlink;?>"></a> <?php } ?>
<?php if($profile->fblink) { ?><a target="_blank" rel="nofollow" class="icon icon-facebook" href="<?php echo $profile->fblink;?>"></a> <?php } ?>
<?php if($profile->glink) { ?> <a target="_blank" rel="nofollow" class="icon icon-google-plus" href="<?php echo $profile->glink;?>"></a> <?php } ?>
<?php if($profile->iglink) { ?> <a target="_blank" rel="nofollow" class="icon icon-instagram" href="<?php echo $profile->iglink;?>"></a> <?php } ?>
                </div>
          
		 </div>
            </div>
            <div class="widget-footer">
              <div class="row no-space">
                <div class="col-xs-4">
                  <strong class="profile-stat-count"><?php echo u_k($vd->nr + $id->nr);?></strong>
                  <span><?php echo _lang("Shares"); ?></span>
                </div>
                <div class="col-xs-4">
                  <strong class="profile-stat-count"><?php echo u_k($vd->vnr + $id->vnr);?></strong>
                  <span><?php echo _lang("Media views"); ?></span>
                </div>
                <div class="col-xs-4">
                  <strong class="profile-stat-count"><?php echo u_k($vd->lnr + $id->lnr);?></strong>
                  <span><?php echo _lang("Likes"); ?></span>
                </div>
              </div>
			 
            </div>
			 <div class="row no-space msg-footer">
			  <a href="<?php echo site_url();?>msg/<?php echo $profile->id;?>/" class="btn btn-block btn-squared btn-primary btn-raised active"><?php echo _lang("Message"); ?></a>
			  </div>
          </div>
<?php  $followers = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') order by lastlogin desc limit 0,5");
        if($followers) { ?>
		<h4 class="user-heads block mtop20"><?php echo _lang('Followed by'); ?></h4>
		<ul class="list-group list-group-dividered">
		<?php foreach ($followers as $follower) { ?>
		  <li class="list-group-item user-small-list">
          <a class="" title="<?php echo $follower->name;?>" href="<?php echo profile_url($follower->id , $follower->name); ?>">
		  <img src="<?php echo thumb_fix($follower->avatar, true, 23, 23);?>" alt="<?php echo  $follower->name; ?>" />
		  <?php echo  $follower->name; ?>
		  </a>
          </li>
		<?php } ?>                  
        </ul>	
    <div class="row no-space msg-footer bottom20">
	<a href="<?php echo $canonical; ?>&sk=subscribers" class="btn btn-block btn-squared btn-default"><?php echo _lang("All followers"); ?></a>
	</div>		
<?php } ?>
<?php  $fans = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') order by lastlogin desc limit 0,5");
        if($fans) { ?>
		<h4 class="user-heads block mtop20"><?php echo _lang('Subscribed to'); ?></h4>
		<ul class="list-group list-group-dividered">
		<?php foreach ($fans as $fan) { ?>
		  <li class="list-group-item user-small-list">
          <a class="" title="<?php echo $fan->name;?>" href="<?php echo profile_url($fan->id , $fan->name); ?>">
		  <img src="<?php echo thumb_fix($fan->avatar, true, 23, 23);?>" alt="<?php echo  $fan->name; ?>" />
		  <?php echo  $fan->name; ?>
		  </a>
          </li>
		<?php } ?>                  
        </ul>	
    <div class="row no-space msg-footer bottom20">
	<a href="<?php echo $canonical; ?>&sk=subscribed" class="btn btn-block btn-squared btn-default"><?php echo _lang("All subscriptions"); ?></a>
	</div>		
<?php } ?>
</div>
<div id="profile-content" class="col-md-9">
<nav id="profile-nav" class="red-nav">
  <ul>
    <li class="<?php echo aTab("profile");?>"><a href="<?php echo $canonical; ?>"><?php echo _lang("Channel"); ?></a></li>
	<li class="<?php echo aTab("collections");?>"><a href="<?php echo $canonical; ?>&sk=collections"><?php echo _lang("Collections"); ?></a></li>
    <li class="<?php echo aTab("videos");?>"><a href="<?php echo $canonical; ?>&sk=videos"><?php echo _lang("Videos</a>"); ?></li>
    <li class="<?php echo aTab("images");?>"><a href="<?php echo $canonical; ?>&sk=images"><?php echo _lang("Images</a>"); ?></li>
    <li class="<?php echo aTab("music");?>"><a href="<?php echo $canonical; ?>&sk=music"><?php echo _lang("Music</a>"); ?></li>
    <li class="<?php echo aTab("activity");?>"><a href="<?php echo $canonical; ?>&sk=activity"><?php echo _lang("Activity</a>"); ?></li>
	<li class="<?php echo aTab("comments");?>"><a href="<?php echo $canonical; ?>&sk=comments"><?php echo _lang("Comments</a>"); ?></li>
  </ul>
</nav>
<div id="panel-<?php echo $active;?>" class="panel">
<div class="panel-body">

<?php do_action('profile-precontent');
switch(_get('sk')){
case 'subscribed':
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select uid from ".DB_PREFIX."users_friends where fid ='".$profile->id."')");
$vq = "select id,avatar,name,lastnoty from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select uid from ".DB_PREFIX."users_friends where fid ='".$profile->id."') ORDER BY ".DB_PREFIX."users.views DESC ".this_offset(18);
include_once(TPL.'/profile/users.php');	
$pagestructure = $canonical.'&sk=subscribed&p=';
$bp = bpp();	
break;
case 'comments':
include_once(TPL.'/profile/user_coms.php');	
break;
case 'images':
include_once(TPL.'/profile/user_images.php');	
break;
case 'subscribers':
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."')");
$vq = "select id,avatar,name,lastnoty from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$profile->id."') ORDER BY ".DB_PREFIX."users.views DESC ".this_offset(18);
include_once(TPL.'/profile/users.php');	
$pagestructure = $canonical.'&sk=subscribers&p=';
$bp = bpp();
break;
case 'activity':
$sort =(isset($_GET['sort']) && (intval($_GET['sort']) > 0) ) ? "and type='".intval($_GET['sort'])."'" : "";
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort);
$vq = "Select * from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort." ORDER BY id DESC ".this_offset(45);
include_once(TPL.'/profile/activity.php');	
break;	
case 'videos':
$pagestructure = $canonical.'&sk=videos&p=';
$canonical = $canonical.'&sk=videos';
include_once(TPL.'/profile/user_videos.php');
break;
case 'collections':
$pagestructure = $canonical.'&sk=collections&p=';
$canonical = $canonical.'&sk=collections';
include_once(TPL.'/profile/user_collections.php');
break;
case 'music':
$pagestructure = $canonical.'&sk=music&p=';
$canonical = $canonical.'&sk=music';
include_once(TPL.'/profile/user_music.php');
break;	
default:
$pagestructure = $canonical.'&p=';
include_once(TPL.'/profile/user_profile.php');
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
</div>
</div>
</div>
</div>
