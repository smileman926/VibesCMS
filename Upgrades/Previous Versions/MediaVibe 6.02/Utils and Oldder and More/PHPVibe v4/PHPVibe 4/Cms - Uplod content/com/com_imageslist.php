<?php  //Global query options
$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.date,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
/* Define list to load */
$interval = '';
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
switch(token()){

case mostliked:
		$heading = ('Most Liked Images');	
        $heading_plus = _lang('Most Liked Description');
		$sortop = true;
        $vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.liked > 0 and ".DB_PREFIX."videos.media = '3' ".$interval." ORDER BY ".DB_PREFIX."videos.liked DESC ".this_limit();
		$active = mostliked;
		break;
case mostcom:
		$heading = ('Most Commented Images');	
        $heading_plus = _lang('Most Commented Description');
	    $vq = "select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.name as owner , count(a.object_id) as cnt FROM ".DB_PREFIX."em_comments a LEFT JOIN ".DB_PREFIX."videos ON a.object_id LIKE CONCAT('%', ".DB_PREFIX."videos.id) LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.liked > 0 and ".DB_PREFIX."videos.media = '3' group by a.object_id order by cnt desc ".this_limit();
		$active = mostcom;
		break;		
case mostviewed:
		$heading = ('Most Viewed Images');	
        $heading_plus = _lang('Most Viewed Description');
        $sortop = true;		
        $vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views > 0 and ".DB_PREFIX."videos.media = '3' ".$interval." ORDER BY ".DB_PREFIX."videos.views DESC ".this_limit();
		$active = mostviewed;
		break;
case promoted:
		$heading = _lang('Featured images');
        $heading_plus = _lang('Featured Description');
        $sortop = true;		
        $vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.featured = '1' and ".DB_PREFIX."videos.media = '3' ".$interval." ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit();
        $active = promoted;
		break;
default:
		$heading = _lang('Newest images');	
        $heading_plus = _lang('Browse Description');        
		$vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.media = '3' ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit();
        $active = browse;
		break;		
}

//Add sorter 
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
<a data-toggle="dropdown" class="btn btn-small btn-default dropdown-toogle"><i class="icon-angle-down"></i>  '._lang("Filter Images").' </a>


			<ul class="dropdown-menu pad-icons">
			<div class="triangle"></div>
			<li class="TipE" title="'._lang("This Week").'"><a href="'.images_url(token()).'"><i class="icon-reorder"></i>'._lang("All").'</a></li>
			<li class="TipE" title="'._lang("This Week").'"><a href="'.images_url(token()).'&sort=w"><i class="icon-reorder"></i>'._lang("This Week").'</a></li>
			<li class="TipE" title="'._lang("This Month").'"><a href="'.images_url(token()).'&sort=m"><i class="icon-reorder"></i>'._lang("This Month").'</a></li>
			<li class="TipE" title="'._lang("This Year").'"><a href="'.images_url(token()).'&sort=y"><i class="icon-reorder"></i>'._lang("This Year").'</a></li>
		</ul>
		</div>
';
}
// Canonical url
if(_get('sort')) {
$canonical = images_url(token())."&sort="._get('sort'); 
} else {
$canonical = images_url(token()); 
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
include_once(TPL.'/imageslist.php');
if (!is_ajax_call()) { the_footer(); }
?>