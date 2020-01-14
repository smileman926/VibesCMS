<?php if(isset($_GET['ac']) && $_GET['ac'] ="remove-logo"){
update_option('player-logo', '');
 $db->clean_cache();
}
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
if(($key !== "player-logo") && !empty($key)) {
	if($key == "vjimaads") {
	update_option($key, rawurlencode($value));	
	} else {
  update_option($key, $value);
	}
}
}
  echo '<div class="msg-info">Configuration options have been updated.</div>';
  

//Set logo
if(isset($_FILES['player-logo']) && !empty($_FILES['player-logo']['name'])){
$nn = explode('.', $_FILES['player-logo']['name']);	
$extension = end($nn);
$thumb = ABSPATH.'/storage/uploads/'.nice_url($_FILES['player-logo']['name']).uniqid().'.'.$extension;
if (move_uploaded_file($_FILES['player-logo']['tmp_name'], $thumb)) {
     $sthumb = str_replace(ABSPATH.'/' ,'',$thumb);
    update_option('player-logo', $sthumb);
	 
	} else {
	echo '<div class="msg-warning">Logo upload failed.</div>';
	}
	
}
$db->clean_cache();
}

$all_options = get_all_options();
?>

<div class="row">
<h3>Players</h3>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('players');?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 
<label class="control-label"><i class="icon-picture"></i>Player logo</label>	
<div class="form-group form-material form-material-file">
<input type="text" class="form-control empty" readonly="" />
<input type="file" id="player-logo" name="player-logo" class="styled" />
<label class="floating-label">Choose logo</label>
<div class="controls">

<span class="help-block" id="limit-text">The logo on the player.</span>
<?php if(get_option('player-logo')) { ?><p><img src="<?php echo thumb_fix(get_option('player-logo')); ?>"/> <br /> <a href="<?php echo admin_url('players');?>&ac=remove-logo">Remove</a></p><?php } ?>
</div>	
</div>
	
	<div class="form-group form-material">
	<label class="control-label"><i class="icon-cloud-upload"></i>Video path</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="hide-mp4" class="styled" value="0" <?php if(get_option('hide-mp4',0) == 0 ) { echo "checked"; } ?>>Real link</label>
	<label class="radio inline"><input type="radio" name="hide-mp4" class="styled" value="1" <?php if(get_option('hide-mp4',0) == 1 ) { echo "checked"; } ?>>Hide with PHP</label>
	<span class="help-block" id="limit-text">Note: Hiding it with PHP may create issues on some players. Please test which mode you prefer.</span>
	</div>
	</div>
	<div class="form-group form-material">
	<label class="control-label"><i class="icon-play"></i>Default Player <br /> <i>HTML5</i></label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="choosen-player" class="styled" value="6" <?php if(get_option('choosen-player') == 6 ) { echo "checked"; } ?>>VideoJS</label>
	<label class="radio inline"><input type="radio" name="choosen-player" class="styled" value="1" <?php if(get_option('choosen-player') == 1 ) { echo "checked"; } ?>>JwPlayer</i></label>
	<label class="radio inline"><input type="radio" name="choosen-player" class="styled" value="2" <?php if(get_option('choosen-player') == 2 ) { echo "checked"; } ?>>FlowPlayer</label>
	<label class="radio inline"><input type="radio" name="choosen-player" class="styled" value="3" <?php if(get_option('choosen-player') == 3 ) { echo "checked"; } ?>>jPlayer</label>
	<span class="help-block" id="limit-text">Which player should be loaded for mobile supported files (.mp4, .mp3, etc)? JwPlayer is loaded for the rest.</span>
	</div>
	</div>
	<div class="form-group form-material">
	<label class="control-label"><i class="icon-link"></i>Remote Player <br /> <i>(Linked Videos)</i> </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="remote-player" class="styled" value="6" <?php if(get_option('remote-player') == 6 ) { echo "checked"; } ?>>Vibe Player</label>
	<label class="radio inline"><input type="radio" name="remote-player" class="styled" value="1" <?php if(get_option('remote-player') == 1 ) { echo "checked"; } ?>>JwPlayer</i></label>
	<label class="radio inline"><input type="radio" name="remote-player" class="styled" value="2" <?php if(get_option('remote-player') == 2 ) { echo "checked"; } ?>>FlowPlayer</label>
	<label class="radio inline"><input type="radio" name="remote-player" class="styled" value="3" <?php if(get_option('remote-player') == 3 ) { echo "checked"; } ?>>jPlayer</label>
	<span class="help-block" id="limit-text">Which player should be loaded for mobile supported files (.mp4, .mp3, etc)? JwPlayer is loaded for the rest.</span>
	</div>
	</div>
	<div class="form-group form-material">
	<label class="control-label"><i class="icon-youtube"></i>Youtube videos</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="youtube-player" class="styled" value="2" <?php if(get_option('youtube-player') == 2 ) { echo "checked"; } ?>>Use JwPlayer</label>
	<label class="radio inline"><input type="radio" name="youtube-player" class="styled" value="0" <?php if(get_option('youtube-player') == 0 ) { echo "checked"; } ?>>Youtube's Player</label>
	<label class="radio inline"><input type="radio" name="youtube-player" class="styled" value="3" <?php if(get_option('youtube-player') == 3 ) { echo "checked"; } ?>>Vibe Player</label>

	<span class="help-block" id="limit-text">Which player do you wish to handle Youtube?</span>
	</div>
	</div>
	<div class="form-group form-material">
