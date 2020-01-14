<?php function youtubelinks($txt = '') {
return $txt.'
<li><a href="'.admin_url('yt').'"><i class="icon-youtube-play"></i>Youtube API</a></li>
';
}
add_filter('importers_menu', 'youtubelinks')

?>