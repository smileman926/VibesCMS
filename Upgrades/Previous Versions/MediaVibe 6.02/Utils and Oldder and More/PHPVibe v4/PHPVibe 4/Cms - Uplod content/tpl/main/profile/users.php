<?php //echo $vq;
$users = $db->get_results($vq);
if ($users) {
echo '<div class="loop-content phpvibe-user-list top20  oboxed">'; 
foreach ($users as $user) {
			$title = stripslashes(_cut($user->name, 46));
			$full_title = stripslashes(str_replace("\"", "",$user->name));			
			$url = profile_url($user->id , $user->name);
			
		
echo '
<div id="user-'.$user->id.'" class="user">
<div class="user-thumb">
		<a class="clip-link" data-id="'.$user->id.'" title="'.$full_title.'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($user->avatar, true, 101, 101).'" alt="'.$full_title.'" /><span class="vertical-align"></span>
			</span>					
			
		</a>		
	</div>	
	<div class="user-title">
			<a href="'.$url.'" title="'.$full_title.'">'.$title.'</a>
			</div>';
			subscribe_box($user->id,"btn btn-small btn-danger block", false);
echo '			
	</div>
';
}
echo '<br style="clear:both;"/></div>';
}
?>