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
<div class="panel panel-transparent mright20 right10">
            <div class="panel-body">
              <h4>'._lang("Embed a video from the web").'</h4>
<div id="formVid" class="block UploadForm">
	
	<form id="validate" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vembed" id="vembed" readonly/>

	<div class="form-group form-material floating">
                  <div class="input-group">
                    <span class="input-group-addon"><i class=" icon icon-link"></i></span>
                    <div class="form-control-wrap">
                      <input type="text" id="vfile" name="vfile" class="form-control empty" required="">
                      <label class="floating-label">'._lang("Link to video").'</label>
                    </div>
                    <span class="input-group-btn">
                      <button id="Subtn" class="btn btn-outline btn-default" type="submit">'._lang("Embed").'</button>
                    </span>
                  </div>
                </div>
	</form>
	</div>              
			  </div>
            <div class="panel-footer">';
$supported = @Vibe_Providers::Hostings();
	$local = array("localfile", "localimage");
	$data .= '<p><strong>'._lang("You can embed from:").'</strong></p>';
	foreach ($supported as $su) {
		if(!in_array($su, $local)) {
		$data .= ucfirst($su).', ';
		}
	}			
     $data .=  ' </div>  </div>
        </div>
    ';
		
} elseif(_post('vfile')) {
$vid = new Vibe_Providers();
$file = _post('vfile');
$file = str_replace('youtu.be/', 'youtube.com/watch?v=',$file );
if(!$vid->isValid($file)){
return '<div class="msg-warning">'._lang("We don't support yet embeds from that website").'</div>';
}
$details = $vid->get_data();	
/* Overwrite file, needed for some sources like Soundcloud */
if(isset($details['source']) && !nullval($details['source'])) {$file = $details['source'];}
$type = 1;
if(_post('media') && (intval(_post('media') > 0))) {$mt = intval(_post('media'));} else {$mt = 1;}	

$span = 12;
	$data =  $error.'
	<div class="row odet">
    <div id="formVid block">
	<div class="ajax-form-result clearfix "></div>
	<form id="validate" class="row ajax-form-video" action="'.site_url().'lib/ajax/addVideo.php" enctype="multipart/form-data" method="post">
	
	<div class="col-md-7">
	
	<input type="hidden" name="file" id="file" value="'.$file.'" readonly/>
	<input type="hidden" name="type" id="type" value="'.$type.'" readonly/>
	';
	$data .= '<input type="hidden" name="media" id="media" readonly value="'.$mt.'"/>';	
	$data .= '<div class="control-group">
	
	<div class="form-group form-material floating">
	<input type="text" id="title" name="title" class="form-control" required="" value="'.$details['title'].'">
	<label class="floating-label">'._lang("Title").'</label>
	</div>
	</div>
	<div class="control-group">
	<div class="form-group">
	<label class="control-label">'._lang("Description").'</label>
	<textarea id="description" name="description" class=" form-control auto" required="">'.$details['description'].'</textarea>
	</div>
	</div>
	<div class="control-group">
	<div class="form-group">
	<div class="input-group">
    <span class="input-group-addon">'._lang("Tags:").'</span>
	<div class="form-control withtags">
	<input type="text" id="tags" name="tags" class="tags form-control" value="'.$details['tags'].'">
	</div>
	</div>
	</div>
	</div>';
		  $init = (isset($details['duration']) && !nullval($details['duration']))? $details['duration'] : 0;
	  $hours = floor($init / 3600);
$minutes = floor(($init / 60) % 60);
$seconds = $init % 60;
$data .=' 	<div class="control-group">
	<label class="control-label">'._lang("Duration:").'</label>
	<div class="controls row">
<div class="col-md-4">
   <div class="input-group">
        <span class="input-group-addon">'._lang("Hours").'</span>
        <input type="number" class="form-control" min="00" max="59" name="hours" value="'.$hours.'">
    </div>
</div>	
<div class="col-md-4">
 <div class="input-group">
        <span class="input-group-addon">'._lang("Min").'</span>
        <input type="number" min="00" max="59" class="form-control" name="minutes" value="'.$minutes.'">
    </div>
</div>
<div class="col-md-4">
<div class="input-group">
        <span class="input-group-addon">'._lang("Sec").'</span>
        <input type="number" name="seconds" min="00" max="59" class="form-control" value="'.$seconds.'">
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
	</div>
	
	<div class="col-md-4 col-md-offset-1">
	<div class="row">
	<div class="control-group">
	<label class="control-label">'._lang("Channel:").'</label>
	<div class="form-group form-material floating">
	'.cats_select('categ','select',' form-control',$mt).'
	  </div>             
	  </div><div class="control-group mtop20">
	<div class="controls " style="padding-left:3px; "> ';
	if($details['thumbnail'] && !empty($details['thumbnail'])) {
$data .=' 
	<img style="max-width:164px" src="'.$details['thumbnail'].'"/>
	<input type="hidden" id="remote-img" name="remote-img" class=" col-md-12" value="'.$details['thumbnail'].'">
';
} else {
$data .='
<div class="form-group form-material">
<label class="control-label" for="inputFile">'._lang("Choose thumbnail:").'</label>
<input type="text" class="form-control" placeholder="'._lang("Browse...").'" readonly="" />
<input type="file" name="play-img" id="play-img" />
    </div>

 ';
}	
$data .=' 	
	</div>
	</div>
	<div class="control-group blc" style="margin-top:50px;">
	<button id="Subtn" class="btn btn-primary pull-right" type="submit">'._lang("Create & Save video").'</button>
	</div>	
	</div>
	<div class="clearfix"></div>
	</div>
	</div>
	</form>
		<div class="clearfix"></div>
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
