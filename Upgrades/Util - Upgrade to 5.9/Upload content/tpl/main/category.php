<div id="channel-content" class="main-holder pad-holder col-md-12 top10 nomargin">
<?php 
$heading = '';
$heading_meta = '';
$heading_meta .= '
<div class="row bottom20 category-head top20">
<div class="col-md-1 col-xs-1 mleft20 text-center">
<img class="img-responsive mtop20" src="'.thumb_fix($channel->picture, true, 80, 80).'" />
</div>
<div class="col-md-9 col-xs-10 left20">
<h1>'._html($channel->cat_name).'</h1>
<p>'._html($channel->cat_desc).'</p>
</div>
</div>
';
if($subchannels) { 
$heading_meta .= ' <div class="fake-padding black-slider">
<ul id="carousel" class="owl-carousel">';
foreach ( $subchannels as $more ) { 
$heading_meta .= '<div class="subitem">
<a href="'.channel_url($more->cat_id,$more->cat_name).'" title="'.$more->cat_name.'">
<img alt="'.$more->cat_name.'" class="cartistic" src="'.thumb_fix($more->picture, true, 180, 180).'">
<span class="btn btn-outline btn-default btn-block">'._html($more->cat_name).'</span>
</a>
</div>';			
}
$heading_meta .= '</ul></div>';
}
if($typeofC <> 3) {
/* Music or videos */	
$options = (($typeofC > 1)? DB_PREFIX."videos.description,".DB_PREFIX."videos.liked," : "").DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.date,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.duration";
$vq = "select ".$options.", ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id 
WHERE ".DB_PREFIX."videos.category in (SELECT cat_id from ".DB_PREFIX."channels where cat_id = '".$channel->cat_id."' or cat_id in  (select  cat_id 
from    (select * from ".DB_PREFIX."channels
         order by child_of, cat_id) cats_sorted,
        (select @pv := '".$channel->cat_id."') initialisation
where   find_in_set(child_of, @pv) > 0
and     @pv := concat(@pv, ',', cat_id)) ) and ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.date < now() ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit(bpp());
if($typeofC > 1) {
include_once(TPL.'/music-loop.php');	
} else {
include_once(TPL.'/video-loop.php');
}
} else {
/* Images */
$options = DB_PREFIX."images.id,".DB_PREFIX."images.date,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."images.thumb,".DB_PREFIX."images.views";
$vq = "select ".$options.", ".DB_PREFIX."users.avatar, ".DB_PREFIX."users.name as owner FROM ".DB_PREFIX."images LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id 
WHERE ".DB_PREFIX."images.category in (SELECT cat_id from ".DB_PREFIX."channels where cat_id = '".$channel->cat_id."' or cat_id in  (select  cat_id 
from    (select * from ".DB_PREFIX."channels
         order by child_of, cat_id) cats_sorted,
        (select @pv := '".$channel->cat_id."') initialisation
where   find_in_set(child_of, @pv) > 0
and     @pv := concat(@pv, ',', cat_id)) ) and ".DB_PREFIX."images.pub > 0 and ".DB_PREFIX."images.date < now() ORDER BY ".DB_PREFIX."images.id DESC ".this_limit(bpp());
$blockextra = '<div class="mtop20">&nbsp;</div>';
include_once(TPL.'/images-loop.php');	
}
?>
</div>
<div class="cats cats-fixed-right">
<div class="cats-inner">
<?php if(!is_ajax_call()) {
	if($typeofC < 2) {echo the_nav();} else { echo the_nav($typeofC);} 
	}; ?>
</div>
</div>