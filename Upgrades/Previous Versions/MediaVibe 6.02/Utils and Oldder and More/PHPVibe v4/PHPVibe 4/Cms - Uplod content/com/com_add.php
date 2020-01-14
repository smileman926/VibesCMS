<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';
// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}
$token = md5(user_name().user_id().time());
function file_up_support($text) {
global $token;
$text  = '';
$text .= '
<!-- The basic File Upload plugin -->
<script src="'.site_url().'lib/maxupload.js"></script>
 <script type="text/javascript" >
$(document).ready(function(){
	$(\'#dumpvideo\').MaxUpload({
		maxFileSize:'.get_option('maxup','3145728000').',
		maxFileCount: 1,';
if(get_option('ffa','0') == 1 ) {		
$text .= 'target: \''.site_url().'lib/upload-ffmpeg.php\',';
}	
$allext = '';
foreach (explode(",",get_option('alext','flv,mp4,mp3,avi,mpeg'))	as $ex) {
if(!empty($ex)) {  
	$allext .= 	"'.".$ex."',";
}		
}		
$text .= '	
        allowedExtensions:['.$allext.'],
        data: {"token": "'.$token.'"},
        onComplete: function (data) { processVid(data);  },
		onError: function () { findVideo("'.$token.'"); }		
	});
	 });

  </script>

';
return $text;
}
add_filter( 'filter_extrajs', 'file_up_support');
if(isset($_POST['vtoken'])) {
$tok = toDb(_post('vtoken'));
$doit = $db->get_row("SELECT id from ".DB_PREFIX."videos where token = '".$tok."'");
if($doit) {
if(get_option('ffa','0') <> 1 ) {	
//No ffmpeg
$formInputName   = 'play-img';							
	$savePath	     = ABSPATH.'/'.get_option('mediafolder').'/thumbs';								
	$saveName        = md5(time()).'-'.user_id();									
	$allowedExtArray = array('.jpg', '.png', '.gif');	
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$thumb = str_replace(ABSPATH.'/' ,'',$thumb);
} else { $thumb  = 'uploads/noimage.png'; 	}

$sec = _tSec(_post('hours').":"._post('minutes').":"._post('seconds'));
$db->query("UPDATE  ".DB_PREFIX."videos SET duration='".$sec."', thumb='".toDb($thumb )."' , privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."', title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
//$error .=$db->debug();
} else {
//Ffmpeg active
$db->query("UPDATE  ".DB_PREFIX."videos SET privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."',title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
}
add_activity('4', $doit->id);
if(get_option('ffa','0') <> 1 ) {
$error .= '<div class="msg-info">'._post('title').' '._lang("created successfully.").' <a href="'.site_url().me.'">'._lang("Manage videos.").'</a></div>';
} else {
$error .= '<div class="msg-info">'._post('title').' '._lang("uploaded successfully.").' <a href="'.site_url().me.'">'._lang("This video will be available after conversion.").'</a></div>';
}
if(get_option('videos-initial') <> 1) {
$error .= '<div class="msg-info">'._lang("Video requires admin approval before going live.").'</div>';

}
}
}
function modify_content( $text ) {
global $error, $token, $db;
$data =  $error.'
<h1 style="display:block; margin:35px; text-align:center">'._lang("Share a video").'</h1>	
   <div class="clearfix vibe-upload">			
	<div class="row-fluid clearfix ">
	<div id="AddVid" class="row-fluid pull-left">
	<div id="dumpvideo"></div>
	</div>
	</div>
	<div class="row-fluid clearfix ">
    <div id="formVid" class="nomargin well ffup">
	<form id="validate" class="form-horizontal styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vfile" id="vfile"/>	
	<input type="hidden" name="vup" id="vup" value="1"/>	
	<input type="hidden" name="vtoken" id="vtoken" value="'.$token.'"/>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Title:").'</label>
	<div class="controls">
	<input type="text" id="title" name="title" class="validate[required] span12" value="">
	</div>
	</div>';
	if(get_option('ffa','0') <> 1 ) {
	$data .= '<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Thumbnail:").'</label>
	<div class="controls"> <input type="file" name="play-img" id="play-img" class="validate[required] styled"></div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Duration:").'</label>
	<div class="controls">
<div class="row-fluid">
<div class="span4">
<input type="number" min="00" max="59" name="hours" class="span12" value="0"><span class="help-block">Hours format: hh</span>
</div>
<div class="span4">
<input type="number" name="minutes" min="0" max="59" class="span12" value="00"><span class="help-block align-center"> Minutes format: mm</span>
</div>
<div class="span4">
<input type="number" name="seconds" min="0" max="59" class="span12" value="00"><span class="help-block align-center">Seconds format: ss</strong></span>
</div>
</div>
	</div>
	</div>';
	}
	$data .= '
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Category:").'</label>
	<div class="controls">
	'.cats_select('categ').'
	  </div>             
	  </div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Description:").'</label>
	<div class="controls">
	<textarea id="description" name="description" class="validate[required] span12 auto"></textarea>
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Tags:").'</label>
	<div class="controls">
	<input type="text" id="tags" name="tags" class="tags span12" value="">
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("NSFW:").'</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="1"> '._lang("Not safe").' </label>
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="0" checked>'._lang("Safe").'</label>
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Visibility").' </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="1"> '._lang("Users only").' </label>
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="0" checked>'._lang("Everybody").' </label>
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<button id="Subtn" class="btn btn-large pull-right" type="submit" disabled>'._lang("Waiting for upload").'</button>
	</div>
	</form>
	</div>
	
	</div>
	</div>
	</div>
';
return $data;
}
add_filter( 'phpvibe_title', 'modify_title' );

if((get_option('uploadrule') == 1) ||  is_moderator()) {	
add_filter( 'the_defaults', 'modify_content' );
} else {
function udisabled() {
return _lang("This uploading section is disabled");
}
add_filter( 'the_defaults', 'udisabled'  );
}
//Time for design
 the_header();
include_once(TPL.'/sharemedia.php');
the_footer();
?>