<?php function com(){
/* Returns the current component used */	
global $route;
if(isset($route) && $route) {
return toDb($route->getTarget());
}
return false;
}
function token(){
/* Returns the token in the url */	
global $route;
if(isset($route) && $route) {
$idx = $route->getParameters();
if(isset($idx["section"])) {
$idx["section"] = current(explode("/", $idx["section"]));
$idx["section"] = trim(str_replace("/","",$idx["section"]));
return $idx["section"];
}
}
return false;
}
function token_id(){
/* Returns the id or hashed id in the url */	
	
global $route;
if(isset($route) && $route) {
$idx = $route->getParameters();
if(isset($idx["id"])) {
return $idx["id"];
} elseif(isset($idx["hid"])) {
return _dHash($idx["hid"]);	
}
}
return false;
}
// Sef rewrite tag function
function nice_tag($tag){
$tag = str_replace(array('.','(',')','%','#','\'','"','@'),'',$tag);	
$tag = str_replace(array(' ','/','_'),'-',$tag);
$tag = str_replace(array('-----','----','---','--'),'-', $tag);
return strtolower($tag);
}
function strip_punctuation($string, $rep ="-") {
    $string = strtolower($string);
    $string = preg_replace('/[[:punct:]]/', $rep, $string);
    $string = str_replace(" ", $rep, $string);
	$string = preg_replace('/\s+/', $rep, $string);
	$string = str_replace($rep.$rep, $rep, $string);
    return $string;
}
// Sef rewrite function	
function nice_url($iniurl) {
$string ='';
// translate utf-8
$url = url_translate($iniurl);
// remove all chars
//$url = preg_replace("/[^a-z0-9]+/","-",strtolower($url));
$url = strip_punctuation(strtolower($url));
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
'ن' => 'n', 'ه' => 'h', 'و' => 'o', 'ي' => 'y', 'ة'=> 'o',
/* Serbian */
'ђ' => 'dj', 'ј' => 'j', 'љ' => 'lj', 'њ' => 'nj', 'ћ' => 'c', 'џ' => 'dz', 'đ' => 'dj',
'Ђ' => 'Dj', 'Ј' => 'j', 'Љ' => 'Lj', 'Њ' => 'Nj', 'Ћ' => 'C', 'Џ' => 'Dz', 'Đ' => 'Dj',
/* Azerbaijani */
'ç' => 'c', 'ə' => 'e', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
'Ç' => 'C', 'Ə' => 'E', 'Ğ' => 'G', 'İ' => 'I', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U'			
);
return strtr($string,$specialchars);
}
// Rewrite urls for coms
function video_url($id, $title, $list=null){
$post = '';
if(!is_null($list)){ $post= '?list='.$list; }
$or = get_option('video-seo-url','/video/:id/:name');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':hid',_mHash($id),$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or.$post;
return $url;
}
function image_url($id, $title, $list=null){
$or = get_option('image-seo-url','/image/:id/:name');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':hid',_mHash($id),$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function profile_url($id, $title){
$or = get_option('profile-seo-url','/profile/:name/:id/:section');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':hid',_mHash($id),$or);
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
$or = str_replace(':hid',_mHash($id),$or);
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
$or = str_replace(':hid',_mHash($id),$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
function article_url($id, $title=null){
$or = get_option('article-seo-url','/article/:name/:id');
$or = str_replace(':name',nice_url($title),$or);
$or = str_replace(':id',$id,$or);
$or = str_replace(':hid',_mHash($id),$or);
$or = str_replace(':section','',$or);
$or .= (substr($or, -1) == '/' ? '' : '/');
$url = sef_url().$or;
return $url;
}
//Video & Music tags
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
//Image tags
function pretty_imgtags($tags, $class='', $pre='', $post = ''){
$list ='';
$keywords_array = explode(',', $tags);
if (count($keywords_array) > 0){
foreach ($keywords_array as $keyword){
if (not_empty(trim($keyword))){
$k_url = images_url('tag').'?tag='.nice_tag(trim($keyword));
$list .=  $pre.'<a class="'.$class.'" href="'.$k_url.'">'.$keyword.'</a>'.$post;
}
}
}
return $list;
}
?>