<label class="control-label"><i class="icon-fast-forward"></i>Video page settings</label>
 <div class="controls">
<div class="row">
<div class="col-md-3">
<input type="text" name="video-width" class="col-md-12" value="<?php echo get_option('video-width'); ?>"><span class="help-block">Default video <strong>width</strong> </span>
</div>
<div class="col-md-3">
<input type="text" name="video-height" class="col-md-12" value="<?php echo get_option('video-height'); ?>"><span class="help-block align-center">Default video <strong>height</strong></span>
</div>
<div class="col-md-3">
<input type="text" name="related-nr" class="col-md-12" value="<?php echo get_option('related-nr'); ?>"><span class="help-block align-center">Number of <strong> related videos</strong></span>
</div>
<div class="col-md-3">
<input type="text" name="jwkey" class="col-md-12" value="<?php echo get_option('jwkey'); ?>"><span class="help-block align-right"><strong>JwPlayer key</strong> <a href="https://dashboard.jwplayer.com/" target="_blank"> Free signup</a></span>
</div>
</div>
</div>
</div>
<div class="form-group form-material">
<label class="control-label"><i class="icon-fast-forward"></i>VAST/VPAID Ad tag url</label>
 <div class="controls">
<div class="row">
<div class="col-md-12">
<input type="text" name="vjimaads" class="col-md-12" value="<?php echo get_option('vjimaads'); ?>">
<span class="help-block">Load VAST/VPAID ads to player. <strong>For now: VideoJS supports this by default.</strong>
<br> JwPlayer sells the <a href="https://www.jwplayer.com/video-solutions/video-advertising/" target="_blank"> JwPlayer ads edition</a>
and Flowplayer has a <a href="https://flowplayer.com/docs/vast.html" target="_blank">commercial (subscription based) plugin</a>.
</span>
</div>

</div>
</div>
</div>
	<div class="form-group form-material">
<label class="control-label"><i class="icon-fast-forward"></i>Default music cover</label>
 <div class="controls">
<div class="row">
<div class="col-md-12">
<input type="text" name="musicthumb" class="col-md-12" value="<?php echo get_option('musicthumb','http://37.media.tumblr.com/c87921eefd315482e66706d51a05054e/tumblr_n71ifjJAwU1tchrkco1_500.gif'); ?>">
<span class="help-block">Default <strong>cover for player</strong> when the music creative was not uploaded. </span>
</div>

</div>
</div>
</div>
		<div class="form-group form-material">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update settings"); ?></button>	
</div>	
</fieldset>						
</form>
</div>
