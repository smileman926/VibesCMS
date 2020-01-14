<?php
if(isset($_GET['delete-page'])) {
$db->query("DELETE from ".DB_PREFIX."posts where pid = '".intval($_GET['delete-page'])."' ");
echo '<div class="msg-info">Page deleted</div>';
} 
$menux = array("0" => "No","1" => "Yes" );
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."posts");
$pages = $db->get_results("select * from ".DB_PREFIX."posts ORDER BY pid DESC ".this_limit()."");
$categories = $db->get_results("SELECT cat_id as id, cat_name as name FROM  ".DB_PREFIX."postcats order by cat_name asc limit 0,10000");
$ch = array();
$ch[0] = "None";
if($categories) {
foreach ($categories as $cat) {	
$ch[$cat->id] = _html($cat->name);
	}
}
?>
<div class="row">
<h3>Blog posts</h3>				
</div>
<?php
if($pages) {
$ps = admin_url('posts').'&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?><div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th width="100px">Pic</th>
                                  <th width="35%">Title</th>
								  <th>Created</th>
                                  <th>Category</th>
								   <th>Options</th>
                                  </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($pages as $page) { ?>
                              <tr>
                                  <td><img src="<?php echo thumb_fix($page->pic, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="width:100px; height:50px;"></td>
                                  <td><?php echo _html($page->title); ?></td>
								  <td><?php echo time_ago($page->date); ?></td> 	
                                  <td><?php echo $ch[intval($page->ch)]; ?></td>                                    							  
								  <td>
								  <div class="btn-group">
								   <a class="btn btn-success btn-sm btn-outline tipS" target="_blank" href="<?php echo page_url($page->pid, $page->title);?>" title="<?php echo _lang("View"); ?>"><i class="icon-eye" style=""></i></a>
								   <a class="btn btn-info btn-sm btn-outline tipS" href="<?php echo admin_url('edit-post');?>&pid=<?php echo $page->pid;?>" title="<?php echo _lang("Edit"); ?>"><i class="icon-edit" style=""></i></a>
								   <a class="btn btn-danger btn-sm btn-outline tipS" href="<?php echo $ps;?>&p=<?php echo this_page();?>&delete-page=<?php echo $page->pid;?>" title="Delete"><i class="icon-trash" style=""></i></a>
                                 </div>
								</td>
                              </tr>
							   <?php } ?>
						</tbody>  
</table>
</div>						

<?php  $a->show_pages($ps); 
}else {
echo '<div class="msg-note">Nothing here yet.</div>';
}

 ?>
