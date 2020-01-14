<?php $pid = token_id();

$_post = $db->get_row("select * from ".DB_PREFIX."posts where pid = '".$pid."'");

if($_post) {

// SEO Filters
function modify_title( $text ) {
global $_post;
 return get_option('seo-post-pre','')._cut(strip_tags(stripslashes($_post->title)), 260).get_option('seo-post-post','');
}
function modify_desc( $text ) {
global $_post;
    return _cut(strip_tags(stripslashes($_post->content)), 160);
}
function modify_content( $text ) {
global $_post;
$txt = '<h3 class="loop-heading"><span>'._html($_post->title).'</span></h3>';
if($_post->pic) {$txt .= '<div class="row-fluid"><img src="'.thumb_fix($_post->pic).'" /></div>';}
$txt .= '<div class="page-content">';
$txt .= _html($_post->content);
$txt .= '</div>';
return $txt;
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'the_defaults', 'modify_content' );
add_filter( 'phpvibe_desc', 'modify_desc' );
//Time for design
 the_header();
include_once(TPL.'/post.php');
the_footer();
} else {
//Oups, not found
layout('404');
}

?>