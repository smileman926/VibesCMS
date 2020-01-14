<?php  $the = $_GET;
$the['bpp'] = 15;
if(!isset($the['auto'])) { $the['auto'] = 0;}
if(!isset($the['imode']) || nullval($the['imode'])) { $the['imode'] = 2;}
$auto = ($the['imode'] < 2) ? true : false;
$all = ($the['auto'] > 0) ? true : false;
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $raw) {
$video = base64_decode($raw);
youtube_import(maybe_unserialize($video), intval($the['categ']),$the['owner']);
}
echo '<div class="msg-info">Selected videos have been imported.</div>';
}
$youtube = new Youtube(array('key' => get_option('youtubekey')));
$params = array(
    'maxResults'    => $the['bpp']
);

if(!isset($the['name'])) {
$playlist = $youtube->getPlaylistById($the['pID']);
$the['name'] = $playlist->snippet->title.' <em>by '.$playlist->snippet->channelTitle.'</em>';
}
echo '<h1> '.$the['name'].'</h1>';
if(!$all) {
$params['pageToken'] = $youtube->thisToken($params['maxResults'],this_page()); 
$search = $youtube->getPlaylistItemsByPlaylistId($the['pID'],$the['bpp']);	
if(!$auto) { ?>
<form id="validate" class="form-horizontal styled" action="<?php echo canonical();?>" enctype="multipart/form-data" method="post">
<button class="btn btn-large btn-default" type="submit"><?php echo _lang("Import all selected"); ?></button>
<div class="blc" style="height:20px;"></div>
<?php } ?>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                                <tr>   
<?php if(!$auto) { ?>	
<th>
 <div class="checkbox-custom checkbox-primary">
<input type="checkbox" name="checkRows" class="styled check-all" />
<label></label>
 </div>
</th>                                 
<?php } ?>							
                                  <th width="130px"></th>								 
                                  <th><?php echo _lang("Video"); ?></th>
							      <th>Status</th>								  
                                  <th>Youtube link</th> 
								</tr>
                          </thead>
                          <tbody>						  
						  <?php 
						  if($search) {
						  foreach ($search as $vd) {
$video = $youtube->Single($vd->contentDetails->videoId);
						  ?>
                              <tr>
							  <?php if(!$auto) { ?>	
                             <td><input type="checkbox" name="checkRow[]" value="<?php echo base64_encode(maybe_serialize($video)); ?>" class="styled" /></td>
                            <?php } ?>	
							<td><img src="<?php echo $video['thumb']; ?>" style="width:130px; height:90px;"></td>
                                  <td><strong><?php echo _html($video['title']); ?></strong>
								  <br/> <?php echo _cut(_html($video['description']), 120); ?>
								  </td>
                                  <td>
								    <?php 
									if($auto) {
									if(isset($the['allowduplicates']) && ($the['allowduplicates'] > 0)) {
								   echo '<span class="greenText">Imported</span>';
								  youtube_import($video,$the['categ'],$the['owner'] );
								  } else {
                                   if(ytExists($video['videoid'])) {
								    echo '<span class="redText">Skipped as duplicate</span>';
								   } else {
								    echo '<span class="greenText">Imported</span>';
									youtube_import($video,$the['categ'],$the['owner'] );
								   }
                                  }
									} else {
                                  if(ytExists($video['videoid'])) {
								    echo '<span class="redText">Exists in site</span>';
								  } else {
									  echo '<span class="greenText">Unique</span>';
								  }

								  }								  
								  
								  ?>
								  </td>
								  <td><a class="btn btn-primary btn-small" href="https://www.youtube.com/watch?v=<?php echo $video['videoid']; ?>" target="_blank"><i class="icon-play whiteText"></i>  Preview</a></td>
                                  
                              </tr>
							  <?php 
							  }  //end loop 
} else {
	echo "API : No more results";
}
							  ?>
 </tbody>  
 </table>
 </div>
<?php if(!$auto) { ?>
</form><?php } 
$params['pageToken'] = $youtube->nextToken($params['maxResults'],this_page());
unset($the['p']);
$pageVars = $the;
$pagi = admin_url().'?'.urldecode(http_build_query($pageVars)).'&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(12);
$a->set_per_page($the['bpp']);
$a->set_values(500);
$a->show_pages($pagi);
} else {
	if(!$auto) { ?>
<form id="validate" class="form-horizontal styled" action="<?php echo canonical();?>" enctype="multipart/form-data" method="post">
<button class="btn btn-large btn-default" type="submit"><?php echo _lang("Import all selected"); ?></button>
<div class="blc" style="height:20px;"></div>
<?php } ?>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                                <tr>   
<?php if(!$auto) { ?>	
<th>
 <div class="checkbox-custom checkbox-primary">
<input type="checkbox" name="checkRows" class="styled check-all" />
<label></label>
 </div>
</th>                                 
<?php } ?>							
                                  <th width="130px"></th>								 
                                  <th><?php echo _lang("Video"); ?></th>
							      <th>Status</th>								  
                                  <th>Youtube link</th> 
								</tr>
                          </thead>
                          <tbody> 
	<?php //Monster loop videos
	
	for($i=1; $i <= 5000; $i++) {
$params = array(
    'maxResults'    => $the['bpp']
);
$params['pageToken'] = $youtube->thisToken($params['maxResults'],$i); 
$search = $youtube->getPlaylistItemsByPlaylistId($the['pID'],$the['bpp'],$i);
if($search) {
foreach ($search as $vd) {
$video = $youtube->Single($vd->contentDetails->videoId);
						  ?>
                              <tr>
							  <?php if(!$auto) { ?>	
                             <td><input type="checkbox" name="checkRow[]" value="<?php echo base64_encode(maybe_serialize($video)); ?>" class="styled" /></td>
                            <?php } ?>	
							<td><img src="<?php echo $video['thumb']; ?>" style="width:130px; height:90px;"></td>
                                  <td><strong><?php echo _html($video['title']); ?></strong>
								  <br/> <?php echo _cut(_html($video['description']), 120); ?>
								  </td>
                                  <td>
								    <?php 
									if($auto) {
									if(isset($the['allowduplicates']) && ($the['allowduplicates'] > 0)) {
								   echo '<span class="greenText">Imported</span>';
								  youtube_import($video,$the['categ'],$the['owner'] );
								  } else {
                                   if(ytExists($video['videoid'])) {
								    echo '<span class="redText">Skipped as duplicate</span>';
								   } else {
								    echo '<span class="greenText">Imported</span>';
									youtube_import($video,$the['categ'],$the['owner'] );
								   }
                                  }
									} else {
                                  if(ytExists($video['videoid'])) {
								    echo '<span class="redText">Exists in site</span>';
								  } else {
									  echo '<span class="greenText">Unique</span>';
								  }

								  }								  
								  
								  ?>
								  </td>
								  <td><a class="btn btn-primary btn-small" href="https://www.youtube.com/watch?v=<?php echo $video['videoid']; ?>" target="_blank"><i class="icon-play whiteText"></i>  Preview</a></td>
                                  
                              </tr>
							  <?php 
							  }  //end loop 
			
} else {
break;
}	
	}
	echo '
 </tbody>  
 </table>
 </div>
	';
	
}
?>
<div class="row" style="padding: 10px 0">
<a class="btn btn-large btn-success pull-right" href="<?php echo str_replace('sk=','sk=crons&docreate&type=',$pagi);?>" ><i class="icon-time"></i>Automate this</a>
</div>
