<?php
 if(isset($_GET['delete'])){ 
    $db->query("DELETE from ".DB_PREFIX."homepage WHERE id = '".intval($_GET['delete'])."'");
	echo '<div class="msg-info">You deleted the home box with id : '.$_GET['delete'].'</div>';
	 }
if(isset($_POST['queries'])){ 
$insertvideo = $db->query("INSERT INTO ".DB_PREFIX."homepage (`title`, `type`, `ident`, `querystring`, `total`, `ord`, `mtype`,`car` ) VALUES ('".toDb($_POST['title'])."', '2', '".toDb($_POST['ident'])."', '".toDb($_POST['queries'])."', '".toDb($_POST['number'])."', '1', '".toDb($_POST['type'])."', '".toDb($_POST['car'])."')");		
}

?>
<div class="row-fluid">

<div class="box-element span6">
					<div class="box-head-light"><i class="icon-plus"></i><h3>Create a block</h3></div>
					<div class="box-content">
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('homepage'); ?>" enctype="multipart/form-data" method="post">
		
	<div class="control-group">
	<label class="control-label">Block title</label>
	<div class="controls">
	<input type="text" id="title" name="title" class="span12" value="">
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label">Videos limit</label>
	<div class="controls">
	<input type="text" id="number" name="number" class="span4 validate[required]" value="24">
	<span class="help-block" id="limit-text">Number of videos per block. If you have 1 block, it will be the number of videos to load per scroll.</span>
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label">Video query:</label>
	<div class="controls">
	<select data-placeholder="Select type" name="queries" id="queris" class="select validate[required]" tabindex="2">
	<option value="most_viewed">Most viewed </option>
<option value="top_rated">Most liked</option>
<option value="viral" selected>Recent</option>
<option value="featured">Featured</option>
<option value="random">Random </option>

	</select>

	</div>
	</div>	
	<div class="control-group">
	<label class="control-label"><i class="icon-sort"></i>Media</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="type" class="styled trigger" value="0">All</label>
	<label class="radio inline"><input type="radio" name="type" data-rel="1" class="styled trigger" value="1" checked>Video</label>
	<label class="radio inline"><input type="radio" name="type" data-rel="2" class="styled trigger" value="2">Music</label>
	<label class="radio inline"><input type="radio" name="type" data-rel="3" class="styled trigger" value="3">Images</label>
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label"><i class="icon-sort"></i>Carousel</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="car" class="styled" value="1">Yes</label>
	<label class="radio inline"><input type="radio" name="car" class="styled" value="0" checked>No</label>

	</div>
	</div>	
<?php echo '
<div class="control-group">
	<label class="control-label">'._lang("Category:").'</label>
	<div class="controls">
	<select name="ident" id="ident" class="select">
	';
$categories = $db->get_results("SELECT cat_id as id, type, cat_name as name FROM  ".DB_PREFIX."channels order by type,cat_name asc limit 0,10000");
if($categories) {
$tt =array(""=>"[V]" ,"1" =>"[V] ","2" =>"[M]", "3"=>"[I]");	
foreach ($categories as $cat) {	
echo '<option value="'.intval($cat->id).'">'.$tt[$cat->type].' '._html($cat->name).'</option>';
}
}  echo '<option value="" selected>-- None --</option>'; 
echo '	  
	  </select>
	  	<span class="help-block" id="limit-text"> Optional: Restrict video in block to a category.</span>
	  </div>             
	  </div>
';
echo '
 <div class="box-bottom clearfix"> <button class="btn btn-primary btn-mini pull-right">Add block</button>  </div>
</form>
</div>
</div>
<div class="box-element span6">	
<div class="box-head-light"><i class="icon-list-ol"></i><h3>Blocks</h3></div>
<div class="box-content">	
 <div id="easyhome">
<ul id="sortable" class="droptrue">
';
$boxes_sql = $db->get_results("SELECT * FROM ".DB_PREFIX."homepage order by `ord` ASC limit 0,1000000");
if($boxes_sql) {
foreach($boxes_sql as $box){ 
echo '
<li id="recordsArray_'.$box->id.'" class="sortable clearfix">
<div class="ns-row pull-left"><div class="ns-title"><i class="icon-sort" style="margin-right:8px;"></i>'._html($box->title).'</div>
<a style="padding:0 20px;" href="'.admin_url('edit-block').'&id='.$box->id.'" class="tipS delete-menu pull-right" title="Edit" ><i class="icon-pencil"></i></a>
<a href="'.admin_url('homepage').'&delete='.$box->id.'" class="tipS delete-menu pull-right" title="Delete"><i class="icon-trash"></i></a></div>
 
 </li>';
 }
}  

echo '
 </ul>
</div>	
<div id="respo" style="display:none;"></div>	
				</div>	
</div>';				
 ?>