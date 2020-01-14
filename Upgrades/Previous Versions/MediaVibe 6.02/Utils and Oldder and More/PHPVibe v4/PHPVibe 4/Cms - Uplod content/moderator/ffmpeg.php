<?php
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
update_option($key, $value);
}
  echo '<div class="msg-info">FFMPEG options have been updated.</div>';
  $db->clean_cache();
}
$all_options = get_all_options();
?>
<div class="row-fluid">
<h3>FFMPEG Settings</h3>
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('ffmpeg');?>" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 
<div class="control-group">
	<label class="control-label"><i class="icon-wrench"></i>Server bin path</label>
	<div class="controls">
	<input type="text" name="binpath" class=" span6" value="<?php echo get_option('binpath'); ?>" /> 
	<span class="help-block" id="limit-text">PHP Bin path for ffmpeg (conversion tasks). Note: Also make sure videocron.php has execute permissions (chmod : 0555)</span>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label"><i class="icon-check"></i>Enable ffmpeg conversion</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="ffa" class="styled" value="1" <?php if(get_option('ffa') == 1 ) { echo "checked"; } ?>>Yes</label>
	<label class="radio inline"><input type="radio" name="ffa" class="styled" value="0" <?php if(get_option('ffa') == 0 ) { echo "checked"; } ?>>No</label>
	<span class="help-block" id="limit-text">Please make sure you have FFMPEG installed on server before enabling this</span>
	</div>
	</div>
	<div class="control-group">
<label class="control-label"><i class="icon-key"></i>FFmpeg size output</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="ffmpeg-vsize" class="span12" value="<?php echo get_option('ffmpeg-vsize','640x360'); ?>"><span class="help-block align-center"> Replace sizes (if forced) for converted videos: <strong>width and height, ex: 630x320</strong></span>
</div>

</div>
</div>
</div>
<div class="control-group">
<label class="control-label"><i class="icon-picture"></i>FFmpeg thumb extraction</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="ffmpeg-thumb-time" class="span12" value="<?php echo get_option('ffmpeg-thumb-time','00:00:03'); ?>">
<span class="help-block align-center"> At which time to extract the thumbnail from the video?</span>
</div>

</div>
</div>
</div>	
<div class="control-group">
<label class="control-label"><i class="icon-magic"></i>FFmpeg executable</label>
 <div class="controls">
<div class="row-fluid">
<div class="span6">
<input type="text" name="ffmpeg-cmd" class="span12" value="<?php echo get_option('ffmpeg-cmd','ffmpeg'); ?>"><span class="help-block">FFMPEG comand to run, ex: ffmpeg, usr/bin/ffmpeg. Make sure it works. </span>
</div>
</div>
</div>
</div>
<?php $fftheme ="{ffmpeg-cmd} -i {input} -vcodec libx264 -s {ffmpeg-vsize} -threads 4 -movflags faststart {output}.mp4";
$output = get_option('fftheme',$fftheme); ?>
<div class="control-group">
<label class="control-label"><i class="icon-folder-open"></i>FFMPEG template</label>
<div class="controls">
<input type="text" name="fftheme" class="span12" value="<?php echo $output; ?>" /> 
<span class="help-block" id="limit-text">This is the full ffmpeg command used on videos.</span>						
</div>	
</div>	
<div class="row-fluid">
<section class="panel span5">
<div class="panel-heading">
Shortcodes
</div>
<div class="panel-body">
<strong>{ffmpeg-cmd}</strong> - replaced by the input in the field 'FFmpeg executable' from up <br> 
<strong>{input}</strong> - dynamically replaced by the video that has to be converted <br> 
<strong>{ffmpeg-vsize}</strong> - dynamically replaced by the 'Replace sizes' from up <br> 
<strong>{ffmpeg-vsize}</strong> - dynamically replaced by the input in the field 'Replace sizes' from up <br> 
<p>&nbsp;</p>
<p>Need more tips?</p>
<p><a href="https://ffmpeg.org/ffmpeg.html" target="_blank">FFMPEG's official documentation</a></p>
<p><a href="https://www.google.com/search?q=ffmpeg+tips+%26+tricks&num=20&newwindow=1&espv=2&biw=1920&bih=965&source=lnt&tbs=cdr%3A1%2Ccd_min%3A2014%2Ccd_max%3A2016&tbm=" target="_blank">The web is full of ffmpeg tips</a></p>
</div>	
</section>
<section class="panel span7">
<div class="panel-heading">
Options
</div>
<div class="panel-body">
<strong>Default:</strong> 
<pre><code class="html"><?php echo $fftheme; ?></code></pre>
<p>Restricts videos to gives sizes (scaled up or down according to the sizes).
<strong>Kept proportions:</strong> 
<pre><code class="html">{ffmpeg-cmd} -i {input} -c:v libx264 -preset slow -crf 28 -vf yadif -strict -2 -movflags faststart {output}.mp4</code></pre>
<p>Keeps the input videos sizes (no scaling, no resizing). You can edit -crf 28 (<a href="http://slhck.info/articles/crf" target="_blank">Constant Rate Factor</a>) to a lower value <span class="redText">(bigger & quality videos,big duration and server load )</span>.
This is pretty server heavy!!! But will keep HD in HD. <a href="http://www.phpvibe.com/forum/tutorials/ffmpeg-transcoding-for-quality/" target="_blank">Read more</a></p>
</div>	
</section>

</div>
<div class="control-group">
<label class="control-label"><i class="icon-folder-open"></i>Raw media folder</label>
<div class="controls">
<input type="text" name="tmp-folder" class="span12" value="<?php echo get_option('tmp-folder','rawmedia'); ?>" /> 
<span class="help-block" id="limit-text">Folder to store unconverted files. Default: rawmedia</span>						
</div>	
</div>	
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update settings"); ?></button>	
</div>	
</fieldset>						
</form>
</div>
<div class="row-fluid">
<h3>Helpers</h3>
<?php
if(function_exists('exec')) {
echo "<div class=\"control-group\"><p>Attempting a 'which php' command:</p><pre><code class=\"html hljs xml\">";
echo exec('which php');
echo "</code></pre></div>"; 
echo "<div class=\"control-group\"><p>Attempting a 'which ffmpeg' command:</p><pre><code class=\"html hljs xml\">";
echo exec('which ffmpeg');
echo "</code></pre></div>";
echo "<div class=\"msg-info\">This values are not 100% reliable. Please check them with your hosting.</div>";
echo "<div class=\"msg-info\">Newer versions of FFMPEG use the command <strong>ffmpeg</strong> in most cases.</div>";

} else {
echo "<div class=\"msg-warning\">Exec is disabled</div>";	
}
?>
</div>