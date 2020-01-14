<?php the_header(); the_sidebar(); 
the_sidebar(); ?>
<div class="row">
<div id="DashSidebar" class="col-md-2 col-xs-12">
<?php   do_action('dashSide-top'); ?>
<div class="nav-tabs-vertical">
<ul class="nav nav-tabs nav-tabs-line">
<li class=""><a href="<?php echo site_url(); ?>dashboard/"><i class="icon icon-hashtag"></i><?php echo _lang("Overview");?></a></li>
<li class=""><a href="<?php echo site_url(); ?>dashboard/&sk=edit"><i class="icon icon-cogs"></i><?php echo _lang("Channel Settings");?></a></li>
<li class=""><a href="<?php echo site_url().me; ?>"><i class="icon icon-film"></i><?php echo _lang("Videos");?></a></li>
<li class="left20"><a href="<?php echo site_url().me; ?>&sk=playlists"><i class="icon icon-bars"></i><?php echo _lang("Playlists");?></a></li>
<li class=""><a href="<?php echo site_url().me; ?>&sk=images"><i class="icon icon-camera"></i><?php echo _lang("Images");?></a></li>
<li class="left20"><a href="<?php echo site_url().me; ?>&sk=albums"><i class="icon icon-bars"></i><?php echo _lang("Albums");?></a></li>
<li class="left20"><a href="<?php echo site_url().me; ?>&sk=hearts"><i class="icon icon-heart"></i><?php echo _lang("Loved");?></a></li>
<li class=""><a href="<?php echo site_url().me; ?>&sk=music"><i class="icon icon-headphones"></i><?php echo _lang("Music");?></a></li>
</ul>
</div>
<?php
do_action('dashSide-bottom'); ?>
</span>
</div>
 <div id="DashContent" class="col-md-7 col-xs-12 left20 page"> 
 <div class="row odet">
<?php if(_get('msg')) {echo '<div class="msg-info">'.toDb(_get('msg')).'</div>';}
if(isset($msg)) {echo $msg;}
do_action('dash-top'); 
 if((_get('sk') == "edit") || isset($_POST['changeavatar']) ||isset($_POST['changeuser'])  ) {
include_once(TPL.'/profile/edit.php');		
} else { ?>	
<div class="row odet">
<div class="panel panel-transparent">
<div class="panel-heading">
<h4 class="panel-title"><i class="icon icon-bell-o"></i><?php echo _lang("Latest activity");?></h4>
</div>
<div class="panel-body">
<?php
//Latest notifications
$count= $db->get_row("Select count(*) as nr from vibe_activity where (type not in (8,9) and vibe_activity.object in (select id from vibe_videos where user_id ='".user_id()."' ) ) or (type in (8,9) and vibe_activity.object in (select id from vibe_images where user_id ='".user_id()."' ) ) and user <> '".user_id()."'");

if($count){
if($count->nr > 0) {
$vq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where
((".DB_PREFIX."activity.type not in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' ))  or
(".DB_PREFIX."activity.type in (8,9) and ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."images where user_id ='".user_id()."' ))) and ".DB_PREFIX."activity.user <> '".user_id()."'
ORDER BY ".DB_PREFIX."activity.id DESC ".this_limit(bpp());
$activity = $db->get_results($vq);
if ($activity) {
$did =  array();
echo '<div class="row">
<ul id="user-timeline" class="timelist user-timeline">
'; 
$licon = array();
$licon["1"] = "icon-heart";
$licon["2"] = "icon-share";
$licon["3"] = "icon-youtube-play";
$licon["4"] = "icon-upload";
$licon["5"] = "icon-rss";
$licon["6"] = "icon-comments";
$licon["7"] = "icon-thumbs-up";
$licon["8"] = "icon-camera";
$licon["9"] = "icon-star";
$lback = array();
$lback["1"] = $lback["9"] = "bg-smooth";
$lback["2"] = "bg-success";
$lback["3"] = "bg-flat";
$lback["4"] = $lback["8"] = "bg-default";
$lback["5"] = "bg-default";
$lback["6"] = "bg-info";
$lback["7"] = "bg-smooth";
foreach ($activity as $buzz) {
$did = get_activity($buzz);	
if(isset($did["what"]) && !nullval($did["what"])) {
echo '
<li class="cul-'.$buzz->type.' t-item">
 <div class="user-timeline-time">'.time_ago($buzz->date).'</div>
<i class="icon '.$licon[$buzz->type].' user-timeline-icon '.$lback[$buzz->type].'"></i>
<div class="user-timeline-content">
<p><a href="'.profile_url($buzz->pid,$buzz->name).'">'._html($buzz->name).'</a>  '.$did["what"].'</p>
';
if(isset($did["content"]) && !nullval($did["content"])) {
echo '<div class="timeline-media">'.$did["content"].'</div>';
}
echo '</div>

</li>';
unset($did);
}
}
echo '</ul><br style="clear:both;"/></div>';
}	
} else {
echo '<p>'._lang("No activity on your media yet").'</p>';	
}
} else {
echo '<p>'._lang("No activity yet").'</p>';	
}
?>	
</div>
</div>
</div>
<?php
}
 do_action('dash-bottom'); ?>
</div>
<?php do_action('dashboard-bottom'); ?>
