<?php //Add your filters and actions here
function header_add(){
global $page;
$head = '
<link rel="stylesheet" type="text/css" href="'.tpl().'css/vibe.style.css" media="screen" />
<!-- Bootstrap -->
<link href="'.tpl().'css/bootstrap.css" rel="stylesheet" />
<link href="'.tpl().'css/responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="'.tpl().'css/plugins.css"/>
<link rel="stylesheet" href="'.tpl().'css/font-awesome.css"/>
<link rel="stylesheet" href="'.tpl().'/js/fluidbox/fluidbox.css"/>
<link rel="stylesheet" href="'.tpl().'js/owl-carousel/owl.carousel.css"/>
<link rel="stylesheet" href="'.tpl().'js/owl-carousel/owl.theme.css"/>
<link rel="stylesheet" href="'.site_url().'lib/players/ads.jplayer.css"/>
<link rel="stylesheet" id="lane-css"  href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,700|Roboto:400,500,700&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" type="text/css" media="all" />
'.extra_css().'
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
';
  $head .=players_js();
 
$head .= '</head>
<body class="touch-gesture body-'.$page.'" style="">
'.top_nav().'
<div id="wrapper" class="container haside">
<div class="row-fluid block page" style="position:relative;">
';
return $head;
}
function meta_add(){
$meta = '<!doctype html> 
<html prefix="og: http://ogp.me/ns#"> 
 <html dir="ltr" lang="en-US">  
<head>  
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<title>'.seo_title().'</title>
<meta charset="UTF-8">  
<meta name="viewport" content="width=device-width,  height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<base href="'.site_url().'" />  
<meta name="description" content="'.seo_desc().'">
<meta name="generator" content="PHPVibe" />
<link rel="alternate" type="application/rss+xml" title="'.get_option('site-logo-text').' '._lang('All Media Feed').'" href="'.site_url().'feed/" />
<link rel="alternate" type="application/rss+xml" title="'.get_option('site-logo-text').' '._lang('Video Feed').'" href="'.site_url().'feed&m=1" />
<link rel="alternate" type="application/rss+xml" title="'.get_option('site-logo-text').' '._lang('Music Feed').'" href="'.site_url().'feed&m=2" />
<link rel="alternate" type="application/rss+xml" title="'.get_option('site-logo-text').' '._lang('Images Feed').'" href="'.site_url().'feed&m=3" />
<link rel="canonical" href="'.canonical().'" />
<meta property="og:site_name" content="'.get_option('site-logo-text').'" />
<meta property="fb:app_id" content="'.Fb_Key.'">
<meta property="og:url" content="'.canonical().'" />
';
if(com() == video) {
global $video;
if(isset($video) && $video) {
$meta .= '
<meta property="og:image" content="'.thumb_fix($video->thumb).'" />
<meta property="og:description" content="'.seo_desc().'"/>
<meta property="og:title" content="'._html($video->title).'" />';
}
}
if(com() == profile) {
global $profile;
if(isset($profile) && $profile) {
$meta .= '
<meta property="og:image" content="'.thumb_fix($profile->avatar).'" />
<meta property="og:description" content="'.seo_desc().'"/>
<meta property="og:title" content="'._html($profile->name).'" />';
}
}
return $meta;
}

