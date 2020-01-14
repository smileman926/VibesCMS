<?php
if(isset($_POST['play-name'])) {
$picture ='';
$formInputName   = 'play-img';							# This is the name given to the form's file input
	$savePath	     = ABSPATH.'/uploads';								# The folder to save the image
	$saveName        = md5(time()).'-'.user_id();									# Without ext
	$allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$picture  = str_replace(ABSPATH.'/' ,'',$thumb);
} else { $picture = '';}
$db->query("INSERT INTO ".DB_PREFIX."pages (`date`, `menu`, `pic`, `title`, `content`, `tags`)
 VALUES (now(),'".intval($_POST['menu'])."', '".$picture."', '".$db->escape($_POST['play-name'])."', '".$db->escape(htmlentities($_POST['content']))."', '".$db->escape($_POST['tags'])."')");
echo '<div class="msg-info">Page '.$_POST['play-name'].' created</div>';
}

?>
<div class="row-fluid">
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('create-page');?>" enctype="multipart/form-data" method="post">
<fieldset>
<div class="control-group">
<label class="control-label"><i class="icon-text-height"></i>Page title</label>
<div class="controls">
<input type="text" name="play-name" class="validate[required] span12"/> 						
</div>	
</div>	

<div class="control-group">
	<label class="control-label"><i class="icon-check"></i>Show in menu?</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="menu" class="styled" value="1">Yes</label>
	<label class="radio inline"><input type="radio" name="menu" class="styled" value="0" checked>No</label>
	<span class="help-block" id="limit-text">Should this be visible in menus?</span>
	</div>
	</div>	
<div class="control-group">
<label class="control-label">Page content</label>
<div class="controls">
<textarea rows="5" cols="5" name="content" class="ckeditor span12" style="word-wrap: break-word; resize: horizontal; height: 88px;"></textarea>					
</div>	
</div>
<div class="control-group">
<label class="control-label">Image?</label>
<div class="controls">
<input type="file" id="play-img" name="play-img" class="styled" />
</div>	
</div>
<div class="control-group">
	<label class="control-label">Tags</label>
	<div class="controls">
	<input type="text" id="tags" name="tags" class="tags span12" value="">
	<span class="help-block" id="limit-text">Press enter after each tag</span>
	</div>
	</div>
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit">Create page</button>	
</div>	
</fieldset>						
</form>
</div>