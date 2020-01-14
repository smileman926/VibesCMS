<?php //Usergroups
if(isset($_POST['add_group'])) {
$db->query("INSERT INTO ".DB_PREFIX."users_groups (`name`,`admin`) VALUES ('".$db->escape($_POST['group-name'])."','".intval($_POST['admin'])."')
");
echo '<div class="msg-win">Usergroup "'.$_POST['group-name'].'" was created.</div>';
}
if(isset($_GET['delete'])) {
if(intval($_GET['delete']) > 4) {
$db->get_row("DELETE from ".DB_PREFIX."users_groups where id ='".intval($_GET['delete'])."'");
echo '<div class="msg-win">Usergroup #'.intval($_GET['delete']).' was removed.</div>';
} else {
echo '<div class="msg-warning">Usergroup #'.intval($_GET['delete']).' was not removed : Reason protected group</div>';
}
} 
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users_groups");
$users = $db->get_results("select * from ".DB_PREFIX."users_groups order by id ASC ".this_limit()."");

if($users) {
if(isset($term)){
$ps = admin_url('usergroups').'&term='.$term.'&p=';
}else if(isset($_GET['sort'])) {
$ps = admin_url('usergroups').'&sort='.$_GET['sort'].'&p=';
} else {
$ps = admin_url('usergroups').'&p=';
}
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
$ar = array("0" => "No", "1" => "Yes")
?>

<form class="form-horizontal styled" action="<?php echo $ps;?><?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><?php echo _lang("Id"); ?></th>
                                  <th><?php echo _lang("Group"); ?></th>
                                  <th><?php echo _lang("Is admin"); ?></th>
                                <th>Remove</th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($users as $video) { ?>
                              <tr>
                                <td><?php echo _html($video->id); ?> </td>
                                  <td><?php echo _html($video->name); ?> </td>
								  <td><?php echo $ar[intval($video->admin)]; ?></td>
								  <td>
								  <p><a class="confirm" href="<?php echo admin_url('usergroups');?>&p=<?php echo this_page();?>&delete=<?php echo $video->id;?>"><i class="icon-trash" style="margin-right:5px;"></i><?php echo _lang("Delete"); ?></a></p>
								  
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>	
<h3>Add group</h3>
<form class="form-horizontal styled" action="<?php echo $ps;?><?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>		
<input type="hidden" name="add_group" class="hide" value="1" />	
<div class="form-group form-material">
<label class="control-label"><i class="icon-user"></i>Group's name</label>
<div class="controls">
<input type="text" name="group-name" class=" col-md-6" value="" /> 
<span class="help-block" id="limit-text">New group's name.</span>						
</div>	
</div>	
<div class="form-group form-material">
	<label class="control-label"><i class="icon-key"></i>Is admin</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="admin" class="styled" value="1" >Yes</label>
	<label class="radio inline"><input type="radio" name="admin" class="styled" value="0" checked>No</label>
	</div>
	</div>
<div class="form-group form-material">
<button class="btn btn-large btn-primary pull-right" type="submit">Add group</button>	
</div>		
</fieldset>					
</form>
<?php  $a->show_pages($ps); } else {
$db->query("INSERT INTO `".DB_PREFIX."users_groups` (`id`, `name`, `admin`, `default_value`, `access_level`) VALUES
(1, 'Administrators', 1, 0, 3),
(4, 'Members', 0, 1, 1),
(3, 'Author', 0, 2, 2),
(2, 'Moderators', 0, 2, 2);");
 echo '<div class="msg-win">Small error. Usergroups missing. Default ones where installed. Please refresh page.</div>';
 } ?>
