<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';
// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}
$token = md5(user_name().user_id().time());
function modify_content_embed( $text ) {
global $error, $token, $db;
if(!_post('vfile')) {
$data =  $error.'
  <div class="clearfix" style="margin:10px;">			
	<div class="row-fluid clearfix ">
		<h1 style="display:block; margin:35px; text-align:center">'._lang("Embed a video from the web").'</h1>

    <div id="formVid" class="block UploadForm">
	
	<form id="validate" class="form-horizontal styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vembed" id="vembed" readonly/>

	<input type="text" id="vfile" name="vfile" value="" placeholder="'._lang("Link to video").'">
	<button id="Subtn" class=" type="submit">'._lang("Embed").'</button>
	</form>
	</div>';
	
	
$data .= '	<div class="UploadFooter">
	<span>
	&nbsp;
	</span>
	</div>
	
	';
$supported = @Vibe_Providers::Hostings();
	$local = array("localfile", "localimage");
	$data .= '<p><strong>'._lang("You can embed from:").'</strong></p>';
	foreach ($supported as $su) {
		if(!in_array($su, $local)) {
		$data .= ucfirst($su).', ';
		}
	}		
} elseif(_post('vfile')) {
$vid = new Vibe_Providers();
if(!$vid->isValid(_post('vfile'))){
return '<div class="msg-warning">'._lang("We don't support yet embeds from that website").'</div>';
}
$details = $vid->get_data();	
$file = _post('vfile');
/* Overwrite file, needed for some sources like Soundcloud */
if(isset($details['source']) && !nullval($details['source'])) {$file = $details['source'];}
$type = 1;
if(_post('media') && (intval(_post('media') > 0))) {$mt = intval(_post('media'));} else {$mt = 1;}	

$span = 12;
	$data =  $error.'
   <div class="clearfix" style="margin:10px;">			
	<div class="row-fluid clearfix mtop20">
    <div id="formVid" class="row-fluid pull-left ">
	<div class="ajax-form-result clearfix "></div>
	<form id="validate" class="form-horizontal ajax-form-video styled" action="'.site_url().'lib/ajax/addVideo.php" enctype="multipart/form-data" method="post">
	
	<div class="span8">
	
	<input type="hidden" name="file" id="file" value="'.$file.'" readonly/>
	<input type="hidden" name="type" id="type" value="'.$type.'" readonly/>
	';
	$data .= '<input type="hidden" name="media" id="media" readonly value="'.$mt.'"/>';	
	$data .= '<div class="control-group">
	<label class="control-label">'._lang("Title:").'</label>
	<div class="controls">
	<input type="text" id="title" name="title" class="validate[required]" value="'.$details['title'].'">
	</div>
	</div>
	<div class="control-group">
	<label class="control-label">'._lang("Description:").'</label>
	<div class="controls">
	<textarea id="description" name="description" class="validate[required] auto">'.$details['description'].'</textarea>
	</div>
	</div>
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Tags:").'</label>
	<div class="controls" style="max-width:614px">
	<input type="text" id="tags" name="tags" class="tags " value="'.$details['tags'].'">
	</div>
	</div>
	<div class="control-group">
	<label class="control-label">'._lang("NSFW:").'</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="1"> '._lang("Not safe").' </label>
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="0" checked>'._lang("Safe").'</label>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label">'._lang("Visibility").' </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="1"> '._lang("Users only").' </label>
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="0" checked>'._lang("Everybody").' </label>
	</div>
	</div>
	</div>
	<div class="span4">
	<div class="row-fluid">
	<div class="control-group">
	<label class="control-label">'._lang("Channel:").'</label>
	<div class="controls">
	'.cats_select('categ','select','validate[required]',$mt).'
	  </div>             
	  </div>';
	  $init = (isset($details['duration']) && !nullval($details['duration']))? $details['duration'] : 0;
	  $hours = floor($init / 3600);
$minutes = floor(($init / 60) % 60);
$seconds = $init % 60;
$data .=' 	<div class="control-group">
	<label class="control-label">'._lang("Duration:").'</label>
	<div class="controls row-fluid">
	
<div class="span2">
<input type="number" min="00" max="59" name="hours" value="'.$hours.'"> <span class="d-block">hh</span>
</div>
<div class="span2">
<input type="number" name="minutes" min="00" max="59" value="'.$minutes.'">  <span class="d-block">mm</span>
</div>
<div class="span2">
<input type="number" name="seconds" min="01" max="59" value="'.$seconds.'"> <span class="d-block">ss</span>
</div>
</div>
	</div>
		<div class="control-group">
	<label class="control-label">'._lang("Thumbnail:").' </label>
	<div class="controls" style="padding-left:3px;"> ';
	if($details['thumbnail'] && !empty($details['thumbnail'])) {
$data .=' 
	<img style="max-width:164px" src="'.$details['thumbnail'].'"/>
	<input type="hidden" id="remote-img" name="remote-img" class=" span12" value="'.$details['thumbnail'].'">
';
} else {
$data .='
<input type="file" name="play-img" id="play-img" class="styled">
	
 ';
}	
$data .=' 	
	</div>
	</div>
	<div class="control-group" style="margin-top:30px;">
	<button id="Subtn" class="btn btn-primary pull-right" type="submit">'._lang("Create & Save video").'</button>
	</div>
	</form>
	
	</div>
	
	</div>
	</div>
	</div>
';
} else {
$data ='<div class="msg-warning">'._lang("Something went wrong, please try again.").'</div>';
}
return $data;
}
add_filter( 'phpvibe_title', 'modify_title' );

if((get_option('sharingrule','0') == 1) ||  is_moderator()) {	
add_filter( 'the_defaults', 'modify_content_embed' );
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