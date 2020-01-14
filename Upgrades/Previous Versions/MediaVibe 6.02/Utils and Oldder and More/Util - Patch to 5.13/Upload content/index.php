<?php  error_reporting(E_ERROR); 
// Degugging?
$sttime = microtime(true); 
//Check if installed
if(!is_readable('vibe_config.php') || is_readable('hold')){
echo '<div style="padding:10% 20%; display:block; color:#fff; background:#ff604f"><h1>Hold on!</h1>';
echo '<h3> The configuration file needs editing OR/AND the "hold" file exists on your server! </h3><br />';
echo '<a href="setup/index.php"><h2>RUN PHPVibe\'s SETUP</h2></a></strong>';
echo '</div>';
die();
}
//Check session start
if (!isset($_SESSION)) { session_start(); }
/*** Start static serving from cache ***/
/** Static pages configuration **/
define('vSTATIC_FOLD',	'/cache/html/');
define('vSTATIC_TTL', 7200); /* Expiry time in seconds */
/* 1 day = 86400; 1 hour = 3600; */
define('vSTATIC_HASH',	'sha1');
/* Kill static cache for users */
if(isset($_SESSION['user_id']) || isset($_GET['action'])) {
$killcache = true;
}
/* Serve static pages for visitors */
if (!isset($killcache)) {
$url = preg_replace("/[^a-z0-9.]+/i","-",$_SERVER['REQUEST_URI']);
$a = (empty($url) || ($url === "") || is_null($url) || ($url === "index.php") ) ? "home" : $url;
/* Exclude specific pages */
if((strpos($a,'register') == false) && (strpos($a,'dashboard') == false) && (strpos($a,'login') == false) && (strpos($a,'moderator') == false) && (strpos($a,'setup') == false) && !isset($_GET['clang'])) {
/* Define this path here and ask for class */
$cInc = str_replace( '\\', '/',  dirname( __FILE__ ) );
require_once( $cInc.'/lib/fullcache.php' );
/* Create cache token for this page */
$token = (isset($_SESSION['phpvibe-language'])) ? $a.$_SESSION['phpvibe-language'] : $a;
/* Run static caching and serving */
FullCache::Encode($token);
FullCache::Live();
}
}
/** End static serving from cache **/
//Vital file include
require_once("load.php");
ob_start();
// Login, maybe?
if (!is_user()) {
    //action = login, logout ; type = twitter, facebook, google
    if (!empty($_GET['action']) && $_GET['action'] == "login") {
        switch ($_GET['type']) {
            case 'facebook':
			if(get_option('allowfb') == 1 ) {
  require_once( INC.'/facebook/autoload.php' );
  $fb = new Facebook\Facebook([
  'app_id'  => Fb_Key,
  'app_secret' => Fb_Secret,
  'default_graph_version' => 'v2.8',
 ]);
 $helper = $fb->getRedirectLoginHelper();
$permissions = explode (",", $conf_facebook['permissions']); // Optional permissions
$facebookLoginUrl = $helper->getLoginUrl($conf_facebook['redirect_uri'], $permissions);
//Send user to login
redirect($facebookLoginUrl);
			}	
                break;
            case 'google':
			if(get_option('allowg') == 1 ) {
                //Initialize google login
                
				require_once(INC.'/google/Google/Client.php');
				
				$client = new Google_Client();
$client->setClientId(trim(get_option('GClientID')));
$client->setClientSecret(trim(get_option('GClientSecret')));
$client->setRedirectUri($conf_google['return_url']);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
$authUrl = $client->createAuthUrl();

                if (!empty($authUrl)) {
                        
                       redirect($authUrl);
                }
             } 
			  break;
        
            default:
                //If any login system found, warn user
                echo _lang('Invalid Login system');
        }
    }
} else {
    if (!empty($_GET['action']) && $_GET['action'] == "logout") {
        //If action is logout, kill sessions
        user::clearSessionData();
        //var_dump($_COOKIE);exit;
       redirect(site_url()."index.php");
    }
}

// Let's start the site
//$page = com();
$id_pos = null;
$router = new Router();
if(!nullval(get_option('SiteBasePath',null))) {
$router->setBasePath('/'.get_option('SiteBasePath'));
}
do_action('VibePermalinks');
$router->map('/', 'home', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map(get_option('profile-seo-url','/profile/:name/:id/:section'), 'profile', array('methods' => 'GET,PUT,POST', 'filters' => array('id' => '(\d+)','section' => '(.*)')));

$router->map('/'.videos.'/:section', 'videolist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/images/:section', 'imageslist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/music/:section', 'musiclist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('channel-seo-url','/channel/:name/:id/:section'), 'category', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map('/playlist/:name/:id/:section', 'playlist', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map(get_option('page-seo-url','/read/:name/:id'), 'page', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/'.me.':section', 'manager', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.blog, 'blog', array('methods' => 'GET'));
$router->map('/'.members.'/:section', 'members', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.playlists.'/:section', 'playlists', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.blogcat.'/:name/:id/:section', 'blogcat', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('article-seo-url','/article/:name/:id'), 'post', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/forward/:section',  'forward', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/msg/:section',  'msg', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/conversation/:id',  'conversation', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/login/:section', 'login',  array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/register/:section', 'register', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.buzz.'/:section', 'buzz', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.show.'/:section', 'search', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/api/:section', 'api', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/embed/:section', 'embed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/feed/:section', 'feed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/share/:section', 'share', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upimage.'/:section', 'addimage', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upmusic.'/:section', 'addmusic', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.add.'/:section', 'add', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/dashboard/:section', 'dashboard', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$vxurl = get_option('video-seo-url','/video/:id/:name');
if (strpos($vxurl,':name') == false) {
/* Fix for arabic/other languages */	
$router->map($vxurl, 'video', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
} else {
$router->map($vxurl, 'video', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','name' => '(.*)')));
}
$imgurl = get_option('image-seo-url','/image/:id/:name');
if (strpos($imgurl,':name') == false) {
/* Fix for arabic/other languages */	
$router->map($imgurl, 'image', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
} else {
$router->map($imgurl, 'image', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','name' => '(.*)')));
}
//Match
$route = $router->matchCurrentRequest();
//end routing
/* include the theme functions / filters */
//Global tpl
if($route) {	$page = $route->getTarget();  }
include_once(TPL.'/tpl.globals.php');
//If offline
if(!is_admin() && (get_option('site-offline', 0) == 1 )) { 
layout('offline'); 
exit(); 
}
//Include com
 if($route) {	 
include_once(ABSPATH."/com/com_".$route->getTarget().".php");	
 } else {
include_once(ABSPATH."/com/com_404.php");	 
 }

//end sitewide
//Debugging 
ob_end_flush();

//if(is_admin()) {
//echo "<pre class=\"footerdebug\">Time Elapsed: ".(microtime(true) - $sttime)."s</pre> <br />";
//}

//That's all folks!
?>