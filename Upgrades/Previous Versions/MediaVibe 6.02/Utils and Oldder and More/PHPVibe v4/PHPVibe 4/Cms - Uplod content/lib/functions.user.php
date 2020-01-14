<?php
function is_user( ) {
return (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) > 0);
}
function get_ip() {		
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}
function user_id( ) {
if(is_user( )){
return intval($_SESSION['user_id']);
}
}
function user_name( ) {
return $_SESSION['name'];
}
function LastOnline() {
global $db;
if(is_user( )){
$db->query ("UPDATE  ".DB_PREFIX."users SET lastNoty=current_timestamp WHERE id ='" . sanitize_int(user_id( )) . "'");
}
}
function user_noty() {
global $db;
if(is_user( )){
$x = $db->get_row("SELECT lastNoty from  ".DB_PREFIX."users WHERE id ='" . sanitize_int(user_id( )) . "'");
if($x) {
return 	$x->lastNoty;
}
}
}
function my_profile() {
return profile_url(user_id( ), user_name( ));
}
function user_un( ) {
return $_SESSION['username'];
}
function user_avatar( ) {
return thumb_fix($_SESSION['avatar']);
}
function user_group() {
if (is_user( )) {
$gr = isset($_SESSION['group']) ? intval($_SESSION['group']) : 1 ;
return $gr;
} else {
return false;
}
}
function is_admin() {
global $db;
if (!is_user() || user_group() <> 1) {
return false;
} else {
$check = $db->get_row("SELECT group_id from ".DB_PREFIX."users WHERE id='".user_id()."'");
if($check && ($check->group_id == 1)) {
return true;
} else {
return false;
}
}
}
function user_videos($uid = null) {
global $db;
if(is_null($uid)) {$uid = user_id();}
if($uid && !is_null($uid)) {
$check = $db->get_results("SELECT DISTINCT id from ".DB_PREFIX."videos WHERE user_id='".$uid."' limit 0,1000000");
$videos = "";
if($check) {
foreach ($check as $ch) { $videos .= $ch->id.",";	 }
}
return $videos;
}
}
function user_subscriptions($uid = null) {
global $db;
if(is_null($uid)) {$uid = user_id();}
if($uid && !is_null($uid)) {
$check = $db->get_results("SELECT DISTINCT fid from ".DB_PREFIX."users_friends WHERE uid='".$uid."' limit 0,1000000");
$subscriptions = "";
if($check) {
foreach ($check as $ch) { $subscriptions .= $ch->fid.",";	 }
}
return $subscriptions;
}
}
function user_likes($uid = null) {
global $db;
if(is_null($uid)) {$uid = user_id();}
if($uid && !is_null($uid)) {
$check = $db->get_results("SELECT DISTINCT vid from ".DB_PREFIX."likes WHERE uid='".$uid."' limit 0,1000000");
$liked = "";
if($check) {
foreach ($check as $ch) { $liked .= $ch->vid.",";	 }
}
return $liked;
}
}
function uLikes($uid = null){
if(is_null($uid)) {$uid = user_id();}
$likes = (isset( $_SESSION['ulikes']))? $_SESSION['ulikes'] : user_likes($uid);
$_SESSION['ulikes'] = $likes;
return $likes;
}
function is_liked($id =null){
global $video;
if(is_null($id) && isset($video) && isset($video->id)) {$id = $video->id;}
if(is_user() && $id && !is_null($id)) {
$likes_list = (array) explode(',', uLikes());
return in_array($id, $likes_list);
} else {
return false;
}
}
function user_history($uid = null) {
global $db;
if(is_null($uid)) {$uid = user_id();}
if($uid && !is_null($uid)) {
$check = $db->get_results("SELECT DISTINCT object as vid from ".DB_PREFIX."activity WHERE user='".$uid."' and type = '3' limit 0,1000000");
$watched = "";
if($check) {
foreach ($check as $ch) { $watched .= $ch->vid.",";	 }
}
return $watched;
}
}
function uHistory($uid = null){
if(is_null($uid)) {$uid = user_id();}
$history = (isset( $_SESSION['uhistory']))? $_SESSION['uhistory'] : user_history($uid);
$_SESSION['uhistory'] = $history;
return $history;
}
function is_watched($id =null){
global $video;
if(is_null($id) && isset($video) && isset($video->id)) {$id = $video->id;}
if($id && !is_null($id)) {
$watched_list = (array) explode(',', uHistory());
return in_array($id, $watched_list);
} else {
return false;
}
}
function watched($id = null) {
global $video, $db;
if(is_null($id) && isset($video) && isset($video->id)) {$id = $video->id;}
if($id && !is_null($id)) {
if(!is_watched($id)) {
$_SESSION['uhistory'] .= $id.",";
if(is_user()) {
add_activity('3', $id);
//* Add to history playlist *//
$db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `video_id`) VALUES ('".history_playlist()."', '".$id."')");
}
}
}
}
function history_playlist($uid = null) {
global $db;
if(isset($_SESSION['history_id'])) {
return $_SESSION['history_id'];
}
if(is_null($uid)) {$uid = user_id();}
$plays = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".$uid."' and picture like '[history]'");
if($plays && isset($plays->id) && !nullval($plays->id)) {
return $plays->id;
} else {
return build_history($uid);
}
}
function build_history($uid) {
global $db;
$db->query("INSERT INTO ".DB_PREFIX."playlists (`owner`, `title`, `picture`, `description`) VALUES ('".$uid."','"._lang('Watched')."', '[history]' , '"._('Watched videos')."')");
$last = $db->insert_id;
$hist = uHistory($uid);
$ha = array();
if(!nullval($hist) && !nullval($hist)){
$ha = explode(",",$hist);
foreach ($ha as $v) {
if(!nullval($v)) {
$db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `video_id`) VALUES ('".intval($last)."', '".$v."')");
}
}
}
$_SESSION['history_id'] = $last;
return $last;
}
function likes_playlist($uid = null) {
global $db;
if(isset($_SESSION['likes_id'])) {
return $_SESSION['likes_id'];
}
if(is_null($uid)) {$uid = user_id();}
$plays = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".$uid."' and picture like '[likes]'");
if($plays && isset($plays->id) && !nullval($plays->id)) {
return $plays->id;
} else {
return build_likes($uid);
}
}
function build_likes($uid) {
global $db;
$db->query("INSERT INTO ".DB_PREFIX."playlists (`owner`, `title`, `picture`, `description`) VALUES ('".$uid."','"._lang('Liked videos')."', '[likes]' , '"._('Watch liked videos')."')");
$last = $db->insert_id;
$hist = uLikes($uid);
$ha = array();
if(!nullval($hist) && !nullval($hist)){
$ha = explode(",",$hist);
foreach ($ha as $v) {
if(!nullval($v)) {
$db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `video_id`) VALUES ('".intval($last)."', '".$v."')");
}
}
}
$_SESSION['likes_id'] = $last;
return $last;
}
function later_playlist($uid = null) {
global $db;
if(isset($_SESSION['later_id'])) {
return $_SESSION['later_id'];
}
if(is_null($uid)) {$uid = user_id();}
$plays = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".$uid."' and picture like '[later]'");
if($plays && isset($plays->id) && !nullval($plays->id)) {
return $plays->id;
} else {
$db->query("INSERT INTO ".DB_PREFIX."playlists (`owner`, `title`, `picture`, `description`) VALUES ('".$uid."','"._lang('Watch later')."', '[later]' , '"._('Watch later')."')");
$_SESSION['later_id'] = $last;
$last = $db->insert_id;
return $last;
}
}
function is_moderator(){
global $db;
if (!is_user() || user_group() > 2 ) {
return false;
} else {
$check = $db->get_row("SELECT group_id from ".DB_PREFIX."users WHERE id='".user_id()."'");
if($check && ($check->group_id < 3)) {
return true;
} else {
return false;
}
}
}
function validate_session() {
if (isset($_SESSION['HTTP_USER_AGENT'])) {
if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
user::clearSessionData();
exit;
}
}
}
function authByCookie() {	
if (!is_user() && isset($_COOKIE[COOKIEKEY]))
{
user::LoginbyPass($_COOKIE[COOKIEKEY]);
}
}
class user{
public static function encrypt($text, $salt)
{
if ( function_exists('mcrypt_encrypt') ) {
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB), MCRYPT_RAND);
return strrev($iv) . '@@' .
mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_CFB, $iv);
} else {
return $text;
}
}
public static function decrypt($text, $salt)
{
if ( function_exists('mcrypt_encrypt') ) {
list($iv, $data) = explode('@@', $text);
return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, $data, MCRYPT_MODE_CFB, strrev($iv)));
} else {
return $text;
}
}
public static function checkUser($userData) {
global $db;
/* Uncomment below for multiple logins to same account */
/* 
if(isset($userData['email']) && !nullval($userData['email'])) {
if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL) === false) {
if(user::CheckMail($userData['email']) > 0)	{
$result = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE email ='" . toDb($userData['email']) . "'");
return 	$result;
}
}
}*/
switch ($userData['type']) {
case "twitter":
if(!nullval($userData['oauth_token'])) {
$result = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE oauth_token ='" . $userData['oauth_token'] . "'");
if($result) {
return 	$result;
}  
} else {
die(_lang('Error. Please go back'));
}
break;
case "google":
if(!nullval($userData['email']) || !nullval($userData['gid'])) {
if(!nullval($userData['gid'])) {
$result = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE type ='google' and gid ='" . $userData['gid'] . "'");
}
return 	$result;
}
else {
die(_lang('Error. Please go back'));
}
break;
case "facebook":
if(!nullval($userData['fid']) && !nullval($userData['email'])) {
$result = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE type='facebook' and fid ='" . $userData['fid'] . "'");
return 	$result;
} else {
die(_lang('Error. Please go back'));
}
break;
}
}
public static function CheckMail($mail) {
global $db;
$result = $db->get_row("SELECT count(*) as dup FROM ".DB_PREFIX."users WHERE email ='" . toDb($mail) . "'");
if($result) {
return $result->dup;
}
return 0;
}
public static function checkUnique($field, $value) {
global $db;
$result = $db->get_var("SELECT count(*) FROM ".DB_PREFIX."users WHERE ".$field." ='" . $value . "'");
if($result && ($result > 0 )) {
return false;
} else {return true;}
}
public static function generateRandomNumber($length = 9) {
$random= "";
srand((double)microtime()*1000000);
$data = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$data .= "abcdefghijklmnopqrstuvwxyz";
$data .= "0123456789";
for($i = 0; $i < $length; $i++) {
$random .= substr($data, (rand()%(strlen($data))), 1);
}
$tk = md5($random);
if (user::checkUnique('pass', $tk)) {
return $tk;
} else {
$tk= user::generateRandomNumber();
}
}
public static function AddUser($userData) {
global $db;
$pass = user::generateRandomNumber();
//avoid not set issues
if(!isset($userData['username']) || nullval($userData['username'])) { $userData['username'] = nice_url($userData['name']); }
if (!user::checkUnique('username', $userData['username'])) { $userData['username'] = ''; }
if(!isset($userData['avatar']) || nullval($userData['avatar'])) {$userData['avatar'] = 'uploads/noimage.png';}
if(!isset($userData['email']) || nullval($userData['email'])) {$userData['email'] = '';}
if(!isset($userData['gid']) || nullval($userData['gid'])) {$userData['gid'] = '';}
if(!isset($userData['fid']) || nullval($userData['fid'])) {$userData['fid'] = '';}
if(!isset($userData['oauth_token']) || nullval($userData['oauth_token'])) {$userData['oauth_token'] = '';}
if(!isset($userData['password']) || nullval($userData['password'])) {$userData['password'] = '';}
if(!isset($userData['local']) || nullval($userData['local'])) {$userData['local'] = '';}
if(!isset($userData['country']) || nullval($userData['country'])) {$userData['country'] = '';}
if(!isset($userData['bio']) || nullval($userData['bio'])) {$userData['bio'] = '';}
//insert to db
$sql = "INSERT INTO ".DB_PREFIX."users (name,username,email,type,lastlogin,date_registered,gid,fid,oauth_token,avatar,local,country,group_id,pass,password,bio)"
. " VALUES ('" . toDb($userData['name']) . "','" . toDb($userData['username']) . "','" . esc_attr($userData['email']) . "','" . $userData['type'] . "', now(), now(), '".$userData['gid']."', '".$userData['fid']."', '".$userData['oauth_token']."', '".$userData['avatar']."', '".toDb($userData['local'])."', '".toDb($userData['country'])."', '4', '".$pass."','".toDb($userData['password'])."', '".toDb($userData['bio'])."')";
$db->query($sql);
$tid = user::checkUser($userData);
return $tid;
}
public static function loginbymail($mail, $pass=null) {
global $db;
if(is_null($pass)) {
$result = $db->get_row("SELECT id FROM ".DB_PREFIX."users WHERE email ='" . toDb($mail) . "'");
}else {
$result = $db->get_row("SELECT id FROM ".DB_PREFIX."users WHERE email ='" . toDb($mail) . "' and password = '".sha1($pass)."'");
}
if ($result && $result->id) {
user::LoginUser($result->id);
return true;
}
return false;
}
public static function LoginUser($id) {
global $db;
if($id && ($id > 0)) {
user::LastLogin($id);
$result = $db->get_row ("SELECT * FROM ".DB_PREFIX."users WHERE id ='" . sanitize_int($id) . "'");
$new_pass = user::generateRandomNumber();
user::ChangePass($id, $new_pass);
user::setSessionData('vibe_user',$result,$new_pass);
}
}
public static function ChangePass($id, $pass) {
global $db;
$db->query ("UPDATE  ".DB_PREFIX."users SET pass='".$pass."' WHERE id ='" . sanitize_int($id) . "'");
}
public static function LastLogin($id) {
global $db;
$db->query ("UPDATE  ".DB_PREFIX."users SET lastlogin=now() WHERE id ='" . sanitize_int($id) . "'");
}
public static function LoginbyPass($full) {	
global $db;
if($full && !nullval($full)){
$dec = user::decrypt($full, SECRETSALT);
if($dec && !nullval($dec)){
@list($id, $pass, $ip) = @explode(COOKIESPLIT, $dec );
if(isset($id) && isset($pass) && ($id > 0) && !nullval($pass) && !nullval($ip)) {
$id = intval($id);
if(($ip == get_ip()) && intval($id) > 0) {
$result = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE id ='" . sanitize_int($id). "' and pass ='" .toDb($pass) . "'");
if($result && !nullval($result)) {
user::LoginUser($result);
}
}
}
}
}
}
public static function RefreshUser($id) {
user::clearSessionData();
user::LoginUser($id);
}
public static function Update($field, $value,$id = null) {
global $db;
if(!is_moderator() || is_null($id)) { $id = user_id();}
if ($field && $value) {
$db->query ("UPDATE  ".DB_PREFIX."users SET ".$field."='".toDb($value)."' WHERE id ='" . sanitize_int($id) . "'");
}
}
public static function setSessionData($key, $val, $np =null) {
global $db;
if (!is_array($val)) { 		$val = user::obj_to_array($val);	 		}
if (function_exists('session_regenerate_id')) {	@session_regenerate_id(true);   }
$_SESSION['logintype']      = toDb($val["type"]);
$_SESSION['name']       = toDb($val["name"]);
$_SESSION['group']       = intval($val["group_id"]);
$_SESSION['username']       = toDb($val["username"]);
$_SESSION['user_id']         = intval($val["id"]);
$_SESSION['avatar']         = toDb($val["avatar"]);
if(!is_null($np)) {
$_SESSION['pass']         = $np; } else { $_SESSION['pass']         = toDb($val["pass"]); }                   $_SESSION['usergroup']      = intval($val["group_id"]);
$_SESSION['token']          = md5(uniqid(rand(), TRUE));
$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
$_SESSION['ulikes'] = uLikes(intval($val["id"]));
$_SESSION['uhistory'] = uHistory(intval($val["id"]));
$_SESSION['likes_id']         = likes_playlist(intval($val["id"]));
$_SESSION['history_id']       = history_playlist(intval($val["id"]));
$_SESSION['later_id']       = later_playlist(intval($val["id"]));
setcookie(COOKIEKEY, user::encrypt($_SESSION['user_id'].COOKIESPLIT.$_SESSION['pass'].COOKIESPLIT.get_ip(), SECRETSALT), time() + 60 * 60 * 24 * 5,'/', cookiedomain());
}
public static function obj_to_array($data)
{
if (is_array($data) || is_object($data))
{
$result = array();
foreach ($data as $key => $value)
{
$result[$key] = user::obj_to_array($value);
}
return $result;
}
return $data;
}
public static function clearSessionData() {
$_SESSION = array();
session_destroy();
setcookie(COOKIEKEY, '', -3600,'/', cookiedomain());
if (nullval($_SESSION)) {
session_start();
}
}
public static function getDataFromUrl($url) {
$ch = curl_init();
$timeout = 15;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
}
?>