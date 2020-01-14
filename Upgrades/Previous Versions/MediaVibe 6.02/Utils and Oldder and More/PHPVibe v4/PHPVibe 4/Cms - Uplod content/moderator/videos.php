<?php function add_sort($sorter){
	global $ps;
	if($sorter == "featured") {		
	return str_replace('&sort=','&sort='.$sorter.';',$ps);
	}
	return admin_url('videos').'&sort='.$sorter.'&p=1';
}
function remove_sort($sorter){
	global $ps;
	return str_replace($sorter.'','',$ps);
}
function get_domain($url)
{
	if (strpos($url,'localfile') !== false) {
	return '<i class="icon-cloud-upload"></i>';	
	}
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return str_replace('.com','',$regs['domain']);
  }
  return false;
}

if(isset($_GET['delete-video'])) {
unpublish_video(intval($_GET['delete-video']));
} 
if(isset($_GET['feature-video'])) {
$id = intval($_GET['feature-video']);
if($id){
$db->query("UPDATE ".DB_PREFIX."videos set featured = '1' where id='".intval($id)."'");
echo '<div class="msg-info">Video #'.$id.' was featured.</div>';
}
} 
if(isset($_GET['unfeature-video'])) {
$id = intval($_GET['unfeature-video']);
if($id){
$db->query("UPDATE ".DB_PREFIX."videos set featured = '0' where id='".intval($id)."'");
echo '<div class="msg-info">Video #'.$id.' was unfeatured.</div>';
}
} 
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
unpublish_video(intval($del));
}
echo '<div class="msg-info">Videos #'.implode(',', $_POST['checkRow']).' unpublished.</div>';
}
$order = "ORDER BY ".DB_PREFIX."videos.id desc";
$where = "";
$sortA = array();
if(isset($_GET['sort']))  {
$sortA = explode(";",$_GET['sort'] );
$sortA = array_unique(array_filter($sortA));	
if(in_array("featured", $sortA )) {
$where = "and featured > 0";
}
if(in_array("date-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.date asc";
}
if(in_array("date-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.date desc";
}
if(in_array("website-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.source asc";
}
if(in_array("website-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.source desc";
}	
if(in_array("liked-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.liked asc";
}
if(in_array("liked-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.liked desc";
}
if(in_array("views-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.views asc";
}
if(in_array("views-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.views desc";
}
if(in_array("title-asc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.title asc";
}
if(in_array("title-desc", $sortA )) {
$order = "ORDER BY ".DB_PREFIX."videos.title desc";
}

/* End if */
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where pub >  0 and media < 2 $where ");
$videos = $db->get_results("select * from ".DB_PREFIX."videos where pub > 0 and media < 2 $where $order ".this_limit()."");
//$db->debug();
?>
<div class="row-fluid">
		    	<form class="search widget" action="" method="get" onsubmit="location.href='<?php echo admin_url('search-videos'); ?>&key=' + encodeURIComponent(this.key.value); return false;">
		    		<div class="autocomplete-append">			   
			    		<input type="text" name="key" placeholder="Search media..." id="key" />
			    		<input type="submit" class="btn btn-info" value="Search" />
			    	</div>
		    	</form>
<h3>Video management</h3>				
</div>
<?php
if($videos) {
$sort=	implode(";",$sortA );
$ps = admin_url('videos').'&sort='.$sort.'&p=';
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
<form class="form-horizontal styled" action="<?php echo admin_url('videos');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">

<div class="cleafix full"></div>
<fieldset>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" name="checkRows" class="styled check-all" /></th>
                                 <th width="19"><button class="btn btn-default btn-mini tipS" type="submit" title="<?php echo _lang("Unpublish all selected"); ?>"><i class="icon-trash"></i></button></th>
                                  
								  <th><?php echo _lang("Video"); ?>
								  <a class="tipS" title="Order by title ascending" href="<?php echo add_sort('title-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by title descending" href="<?php echo add_sort('title-desc');?>"><i class="icon-angle-down"></i></a>
								 
								  </th>
								 <th></th>
								 <th>Rated
								 <a class="tipS" title="Order by likes ascending" href="<?php echo add_sort('liked-asc');?>"><i class="icon-angle-up"></i></a>
								 <a class="tipS" title="Order by likes descending" href="<?php echo add_sort('liked-desc');?>"><i class="icon-angle-down"></i></a>
								 </th>
								  <th><i class="icon-film"></i>
								  <a class="tipS" title="Order by website ascending" href="<?php echo add_sort('website-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by website descending" href="<?php echo add_sort('website-desc');?>"><i class="icon-angle-down"></i></a>
								 
								  </th>
								 <th><i class="icon-clock-o"></i>
								 <a class="tipS" title="Order by duration ascending" href="<?php echo add_sort('duration-asc');?>"><i class="icon-angle-up"></i></a>
<a class="tipS" title="Order by duration descending" href="<?php echo add_sort('duration-desc');?>"><i class="icon-angle-down"></i></a>
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
						  <?php foreach ($videos as $video) { ?>
                              <tr>
                                  <td><input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" class="styled" /></td>
                                   <td class="bord">
								<a class="tipS" title="<?php echo _lang("Unpublish"); ?>" href="<?php echo admin_url('videos');?>&p=<?php echo this_page();?>&delete-video=<?php echo $video->id;?>"><i class="icon-trash"></i></a>
								  </td>
								  <td width="124" style="width:124px"><img src="<?php echo thumb_fix($video->thumb); ?>" style="width:100px; height:60px;"></td>
                                  <td><a target="_blank" href="<?php echo video_url($video->id, $video->title);?>"><strong><?php echo _html($video->title); ?></strong></a>
								  </td>
								  <td>
								  <i class="icon-thumbs-up" style="margin-right:5px;"></i><?php echo intval($video->liked); ?>  <span style="display:inline-block;width:12px;"></span> <i class="icon-thumbs-down" style="margin-right:5px;"></i><?php echo intval($video->disliked); ?> <span style="display:inline-block;width:12px;"></span>
								  </td>
								  <td>
								  <em style="font-size:11px;"><?php echo ucfirst(get_domain($video->source)); ?></em></p>
								  </td>
								  
                                  <td><?php echo video_time($video->duration); ?></td>
                                  <td><?php echo time_ago($video->date); ?></td>
                                  <td><?php echo _html($video->views); ?></td>
								 
								  <td>
								  <?php if($video->featured < 1) { ?>
								  <p><a  class="tipS" title="<?php echo _lang("Not featured. Click to feature video"); ?>" href="<?php echo canonical(); ?>&feature-video=<?php echo $video->id;?>"><i class="icon-star"></i></a></p>
								 <?php } else { ?>
								  <p><a class="tipS" title="<?php echo _lang("Featured video! Click to remove"); ?>" href="<?php echo canonical(); ?>&unfeature-video=<?php echo $video->id;?>"><i class="icon-star greenSeaText"></i></a></p>
								 <?php } ?>
								 
								  </td>
								  <td>
								<a class="tipS" title="<?php echo _lang("Edit"); ?>" href="<?php echo admin_url('edit-video');?>&vid=<?php echo $video->id;?>"><i class="icon-pencil"></i></a>

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