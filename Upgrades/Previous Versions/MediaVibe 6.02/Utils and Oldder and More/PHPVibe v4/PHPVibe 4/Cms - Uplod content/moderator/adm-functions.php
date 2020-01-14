<?php 
function admin_url($sk = null){
if(is_null($sk)) {
return site_url().ADMINCP.'/';
} else {
return site_url().ADMINCP.'/?sk='.$sk;
}
}
function video_importer_links() {
return apply_filters('importers_menu',false);
}
//filter
function admin_css(){
return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHPVibe - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="PHPVibe.com">
	<base href="'.admin_url().'" />  
<link rel="stylesheet" href="'.admin_url().'css/bootstrap.min.css">
	<link rel="stylesheet" href="'.admin_url().'css/responsive.css">
    <link rel="stylesheet" href="'.admin_url().'css/font-awesome.css">
    <link rel="stylesheet" href="'.admin_url().'css/style.css" type="text/css" media="screen" >
	<link rel="stylesheet" href="'.admin_url().'css/plugins.css"/>

    <link href=\'//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800\' rel=\'stylesheet\' type=\'text/css\'>
  
   <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    ';
}
function admin_h(){
$head= '
    '.admin_css().'
	<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.imagesloaded.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.tipsy.js"></script>
<script type="text/javascript" src="'.admin_url().'js/bootstrap.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.validation.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.validationEngine-en.js"></script> 	
<script type="text/javascript" src="'.admin_url().'js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.tagsinput.min.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.select2.min.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.listbox.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.inputmask.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.autosize.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.form.js"></script>
<script type="text/javascript" src="'.admin_url().'editor/tinymce.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.navgoco.js"></script>
<script type="text/javascript" src="'.admin_url().'js/highlight.pack.js"></script>
<script type="text/javascript" src="'.admin_url().'js/phpvibe.js"></script>
<script type="text/javascript" src="' . site_url() . 'lib/players/jwplayer/jwplayer.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script type="text/javascript">
tinymce.init({
theme: "modern",
skin: "light",
mode : "textareas",
    editor_selector : "ckeditor",
	 plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern code"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "code print preview media | forecolor backcolor emoticons",
    image_advtab: true
 });
</script>
';
if (get_option('jwkey')) {$head .= '<script type="text/javascript">jwplayer.key="' . get_option('jwkey') . '";</script>';}
if(!isset($_GET['sk'])) {$clx = 'page-transparent';} else {$clx = '';}
$head .= '</head>
   <div id="wrap">
<div class="container-fluid page '.$clx.'">';
if(get_option('site-offline', 0) == 1 ) { $st = 'checked="checked"'; } else {$st = ''; }
$head .=  '<div id="header">   
	<a class="toggle-btn"><i class="icon-indent-left"></i></a>
	<form id="on-off"  class="ajax-form" action="'.admin_url().'api.php?action=offline" enctype="multipart/form-data" method="post">
	<input type="checkbox" name="offline" id="offline" class="sw" '.$st.' />
		<label class="switch switch--dark switch--header" for="offline"></label>
	</form>
   <div id="header-menu">
<div class="topnav">
<ul>
<li><a href="'.admin_url().'" title="Logout"><i class="icon-home"></i>Administration</a></li>
<li><a href="'.admin_url('clean-cache').'"><i class="icon-remove-sign"></i>Clean Cache</a></li>
<li class="pull-right"><a href="'.site_url().'index.php?action=logout" title="Logout"><i class="icon-off"></i>Logout</a></li>
<li class="pull-right"><a href="'.site_url().'" target="_blank" title="Open the website"><i class="icon-external-link"></i>Preview site</a></li>

</ul>
</div>
   </div>
   </div>
   <div class="ajax-form-result hide"></div>
   
'.adm_sidebar();
return $head;
}
add_filter('adm_head', 'admin_h');
//common
function admin_head () {
echo apply_filters('adm_head', false);
}
add_action('ahead','admin_head', 1);
function admin_header() {
do_action('ahead');
}
function add_active($sub) {
$a = _get('sk');
$c = explode(",",$sub);
if(!in_array($a, $c)) {
return '';
} else {
return 'in';
}
}
//style
function adm_sidebar(){
$sb = '
<div class="navbar admin-sidebar">
<div class="sidescroll">
          <div class="sidebar-nav blc">
		  <ul>
           <li class="LiHead"> <a href="#"><i class="icon-cogs"></i> Settings</a>
                   <ul>
                     <li><a href="'.admin_url('setts').'"><i class="icon-magic"></i> Globals</a>
					  <li><a href="'.admin_url('ytsetts').'"><i class="icon-youtube"></i> Youtube API</a>
					 <li><a href="'.admin_url('upsetts').'"><i class="icon-upload-alt"></i> Uploads Config</a>
					<li><a href="'.admin_url('menulinks').'"><i class="icon-th-list"></i> Menu Links</a>
                    <li><a href="'.admin_url('players').'"><i class="icon-play"></i> Players Config</a>
                    <li><a href="'.admin_url('ffmpeg').'"><i class="icon-facetime-video"></i> FFMPEG Conversion </a>
                    <li><a href="'.admin_url('login').'"><i class="icon-key"></i> Login Settings</a>
					 <li><a href="'.admin_url('seo').'"><i class="icon-search"></i> SEO Setts</a>
					 <li><a href="'.admin_url('sef').'"><i class="icon-link"></i> Permalinks</a>
					 <li><a href="'.admin_url('cache').'"><i class="icon-database"></i> Cache</a>
'.apply_filters("configuration_menu",false).'
					</ul>
				</li>	
      
           
                    <li class="LiHead"><a href="#"><i class="icon-list"></i> Media Manager</a>
                  
                    <ul>
                     <li> <a  href="'.admin_url('videos').'"><i class="icon-list-alt"></i>Video manager</a></li>
                      <li><a  href="'.admin_url('images').'"><i class="icon-picture"></i>Images manager</a></li>
					  <li><a  href="'.admin_url('music').'"><i class="icon-music"></i>Music manager</a></li>	
                     <li> <a  href="'.admin_url('add-video').'"><i class="icon-plus-sign"></i>Add Remote video</a></li>
            		<li>  <a  href="'.admin_url('add-by-iframe').'"><i class="icon-plus-sign"></i>Add Embed Code</a></li>
					<li> <a  href="'.admin_url('rawmedia').'"><i class="icon-trash"></i>RawMedia Explorer</a></li>
					<li> <a  href="'.admin_url('unvideos').'"><i class="icon-trash"></i>Unpublished / Trash Can</a></li>
					</ul>
                  </li>
                <li class="LiHead">
                    <a href="#"><i class="icon-signin"></i> Video Importers</a>
                    <ul>
					'.apply_filters("pre-importers_menu",false).'
                     '.video_importer_links().'
					 '.apply_filters("post-importers_menu",false).'
                    </ul>
                  </li>
                <li class="LiHead">
                    <a   href="#"><i class="icon-table"></i> Channels</a>
                    <ul>
                      <li><a href="'.admin_url('channels').'"><i class="icon-list-alt"></i>Manage</a>
                      <li><a href="'.admin_url('create-channel').'"><i class="icon-plus-sign"></i>Create</a>
                    </ul>
                  </li>
               
                 <li class="LiHead"><a href="'.admin_url('playlists').'"><i class="icon-forward"></i> Playlists</a>
                  </li>
                
               '.apply_filters("midd_menu",false).'
               <li class="LiHead">
                    <a  href="#"><i class="icon-user"></i> Users</a>
                  <ul>
					<li><a href="'.admin_url('create-user').'"><i class="icon-plus-sign"></i>Create user</a>
					<li><a href="'.admin_url('usergroups').'"><i class="icon-list-alt"></i>Usergroups</a>
					<li><a href="'.admin_url('users').'"><i class="icon-list-alt"></i>All users</a>
                    <li><a href="'.admin_url('users').'&sort=active"><i class="icon-list-alt"></i>Active</a> 
                    <li><a href="'.admin_url('users').'&sort=innactive"><i class="icon-list-alt"></i>Innactive</a>             
                   </ul>
                  </li>
                <li>
                    <a  href="#collapse110"><i class="icon-edit"></i> Articles</a>
                    <ul>
                    <li><a href="'.admin_url('posts').'"><i class="icon-file"></i>Articles</a> 
                    <li><a href="'.admin_url('create-post').'"><i class="icon-plus-sign"></i>New Article</a> 
                    <li><a href="'.admin_url('pch').'"><i class="icon-list-alt"></i>Categories</a> 
					<li><a href="'.admin_url('create-pch').'"><i class="icon-plus-sign"></i>New Category</a> 					  
                   </ul>
                  </li>
               <li class="LiHead">
                    <a  href="#"><i class="icon-book"></i> Pages</a>
                   <ul>
				
                    <li><a href="'.admin_url('pages').'"><i class="icon-list-alt"></i>List pages</a> 
                      <li><a href="'.admin_url('create-page').'"><i class="icon-plus-sign"></i>Create</a>             
                   </ul>
                  </li>
               
				<li class="LiHead">
                    <a  href="#"><i class="icon-money"></i> Ads Placement</a>
                  <ul>
					<li><a href="'.admin_url('videoads').'"><i class="icon-plus-sign"></i>OverVideo ads</a>
				
                    <li><a href="'.admin_url('ads').'"><i class="icon-list-alt"></i>inSite Ads</a> 
				  
                     		'.apply_filters('filter-ads-menu',false).'		  
                   </ul>
                  </li>
               
                 <li class="LiHead"><a href="'.admin_url('comments').'"><i class="icon-comments-alt"></i> Comments</a>
                  </li>
                                    <li><a href="'.admin_url('reports').'"><i class="icon-flag"></i> Reports</a>
                  </li>
                                   <li><a href="'.admin_url('plugins').'"><i class="icon-filter"></i> Plugins</a>
                  </li>
                
                 <li class="LiHead"><a href="'.admin_url('homepage').'"><i class="icon-edit"></i> Homepage builder</a>
                  </li>
                
                   <li class="LiHead"><a href="'.admin_url('langs').'"><i class="icon-globe"></i> Languages</a>
                  </li>
                                    <li><a href="'.admin_url('crons').'"><i class="icon-retweet"></i> Scheduled tasks</a>
                  </li>
               
				
                 <li class="LiHead"><a href="'.admin_url('activity').'"><i class="icon-check"></i> Activity</a>
                  </li>

				'.apply_filters("end_menu",false).'
				<li class="LiHead"><a  href="#"><i class="icon-cogs"></i> Tools</a>
                 
                    <ul>
                      '.tools_menu().'                     
					 </ul>
                  </li>
               
        </div>
   </div>
 </div>
';
return $sb;
}

