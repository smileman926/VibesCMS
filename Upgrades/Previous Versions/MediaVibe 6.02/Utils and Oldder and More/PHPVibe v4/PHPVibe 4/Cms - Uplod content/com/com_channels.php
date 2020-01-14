<?php $type = token();
$accept = array("video", "music","images");
if(!in_array($type, $accept)) {$type = 'video';}
$tid = array("video" => 1, "music" => 2, "images"=> 3);
$content = the_nav($tid[$type]);
$active = $type;
// Canonical url
$canonical = site_url().channels.'/'.$type;   
// SEO Filters
function modify_title( $text ) {
global $channel;
    return get_option("BrowseChannels","Browse Channels");
}
function modify_desc( $text ) {
global $channel;
    return get_option("BrowseChannelsDesc","Channels seo description");
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
/*Now to the actual channels page */
if (!is_ajax_call()) { 
the_header();
the_sidebar();
}
include_once(TPL.'/channels.php');
the_footer();
?>