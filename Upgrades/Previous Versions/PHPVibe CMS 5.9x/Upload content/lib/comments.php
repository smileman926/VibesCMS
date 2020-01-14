<?php  $cobj = '';
function comments($iden =null) {
global $video,$cobj;
if (get_option('video-coms') == 1) {
//Facebook comments
if(is_null($iden) && isset($video)) {
/* Facebook video comments */	
return '<div id="coments" class="fb-comments" data-href="'.video_url($video->id,$video->title).'" data-width="100%" data-num-posts="15" data-notify="true"></div>						
';
} else {
/* Facebook comments */	
return '<div id="coments" class="fb-comments" data-href="'.canonical().'" data-width="100%" data-num-posts="15" data-notify="true"></div>';						
	
}	
} else {
/* Local PHPVibe comments system */	
if(is_null($iden) && isset($video)) {$cobj = 'video_'.$video->id;	} else {  $cobj = $iden;}	 
return show_comments($cobj);
}
}
function reply_box($to ='0'){
global $cobj;	
$uhtml = '';
$xtra = ($to > 0) ? "hide" : "";
$comtra = ($to > 0) ? _lang('reply') : _lang('comment');	
if( is_user() ){
    $uhtml .= '<li id="reply-'.$cobj.'-'.$to.'" class="addcm '.$xtra.'">
  <img class="avatar" src="'.thumb_fix(user_avatar(), true, 55, 55).'">
  <div class="message clearfix">
  <div class="arrow">
  <div class="arrow-inner"></div>
  <div class="arrow-outer"></div>
  </div>
  <form class="body" method="post" action="'.site_url().'ajax/addComment.php" onsubmit="return false;">
  <textarea placeholder="'._lang('Write your '.$comtra).'" id="addEmComment_'.$to.'" class="addEmComment auto" name="comment" /></textarea>
  <button type="submit" class="btn btn-primary btn-sm buttonS pull-right" id="emAddButton_'.$cobj.'" onclick="addEMComment(\''.$cobj.'\',\''.$to.'\')" />'._lang("Post").'</button>
  <input type="hidden" name="object_id" value="'.$cobj.'" />
  <input type="hidden" name="reply_to" value="'.$to.'" />
  </form>
  </div>
  </li>';
} elseif($to < 1) {
	$uhtml .= '<li id="reply-'.$cobj.'-'.$to.'" class="addcm '.$xtra.'">
	<img class="avatar" src="'.thumb_fix(site_url().'uploads/def-avatar.jpg', true, 55, 55).'">
	<div class="message clearfix">
    <div class="arrow">
    <div class="arrow-inner"></div>
    <div class="arrow-outer"></div>
    </div>
    <form class="body" method="post" onsubmit="return false;">
	<textarea placeholder="'._lang("Register or login to leave your impressions.").'" id="addDisable" class="addEmComment auto" name="comment"/></textarea>
    </form>
   </div>
   </li>';
}
return $uhtml; 	
}
function show_comments($object_id, $limit=2000, $offset = 0, $ALLOWLIKE = true) {
global $db,$cobj;
$CCOUNT = $limit;
$uhtml = '';
//get comments from database
$totals = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."em_comments WHERE object_id =  '".$object_id."'");
$html     = '<ul id="emContent_'.$object_id.'-0" class="comments full">
<div class="cctotal">'._lang("Comments").' ('.$totals->nr.')</div>
';

$html .=  reply_box();
if($totals->nr > 0) {
$comments   = $db->get_results("SELECT ".DB_PREFIX."em_comments . * , ".DB_PREFIX."em_likes.vote , ".DB_PREFIX."users.name, ".DB_PREFIX."users.avatar
FROM ".DB_PREFIX."em_comments
LEFT JOIN ".DB_PREFIX."em_likes ON ".DB_PREFIX."em_comments.id = ".DB_PREFIX."em_likes.comment_id and ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."em_likes.sender_ip
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."users.id
WHERE object_id =  '".$object_id."'
ORDER BY  ".DB_PREFIX."em_comments.id desc limit $offset,$limit");
if($comments) {
$ci = 1;
$cmp = array();
	 foreach( $comments as $comment) {
	 if($comment->vote && is_user()){            
            $likeText = commentLikeText($comment->rating_cache -1,true);
        }else{
            $likeText = '<a class="tipS" href="javascript:iLikeThisComment('.$comment->id.')" title="'._lang('Like this comment').'"> <i class="icon-thumbs-up"></i> '._lang('Like').' </a>';
            if($comment->rating_cache){
                $likeText .= ' &mdash; '.commentLikeText($comment->rating_cache,false);
            }
        }
	$cls = ($comment->reply < 1) ? '<li class="reply-btn"><a href="javascript:ReplyCom(\'reply-'.$cobj.'-'.$comment->id.'\')"><i class="icon-mail-forward"></i>'._lang("Reply").'</a></li>' : '';
	$cmp[$comment->reply][$comment->id]['id']=$comment->id;
    $cmp[$comment->reply][$comment->id]['body']= ' <li id="comment-id-'.$comment->id.'" class="left">
<img class="avatar" src="'.thumb_fix($comment->avatar, true, 55, 55).'">
<div class="message">
<span class="arrow"> </span>
<a class="name" href="'.profile_url($comment->sender_id,$comment->name).'">'._html($comment->name).'</a> 
<span class="body">'._html($comment->comment_text).'</span>
<ul class="msg-footer">
<li>'.time_ago($comment->created).'</li>
'.$cls.'
<li><span class="like-com" id="iLikeThis_'.$comment->id.'">'.$likeText.'</span></li>
</ul>
';	
$cmp[$comment->reply][$comment->id]['body'] .='</div>
</li>
';
$ci++;
}
foreach ($cmp[0] as $body) {	
$html .= $body['body'];
$html .= '<ul id="emContent_'.$cobj.'-'.$body['id'].'" class="reply" >';
$html .= reply_box($body['id']);	
if(isset($cmp[$body['id']])){

foreach ($cmp[$body['id']] as $ch) {
$html .= $ch['body'];	
}
}
$html .= "</ul>";
}
 $html .= '</ul>';
} 
}

    //send reply to client
    return '<div id="'.$object_id.'" class="emComments" object="'.$object_id.'" class="ignorejsloader">'.$html.'</div>';

}

function commentLikeText($total, $me=true){
           
        if($me){
			if($total < 0){
			return '';	
			}
            elseif($total == 0){
                return '<i class="icon-thumbs-up"></i>'._lang('by you');
            }elseif($total == 1){
                return '<i class="icon-thumbs-up"></i>'._lang('by you +1 like this');
            }else{
                return '<i class="icon-thumbs-up"></i>'.str_replace('XXX',$total,_lang('by you and XXX others'));
            }       
        }else{
            if($total < 0){
			return '';	
			}
            elseif($total == 1){
                return '<i class="icon-thumbs-up"></i>'._lang('by one');
            }else{
                return '<i class="icon-thumbs-up"></i>'.str_replace('XXX',$total,_lang(' by XXX others'));
            }
        }
    }	
 ?>