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
    <title>PHPVibe 5 - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="PHPVibe.com">
	<base href="'.admin_url().'" />  
<link rel="stylesheet" href="'.admin_url().'css/bootstrap.min.css">
	<link rel="stylesheet" href="'.admin_url().'css/responsive.css">
    <link rel="stylesheet" href="'.admin_url().'css/font-awesome.css">
    <link rel="stylesheet" href="'.admin_url().'css/style.css" type="text/css" media="screen" >
	<link rel="stylesheet" href="'.admin_url().'css/plugins.css"/>

    <link href=\'//fonts.googleapis.com/css?family=Raleway:300|Lato:300,400|Roboto:400,500,700&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic\' rel=\'stylesheet\' type=\'text/css\'>
    ';
}
function admin_h(){
$head= '
    '.admin_css().'
	<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
if((typeof jQuery == "undefined") || !window.jQuery )
{
   var script = document.createElement("script");
   script.type = "text/javascript";
   script.src = "http://www.lol.pub/tpl/main/styles/js/jquery.js";
   document.getElementsByTagName(\'head\')[0].appendChild(script);
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.imagesloaded.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.tipsy.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
var bootsloaded = (typeof $().modal == \'function\');
if(!bootsloaded) {
$.getScript("'.site_url().'/tpl/main/styles/js/bootstrap.js");	
}
</script>

<script type="text/javascript" src="'.admin_url().'js/jquery.validation.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.validationEngine-en.js"></script> 	
<script type="text/javascript" src="'.admin_url().'js/jquery.tagsinput.min.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.select2.min.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.listbox.js"></script>	
<script type="text/javascript" src="'.admin_url().'js/jquery.autosize.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.form.js"></script>
<script type="text/javascript" src="'.admin_url().'editor/tinymce.min.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery.navgoco.js"></script>
<script type="text/javascript" src="'.admin_url().'js/highlight.pack.js"></script>
<script type="text/javascript" src="'.admin_url().'js/jquery-labelauty.js"></script>
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
$head .=  '<div id="header">   
	<a class="toggle-btn"><i class="icon-navicon"></i></a>
<div class="searchWidget">
<form action="" method="get" onsubmit="location.href=\''.admin_url().'?sk=search-videos&key=\' + encodeURIComponent(this.key.value); return false;" id="searchform">
<div class="form-group form-material floating">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-search"></i></span>
                    <div class="form-control-wrap">
                      <input type="text" class="form-control input-lg empty" name="key" value ="">
                      <label class="floating-label">Search media</label>
                    </div>
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Search</button>
                    </span>
                  </div>
                </div>
				</form>
</div>
<div id="suggest-results">
</div>
	
	
   <div id="header-menu">
<div class="topnav pull-right">
<ul>
<li><a href="'.admin_url().'" title="Logout"><i class="icon-home"></i>Home</a></li>
<li><a href="'.admin_url('clean-cache').'"><i class="icon-stack-exchange"></i>Clean Cache</a></li>
<li><a href="'.site_url().'" target="_blank" title="Open the website"><i class="icon-sign-out"></i>Frontend</a></li>
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
<div class="logo-content">'.show_logo().'</div>
<div class="sidescroll">
          <div class="sidebar-nav blc">
		  <ul>
           <li class="LiHead"> <a href="#"><i class="icon-cogs"></i> Settings</a>
                   <ul>
                     <li><a href="'.admin_url('setts').'"><i class="icon-pencil"></i> Site & globals</a>
					 <li><a href="'.admin_url('upsetts').'"><i class="icon-cloud-upload"></i> Uploading</a>
                    <li><a href="'.admin_url('players').'"><i class="icon-youtube-play"></i> Players</a>
                    <li><a href="'.admin_url('ffmpeg').'"><i class="icon-linux"></i> FFMPEG Conversion </a>
                    <li><a href="'.admin_url('login').'"><i class="icon-facebook-official"></i> Logins</a>
					 <li><a href="'.admin_url('seo').'"><i class="icon-google-plus-square"></i> S.E.O.  </a>
					 <li><a href="'.admin_url('sef').'"><i class="icon-link"></i> Permalinks</a>
                     <li><a href="'.admin_url('plugins').'"><i class="icon-plug"></i> Plugins</a></li>
                     <li><a href="'.admin_url('homepage').'"><i class="icon-home"></i> Frontpage</a></li>
					 <li><a href="'.admin_url('langs').'"><i class="icon-globe"></i> Languages</a></li>
					<li><a href="'.admin_url('footer-socials').'"><i class="icon-twitter"></i> Socials</a></li>
 
					'.apply_filters("configuration_menu",false).'
					</ul>
				</li>	
      
           
                    <li class="LiHead"><a href="#"><i class="icon-list"></i> Media Manager</a>
                  
                    <ul>
                     <li> <a  href="'.admin_url('videos').'"><i class="icon-play-circle"></i>Video manager</a></li>
                      <li><a  href="'.admin_url('images').'"><i class="icon-camera"></i>Images manager</a></li>
					  <li><a  href="'.admin_url('music').'"><i class="icon-soundcloud"></i>Music manager</a></li>	
                      <li> <a  href="'.admin_url('rawmedia').'"><i class="icon-tint"></i>RawMedia Explorer</a></li>
					<li> <a  href="'.admin_url('unvideos').'"><i class="icon-trash"></i>Del. Vids & Songs</a></li>
					<li> <a  href="'.admin_url('unimages').'"><i class="icon-trash"></i>Del. Images</a></li>
					</ul>
                  </li>
                <li class="LiHead">
                    <a href="#"><i class="icon-stack-overflow"></i> Add Media</a>
                    <ul>
					'.apply_filters("pre-importers_menu",false).'
                     '.video_importer_links().'
					 '.apply_filters("post-importers_menu",false).'
					  <li> <a  href="'.admin_url('add-video').'"><i class="icon-link"></i>Add Remote video</a></li>
            		<li>  <a  href="'.admin_url('add-by-iframe').'"><i class="icon-code"></i>Add Embed Code</a></li>
                    <li><a href="'.admin_url('crons').'"><i class="icon-retweet"></i> Scheduled tasks</a></li>
      
                    </ul>
                  </li>
                <li class="LiHead">
                    <a   href="#"><i class="icon-table"></i> Categories</a>
                    <ul>
                      <li><a href="'.admin_url('channels').'"><i class="icon-pencil"></i>Manage</a>
                      <li><a href="'.admin_url('create-channel').'"><i class="icon-plus"></i>Create</a>
                    </ul>
                  </li>
               
                 <li class="LiHead"><a href="'.admin_url('playlists').'"><i class="icon-forward"></i> Collections</a>
                  </li>
                
               '.apply_filters("midd_menu",false).'
               <li class="LiHead">
                    <a  href="#"><i class="icon-slideshare"></i> User Channels</a>
                  <ul>
					<li><a href="'.admin_url('create-user').'"><i class="icon-plus"></i>Create user</a>
					<li><a href="'.admin_url('usergroups').'"><i class="icon-list-alt"></i>Usergroups</a>
					<li><a href="'.admin_url('users').'"><i class="icon-list-alt"></i>All users</a>
                    <li><a href="'.admin_url('users').'&sort=active"><i class="icon-list-alt"></i>Active</a> 
                    <li><a href="'.admin_url('users').'&sort=innactive"><i class="icon-list-alt"></i>Innactive</a>             
                   </ul>
                  </li>
                <li>
                    <a href="#"><i class="icon-newspaper-o"></i> Articles & Pages</a>
                    <ul>
                    <li><a href="'.admin_url('posts').'"><i class="icon-file-word-o"></i>Articles</a> 
                    <li><a href="'.admin_url('create-post').'"><i class="icon-edit"></i>New Article</a> 
                    <li><a href="'.admin_url('pch').'"><i class="icon-navicon"></i>Categories</a> 
					<li><a href="'.admin_url('create-pch').'"><i class="icon-plus"></i>New Category</a> 					  
                    <li><a href="'.admin_url('pages').'"><i class="icon-file-text-o"></i>Pages</a> 
                    <li><a href="'.admin_url('create-page').'"><i class="icon-plus"></i>New Page</a>             
                 
				   </ul>
                  </li>
               
				<li class="LiHead">
                    <a  href="#"><i class="icon-money"></i> Advertising</a>
                  <ul>
					<li><a href="'.admin_url('videoads').'"><i class="icon-play-circle"></i>Player Overlays</a>
				
                    <li><a href="'.admin_url('ads').'"><i class="icon-edit"></i>Site Ads</a> 
				  
                     		'.apply_filters('filter-ads-menu',false).'		  
                   </ul>
                  </li>
               
                 <li class="LiHead"><a href="'.admin_url('comments').'"><i class="icon-comments-o"></i> Comments</a>
                  </li>
                                    <li><a href="'.admin_url('reports').'"><i class="icon-flag"></i> Reports</a>
                  </li>
                  <li class="LiHead"><a href="'.admin_url('activity').'"><i class="icon-bell-o"></i> Activity</a>
                  </li>
				  <li class="LiHead"><a href="'.admin_url('alog').'"><i class="icon-flask"></i> Ffmpeg Logs</a>
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
$user = $db->get_row("select id,name,avatar from ".DB_PREFIX."users where id = ".$id." and group_id > 1");
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
$videos = $db->get_results("Select id,token,source from ".DB_PREFIX."videos where user_id ='".$id."' limit 0,10000000");
if($videos) {
foreach ($videos as $re) {
if($re->id == "up") {
/* Get list of video files attached */
$pattern = "{*".$re->token."*}";
$folder = ABSPATH.'/'.get_option('mediafolder','media').'/';
$vl = glob($folder.$pattern, GLOB_BRACE);
foreach($vl as $videocheck) {
remove_file($videocheck);
}
}	
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