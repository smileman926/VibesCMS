<?php the_sidebar(); do_action('pre-video');?>


<div class="video-under span77">
<div class="oboxed odet">
<?php if ($video->media ==  3) { 
do_action('before-image');
echo $embedvideo; 
do_action('after-image');
} ?>
<div class="video-header row-fluid nomargin">
<?php do_action('before-video-title'); ?>
<h1><?php echo _html($video->title);?></h1>
<?php do_action('after-video-title'); ?>
</div>
<div class="full top10 bottom10">
<div class="pull-left user-box" style="">
<?php echo '<a class="userav" href="'.profile_url($video->user_id,$video->owner).'" title="'.$video->owner.'"><img src="'.thumb_fix($video->avatar, true, 60,50).'" /></a>';?>
<div class="pull-right">
<?php echo '<a class="" href="'.profile_url($video->user_id,$video->owner).'" title="'.$video->owner.'"><h3>'.$video->owner.'</h3></a>';?>
<?php subscribe_box($video->user_id);?>
</div>
</div>

<div class="like-box pull-right">
<div class="like-views pull-right">
<?php echo number_format($video->views);?>
</div>
<div class="progress progress-micro"><div class="bar bar-success" style="width: <?php echo $likes_percent;?>%;"></div>
<div class="bar bar-danger second" style="width: <?php echo $dislikes_percent;?>%;"></div></div>
<div class="like-show">
<?php if(is_liked($video->id)) { ?>
<a href="javascript:RemoveLike(<?php echo $video->id;?>)" id="i-like-it" class="isLiked tipE likes" title="<?php echo _lang('Remove from liked');?>"><i class="icon-thumbs-up"></i></a> <?php echo $video->liked;?> 
<?php } else { ?>
<a href="javascript:iLikeThis(<?php echo $video->id;?>)" id="i-like-it" class="tipE likes" title="<?php echo _lang('Like');?>"><i class="icon-thumbs-up"></i></a> <?php echo $video->liked;?> 
<?php } ?>
<a href="javascript:iHateThis(<?php echo $video->id;?>)" id="i-dislike-it" class="pv_tip dislikes" data-toggle="tooltip" data-placement="right" title="<?php echo _lang('Dislike');?>"><i class="icon-thumbs-down"></i></a> <?php echo $video->disliked; ?>
</div>
</div>	
<div class="clearfix"></div>
</div>
<div class="full bottom10">
<div class="likes-holder">
<div class="addiv">
<a id="embedit" href="javascript:void(0)" class="tipW" title="<?php echo _lang('Embed video');?>"><i class="icon-share-alt"></i> <?php echo _lang('Embed Code');?></a>
<?php if ($video->media <>  3) { 
/* prevent image adding to playlists */
?>
<a id="addtolist" href="javascript:void(0)" class="tipW" title="<?php echo _lang('Add To');?>"><i class="icon-plus"></i> <?php echo _lang('Add to playlist');?></a>
<?php } ?>
<a id="report" href="javascript:void(0)" class="tipW" title="<?php echo _lang('Report media');?>"><i class="icon-flag"></i> <?php echo _lang('Report');?></a>
</div>
</div>
</div>
<div class="clearfix"></div>
</div>
<div class="video-share mtop10 oboxed odet hide clearfix">    
        
    <ul class="share-top">
    	<li class="share-sign"><i class="icon-link"></i></li>
        <li class="share-link span11 nomargin"><div class="share-link-input"><input type="text" name="link-to-this" id="share-this-link" class="span12" title="<?php echo _lang('Link to video');?>" value="<?php echo canonical();?>" /> </div></li>
    </ul>
	    <ul class="share-top">
    	<li class="share-sign smt">480x360</li>
        <li class="share-link span11 nomargin"><div class="share-link-input"><input type="text" id="share-embed-code-small" name="embed-this" class="span12" title="<?php echo _lang('Embed this video as 480x360');?>" value='<iframe width="480" height="360" src="<?php echo site_url().embedcode.'/'.$video->id.'/';?>" frameborder="0" allowfullscreen></iframe>' /> </div></li>
    </ul>
	 <ul class="share-top">
    	<li class="share-sign smt">640x480</li>
        <li class="share-link span11 nomargin"><div class="share-link-input"><input type="text" id="share-embed-code-large" name="embed-this" class="span12" title="<?php echo _lang('Embed this video as 640x480');?>" value='<iframe width="640" height="480" src="<?php echo site_url().embedcode.'/'.$video->id.'/';?>" frameborder="0" allowfullscreen></iframe>' /> </div></li>
    </ul>
</div>


