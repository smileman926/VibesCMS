<?php  //Global query options
$key = toDb(token());
$heading = _lang('#').ucfirst(str_replace(array("+","-")," ",$key));	
$heading_plus = ('Video search results for ').$key;

if(!nullval($key)) {
$interval = '';
//Check for sorting 
if(_get('sort'))
{
switch(_get('sort')){
case "w":
$interval = "AND WEEK( DATE ) = WEEK( CURDATE( ) ) ";
break;
case "m":
$interval = "AND MONTH(date) = MONTH(CURDATE( ))";
break;
case "y":
$interval = "AND YEAR( DATE ) = YEAR( CURDATE( ) ) ";
break;
}
}
//Remove url format
$key = str_replace(array("-","+")," ",$key);

$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.description,".DB_PREFIX."videos.title, ".DB_PREFIX."videos.date,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
 /* If 3 letter word */
 if(strlen($key) < 4) {
 $vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id 
	WHERE ".DB_PREFIX."videos.pub > 0 and ( ".DB_PREFIX."videos.title like '%".$key."%' or ".DB_PREFIX."videos.description like '%".$key."%' or ".DB_PREFIX."videos.tags like '%".$key."%' ) ".$interval."
	   ORDER BY CASE WHEN ".DB_PREFIX."videos.title like '" .$key. "%' THEN 0
	           WHEN ".DB_PREFIX."videos.title like '%" .$key. "%' THEN 1
	           WHEN ".DB_PREFIX."videos.tags like '" .$key. "%' THEN 2
               WHEN ".DB_PREFIX."videos.tags like '%" .$key. "%' THEN 3		   
               WHEN ".DB_PREFIX."videos.description like '%" .$key. "%' THEN 4
			   WHEN ".DB_PREFIX."videos.tags like '%" .$key. "%' THEN 5
               ELSE 6
          END, title ".this_limit();
 } else {
 /* Use full search */	 
$vq = "select ".$options.", ".DB_PREFIX."users.name as owner,
MATCH (title,description,tags) AGAINST ('".$key."' IN BOOLEAN MODE) AS relevance,
MATCH (title) AGAINST ('".$key."' IN BOOLEAN MODE) AS title_relevance FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id 
	WHERE MATCH (title,description,tags) AGAINST('".$key."' IN BOOLEAN MODE) AND ".DB_PREFIX."videos.pub > 0 $interval ORDER by title_relevance DESC,relevance DESC ".this_limit();
 }	
// Canonical url
if(_get('sort')) {
$canonical = site_url().show.url_split.str_replace(array(" "),array("-"),$key)."&sort="._get('sort'); 
} else {
$canonical = site_url().show.url_split.str_replace(array(" "),array("-"),$key);	
}
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
<a data-toggle="dropdown" class="btn btn-small btn-primary btn-primary dropdown-toogle"><b class="caret"></b> </a>
			<a class="btn btn-small btn-primary">'._lang("Filter results").' </a>


			<ul class="dropdown-menu pad-icons">
			<div class="triangle"></div>
			<li class="TipE" title="'._lang("This Week").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'"><i class="icon-reorder"></i>'._lang("All").'</a></li>
			<li class="TipE" title="'._lang("This Week").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=w"><i class="icon-reorder"></i>'._lang("This Week").'</a></li>
			<li class="TipE" title="'._lang("This Month").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=m"><i class="icon-reorder"></i>'._lang("This Month").'</a></li>
			<li class="TipE" title="'._lang("This Year").'"><a href="'.site_url().show.url_split.str_replace(array(" "),array("-"),$key).'&sort=y"><i class="icon-reorder"></i>'._lang("This Year").'</a></li>
		</ul>
		</div>
';
} else {
$vq = '';
}	
// SEO Filters
function modify_title( $text ) {
global $heading;
    return strip_tags(stripslashes($heading));
}
function modify_desc( $text ) {
global $heading_plus;
    return _cut(strip_tags(stripslashes($heading_plus)), 160);
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
//Time for design
if (!is_ajax_call()) {  the_header(); the_sidebar(); }
include_once(TPL.'/searchresults.php');
if (!is_ajax_call()) { the_footer(); }
?>