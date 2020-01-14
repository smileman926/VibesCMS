<?php  //Notifications
if(is_user() && (token() == "noty")) {

if(isset($_SESSION['lastNoty'])) {
if(($_SESSION['lastNoty'] - time()) > 599  ) { $continue = true;  } else { $continue = false;}
} else {
$continue = true;
}
if($continue){	
$vq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' )  and ".DB_PREFIX."activity.user <>'".user_id()."' and ".DB_PREFIX."activity.date > '".user_noty()."'  ORDER BY ".DB_PREFIX."activity.id DESC limit 0,12";
//$vq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where (".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' )  and ".DB_PREFIX."activity.user <>'".user_id()."') ORDER BY ".DB_PREFIX."activity.id DESC limit 0,12";

$notif = $db->get_results($vq);
$note = array();
if($notif) {
foreach ($notif as $buzz) {
$did = get_activity($buzz);		
$note[$buzz->id]['image'] = thumb_fix($buzz->avatar, true, 40, 40);
$note[$buzz->id]['text'] = '<div class="buzzBody"><a href="'.profile_url($buzz->pid, $buzz->name).'">'.$buzz->name.'</a> '.$did["what"].'</div><div class="buzzTime">'.time_ago($buzz->date).'</div>';
} 	
}
echo json_encode($note);	
LastOnline();
$_SESSION['lastNoty'] = time();	
}
//End notifications
}

?>