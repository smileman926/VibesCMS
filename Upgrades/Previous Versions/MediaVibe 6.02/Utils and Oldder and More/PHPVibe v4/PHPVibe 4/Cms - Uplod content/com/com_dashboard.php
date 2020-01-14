<?php // SEO Filters
function modify_title( $text ) {
    return get_option("DashboardSEO","Your Dashboard");
}	
add_filter( 'phpvibe_title', 'modify_title' );
if(is_user()){
$profile = $db->get_row("SELECT * FROM ".DB_PREFIX."users where id = '".user_id() ."' limit  0,1");	
if(isset($_POST['changeavatar'])) {
$formInputName   = 'avatar';							
	$savePath	     = ABSPATH.'/uploads';								
	$saveName        = md5(time()).'-'.user_id();									
	$allowedExtArray = array('.jpg', '.png', '.gif');	
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$avatar = str_replace(ABSPATH.'/' ,'',$thumb);
	
	user::Update('avatar',$avatar);
	user::RefreshUser(user_id());
	redirect(site_url().'dashboard/&sk=edit');
} else { 
$msg = '<div class=".msg-warning">'._lang("Avatar upload failed.").'</div>';

	}

}
//Details change
if(isset($_POST['changeuser'])) {
var_dump($_POST);	
if(isset($_POST['name'])) { user::Update('name',$_POST['name']); }
if(isset($_POST['city'])) { user::Update('local',$_POST['city']); }
if(isset($_POST['country'])) { user::Update('country',$_POST['country']); }
if(isset($_POST['bio'])) { user::Update('bio',$_POST['bio']); }
if(isset($_POST['gender'])) { user::Update('gender',$_POST['gender']); }
if(isset($_POST['f-link'])) { user::Update('fblink',$_POST['f-link']); }
if(isset($_POST['g-link'])) { user::Update('glink',$_POST['g-link']); }
if(isset($_POST['tw-link'])) { user::Update('twlink',$_POST['tw-link']); }
user::RefreshUser(user_id());
//redirect(site_url().'dashboard/&sk=edit');
}	
$vd = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."videos where user_id='".user_id()."'");
$vvd = $cachedb->get_row("SELECT sum(views) as nr FROM ".DB_PREFIX."videos where user_id='".user_id()."'");
$ad = $cachedb->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."activity where user='".user_id()."'");
//Latest subscribers
$count = $cachedb->get_row("Select count(*) as nr from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".user_id()."')");
$vq = "select id,avatar,name from ".DB_PREFIX."users where ".DB_PREFIX."users.id in ( select fid from ".DB_PREFIX."users_friends where uid ='".user_id()."') ORDER BY ".DB_PREFIX."users.views DESC ".this_offset(10);
//Latest notifications
$avq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where ".DB_PREFIX."activity.object in (select id from ".DB_PREFIX."videos where user_id ='".user_id()."' )  and ".DB_PREFIX."activity.user <>'".user_id()."'  ORDER BY ".DB_PREFIX."activity.id DESC limit 0,50";
$notif = $db->get_results($avq);
$note = array();
if($notif) {
foreach ($notif as $buzz) {
$did = get_activity($buzz);		
$note[$buzz->id]['image'] = thumb_fix($buzz->avatar, true, 15, 15);
$note[$buzz->id]['text'] = '<div class="aBody"><a href="'.profile_url($buzz->pid, $buzz->name).'">'.$buzz->name.'</a> '.$did["what"].'</div><div class="aTime">'.time_ago($buzz->date).'</div>';
} 	
}
include_once(TPL.'/dashboard.php');
the_footer();
} else {
redirect(site_url().'login/');	
}
?>