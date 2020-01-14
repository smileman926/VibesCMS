<?php 
if(isset($_GET['delete-user'])) {
delete_user(intval($_GET['delete-user']));
} 
if(isset($_GET['ban'])) {
user::BanUser(intval($_GET['ban']));
}
if(isset($_GET['removeban'])) {
user::ChangePass(intval($_GET['removeban']) , md5(intval($_GET['removeban']).time().date()));
}
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
delete_user(intval($del));
}
}
if(isset($_GET['term'])) {
$term = toDb($_GET['term']);
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users where name like '".$term."' or name like '%".$term."' or name like '%".$term."&' or email like '".$term."' ");
$users = $db->get_results("select * from ".DB_PREFIX."users where name like '".$term."' or name like '%".$term."' or name like '%".$term."%' or email like '%".$term."%' order by id DESC ".this_limit()."");
//active
}elseif(isset($_GET['group'])) {
$g = toDb($_GET['group']);
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users where group_id = '".$g."' ");
$users = $db->get_results("select * from ".DB_PREFIX."users where group_id = '".$g."' order by lastNoty DESC ".this_limit()."");
//active
} elseif(isset($_GET['sort'])) {
if($_GET['sort'] == "active") {
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users where id in (SELECT DISTINCT user FROM ".DB_PREFIX."activity)");
$users = $db->get_results("select * from ".DB_PREFIX."users where id in (SELECT DISTINCT user FROM ".DB_PREFIX."activity) order by id DESC ".this_limit()."");
//active
} else {
//inactive
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users where id not in (SELECT DISTINCT user FROM ".DB_PREFIX."activity)");
$users = $db->get_results("select * from ".DB_PREFIX."users where id not in (SELECT DISTINCT user FROM ".DB_PREFIX."activity) order by id DESC ".this_limit()."");

}
} else {
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."users");
$users = $db->get_results("select * from ".DB_PREFIX."users order by id DESC ".this_limit()."");
}
?>
<div class="row">
		    	<form class="search widget" action="" method="get" onsubmit="location.href='<?php echo admin_url('users'); ?>&term=' + encodeURIComponent(this.key.value); return false;">
		    		<div class="autocomplete-append">			   
			    		<input type="text" name="key" placeholder="Search user..." id="key" />
			    		<input type="submit" class="btn btn-info" value="Search" />
			    	</div>
		    	</form>
</div>
<div class="row">
<div class="thefilters blc">
<h2>Filter</h2>
	<?php
	$groups = $db->get_results("select * from ".DB_PREFIX."users_groups order by id ASC ".this_limit()."");
	if($groups) {
	$pp = admin_url('users').'&group=XXX&p=1';	
	foreach ($groups as $group) {
		echo '<a href="'.str_replace("XXX", $group->id,$pp).'"><i class="material-icons">&#xE152;</i> '.$group->name.'</a>';
		
	}
	}
	
	?>
	
	</div>
</div>
<?php
if($users) {
if(isset($term)){
$ps = admin_url('users').'&term='.$term.'&p=';
}elseif(isset($_GET['sort'])) {
$ps = admin_url('users').'&sort='.$_GET['sort'].'&p=';
}elseif(isset($_GET['group'])) {
$ps = admin_url('users').'&group='.$_GET['group'].'&p=';
} else {
$ps = admin_url('users').'&p=';
}
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($ps);
?>

<form class="form-horizontal styled" action="<?php echo $ps;?><?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
<th> <div class="checkbox-custom checkbox-danger"> <input type="checkbox" name="checkRows" class="check-all" /> <label for="checkRows"></label> </div>  </th>
                                  <th width="130px"><button class="confirm btn btn-sm btn-danger" type="submit"><?php echo _lang("Delete selected"); ?></button></th>
                                  <th><?php echo _lang("Name"); ?></th>
                                  <th><?php echo _lang("About"); ?></th>                             
								  <th>Options</th>
                              </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($users as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                  <td><img src="<?php echo thumb_fix($video->avatar); ?>" style="width:130px; height:90px;">
								  <p><a href="<?php echo profile_url($video->id, $video->name); ?>" target="_blank">Visit profile</a></p>			 
								 </td>
                                  <td><strong style="color:#111111; font-size:17px"><?php echo _html($video->name); ?></strong> - <?php echo _html($video->email); ?>
								  <p><strong><?php echo count_uvid($video->id); ?></strong> videos , <strong><?php echo count_uact($video->id); ?></strong> activities</p>
								  
								  </td>
								  <td><?php echo _html($video->bio); ?></td>
								  <td>
								  <div class="btn-group">
								  
								  <a class="right20" href="<?php echo admin_url('edit-user');?>&id=<?php echo $video->id;?>"><i class="material-icons mright10">&#xE254;</i></a>
								 <?php 
if(!_contains($video->pass, 'banned')) { ?>
								 <a class="tipS right20" title="Ban user" href="<?php echo admin_url('users');?>&p=<?php echo this_page();?>&ban=<?php echo $video->id;?>"><i class="material-icons">&#xE14B;</i></a>
<?php } else { ?>  
<a class="tipS right20" title="Remove ban on user" href="<?php echo admin_url('users');?>&p=<?php echo this_page();?>&removeban=<?php echo $video->id;?>"><i class="material-icons">&#xE15D;</i></a>


<?php } ?>  
								  <a class="tipS confirm" title="Remove user account" href="<?php echo admin_url('users');?>&p=<?php echo this_page();?>&delete-user=<?php echo $video->id;?>"><i class="material-icons">&#xE92B;</i></a>

								  </div>
								  
								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); } else { echo "No user found."; } ?>
