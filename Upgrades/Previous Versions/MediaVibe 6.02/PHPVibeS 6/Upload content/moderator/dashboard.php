<div class="row">
<div class="col-xlg-4 col-md-6 col-xs-12">
<div class="panel panel-bordered">
<div class="panel-heading">
<h3 class="panel-title">
Statistics
</h3>
</div>
<div class="panel-body nopad scroll-items">
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
    <i class="icon-eye">
        </i>
		<strong>
        <?php echo _count('videos','views'); ?>
      </strong>
      <a href="<?php echo admin_url('videos'); ?>" title="">
         <?php echo _lang('Media views');?>
      </a>
  </li>
   <li class="list-group-item">
    <i class="icon-eye">
        </i>
		<strong>
        <?php echo _count('images','views',true ); ?>
      </strong>
      <a href="<?php echo admin_url('images'); ?>" title="">
         <?php echo _lang('Picture views');?>
      </a>
  </li>
 <li class="list-group-item">
    <i class="icon-check">
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
     <?php echo _lang('Collections');?>
      </a>

  </li>
 <li class="list-group-item">
    <i class="icon-comments">
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
    <i class="icon-linux">
        </i>
		
      <a href="<?php echo admin_url('ffmpeg'); ?>" title="">
        FFMPEG is <code><?php echo (get_option('ffa','0') == 1)? 'On' : 'Off'; ?></code>
      </a>  
  </li>
  
</ul>
</div>
</div>
</div>
<div class="col-xlg-4 col-md-6  col-xs-12 ">
<div class="panel panel-bordered">
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
<div class="panel-heading">
<h3 class="panel-title">Updates</h3>
<div class="panel-actions">
 <a href="http://get.phpvibe.com" target="_blank" class="panel-action"><i class="icon icon-link"></i></a></div>
 </div>
<div class="panel-body nopad scroll-items">
<ul class="list-group">
<li class="list-group-item">
<?php 
$vFull = $upd["cms"]["version"].".".$upd["cms"]["suv"];
$yFull = 5;
 ?>
<div class="show no-margin pd-t-xs"> <h3 style="margin-top:2px;"><?php echo _html($upd["cms"]["release"]); echo '<span class="badge badge-success pull-right">'.$vFull.'</span>'; ?></h3>
</div>
<?php if (file_exists(ABSPATH.'/'.ADMINCP.'/version.php')) {
	include_once(ABSPATH.'/'.ADMINCP.'/version.php');
	$yFull = $phpVersion.'.'.$phpSubversion;
?>
<p><?php echo '<span class="badge badge-primary">This is '.$yFull.'</span>'; ?>
<?php if($yFull < $vFull) { echo "<a href=\"http://www.phpvibe.com/forum/\" target=\"_blank\"><strong class=\"redText\"> This is older. [Info]</strong></a>";} else {echo "<strong class=\"greenText\"> Up to date!</strong>";} ?></p>
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
<div class="show no-margin pd-t-xs"> <a href="http://get.phpvibe.com/buy?id=<?php echo $recent["id"]; ?>" target="_blank"><?php echo _html($recent["name"]); ?></a> <small class="pull-right"><?php echo $recent["buys"]; ?> </small></div>
</li>
<?php } ?>
</ul>
<?php } else {
echo "Failed to retrieve the updates.";	
} ?>
</div>
</div>
</div>
<div class="col-xlg-4 col-md-6  col-xs-12">
<div class="panel panel-bordered">
<?php $countu = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users");
$users = $db->get_results("select * from ".DB_PREFIX."users order by id DESC limit 0,8");
?>
<div class="panel-heading">
<h3 class="panel-title">New users</h3> 
<ul class="panel-actions">
<li><a href="<?php echo admin_url("users");?>">View all (<?php echo $countu->nr; ?>)</a></div></li>

              </ul>
<div class="panel-body nopad scroll-items">
<ul class="list-group">
 <?php foreach ($users as $u) { ?>
<li class="list-group-item">
<div class="show no-margin pd-t-xs"> <a href="<?php echo profile_url($u->id, $u->name); ?>" target="_blank"><?php echo _html($u->name); ?></a> <small class="pull-right"><?php echo count_uvid($u->id); ?> videos</small></div>
<small class="text-muted">Has <?php echo count_uact($u->id); ?> activities so far</small>
</li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>