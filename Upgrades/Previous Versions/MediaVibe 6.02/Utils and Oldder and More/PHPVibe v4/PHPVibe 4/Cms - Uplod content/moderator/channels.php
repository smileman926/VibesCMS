<?php
if(isset($_GET['delete-channel'])) {
$db->get_row("DELETE from ".DB_PREFIX."channels where cat_id ='".intval($_GET['delete-channel'])."' ");
echo '<div class="msg-info">Channel #'.$_GET['delete-channel'].' deleted.</div>';
} 
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
$db->get_row("DELETE from ".DB_PREFIX."channels where cat_id ='".intval($del)."' ");
}
echo '<div class="msg-info">Channels #'.implode(',', $_POST['checkRow']).' deleted.</div>';
}

$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."channels");
$channels = $db->get_results("select ".DB_PREFIX."channels.* from ".DB_PREFIX."channels order by cat_id DESC ".this_limit()."");
if($channels) {
$names = $db->get_results("select cat_id,cat_name from ".DB_PREFIX."channels order by cat_id DESC limit 0,10000000000000000");
$titles = array();
foreach ($names as $naray) {
$titles[$naray->cat_id] = $naray->cat_name;
}
$ps = admin_url('channels').'&p=';

$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>
<form class="form-horizontal styled" action="<?php echo admin_url('channels');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
<h3>Channels management</h3>
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="checkRows" class="styled check-all" /></th>
                                  <th width="130px"><?php echo _lang("Thumb"); ?></th>
                                  <th><?php echo _lang("Channel"); ?></th>								  
								    <th>Accepts</th>
									 <th>Availability</th>
								   <th>Child of</th>
                                                               
								  <th><button class="btn btn-large btn-danger" type="submit"><?php echo _lang("Delete selected"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php 
						  $type= array();
						  $type[1] = "Videos";
						  $type[2] = "Music ";
						  $type[3] = "Images";
						  $sub= array();
						  $sub[0] = "Private";
						  $sub[1] = "Public";
						  
						  
						  foreach ($channels as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $video->cat_id; ?>" class="styled" /></td>
                                  <td>
								  <img src="<?php echo thumb_fix($video->picture); ?>" style="width:130px; height:90px;">
								  </td>
                                  <td><strong><?php echo stripslashes($video->cat_name); ?></strong>
								  <p><?php echo stripslashes($video->cat_desc); ?></p>
								  </td>
								  <td>
								  <?php echo $type[$video->type] ?>
								   </td>
								   <td>
								  <?php echo $sub[$video->sub] ?>
								   </td>
								  <td>
								  <?php if($video->child_of) {
								  echo $titles[$video->child_of];
								  } else {
								  echo '-';
								  }
								  ?>
								  </td>
                                 <td>
								  <p><a href="<?php echo admin_url('channels');?>&p=<?php echo this_page();?>&delete-channel=<?php echo $video->cat_id;?>"><i class="icon-trash" style="margin-right:5px;"></i><?php echo _lang("Delete"); ?></a></p>
								  <p><a href="<?php echo admin_url('edit-channel');?>&id=<?php echo $video->cat_id;?>"><i class="icon-edit" style="margin-right:5px;"></i><?php echo _lang("Edit"); ?></a></p>
								  
								 <p> <a target="_blank" href="<?php echo channel_url($video->cat_id, $video->cat_name);?>"><i class="icon-check" style="margin-right:5px;"></i><?php echo _lang("View"); ?></a></p>
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); }else {
echo '<div class="msg-note">Nothing here yet.</div>';
}

 ?>