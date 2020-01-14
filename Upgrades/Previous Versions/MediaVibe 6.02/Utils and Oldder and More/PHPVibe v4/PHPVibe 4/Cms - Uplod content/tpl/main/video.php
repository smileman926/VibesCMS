<?php the_sidebar(); do_action('pre-video');?>
<div class="video-holder row-fluid">
<div class="<?php if(!has_list()){ echo "span77";} else {echo "row-fluid block player-in-list";}?> ">
<div id="video-content" class="<?php if(has_list()){ echo "span77";} else {echo "row-fluid block";}?>">
<div class="video-player pull-left <?php rExternal() ?>">
<?php do_action('before-videoplayer');
echo _ad('0','before-videoplayer');
echo the_embed(); 
echo _ad('0','after-videoplayer');
do_action('after-videoplayer');
?>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
<?php if(has_list()){ ?>
<div id="ListRelated" class="video-under-right nomargin pull-right span44">
<?php do_action('before-listrelated'); ?>
<div class="video-player-sidebar pull-left hide">
<?php if(has_list()){
$next = guess_next();
?>
<div class="video-header row-fluid list-header">
<span class="tt"><?php  echo _html(_cut(list_title(_get('list')),60));?></span>
<div class="next-an pull-right">
<a class="fullit tipS" href="javascript:void(0)" title="<?php  echo _html('Resize player');?>"><i id="flT" class="icon-resize-full"></i></a>
<a href="<?php echo $next['link'].'&list='._get('list'); ?>" class="tipS" title="<?php echo _html($next['title']);?>"><i class="icon-forward"></i></a>
<a class="tipS" title="<?php  echo _html('Stop playlist to this video');?>" href="<?php  echo $canonical;?>"><i class="icon-stop"></i></a></div>
</div>
<?php } ?>
<div class="items">
<ul>
<?php layout('layouts/list');  ?>
</ul>
</div>
<?php do_action('after-listrelated'); ?>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<div class="video-under-right oboxed <?php if(has_list()){ echo "mtop10";}?> pull-right span44">
<?php do_action('before-related');  echo _ad('0','related-videos-top');?>
<div class="relatedLoader sLoad">
<img src="<?php echo tpl(); ?>images/loaders.gif"/>
</div>
<div class="related video-related top10 related-with-list hide">
<ul>
<?php layout('layouts/related');
?>			
</ul>
</div>
<?php do_action('after-related'); ?>
</div>
<div class="video-under span77">
<div class="oboxed odet mtop10">
<div class="row-fluid nomargin">
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
<div>
<a id="embedit" href="javascript:void(0)"  title="<?php echo _lang('Embed video');?>"><i class="icon-share-alt"></i> <?php echo _lang('Embed Code');?></a>
</div>
<?php if (is_user()) { ?>
<div>
<a data-toggle="dropdown" id="dLabel" data-target="#" class="dropdown-toogle" title="<?php echo _lang('Add To');?>"><i class="icon-plus"></i> <?php echo _lang('Add to playlist');?></a>
<ul class="dropdown-menu" aria-labelledby="dLabel">
			<div class="triangle"></div>
			<?php  $playlists=$db->get_results("SELECT id, title from ".DB_PREFIX."playlists where owner='".user_id()."' and id not in (SELECT playlist from ".DB_PREFIX."playlist_data where video_id='".$video->id."') limit 0,10000");
			if($playlists){?>
<?php  foreach($playlists as $pl){?>
			<li id="PAdd-<?php echo $pl->id; ?>"><a href="javascript:Padd(<?php echo $video->id; ?>,<?php echo $pl->id; ?>)"><i class="icon-plus"></i><?php  echo _html($pl->title);?></a></li>
			
		<?php }?>
		<?php }?>
		</ul>
</div>
<?php } ?>
<div>
<a id="report" href="javascript:void(0)"  title="<?php echo _lang('Report media');?>"><i class="icon-flag"></i> <?php echo _lang('Report');?></a>
</div>
<div class="clearfix"></div>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
<div class="video-share mtop10 oboxed odet hide clearfix">    
        
    <ul class="share-top">
    	<li class="share-sign smt"><?php  echo _lang('Link');?></li>
        <li class="share-link span10"><div class="share-link-input"><input type="text" name="link-to-this" id="share-this-link" class="span12" title="<?php echo _lang('Link to video');?>" value="<?php echo canonical();?>" /> </div></li>
    </ul>
	    <ul class="share-top">
    	<li class="share-sign smt"><?php  echo _lang('Embed');?></li>
        <li class="share-link span10"><div class="share-link-input"><textarea id="share-embed-code-small" name="embed-this" class="span12" title="<?php echo _lang('Embed this video (responsive size)');?>"><iframe class="vibe_embed" width="100%" height="60%" src="<?php echo site_url().embedcode.'/'.$video->id.'/';?>" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe> <?php echo '<script type="text/javascript" src="'.tpl().'js/vid.js"></script>';?></textarea> </div></li>
    </ul>
</div>
<div id="report-it" class="oboxed mtop10 odet clearfix">
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
<?php do_action('before-description-box'); ?>
<div class="mtop10 oboxed odet">
<div class="full bottom10">	
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
</div>
<script type="text/javascript">
$(document).ready(function(){
	DOtrackview(<?php echo $video->id; ?>);
});
</script>
</div>
<?php do_action('post-video'); ?>