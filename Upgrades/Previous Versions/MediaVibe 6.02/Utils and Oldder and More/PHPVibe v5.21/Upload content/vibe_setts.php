<?php /** Arrays with options for logins **/
$conf_twitter = array();
$conf_facebook = array();
$conf_google = array();
//Callback url for twitter
$conf_twitter['oauth_callback'] = SITE_URL.'callback.php?type=twitter';
//Callback url for facebook
$conf_facebook['redirect_uri'] = SITE_URL.'callback.php?type=facebook';
//Callback url for google
$conf_google['return_url'] = SITE_URL.'callback.php?type=google';
//Facebook callback fields
$conf_facebook['fields'] = 'id,name,email,first_name,gender,last_name,location,about';
//Facebook permissions(default values)
$conf_facebook['permissions'] = 'public_profile,email';


/* URL delimiter RULE for phpVibe */
define( 'url_split', '/' );

/* SEO url structure */
define( 'page', 'read' );
define( 'blog', 'blog' );
define( 'blogcat', 'articles' );
define( 'blogpost', 'article' );
define( 'embedcode', 'embed' );
define( 'video', 'video' );
define( 'videos', 'videos' );
define( 'channel', 'channel' );
define( 'channels', 'channels' );
define( 'playlist', 'playlist' );
define( 'playlists', 'lists' );
define( 'note', 'note' );
define( 'profile', 'profile' );
define( 'show', 'show' );
define( 'members', 'users' );
define( 'share', 'share' );
define( 'add', 'add-video' );
define( 'upmusic', 'add-music' );
define( 'upimage', 'add-image' );
define( 'subscriptions', 'subscriptions' );
define( 'manage', 'manage' );
define( 'me', 'me' );
define( 'buzz', 'activity' );
// Mini video seo excerpts
define( 'mostliked', 'most-liked' );
define( 'mostviewed', 'most-viewed' );
define( 'promoted', 'featured' );
define( 'browse', 'browse' );
define( 'mostcom', 'most-commented' );

?>