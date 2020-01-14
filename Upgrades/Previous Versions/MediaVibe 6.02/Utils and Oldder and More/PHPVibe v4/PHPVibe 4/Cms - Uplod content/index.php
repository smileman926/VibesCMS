<?php  error_reporting(E_ALL); 
// Degugging?
//$sttime = microtime(true); 
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

/* Cache it for visitors */
if (!isset($killcache)) {
$a = (empty($a)) ? preg_replace("/[^a-z0-9]+/","-",strtolower($_SERVER['REQUEST_URI'])) : "index.php";
if(!isset($_SESSION['user_id']) && !isset($_GET['action']) && (strpos($a,'register') == false) && (strpos($a,'login') == false) && (strpos($a,'moderator') == false) && (strpos($a,'setup') == false)) {
$cInc = str_replace( '\\', '/',  dirname( __FILE__ ) );
require_once( $cInc.'/lib/fullcache.php' );
$token = (isset($_SESSION['phpvibe-language'])) ? $a.$_SESSION['phpvibe-language'] : $a;
FullCache::Encode($token);
FullCache::Live();
}
}
/* End cache */
//Vital file include
require_once("load.php");
ob_start();
// Login, maybe?
if (!is_user()) {
    //action = login, logout ; type = twitter, facebook, google
    if (!empty($_GET['action']) && $_GET['action'] == "login") {
        switch ($_GET['type']) {
            case 'twitter':
			if(get_option('allowtw') == 1 ) {
                //Initialize twitter
				require_once( INC.'/twitter/EpiCurl.php' );
			    require_once( INC.'/twitter/EpiOAuth.php' );
                require_once( INC.'/twitter/EpiTwitter.php' );
                $twitterObj = new EpiTwitter(Tw_Key, Tw_Secret);
                //Get login url according to configurations you specified in configs.php
                $twitterLoginUrl = $twitterObj->getAuthenticateUrl(
                    null, array('oauth_callback' => $conf_twitter['oauth_callback']));
                redirect($twitterLoginUrl);
			}	
                break;
            case 'facebook':
			if(get_option('allowfb') == 1 ) {
                //Initialize facebook by using factory pattern over main class(SocialAuth)
				require_once( INC.'/fb/facebook.php' );
                $facebookObj = new Facebook(array(
  'appId'  => Fb_Key,
  'secret' => Fb_Secret,
));
                //Get login url according to configurations you specified in configs.php
                $facebookLoginUrl = $facebookObj->getLoginUrl(array('scope' => $conf_facebook['permissions'],
                                                                    'canvas' => 1,
                                                                    'fbconnect' => 0,
                                                                    'redirect_uri' => $conf_facebook['redirect_uri']));
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
$router->map('/'.channels.'/:section', 'channels', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('channel-seo-url','/channel/:name/:id/:section'), 'channel', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map('/playlist/:name/:id/:section', 'playlist', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map(get_option('page-seo-url','/read/:name/:id'), 'page', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/'.me.':section', 'manager', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.blog, 'blog', array('methods' => 'GET'));
$router->map('/'.members.'/:section', 'members', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.playlists.'/:section', 'playlists', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.blogcat.'/:name/:id/:section', 'blogcat', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('article-seo-url','/article/:name/:id'), 'post', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/forward/:section',  'forward', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/login/:section', 'login',  array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/register/:section', 'register', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.buzz.'/:section', 'buzz', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.show.'/:section', 'search', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/api/:section', 'api', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/embed/:section', 'embed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/feed/:section', 'feed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/share/:section', 'share', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upimage.'/:section', 'image', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upmusic.'/:section', 'music', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.add.'/:section', 'add', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/dashboard/:section', 'dashboard', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$vxurl = get_option('video-seo-url','/video/:id/:name');
if (strpos($vxurl,':name') == false) {
/* Fix for arabic/other languages */	
$router->map($vxurl, 'video', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
} else {
$router->map($vxurl, 'video', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','name' => '(.*)')));
}
//Match
$route = $router->matchCurrentRequest();
//end routing
/* include the theme functions / filters */
//Global tpl
if($route) {	$page = $route->getTarget();  }
include_once(TPL.'/tpl.globals.php');
//If offline
if(!is_admin() && (get_option('site-offline', 0) == 1 )) { layout('offline'); exit(); }
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