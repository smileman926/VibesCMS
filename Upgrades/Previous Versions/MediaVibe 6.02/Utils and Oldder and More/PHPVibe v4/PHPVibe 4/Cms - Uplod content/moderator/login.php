<?php
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
update_option($key, toDb($value));
}
  echo '<div class="msg-info">Login options have been updated.</div>';
  $db->clean_cache();
}
$all_options = get_all_options();
?>

<div class="row-fluid">
<h3>Login Settings</h3>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('login');?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 

	<div class="control-group">
	<label class="control-label"><i class="icon-facebook-sign"></i>Allow Facebook logins/registrations </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="allowfb" class="styled" value="1" <?php if(get_option('allowfb') == 1 ) { echo "checked"; } ?>>Yes</label>
	<label class="radio inline"><input type="radio" name="allowfb" class="styled" value="0" <?php if(get_option('allowfb') == 0 ) { echo "checked"; } ?>>No</label>
	<span class="help-block" id="limit-text">Allow Facebook users to login? Note: It will not work without a valid Key and Secret from your <a href="https://developers.facebook.com/apps" target="_blank">Facebook App</a> </span>
	</div>
	</div>
	<div class="control-group">
<label class="control-label"><i class="icon-key"></i>Facebook login settings</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="Fb_Key" class="span12" value="<?php echo get_option('Fb_Key'); ?>"><span class="help-block">Facebook app <strong>App ID/API Key</strong> </span>
</div>
<div class="span6">
<input type="text" name="Fb_Secret" class="span12" value="<?php echo get_option('Fb_Secret'); ?>"><span class="help-block align-center"> <strong>App Secret</strong></span>
</div>
</div>
</div>
</div>
	<div class="control-group">
	<label class="control-label"><i class="icon-twitter"></i>Allow Twitter logins/registrations </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="allowtw" class="styled" value="1" <?php if(get_option('allowtw') == 1 ) { echo "checked"; } ?>>Yes</label>
	<label class="radio inline"><input type="radio" name="allowtw" class="styled" value="0" <?php if(get_option('allowtw') == 0 ) { echo "checked"; } ?>>No</label>
	<span class="help-block" id="limit-text">Allow Twitter users to login? Note: It will not work without a valid Consumer key and Secret from your app at <a href="https://dev.twitter.com/apps" target="_blank">dev.twitter.com</a> </span>
	</div>
	</div>
	<div class="control-group">
<label class="control-label"><i class="icon-key"></i>Twitter login settings</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="Tw_Key" class="span12" value="<?php echo get_option('Tw_Key'); ?>"><span class="help-block">Twitter <strong>API key</strong> </span>
</div>
<div class="span6">
<input type="text" name="Tw_Secret" class="span12" value="<?php echo get_option('Tw_Secret'); ?>"><span class="help-block align-center"> <strong>API secret</strong></span>
</div>
</div>
</div>

</div>
<div class="control-group">
	<label class="control-label"><i class="icon-google-plus-sign"></i>Allow Google logins/registrations </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="allowg" class="styled" value="1" <?php if(get_option('allowg') == 1 ) { echo "checked"; } ?>>Yes</label>
	<label class="radio inline"><input type="radio" name="allowg" class="styled" value="0" <?php if(get_option('allowg') == 0 ) { echo "checked"; } ?>>No</label>
	<span class="help-block" id="limit-text">Allow Google users to login? No key/app required </span>
	</div>
	</div>
<div class="control-group">
<label class="control-label"><i class="icon-key"></i>Google login settings</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="GClientID" class="span12" value="<?php echo get_option('GClientID'); ?>"><span class="help-block align-center"> <strong>Client ID</strong></span>
</div>
<div class="span6">
<input type="text" name="GClientSecret" class="span12" value="<?php echo get_option('GClientSecret'); ?>"><span class="help-block align-center"> <strong>Client Secret</strong></span>
</div>
	<span class="help-block" id="limit-text">Get your developer keys from <a href="https://console.developers.google.com/project" target="_blank">Google</a>. <a href="https://developers.google.com/api-client-library/php/guide/aaa_oauth2_web" target="_blank">More info</a> </span>

</div>
</div>
</div>
	
		<div class="control-group">
	<label class="control-label"><i class="icon-pencil"></i>Allow local <br />(mail & password) registrations </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="allowlocalreg" class="styled" value="1" <?php if(get_option('allowlocalreg') == 1 ) { echo "checked"; } ?>>Yes</label>
	<label class="radio inline"><input type="radio" name="allowlocalreg" class="styled" value="0" <?php if(get_option('allowlocalreg') == 0 ) { echo "checked"; } ?>>No</label>
	</div>
	</div>
		<div class="control-group">
<label class="control-label"><i class="icon-exclamation-sign"></i>Cookie security data</label>
 <div class="controls">
<div class="row-fluid">
<div class="span4">
<input type="text" name="COOKIEKEY" class="span12" value="<?php echo get_option('COOKIEKEY'); ?>"><span class="help-block">Custom  <strong>cookie key</strong> </span>
</div>
<div class="span4">
<input type="text" name="SECRETSALT" class="span12" value="<?php echo get_option('SECRETSALT'); ?>"><span class="help-block align-center"> Secret encryption <strong>salt</strong> (6-8 char)</span>
</div>
<div class="span4">
<input type="text" name="COOKIESPLIT" class="span12" value="<?php echo get_option('COOKIESPLIT'); ?>"><span class="help-block align-center">Cookie <strong>split</strong></span>
</div>
</div>
</div>
</div>
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update settings"); ?></button>	
</div>	
</fieldset>						
</form>
</div>