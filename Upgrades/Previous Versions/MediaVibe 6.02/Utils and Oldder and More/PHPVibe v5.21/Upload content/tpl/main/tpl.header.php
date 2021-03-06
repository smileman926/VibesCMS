<?php /* PHPVibe v5's header */
function header_add(){
global $page;
$head = '
<link rel="stylesheet" type="text/css" href="'.tpl().'styles/phpvibe.css" media="screen" />
<link href="'.tpl().'styles/bootstrap.custom.css" rel="stylesheet" />
<link rel="stylesheet" href="'.tpl().'styles/font-awesome/font-awesome.css"/>
<link rel="stylesheet" href="'.tpl().'styles/js/owl-carousel/owl.carousel.css"/>
<link rel="stylesheet" href="'.tpl().'styles/js/owl-carousel/owl.theme.css"/>
<link rel="stylesheet" href="'.site_url().'lib/players/ads.jplayer.css"/>
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway:300|Roboto:400,500,700&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" type="text/css" media="all" />';
if(not_empty(get_option('rtl_langs',''))) {
//Rtl	
$lg = @explode(",",get_option('rtl_langs'));
if(in_array(current_lang(),$lg)) {	
	$head .= '<link href="'.tpl().'styles/rtl.css" rel="stylesheet" />';
}
}
$head .= extra_css().'
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script>
if((typeof jQuery == "undefined") || !window.jQuery )
{
   var script = document.createElement("script");
   script.type = "text/javascript";
   script.src = "'.tpl().'styles/js/jquery.js";
   document.getElementsByTagName(\'head\')[0].appendChild(script);
}
</script>
';
$head .=players_js();

$head .= '</head>
<body class="body-'.$page.'">
'.top_nav().'
<div id="wrapper" class="'.wrapper_class().' haside">
<div class="row block page p-'.$page.'">
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
if(is_video()) {
global $video;
if(isset($video) && $video) {
$meta .= '
<meta property="og:image" content="'.thumb_fix($video->thumb).'" />
<meta property="og:description" content="'.seo_desc().'"/>
<meta property="og:title" content="'._html($video->title).'" />
<meta property="og:type" content="video.other" />
<meta itemprop="name" content="'._html($video->title).'">
<meta itemprop="description" content="'.seo_desc().'">
<meta itemprop="image" content="'.thumb_fix($video->thumb).'">
';
}
}
if(is_picture()) {
global $image;
if(isset($image) && $image) {
$meta .= ' 
<meta property="og:image" content="'.thumb_fix($image->thumb, true, 500, 'auto').'" />
<meta property="og:image" content="'.thumb_fix(str_replace('localimage',get_option('mediafolder','media'),$image->source), false).'" />
<meta property="og:description" content="'.seo_desc().'"/>
<meta property="og:title" content="'._html($image->title).'" />
<meta itemprop="name" content="'._html($image->title).'">
<meta itemprop="description" content="'.seo_desc().'">
<meta itemprop="image" content="'.thumb_fix(str_replace('localimage',get_option('mediafolder','media'),$image->source), false).'">
';
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

function top_nav(){
$nav = '';
$nav .= '
<div class="fixed-top">
<div class="row block" style="position:relative;">
<div class="logo-wrapper">';    
$nav .= '<a id="show-sidebar" href="javascript:void(0)" title="'._lang('Show sidebar').'"><i class="icon-bars"></i></a>
<a href="'.site_url().'" title="" class="logo">'.show_logo().'</a>
<br style="clear:both;"/>
</div>		
<div class="header">
<div class="searchWidget">
<form action="" method="get" id="searchform" onsubmit="location.href=\''.site_url().show.'/\' + encodeURIComponent(this.tag.value).replace(/%20/g, \'+\'); return false;">
<div class="form-group form-material floating">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-search"></i></span>
                    <div class="form-control-wrap">
                      <input type="text" class="form-control input-lg empty" name="tag" value ="">
                      <label class="floating-label">'._lang("Search media").'</label>
                    </div>
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">'._lang("Search").'</button>
                    </span>
                  </div>
                </div>
				</form>
</div>
<div id="suggest-results">
</div>
';
$nav .= '
<div class="user-quick"><a data-target="#search-now" data-toggle="modal" href="javascript:void(0)" class="btn btn-default btn-small" id="show-search"><i class="icon icon-search"></i></a>';
if(!is_user()) {
$nav .= '	
<a id="uploadNow" data-target="#login-now" data-toggle="modal" href="javascript:void(0)" class="btn btn-default btn-small" title="'._lang("Login to upload").'">					
<i class="icon icon-upload"></i> <span>'._lang("Upload").'</span>				
</a> 		
<a id="loginNow" data-target="#login-now" data-toggle="modal" href="javascript:void(0)" class="btn btn-default btn-small" original-title="'._lang("Login").'">					
<i class="icon icon-sign-in"></i>				
</a> 
</div>

';

} else {


if((get_option('upmenu') == 1) ||  is_moderator()) {
$nav .= '<a id="uplBtn" href="'.site_url().share.'" class="btn btn-default btn-small " title="'._lang('Upload video').'">	
<i class="icon-cloud-upload uqi"></i> <span>'._lang('Upload').'	</span>
</a>';	
}	
$nav .= '<a id="openusr" class="btn uav btn-small dropdown-toggle"  data-toggle="dropdown" href="#" aria-expanded="false"
data-animation="scale-up" role="button" title="'._lang('Dashboard').'">	
<img src="'.thumb_fix(user_avatar(), true, 35,35).'" /> 
</a>
<ul class="dropdown-menu dropdown-left" role="menu">
<li role="presentation"> <a href="'.site_url().'dashboard/"><i class="icon icon-hashtag"></i>'.user_name().'</a></li>
<li class="divider" role="presentation"></li>
<li role="presentation"><a href="'.profile_url(user_id(), user_name()).'"><i class="icon icon-street-view"></i>'._lang("My Channel").'</a></li>
<li class="my-buzz" role="presentation"><a href="'.site_url().'dashboard/"><i class="icon icon-film"></i> '. _lang('My Studio').'</a> </li>
<li role="presentation"><a href="'.site_url().'dashboard/&sk=edit"><i class="icon icon-cog"></i>'._lang("Settings").'</a></li>
<li class="my-inbox" role="presentation"><a href="'.site_url().'conversation/0/"><i class="icon icon-comment-o"></i> '. _lang('Inbox').'</a> </li>
<li class="divider" role="presentation"></li>';
if(is_admin()){
$nav .= '
<li role="presentation"><a href="'.ADMINCP.'"><i class="icon icon-user-secret"></i>'._lang("Administration").'</a></li>
<li class="divider" role="presentation"></li>
';	
}
$nav .= '<li role="presentation"><a href="'.site_url().'index.php?action=logout"><i class="icon icon-power-off"></i> '._lang("Logout").'</a></li>
</ul>
</div>
';
}
$nav .= '
</div>
</div>
</div>
';
return $nav;
}