<div id="report-it" class="oboxed mtop10 odet clearfix">
<div class="form-name"><i class="icon-flag"></i><?php echo _lang('Report');?>  </div>
<?php if(!is_user()){?>
<p><?php  echo _lang('Please login in order to report media.');?></p>
<?php } elseif(is_user()){?>
<div class="ajax-form-result"></div>  
<form class="horizontal-form ajax-form" action="<?php echo site_url().'lib/ajax/report.php';?>" enctype="multipart/form-data" method="post">
<input type="hidden" name="id" value="<?php  echo $video->id;?>" />
<input type="hidden" name="token" value="<?php  echo $_SESSION['token'];?>" />
<div class="control-group" style="border-top: 1px solid #fff;">
<label class="control-label"><?php  echo _lang('Reason for reporting');?>: </label>
<div class="controls">
<label class="checkbox">
<input type="checkbox" name="rep[]" value="<?php echo _lang('Video not playing');?>" class="styled"><?php echo _lang('Video not playing');?>
</label>
<label class="checkbox">
<input type="checkbox" name="rep[]" value="<?php  echo _lang('Wrong title/description');?>" class="styled"> <?php  echo _lang('Wrong title/description');?>
</label>
<label class="checkbox">
<input type="checkbox" name="rep[]" value="<?php  echo _lang('Video is offensive');?>" class="styled"> <?php echo _lang('Video is offensive');?>
</label>
<label class="checkbox">
<input type="checkbox" name="rep[]" value="<?php  echo _lang('Video is restricted');?>" class="styled"><?php echo _lang('Video is restricted');?>
</label>
<label class="checkbox">
<input type="checkbox" name="rep[]" value="<?php  echo _lang('Copyrighted material');?>" class="styled"> <?php  echo _lang('Copyrighted material');?>
</label>
									
</div>										

</div>
<div class="control-group">
<label class="control-label"><?php  echo _lang('Details of the report');?></label>
<div class="controls">
<textarea rows="5" cols="3" name="report-text" class="full auto"></textarea>
</div>
</div>
<button class="btn btn-primary pull-right" type="submit"><?php  echo _lang('Send report');?>	</button> 
</form>		
<?php } ?>							
</div>
<div id="bookit" class="oboxed odet mtop10 clearfix">
<div class="form-name"><i class="icon-plus"></i><?php echo _lang('Add to playlist(s)');?>  </div>
<?php if(!is_user()){?>
<p><?php  echo _lang('Please login in order to collect videos.');?></p>
<?php }elseif(is_user()){?>
<div class="ajax-form-result"></div>  
<form class="horizontal-form ajax-form" action="<?php
 echo site_url().'lib/ajax/playlist-add.php';?>" enctype="multipart/form-data" method="post">
<input type="hidden" name="vp-id" value="<?php  echo $video->id;?>" />
<input type="hidden" name="token" value="<?php  echo $_SESSION['token'];?>" />
<?php  $playlists=$db->get_results("SELECT id, title from ".DB_PREFIX."playlists where owner='".user_id()."' and id not in (SELECT playlist from ".DB_PREFIX."playlist_data where video_id='".$video->id."') limit 0,10000");if($playlists){?>

<select id='multisel' name="booked[]" multiple='multiple'>
<?php  foreach($playlists as $pl){?>
<option value="<?php  echo $pl->id;?>"><?php  echo _html($pl->title);?></option>                            
<?php }?>
</select>				
<div class="bottom20 top10 clearfix">
<button class="btn btn-primary" type="submit"><?php  echo _lang('Collect in selected');?>	</button>
</div>	              
<?php }else{echo _lang('You have no playlists that don\'t contain this video');}?>	
 	<div class="clearfix"></div>
</form> 
<?php }?>			  
<div class="clearfix"></div>
</div>	     
<div class="mtop10 oboxed odet">
<div class="full bottom10">	
<?php do_action('before-description-box'); ?>
    <ul class="share-body">
    
        <li class="facebook">
        <a target="_blank" class="icon-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $canonical; ?>&amp;t=<?php  echo _html(_cut($video->title,300));?>" title=""></a>
        </li>
        
		<li class="twitter">
        <a target="_blank" class="icon-twitter" href="http://twitter.com/home?status=<?php echo $canonical; ?>" title=""></a>
        </li>
        
        <li class="googleplus">
        <a target="_blank" class="icon-google-plus" href="https://plus.google.com/share?url=<?php echo $canonical; ?>" title=""></a>
        </li>
        
        <li class="linkedin">
        <a target="_blank" class="icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $canonical; ?>" title=""></a>
        </li>
        
    	<li class="pinterest">
        <a target="_blank" class="icon-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $canonical; ?>&media=<?php  echo thumb_fix($video->thumb); ?>&description=<?php  echo _html(_cut($video->title,300));?>" title=""></a>
        </li>
<li class="fbxs">
		<div class="fb-like" data-href="<?php echo $canonical; ?>" data-width="124" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
		</li>		
        
    </ul>
        
    </div>

<ul>
<li> <?php echo _lang("Uploaded"). ' '.time_ago($video->date); ?> <?php echo _lang("in the category");?> <a href="<?php echo channel_url($video->category,$video->channel_name);?>" title="<?php echo _html($video->channel_name);?>"><span class="redText"><?php echo _html($video->channel_name);?></span></a> 
<span class="small-text"><?php echo makeLn(_html($video->description));?></span>
</li>
<?php if ($video->tags) { ?><li> <?php echo pretty_tags($video->tags,'innerR','<i class="icon-tag" style="font-size: 10pt; margin:0 10px;"></i>','');?></li> <?php } ?>
</ul>
<?php do_action('after-description-box'); ?>
</div>


<div class="clearfix"></div>
<div class="oboxed ocoms mtop10">
<?php echo _ad('0','top-of-comments');?>
<?php do_action('before-comments'); ?>
<?php echo comments();?>
<?php do_action('after-comments'); ?>
</div>
<div class="clearfix"></div>
</div>
<div class="video-under-right oboxed <?php if(has_list()){ echo "mtop10";}?> pull-right span44">
<?php do_action('before-related');  echo _ad('0','related-videos-top');?>
<div class="related video-related top10 related-with-list">
<ul>
<?php layout('layouts/related');
?>			
</ul>
</div>
<?php do_action('after-related'); ?>
</div>

</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
DOtrackview(<?php echo $video->id; ?>);
});
</script>
<?php do_action('post-video'); ?>