<?php $v_id = token_id();
if(_get('nsfw') == 1) { $_SESSION['nsfw'] = 1; }
$embedCode = '';
//Query this image
if(intval($v_id) > 0) { 
$cache_name = "image-".$v_id;
$image = $db->get_row("SELECT ".DB_PREFIX."channels.cat_name as channel_name,".DB_PREFIX."images.*,".DB_PREFIX."users.avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.avatar FROM ".DB_PREFIX."images 
LEFT JOIN ".DB_PREFIX."channels ON ".DB_PREFIX."images.category =".DB_PREFIX."channels.cat_id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."images.`id` = '".$v_id."' limit 0,1");
$cache_name = null; //reset
$is_owner = false;
if ($image) {
if(is_user()) {
/* Check if current user is the owner */	
if($image->user_id == user_id()){
$is_owner = true;
}	
}
// Canonical url
$canonical = image_url($image->id , $image->title); 
//Check for local thumbs
$image->thumb = thumb_fix($image->thumb);

//Check if it's private 
if((($image->privacy == 1) || $image->private == 1 ) && !im_following($image->user_id)) {
//Video is not public
$embedimage = '<div class="vprocessing">
<div class="vpre">'._lang("This image is for subscribers only!").'</div> 
<div class="vex"><a href="'.profile_url($image->user_id,$image->owner).'">'._lang("Please subscribe to ").' '.$image->owner.' '._lang("to see this image").'</a>
</div>
</div>';
}else {
$path = str_replace("localimage/","",$image->source).'@@'.get_option('mediafolder');
$real_link        = site_url() . 'stream.php?type=1&file=' . base64_encode(base64_encode($path));
$embedimage = '<a rel="lightbox" class="media-href img-responsive" title="' . _html($image->title) . '" href="' . $real_link . '">
<img class="media-img" src="' . $real_link . '" />
</a>';
 }  
 if (nsfilter()) { 
$embedimage	.='<div class="nsfw-warn"><span>'._lang("This image is not safe").'</span>
<a href="'.$canonical.'&nsfw=1">'.("I understand and I am over 18").'</a><a href="'.site_url().'">'._lang("I am under 18").'</a>
</div>';
} 
//Lightbox support
function lbox(){
$lightbox = '
<script type="text/javascript" src="'.tpl().'styles/js/jquery.fluidbox.min.js"></script>
<script type="text/javascript">
$(function () {
    $(\'a[rel="lightbox"]\').fluidbox({ viewportFill: 0.99, loader: true});
})
</script>
';
return apply_filters('the_lightbox' , $lightbox);	
}
function lboxcss(){
return '<link href="'.tpl().'styles/fluidbox.min.css" rel="stylesheet" />';	
}
add_filter( 'filter_extracss', 'lboxcss');
add_filter( 'filter_extrajs', 'lbox');
// SEO Filters
function modify_title( $text ) {
global $image;
    return strip_tags(_html(get_option('seo-image-pre','').$image->title.get_option('seo-image-post','')));
}
function modify_desc( $text ) {
global $image;
    return _cut(strip_tags(_html($image->description)), 160);
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
//Time for design
 the_header();
 if(intval($image->media) <> 3) {
/* Is image or music */	 
include_once(TPL.'/image.php');	 
 } else {
/* Is image */	 	 
include_once(TPL.'/single_image.php');
 }
 the_footer();
 
} else {
//Oups, not found
layout('404');
}
}else {
//Oups, not found
layout('404');
}
?>