<div class="row-fluid">
<section class="panel span4">
<div class="panel-heading">Overall</div>
<div class="panel-body nopad">
<ul class="list-group iconed-xlist">
 <li class="list-group-item">  <i class="icon-film"></i>  
   <strong> <?php echo _count('videos'); ?> </strong>   <a href="<?php echo admin_url('videos'); ?>" title=""><?php echo _lang('Videos');?></a>
  </li>
 <li class="list-group-item">
  <i class="icon-group">
        </i>
    <strong>
        <?php echo _count('users'); ?>
      </strong>
      <a href="<?php echo admin_url('users'); ?>" title=""><?php echo _lang('Members');?>
</a>
 </li>
 <li class="list-group-item">
    <i class="icon-eye-open">
        </i>
		<strong>
        <?php echo _count('videos','views',true ); ?>
      </strong>
      <a href="<?php echo admin_url('videos'); ?>" title="">
         <?php echo _lang('Video views');?>
      </a>
  </li>
 <li class="list-group-item">
    <i class="icon-ok">
        </i> <strong><?php echo _count('likes' ); ?> </strong>
      <a href="<?php echo admin_url('videos'); ?>" title="">
        <?php echo _lang('Video likes');?>
      </a>
  </li>
 <li class="list-group-item">
       <i class="icon-fast-forward">
        </i>
		<strong>
        <?php echo _count('playlists' ); ?>
      </strong>
      <a href="<?php echo admin_url('playlists'); ?>" title="">
     <?php echo _lang('Playlists');?>
      </a>

  </li>
 <li class="list-group-item">
    <i class="icon-comment-alt">
        </i>
		<strong>
        <?php echo _count('em_comments' ); ?>
      </strong>
      <a href="<?php echo admin_url('comments'); ?>" title="">
       <?php echo _lang('Comments');?> 
      </a>
  </li>
 <li class="list-group-item">
    <i class="icon-flag">
        </i>
		<strong>
        <?php echo _count('reports' ); ?>
      </strong>
      <a href="<?php echo admin_url('reports'); ?>" title="">
        <?php echo _lang('Reports');?>
      </a>  
  </li>
   <li class="list-group-item">
    <i class="icon-exclamation-sign">
        </i>
		
      <a href="<?php echo admin_url('ffmpeg'); ?>" title="">
        FFMPEG is <?php echo (get_option('ffa','0') == 1)? 'On' : 'Off'; ?>
      </a>  
  </li>
  
</ul>
</div>
</section>

<section class="panel span4">
<div class="panel-heading">Setup</div>
<div class="panel-body nopad">
<ul class="list-group iconed-xlist">
<?php
if (is_readable(ABSPATH.'/setup')) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Setup folder <em>(/setup)</em> exists. Delete it!</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Setup folder is not present</li>';
}
if (!is_writable(ABSPATH.'/'.ADMINCP.'/cache')) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Admin cache & assets folder ('.ABSPATH.'/'.ADMINCP.'/cache/) is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Admin cache is ok.</li>';
}
if (!is_writable(ABSPATH.'/cache')) {
echo '<li class="list-group-item">Cache folder (/cache)is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Cache is ok.</li>';
}
if (!is_writable(ABSPATH.'/cache/full')) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Fullcache folder (/cache/full)is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Full cache is ok.</li>';
}
if (!is_writable(ABSPATH.'/'.get_option('mediafolder'))) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Media storage folder (/'.get_option('mediafolder').')is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Media storage is ok.</li>';
}
if (!is_writable(ABSPATH.'/'.get_option('mediafolder').'/thumbs')) {
echo '<li class="list-group-item">Media thumbs storage folder (/'.get_option('mediafolder').'/thumbs)is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Media thumbs storage is ok.</li>';
}
if (!is_writable(ABSPATH.'/cache/thumbs')) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Thumbs folder (/cache/thumbs) is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Thumbs storage is ok.</li>';
}
if (!is_writable(ABSPATH.'/uploads')) {
echo '<li class="list-group-item"><i class="icon-fire-extinguisher redText"></i>Uploads folder ('.ABSPATH.'/uploads)is not writeable</li>';
} else {
echo '<li class="list-group-item"><i class="icon-ok greenText"></i>Uploads folder is ok.</li>';
}
?>
</ul>
</div>				
</section>
<section class="panel span4">
<?php 
 function getpb($url)
      {
          $ch      = curl_init();
          $timeout = 15;
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
      }
