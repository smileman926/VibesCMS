<?php the_sidebar(); 
$active = _get("sk"); if(is_null(_get("sk"))) { $active = "profile";}
do_action('profile-start');
$vd = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr, sum(views) as vnr , sum(liked) as lnr FROM ".DB_PREFIX."videos where user_id='".$profile->id."'");
$id = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr, sum(views) as vnr, sum(liked) as lnr FROM ".DB_PREFIX."images where user_id='".$profile->id."'");
$ad = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."activity where user='".$profile->id."'");
$md = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr FROM ".DB_PREFIX."videos where media > 1 and user_id='".$profile->id."'");
 ?>
<div id="profile-content" class="row">
<div id="profile-content" class="col-md-12">
<?php $pcover = (not_empty($profile->cover)) ? thumb_fix($profile->cover) : tpl().'images/default-cover.jpg';   ?>
<div id="profile-cover" class="row" style="height:320px; background-image: url(<?php echo $pcover;?>);">
    
<div class="avatar-holder block row" style="position:absolute; bottom:-3px; left:10px;">
			    <a class="avatar avatar-lg pull-left block " href="javascript:void(0)">
                  <img class="profile-image" src="<?php echo thumb_fix($profile->avatar, true, 160, 160);?>" alt="<?php echo _html($profile->name);  ?>">
                </a>	          
</div> 
</div>
<div class="playlistvibe profile-hero">
<div class="cute">
<h1><?php echo _html($profile->name);  ?>
<div class="btn-group">
<a href="<?php echo site_url();?>msg/<?php echo $profile->id;?>/" class="btn btn-sm btn-danger tipS" title="<?php echo _lang("Message"). ' '._html($profile->name); ?>"><i class="icon-envelope"></i> <?php echo _lang("Message");?></a>
</div>

</h1>
<div class="cute-line"></div>
<div class="block row full">
<div class="pull-left">
 <div class="row mtop10">
                <div class="inline-block mright10">
                  <strong class="profile-stat-count"><?php echo u_k($vd->nr + $id->nr);?></strong>
                  <span><?php echo _lang("Shares"); ?></span>
                </div>
                <div class="inline-block mright10">
                  <strong class="profile-stat-count"><?php echo u_k($vd->vnr + $id->vnr);?></strong>
                  <span><?php echo _lang("Media views"); ?></span>
                </div>
                <div class="inline-block">
                  <strong class="profile-stat-count"><?php echo u_k($vd->lnr + $id->lnr);?></strong>
                  <span><?php echo _lang("Likes"); ?></span>
                </div>
              </div>
</div>
<div class="profile-social inline pull-right">
<?php if($profile->twlink) { ?><a target="_blank" rel="nofollow" class="icon icon-twitter" href="<?php echo $profile->twlink;?>"></a> <?php } ?>
<?php if($profile->fblink) { ?><a target="_blank" rel="nofollow" class="icon icon-facebook" href="<?php echo $profile->fblink;?>"></a> <?php } ?>
<?php if($profile->glink) { ?> <a target="_blank" rel="nofollow" class="icon icon-google-plus" href="<?php echo $profile->glink;?>"></a> <?php } ?>
<?php if($profile->iglink) { ?> <a target="_blank" rel="nofollow" class="icon icon-instagram" href="<?php echo $profile->iglink;?>"></a> <?php } ?>
                </div>
</div>
</div>
</div>			
<nav id="profile-nav" class="red-nav">
  <ul>
    <li class="<?php echo aTab("profile");?>"><a href="<?php echo $canonical; ?>"><?php echo _lang("Channel"); ?></a></li>
	<li class="<?php echo aTab("comments");?>"><a href="<?php echo $canonical; ?>?sk=comments"><?php echo _lang("About"); ?></a></li>
	<li class="<?php echo aTab("collections");?>"><a href="<?php echo $canonical; ?>?sk=collections"><?php echo _lang("Collections"); ?></a></li>
    <li class="<?php echo aTab("videos");?>"><a href="<?php echo $canonical; ?>?sk=videos"><?php echo _lang("Videos"); ?></a></li>
    <li class="<?php echo aTab("images");?>"><a href="<?php echo $canonical; ?>?sk=images"><?php echo _lang("Images"); ?></a></li>
    <li class="<?php echo aTab("music");?>"><a href="<?php echo $canonical; ?>?sk=music"><?php echo _lang("Music"); ?></a></li>
    <li class="<?php echo aTab("activity");?>"><a href="<?php echo $canonical; ?>?sk=activity"><?php echo _lang("Activity"); ?></a></li>
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
$pagestructure = $canonical.'?sk=subscribed&p=';
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
$pagestructure = $canonical.'?sk=subscribers&p=';
$bp = bpp();
break;
case 'activity':
$sort =(isset($_GET['sort']) && (intval($_GET['sort']) > 0) ) ? "and type='".intval($_GET['sort'])."'" : "";
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort);
$vq = "Select * from ".DB_PREFIX."activity where user='".$profile->id."' ".$sort." ORDER BY id DESC ".this_offset(45);
include_once(TPL.'/profile/activity.php');	
break;	
case 'videos':
$pagestructure = $canonical.'?sk=videos&p=';
$canonical = $canonical.'?sk=videos';
include_once(TPL.'/profile/user_videos.php');
break;
case 'collections':
$pagestructure = $canonical.'?sk=collections&p=';
$canonical = $canonical.'?sk=collections';
include_once(TPL.'/profile/user_collections.php');
break;
case 'music':
$pagestructure = $canonical.'?sk=music&p=';
$canonical = $canonical.'?sk=music';
include_once(TPL.'/profile/user_music.php');
break;	
default:
$pagestructure = $canonical.'?p=';
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
<?php if ($profile->id <> user_id()) { ?>
<div class="row block text-center mtop20 mbot20">		  
<?php subscribe_box($profile->id); ?>	
</div>
<?php } ?> 
