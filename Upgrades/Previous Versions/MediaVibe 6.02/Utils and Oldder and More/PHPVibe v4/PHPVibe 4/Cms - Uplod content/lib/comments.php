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
<span class="arrow"> </span>
<a class="name" href="'.profile_url(user_id(), user_name()).'">'.user_name().'</a>
                <form class="body" method="post" action="'.site_url().'ajax/addComment.php" onsubmit="return false;">
				 <textarea placeholder="'._lang('Write your '.$comtra).'" id="addEmComment_'.$to.'" class="addEmComment auto" name="comment" /></textarea>
				  <button type="submit" title="'._lang("Post").'" class="btn btn-default btn-small buttonS pull-right tipS" id="emAddButton_'.$cobj.'" onclick="addEMComment(\''.$cobj.'\',\''.$to.'\')" /><i class="icon-send"></i></button>
                   <input type="hidden" name="object_id" value="'.$cobj.'" />
				   <input type="hidden" name="reply_to" value="'.$to.'" />
   </form>
  </div></li>';
} elseif($to < 1) {
	$uhtml .= '<li id="reply-'.$cobj.'-'.$to.'" class="addcm '.$xtra.'">
	<img class="avatar" src="'.thumb_fix(site_url().'uploads/def-avatar.jpg', true, 55, 55).'">
	<div class="message clearfix">
<span class="arrow"> </span>
<a class="name" href="'.site_url().'login/">'._lang("Guest").'</a>
                <form class="body" method="post" onsubmit="return false;">
				 <textarea placeholder="'._lang("Register or login to leave your impressions.").'" id="addEmComment_'.$to.'" class="addEmComment auto" name="comment" disabled="disabled"/></textarea>
				  <p><a href="'.site_url().'login/">'._lang("Login here to comment.").'</a></p>
   </form>
  </div></li>';
}
return $uhtml; 	
}
function show_comments($object_id, $limit=50000, $moreurl = null, $ALLOWLIKE = true) {
global $db,$cobj;
$CCOUNT = $limit;
$uhtml = '';
//get comments from database
$totals = $db->get_row("SELECT count(*) as nr from ".DB_PREFIX."em_comments WHERE object_id =  '".$object_id."'");
$html     = '<ul id="emContent_'.$object_id.'-0" class="chat full">
<div class="cctotal">'._lang("Comments").' ('.$totals->nr.')</div>
';

$html .=  reply_box();
if($totals->nr > 0) {
$comments   = $db->get_results("SELECT ".DB_PREFIX."em_comments . * , ".DB_PREFIX."em_likes.vote , ".DB_PREFIX."users.name, ".DB_PREFIX."users.avatar
FROM ".DB_PREFIX."em_comments
LEFT JOIN ".DB_PREFIX."em_likes ON ".DB_PREFIX."em_comments.id = ".DB_PREFIX."em_likes.comment_id and ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."em_likes.sender_ip
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."users.id
WHERE object_id =  '".$object_id."'
ORDER BY  ".DB_PREFIX."em_comments.id desc limit 0,".$limit."");
if($comments) {
$ci = 1;
$cmp = array();
	 foreach( $comments as $comment) {
	$cls = ($comment->reply < 1) ? '<li class="reply-btn"><a href="javascript:ReplyCom(\'reply-'.$cobj.'-'.$comment->id.'\')"><i class="icon-reply"></i>'._lang("Reply").'</a></li>' : '';
	 if($comment->vote && is_user()){            
            $likeText = commentLikeText($comment->rating_cache -1,true);
        }else{
            $likeText = '<a class="tipS" href="javascript:iLikeThisComment('.$comment->id.')" title="'._lang('Like this comment').'"> <i class="icon-heart-o"></i> '._lang('Like').' </a>';
            if($comment->rating_cache){
                $likeText .= ' &mdash; '.commentLikeText($comment->rating_cache,false);
            }
        }
	$cmp[$comment->reply][$comment->id]['id']=$comment->id;
    $cmp[$comment->reply][$comment->id]['body']= ' <li id="comment-id-'.$comment->id.'" class="left">
<img class="avatar" src="'.thumb_fix($comment->avatar, true, 55, 55).'">
<div class="message">
<span class="arrow"> </span>
<a class="name" href="'.profile_url($comment->sender_id,$comment->name).'">'._html($comment->name).'</a> 
<span class="body">'._html($comment->comment_text).'</span>
<ul class="list-inline">
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
                return _lang('You like this');
            }elseif($total == 1){
                return _lang('You +1 like this');
            }else{
                return str_replace('XXX',$total,_lang('You and XXX others like this'));
            }       
        }else{
            if($total < 0){
			return '';	
			}
            elseif($total == 1){
                return _lang('One like');
            }else{
                return str_replace('XXX',$total,_lang('XXX like this'));
            }
        }
    }	
 ?>