$upd = json_decode(getpb("http://get.phpvibe.com/api"), true);	
if(!nullval($upd)) {
?>
<div class="panel-heading">Updates <a href="http://get.phpvibe.com" target="_blank" class="pull-right pd-l-sm pd-r-sm">View more</a></div>
<div class="panel-body nopad">
<ul class="list-group">
<li class="list-group-item">
<div class="show no-margin pd-t-xs"> <a href="http://get.phpvibe.com/buy?id=22" target="_blank"><h3 style="margin-top:2px;"><?php echo _html($upd["cms"]["release"]); ?></h3></a> <h4 class="pull-right">v<?php echo $upd["cms"]["version"]; ?></h4></div>
<?php $vint = filter_var($upd["cms"]["release"], FILTER_SANITIZE_NUMBER_INT);
$vint = str_replace(array('+','-'), '', $vint);
$vFull = $vint.'.'.$upd["cms"]["suv"];
$yFull = 4.16;
 ?>
<p>Current : <?php echo $vFull; ?></p>
<?php if (file_exists(ABSPATH.'/'.ADMINCP.'/version.php')) {
	include_once(ABSPATH.'/'.ADMINCP.'/version.php');
	$yFull = $phpVersion.'.'.$phpSubversion;
?>
<p>This: <?php echo $yFull; ?></p>
<p><?php if($yFull < $vFull) { echo "<a href=\"http://www.phpvibe.com/forum/client-debates/phpvibe-4-0-patches/#new\" target=\"_blank\"><strong class=\"redText\">Your release is older. [Info]</strong></a>";} else {echo "<strong class=\"greenText\">You use the newest release!</strong>";} ?></p>
<?php } else {
	echo "<strong class=\"redText\">Missing version.php file</strong>";
} ?>
</li>
 <?php  foreach ($upd["latest"] as $recent) {
$thumb = "http://get.phpvibe.com/".stripslashes($recent["thumb"]);
 ?>
<li class="list-group-item">
<span class="pull-left mg-t-xs mg-r-md">
<img src="<?php echo thumb_fix($thumb); ?>" class="avatar avatar-sm" alt="">
</span>
<div class="show no-margin pd-t-xs"> <a href="http://get.phpvibe.com/buy?id=<?php echo $recent["id"]; ?>" target="_blank"><?php echo _html($recent["name"]); ?></a> <small class="pull-right"><i class="icon-user"></i> <?php echo $recent["buys"]; ?> </small></div>
</li>
<?php } ?>
</ul>
<?php } else {
echo "Failed to retrieve the updates.";	
} ?>
</div>
</section>
</div>
<div class="row-fluid">
<section class="panel span8">
<div class="panel-heading">Recent videos <a href="<?php echo admin_url("videos");?>" class="pull-right pd-l-sm pd-r-sm">Manager</a></div>
<table class="table table-bordered">
                          <thead>
                              <tr>
							  <th></th>
                                  <th>Video</th>
								  <th>Views</th>
								  <th>Uploader</th>
								  <th><i class="icon-clock-o"></i></th>
								  <th><i class="icon-pencil"></i></th>
								</tr>  
								  </thead>
								   <tbody>
								   
<?php 
$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.date,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
$vq = $db->get_results("select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id ORDER BY ".DB_PREFIX."videos.id DESC limit 0,8");
if($vq) {
foreach ($vq as $video) {
		?>
<tr>
<td>
<span class="pull-left mg-t-xs mg-r-md"><img class="avatar avatar-xl " src="<?php echo thumb_fix($video->thumb)?>"></span>
</td>
<td><a target="_blank" href="<?php echo video_url($video->id , $video->title); ?>"><?php echo  stripslashes(_cut($video->title, 46)); ?></a>
</td>
<td>
<?php echo $video->views; ?>
</td>
<td>
<?php echo _lang("by").' <a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner ?></a> 

</td>
<td>
<?php echo time_ago($video->date); ?>
</td>
<td>
<a class="tipS" title="<?php echo _lang("Edit"); ?>" href="<?php echo admin_url('edit-video');?>&vid=<?php echo $video->id;?>"><i class="icon-pencil"></i></a>
</td>
</tr>		
<?php } 
}
?>                               
 </tbody>
 </table>
 </section>

<section class="panel span4">
<?php $countu = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users");
$users = $db->get_results("select * from ".DB_PREFIX."users order by id DESC limit 0,8");
?>
<div class="panel-heading">New users <a href="<?php echo admin_url("users");?>" class="pull-right pd-l-sm pd-r-sm">View all (<?php echo $countu->nr; ?>)</a></div>
<div class="panel-body nopad">
<ul class="list-group">
 <?php foreach ($users as $u) { ?>
<li class="list-group-item">
<div class="show no-margin pd-t-xs"> <a href="<?php echo profile_url($u->id, $u->name); ?>" target="_blank"><?php echo _html($u->name); ?></a> <small class="pull-right"><?php echo count_uvid($u->id); ?> videos</small></div>
<small class="text-muted">Has <?php echo count_uact($u->id); ?> activities so far</small>
</li>
<?php } ?>
</ul>
</div>
</section>
</div>			
<?php //End ?>