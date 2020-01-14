<?php
if(isset($_GET['delete-cron'])) {
$cron = $_GET['delete-cron'];
delete_cron($cron);
echo '<div class="msg-info">Cron #'.$cron.' deleted.</div>';

}
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
delete_cron($del);
}
echo '<div class="msg-info">Crons #'.implode(',', $_POST['checkRow']).' deleted.</div>';
}
if(isset($_GET["docreate"])) {
add_cron($_GET);

} else {
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."crons");
$crons = $db->get_results("select * from ".DB_PREFIX."crons order by cron_id DESC ".this_limit()."");
$ps = admin_url('crons').'&p=';

$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>
<form class="form-horizontal styled" action="<?php echo admin_url('crons');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
<h3>Crons management</h3>
<?php if($crons) { ?>
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="checkRows" class="styled check-all" /></th>
                                 
                                  <th>Cron</th>								   
                                   <th>Last Run</th> 
                                   <th>Run interval  <br /> <i> in seconds</i></th> 	
                                   <th>Number of pages <br /> <i> 25 results/page</i></th> 									   
								  <th><button class="btn btn-large btn-danger" type="submit"><?php echo _lang("Delete selected"); ?></button></th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($crons as $cron) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $cron->cron_id; ?>" class="styled" /></td>
                                
                                  <td><strong><?php echo stripslashes($cron->cron_name);?></strong>
								 <td><strong><?php if($cron->cron_lastrun > 1) { echo $cron->cron_lastrun; } else { echo "Never";} ?></strong>
								 <td><strong><?php echo stripslashes($cron->cron_period); ?></strong>
								  <td><strong><?php echo stripslashes($cron->cron_pages); ?></strong>
								  </td>
								  
                                 <td>
								 
								  <p><a href="<?php echo admin_url('crons');?>&p=<?php echo this_page();?>&delete-cron=<?php echo $cron->cron_id;?>"><i class="icon-trash" style="margin-right:5px;"></i><?php echo _lang("Delete"); ?></a></p>
								  <p><a href="<?php echo admin_url('edit-cron');?>&id=<?php echo $cron->cron_id;?>"><i class="icon-edit" style="margin-right:5px;"></i><?php echo _lang("Edit"); ?></a></p>
								  
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>

<?php  $a->show_pages($ps); } else { 
echo '<div class="msg-note">No crons yet</div>';
} 
}
?>