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
if(!is_insecure_file($_FILES['play-img']['name'])) {	
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
} else { $thumb  = 'uploads/noimage.png'; 	}
} else {
//Ffmpeg active
$db->query("UPDATE  ".DB_PREFIX."videos SET privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."',title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
}
add_activity('4', $doit->id);
if(get_option('ffa','0') <> 1 ) {
$error .= '<div class="msg-info mtop20 mright20">'._post('title').' '._lang("created successfully.").' <a href="'.site_url().me.'">'._lang("Manage videos.").'</a></div>';
} else {
$error .= '<div class="msg-info mtop20 mright20">'._post('title').' '._lang("uploaded successfully.").' <a href="'.site_url().me.'">'._lang("This video will be available after conversion.").'</a></div>';
}
if(get_option('videos-initial') <> 1) {
$error .= '<div class="msg-info mtop20 mright20">'._lang("Video requires admin approval before going live.").'</div>';

}
}
}
function modify_content( $text ) {
global $error, $token, $db;
$data =  $error.'
<h1 class="block full mtop20 mbot20">'._lang("Share a video").'</h1>	
   <div class="clearfix vibe-upload mright20 mbot20">			
	<div class="row clearfix ">
	<div id="AddVid" class="text-center">
	<div id="dumpvideo"></div>
	</div>
	</div>
	<div class="row clearfix right10">
    <div id="formVid" class="nomargin well ffup">
	<form id="validate" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vfile" id="vfile"/>	
	<input type="hidden" name="vup" id="vup" value="1"/>	
	<input type="hidden" name="vtoken" id="vtoken" value="'.$token.'"/>
	<div class="control-group blc row">
	<label class="control-label">'._lang("Title:").'</label>
	<div class="controls">
	<input type="text" id="title" name="title" class="validate[required] form-control col-md-12" value="">
	</div>
	</div>';
	if(get_option('ffa','0') <> 1 ) {
	$data .='
<div class="form-group form-material mtop10">
<label class="control-label" for="inputFile">'._lang("Choose thumbnail:").'</label>
<input type="text" class="form-control" placeholder="'._lang("Browse...").'" readonly="" />
<input type="file" name="play-img" id="play-img" />
    </div>
 ';		
	$data .= '<div class="control-group">
	<label class="control-label">'._lang("Duration:").'</label>
	<div class="controls row">
<div class="col-md-4">
   <div class="input-group">
        <span class="input-group-addon">'._lang("Hours").'</span>
        <input type="number" class="form-control" min="00" max="59" name="hours" value="">
    </div>
</div>	
<div class="col-md-4">
 <div class="input-group">
        <span class="input-group-addon">'._lang("Min").'</span>
        <input type="number" min="00" max="59" class="form-control" name="minutes" value="">
    </div>
</div>
<div class="col-md-4">
<div class="input-group">
        <span class="input-group-addon">'._lang("Sec").'</span>
        <input type="number" name="seconds" min="00" max="59" class="form-control" value="">
</div>
</div>
</div>
</div>';
	}
	$data .= '
	<div class="control-group mtop10">
	<label class="control-label">'._lang("Category:").'</label>
	<div class="controls">
	'.cats_select('categ').'
	  </div>             
	  </div>
	<div class="control-group mtop10">
	<label class="control-label">'._lang("Description:").'</label>
	<div class="controls">
	<textarea id="description" name="description" class="validate[required] form-control auto"></textarea>
	</div>
	</div>
	<div class="control-group mtop10">
	<div class="form-group">
	<div class="input-group">
    <span class="input-group-addon">'._lang("Tags:").'</span>
	<div class="form-control withtags">
	<input type="text" id="tags" name="tags" class="tags form-control" value="">
	</div>
	</div>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label">'._lang("NSFW:").'</label>
	<div class="controls row">
	<div class="radio-custom radio-primary col-md-4">
	<input type="radio" name="nsfw" value="1">
	<label> '._lang("Not safe").' </label>
	</div>
	<div class="radio-custom radio-primary col-md-4">
	<input type="radio" name="nsfw" value="0" checked>
	<label >'._lang("Safe").'</label>
	</div>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label">'._lang("Privacy:").' </label>
	<div class="controls row">
	<div class="radio-custom radio-primary col-md-4">
	<input type="radio" name="priv" value="1">
	<label> '._lang("Followers only").' </label>
	</div>
	<div class="radio-custom radio-primary col-md-4">
	<input type="radio" name="priv" value="0" checked>
	<label>'._lang("Public").' </label>
	</div>
	</div>
	</div>
	<div class="control-group blc row">
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