function extra_js() {
return apply_filter( 'filter_extrajs', false );
}
function extra_css() {
return apply_filter( 'filter_extracss', false );
}
function lang_menu() {
global $cachedb;
$row = $cachedb->get_results( "SELECT `lang_code`, `lang_name` FROM ".DB_PREFIX."languages LIMIT 0,100" );
$menu = ''; $cr = ''; 
if($row) {
$menu .= '<h4 class="li-heading">'._lang('Change language').'</h4><div class="sidebar-nav blc"><ul>';
foreach($row as $l) {
if($l->lang_code == current_lang()) {$cr = $l->lang_name; $ico = "on isActive";}	else {$ico = "off";}
$menu .= '<li><a href="'.canonical().'&clang='.$l->lang_code.'"><i class="icon-toggle-'.$ico.'"></i>'.$l->lang_name.'</a></li>';

}
$menu .= '</ul>';
$menu .= '</div>';
}
return $menu;
}
function top_nav(){
$nav = '';
$nav .= '
		<div class="fixed-top">
		<div class="row-fluid block" style="position:relative;">

		<div class="logo-wrapper">';
    
		$nav .= '	<a href="'.site_url().'" title="" class="logo">'.show_logo().'</a>
			<a id="show-sidebar" href="javascript:void(0)" title="'._lang('Show sidebar').'"><i class="icon-indent"></i></a>
		<br style="clear:both;"/>
		</div>		
		<div class="header">
		<div class="searchWidget hidden-phone visible-tablet visible-desktop">
            <form action="" method="get" id="searchform" onsubmit="location.href=\''.site_url().show.'/\' + encodeURIComponent(this.tag.value).replace(/%20/g, \'+\'); return false;">
                <input type="text" name="tag" id="suggest-videos" value="'._lang("Search videos").'" onfocus="if (this.value == \''._lang("Search videos").'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''._lang("Search videos").'\';}" />
             <button type="submit" class="btn btn-search pull-right"><i class="icon-search"></i></button>
			 </form>       
		</div>
		<div id="suggest-results">
		</div>
		
		';
	
		if(!is_user()) {
		$nav .= '	<div class="user-quick">
<a id="uploadNow" href="'.site_url().'login/" class="btn btn-default btn-small" original-title="Login">					
					'._lang("Upload").'				
					</a> 		
					<a id="loginNow" href="'.site_url().'login/" class="btn btn-primary btn-small" original-title="Login">					
					'._lang("Sign in").'				
					</a> 
		 </div>
		
		';
		
		} else {
if((get_option('upmenu') == 1) ||  is_moderator()) {		
	$nav .= '
<div class="user-quick">
<a id="uplBtn" href="'.site_url().share.'" class="btn btn-default btn-small " title="'._lang('Upload video').'">	
	<i class="icon-cloud-upload uqi"></i> <span>'._lang('Upload').'	</span>
	</a>			
<a id="openusr" href="'.site_url().'dashboard/" class="btn uav btn-small " title="'._lang('Dashboard').'">	
	<img src="'.thumb_fix(user_avatar(), true, 35,35).'" /> 
	</a>
</div>';
}
	
		}
		$nav .= '
		</div>
		</div>
		</div>
	

	';
	
return $nav;
}

function footer_add(){
$next = 'var next_time = \'0\';';

if(com() == video) {
global $video;
if((isset($video) && $video) && has_list()) {
$new = guess_next();
if(isset($new['link']) && !nullval($new['link'])) {
$next = 'var next_time = \''.intval($video->duration * 1000 + 1000).'\';';
$next .= 'var next_url = \''.$new['link'].'&list='._get('list').'\';';
}
}
}
$next .= 'var nv_lang = \''._lang("Next video starting soon").'\';';
$footer =  '
</div>
</div>
</div>
</div>
<a href="#" id="linkTop" class="backtotop"><i class="icon-double-angle-up"></i></a>
<div id="footer">
<div class="container">
<div class="row">
<div class="span2 footer-logo nomargin">
'.show_logo('footer').' 
</div>
<div class="span8 footer-content">
'.site_copy().'
</div>
</div>
</div>
</div>
<script type="text/javascript">
var site_url = \''.site_url().'\';
'.$next.'

</script>
<script type="text/javascript" src="'.tpl().'js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/phpvibe_plugins.js"></script>
<script type="text/javascript" src="'.tpl().'js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/phpvibe_forms.js"></script>
<script type="text/javascript" src="'.tpl().'js/nprogress.js"></script>
<script type="text/javascript" src="'.tpl().'/js/fluidbox/jquery.fluidbox.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/typeahead.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/jQuery.jQTubeUtil.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/jquery.autosize.js"></script>
<script type="text/javascript" src="'.tpl().'js/jquery.gritter.js"></script>
<script type="text/javascript" src="'.tpl().'js/eh.js"></script>
<script type="text/javascript" src="'.tpl().'js/jquery.emoticons.js"></script>
<script type="text/javascript" src="'.tpl().'js/owl-carousel/owl.carousel.min.js"></script>
<script type="text/javascript" src="'.tpl().'js/phpvibe_app.js"></script>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId='.Fb_Key.'";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>

'.extra_js().'
'._pjs(get_option('googleanalitycs')).'
</body>
</html>';

return $footer;
}
function u_k($nr){
if ($nr > 999 && $nr <= 999999) {
    $result = round($nr / 1000, 1). _lang('k');
} elseif ($nr > 999999) {
    $result = round($nr / 1000000, 1). _lang('m');
} else {
    $result = $nr;
}
return $result;
}
?>