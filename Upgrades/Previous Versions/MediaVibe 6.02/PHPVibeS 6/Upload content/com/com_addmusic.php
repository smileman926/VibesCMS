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
		maxFileSize:'.get_option('maxup','3145728000') * pow(1024,2).',
		maxFileCount: 1,';
		
$text .= 'target: \''.site_url().'lib/upload-mp3.php\',';

$text .= '	
        allowedExtensions:[\'.mp3\'],
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
$sec = _tSec(_post('hours').":"._post('minutes').":"._post('seconds'));
} else {
$sec = 0;
}
$thumb  = 'storage/'.get_option("mediafolder").'/thumbs/xmp3.jpg';
if($_FILES['play-img'] && !nullval($_FILES['play-img']['name'])){
$formInputName   = 'play-img';							# This is the name given to the form's file input
	$savePath	     = ABSPATH.'/storage/'.get_option('mediafolder').'/thumbs';								# The folder to save the image
	$saveName        = md5(time()).'-'.user_id();									# Without ext
	$allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$thumb  = str_replace(ABSPATH.'/' ,'',$thumb);
} 	
} 

if(get_option('ffa','0') <> 1 ) {
$db->query("UPDATE  ".DB_PREFIX."videos SET thumb='".toDb($thumb )."' , privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."', title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', duration='".intval(_post('duration') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
} else {
$db->query("UPDATE  ".DB_PREFIX."videos SET thumb='".toDb($thumb )."', privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."', title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
}
add_activity('4', $doit->id);
$error .= '<div class="msg-info mtop20 mleft20 mright20">'._post('title').' '._lang("created successfully.").' <a href="'.site_url().me.'">'._lang("Manage media.").'</a></div>';
if(get_option('videos-initial') <> 1) {
$error .= '<div class="msg-info mtop20 mleft20 mright20">'._lang("Melody requires admin approval before going live.").'</div>';

}
}
}
function modify_content( $text ) {
global $error, $token, $db;
$data =  $error.'<div id="formVid" class="block isBoxed UploadForm top20 right20" >
<div class="tab-content mtop20 mleft20">';
if(_EmbedMusic() || _UpMusic()) {
$data .= '<ul class="nav nav-tabs nav-tabs-line" id="myTabs" role="tablist">';	
if(_EmbedMusic()) {	
$data .= '<li class="active"><a data-toggle="tab" href="#soundCloud" role="tab"><i class="material-icons">&#xE1B8;</i>'._lang("Soundcloud").'</a></li>';
}
if(_UpMusic()) {	
$data .= '<li class=""><a data-toggle="tab" href="#mp3up" role="tab"><i class="material-icons">&#xE2C6;</i> '._lang("Upload mp3").'</a></li>';
}
$data .= '</ul>';	
}

if(_EmbedMusic()) {	
$data .= '<div class="tab-pane active" id="soundCloud">
<form id="validate" class="row right20 mtop20" action="'.site_url().share.'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vembed" id="vembed" readonly/>
	<input type="hidden" name="media" id="media" readonly value="2"/>
<div class="form-group form-material floating mright10 mtop20">
                  <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class=" icon icon-soundcloud"></i></span>
                    <div class="form-control-wrap mtop20 mbot20">
                      <input type="url" id="vfile" name="vfile" class="form-control input-lg empty" required data-error="'._lang("Please enter a soundcloud url").'">
                      <label class="floating-label">'._lang("Link to a SoundCloud user or track").'</label>
                    <div class="help-block with-errors"></div>
					</div>
                    <span class="input-group-btn">
                      <button id="Subtn2" class="btn btn-outline btn-default" type="submit">'._lang("Embed").'</button>
                    </span>
                  </div>
                </div>
	</form>
	</div>
';
}
$data .= '<div class="tab-pane" id="mp3up">';	

$data .= '<div class="clearfix vibe-upload">			
	<div class="row clearfix ">
	<div id="AddVid" class="row">
	<div id="dumpvideo"></div>
	</div>	
	</div>
	<div class="row clearfix bottom20" style="margin-right:30px">
    <div id="formVid" class="nomargin well ffup bottom20">
	<form id="validate" class="form-horizontal styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vfile" id="vfile"/>	
	<input type="hidden" name="vup" id="vup" value="1"/>	
	<input type="hidden" name="vtoken" id="vtoken" value="'.$token.'"/>
	<div class="control-group">
	<label class="control-label">'._lang("Title:").'</label>
	<div class="controls">
	<input type="text" id="title" name="title" class="form-control" required="" value="">
	</div>
	</div>
	<div class="control-group left10 right10">
	<div class="form-group form-material mtop10">
<label class="control-label" for="inputFile">'._lang("Choose song cover:").'</label>
<input type="text" class="form-control" placeholder="'._lang("Browse for image...").'" readonly="" />
<input type="file" name="play-img" id="play-img" />
    </div>
	</div>
	';
	if(get_option('ffa','0') <> 1 ) {
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
	<div class="control-group">
	<label class="control-label">'._lang("Music category:").'</label>
	<div class="controls">
	'.cats_select('categ',"select","","2").'
	  </div>             
	  </div>
	<div class="control-group">
	<label class="control-label">'._lang("Description:").'</label>
	<div class="controls">
	<textarea id="description" name="description" class="form-control auto" required=""></textarea>
	</div>
	</div>
<div class="control-group mtop20" style="margin-left:15px; margin-right:15px">
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
	 <label class="control-label">'._lang("Audience").'</label>
	  <div class="inline">	  
			<div class="btn-group" data-toggle="buttons" role="group">
			<label class="btn btn-outline btn-primary active">
			<input type="radio" name="nsfw" autocomplete="off" value="0" checked="checked" />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("SAFE").'</label>
			<label class="btn btn-outline btn-primary ">
			<input type="radio" name="nsfw" autocomplete="off" value="1"  />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("NSFW").'</label>
			</div>
	  </div>
	   <div class="inline mleft20">
			<div class="btn-group" data-toggle="buttons" role="group">
			<label class="btn btn-outline btn-primary active">
			<input type="radio" name="priv" autocomplete="off" value="0" checked="checked" />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("Public").'</label>
			<label class="btn btn-outline btn-primary ">
			<input type="radio" name="priv" autocomplete="off" value="2"  />
			<i class="icon icon-check text-active" aria-hidden="true"></i>
			'._lang("Followers").'</label>
			</div>
	  </div>
	  
	  </div>
	<div class="control-group top20 bottom20">
	<button id="Subtn" class="btn btn-large pull-right" type="submit" disabled>'._lang("Waiting for upload").'</button>
	<div class="clearfix"></div>
	</div>
	</form>
	</div>
	</div>
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
