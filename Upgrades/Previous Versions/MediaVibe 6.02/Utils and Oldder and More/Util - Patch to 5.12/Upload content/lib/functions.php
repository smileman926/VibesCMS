<?php /*!
* phpVibe v5
*
* Copyright Media Vibe Solutions
* http://www.phpvibe.com
* phpVibe IS NOT A FREE SOFTWARE
* If you have downloaded this CMS from a website other
* than www.phpvibe.com if you have received
* this CMS from someone who is not a representative of phpVibe, you are involved in an illegal activity.
* The phpVibe team takes actions against all unlincensed websites using Google, local authorities and 3rd party agencies.
* Designed and built exclusively for sale @ phpVibe.com & phpRevolution.com.
*/
// Global functions
//Site url
function site_url() {
$site_url = SITE_URL;	
$site_url .= (substr(SITE_URL, -1) == '/' ? '' : '/');	
return $site_url;
}
function redirect($url=null) {
if(!$url) { 	$url = site_url(); 	}
header('Location: '.$url);
exit();
}
//array isset
function _globalIsSet($arrayPostGet,$postGetList){
$flagValidation = true;
foreach ($postGetList as $testValue){
if (!(isset($arrayPostGet[$testValue]))){
$flagValidation = false;
}
}
return $flagValidation;
}
//returns current page
function this_page() {
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
//No negatives
if($page < 1) {$page = 1;}
return $page;
}
//return next page
function next_page(){
return this_page() + 1;
}
//query limit
function this_limit(){
$limit = 'LIMIT ' .(this_page() - 1) * bpp() .',' .bpp();
return $limit;
}
//query offset
function this_offset($nr){
$limit = 'LIMIT ' .(this_page() - 1) * $nr .',' .$nr;
return $limit;
}
//browse per page
function bpp() {
if(get_option('bpp') > 0) {
return get_option('bpp');
}
return 24;
}
//ajax call
function is_ajax_call() {
global $_GET;
return (isset($_GET['ajax']) || isset($_GET['lightbox'] ));
}
//check if value is null
function nullval($value){
if(is_null($value) || $value==""){
return true;  }
else { return false;
}
}
//Alias null
function is_empty($v){
return 	nullval($v);
}
//Not null
function not_empty($v){
return 	!nullval($v);
}
//global time ago
function time_ago($date,$granularity=2) {
if (nullval($date)) {
return '';
}
$periods         = array(_lang("second"), _lang("minute"), _lang("hour"), _lang("day"), _lang("week"), _lang("month"), _lang("year"), _lang("decade"));
$lengths         = array("60","60","24","7","4.35","12","10");
$now             = time();
$unix_date         = strtotime($date);
// check validity of date
if(empty($unix_date)) {
return $date;
}
// is it future date or past date
if($now > $unix_date) {
$difference     = $now - $unix_date;
$tense         = _lang("ago");
} else {
$difference     = $unix_date - $now;
$tense         = _lang("from now");
}
for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
$difference /= $lengths[$j];
}
$difference = round($difference);
if($difference != 1) {
$periods[$j].= _lang("s");
}
return $difference.' '._lang($periods[$j]).' '._lang($tense);
}
/**
* Read an option from DB (or from cache if available). Return value or $default if not found
*
*/
function get_option( $option_name, $default = false ) {
global $db, $all_options;
// Allow plugins to short-circuit options
$pre = apply_filter( 'shunt_option_'.$option_name, false );
if ( false !== $pre )
return $pre;
// If option not available already, get its value from the DB
if ( !isset( $all_options[$option_name] ) ) {
$option_name = escape( $option_name );
$row = $db->get_row( "SELECT `option_value` FROM ".DB_PREFIX."options WHERE `option_name` = '$option_name' LIMIT 1" );
if ( is_object( $row) ) { // Has to be get_row instead of get_var because of funkiness with 0, false, null values
$value = $row->option_value;
} else { // option does not exist, so we must cache its non-existence
$value = $default;
}
$all_options[ $option_name ] = maybe_unserialize( $value );
}
return apply_filter( 'get_option_'.$option_name, $all_options[$option_name] );
}
/**
* Read all options from DB at once
*
*/
function get_all_options() {
global $cachedb;
$vibe_opt = array();
// Allow plugins to short-circuit all options. (Note: regular plugins are loaded after all options)
$pre = apply_filter( 'shunt_all_options', false );
if ( false !== $pre )
return $pre;
$allopt = $cachedb->get_results( "SELECT `option_name`, `option_value` FROM  ".DB_PREFIX."options where autoload='yes'" );
foreach( (array)$allopt as $option ) {
$vibe_opt[$option->option_name] = maybe_unserialize( $option->option_value );
}
$vibe_opts = apply_filter( 'get_all_options', $vibe_opt );
return $vibe_opts;
}
/**
* Update (add if doesn't exist) an option to DB
*
*/
function update_option( $option_name, $newvalue ) {
global $db,$all_options;
$safe_option_name = escape( $option_name );
$oldvalue = get_option( $safe_option_name, false );
// If the new and old values are the same, no need to update.
if ( $newvalue === $oldvalue )
return false;
if ( false === $oldvalue ) {
add_option( $option_name, $newvalue );
return true;
}
$_newvalue = escape( maybe_serialize( $newvalue ) );
//do_action( 'update_option', $option_name, $oldvalue, $newvalue );
$db->query( "UPDATE  ".DB_PREFIX."options SET `option_value` = '$_newvalue' WHERE `option_name` = '$option_name'" );
if ( $db->rows_affected == 1 ) {
$all_options[ $option_name ] = $newvalue;
return true;
}
return false;
}
/**
* Add an option to the DB
*
*/
function add_option( $name, $value = '' ) {
global $db;
$safe_name = escape( $name );
// Make sure the option doesn't already exist
if ( false !== get_option( $safe_name ) )
return;
$_value = escape( maybe_serialize( $value ) );
//do_action( 'add_option', $safe_name, $_value );
$db->query( "INSERT INTO  ".DB_PREFIX."options (`option_name`, `option_value`) VALUES ('$name', '$_value')" );
return;
}
/**
* Delete an option from the DB
*
*/
function delete_option( $name ) {
global $db;
$name = escape( $name );
// Get the ID, if no ID then return
$option = $db->get_row( "SELECT option_id FROM  ".DB_PREFIX."options WHERE `option_name` = '$name'" );
if ( is_null( $option ) || !$option->option_id )
return false;
//do_action( 'delete_option', $option_name );
$db->query( "DELETE FROM  ".DB_PREFIX."options WHERE `option_name` = '$name'" );
return true;
}
// seo rewrite tag function
function nice_tag($tag){
$tag = str_replace(array('.','(',')','%','#','\'','"','@'),'',$tag);	
$tag = str_replace(array(' ','/','_'),'-',$tag);
$tag = str_replace(array('-----','----','---','--'),'-', $tag);
return strtolower($tag);
}
// seo rewrite function	
function nice_url($iniurl) {
$string ='';
// translate utf-8
$url = url_translate($iniurl);
// remove all chars
$url = preg_replace("/[^a-z0-9]+/","-",strtolower($url));
$test = str_replace('-','',$url);
if(nullval($test)) {
/* Fallback if empty */	
$url	= nice_tag($iniurl);
}
//remove doubled -
$url = str_replace(array('-----','----','---','--'),'-', $url);
return urlencode(strtolower($url));
}
function url_translate($string) {
$specialchars = array(
// Latin
'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
'ß' => 'ss',
'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
'ÿ' => 'y',
// Latin symbols
'©' => '(c)',
/* German */
'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue', 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
'ẞ' => 'SS',
// Greek
'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
'Ϋ' => 'Y',
'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
// Turkish
'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
// Russian
'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
'Я' => 'Ya',
'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
'я' => 'ya',
// Ukrainian
'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
// Czech
'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
'Ž' => 'Z',
'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
'ž' => 'z',
// Polish
'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
'Ż' => 'Z',
'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
'ż' => 'z',
// Latvian
'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
'š' => 's', 'ū' => 'u', 'ž' => 'z',
/* Lithuanian */
'ą' => 'a', 'č' => 'c', 'ę' => 'e', 'ė' => 'e', 'į' => 'i', 'š' => 's', 'ų' => 'u', 'ū' => 'u', 'ž' => 'z',
'Ą' => 'A', 'Č' => 'C', 'Ę' => 'E', 'Ė' => 'E', 'Į' => 'I', 'Š' => 'S', 'Ų' => 'U', 'Ū' => 'U', 'Ž' => 'Z',
/* Vietnamese */
'Á' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A', 'Ă' => 'A', 'Ắ' => 'A', 'Ằ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A', 'Â' => 'A', 'Ấ' => 'A', 'Ầ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a', 'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E', 'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e', 'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I', 'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O', 'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O', 'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U', 'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y', 'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
'Đ' => 'D', 'đ' => 'd',
/* Arabic */
'أ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'g', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd',
'ذ' => 'th', 'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't',
'ظ' => 'th', 'ع' => 'aa', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'k', 'ك' => 'k', 'ل' => 'l', 'م' => 'm',
'ن' => 'n', 'ه' => 'h', 'و' => 'o', 'ي' => 'y',
/* Serbian */
'ђ' => 'dj', 'ј' => 'j', 'љ' => 'lj', 'њ' => 'nj', 'ћ' => 'c', 'џ' => 'dz', 'đ' => 'dj',
'Ђ' => 'Dj', 'Ј' => 'j', 'Љ' => 'Lj', 'Њ' => 'Nj', 'Ћ' => 'C', 'Џ' => 'Dz', 'Đ' => 'Dj',
/* Azerbaijani */
'ç' => 'c', 'ə' => 'e', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
'Ç' => 'C', 'Ə' => 'E', 'Ğ' => 'G', 'İ' => 'I', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U'			
);
return strtr($string,$specialchars);
}
//return safe GET
function _get($val){
global $_GET;
if(isset($_GET[$val])) {
return esc_attr($_GET[$val]);
}
return null;
}
//return safe GET integer
function _get_int($val){
return intval(_get($val));
}
function t_copy($text){
if(function_exists('base64_decode') ) {
$text = $text.'<p class="small">'.base64_decode(get_option('softc')).' '.get_option('licto').'</p>';
} else {
$text = $text.'<p class="small">'._lang("Powered by ").' <a rel="nofollow" href="http://www.phpvibe.com" target="_blank" title="PHPVibe Video CMS">PHPVibe&trade;</a> '.get_option('licto').'</p>';
}
return $text;
}
//return safe POST
function _post($val){
global $_POST;
if(isset($_POST[$val])) {
return esc_attr($_POST[$val]);
}
return null;
}
//return safe POST integer
function _post_int($val){
return intval(_post($val));
}
//return percent
function percent($first, $num_total, $precision = 0) {
if ($num_total > 0){
$res = round( ($first / $num_total) * 100, $precision );
return $res;
} elseif($first > 0) {
return 100;
}
return 0;
}
//limit a string
function _cut($str,$nb=10) {
if (strlen($str) > $nb) {
if (extension_loaded('mbstring')) {
mb_internal_encoding("UTF-8");
$str = mb_substr($str, 0, $nb);
} else {
$str = substr($str, 0, $nb);
}
}
return $str;
}
//Language function
function current_lang() {
$cl = isset($_SESSION['phpvibe-language'])? $_SESSION['phpvibe-language'] : get_option('def_lang');
return $cl;
}
function lang_terms($lang = null){
global $db;
$lang = (is_null($lang)) ? current_lang() : $lang;
$all_terms  = get_language($lang);
if(is_null($all_terms) || !is_array($all_terms)) {
//Switch to english if
$all_terms  = get_language('en');
}
return $all_terms;
}
function _lang($txt) {
global $trans;
if (isset($trans[$txt])){
return _html($trans[$txt]);
} else {
lang_log($txt);
return $txt;
}
}
/**
* Log term in the DB
*
*/
function lang_log($txt) {
global $cachedb;
if($cachedb) {
$txt = escape($txt);
/* Check if term exists in matrix */
$check = $cachedb->get_row( "SELECT count(*) as nr FROM  ".DB_PREFIX."langs WHERE `term` = '$txt'" );
if ( !$check || ($check->nr < 1) ) {
//Insert term
$cachedb->query( "INSERT INTO  ".DB_PREFIX."langs (`term`) VALUES ('$txt')" );
}
}
}
/**
* Get language terms from the DB
*
*/
function get_language( $lang_code, $default = false ) {
global $cachedb;
$lang_code = escape( $lang_code );
$row = $cachedb->get_row( "SELECT `lang_terms` FROM ".DB_PREFIX."languages WHERE `lang_code` = '$lang_code' LIMIT 1" );
if ( is_object( $row) ) { // Has to be get_row instead of get_var because of funkiness with 0, false, null values
$value = $row->lang_terms;
} else { // language does not exist, so we must cache its non-existence
$value = $default;
}
return apply_filter( 'get_language_'.$lang_code,  json_decode( $value, true) );
}
/**
* Add an language to the DB
*
*/
function add_language( $code, $value = '' ) {
global $db;
$code = addslashes( $code );
$long_name = addslashes($value["language-name"]);
// Make sure the language doesn't already exist
$language = $db->get_row( "SELECT count(*) as nr FROM  ".DB_PREFIX."languages WHERE `lang_code` = '$code'" );
if ( $language) {
if ( $language->nr > 1 ) {
/* Language code already exists */	
$nx = $language->nr + 1;	
$code .= '-'.$nx;
}
}	
//$_value = escape( json_encode( $value ) );
$_value = addslashes(json_encode( $value )) ;
//do_action( 'add_language', $safe_name, $_value );
$db->query( "INSERT INTO  ".DB_PREFIX."languages (`lang_name`, `lang_code`, `lang_terms`) VALUES ('$long_name','$code', '$_value')" );

return;
}
/**
* Delete an language from the DB
*
*/
function delete_language( $name ) {
global $db;
$name = escape( $name );
// Get the ID, if no ID then return
$language = $db->get_row( "SELECT term_id FROM  ".DB_PREFIX."languages WHERE `lang_code` = '$name'" );
if ( is_null( $language ) || !$language->term_id )
return false;
//do_action( 'delete_language', $lang_code );
$db->query( "DELETE FROM  ".DB_PREFIX."languages WHERE `lang_code` = '$name'" );
return true;
}
/* end language */
//Common actions
function the_header(){
do_action('vibe_header');
}
function the_footer() {
do_action('vibe_footer');
}
function the_sidebar() {
if(is_admin() || (get_option('site-offline', 0) == 0 )) {
do_action('vibe_sidebar');
}
}
function right_sidebar() {
do_action('right_sidebar');
}
function vibe_headers () {
echo apply_filters('vibe_meta_filter', meta_add());
echo apply_filters('vibe_header_filter', header_add());
}
function vibe_footers() {
echo apply_filters('vibe_footer_filter', footer_add());
}
//Map the actions
add_action('vibe_header', 'vibe_headers', 1);
add_action('vibe_footer', 'vibe_footers', 1);
//sidebar
function the_side(){
global $db, $cachedb;
include_once(TPL.'/sidebar.php');
}
function right_side(){
global $db, $cachedb;
include_once(TPL.'/sidebar-right.php');
}
add_action('vibe_sidebar', 'the_side', 1);
add_action('right_sidebar', 'right_side', 1);
add_filter('tsitecopy', 't_copy');
function site_copy(){
return apply_filters('tsitecopy', get_option('site-copyright'));
}
//Video time func
function video_time($sec, $padHours = false) {
$hms = "";
// there are 3600 seconds in an hour, so if we
// divide total seconds by 3600 and throw away
// the remainder, we've got the number of hours
$hours = intval(intval($sec) / 3600);
if ($hours > 0):
// add to $hms, with a leading 0 if asked for
$hms .= ($padHours)
? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
: $hours. ':';
endif;
// dividing the total seconds by 60 will give us
// the number of minutes, but we're interested in
// minutes past the hour: to get that, we need to
// divide by 60 again and keep the remainder
$minutes = intval(($sec / 60) % 60);
// then add to $hms (with a leading 0 if needed)
$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
// seconds are simple - just divide the total
// seconds by 60 and keep the remainder
$seconds = intval($sec % 60);
// add to $hms, again with a leading 0 if needed
$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
return $hms;
}
function sef_url(){
$url = site_url();
if(substr($url, -1) == '/') {$url =rtrim($url, "/"); }
return $url;
}
// SEO Func support
function video_url($id, $title, $list=null){
$post = '';
if(!is_null($list)){ $post= '&list='.$list; }
$or = get_option('video-seo-url','/video/:id/:name');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or.$post;
return $url;
}
function image_url($id, $title, $list=null){
$or = get_option('image-seo-url','/image/:id/:name');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function profile_url($id, $title){
$or = get_option('profile-seo-url','/profile/:name/:id/:section');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function playlist_url($id, $title){
return site_url().playlist.url_split.nice_url($title).url_split.$id.'/';
}
function channel_url($id, $title){
$or = get_option('channel-seo-url','/channel/:name/:id/:section');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function bc_url($id, $title){
return site_url().blogcat.url_split.nice_url($title).url_split.$id.'/';
}
function note_url($id, $title=null){
if(!is_null($title)) {
return site_url().note.url_split.$id.url_split.nice_url($title).'/';
}
return site_url().note.url_split.$id.'/';
}
function list_url($part){
return site_url().videos.url_split.$part.'/';
}
function images_url($part){
return site_url().'images'.url_split.$part.'/';
}
function music_url($part){
return site_url().'music'.url_split.$part.'/';
}
function page_url($id, $title=null){
$or = get_option('page-seo-url','/read/:name/:id');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function article_url($id, $title=null){
$or = get_option('article-seo-url','/article/:name/:id');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
//Misc functions
function render_video($code) {
return htmlspecialchars_decode(specialchars_decode($code));
}
// Mini Layout func
function layout($part){
global $db, $video, $cachedb;
include_once(TPL.'/'.$part.'.php');
}
function tpl() {
return site_url().'tpl/'.THEME.'/';
}
// NSFW
function nsfilter() {
global $video,$image;
if(isset($video)) {
if($video->nsfw < 1){
return false;
}elseif(isset($_SESSION['nsfw']) && $_SESSION['nsfw'] > 0){
return false;
}else {
return true;
}
}elseif(isset($image)) {
if($image->nsfw < 1){
return false;
}elseif(isset($_SESSION['nsfw']) && $_SESSION['nsfw'] > 0){
return false;
}else {
return true;
}	
}
}
//SEO
function seo_title() {
return apply_filters( 'phpvibe_title', get_option('seo_title'));
}
function seo_desc() {
return apply_filters( 'phpvibe_desc', get_option('seo_desc'));
}
//Db count
function _count($table, $field=null, $sum=false){
global $db;
if($field && !$sum) {
$c = $db->get_row("SELECT count(".$field.") as nr FROM ".DB_PREFIX.$table);
} elseif ($field && $sum) {
$c = $db->get_row("SELECT sum(".$field.") as nr FROM ".DB_PREFIX.$table);
} else {
$c = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX.$table);
}
return number_format($c->nr, 0);
}
//Fb count
function _fb_count($name){
return '';
//Deprecated
}
// Thumb fix
function thumb_fix($thumb, $resize = false, $w=280, $h=180) {
if($thumb) {
if ((strpos($thumb, "http") === 0) || (strpos($thumb, "https") === 0) || (strpos($thumb, "www.") === 0)){
$thumb = str_replace("www.","",$thumb);
if(strpos($thumb, 'http') !== 0)  {
return 'http://' . $thumb;
} else {
return $thumb;
}
} elseif($resize) {
return site_url().'res.php?src='.$thumb.'&q=100&w='.$w.'&h='.$h;
}else {
return site_url().$thumb;
}
}
}
//Prettify tags
function pretty_tags($tags, $class='', $pre='', $post = ''){
$list ='';
$keywords_array = explode(',', $tags);
if (count($keywords_array) > 0){
foreach ($keywords_array as $keyword){
if (not_empty(trim($keyword))){
$k_url = site_url().show.'/'.nice_tag(trim($keyword)).'/';
$list .=  $pre.'<a class="'.$class.'" href="'.$k_url.'">'.$keyword.'</a>'.$post;
}
}
}
return $list;
}
//Prettify image tags
function pretty_imgtags($tags, $class='', $pre='', $post = ''){
$list ='';
$keywords_array = explode(',', $tags);
if (count($keywords_array) > 0){
foreach ($keywords_array as $keyword){
if (not_empty(trim($keyword))){
$k_url = images_url('tag').'&tag='.nice_tag(trim($keyword));
$list .=  $pre.'<a class="'.$class.'" href="'.$k_url.'">'.$keyword.'</a>'.$post;
}
}
}
return $list;
}
function has_list() {
return (!is_null(_get('list')) && ((intval(_get('list')) > 0) || (strpos(_get('list'), 'ums-') !== false) || (strpos(_get('list'), 'uvs-') !== false) ));
}
function isPost () {
return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
}
/* Categories navigator */
function the_nav($type=1) {
global $db, $cachedb;
include_once( INC.'/class.tree.php' );
$nav = '';
$tree = new Tree;
$categories = $cachedb->get_results("SELECT cat_id as id, child_of as ch, cat_name as name, picture FROM  ".DB_PREFIX."channels WHERE type='$type' order by cat_name ASC limit 0,1000");
if($categories) {
foreach ($categories as $cat) {
if($cat->ch < 1) {$cat->ch = null;}
$label = '
<a class="cats-img" href="'.channel_url($cat->id, $cat->name).'" title="'. _html($cat->name).'"><img src="'.thumb_fix($cat->picture, true, 25, 20).'"/></a>
<a class="cats-link" href="'.channel_url($cat->id, $cat->name).'" title="'. _html($cat->name).'">'. _html($cat->name).'</a>
';
$li_attr = '';
$tree->add_row($cat->id, $cat->ch, $li_attr, $label);
}
$nav .=$tree->generate_list();
}
return apply_filters('the_navigation' , $nav);
}
function subscribe_box($user, $btnc = null, $counter = true){
global $db;
echo '<div class="btn-group">';
if (!is_user()) {
//It's guest
$btnc = "btn btn-labled social-google-plus btn-sm nomargin subscriber";
echo '<a class="'.$btnc.'" href="javascript:showLogin()"><i class="icon icon-plus"></i>'._lang('Subscribe').'</a>';
} elseif ($user <> user_id()) {
//If it's not you
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."users_friends where uid ='".$user."' and fid='".user_id()."'");
if($check->nr < 1) {
//You're not subscribed
$btnc = "btn btn-labled social-google-plus btn-sm nomargin subscriber";
echo '<a id="subscribe" class="'.$btnc.' pv_tip" href="javascript:Subscribe('.$user.', 1)" title="'._lang('Click to add a subscription').'"><i class="icon icon-plus"></i>'._lang('Subscribe').'</a>';
} else {
//You are, but can unsubscribe
$btnc = "btn btn-default btn-sm nomargin subscriber";
echo '<a id="unsubscribe" class="'.$btnc.' pv_tip" href="javascript:Subscribe('.$user.', 3)" title="'._lang('Click to remove subscription').'"><i class="icon icon-check"></i>'._lang('Subscribed').'</a>';
}
} else {
//It's you
$btnc = "btn btn-default btn-sm nomargin subscriber";
echo '<a href="'.site_url().'dashboard/" class="'.$btnc.'"><i class="icon icon-cogs"></i>'._lang('Settings').'</a>';
}
if ($counter) { 
echo '<span class="kcounter btn btn-default btn-sm">'.get_subscribers($user).'</span>'; 
}

echo '</div>';
}
function get_subscribers($user) {
global $db, $cachedb;
$sub = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".$user."')");
return number_format($sub->nr);
}
function list_title($list) {
global $db;
/*for video header in list mode */
if(intval($list) > 0) {
$playlist = $db->get_row("SELECT title FROM ".DB_PREFIX."playlists where id = '".intval($list) ."' limit  0,1");
if($playlist) {
return strip_tags(_html($playlist->title));
}
} elseif(strpos($list, 'ums-') !== false) {
/* All music */	
$uid = intval(str_replace('ums-','',$list));
$playlist = $db->get_row("SELECT name FROM ".DB_PREFIX."users where id = '".intval($uid) ."' limit  0,1");	
if($playlist) {
return strip_tags(_html(_lang("All songs by ").$playlist->name));
}
}elseif(strpos($list, 'uvs-') !== false) {
/* All videos */	
$uid = intval(str_replace('uvs-','',$list));
$playlist = $db->get_row("SELECT name FROM ".DB_PREFIX."users where id = '".intval($uid) ."' limit  0,1");
if($playlist) {
return strip_tags(_html(_lang("All videos by ").$playlist->name));
}
}	
}

function canonical() {
global $canonical;
if(isset($canonical) && !empty($canonical)) {
return $canonical;
}
/*else try to build an url for menu's back step not to fail */
return selfURL();
}
function selfURL() {
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}
function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
/*Track  activity */
function add_activity($type, $obj, $extra ='') {
global $db;
if (is_user()&& $type && $obj)
{
$db->query("INSERT INTO ".DB_PREFIX."activity (`user`, `type`, `object`, `extra`) VALUES ('".user_id()."', '".toDb($type)."', '".toDb($obj)."', '".toDb($extra)."')");
do_action('add-activity');
}
}
function has_activity($type, $obj, $extra =''){
global $db;
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."activity where user ='".user_id()."' and type = '".toDb($type)."' and object = '".toDb($obj)."' and extra = '".toDb($extra)."'" );
return ($check->nr > 0);
}
function remove_activity($type, $obj, $extra =''){
global $db;
$db->query("delete from ".DB_PREFIX."activity where user ='".user_id()."' and type = '".toDb($type)."' and object = '".toDb($obj)."' and extra = '".toDb($extra)."'" );
do_action('remove-activity');
}
function default_content(){
/* Dummy function for default template
manipulated by filters */
$def = '';
return apply_filters('the_defaults' , $def);
}
function get_activity($done) {
global $db,$cachedb;
if($done){
do_action('get-activity');
$did = array();
$did["what"]  = '';
$did["content"]= '';
switch($done->type){
case 1:
$tran["like"] = _lang('liked ');
$tran["dislike"] = _lang('disliked ');
$class["like"] = "greenText";
$class["dislike"] = "redText";
$video = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] .= $tran[$done->extra].' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 68)).'</a>';
}
break;
case 2:
$video = $cachedb->get_row("SELECT title,id,thumb, description from ".DB_PREFIX."videos where id='".intval($done->object)."'");
$playlist = $db->get_row("SELECT title,id from ".DB_PREFIX."playlists where id='".intval($done->extra)."'");
if($video && $playlist) {
$did["what"] = _lang("added to ").' <a class="text-primary" href="'.playlist_url($playlist ->id , $playlist ->title).'" title="'._html($playlist ->title).'"><i class="icon-list-alt"></i> <strong>'._html(_cut($playlist ->title, 446)).'</strong></a>';
$did["content"] = '<a href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><i class="icon-film"></i> <strong>'._html(_cut($video->title, 266)).'</strong></a> ';
}
break;
case 3:
$video = $cachedb->get_row("SELECT title,id  from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("watched") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';
}
break;
case 4:
$video = $cachedb->get_row("SELECT title,id,description,thumb from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("shared").' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';
$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($video->description));
$description = _cut(trim($description),240);
$did["content"] = '
<div class="row">
<div class="col-md-4">
<div class="innerT">
<div class="text-center ">
<a href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" /></a>
</div>
</div>
</div>
<div class="col-md-8">
<div class="innerT">
<a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'"><h5 class="strong">'._html(_cut($video->title, 68)).'</h5></a>
<p>'.$description.'</p>
</div>
</div>
</div>';
}
break;
case 5:
$video = $cachedb->get_row("SELECT name,id,avatar from ".DB_PREFIX."users where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("subscribed to") .' <a class="text-primary" href="'.profile_url($video->id , $video->name).'" title="'._html($video->name).'">'._html(_cut($video->name, 246)).'</a>';
}
break;
case 6:
$video = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".intval($done->object)."'");
if($video) {
$did["what"] = _lang("commented on the video") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';;
}
break;
case 7:
$com = $cachedb->get_row("SELECT comment_text,object_id from ".DB_PREFIX."em_comments where id='".intval($done->object)."'");
$vid = intval(str_replace('video_','',$com->object_id));
$video = $db->get_row("SELECT title,id from ".DB_PREFIX."videos where id='".$vid."'");
if($video) {
$did["what"] = _lang("liked a comment on") .' <a class="text-primary" href="'.video_url($video->id , $video->title).'" title="'._html($video->title).'">'._html(_cut($video->title, 268)).'</a>';;
$did["content"] = '<div class="content-filled">'.$com->comment_text.'</div>';
}
break;
case 8:
$image = $cachedb->get_row("SELECT title,id,description,thumb from ".DB_PREFIX."images where id='".intval($done->object)."'");
if($image) {
$did["what"] = _lang("shared").' <a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'">'._html(_cut($image->title, 268)).'</a>';
$description = str_replace(array("\"","<br>","<br/>","<br />")," ",_html($image->description));
$description = _cut(trim($description),240);
$did["content"] = '
<div class="row">
<div class="col-md-4">
<div class="innerT">
<div class="text-center ">
<a href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'"><img src="'.thumb_fix($image->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" /></a>
</div>
</div>
</div>
<div class="col-md-8">
<div class="innerT">
<a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'"><h5 class="strong">'._html(_cut($image->title, 68)).'</h5></a>
<p>'.$description.'</p>
</div>
</div>
</div>';
}
break;
case 9:
$tran["like"] = _lang('liked ');
$tran["dislike"] = _lang('disliked ');
$class["like"] = "greenText";
$class["dislike"] = "redText";
$image = $cachedb->get_row("SELECT title,id from ".DB_PREFIX."images where id='".intval($done->object)."'");
if($image) {
$did["what"] .= $tran[$done->extra].' <a class="text-primary" href="'.image_url($image->id , $image->title).'" title="'._html($image->title).'">'._html(_cut($image->title, 68)).'</a>';
}
break;

}
return $did;
}
}
//Video processing
function unpublish_video($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '0' where id='".$id."'");
} else {
//make sure it's his video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '0' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function publish_video($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '1' where id='".$id."'");
} else {
//make sure it's his video
$db->query("UPDATE ".DB_PREFIX."videos SET pub = '1' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function delete_video($id) {
global $db;
if(intval($id) && is_moderator()){
$video = $db->get_row("SELECT * from ".DB_PREFIX."videos where id='".intval($id)."'");
if($video) {
if($video->embed || $video->remote) {
//delete imediatly if remote
$db->query("DELETE from ".DB_PREFIX."videos where id='".intval($id)."'");
} else {
//try to delete file to
$vid = new Vibe_Providers(10, 10);
$source = $vid->VideoProvider($video->source);
if(($source == "localimage") || ($source == "localfile") || ($source == "up") ) {
$path = ABSPATH.'/'.get_option('mediafolder').str_replace(array("localimage", "localfile"),array("", ""),$video->source);
//remove video file
remove_file($path);
// Remove qualities
$patternx = "{*".$video->token."*}";
$folderx = ABSPATH.'/'.get_option('mediafolder','media').'/';
$vl = glob($folderx.$patternx, GLOB_BRACE);
if($vl) {
foreach($vl as $videocheck) {
remove_file($videocheck);
}
}
//Remove thumb
$thumb = $video->thumb;
if($thumb && ($thumb != "uploads/noimage.png")&& ($thumb != "media/thumbs/xmp3.jpg")) {
$vurl = parse_url(trim($thumb, '/'));
if(!isset($vurl['scheme']) || $vurl['scheme'] !== 'http'){
$thumb = ABSPATH.'/'.$thumb;
//remove thumb
remove_file($thumb);
}
}
}
$db->query("DELETE from ".DB_PREFIX."videos where id='".intval($id)."'");
$db->query("DELETE from ".DB_PREFIX."playlist_data where video_id='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."activity where object='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."likes where vid='".intval($id)."'"); 
$db->query("DELETE from ".DB_PREFIX."em_comments where object_id='video_".intval($id)."'");
echo '<div class="msg-info">'.$video->title.' was removed.</div>';
}
}
}
}
function delete_image($id) {
global $db;
if(intval($id) && is_moderator()){
$image = $db->get_row("SELECT * from ".DB_PREFIX."images where id='".intval($id)."'");
if($image) {
//try to delete file to
//Remove thumb
$thumb = $image->thumb;
if($thumb && ($thumb != "uploads/noimage.png")&& ($thumb != "media/thumbs/xmp3.jpg")) {
$vurl = parse_url(trim($thumb, '/'));
if(!isset($vurl['scheme']) || $vurl['scheme'] !== 'http'){
$thumb = ABSPATH.'/'.$thumb;
//remove thumb
remove_file($thumb);
}
}
}
$db->query("DELETE from ".DB_PREFIX."images where id='".intval($id)."'");
$db->query("DELETE from ".DB_PREFIX."playlist_data where video_id='".intval($id)."' and playlist in (SELECT id from ".DB_PREFIX."playlists where ptype = '2')"); 
$db->query("DELETE from ".DB_PREFIX."em_comments where object_id='img_".intval($id)."'");
echo '<div class="msg-info">'.$image->title.' was removed.</div>';

}
}
//image processing
function unpublish_image($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '0' where id='".$id."'");
} else {
//make sure it's his image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '0' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function publish_image($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//can edit any image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '1' where id='".$id."'");
} else {
//make sure it's his image
$db->query("UPDATE ".DB_PREFIX."images SET pub = '1' where id='".$id."' and user_id ='".user_id()."'");
}
}
}
function remove_file($filename) {
if(is_moderator() && is_readable($filename)) {
chmod($filename, 0777);
if (unlink($filename)){
echo '<div class="msg-info">'.$filename.' removed.</div>';
} else {
echo '<div class="msg-warning">'.$filename.' was not removed. Check server permisions for "unlink" function.</div>';
}
;
}
}
function unlike_video($id, $u = null){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
if(is_null($u)) {
$u = user_id();
}
$db->query("delete from ".DB_PREFIX."likes where uid ='".$u."' and vid='".$id."'");
} else {
//delete like
$db->query("delete from ".DB_PREFIX."likes where uid ='".user_id()."' and vid='".$id."'");
}
//Set video to -1
$db->query("update ".DB_PREFIX."videos set liked=liked-1 where id='".$id."'");
do_action('unlikevideo');
}
}
function delete_playlist($id){
global $db;
$id = intval($id);
if($id){
if (is_moderator()){
//delete playlist
$db->query("DELETE FROM ".DB_PREFIX."playlists where id='".$id."'");
//delete data
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist='".$id."'");
} else {
//make sure it's his playlist
$db->query("DELETE FROM ".DB_PREFIX."playlists where id='".$id."' and owner ='".user_id()."'");
if($db->rows_affected > 0) {
//delete data only on success
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist='".$id."'");
do_action('deleteplaylist');
}
}
}
}
// Playlist forwarding
function start_playlist(){
global $db;
$list = token();
if($list) {
if(intval($list) > 0) {	
/* Regular playlist */
$videox = $db->get_row("select id,video_id as vid from ".DB_PREFIX."playlist_data where playlist=$list  order by id desc");
} elseif(strpos($list, 'uvs-') !== false) {
/* All videos */	
$uid = intval(str_replace('uvs-','',$list));
$videox = $db->get_row("select id as vid from ".DB_PREFIX."videos where user_id=$uid and media < 2  order by id desc");
} elseif(strpos($list, 'ums-') !== false) {
/* All music */
$uid = intval(str_replace('ums-','',$list));
$videox = $db->get_row("select id as vid from ".DB_PREFIX."videos where user_id=$uid and media > 1  order by id desc");
}
if($videox){
if(intval($list) > 0) {	
$list .='&pos='.$videox->id;
}
return video_url($videox->vid, 'playlist',$list );
}
}
return playlist_url($list, 'all');
}
//Get the media file
function get_file($input, $token){
$filename = ABSPATH.'/'.get_option('mediafolder')."/".$input;
if (file_exists($filename)) {
return is_image($input) ? 'localimage/'.$input : 'localfile/'.$input;
} else{
return get_by_token($token);
}
}
function get_by_token($token){
global $db;
$video = $db->get_row("SELECT path from ".DB_PREFIX."videos_tmp where name='".toDb($token)."'");
if($video){
return is_image($video->path) ? 'localimage/'.$video->path : 'localfile/'.$video->path;
} else{
return null;
}
}
function is_image($url) {
$pieces_array = explode('.', $url);
$ext = end($pieces_array);
$file_supported = array("jpg", "jpeg", "png", "gif");
if(in_array($ext, $file_supported)) {
return true;
}
return false;
}
//durations -> seconds
function toSeconds($str){
$str=explode(':',$str);
switch( count($str) )
{
case 2: return $str[0]*60 + $str[1];
case 3: return $str[0]*3600 + $str[1]*60 + $str[2];
}
return 0;
}
//validate remote
function validateRemote($url){
$pieces_array = explode('.', $url);
$ext = end($pieces_array);
$file_supported = array("mp4","m4v","3gp", "flv", "webm", "ogv", "m3u8", "ts", "tif");
if(in_array($ext, $file_supported) || is_image($url)) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
// don't download content
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if(curl_exec($ch)!==FALSE)
{
return true;
}
else
{
return false;
}
} else {
return false;
}
}
//fix cookie
function cookiedomain() {
$parse = parse_url(site_url());
return '.'.str_replace('www.','', $parse['host']);
}
//delete playlist
function playlist_remove($play, $video) {
global $db;
if(is_array($video)) {
foreach ($video as $del) {
playlist_remove($play, $del);
}
} else {
if($video && $play) {
$db->query("DELETE FROM ".DB_PREFIX."playlist_data where playlist= '".intval($play)."' and video_id= '".intval($video)."' ");
}
}
do_action('playlistremove');
}
//logo
function show_logo($pos = 'header'){
global $page;
$l = '';
if(get_option('site-logo')) {
return '<img src="'.thumb_fix(get_option('site-logo')).'"/>'.$l;
} else {
return '<span>'._html(get_option('site-logo-text')).'</span>'.$l;
}
}
// duration to seconds
function _tSec($time) {
$t = explode(':', $time);
if ( count($t) > 2) {
return $t[0] * 3600 + $t[1] * 60 + $t[2];
}else {
return $t[0] * 60 + $t[1];
}
}
//is profile owner
function is_powner() {
global $profile;
return (isset($profile) && $profile && is_user() && $profile->id == user_id());
}
//Guess next video
function guess_next($list=null) {
global $video, $cachedb, $db;
$pos =intval(_get('pos'));
$list =intval(_get('list'));
//Small fix if id is low
//to avoid errors
$skip = false;
if(strpos(_get('list'), 'ums-') !== false) {
$uid = intval(str_replace('ums-','',_get('list')));	
$videox = $db->get_row("SELECT id ,title from ".DB_PREFIX."videos where user_id=$uid and id < $video->id and media > 1 and pub > 0 and date < now() order by id desc limit 0,1");
$skip = true;
}elseif(strpos(_get('list'), 'uvs-') !== false) {
$uid = intval(str_replace('uvs-','',_get('list')));
$videox = $db->get_row("SELECT id,title from ".DB_PREFIX."videos where user_id=$uid and id < $video->id and media < 2 and pub > 0 and date < now() order by id desc limit 0,1");
$skip = true;
}elseif(is_null($list) || ($list < 1)) {
$videox = $db->get_row("SELECT id as video_id ,title from ".DB_PREFIX."videos where id < $video->id order by id asc limit 0,1");
} else {
if(!$skip && (is_null($pos) || ($pos < 1))) {
$cpos = $db->get_row("SELECT id from ".DB_PREFIX."playlist_data where playlist=$list and video_id = $video->id ");
if($cpos) { $pos = $cpos->id; }
}
$videox = $db->get_row("SELECT ".DB_PREFIX."playlist_data.video_id,".DB_PREFIX."playlist_data.id, ".DB_PREFIX."videos.title
FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id WHERE ".DB_PREFIX."playlist_data.playlist =$list AND ".DB_PREFIX."playlist_data.id < $pos ORDER BY ".DB_PREFIX."playlist_data.id DESC LIMIT 0,1");
}
$next = array();
if($videox) {
$next['av'] = true;
if(is_empty($list) || $skip) {
$next['link'] = video_url($videox->id , $videox->title);
$next['title'] = _html($videox->title);
} else {
if(isset($videox->video_id)) {	
$next['link'] = video_url($videox->video_id , $videox->title).'&list='._get('list').'&pos='.$videox->id;
} else {
$next['link'] = video_url($videox->id , $videox->title).'&list='._get('list');
}
$next['title'] = _html($videox->title);
}
} else {
//Avoid warnings
$next['av'] = false;
$next['link'] = null;
$next['title'] = null;
}
return $next;
}
function _ad($type, $spot= null) {
global $cachedb;
if($type == 1) {
$ad = $cachedb->get_row("select jad_body from ".DB_PREFIX."jads where jad_type = '5' ORDER BY rand()");
if($ad){
return '
<div class="floating-video-ad adx">'.trim(stripslashes($ad->jad_body)).'<span class=" close-ad"></span></div>
';
}

} else {
$ad = $cachedb->get_row("select ad_content from ".DB_PREFIX."ads where ad_type = '0' and ad_spot='".$spot."' ORDER BY rand()");
if($ad){
return '<div class="static-ad">'._pjs($ad->ad_content).'</div>';
}
}
}
function _jads() {
/* Load ads to jPlayer */
global $cachedb;
define( 'jplayerloaded', 'true');
$ads = $cachedb->get_results("select * from ".DB_PREFIX."jads limit 0,1000",ARRAY_A);
$fads = '';
$cads = ''; $fads = '';
if($ads) {
$pre=array();
foreach ($ads as $ad) {
$ar = array();
$pre[$ad['jad_type']][] = $ad;
}
/* Let's start rendering*/
if(isset($pre[3]) && !is_null($pre[3])) {
/* Pre-roll ad */
/* Extract only one random ad */
$pread = array();
$pread = $pre[3][array_rand($pre[3], 1)];
$pread["jad_body"] = trim(stripslashes($pread["jad_body"]));
//var_dump($pread);
$fads .= "
$('.mediaPlayer').bind($.jPlayer.event.ready, function() {
$(cpJP).jPlayer('pause');
$('.pre-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){  $(cpJP).jPlayer('play'); });
});
";
$cads .= "<div id='bigAd' class='pre-roll-ad screenAd hide'><div class='innerAd'>".$pread['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Skip this and play')."</a></div></div>";
/* End pre-rol */
}
if(has_list()){
/* If we have a list, replace post-roll with a video push */
global $new;
if(!isset($new) || !isset($new['link']) || nullval($new['link'])) {
$new = guess_next();
}
$fads .= 'var next_vid = \''.$new['link'].'&list='._get('list').'\';';
$fads .=  '
if (typeof next_url !== \'undefined\') {
$(\'.mediaPlayer\').bind($.jPlayer.event.ended, function() {
window.location.replace(next_vid);
});
}
';
} elseif(isset($pre[4]) && !is_null($pre[4])) {
/* Post-roll ad */
/* Extract only one random ad */
$postad = array();
$postad = $pre[4][array_rand($pre[4], 1)];
//var_dump($postad);
$postad["jad_body"] = trim(stripslashes($postad["jad_body"]));
$fads .= "
$('.mediaPlayer').bind($.jPlayer.event.ended, function() {
$(cpJP).jPlayer('pause');
$('.post-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){  $(cpJP).jPlayer('play'); });
});
";
$cads .= "<div id='bigAd' class='post-roll-ad screenAd hide'><div class='innerAd'>".$postad['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Restart the video')."</a></div></div>";
/* End post-rol */
}
/* Star time dependable events */
$fads .= "
$('.mediaPlayer').bind($.jPlayer.event.timeupdate, function(event) {
var currentTime = Math.floor(event.jPlayer.status.currentTime);
";
/* Start Annotations */
if(isset($pre[2]) && !is_null($pre[2])) {
foreach ($pre[2] as $an) {
//var_dump($an);
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#PL".$an["jad_id"]."').hasClass('hide')){
$('#PL".$an["jad_id"]."').removeClass('hide');
$(\"#PL".$an["jad_id"]." > .plclose\").click(function(){  $('#PL".$an["jad_id"]."').addClass('hide');   });
}
}
";
$cads .= "<div id='PL".$an["jad_id"]."' class='plAd ".$an["jad_pos"]." hide'>".$an["jad_body"]."<a class='plclose' href='javascript:void(0)'></a></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#PL'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#PL'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Annotations */
/* Start OVerlays */
if(isset($pre[5]) && !is_null($pre[5])) {
foreach ($pre[5] as $an) {
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$box_render = array("0" =>"plTransparent" , "1" => "");
//var_dump($an);
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#BD".$an["jad_id"]."').hasClass('hide')){
$(\"div.bAd\").addClass('hide');
$('#BD".$an["jad_id"]."').removeClass('hide');
$(\".adclose\").click(function(){  	$(\"div.bAd\").addClass('hide');   });
}
}
";
$cads .= "<div id='BD".$an["jad_id"]."' class='bAd ".$box_render[intval($an["jad_box"])]." hide'><div class='innerAd'>".$an["jad_body"]."<a class='adclose' href='javascript:void(0)'></a></div></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#BD'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#BD'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Overlays */
$fads .= "
});
";
/* End time dependable events */
}
/* Ends IF ads */
$fads .= "
});
";
$res = array();
/* jquery return */
$res['js'] = $fads;
/* html return */
$res['html'] =$cads;
return $res;
}
function _jwads() {
global $cachedb;
define( 'jwplayerloaded', 'true');
$ads = $cachedb->get_results("select * from ".DB_PREFIX."jads limit 0,1000",ARRAY_A);
$cads = ''; $fads = '';
if($ads) {
$fads = ' $(document).ready(function() {
jwplayer().onPlay( function(){
$(\'.plAd\').detach().appendTo(\'.jwplayer\');	
$(\'.bAd\').detach().appendTo(\'.jwplayer\');	
$(\'div.screenAd\').addClass(\'hide\');
$(\'.plAd\').addClass(\'hide\');
});';
$pre=array();
foreach ($ads as $ad) {
$ar = array();
$pre[$ad['jad_type']][] = $ad;
}
/* Let's start rendering*/
if(isset($pre[3]) && !is_null($pre[3])) {
/* Pre-roll ad */
/* Extract only one random ad */
$pread = array();
$pread = $pre[3][array_rand($pre[3], 1)];
$pread["jad_body"] = trim(stripslashes($pread["jad_body"]));
//var_dump($pread);
$fads .= "
jwplayer().onReady( function(){
$('.pre-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){ $('.pre-roll-ad').addClass('hide'); jwplayer().play(true); });
});
";
$cads .= "<div id='bigAd' class='pre-roll-ad screenAd hide'><div class='innerAd'>".$pread['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Skip this and play')."</a></div></div>";
/* End pre-rol */
}
if(!has_list() && (isset($pre[4]) && !is_null($pre[4]))) {
/* Post-roll ad */
/* Extract only one random ad */
$postad = array();
$postad = $pre[4][array_rand($pre[4], 1)];
//var_dump($postad);
$postad["jad_body"] = trim(stripslashes($postad["jad_body"]));
$fads .= "
jwplayer().onComplete( function(){
$('.post-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){  jwplayer().play(true); });
});
";
$cads .= "<div id='bigAd' class='post-roll-ad screenAd hide'><div class='innerAd'>".$postad['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Restart the video')."</a></div></div>";
/* End post-rol */
}
/* Star time dependable events */
$fads .= "
jwplayer().onTime( function(){
var currentTime = Math.floor(jwplayer().getPosition());
";
/* Start Annotations */
if(isset($pre[2]) && !is_null($pre[2])) {
foreach ($pre[2] as $an) {
//var_dump($an);
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#PL".$an["jad_id"]."').hasClass('hide')){
$('#PL".$an["jad_id"]."').removeClass('hide');
$(\"#PL".$an["jad_id"]." > .plclose\").click(function(){  $('#PL".$an["jad_id"]."').addClass('hide');   });
}
}
";
$cads .= "<div id='PL".$an["jad_id"]."' class='plAd ".$an["jad_pos"]." hide'>".$an["jad_body"]."<a class='plclose' href='javascript:void(0)'></a></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#PL'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#PL'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Annotations */
/* Start OVerlays */
if(isset($pre[5]) && !is_null($pre[5])) {
foreach ($pre[5] as $an) {
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$box_render = array("0" =>"plTransparent" , "1" => "");
//var_dump($an);
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#BD".$an["jad_id"]."').hasClass('hide')){
$(\"div.bAd\").addClass('hide');
$('#BD".$an["jad_id"]."').removeClass('hide');
$(\".adclose\").click(function(){  	$(\"div.bAd\").addClass('hide');   });
}
}
";
$cads .= "<div id='BD".$an["jad_id"]."' class='bAd ".$box_render[intval($an["jad_box"])]." hide'><div class='innerAd'>".$an["jad_body"]."<a class='adclose' href='javascript:void(0)'></a></div></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#BD'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#BD'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Overlays */
$fads .= "
});
";
/* End time dependable events */
$fads .= "
});
";
}
/* Ends IF ads */
$res = array();
/* jquery return */
$res['js'] = $fads;
/* html return */
$res['html'] =$cads;
return $res;
}
function _vjsads() {
global $cachedb;
define( 'vjsloaded', 'true');
$ads = $cachedb->get_results("select * from ".DB_PREFIX."jads limit 0,1000",ARRAY_A);
$cads = ''; $fads = '';
if($ads) {
$fads = ' $(document).ready(function() {
myPlayer.on("play", function(){
$(\'div.screenAd\').addClass(\'hide\');
$(\'.plAd\').addClass(\'hide\');
});
';
$pre=array();
foreach ($ads as $ad) {
$ar = array();
$pre[$ad['jad_type']][] = $ad;
}
/* Let's start rendering*/
if(isset($pre[3]) && !is_null($pre[3])) {
/* Pre-roll ad */
/* Extract only one random ad */
$pread = array();
$pread = $pre[3][array_rand($pre[3], 1)];
$pread["jad_body"] = trim(stripslashes($pread["jad_body"]));
//var_dump($pread);
$fads .= "
myPlayer.ready(function(){
$('.pre-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){ $('.pre-roll-ad').addClass('hide'); myPlayer.play();; });
});
";
$cads .= "<div id='bigAd' class='pre-roll-ad screenAd hide'><div class='innerAd'>".$pread['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Skip this and play')."</a></div></div>";
/* End pre-rol */
}
if(!has_list() && (isset($pre[4]) && !is_null($pre[4]))) {
/* Post-roll ad */
/* Extract only one random ad */
$postad = array();
$postad = $pre[4][array_rand($pre[4], 1)];
//var_dump($postad);
$postad["jad_body"] = trim(stripslashes($postad["jad_body"]));
$fads .= "
myPlayer.on('ended', function(){
$('.post-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){  myPlayer.play(); });
});
";
$cads .= "<div id='bigAd' class='post-roll-ad screenAd hide'><div class='innerAd'>".$postad['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Restart the video')."</a></div></div>";
/* End post-rol */
}
/* Star time dependable events */
$fads .= "
myPlayer.on('timeupdate', function(){
var currentTime = Math.floor(myPlayer.currentTime());
";
/* Start Annotations */
if(isset($pre[2]) && !is_null($pre[2])) {
foreach ($pre[2] as $an) {
//var_dump($an);
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#PL".$an["jad_id"]."').hasClass('hide')){
$('#PL".$an["jad_id"]."').removeClass('hide');
$(\"#PL".$an["jad_id"]." > .plclose\").click(function(){  $('#PL".$an["jad_id"]."').addClass('hide');   });
}
}
";
$cads .= "<div id='PL".$an["jad_id"]."' class='plAd ".$an["jad_pos"]." hide'>".$an["jad_body"]."<a class='plclose' href='javascript:void(0)'></a></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#PL'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#PL'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Annotations */
/* Start OVerlays */
if(isset($pre[5]) && !is_null($pre[5])) {
foreach ($pre[5] as $an) {
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$box_render = array("0" =>"plTransparent" , "1" => "");
//var_dump($an);
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#BD".$an["jad_id"]."').hasClass('hide')){
$(\"div.bAd\").addClass('hide');
$('#BD".$an["jad_id"]."').removeClass('hide');
$(\".adclose\").click(function(){  	$(\"div.bAd\").addClass('hide');   });
}
}
";
$cads .= "<div id='BD".$an["jad_id"]."' class='bAd ".$box_render[intval($an["jad_box"])]." hide'><div class='innerAd'>".$an["jad_body"]."<a class='adclose' href='javascript:void(0)'></a></div></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#BD'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#BD'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Overlays */
$fads .= "
});
";
/* End time dependable events */
$fads .= "
});
";
}
/* Ends IF ads */
$res = array();
/* jquery return */
$res['js'] = $fads;
/* html return */
$res['html'] =$cads;
return $res;
}
function _flowads() {
global $cachedb;
define( 'flowloaded', 'true');
$ads = $cachedb->get_results("select * from ".DB_PREFIX."jads limit 0,1000",ARRAY_A);
$cads = ''; $fads = '';
if($ads) {
$fads = ' $(document).ready(function() {
api = flowplayer($(".flowplayer"));
api.one("playing", function(){
$(\'div.screenAd\').addClass(\'hide\');
$(\'.plAd\').addClass(\'hide\');
});
';
$pre=array();
foreach ($ads as $ad) {
$ar = array();
$pre[$ad['jad_type']][] = $ad;
}
/* Let's start rendering*/
if(isset($pre[3]) && !is_null($pre[3])) {
/* Pre-roll ad */
/* Extract only one random ad */
$pread = array();
$pread = $pre[3][array_rand($pre[3], 1)];
$pread["jad_body"] = trim(stripslashes($pread["jad_body"]));
//var_dump($pread);
$fads .= "
api.one('ready', function(e, api, video) {
$('.pre-roll-ad').removeClass('hide');
$('.bigadclose').click(function(){ $('.pre-roll-ad').addClass('hide'); api.play(); });
});
";
$cads .= "<div id='bigAd' class='pre-roll-ad screenAd hide'><div class='innerAd'>".$pread['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Skip this and play')."</a></div></div>";
/* End pre-rol */
}
if(!has_list() && (isset($pre[4]) && !is_null($pre[4]))) {
/* Post-roll ad */
/* Extract only one random ad */
$postad = array();
$postad = $pre[4][array_rand($pre[4], 1)];
//var_dump($postad);
$postad["jad_body"] = trim(stripslashes($postad["jad_body"]));
$fads .= "
api.bind('finish', function (e, api, video) {
$('.post-roll-ad').removeClass('hide');
$('.plAd').addClass('hide');
$('.bAd').addClass('hide');
$('.bigadclose').click(function(){
$('div.screenAd').addClass('hide');
api.play();
});
});
";
$cads .= "<div id='bigAd' class='post-roll-ad screenAd hide'><div class='innerAd'>".$postad['jad_body']." <a class='bigadclose' href='javascript:void(0)'>"._lang('Restart the video')."</a></div></div>";
/* End post-rol */
}
/* Star time dependable events */
$fads .= "
api.bind('progress', function (e, api, video) {
var currentTime = Math.floor(api.video.time);
";
/* Start Annotations */
if(isset($pre[2]) && !is_null($pre[2])) {
foreach ($pre[2] as $an) {
//var_dump($an);
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#PL".$an["jad_id"]."').hasClass('hide')){
$('#PL".$an["jad_id"]."').removeClass('hide');
$(\"#PL".$an["jad_id"]." > .plclose\").click(function(){  $('#PL".$an["jad_id"]."').addClass('hide');   });
}
}
";
$cads .= "<div id='PL".$an["jad_id"]."' class='plAd ".$an["jad_pos"]." hide'>".$an["jad_body"]."<a class='plclose' href='javascript:void(0)'></a></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#PL'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#PL'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Annotations */
/* Start OVerlays */
if(isset($pre[5]) && !is_null($pre[5])) {
foreach ($pre[5] as $an) {
$an["jad_body"] = trim(stripslashes($an["jad_body"]));
$box_render = array("0" =>"plTransparent" , "1" => "");
//var_dump($an);
$fads .= "
if (currentTime == ".$an["jad_start"]."){
if($('#BD".$an["jad_id"]."').hasClass('hide')){
$(\"div.bAd\").addClass('hide');
$('#BD".$an["jad_id"]."').removeClass('hide');
$(\".adclose\").click(function(){  	$(\"div.bAd\").addClass('hide');   });
}
}
";
$cads .= "<div id='BD".$an["jad_id"]."' class='bAd ".$box_render[intval($an["jad_box"])]." hide'><div class='innerAd'>".$an["jad_body"]."<a class='adclose' href='javascript:void(0)'></a></div></div>";
if(intval($an["jad_end"]) > 0) {
$time = $an["jad_end"] + $an["jad_start"];
$fads .=  '
if(!$("#BD'.$an["jad_id"].'").hasClass("hide")){
if (currentTime > '.$time.'){
$("#BD'.$an["jad_id"].'").addClass("hide");
}
}
';
}
}
}
/* End Overlays */
$fads .= "
});
";
/* End time dependable events */
$fads .= "
});
";
}
/* Ends IF ads */
$res = array();
/* jquery return */
$res['js'] = $fads;
/* html return */
$res['html'] =$cads;
return $res;
}
/* Channels dropdown builder */
function cats_select($name = null, $class= "select", $validate ="validate[required] form-control", $type="1"){
global $cachedb;
$sub = '';
$data = '';
if(!is_moderator()) { $sub ="AND sub > 0";}
$categories = $cachedb->get_results("SELECT cat_id as id, cat_name as name, child_of as ch  FROM  ".DB_PREFIX."channels WHERE type = '".$type."' ".$sub." order by cat_name asc limit 0,10000");
$data =' <select placeholder="'._lang("Select channel:").'" name="'.$name.'" class="'.$class.' '.$validate.'"> ';
if($categories) {
$catarrays = array();
foreach ($categories as $cats) {
$catarrays[intval($cats->ch)][$cats->id]["id"] = _html($cats->id);
$catarrays[intval($cats->ch)][$cats->id]["name"] = _html($cats->name);
ksort($catarrays);
}
foreach ($catarrays['0'] as $cat) {
$data .= ' <option value="'. $cat["id"].'" class="opm">'. $cat["name"].'</option>';
if(isset($catarrays[$cat["id"]])) {
$data .=  rec_ch($catarrays[$cat["id"]], $catarrays, '','class="ops"');
}
}
}	else { $data .='<option value="">'._lang("Warning: No channels.").'</option>'; }
$data .='	 </select> ';
return $data;
}
function rec_ch($in = array(), $full = array(), $pre = '', $class = ''){
$data = '';
$chd = '';
asort($in);
foreach ($in as $child) {
$data .= ' <option value="'. $child["id"].'" '.$class.' >'.$pre.' '. $child["name"].'</option>';
if(isset($full[$child["id"]])) {
$data .= rec_ch($full[$child["id"]], $full, '','class="opz"');
}
}
return $data;
}
//detect IOS
function isIOS(){
return (stripos($_SERVER['HTTP_USER_AGENT'],"iPod") || stripos($_SERVER['HTTP_USER_AGENT'],"iPhone") || stripos($_SERVER['HTTP_USER_AGENT'],"iPad"));
}
if (!function_exists('_')) {
function _($txt){
return _lang($txt);
}
}
//players support
function players_js() {
return apply_filter( 'addplayers', false );
}
//FlowPlayer
function flowsup($ini = ''){
$jp = '<link rel="stylesheet" type="text/css"href="' . site_url() . 'lib/players/fplayer/skin/functional.css">';
$jp .= ' <script src="' . site_url() . 'lib/players/fplayer/flowplayer.min.js"></script>';
return $ini.$jp;
}
//jPlayer Support
function jpsup($ini = ''){
$jp = '<link rel="stylesheet"  href="' . site_url() . 'lib/players/customJP/css/ytube.jplayer.css" />';
$jp .= ' <script src="' . site_url() . 'lib/players/customJP/js/jquery.jplayer.min.js"></script>';
$jp .= ' <script src="' . site_url() . 'lib/players/customJP/js/jplayer.easydeploy.min.js"></script>';
return $ini.$jp;
}
//Videojs Support
function vjsup($ini = ''){
$vjs = '<link rel="stylesheet"  href="' . site_url() . 'lib/players/vjs/video-js.css" />';
$vjs .= ' <script src="' . site_url() . 'lib/players/vjs/video.js"></script>';
return $ini.$vjs;
}
//JwPlayer
function jwplayersup($ini = ''){
$jp = '<script type="text/javascript" src="' . site_url() . 'lib/players/jwplayer/jwplayer.js"></script>';
if (get_option('jwkey')) { $jp .= '<script type="text/javascript">jwplayer.key="' . get_option('jwkey') . '";</script>';}
return $ini.$jp;
}
//Active function
function aTab($current= null){
global $active;
if($active){
if($current == $active){
echo 'active';
}
}
}
function rExternal() {
global $vid,$video;
if(isset($video) && isset($vid)) {
$keep = array("youtube", "localimage", "localfile");
if(!in_array($vid->VideoProvider(),$keep)) {
echo "external";
}
}
}
function plugin_inc($p){
return ABSPATH."/plugins/".$p."/plugin.php";
}
function _checkData($url)
{
$ch      = curl_init();
$timeout = 15;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
function get_soundcloud($url) {
$url = 'http://soundcloud.com/oembed?format=json&url='.$url;
$content = _checkData($url);
$video = json_decode($content, true);
$video['thumbnail'] = $video['thumbnail_url'];
preg_match('/src="([^"]+)"/', $video["html"], $match);
if(isset($match[1])) {
$initial = parse_url($match[1]);
$initial = $initial['query'];
$initial = str_replace(array('&show_artwork=true','visual=true&','url='),'',$initial);
$video['source'] = urldecode($initial);
$video['tags'] ='';
}
return $video;
}
function is_insecure_file($file){
$fa = explode(".",$file);
$bad = array("php","php1","php2","php3","php4","php5","phtml","exe","php6","php7","php8","pl");
$fa = array_map('strtolower', $fa);
$a1_flipped = array_flip($fa);
$a2_flipped = array_flip($bad);
return (bool)count(array_intersect_key($a1_flipped, $a2_flipped));
}
function removeCommonWords($input){
$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
}
function the_embed() {
global $embedvideo;
if(isset($embedvideo)){
return apply_filter('theEmbed',$embedvideo);
}	
}
function lang_menu() {
global $cachedb;
$row = $cachedb->get_results( "SELECT `lang_code`, `lang_name` FROM ".DB_PREFIX."languages order by `lang_name` asc  LIMIT 0,100" );
$menu = ''; $cr = ''; 
if($row) {
foreach($row as $l) {
if($l->lang_code == current_lang()) {$cr = $l->lang_name; $ico = '<i class="icon icon-circle icon-lang-active"></i>';}	else {$ico = '<i class="icon icon-circle-o"></i>';}
$menu .= '<li><a href="'.canonical().'&clang='.$l->lang_code.'">'.$ico.' '.$l->lang_name.'</a></li>';
}
}
return $menu;
}

function u_k($nr){
/* Turns 000-s to k-s, m-s, like Fb */ 	
if ($nr > 999 && $nr <= 999999) {
    $result = round($nr / 1000, 1). _lang('k');
} elseif ($nr > 999999) {
    $result = round($nr / 1000000, 1). _lang('m');
} else {
    $result = $nr;
}
return $result;
}
/* Utils */
function is_home(){
/* home page check */	
return (com() == "home");	
}
function is_video(){
/* single video/music page check */	
return (com() == "video");	
}
function is_picture(){
/* single picture page check */	
return (com() == "image");	
}
function is_channel(){
/* channel/profile page check */	
return (com() == "profile");	
}
function is_com($com){
/* Check if this com/page is current */
return (com() == $com);	
}
/** Permissions **/
/* Moderators & Admins are not restricted */	
function _UpVideo () {
/* Check if user is allowed to upload video files */
return ((get_option('uploadrule',1) == 1) ||  is_moderator());	
}
function _EmbedVideo () {
/* Check if user is allowed to embed/social share videos */
return ((get_option('sharingrule',1) == 1) ||  is_moderator());	
}
function _UpMusic () {
/* Check if user is allowed to upload music (mp3) files */
return ((get_option('mp3rule',1) == 1) ||  is_moderator());	
}
function _EmbedMusic () {
/* Check if user is allowed to embed soundcloud music */
return ((get_option('musicembed',1) == 1) ||  is_moderator());	
}
function _UpImage () {
/* Check if user is allowed to upload images */
return ((get_option('imgrule',1) == 1) ||  is_moderator());	
}
function im_following($a){
global $db;	
if($a == user_id()) {
return true;
}	
$check = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."users_friends where uid ='".$a."' and fid='".user_id()."'");	
if(!$check) {
return false;
} else {
return (bool)$check->nr;	
}
}
/* Function to extract video data */
function _get_va($video, $ffmpeg) {

    $command = $ffmpeg . ' -i ' . $video . ' -vstats 2>&1';  
    $output = shell_exec($command);  

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
    if (preg_match($regex_sizes, $output, $regs)) {
        $codec = $regs [1] ? $regs [1] : null;
        $width = $regs [3] ? $regs [3] : null;
        $height = $regs [4] ? $regs [4] : null;
     }
	 if(!isset($height) || intval($height < 10)) {
		$regex_sizes = "(\b[^0]\d+x[^0]\d+\b)"; 
		if (preg_match($regex_sizes, $output, $regs)) { 
		   if(isset($regs[0])) {
		$sizer = @explode('x',$regs[0]);
        $width = $sizer[0] ? $sizer[0] : null;
        $height = $sizer[1] ? $sizer[1] : null;		
		//var_dump($sizer);
		    }		 
		}
	 }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
        $hours = $regs [1] ? $regs [1] : null;
        $mins = $regs [2] ? $regs [2] : null;
        $secs = $regs [3] ? $regs [3] : null;
        $ms = $regs [4] ? $regs [4] : null;
    }

    return array (
            'width' => $width,
            'height' => $height,
            'hours' => $hours,
            'mins' => $mins,
            'secs' => $secs,
            'ms' => $ms
    );

}
/* Admin log */

function vibe_log($in){
$file = ABSPATH.'/'.ADMINCP.'/alog.txt';
if (is_file($file) && is_writable ($file)) {
@file_put_contents($file, $in, FILE_APPEND | LOCK_EX);
}	
}
?>