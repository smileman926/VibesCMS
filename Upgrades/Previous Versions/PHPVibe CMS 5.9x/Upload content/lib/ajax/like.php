<?php include_once('../../load.php');
$id = intval($_POST['video_id']);
$type = intval($_POST['type']);
$stype = $type + 2;
$tran = array();
$tran[1] = _lang('like');
$tran[2] = _lang('dislike');
$tran[3] = 'like';
$tran[4] = 'dislike';
if(is_user() && ($id > 0)) {
$check = $db->get_row("SELECT count(*) as nr, type FROM ".DB_PREFIX."likes WHERE vid = '".$id ."' AND uid ='".user_id()."'");
if(($check->nr > 0) && ($type == 1)) {
echo json_encode(array("added"=>"0","title"=>_lang('Ohhh!'),"text"=>_lang('You already'). ' ' .$check->type. ' '._lang('this')));
}elseif(($check->nr > 0) && ($type == 2)) {
$db->query("delete from ".DB_PREFIX."likes where uid ='".user_id()."' and vid='".$id."'");
$db->query("update ".DB_PREFIX."videos set disliked=disliked-1 where id='".$id."'");
echo json_encode(array("added"=>"2","title"=>_lang('Dislike removed'),"text"=>_lang('You\'ve removed the dislike')));
} else {
$db->query("INSERT INTO ".DB_PREFIX."likes (`uid`, `vid`, `type`) VALUES ('".user_id()."', '".$id."', '".$tran[$stype]."')");
$db->query("UPDATE ".DB_PREFIX."videos set ".$tran[$stype]."d = ".$tran[$stype]."d+1 where id = '".$id."'");
$db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `video_id`) VALUES ('".likes_playlist()."', '".$id."')");
if($type == 1) {
echo json_encode(array("added"=>"1","title"=>_lang('Success!'),"text"=>_lang('You'). ' '.$tran[$type].' '._lang('this')));
$_SESSION['ulikes'] .= $id.",";
} else {
echo json_encode(array("added"=>"3","title"=>_lang('Success!'),"text"=>_lang('You'). ' '.$tran[$type].' '._lang('this')));
	
}
add_activity('1', $id, $tran[$type]);

}

} else {
echo json_encode(array("added"=>"0","title"=>_lang('Hmm..have a name stranger?!'),"text"=>'<a href="'.site_url().login.'">'._lang('Please login in order to like a video! It\'s fast and worth it').'</a>'));
}
?>