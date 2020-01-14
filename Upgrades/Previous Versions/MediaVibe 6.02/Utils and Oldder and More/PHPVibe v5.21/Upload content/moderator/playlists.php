<?php
if(isset($_GET['delete-playlist'])) {
delete_playlist(intval($_GET['delete-playlist']));
echo '<div class="msg-info">Playlist #'.$_GET['delete-playlist'].' deleted.</div>';
} 
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
delete_playlist(intval($del));
}
echo '<div class="msg-info">Playlists #'.implode(',', $_POST['checkRow']).' deleted.</div>';
}
$add = "";
if(_get('sort')) {
$add = 'and ptype = '.intval(_get('sort')). ' ';	
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."playlists WHERE ".DB_PREFIX."playlists.picture not in ('[likes]','[history]','[later]') $add");
$playlists = $db->get_results("select ".DB_PREFIX."playlists.*, ".DB_PREFIX."users.name as user from ".DB_PREFIX."playlists LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."playlists.owner = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."playlists.picture not in ('[likes]','[history]','[later]') $add order by id DESC ".this_limit()."");

if($playlists) {

if(_get('sort')) {
$ps = admin_url('playlists').'&sort='._get('sort').'&p=';	
} else {
$ps = admin_url('playlists').'&p=';
}
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>
<form class="form-horizontal styled" action="<?php echo admin_url('playlists');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
<th> <div class="checkbox-custom checkbox-danger"> <input type="checkbox" name="checkRows" class="check-all" /> <label for="checkRows"></label> </div>  </th>
                                  <th width="130px"><?php echo _lang("Thumb"); ?></th>
								    <th><?php echo _lang("Type"); ?></th>
                                  <th><?php echo _lang("Collection"); ?></th>
                                  <th><?php echo _lang("Owner"); ?></th>
								
                                
								  <th><button class="btn btn-large btn-danger" type="submit"><?php echo _lang("Delete selected"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($playlists as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td><img src="<?php echo thumb_fix($video->picture); ?>" style="width:130px; height:90px;"></td>
                                    <td><a href="<?php echo admin_url('playlists');?>&sort=<?php echo $video->ptype; ?>">
								  <?php if($video->ptype > 1) {echo "Album";} else {echo "Playlist";} ?>
								   </a>		
								  </td>
								  <td><?php echo _html($video->title); ?></td>
                                  <td><a href="<?php echo profile_url($video->owner, $video->user); ?>" target="_blank"><?php echo $video->user; ?></a></td>                                 
								
									 						 
								 <td>
								  <div class="btn-group"><a class="btn btn-sm btn-outline btn-danger confirm" href="<?php echo admin_url('playlists');?>&p=<?php echo this_page();?>&delete-playlist=<?php echo $video->id;?>"><i class="icon-trash" style="margin-right:5px;"></i><?php echo _lang("Delete"); ?></a>
								  <a class="btn btn-sm btn-outline btn-primary" href="<?php echo admin_url('edit-playlist');?>&id=<?php echo $video->id;?>"><i class="icon-edit" style="margin-right:5px;"></i><?php echo _lang("Edit"); ?></a>
								  <a class="btn btn-sm btn-outline btn-success" target="_blank" href="<?php echo playlist_url($video->id, $video->title);?>"><i class="icon-check" style="margin-right:5px;"></i><?php echo _lang("View"); ?></a></div>
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); } ?>
