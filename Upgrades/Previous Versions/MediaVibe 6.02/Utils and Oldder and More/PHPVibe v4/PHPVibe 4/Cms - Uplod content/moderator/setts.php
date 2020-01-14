<?php if(isset($_GET['ac']) && $_GET['ac'] ="remove-logo"){
update_option('site-logo', '');
 $db->clean_cache();
}
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
if($key !== "site-logo") {
  update_option($key, $value);
}
}
  echo '<div class="msg-info">Configuration options have been updated.</div>';

//Set logo
if(isset($_FILES['site-logo']) && !empty($_FILES['site-logo']['name'])){
$extension = end(explode('.', $_FILES['site-logo']['name']));
$thumb = ABSPATH.'/uploads/'.nice_url($_FILES['site-logo']['name']).uniqid().'.'.$extension;
if (move_uploaded_file($_FILES['site-logo']['tmp_name'], $thumb)) {
     $sthumb = str_replace(ABSPATH.'/' ,'',$thumb);
    update_option('site-logo', $sthumb);
	  //$db->clean_cache();
	} else {
	echo '<div class="msg-warning">Logo upload failed.</div>';
	}
	
}
  $db->clean_cache();
}
$all_options = get_all_options();
?>

<div class="row-fluid">
<h3>Configuration</h3>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('setts');?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 	
<div class="control-group">
<label class="control-label"><i class="icon-pencil"></i>Website Name</label>
<div class="controls">
<input type="text" name="site-logo-text" class="span12" value="<?php echo get_option('site-logo-text'); ?>" /> 						
<span class="help-block" id="limit-text">Global site name. Also see <a href="<?php echo admin_url('seo'); ?>">SEO Setts</a> for titles.</span>
</div>	
</div>
	
<div class="control-group">
<label class="control-label"><i class="icon-picture"></i>Website Logo</label>
<div class="controls">
<input type="file" id="site-logo" name="site-logo" class="styled" />
<span class="help-block" id="limit-text">If used, replaces the default text logo with this image.</span>
<?php if(get_option('site-logo')) { ?><p><img src="<?php echo thumb_fix(get_option('site-logo')); ?>"/> <br /> <a href="<?php echo admin_url('setts');?>&ac=remove-logo">Remove</a></p><?php } ?>
</div>	
</div>

<div class="control-group">
<label class="control-label"><i class="icon-th-list"></i>Site Theme</label>
<div class="controls">
<select placeholder="Select theme:" name="theme" class="select ">
<?php $directories = glob(ABSPATH.'/tpl' . '/*' , GLOB_ONLYDIR);
foreach($directories as $dir){
$dir = explode('/',$dir);
$dir= end($dir);	
$checkd =(get_option('theme','main') == $dir)? 'selected' : '';	
echo '<option value="'.$dir.'" '.$checkd.'>'.$dir.'</option>';	
}

?>
</select>
</div>	
</div>	

<div class="control-group">
<label class="control-label"><i class="icon-play-circle"></i>Global results per page</label>
<div class="controls">
<input type="text" name="bpp" class=" span4" value="<?php echo get_option('bpp'); ?>" /> 
<span class="help-block" id="limit-text">Global number of elements (~videos) per page.</span>						
</div>	
</div>
<div class="control-group">
<label class="control-label"><i class="icon-picture"></i>Video thumbs resizing <br/> Note: timthumb setting.</label>
 <div class="controls">
<div class="row-fluid">
<div class="span3">
<input type="text" name="thumb-width" class="span12" value="<?php echo get_option('thumb-width'); ?>"><span class="help-block">Default image <strong>resized width</strong> </span>
</div>
<div class="span3">
<input type="text" name="thumb-height" class="span12" value="<?php echo get_option('thumb-height'); ?>"><span class="help-block align-center">Default image <strong> resized height</strong></span>
</div>
</div>
</div>
</div>
	<div class="control-group">
	<label class="control-label"><i class="icon-comments"></i>Related Videos Algorithm</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="RelatedSource" class="styled" value="1" <?php if(get_option('RelatedSource','0') == 1 ) { echo "checked"; } ?>>by Category</label>
	<label class="radio inline"><input type="radio" name="RelatedSource" class="styled" value="0" <?php if(get_option('RelatedSource','0') <> 1 ) { echo "checked"; } ?>>by Title</label>
	<span class="help-block" id="limit-text">Choose what media shows as "Related".</span>						

	</div>
	</div>

	
	<div class="control-group">
	<label class="control-label"><i class="icon-comments"></i>Comments</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="video-coms" class="styled" value="1" <?php if(get_option('video-coms') == 1 ) { echo "checked"; } ?>>Facebook</label>
	<label class="radio inline"><input type="radio" name="video-coms" class="styled" value="0" <?php if(get_option('video-coms') <> 1 ) { echo "checked"; } ?>>PHPVibe</label>
	<span class="help-block" id="limit-text">What comment system to use.</span>						

	</div>
	</div>

	
	<div class="control-group">
<label class="control-label"><i class="icon-font"></i>Copyright</label>
<div class="controls">
<input type="text" name="site-copyright" class=" span12" value="<?php echo get_option('site-copyright'); ?>" /> 
<span class="help-block" id="limit-text">Ex: &copy; 2013 <?php echo ucfirst(ltrim(cookiedomain(),".")); ?></span>						
</div>	
</div>	
	<div class="control-group">
<label class="control-label"><i class="icon-hand-down"></i>Custom licensing</label>
<div class="controls">
<input type="text" name="licto" class=" span12" value="<?php echo get_option('licto'); ?>" /> 
<span class="help-block" id="limit-text">Ex: Licensed to <?php echo ucfirst(ltrim(cookiedomain(),".")); ?></span>						
</div>	
</div>
<div class="control-group">
	<label class="control-label"><i class="icon-bar-chart"></i>Tracking code</label>
	<div class="controls">
	<textarea id="googleanalitycs" name="googleanalitycs" class="auto span12"><?php echo get_option('googleanalitycs'); ?></textarea>
	<span class="help-block" id="limit-text">Paste your full tracking code. For example Google Analytics</span>
	</div>
	</div>
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update settings"); ?></button>	
</div>	
</fieldset>						
</form>
</div>