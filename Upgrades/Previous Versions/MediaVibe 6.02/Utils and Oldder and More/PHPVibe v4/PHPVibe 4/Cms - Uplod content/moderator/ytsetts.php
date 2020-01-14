<?php if(isset($_GET['ac']) && $_GET['ac'] ="remove-logo"){
update_option('site-logo', '');
 $db->clean_cache();
}
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
  update_option($key, $value);
}
  echo '<div class="msg-info">Settings updated.</div>';

  $db->clean_cache();
}
$all_options = get_all_options();
?>

<div class="row-fluid">
<h3>Youtube API Settings</h3>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('ytsetts');?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 	
<div class="control-group">
<label class="control-label"><i class="icon-pencil"></i>Youtube key</label>
<div class="controls">
<input type="text" name="youtubekey" class="span12" value="<?php echo get_option('youtubekey'); ?>" /> 						
<span class="help-block" id="limit-text">Your Youtube API key.<br> See <a href="https://developers.google.com/youtube/registering_an_application" target="_blank">Google : Register your application</a>. <br><a href="https://www.youtube.com/watch?v=jdqsiFw74Jk&feature=youtu.be&t=11m40s" target="_blank"> Video</a></span>
</div>	
</div>
	
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update settings"); ?></button>	
</div>	
</fieldset>						
</form>
</div>