function tools_menu() {
return apply_filters('filter-tools-menu',false);
}
function support_links($tools){
return $tools.'
<li><a href="'.admin_url('clean-cache').'"><i class="icon-trash"></i>Clean cache</a>
<li><a href="'.admin_url('options').'"><i class="icon-list-alt"></i>Current Options </a>
<li><a target="_blank" href="http://www.phpvibe.com/forum/"><i class="icon-external-link"></i>PHPVibe Support</a>
<li><a target="_blank" href="http://get.phpvibe.com/"><i class="icon-external-link"></i>Get plugins</a>                  
';
}
add_filter('filter-tools-menu','support_links');

function count_uvid($u){
global $db;
$sub = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where user_id ='".$u."'");
return $sub->nr;
}
function count_uact($u){
global $db;
$sub = $db->get_row("Select count(*) as nr from ".DB_PREFIX."activity where user ='".$u."'");
return $sub->nr;
}
function delete_activity_by_video($id){
global $db;
$db->query("delete from ".DB_PREFIX."activity where object ='".$id."'");
}
function delete_user($id){
global $db;
$user = $db->get_row("select id,name,avatar from ".DB_PREFIX."users where id = ".$id."");
if($user){
//remove avatar
if($user->avatar){
$thumb = $user->avatar;
if($thumb && ($thumb != "uploads/noimage.png") && ($thumb != "media/thumbs/xmp3.jpg")) {
$vurl = parse_url(trim($thumb, '/')); 
if(!isset($vurl['scheme']) || $vurl['scheme'] !== 'http'){ 
$thumb = ABSPATH.'/'.$thumb;
//remove avatar file
 remove_file($thumb);
 }
}
}
//remove videos
$videos = $db->get_results("Select id from ".DB_PREFIX."videos where user_id ='".$id."' limit 0,10000000");
if($videos) {
foreach ($videos as $re) {
delete_video($re->id);
delete_activity_by_video($re->id);
}
}
//remove likes
$likes = $db->get_results("Select vid from ".DB_PREFIX."likes where uid ='".$id."' limit 0,10000000");
if($likes){
foreach ($likes as $lre) {
unlike_video($lre->vid, $id);
}
}
//remove friendships
$db->query("delete from ".DB_PREFIX."users_friends where uid ='".$id."' or fid='".$id."'");
//remove comments
$db->query("delete from ".DB_PREFIX."em_comments where sender_id ='".$id."'");
//remove playlists
$play = $db->get_results("Select id from ".DB_PREFIX."playlists where owner ='".$id."' limit 0,10000000");
if($play){
foreach ($play as $pl) {
delete_playlist($pl->id);
}
}
//remove activity 
$db->query("delete from ".DB_PREFIX."activity where user ='".$id."'");
//finally remove user
$db->query("delete from ".DB_PREFIX."users where id ='".$id."'");
echo '<div class="msg-info">User '.$user->name.' deleted.</div>';
} else {
echo '<div class="msg-warning">User with id #'.$id.' does not exist.</div>';
}
}
function delete_cron($id) {
global $db;
$db->query("delete from ".DB_PREFIX."crons where cron_id ='".$id."'");
}
function add_cron($args = array(), $title = null) {
global $db;
unset($args["sk"]);
unset($args["docreate"]);
unset($args["p"]);
$value = maybe_serialize($args);
$type = escape($args["type"]);
if(is_null($title)) {
$name = ucfirst($type).' - '.ucfirst($args["action"]).' - '.date('l jS \of F Y h:i:s A');
} else {
$name = escape($title);
}
$db->query( "INSERT INTO  ".DB_PREFIX."crons (`cron_type`, `cron_name`, `cron_value`) VALUES ('$type','$name', '$value')" );
echo '<div class="msg-info">Cron '.$name.' created .</div>';
}
function cron_fastest($new) {
$old = get_option('cron_interval');
if($old > $new ) {
update_option('cron_interval', $new);
}
}
?>