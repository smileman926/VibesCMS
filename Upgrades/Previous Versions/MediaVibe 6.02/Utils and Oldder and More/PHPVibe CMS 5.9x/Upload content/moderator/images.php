<?php function add_sort($sorter){
	global $ps;
	if($sorter == "featured") {		
	return str_replace('&sort=','&sort='.$sorter.';',$ps);
	}
	return admin_url('images').'&sort='.$sorter.'&p=1';
}
function remove_sort($sorter){
	global $ps;
	return str_replace($sorter.'','',$ps);
}
function get_domain($url)
{
	if ((strpos($url,'localfile') !== false) || ($url == 'up')) {
	return '<i class="icon-cloud-upload"></i>';	
	}
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return str_replace('.com','',$regs['domain']);
  }
  return false;
}

if(isset($_GET['delete-image'])) {
unpublish_image(intval($_GET['delete-image']));
} 
if(isset($_GET['feature-image'])) {
$id = intval($_GET['feature-image']);
if($id){
$db->query("UPDATE ".DB_PREFIX."images set featured = '1' where id='".intval($id)."'");
echo '<div class="msg-info">Image #'.$id.' was featured.</div>';
}
} 
if(isset($_GET['unfeature-image'])) {
$id = intval($_GET['unfeature-image']);
if($id){
$db->query("UPDATE ".DB_PREFIX."images set featured = '0' where id='".intval($id)."'");
echo '<div class="msg-info">Image #'.$id.' was unfeatured.</div>';
}
} 
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
unpublish_image(intval($del));
}
echo '<div class="msg-info">Images #'.implode(',', $_POST['checkRow']).' unpublished.</div>';
}
$order = "ORDER BY ".DB_PREFIX."images.id desc";
$where = "";
$sortA = array();
if(isset($_GET['sort']))  {
$sortA = explode(";",$_GET['sort'] );
$sortA = array_unique(array_filter($sortA));	
if(in_array("featured", $sortA )) {
$where = "and featured > 0";
}
if(in_array("date-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.date asc";
}
if(in_array("date-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.date desc";
}
if(in_array("website-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.source asc";
}
if(in_array("website-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.source desc";
}	
if(in_array("liked-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.liked asc";
}
if(in_array("liked-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.liked desc";
}
if(in_array("views-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.views asc";
}
if(in_array("views-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.views desc";
}
if(in_array("title-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.title asc";
}
if(in_array("title-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."images.title desc";
}

/* End if */
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."images where pub >  0 $where ");
$images = $db->get_results("select * from ".DB_PREFIX."images where pub > 0 $where $order ".this_limit()."");
//$db->debug();
?>
<div class="row">
<h3>Image management</h3>				
</div>
<?php
if($images) {
$sort=	implode(";",$sortA );
$ps = admin_url('images').'&sort='.$sort.'&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
//$a->show_pages($ps);
if(!empty($sortA)){
echo '<div class="row-fuild" style="margin-bottom:15px"> Active filters:   ';	
foreach ($sortA as $filter){
	echo '<a class="btn btn-default btn-mini" style="margin-right:10px" href="'.remove_sort($filter).'">'.ucwords(str_replace('-',' : ',$filter)).' <i class="icon-remove" style="margin-right:0; margin-left:4px"></i></a>';
}
echo '</div>';	
}
?>
<form class="form-horizontal styled" action="<?php echo admin_url('images');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th>
								  <div class="checkbox-custom checkbox-danger">
<input type="checkbox" name="checkRows" class="check-all" />
<label for="checkRows"></label>
                                 </div>
								  
								  </th>
                                 <th width="19"><button class="btn btn-default btn-sm tipS" type="submit" title="<?php echo _lang("Unpublish all selected"); ?>"><i class="icon-trash"></i></button></th>
                                  
								  <th><?php echo _lang("Image"); ?>
								  <a class="tipS" title="Order by title ascending" href="<?php echo add_sort('title-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by title descending" href="<?php echo add_sort('title-desc');?>"><i class="icon-angle-down"></i></a>
								 
								  </th>
								 <th></th>
								 <th>Rated
								 <a class="tipS" title="Order by likes ascending" href="<?php echo add_sort('liked-asc');?>"><i class="icon-angle-up"></i></a>
								 <a class="tipS" title="Order by likes descending" href="<?php echo add_sort('liked-desc');?>"><i class="icon-angle-down"></i></a>
								 </th>
								<th><i class="icon-cloud-upload"></i>
								 
								 <a class="tipS" title="Order by date ascending" href="<?php echo add_sort('date-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by date descending" href="<?php echo add_sort('date-desc');?>"><i class="icon-angle-down"></i></a>
							</th>
                                 
                                  <th><i class="icon-eye"></i>
								  <a class="tipS" title="Order by views ascending" href="<?php echo add_sort('views-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by views descending" href="<?php echo add_sort('views-desc');?>"><i class="icon-angle-down"></i></a>
								 
								  </th>
                             <th> <a class="tipS" title="Show featured only" href="<?php echo add_sort('featured');?>"><i class="icon-star"></i></a></th>
							 <th> <i class="icon-edit"></i></th>
							  </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($images as $image) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $image->id; ?>" class="styled" /></td>
                                <td class="bord">
								<div class="dropdown">
								<a class="tipS dropdown-toggle" title="Options"  data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button" style="width:25px; color:#888; font-size:25px"><i class="icon-ellipsis-v"></i></a>
<ul class="dropdown-menu dropdown-right bullet" role="menu">
<li role="presentation"><a title="<?php echo _lang("Edit"); ?>" href="<?php echo admin_url('edit-image');?>&vid=<?php echo $image->id;?>"><i class="icon-pencil"></i>  <?php echo _lang("Edit"); ?></a></li>
<li class="divider" role="presentation"></li>
<li role="presentation"><a title="<?php echo _lang("Unpublish"); ?>" href="<?php echo admin_url('images');?>&p=<?php echo this_page();?>&delete-image=<?php echo $image->id;?>"> <i class="icon icon-eraser"></i><?php echo _lang("Unpublish"); ?></a></li>
<li role="presentation"><a target="_blank" title="<?php echo _lang("Unpublish"); ?>" href="<?php echo admin_url('unimages');?>&p=<?php echo this_page();?>&delete-image=<?php echo $image->id;?>"> <i class="icon icon-trash"></i><span style="color:#f96868; font-weight:bold">Permanently</span> Delete</a></li>
<li class="divider" role="presentation"></li>
<li role="presentation"><a class="confirm" target="_blank" title="<?php echo _lang("Ban user"); ?>" href="<?php echo admin_url('users');?>&p=<?php echo this_page();?>&ban=<?php echo $image->user_id;?>"><i class="icon icon-eraser"></i>Ban uploader</a></li>
<li role="presentation"><a class="confirm" target="_blank" title="<?php echo _lang("Delete user"); ?>" href="<?php echo admin_url('users');?>&p=<?php echo this_page();?>&delete-user=<?php echo $image->user_id;?>"> <i class="icon icon-trash"></i> <span style="color:#f96868; font-weight:bold">Delete uploader </span> </a></li>
</ul>
</div>
								  </td>
								  <td width="124" style="width:124px"><img src="<?php echo thumb_fix($image->thumb); ?>" style="width:100px; height:60px;"></td>
                                  <td><a target="_blank" href="<?php echo image_url($image->id, $image->title);?>"><strong><?php echo _html($image->title); ?></strong></a>
								  </td>
								  <td>
								  <i class="icon-thumbs-up" style="margin-right:5px;"></i><?php echo intval($image->liked); ?>  <span style="display:inline-block;width:12px;"></span> <i class="icon-thumbs-down" style="margin-right:5px;"></i><?php echo intval($image->disliked); ?> <span style="display:inline-block;width:12px;"></span>
								  </td>
															  
                                  <td><?php echo time_ago($image->date); ?></td>
                                  <td><?php echo _html($image->views); ?></td>
								 
								  <td>
								  <?php if($image->featured < 1) { ?>
								  <p><a  class="tipS" title="<?php echo _lang("Not featured. Click to feature image"); ?>" href="<?php echo canonical(); ?>&feature-image=<?php echo $image->id;?>"><i class="icon-star"></i></a></p>
								 <?php } else { ?>
								  <p><a class="tipS" title="<?php echo _lang("Featured image! Click to remove"); ?>" href="<?php echo canonical(); ?>&unfeature-image=<?php echo $image->id;?>"><i class="icon-star greenSeaText"></i></a></p>
								 <?php } ?>
								 
								  </td>
								  <td>
								<a class="tipS" title="<?php echo _lang("Edit"); ?>" href="<?php echo admin_url('edit-image');?>&vid=<?php echo $image->id;?>"><i class="icon-pencil"></i></a>

								  </td>
                              </tr>
							  <?php } ?>
						</tbody>  
</table>
</div>						
</fieldset>					
</form>
<?php  $a->show_pages($ps); 
}else {
echo '<div class="msg-note">Nothing here yet.</div>';
}

 ?>
