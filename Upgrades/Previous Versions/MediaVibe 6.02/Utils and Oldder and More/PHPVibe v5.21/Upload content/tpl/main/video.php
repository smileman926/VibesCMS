<?php the_sidebar(); do_action('pre-video');?>
<div class="video-holder row">
    <div class="<?php if(!has_list()){ echo "col-md-8 col-xs-12";} else {echo "row block player-in-list";}?> ">
        <div id="video-content" class="
            <?php if(has_list()){ echo "col-md-8 col-xs-12";} else {echo "row block";}?>">
            <div class="video-player pull-left 
                <?php rExternal() ?>">
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
        <div id="ListRelated" class="video-under-right nomargin pull-right col-md-4 col-xs-12">
            <?php do_action('before-listrelated'); ?>
            <div class="video-player-sidebar pull-left hide">
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
    <?php if(has_list()){ $next = guess_next(); ?>
    <div id="LH" class="row nomargin">
        <div class="playlistvibe">
            <div class="cute">
                <h1>
                    <?php  echo _html('Now playing:'); ?>
                    <span>
                        <?php  echo _html(_cut(list_title(_get('list')),260));?>
                    </span>
                </h1>
                <div class="cute-line"></div>
            </div>
            <div class="next-an list-next">
                <a class="fullit tipS" href="javascript:void(0)" title="<?php  echo _html('Resize player');?>">
                    <i id="flT" class="icon-arrows-alt"></i>
                </a>
                <a href="<?php echo $next['link'].'&list='._get('list'); ?>" class="tipS" title="<?php echo _html($next['title']);?>">
                    <i class="icon-forward"></i>
                </a>
                <a class="tipS" title="<?php  echo _html('Stop playlist to this video');?>" href="<?php  echo $canonical;?>">
                    <i class="icon-close"></i>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="rur video-under-right oboxed 
        <?php if(has_list()){ echo "mtop10";}?> pull-right col-md-4 col-xs-12">
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
        <div class="video-under col-md-8 col-xs-12">
            <div class="oboxed odet mtop10">
                <div class="row nomargin">
                    <?php do_action('before-video-title'); ?>
                    <h1>
                        <?php echo _html($video->title);?>
                    </h1>
                    <?php do_action('after-video-title'); ?>
                </div>
                <div class="full top10 bottom10">
                    <div class="pull-left user-box" style="">
                        <?php echo '
                        <a class="userav" href="'.profile_url($video->user_id,$video->owner).'" title="'.$video->owner.'">
                            <img src="'.thumb_fix($video->avatar, true, 60,50).'" />
                        </a>';?>
                        <div class="pull-right">
                            <?php echo '
                            <a class="" href="'.profile_url($video->user_id,$video->owner).'" title="'.$video->owner.'">
                                <h3>'.$video->owner.'</h3>
                            </a>';?>
                            <?php subscribe_box($video->user_id);?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="full bottom10">
                    <div class="likes-holder">
                        <div class="addiv">
                            <div class="aaa">
                                <a id="embedit" href="javascript:void(0)"  title="<?php echo _lang('Embed video');?>">
                                    <i class="icon-code"></i>
                                    <span class="hidden-xs">
                                        <?php echo _lang('Embed');?>
                                    </span>
                                </a>
                            </div>
                            <div class="aaa">
                                <a id="social-share" href="javascript:void(0)"  title=" <?php echo _lang('Share video');?>">
                                    <i class="icon-share"></i>
                                    <span class="hidden-xs">
                                        <?php echo _lang('Share');?>
                                    </span>
                                </a>
                            </div>
                            <?php if (is_user()) { ?>
                            <div class="aaa">
                                <a data-toggle="dropdown" id="dLabel" data-target="#" class="dropdown-toogle" title=" <?php echo _lang('Add To');?>">
                                    <i class="icon-plus"></i>
                                    <span class="hidden-xs hidden-sm">
                                        <?php echo _lang('Add to playlist');?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dLabel">
                                    <?php  $playlists=$cachedb->get_results("SELECT * from ".DB_PREFIX."playlists where owner='".user_id()."' and ptype = 1 and picture not in ('[likes]', '[history]', '[later]') limit 0,100");
			if($playlists){?>
                                    <?php  foreach($playlists as $pl){?>
                                    <li id="PAdd-<?php echo $pl->id; ?>">
                                        <a href="javascript:Padd(<?php echo $video->id; ?>,<?php echo $pl->id; ?>)">
                                            <i class="icon icon-square-o"></i>
                                            <?php  echo _html($pl->title);?>
                                        </a>
                                    </li>
                                    <?php }?>
                                    <?php }?>
                                    <li>
                                        <a href="<?php echo site_url().me; ?>&sk=new-playlist">
                                            <i class="icon icon-plus"></i>
                                            <?php  echo _lang("Create playlist");?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="aaa">
                                <a href="javascript:Padd(<?php echo $video->id; ?>,<?php echo later_playlist(); ?>)">
                                    <i class="icon icon-history"></i>
                                    <span class="hidden-xs hidden-sm">
                                        <?php  echo _lang("Watch later");?>
                                    </span>
                                </a>
                            </div>
                            <?php } ?>
                            <div class="aaa">
                                <a data-target="#report-it" data-toggle="modal" href="javascript:void(0)"  title=" <?php echo _lang('Report media');?>">
                                    <i class="icon-flag"></i>
                                    <span class="hidden-xs">
                                        <?php echo _lang('Report');?>
                                    </span>
                                </a>
                            </div>
                            <div class="aaa pull-right">
                                <a href="javascript:iHateThis(<?php echo $video->id;?>)" id="i-dislike-it" class="pv_tip dislikes" data-toggle="tooltip" data-placement="top" title=" <?php echo _lang('Dislike');?>">
                                    <i class="icon-thumbs-down"></i>
                                    <?php echo $video->disliked; ?>
                                </a>
                            </div>
                            <?php if(is_liked($video->id)) { ?>
                            <div class="aaa pull-right">
                                <a href="javascript:RemoveLike(<?php echo $video->id;?>)" id="i-like-it" class="isLiked pv_tip likes" title=" <?php echo _lang('Remove from liked');?>">
                                    <i class="icon-thumbs-up"></i>
                                    <?php echo $video->liked;?>
                                </a>
                            </div>
                            <?php } else { ?>
                            <div class="aaa pull-right">
                                <a href="javascript:iLikeThis(<?php echo $video->id;?>)" id="i-like-it" class="pv_tip likes" title=" <?php echo _lang('Like');?>">
                                    <i class="icon-thumbs-up"></i>
                                    <?php echo $video->liked;?>
                                </a>
                            </div>
                            <?php } ?>
                            <div class="like-box">
                                <div class="like-views">
                                    <?php echo number_format($video->views);?>
                                </div>
                                <div class="like-progress">
                                    <div class="likes-success" style="width: 
                                        <?php echo $likes_percent;?>%;">
                                    </div>
                                    <div class="likes-danger second" style="width: 
                                        <?php echo $dislikes_percent;?>%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="video-share mtop10 oboxed odet hide clearfix">
                <div class="form-group form-material floating">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon icon-link"></i>
                        </span>
                        <div class="form-control-wrap">
                            <input type="text" name="link-to-this" id="share-this-link" class="form-control" title="
                                <?php echo _lang('Link back');?>" value="
                                <?php echo canonical();?>" />
                                <label class="floating-label">
                                    <?php  echo _lang('Link to this');?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-material floating">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon icon-code"></i>
                            </span>
                            <div class="form-control-wrap">
                                <textarea style="min-height:120px" id="share-embed-code-small" name="embed-this" class="form-control" title=" <?php echo _lang('Embed this media on your page');?>">
                                    <iframe class="vibe_embed" width="100%" height="60%" src="
                                        <?php echo site_url().embedcode.'/'.$video->id.'/';?>" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true">
                                    </iframe>
                                    <?php echo '<script type="text/javascript" src="'.tpl().'js/vid.js"></script>';?>
                                </textarea>
                                <label class="floating-label">
                                    <?php  echo _lang('Embed (Responsive)');?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php do_action('before-social-box'); ?>
                <div class="sharing-icos mtop10 odet hide">
                    <ul class="share-body list-unstyled list-inline">
                        <li class="facebook">
                            <a target="_blank" class="icon-facebook" href="http://www.facebook.com/sharer.php?u=
                                <?php echo $canonical; ?>&amp;t=
                                <?php  echo _html(_cut($video->title,300));?>" title="">
                            </a>
                        </li>
                        <li class="twitter">
                            <a target="_blank" class="icon-twitter" href="http://twitter.com/home?status=
                                <?php echo $canonical; ?>" title="">
                            </a>
                        </li>
                        <li class="googleplus">
                            <a target="_blank" class="icon-google-plus" href="https://plus.google.com/share?url=
                                <?php echo $canonical; ?>" title="">
                            </a>
                        </li>
                        <li class="linkedin">
                            <a target="_blank" class="icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=
                                <?php echo $canonical; ?>" title="">
                            </a>
                        </li>
                        <li class="pinterest">
                            <a target="_blank" class="icon-pinterest" href="http://pinterest.com/pin/create/button/?url=
                                <?php echo $canonical; ?>&media=
                                <?php  echo thumb_fix($video->thumb); ?>&description=
                                <?php  echo _html(_cut($video->title,300));?>" title="">
                            </a>
                        </li>
                        <li class="whatsapp">
                            <a target="_blank" class="icon-whatsapp" href="whatsapp://send?text=
                                <?php echo _lang("Check this out: ").' '.$canonical; ?>" data-action="share/whatsapp/share">
                            </a>
                        </li>
                        <li class="fbxs">
                            <div class="fb-like" data-href="
                                <?php echo $canonical; ?>" data-width="124" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true">
                            </div>
                        </li>
                    </ul>
                </div>
                <?php do_action('before-description-box'); ?>
                <div class="mtop10 oboxed odet">
                    <ul class="list-unstyled">
                        <li>
                            <?php echo _lang("Uploaded"). ' '.time_ago($video->date); ?>
                            <?php echo _lang("in the category");?>
                            <a href="
                                <?php echo channel_url($video->category,$video->channel_name);?>" title="
                                <?php echo _html($video->channel_name);?>">
                                <span class="redText">
                                    <?php echo _html($video->channel_name);?>
                                </span>
                            </a>
                            <div id ="smallD" class="small">
                                <?php echo makeLn(_html(_cut($video->description,160)));?>...
                            </div>
                            <div id ="longD" class="small hide">
                                <?php echo makeLn(_html($video->description));?>
                            </div>
                        </li>
                        <?php if ($video->tags) { ?>
                        <li id="vTags" class="hide">
                            <?php echo pretty_tags($video->tags,'right20','
                            <i class="icon-hashtag right10"></i>','');?>
                        </li>
                        <?php } ?>
                    </ul>
                    <a id="revealDesc" href="javascript:void(0)">
                        <span class="show_more text-uppercase">
                            <?php echo _lang("show more");?>
                        </span>
                        <span class="show_more text-uppercase hide">
                            <?php echo _lang("show less");?>
                        </span>
                    </a>
                    <?php do_action('after-description-box'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="oboxed related-mobi mtop10 visible-sm visible-xs hidden-md hidden-lg">
                    <a id="revealRelated" href="javascript:void(0)">
                        <span class="show_more text-uppercase">
                            <?php echo _lang("show more");?>
                        </span>
                        <span class="show_more text-uppercase hide">
                            <?php echo _lang("show less");?>
                        </span>
                    </a>
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
    <!-- Start Report Sidebar -->
    <div class="modal fade" id="report-it" aria-hidden="true" aria-labelledby="report-it"
                        role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sidebar modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">
                        <?php  echo _lang('Report video');?>
                    </h4>
                </div>
                <div class="modal-body">
                    <?php if(!is_user()){?>
                    <p>
                        <?php  echo _lang('Please login in order to report media.');?>
                    </p>
                    <?php } elseif(is_user()){?>
                    <div class="ajax-form-result"></div>
                    <form class="horizontal-form ajax-form" action="
                        <?php echo site_url().'lib/ajax/report.php';?>" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id" value="
                            <?php  echo $video->id;?>" />
                            <input type="hidden" name="token" value="
                                <?php  echo $_SESSION['token'];?>" />
                                <div class="control-group" style="border-top: 1px solid #fff;">
                                    <label class="control-label">
                                        <?php  echo _lang('Reason for reporting');?>: 
                                    </div>
                                    <div class="controls">
                                        <div class="checkbox-custom checkbox-primary">
                                            <input type="checkbox" name="rep[]" value="
                                                <?php echo _lang('Media not playing');?>" class="checkbox-custom">
                                                <label>
                                                    <?php echo _lang('Video not playing');?>
                                                </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="rep[]" value="
                                                    <?php  echo _lang('Wrong title/description');?>" class="styled">
                                                    <label>
                                                        <?php  echo _lang('Wrong title/description');?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary">
                                                    <input type="checkbox" name="rep[]" value="
                                                        <?php  echo _lang('Media is offensive');?>" class="styled">
                                                        <label>
                                                            <?php echo _lang('Video is offensive');?>
                                                        </label>
                                                    </div>
                                                    <div class="checkbox-custom checkbox-primary">
                                                        <input type="checkbox" name="rep[]" value="
                                                            <?php  echo _lang('Media is restricted');?>" class="styled">
                                                            <label>
                                                                <?php echo _lang('Video is restricted');?>
                                                            </label>
                                                        </div>
                                                        <div class="checkbox-custom checkbox-primary">
                                                            <input type="checkbox" name="rep[]" value="
                                                                <?php  echo _lang('Copyrighted material');?>" class="styled">
                                                                <label>
                                                                    <?php  echo _lang('Copyrighted material');?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <textarea rows="5" cols="3" name="report-text" class="form-control"></textarea>
                                                            <p>
                                                                <strong>
                                                                    <?php  echo _lang('Required'); ?>
                                                                </strong> :
                                                                <?php  echo _lang('Tell us what is wrong with the video in a few words');?>
                                                            </p>
                                                            <div class="row mtop20 bottom10">
                                                                <button class="btn btn-primary btn-block" type="submit">
                                                                    <?php  echo _lang('Send report');?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                            <!-- End Report Sidebar -->
