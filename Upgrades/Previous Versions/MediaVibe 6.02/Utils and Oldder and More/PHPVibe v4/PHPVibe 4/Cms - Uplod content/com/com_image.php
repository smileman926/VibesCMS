<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';
// SEO Filters
if(isset($_POST['pic-title'])) {
//var_dump($_FILES);
	$savePath	     = ABSPATH.'/'.get_option('mediafolder').'/';								# The folder to save the image
	$saveName        = md5(time()).'-'.user_id();									# Without ext
	$allowedExtArray = array('jpg', 'png', 'gif');	# Set allowed file types
	$imageQuality    = 100;

$ext = substr($_FILES['play-img']['name'], strrpos($_FILES['play-img']['name'], '.') + 1);
if(in_array($ext,$allowedExtArray )) {
if (move_uploaded_file($_FILES['play-img']['tmp_name'], $savePath.$saveName.'.'.$ext)) {
$thumb  = $savePath.$saveName.'.'.$ext;
$thumb = str_replace(ABSPATH.'/' ,'',$thumb);
$source = str_replace(get_option('mediafolder') ,'localimage',$thumb);
//Do the sql insert
$db->query("INSERT INTO ".DB_PREFIX."videos (`privacy`,`media`,`category`, `pub`,`source`, `user_id`, `date`, `thumb`, `title`, `tags` , `views` , `liked` , `description`, `nsfw`, `duration`) VALUES 
('".intval(_post('priv'))."','3','".intval(_post('categ'))."','".intval(get_option('videos-initial'))."','".$source."', '".user_id()."', now() , '".$thumb."', '".toDb(_post('pic-title')) ."',  '".toDb(_post('tags'))."', '0', '0','".toDb(_post('pic-desc'))."','".toDb(_post('nsfw'))."', '0')");	
$doit = $db->get_row("SELECT id from ".DB_PREFIX."videos where user_id = '".user_id()."' order by id DESC limit 0,1");
add_activity('4', $doit->id);
$error .= '<div class="msg-info">'._post('pic-title').' '._lang("created successfully.").' <a href="'.site_url().me.'">'._lang("Manage media.").'</a></div>';
if(get_option('videos-initial') <> 1) {
$error .= '<div class="msg-info">'._lang("Image requires admin approval before going live.").'</div>';

}
//$db->clean_cache();
}
}
}
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}
$token = md5(user_name().user_id().time());
function file_up_support($text) {
$text = '<script type="text/javascript" >
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();            
            reader.onload = function (e) {
                $(\'#targetImg\').attr(\'src\', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgInp").change(function(){
        readURL(this);
		$( ".Pimg" ).slideDown();
    });

  </script>

';
return $text;
}
add_filter( 'filter_extrajs', 'file_up_support');
function modify_content( $text ) {
global $error, $token, $db;
$data =  $error.'
<h1 style="display:block; margin:35px; text-align:center">'._lang("Share a picture").'</h1>	
   <div class="clearfix vibe-upload">			
	<div class="row-fluid clearfix ">
    <div id="formVid" class="nomargin ">
	<form id="validate" class="form-horizontal styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
	<input type="hidden" name="vfile" id="vfile"/>	
	<input type="hidden" name="vup" id="vup" value="1"/>	
	<input type="hidden" name="vtoken" id="vtoken" value="'.$token.'"/>
	<div class="control-group">
	<label class="control-label">'._lang("Choose an image:").'</label>
	<div class="controls">
	<div class="row-fluid">
	<div class="span4">
	<input type="file" id="imgInp" name="play-img" class="validate[required] styled"/>
	</div>
	<div class="span6">
	<div class="Pimg" style="display:none;">
	<div class="noted label-success"><i class="icon-ok"></i> </div>
        <img id="targetImg" src="#" />
	</div>	
	</div>	
	</div>	
	</div>	
	</div>	
	<div class="control-group blc row-fluid">
	<label class="control-label">'._lang("Title:").'</label>
	<div class="controls">
	<input type="text" id="title" name="pic-title" class="validate[required] span12" value="">
	</div>
	</div>';
	
	$data .= '
	<div class="control-group  blc row-fluid">
	<label class="control-label">'._lang("Category:").'</label>
	<div class="controls">
	'.cats_select('categ',"select","","3").'
	  </div>             
	  </div>
	<div class="control-group  blc row-fluid">
	<label class="control-label">'._lang("Description:").'</label>
	<div class="controls">
	<textarea id="description" name="pic-desc" class="validate[required] span12 auto"></textarea>
	</div>
	</div>
	<div class="control-group  blc row-fluid">
	<label class="control-label">'._lang("Tags:").'</label>
	<div class="controls">
	<input type="text" id="tags" name="tags" class="tags span12" value="">
	</div>
	</div>
	<div class="control-group  blc row-fluid">
	<label class="control-label">'._lang("Visibility").' </label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="1"> '._lang("Users only").' </label>
	<label class="radio inline"><input type="radio" name="priv" class="styled" value="0" checked>'._lang("Everybody").' </label>
	</div>
	</div>
	<div class="control-group  blc row-fluid">
	<label class="control-label">'._lang("NSFW:").'</label>
	<div class="controls">
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="1"> '._lang("Not safe").' </label>
	<label class="radio inline"><input type="radio" name="nsfw" class="styled" value="0" checked>'._lang("Safe").'</label>
	</div>
	</div>
	
	<div class="control-group">
	<button id="Subtn" class="btn btn-primary pull-right" type="submit">'._lang("Upload").'</button>
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