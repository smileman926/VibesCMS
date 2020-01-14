<?php echo the_sidebar(); ?>
<div id="my-content" class="main-holder pad-holder span12 top10" style="padding-right:12px;">
<div class="row-fluid clearfix">
<?php 
echo default_content();
$module = isset($_GET['sk']) ? $_GET['sk'] : '';
switch($module) { 
case "videos":
default:
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where user_id ='".user_id()."'");
?>
<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder fbcolor">
<i class="icon-film"></i>
</div>
</div>
<div class="span8">
<h1><?php echo _lang("Media manager");?></h1>
<?php echo _lang("Media shared by").' '.user_name();?>
<p><?php echo $count->nr; ?> <?php echo _lang("entries.");?><p>
</div>
</div>
<?php
$videos = $db->get_results("select id,title,thumb, views, liked, duration from ".DB_PREFIX."videos where user_id ='".user_id()."' and pub > 0 ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit()."");
if($videos) {
$ps = site_url().me.'/&sk=videos&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
?>

<form class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=videos&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="checkRows" class="styled check-all" /></th>                                  
                                  <th colspan="2"><?php echo _lang("Video"); ?></th>
                                  <th><?php echo _lang("Duration"); ?></th>
                                  <th><?php echo _lang("Likes"); ?></th>
                                  <th><?php echo _lang("Views"); ?></th>
								  <th width="110px"><button class="btn btn-danger" type="submit"><?php echo _lang("Unpublish"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td width="100"><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></a></td>
                                  <td><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><strong><?php echo _html($video->title); ?></strong></a></td>
                                  <td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo stripslashes($video->liked); ?></td>
                                  <td><?php echo stripslashes($video->views); ?></td>
								  <td>
								  <div class="btn-group">
<a class="btn btn-danger tipS" href="<?php echo site_url().me;?>&sk=videos&p=<?php echo this_page();?>&delete-video=<?php echo $video->id;?>" title="<?php echo _lang("Unpublish"); ?>"><i class="icon-trash" style=""></i></a>
								   <?php if((get_option('uploadrule') == 1) ||  is_moderator()) { ?>
								  <a class="btn btn-info tipS" href="<?php echo site_url().me;?>&sk=edit-video&vid=<?php echo $video->id;?>" title="<?php echo _lang("Edit"); ?>"><i class="icon-edit" style=""></i></a>
								 <?php } ?>
						
								  </div>
								 
								 
							
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); }
 break; 
  
case "likes":
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select vid from ".DB_PREFIX."likes where uid ='".user_id()."' and type='like')");
$videos = $db->get_results("select id,title,thumb, views, liked, duration from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select vid from ".DB_PREFIX."likes where uid ='".user_id()."' and type='like') ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit()."");
?>

<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder googlecolor">
<i class="icon-heart"></i>
</div>
</div>
<div class="span8">
<h1><?php echo _lang("What you appreciate");?></h1>
<?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("liked by").' '.user_name();?>
<p><a class="btn btn-default" href="<?php echo site_url(); ?>forward/<?php echo likes_playlist(); ?>"><i class="icon-play"></i> <?php echo  _lang('Play all'); ?></a> </p>

</div>
</div>
<?php
if($videos) {
$ps = site_url().me.'&sk=likes&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
?>

<form class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=likes&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="likesRows" class="styled check-all" /></th>
                                  <th colspan="2"><?php echo _lang("Video"); ?></th>
                                  <th><?php echo _lang("Duration"); ?></th>
                                  <th><?php echo _lang("Likes"); ?></th>
                                  <th><?php echo _lang("Views"); ?></th>
								  <th width="110px"><button class="btn btn-danger" type="submit"><?php echo _lang("Remove"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="likesRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td width="100"><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></a></td>
                                  <td><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><?php echo _html($video->title); ?></a></td>
                                  <td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo stripslashes($video->liked); ?></td>
                                  <td><?php echo stripslashes($video->views); ?></td>
								  <td>
								  <div class="btn-group">
<a class="btn btn-default tipS" href="<?php echo site_url().me;?>&sk=likes&p=<?php echo this_page();?>&delete-like=<?php echo $video->id;?>" title="<?php echo _lang("Remove rating"); ?>"><i class="icon-trash" style=""></i></a>
								  
								  </div>
								  
								  
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); }
 break; 
 case "history":
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select video_id from ".DB_PREFIX."playlist_data where playlist ='".history_playlist()."')");
$videos = $db->get_results("select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
WHERE ".DB_PREFIX."playlist_data.playlist =  '".history_playlist()."' and ".DB_PREFIX."videos.pub > 0
ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_limit());
?>
<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder twittercolor">
<i class="icon-check-square"></i>
</div>
</div>
<div class="span8">
<h1><?php echo _lang("What you've watched");?></h1>
<?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("watched by").' '.user_name();?>
<p><a class="btn btn-default" href="<?php echo site_url(); ?>forward/<?php echo history_playlist(); ?>"><i class="icon-play"></i> <?php echo  _lang('Play all'); ?></a> 
</p>

</div>
</div>
<?php if($videos) {
$ps = site_url().me.'&sk=history&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
?>

<div class="cleafix full"></div>
<div class="table-overflow top10">
                        <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th colspan="2"><?php echo _lang("Video"); ?></th>
                                  <th><?php echo _lang("Duration"); ?></th>
                                  <th><?php echo _lang("Likes"); ?></th>
                                  <th><?php echo _lang("Views"); ?></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>
                                  <td width="100"><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></a></td>
                                  <td><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><?php echo _html($video->title); ?></a></td>
                                  <td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo stripslashes($video->liked); ?></td>
                                  <td><?php echo stripslashes($video->views); ?></td>
								 
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						

<?php  $a->show_pages($ps); }
 break;  
 case "later":
 if(_get("removelater")) {
playlist_remove(later_playlist(), _get("removelater"));
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select video_id from ".DB_PREFIX."playlist_data where playlist ='".later_playlist()."')");
$videos = $db->get_results("select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
WHERE ".DB_PREFIX."playlist_data.playlist =  '".later_playlist()."' and ".DB_PREFIX."videos.pub > 0
ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_limit());
?>
<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder twittercolor">
<i class="icon-clock-o"></i>
</div>
</div>
<div class="span8">
<h1><?php echo _lang("Watch later");?></h1>
<?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("saved for later by").' '.user_name();?>
<p><a class="btn btn-default" href="<?php echo site_url(); ?>forward/<?php echo later_playlist(); ?>"><i class="icon-play"></i> <?php echo  _lang('Play all'); ?></a> 
</a> 
</p>

</div>
</div>
<?php
if($videos) {
$ps = site_url().me.'&sk=later&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>

<div class="cleafix full"></div>
<div class="table-overflow top10">
                        <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th colspan="2" width="60%"><?php echo _lang("Video"); ?></th>
                                  <th><?php echo _lang("Duration"); ?></th>
                                  <th><?php echo _lang("Likes"); ?></th>
                                  <th><?php echo _lang("Views"); ?></th>
								  <th><?php echo _lang("Remove"); ?></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>
							 
                                  <td width="100" style="max-width:100px;"><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></a></td>
                                  <td><a class="tipS" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><?php echo _html($video->title); ?></a></td>
                                  <td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo stripslashes($video->liked); ?></td>
                                  <td><?php echo stripslashes($video->views); ?></td>
								 <td>
								 <a class="btn btn-default tipS" href="<?php echo site_url().me;?>&sk=later&p=<?php echo this_page();?>&removelater=<?php echo $video->id;?>" title="<?php echo _lang("Remove this"); ?>"><i class="icon-trash"></i></a>
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						

<?php  $a->show_pages($ps); }
 break;  
case "playlists":
$count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlists where owner= '".user_id()."'");
$videos = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') order by title desc ".this_limit()."");
?>
<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder googlecolor">
<i class="icon-list-alt"></i>
</div>
</div>
<div class="span8">
<h1><?php echo _lang("Playlists manager");?></h1>
<?php echo $count->nr; ?> <?php echo _lang("playlists by").' '.user_name();?>
<p><a class="btn btn-default" href="<?php echo site_url().me; ?>/&sk=new-playlist"><i class="icon-play"></i> <?php echo  _lang('Create a new playlist'); ?></a> 
</p>
</div>
</div>
<?php

if($videos) {
$ps = site_url().me.'&sk=playlists&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>
<form class="form-horizontal styled mtop10" action="<?php echo site_url().me;?>&sk=playlists&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="likesRows" class="styled check-all" /></th>
								  <th><?php echo _lang("Videos"); ?></th>                               
                                  <th colspan="2"><?php echo _lang("Playlist"); ?></th>
                                  <th><?php echo _lang("Description"); ?></th>                                  
								  <th width="110px"><button class="btn btn-danger" type="submit"><?php echo _lang("Remove"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>							  
                                  <td><input type="checkbox" name="playlistsRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td><a class="btn btn-default tipS" href="<?php echo site_url().me;?>&sk=manage-playlists&playlist=<?php echo $video->id;?>" title="<?php echo _lang("Manage the videos in "); echo _html($video->title); ?>"><i class="icon-th-list"></i></a></td>
								  <td width="100"> <a class="tipS" target="_blank" href="<?php echo playlist_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->picture, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></a></td>
                                  <td> <a class="tipS" target="_blank" href="<?php echo playlist_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><strong><?php echo _html($video->title); ?></strong></a></td>
                                  <td><?php echo _html($video->description); ?></td>                                 
								  
								 <td>
								 <a class="btn btn-default tipS" href="<?php echo site_url().me;?>&sk=playlists&p=<?php echo this_page();?>&delete-playlist=<?php echo $video->id;?>" title="<?php echo _lang("Delete playlist"); ?>"><i class="icon-trash"></i></a>
								 
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); }
break; 
  
case "manage-playlists":
if(!_get("playlist")) {
die(_lang("Something went wrong"));
}
$play_check = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and  id= '".toDb(_get("playlist"))."' order by views desc limit 0,1");
if(!$play_check) {
die(_lang("Something went wrong"));
}
if(_get("playlist") && _get("remove") && $play_check) {
playlist_remove(_get("playlist"), _get("remove"));
}
if(_get("playlist") && isset($_POST['playlistsRemoval']) && $play_check) {
playlist_remove(_get("playlist"), $_POST['playlistsRemoval']);
}
$count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlist_data where playlist= '".toDb($play_check->id)."'");

$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
$vq = "select ".$options." FROM ".DB_PREFIX."videos WHERE ".DB_PREFIX."videos.id in (SELECT ".DB_PREFIX."playlist_data.video_id from ".DB_PREFIX."playlist_data where playlist='".$play_check->id."') ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset(bpp());
$videos = $db->get_results($vq); ?>

<div class="row-fluid blc mIdent">
<div class="span3">
<div class="iholder twittercolor">
<i class="icon-th-list"></i>
</div>
</div>
<div class="span8">
<h1><?php echo $play_check->title;?></h1>
<?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("saved for later by").' '.user_name();?>
<p><a class="btn btn-default" href="<?php echo site_url(); ?>forward/<?php echo $play_check->id; ?>"><i class="icon-play"></i> <?php echo  _lang('Play all'); ?></a> 
</a> 
</p>

</div>
</div>
<?php
if($videos) {
$ps = site_url().me.'&sk=manage-playlists&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>

<form class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=manage-playlists&playlist=<?php echo _get("playlist");?>" enctype="multipart/form-data" method="post">
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="likesRows" class="styled check-all" /></th>                                  
                                  <th colspan="2"><?php echo _lang("Video"); ?></th>
								  <th><?php echo _lang("Duration"); ?></th>
                                  <th><?php echo _lang("Likes"); ?></th>
                                  <th><?php echo _lang("Views"); ?></th>
                                                             
								  <th><button class="btn btn-danger" type="submit"><?php echo _lang("Remove selected"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($videos as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="playlistsRemoval[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td width="130"><img src="<?php echo thumb_fix($video->thumb); ?>" style="width:130px; height:90px;"></td>
                                  <td><?php echo stripslashes($video->title); ?></td>
<td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo stripslashes($video->liked); ?></td>
                                  <td><?php echo stripslashes($video->views); ?></td>								  
								  <td>
								  <p><a href="<?php echo site_url().me;?>&sk=manage-playlists&playlist=<?php echo _get("playlist");?>&remove=<?php echo $video->id;?>"><i class="icon-trash" style=""></i> <?php echo _lang("Remove"); ?></a></p>
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); }
 break; 
case "edit-video":
if(isset($_POST['edited-video'])) {
echo '<div class="msg-hint top10 bottom10">'.$_POST['title']._lang(" updated.").'</div>';
}
if(!_get("vid")){
die(_lang("Missing video id"));
}
if((get_option('uploadrule') <> 1 )&&  !is_moderator()) {
die(_lang("Video editing has been disabled by the administrator"));
}
$video = $db->get_row("SELECT * from ".DB_PREFIX."videos where user_id= '".user_id()."' and id = '".intval(_get("vid"))."' ");
if($video) {
?>
<form id="validate" class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=edit-video&vid=<?php echo $video->id; ?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="edited-video" id="edited-video" value = "<?php echo $video->id; ?>"/>
<div class="control-group blc row-fluid">
<label class="control-label"><i class="icon-bookmark"></i><?php echo _lang("Title"); ?></label>
<div class="controls">
<input type="text" name="title" class="validate[required] span12" value="<?php echo $video->title; ?>" /> 						
</div>	
</div>	
	
<div class="control-group blc row-fluid">
<label class="control-label"><?php echo _lang("Description"); ?></label>
<div class="controls">
<textarea rows="5" cols="5" name="description" class="auto validate[required] span12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"><?php echo $video->description; ?></textarea>					
</div>	
</div>
<div class="control-group blc row-fluid">
<label class="control-label"><?php echo _lang("Image"); ?></label>
<div class="controls">
<input type="file" id="play-img" name="play-img" class="styled" />
<span class="help-block" id="limit-text"><?php echo _lang("Select only if you wish to change the image");?></span>
</div>	
</div>
<div class="control-group blc row-fluid">
	<label class="control-label"><?php echo _lang("Duration (in seconds):") ?></label>
	<div class="controls">
	<input type="text" id="duration" name="duration" class="validate[required] span12" value="<?php echo $video->duration; ?>">
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label"><?php echo _lang("Category:"); ?></label>
	<div class="controls">
	<?php echo cats_select('categ','select','validate[required]', $video->media); ?>
	<?php  if(isset($hint)) { ?>
	  <span class="help-block"> <?php echo $hint; ?></span>
	<?php } ?>  
	  </div>             
	  </div>
	  
	  <script>
	      $(document).ready(function(){
	$('.select').find('option[value="<?php echo $video->category;?>"]').attr("selected",true);	
});
	</script>
	  <div class="control-group blc row-fluid">
	<label class="control-label"><?php echo _lang("Tags:"); ?></label>
	<div class="controls">
	<input type="text" id="tags" name="tags" class="tags span12" value="<?php echo $video->tags; ?>">
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label"><?php echo _lang("NSFW:"); ?></label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="1" <?php if($video->nsfw > 0 ) { echo "checked"; } ?>> <?php echo _lang("Not safe"); ?> </label>
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="0" <?php if($video->nsfw < 1 ) { echo "checked"; } ?>><?php echo _lang("Safe"); ?></label>
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label"><?php echo _lang("Visibility"); ?> </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="1" <?php if($video->private > 0 ) { echo "checked"; } ?>> <?php echo _lang("Users only");?> </label>
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="0" <?php if($video->private < 1 ) { echo "checked"; } ?>><?php echo _lang("Everybody");?> </label>
	</div>
	</div>
<div class="control-group blc row-fluid">
<button class="btn btn-primary pull-right" type="submit"><?php echo _lang("Update video"); ?></button>	
</div>	
</fieldset>						
</form>
<?php
} else {
echo '<div class="msg-warning">'._lang("This video does not belong to you").'</div>';
}
 break; 
case "password":
?>
<h3><?php echo _lang("Change password"); ?></h3>
<form id="validate" class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=password" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="change-password" id="change-password" value = "1"/>
<div class="control-group blc row-fluid">
<label class="control-label"><i class="icon-unlock"></i>New password</label>
<div class="controls">
<input type="password" name="pass1" class="validate[required] span8" value="" /> 						
</div>	
</div>	
<div class="control-group blc row-fluid">
<label class="control-label"><i class="icon-unlock"></i>Repeat password</label>
<div class="controls">
<input type="password" name="pass2" class="validate[required] span8" value="" /> 						
</div>	
</div>	
<div class="control-group">
<button class="btn btn-primary pull-right" type="submit"><?php echo _lang("Change password"); ?></button>	
</div>	
</fieldset>
</form>
<?php	
 break; 
case "new-playlist":
if(isset($_POST['play-name'])) {
echo '<div class="msg-hint top10 bottom10">'.$_POST['play-name']._lang(" created.").'</div>';
}
?>
<form id="validate" class="form-horizontal styled" action="<?php echo site_url().me;?>&sk=new-playlist" enctype="multipart/form-data" method="post">
<fieldset>
<div class="control-group">
<label class="control-label"><i class="icon-bookmark"></i><?php echo _lang("Title"); ?></label>
<div class="controls">
<input type="text" name="play-name" class="validate[required] span12" placeholder="<?php echo _lang("Your playlist's title"); ?>" /> 						
</div>	
</div>	
	
<div class="control-group">
<label class="control-label"><?php echo _lang("Description"); ?></label>
<div class="controls">
<textarea rows="5" cols="5" name="play-desc" class="auto validate[required] span12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"></textarea>					
</div>	
</div>
<div class="control-group">
<label class="control-label"><?php echo _lang("Playlist image"); ?></label>
<div class="controls">
<input type="file" id="play-img" name="play-img" class="styled validate[required]" />
</div>	
</div>
<div class="control-group">
<button class="btn btn-primary pull-right" type="submit"><?php echo _lang("Create playlist"); ?></button>	
</div>	
</fieldset>						
</form>
<?php

 break; 
} ?>
</div>
</div>