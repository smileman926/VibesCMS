<?php the_sidebar(); 
      do_action('pre-image');?>
	  <script type="text/javascript">
$(document).ready(function(){
	DOtrackviewIMG(<?php echo $image->id; ?>);
});
</script>
<div class="row">
    <div class="col-md-8 col-md-offset-2 image-holder">
        <div class="the-image block full">
            <div class="block img-helpers">
                <div class="block" style="position:relative;">
                    <a class="btn btn-pure text-danger" href="javascript:iHeartThis(<?php echo $image->id; ?>)">
                        <i class="icon icon-heart"></i>
                    </a>
					<a class="btn btn-pure" data-toggle="dropdown" data-target="#" class="dropdown-toogle dropdown-left tipS" title="<?php echo _lang('Add to album');?>">
                        <i class="icon-reorder"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <?php  $albums=$cachedb->get_results("SELECT id,title from ".DB_PREFIX."playlists where owner='".user_id()."' and picture not in ('[likes]', '[history]', '[later]') and ptype > 1 limit 0,100");
			if($albums){ foreach($albums as $pl){?>
                        <li id="PAdd-<?php echo $pl->id; ?>"><a href="javascript:Padd(<?php echo $image->id; ?>,<?php echo $pl->id; ?>)">
                                <i class="icon icon-square-o"></i>
                                <?php  echo _html($pl->title);?>
                            </a>
                        </li>
                        <?php }?>
                        <?php }?>
                        <li class="divider" role="presentation"></li>
                        <li>
                            <a href="
                                <?php echo site_url().me; ?>?sk=new-album">
                                <i class="icon icon-plus"></i>
                                <?php  echo _lang("Create album");?>
                            </a>
                        </li>
                    </ul>
                </div>
           </div>
            <?php  do_action('before-image');
                   echo $embedimage; 
                   do_action('after-image');
            ?>
			<a class="rm_next tipS hide"  data-placement="right" href="#"></a>
			<a class="rm_prev tipS hide"  data-placement="left" href="#"></a>
        </div>
		<div class="playlistvibe">
		 <?php do_action('before-image-title'); ?>
            <div class="cute">
            <h1>          
         <?php echo _html($image->title);?>		 
			</h1>
            <div class="cute-line"></div>
            </div>
			 <?php do_action('after-image-title'); ?>   
			<div class="block full img-owner odet">
	<?php echo '<a href="'.profile_url($image->user_id, $image->owner).'" class="owner-avatar"><img class="owner-avatar img-circle" src="'.thumb_fix($image->avatar, true, 35, 35).'"/>
		<span class="owner-name text-action">@'.$image->owner.'</span>
		</a>'; ?>
		<?php subscribe_box($image->user_id);?>
		</div>
    </div>
  
        <div class="block full img-inner odet">
		
            
            <?php do_action('before-social-box'); ?>
            <div class="sharing-icons">
                <ul class="share-body list-unstyled list-inline">
                    <li class="facebook">
                        <a target="_blank" class="icon-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $canonical; ?>&amp;t=
                            <?php  echo _html(_cut($video->title,300));?>" title="">
                        </a>
                    </li>
                    <li class="twitter">
                        <a target="_blank" class="icon-twitter" href="http://twitter.com/home?status=<?php echo $canonical; ?>" title="">
                        </a>
                    </li>
                    <li class="googleplus">
                        <a target="_blank" class="icon-google-plus" href="https://plus.google.com/share?url=<?php echo $canonical; ?>" title="">
                        </a>
                    </li>
                    <li class="linkedin">
                        <a target="_blank" class="icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $canonical; ?>" title=""></a>
                    </li>
                    <li class="pinterest">
                        <a target="_blank" class="icon-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $canonical; ?>description=<?php  echo _html(_cut($video->title,300));?>" title="">
                        </a>
                    </li>
                    <li class="whatsapp">
                        <a target="_blank" class="icon-whatsapp" href="whatsapp://send?text=<?php echo _lang("Check this out: ").' '.$canonical; ?>" data-action="share/whatsapp/share"></a>
                    </li>
                    <li class="fbxs">
                        <div class="fb-like" data-href="<?php echo $canonical; ?>" data-width="124" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true">
                        </div>
                    </li>
                </ul>
            </div>			
            <ul class="list-unstyled">
			<li>
			<div class="block full text-right mtop10"><i class="icon icon-heart mright10"></i><span class="mright20 right10"><?php echo $image->liked;?></span>
			<i class="icon icon-eye mright10 mleft20"></i><span class=""><?php echo $image->views;?></span>
			</div>

			</li>
                <li>
                    <?php echo _lang("Shared"). ' '.time_ago($image->date); ?>
                </li>
                <li>
                    <?php echo _lang("Label: ");?>
                    <a href="
                        <?php echo channel_url($image->category,$image->channel_name);?>" title="<?php echo _html($image->channel_name);?>"><?php echo _html($image->channel_name);?></a>
                    <?php if ($image->tags) { ?>
                    <li>
                        <?php echo pretty_imgtags($image->tags,'right20','
                        <i class="icon-hashtag right10"></i>','');?>
                    </li>
                    <?php } ?>
                    <li>
                        <div id ="smallD" class="small">
                            <?php echo makeLn(_html(_cut($image->description,160)));?>...
                        </div>
                        <div id ="longD" class="small hide">
                            <?php echo makeLn(_html($image->description));?>
                        </div>
                    </li>
                </ul>
                <?php if ($image->description && (strlen($image->description) > 160)) { ?>
                <a id="revealDesc" href="javascript:void(0)">
                    <span class="show_more text-uppercase">
                        <?php echo _lang("show more");?>
                    </span>
                    <span class="show_more text-uppercase hide">
                        <?php echo _lang("show less");?>
                    </span>
                </a>
                <?php } ?>
                <?php do_action('before-description-box'); ?>
				<?php $collections = $db->get_results("select * FROM ".DB_PREFIX."playlists WHERE ptype='2' and id in (Select distinct(playlist) from ".DB_PREFIX."playlist_data where video_id='".$image->id."' ) ");
				//$db->debug();
				if($collections) {
				echo '<h5 class="collin">'._lang("Collected in").'</h5>
				<ul class="list-group list-group-full">';
                  foreach ($collections as $pl) {
				$title = _html(_cut($pl->title, 170));
			    $full_title = _html(str_replace("\"", "",$pl->title));			
			    $url = playlist_url($pl->id , $pl->title);
				?>
				
                  <li class="list-group-item colist">
                    <div class="media">
                      <div class="media-left">
                        <a class="avatar" href="<?php echo $url; ?>">
                          <img class="img-responsive" src="<?php echo thumb_fix($pl->picture, true, 45,35); ?>" alt="<?php echo $full_title;?>"></a>
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading"><i class="icon icon-reorder"></i><?php echo $full_title;?></h4>
                        <p><?php echo _html(_cut($pl->description,170));?></p>
                      </div>
                    </div>
                  </li>
				  <?php } ?>
				  </ul>
				<?php } ?>
                <?php echo _ad('0','top-of-comments');?>
                <?php do_action('before-comments'); ?>
                <?php echo comments('img-'.$image->id);?>
                <?php do_action('after-comments'); ?>
            </div>
        </div>
    </div>
</div>	

    <?php layout('layouts/relatedimages');?>

  <script>
 $(document).ready(function() {
if ($(".image-item").length) {
var NILink = $(".image-item:first").find("a.clip-link").attr("href");
var NITitle = $(".image-item:first").find("a.clip-link").attr("title");
$(".rm_next").attr("title",NITitle).attr("href",NILink).data('title',NITitle).removeClass('hide');
}
<?php 
if(isset($_SESSION['lastImg'])) {
$lastImg = maybe_unserialize($_SESSION['lastImg']);	
if(not_empty($lastImg) && isset($lastImg['url'])) { 
echo '$(".rm_prev").attr("title","'.$lastImg['title'].'").attr("href","'.site_url().urldecode($lastImg['url']).'");
$(".rm_prev").data("title","'.$lastImg['title'].'").removeClass("hide");';
                             }
}
?>	 
 });
 </script>
 
 <?php //Set previous
 $this_img = array(
 'url' => urlencode(str_replace(site_url(),"",$canonical)),
 'title'=>$image->title 
 );
$_SESSION['lastImg'] = maybe_serialize($this_img);
 ?>
