<?php global $image,$canonical;
$options = DB_PREFIX."images.id,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."users.id as owner,".DB_PREFIX."users.avatar,".DB_PREFIX."images.thumb,".DB_PREFIX."images.views";
$vq = "select ".$options.", ".DB_PREFIX."users.name  FROM ".DB_PREFIX."images LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id 
WHERE (".DB_PREFIX."images.category = '".$image->category."' or ".DB_PREFIX."images.user_id = '".$image->user_id."') and ".DB_PREFIX."images.pub > 0 and ".DB_PREFIX."images.date < now() ORDER by views,liked DESC limit 0,".get_option('related-nr')." ";
echo '<div class="row">';
$kill_infinite = true;
include_once(TPL.'/images-loop.php');
echo '</div>';
?>