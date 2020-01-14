<div class="row-fluid">
<?php
if(isset($_GET['delete-com'])) {
$id = intval($_GET['delete-com']);
$db->query("DELETE FROM ".DB_PREFIX."em_comments where id='".$id."'");
$db->query("DELETE FROM ".DB_PREFIX."activity where type = '7' and object='".$id."'");
echo '<div class="msg-info">Comment #'.$id.' deleted.</div>';
}  
if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
$db->query("DELETE FROM ".DB_PREFIX."em_comments where id in (".implode(',', $_POST['checkRow']).")");
}
echo '<div class="msg-info">Comments #'.implode(',', $_POST['checkRow']).' deleted.</div>';
}
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."em_comments");
$comments   = $db->get_results("SELECT ".DB_PREFIX."em_comments . * , ".DB_PREFIX."em_likes.vote , ".DB_PREFIX."users.name, ".DB_PREFIX."users.avatar
FROM ".DB_PREFIX."em_comments
LEFT JOIN ".DB_PREFIX."em_likes ON ".DB_PREFIX."em_comments.id = ".DB_PREFIX."em_likes.comment_id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."em_comments.sender_id = ".DB_PREFIX."users.id
ORDER BY  ".DB_PREFIX."em_comments.id desc ".this_limit()."");
if($comments) {
$ps = admin_url('comments').'&p=';
$here = $ps.this_page();
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
    
    $html = '<div class="comment-list block full">';
	 foreach( $comments as $comment) {
    $html .= ' <article id="comment-id-'.$comment->id.'" class="comment-item media arrow-left">
<a class="pull-left thumb-small com-avatar" href="'.profile_url($comment->sender_id,$comment->name).'"><img src="'.thumb_fix($comment->avatar).'"></a>
<section class="media-body pbody">
<header class="pbody-heading clearfix">
<a href="'.profile_url($comment->sender_id,$comment->name).'">'.print_data(stripslashes($comment->name)).'</a> - '.time_ago($comment->created).' <span class="text-muted m-l-small pull-right" id="iLikeThis_'.$comment->id.'"></span>

<div class="pull-right">
<input type="checkbox" name="checkRow[]" value="'.$comment->id.'" class="styled" />
</div>
<div class="pull-right" style="margin:0 10px">
<a class="confirm pull-right" href="'.$here.'&delete-com='.$comment->id.'"><i class="icon-trash"></i> Delete</a>
</div>

</header>
<div>'.print_data(stripslashes($comment->comment_text)).'
<p><a style="" href="'.video_url(str_replace('video_','',$comment->object_id),'comments').'#emContent_video_'.str_replace('video_','',$comment->object_id).'" target="_blank"><i class="icon-link"></i> View conversation</a></p>
</div>
                
              </section>
</article>
';
}
 $html .= '</div>';
$a->show_pages($ps); 
echo '
<form class="form-horizontal styled" action="'.$here.'" enctype="multipart/form-data" method="post">
<div class="row-fluid" style="margin: 20px 0; padding:2px; border: 1px solid #eee;">Check all :  <input type="checkbox" name="checkRows" class="styled check-all-notb" />
<button class="btn btn-large btn-danger pull-right" type="submit">'._lang("Delete selected").'</button>
</div>
<div class="clearfix">'.$html.'</div>
</form>
';
$a->show_pages($ps); 
} else {
echo '<div class="msg-note">Nothing here yet.</div>';
}
					
?>

				
</div>
<?php //End ?>