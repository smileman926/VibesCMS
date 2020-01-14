<?php echo the_sidebar(); ?> 
 <ul class="nav nav-tabs nav-tabs-line hidden-md hidden-lg visible-xs visible-sm" id="myTabs" role="tablist">
 <li class="active"><a data-toggle="tab" href="#DashContent" role="tab"> <?php echo _lang('Dashboard'); ?></a></li>
 <li class=""><a data-toggle="tab" href="#DashSidebar" role="tab"><?php echo _lang('Menu'); ?></a></li>
 </ul>
  <script>
 $(document).ready(function() {
	 if ($(window).width() < 972) {
		 $('#DashContent').addClass('tab-pane active');
		 $('#DashSidebar').addClass('tab-pane');
		 $('#myTab a,#myTabs a').click(function (e) {
           e.preventDefault();
           $(this).tab('show');
         });
	 }
	 });
 </script>
  <div id="theHolder" class="row tab-content"> 
 <div id="DashContent" class="col-md-10 col-xs-12 isBoxed"> <?php echo default_content(); 
 $module = isset($_GET['sk']) ? $_GET['sk'] : ''; 
 switch($module) { 
 case "subscriptions":
  $payments = $db->get_results("select * from ".DB_PREFIX."user_subscriptions where user_id ='".user_id()."' limit 0,100"); 
if (!function_exists('obfuscate_email')) {
function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1), '@');
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}	
}
echo '<h2>'._lang("Premium subscriptions").'</h2>';
if( !is_empty(premium_upto())) {
	if (new DateTime() > new DateTime(premium_upto())) {	
	echo'<div class="block isBoxed msg-content msg-warning mbot20"> <a href="'.site_url().'payment"> <i class="material-icons">&#xE8D0;</i> '._lang("Premium expired on").' '.premium_upto().' </a></div>';	
	}else {
	echo'<div class="block isBoxed msg-content msg-win mbot20"> <a href="'.site_url().'payment"> <i class="material-icons">&#xE8D0;</i> '._lang("You are a premium member until").' '.premium_upto().' </a></div>';	

	}	
	}elseif( is_empty(premium_upto()) && !is_moderator()) {
	echo'<div class="block isBoxed msg-content msg-hint mbot20"> <a href="'.site_url().'payment"><i class="material-icons">&#xE8D0;</i> '._lang("Why not try premium?").'</a></div>';	
	}
if($payments) {
	foreach ($payments as $paid) {
echo '<div class="block isBoxed msg-content msg-win mbot20">
<span class="badge">'.$paid->payment_gross.' '.$paid->currency_code.' </span> '.time_ago($paid->valid_from).' '._lang('with').' '.$paid->payment_method.' '.obfuscate_email($paid->payer_email).'  '._lang('for premium until').' '.$paid->valid_to.'
     </div>';
	}
	
} else {
echo '<div class="block isBoxed msg-content msg-note mbot20">'._lang("No past subscriptions").'</div>';

}
 break;
 case "videos":    
 case "music":    
 default: 
 if(_get("sk") == "music") { $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where user_id ='".user_id()."' and media > 1 and pub > 0"); 
 } else { 
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where user_id ='".user_id()."' and media < 2 and pub > 0"); 
 } ?> 
 <div class="row blc mIdent"> 
 <div class="col-md-3">   
 <div class="iholder bg-facebook">     
<?php 
 if(_get("sk") == "music") { ?>
 <i class="icon-headphones"></i>   
<?php } else { ?>
<i class="material-icons">&#xE04A;</i>
<?php } ?>
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1">   
 <h1><?php 
 if(_get("sk") == "music") { echo _lang("Music manager"); } else { 
 echo _lang("Video manager"); }?></h1> 
 <?php echo _lang("Media shared by you,").' '.user_name();?>   
 <p> 
   <?php echo $count->nr; ?> <?php echo _lang("entries.");?>       
 <p> 
 </div>  
 </div>  
 <?php 
 if(_get("sk") == "music") {
	 $videos = $db->get_results("select id,title,thumb, views, liked, duration from ".DB_PREFIX."videos where user_id ='".user_id()."' and pub > 0 and media > 1 ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit().""); 
	 } else { 
 $videos = $db->get_results("select id,title,thumb, views, liked, duration from ".DB_PREFIX."videos where user_id ='".user_id()."' and pub > 0 and media < 2 ORDER BY ".DB_PREFIX."videos.id DESC ".this_limit().""); 
     } 
 
 if($videos) { 
 
 if(_get("sk") == "music") {
	 $ps = site_url().me.'/?sk=music&p='; 
	 } else { 
    $ps = site_url().me.'/?sk=videos&p=';
   } 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); ?>   
 <form class="styled" action="<?php echo site_url().me;?>?sk=<?php echo _get('sk');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">       
 <div class="cleafix full">
 </div>  
 <div class="row top10 left10"> 
 <div class="div div-checks">    
 <div class="item-in-list list-header">     
  <div class="segment">
 <div class="checkbox-custom checkbox-primary"> 
 <input type="checkbox" name="checkRows" class="check-all" /> 
 <label></label> Select all
 </div>  
  </div>       
	 <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
	  </div>
   </div>
	<div class="content--items">
	<?php foreach ($videos as $video) { ?>    
		 <div class="item-in-list">     
		 <div class="segment"> 
		 <div class="checkbox-custom checkbox-primary"> 
		 <input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" /> 
		 <label></label> 
		 </div>  
		 </div> 
		 <div class="segment"> 
		 <a class="" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"> <img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style=""> </a> 
		 <a class="content-title mleft10" target="_blank" href="<?php echo video_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><strong><?php echo _html($video->title); ?></strong> </a> 
		 </div> 
		 <div class="segment"> 
		 <div class="btn-group">     
		 <a class="btn  btn-primary " href="<?php echo site_url().me;?>?sk=edit-video&vid=<?php echo $video->id;?>" title="<?php echo _lang("Edit"); ?>"><i class="icon-pencil" style=""></i></a>     
		 <a class="btn  btn-default btn-outline " href="<?php echo site_url().me;?>?sk=videos&p=<?php echo this_page();?>&delete-video=<?php echo $video->id;?>" title="<?php echo _lang("Unpublish"); ?>"><i class="icon-trash" style=""></i></a> 
		 </div>  
		 </div> 
		 </div>
	 <?php } ?>        
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "images":    
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."images where user_id ='".user_id()."'"); ?> 
 <div class="row blc mIdent"> 
 <div class="col-md-3">
 <div class="iholder bg-facebook">    <i class="icon-film"></i>
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1">
 <h1><?php echo _lang("Images manager");?></h1> 
<?php echo _lang("Images shared by you,").' '.user_name();?>
 <p> 
<?php echo $count->nr; ?> <?php echo _lang("entries.");?>    
 <p> 
 </div>  
 </div>  
<?php $images = $db->get_results("select id,title,thumb, views, liked from ".DB_PREFIX."images where user_id ='".user_id()."' and pub > 0 ORDER BY ".DB_PREFIX."images.id DESC ".this_limit().""); 
 if($images) { $ps = site_url().me.'/?sk=images&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); ?>
 <form class="styled" action="<?php echo site_url().me;?>?sk=images&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">    
 <div class="cleafix full">
 </div>  
 <div class="row top10 left10"> 
 <div class="div div-checks">  
 <div class="item-in-list list-header">     
	 <div class="segment">
	 <div class="checkbox-custom checkbox-primary"> 
	 <input type="checkbox" name="checkRows" class="check-all" /> 
	 <label></label> Select all
	 </div>  
	  </div>       
	  <div class="segment">  
		  <button class="btn btn-danger btn-sm" type="submit"> 
		  <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
		  </button>  	   
		  </div>
   </div> 
	<div class="content--items"> <?php foreach ($images as $image) { ?> 
		 <div class="item-in-list">
		 <div class="segment">         
		 <div class="checkbox-custom checkbox-primary"> 
		 <input type="checkbox" name="imagesRow[]" value="<?php echo $image->id; ?>" /> 
		 <label></label> 
		 </div>  
		 </div> 
		 <div class="segment"> 
		 <a class="" target="_blank" href="<?php echo image_url($image->id, $image->title);?>" title="<?php echo _lang("View"); ?>"> <img src="<?php echo thumb_fix($image->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style=""> </a> 
		 <a class="content-title" target="_blank" href="<?php echo image_url($image->id, $image->title);?>" title="<?php echo _lang("View"); ?>"><strong><?php echo _html($image->title); ?></strong> </a> 
		 </div> 
		 <div class="segment"> 
		 <div class="btn-group">
		 <a class="btn btn-sm btn-primary " href="<?php echo site_url().me;?>?sk=edit-image&vid=<?php echo $image->id;?>" title="<?php echo _lang("Edit"); ?>">    <i class="icon-pencil" style=""></i></a>
		 <a class="btn btn-sm btn-default btn-outline " href="<?php echo site_url().me;?>?sk=images&p=<?php echo this_page();?>&delete-image=<?php echo $image->id;?>" title="<?php echo _lang("Unpublish"); ?>">    <i class="icon-trash" style=""></i></a> 
		 </div>  
		 </div> 
		</div>
	<?php } ?>     
	</div>   
 </div>  
 </div>  
 </form> 
    <?php  $a->show_pages($ps); } 
 break; 
 case "likes": 
 $likes_playlist = likes_playlist(); 
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."playlist_data where ".DB_PREFIX."playlist_data.playlist = '".$likes_playlist."'"); 
 $options = DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.duration";
$videos = $db->get_results("SELECT ".DB_PREFIX."videos.id, ".DB_PREFIX."videos.title, ".DB_PREFIX."videos.user_id, ".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration, ".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.name AS owner
FROM ".DB_PREFIX."playlist_data
LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id
WHERE ".DB_PREFIX."playlist_data.playlist =  '".$likes_playlist."'
ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_limit()."");
 ?>        
 <div class="row blc mIdent"> 
 <div class="col-md-3"> 
 <div class="iholder bg-google-plus"> <i class="icon-heart"></i> 
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1"> 
 <h1> <?php echo _lang("What you appreciate");?> </h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("liked by").' '.user_name();?> 
 <p> 
 <a class="btn btn-sm btn-primary" href="<?php echo site_url(); ?>forward/<?php echo $likes_playlist; ?>"><i class="icon-play"></i><?php echo  _lang('Play all'); ?> </a> </p> 
 </div>  
 </div>  
    <?php 
 if($videos) { $ps = site_url().me.'?sk=likes&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); ?> 
 <form class="styled" action="<?php echo site_url().me;?>?sk=likes&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post"> 
 <div class="cleafix full">
 </div>  
 <div class="row top10"> 
 <div class="div div-checks">
 <div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items">
	<?php foreach ($videos as $video) { ?>
	 <div class="item-in-list"> 
	 <div class="segment"> 
	 <div class="checkbox-custom checkbox-primary">
	 <input type="checkbox" name="likesRow[]" value="<?php echo $video->id; ?>" />    
	 <label></label>
	 </div>  
	 </div> 
	 <div class="segment">
	 <a class="" target="_blank" href="<?php echo video_url($video->id, $video->title,$likes_playlist);?>" title="<?php echo _lang("View"); ?>">    <img src="  <?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="">    </a>
	 <a class="content-title" target="_blank" href="<?php echo video_url($video->id, $video->title,$likes_playlist);?>" title="  <?php echo _lang("View"); ?>">    <?php echo _html($video->title); ?><span class="badge badge-primary mleft20"><?php echo video_time($video->duration); ?></span> </a>
	 </div> 
	 <div class="segment">      
	 <a class="" href="<?php echo site_url().me;?>?sk=likes&p=<?php echo this_page();?>&delete-like=<?php echo $video->id;?>" title="<?php echo _lang("Remove rating"); ?>"> <i class="icon-trash" style=""></i>        </a>    
	 </div> 
	 </div>
	 <?php } ?> 
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "hearts":    

 if(_get('delete-heart')) { $id = intval(_get('delete-heart')); 
 $db->query("Delete from ".DB_PREFIX."hearts where uid ='".user_id()."' and vid='".$id."' ");
 echo " 
 <div class='msg-info mtop20 left20 right20'>"._lang('This like was removed.')."
 </div>  
"; } 

 if(isset($_POST['heartsRow'])) {
foreach ($_POST['heartsRow'] as $del) {
$id = intval($del); 
 $db->query("Delete from ".DB_PREFIX."hearts where uid ='".user_id()."' and vid='".$id."' ");	
}
echo '<div class="msg-info mleft20 mright20 mtop20">'._lang("Pictures unliked.").'</div>';
}
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."images where ".DB_PREFIX."images.id in ( select vid from ".DB_PREFIX."hearts where uid ='".user_id()."' and type='like')"); 
 $images = $db->get_results("select id,title,thumb, views, liked from ".DB_PREFIX."images where ".DB_PREFIX."images.id in ( select vid from ".DB_PREFIX."hearts where uid ='".user_id()."' and type='like') ORDER BY ".DB_PREFIX."images.id DESC ".this_limit().""); ?> 
 <div class="row blc mIdent"> 
 <div class="col-md-3">     
 <div class="iholder bg-google-plus"><i class="icon-heart"></i>     
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1">     
 <h1><?php echo _lang("What you hearted");?></h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("liked by").' '.user_name();?> 
 </div>  
 </div>  
 <?php 
 if($images) { $ps = site_url().me.'?sk=hearts&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); ?> 
 <form class="styled" action="<?php echo site_url().me;?>?sk=hearts&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post"> 
 <div class="cleafix full">
 </div>  
 <div class="row top10">
 <div class="div div-checks">  
<div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items"> <?php foreach ($images as $image) { ?> 
	 <div class="item-in-list">
	 <div class="segment">    
	 <div class="checkbox-custom checkbox-primary">        
	 <input type="checkbox" name="heartsRow[]" value="<?php echo $image->id; ?>" /> 
	 <label></label>    
	 </div>  
	 </div> 
	 <div class="segment">        
	 <a class="" target="_blank" href="<?php echo image_url($image->id, $image->title);?>" title="<?php echo _lang("View"); ?>"> <img src="<?php echo thumb_fix($image->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style=""> </a>        
	 <a class="content-title" target="_blank" href="<?php echo image_url($image->id, $image->title);?>" title="<?php echo _lang("View"); ?>"> <?php echo _html($image->title); ?> </a>        
	 </div> 
	 <div class="segment"> 
	 <div class="btn-group"> 
	 <a class="btn btn-sm btn-default btn-outline " href="<?php echo site_url().me;?>?sk=hearts&p=<?php echo this_page();?>&delete-heart=<?php echo $image->id;?>" title="<?php echo _lang("Remove rating"); ?>"> <i class="icon-trash" style=""></i> </a> 
	 </div>  
	 </div> 
		</div>
	<?php } ?>
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "history":    
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select video_id from ".DB_PREFIX."playlist_data where playlist ='".history_playlist()."')"); 
 $videos = $db->get_results("select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id WHERE ".DB_PREFIX."playlist_data.playlist =  '".history_playlist()."' and ".DB_PREFIX."videos.pub > 0 ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_limit()); 
 $history_playlist = history_playlist();
 ?>     
 <div class="row blc mIdent">         
 <div class="col-md-3"> 
 <div class="iholder bg-twitter"> <i class="icon-check-square"></i> 
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1"> 
 <h1> <?php echo _lang("What you've watched");?> </h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("watched by").' '.user_name();?> 
 <p> 
 <a class="btn btn-sm btn-success" href="<?php echo site_url(); ?>forward/<?php echo $history_playlist; ?>"><i class="icon-play"></i><?php echo  _lang('Play all'); ?> </a> </p>         
 </div>  
 </div>  
 <?php 
 if($videos) { $ps = site_url().me.'?sk=history&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); ?>     
 <div class="cleafix full">
 </div>  
 <div class="row top10">
 <div class="full block">  
 <div class="item-in-list"><?php echo _lang("Video"); ?> 
</div>
	<div class="content--items"> <?php foreach ($videos as $video) { ?> 
	 <div class="item-in-list">
	 <div class="segment">    
	 <a class="" href="<?php echo video_url($video->id, $video->title,$history_playlist);?>" title="  <?php echo _lang("View"); ?>">        <img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="">        </a>        
	 <a class="" href="<?php echo video_url($video->id, $video->title,$history_playlist);?>" title="<?php echo _lang("View"); ?>"> 
	 <?php echo _html($video->title); ?>    <span class="badge badge-primary mleft20"><?php echo video_time($video->duration); ?></span>    
	 </a>    
	 </div> 
	</div>
	<?php } ?> 
	</div>   
 </div>  
 </div>  
     <?php  $a->show_pages($ps); } 
 break; 
 case "later":    
 
 if(_get("removelater")) { playlist_remove(later_playlist(), _get("removelater")); } 
 $count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where ".DB_PREFIX."videos.id in ( select video_id from ".DB_PREFIX."playlist_data where playlist ='".later_playlist()."')"); 
 $videos = $db->get_results("select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration FROM ".DB_PREFIX."playlist_data LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id WHERE ".DB_PREFIX."playlist_data.playlist =  '".later_playlist()."' and ".DB_PREFIX."videos.pub > 0 ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_limit()); ?>         
 <div class="row blc mIdent"> 
 <div class="col-md-3"> 
 <div class="iholder bg-linkedin"><i class="icon-history"></i> 
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1"> 
 <h1><?php echo _lang("Watch later");?> </h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("saved for later by").' '.user_name();
 $later_playlist = later_playlist();
 ?> 
 <p> 
 <a class="btn btn-sm btn-success" href="<?php echo site_url(); ?>forward/<?php echo $later_playlist; ?>">    <i class="icon-play"></i><?php echo  _lang('Play all'); ?></a> </a> </p>         
 </div>  
 </div>  
 <?php 
 if($videos) { $ps = site_url().me.'?sk=later&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); 
 $a->show_pages($ps); ?>     
 <div class="cleafix full">
 </div>  
 <div class="row top10">
 <div class="full block">  
	<div class="content--items"> 
		<?php foreach ($videos as $video) { ?> 
		 <div class="item-in-list">
		 <div class="segment">    
		 <a class="" href="<?php echo video_url($video->id, $video->title, $later_playlist);?>" title="  <?php echo _lang("View"); ?>">        
		 <img src="<?php echo thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style="">        </a>           
		 <a class="" href="<?php echo video_url($video->id, $video->title, $later_playlist);?>" title="<?php echo _lang("View"); ?>"> 
		 <?php echo _html($video->title); ?> <span class="badge badge-primary mleft20"><?php echo video_time($video->duration); ?></span>       
		 </a>    
		 </div> 
		 <div class="segment">        
		 <a class="" href="<?php echo site_url().me;?>?sk=later&p=<?php echo this_page();?>&removelater=<?php echo $video->id;?>" title="<?php echo _lang("Remove this"); ?>"> <i class="icon-trash"></i>        </a>    
		 </div> 
		</div>
		<?php } ?> 
	</div>   
 </div>  
 </div>  
     <?php  $a->show_pages($ps); } 
 break; 
 case "playlists":    
 $count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and ptype < 2 and picture not in ('[likes]','[history]','[later]')"); 
 $videos = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype < 2 order by title desc ".this_limit().""); ?>         
 <div class="row blc mIdent"> 
 <div class="col-md-3"> 
 <div class="iholder bg-facebook"><i class="icon-list-alt"></i> 
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1"> 
 <h1><?php echo _lang("Playlists manager");?> </h1> 
 <?php echo $count->nr; ?> <?php echo _lang("playlists by").' '.user_name();?> 
 <p> 
 <a class="btn btn-default" href="<?php echo site_url().me; ?>/?sk=new-playlist">    <i class="icon-play"></i><?php echo  _lang('Create a new playlist'); ?></a> </p> 
 </div>  
 </div>  
     <?php 
 if($videos) { $ps = site_url().me.'?sk=playlists&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); 
 $a->show_pages($ps); ?>         
 <form class="styled mtop10" action="<?php echo site_url().me;?>?sk=playlists&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post"> 
 <div class="cleafix full">
 </div>  
 <div class="row top10">
 <div class="div div-checks">            
<div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items">   
		 <?php foreach ($videos as $video) { ?>        
		 <div class="item-in-list"> 
		 <div class="segment"> 
		 <div class="checkbox-custom checkbox-primary"> 
		 <input type="checkbox" name="playlistsRow[]" value="<?php echo $video->id; ?>" class="styled" />
		 <label></label> 
		 </div>  
		 </div> 
		 <div class="segment"> 
		 <a class="btn btn-sm btn-primary " href="<?php echo site_url().me;?>?sk=manage-playlists&playlist=<?php echo $video->id;?>" title="<?php echo _lang("Manage the videos in "); echo _html($video->title); ?>"><i class="icon icon-navicon"></i><?php echo _lang("Review"); ?> </a> 
		 </div> 
		 <div class="segment" > 
		 <a class="" target="_blank" href="<?php echo playlist_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><img src="<?php echo thumb_fix($video->picture, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style=""></a>    
		 <a class="content-title" target="_blank" href="<?php echo playlist_url($video->id, $video->title);?>" title="<?php echo _lang("View"); ?>"><strong> <?php echo _html($video->title); ?></strong></a> 
		 </div> 
		 <div class="segment">     
		 <a class="btn btn-sm btn-default btn-outline " href="<?php echo site_url().me;?>?sk=playlists&p=<?php echo this_page();?>&delete-playlist=<?php echo $video->id;?>" title="<?php echo _lang("Delete playlist"); ?>"><i class="icon-trash"></i></a> 
		 </div> 
		 </div>
		 <?php } ?> 
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "albums":    
 $count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and ptype > 1 and picture not in ('[likes]','[history]','[later]')"); 
 $albums = $db->get_results("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype > 1 order by title desc ".this_limit().""); ?> 
 <div class="row blc mIdent">
 <div class="col-md-3">    
 <div class="iholder bg-facebook">        <i class="icon-eye"></i>    
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1">    
 <h1>    <?php echo _lang("Albums manager");?>    </h1> 
<?php echo $count->nr; ?> <?php echo _lang("albums by").' '.user_name();?>    
 <p> 
 <a class="btn btn-default" href="<?php echo site_url().me; ?>/?sk=new-album"> <i class="icon-camera"></i> <?php echo  _lang('Create a new album'); ?>        </a>    </p>
 </div>  
 </div>  
 <?php 
 if($albums) { $ps = site_url().me.'?sk=albums&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); 
 $a->show_pages($ps); ?> 
 <form class="styled mtop10" action="<?php echo site_url().me;?>?sk=albums&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
 <div class="cleafix full">
 </div>  
 <div class="row top10">        
 <div class="div div-checks">  
<div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items">
		 <?php foreach ($albums as $album) { ?> 
		 <div class="item-in-list"> 
		 <div class="segment">     
		 <div class="checkbox-custom checkbox-primary">
		 <input type="checkbox" name="playlistsRow[]" value="<?php echo $album->id; ?>" class="styled" /> 
		 <label></label>    
		 </div>  
		 </div> 
		 <div class="segment">         
		 <a class="btn btn-sm " href="<?php echo site_url().me;?>?sk=manage-albums&album=<?php echo $album->id;?>" title="<?php echo _lang("Manage pictures in "); echo _html($album->title); ?>"> <i class="icon icon-navicon"></i> <?php echo _lang("Manage pictures "); ?></a>     
		 </div> 
		 <div class="segment">         
		 <a class="" target="_blank" href="<?php echo playlist_url($album->id, $album->title);?>" title="<?php echo _lang("View"); ?>"> <img src="<?php echo thumb_fix($album->picture, true, get_option('thumb-width'), get_option('thumb-height')); ?>" style=""> </a>         
		 <a class="content-title" target="_blank" href="<?php echo playlist_url($album->id, $album->title);?>" title="<?php echo _lang("View"); ?>"> <strong><?php echo _html($album->title); ?> </strong> </a>         
		 </div> 
		 <div class="segment"> 
		 <a class="btn btn-sm btn-default btn-outline " href="<?php echo site_url().me;?>?sk=albums&p=<?php echo this_page();?>&delete-playlist=<?php echo $album->id;?>" title="<?php echo _lang("Delete playlist"); ?>"> <i class="icon-trash"></i> </a>         
		 </div> 
	     </div>
		<?php } ?> 
	</div>   
 </div>  
 </div>  
 </form> 
<?php  $a->show_pages($ps); } 
 break; 
 case "manage-playlists":    
 
 if(!_get("playlist")) { die(_lang("Something went wrong")); } 
 $play_check = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and  id= '".toDb(_get("playlist"))."' order by views desc limit 0,1"); 
 if(!$play_check) { die(_lang("Something went wrong")); } 
 if(_get("playlist") && _get("remove") && $play_check) { playlist_remove(_get("playlist"), _get("remove")); } 
 if(_get("playlist") && isset($_POST['playlistsRemoval']) && $play_check) { playlist_remove(_get("playlist"), $_POST['playlistsRemoval']); } 
 $count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlist_data where playlist= '".toDb($play_check->id)."'"); 
 $options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw"; 
 $vq = "select ".$options." FROM ".DB_PREFIX."videos WHERE ".DB_PREFIX."videos.id in (SELECT ".DB_PREFIX."playlist_data.video_id from ".DB_PREFIX."playlist_data where playlist='".$play_check->id."') ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset(bpp()); 
 $videos = $db->get_results($vq); ?>    
 <div class="row blc mIdent">        
 <div class="col-md-3"> 
 <div class="iholder bg-twitter"> <i class="icon-navicon"></i> 
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1"> 
 <h1> <?php echo $play_check->title;?> </h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("saved  by").' '.user_name();?> 
 <p> 
 <a class="btn btn-sm btn-success" href="<?php echo site_url(); ?>forward/<?php echo $play_check->id; ?>"> <i class="icon-play"></i> <?php echo  _lang('Play all'); ?></a>        </p>    
 </div>  
 </div>  
<?php 
 if($videos) { $ps = site_url().me.'?sk=manage-playlists&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); //$a->show_pages($ps);
 ?>
 <form class="styled" action="<?php echo site_url().me;?>?sk=manage-playlists&playlist=<?php echo _get("playlist");?>" enctype="multipart/form-data" method="post">    
 <div class="cleafix full">
 </div>  
 <div class="row top10"> 
 <div class="div div-checks">  
<div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items">
		<?php foreach ($videos as $video) { ?>
		 <div class="item-in-list"> 
		 <div class="segment"> 
		 <div class="checkbox-custom checkbox-primary">  
		 <input type="checkbox" name="playlistsRemoval[]" value="<?php echo $video->id; ?>" />      
		 <label></label>    
		 </div>  
		 </div> 
		 <div class="segment">  <img src="<?php echo thumb_fix($video->thumb); ?>" style="">  
		 <span class="thetitles"> <?php echo _html($video->title); ?> </span> 
		 </div> 
		 <div class="segment">      
		 <p> 
		 <a class="btn" href="<?php echo site_url().me;?>?sk=manage-playlists&playlist=<?php echo _get("playlist");?>&remove=<?php echo $video->id;?>"> <i class="material-icons">&#xE872;</i></a>      </p>  
		 </div> 
		 </div>
		 <?php } ?> 
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "manage-albums":    
 
 if(!_get("album")) { die(_lang("Something went wrong")); } 
 $play_check = $db->get_row("SELECT * FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and  id= '".toDb(_get("album"))."' order by views desc limit 0,1"); 
 if(!$play_check) { die(_lang("Something went wrong")); } 
 if(_get("album") && _get("remove") && $play_check) { playlist_remove(_get("album"), _get("remove")); } 
 if(_get("album") && isset($_POST['albumsRemoval']) && $play_check) { playlist_remove(_get("album"), $_POST['albumsRemoval']); } 
 $count = $db->get_row("SELECT count(*) as nr FROM ".DB_PREFIX."playlist_data where playlist= '".toDb($play_check->id)."'"); 
 $options = DB_PREFIX."images.id,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."images.thumb,".DB_PREFIX."images.views,".DB_PREFIX."images.liked,".DB_PREFIX."images.nsfw"; 
 $vq = "select ".$options." FROM ".DB_PREFIX."images WHERE ".DB_PREFIX."images.id in (SELECT ".DB_PREFIX."playlist_data.video_id from ".DB_PREFIX."playlist_data where playlist='".$play_check->id."') ORDER BY ".DB_PREFIX."images.id DESC ".this_offset(bpp()); 
 $images = $db->get_results($vq); ?> 
 <div class="row blc mIdent"> 
 <div class="col-md-3">     
 <div class="iholder bg-google-plus"><i class="icon-camera"></i>     
 </div>  
 </div>  
 <div class="col-md-7 col-md-offset-1">     
 <h1><?php echo $play_check->title;?></h1> 
 <?php echo $count->nr; ?> <?php echo _lang("entries");?> <?php echo _lang("saved for later by").' '.user_name();?> </a> </p> 
 </div>  
 </div>  
    <?php 
 if($images) { $ps = site_url().me.'?sk=manage-albums&p='; 
 $a = new pagination; 
 $a->set_current(this_page()); 
 $a->set_first_page(true); 
 $a->set_pages_items(7); 
 $a->set_per_page(bpp()); 
 $a->set_values($count->nr); //$a->show_pages($ps); 
	?>        
 <form class="styled" action="<?php echo site_url().me;?>?sk=manage-albums&album=<?php echo _get("album");?>" enctype="multipart/form-data" method="post"> 
 <div class="cleafix full">
 </div>  
 <div class="row top10"> 
 <div class="div div-checks">
<div class="item-in-list list-header">     
  <div class="segment">
    <div class="checkbox-custom checkbox-primary"> 
    <input type="checkbox" name="checkRows" class="check-all" /> 
    <label></label> Select all
    </div>  
  </div>       
  <div class="segment">  
      <button class="btn btn-danger btn-sm" type="submit"> 
      <i class="icon icon-trash"></i> <?php echo _lang("selected"); ?>  
      </button>  	   
  </div>
 </div>
	<div class="content--items"> 
		<?php foreach ($images as $video) { ?> 
		 <div class="item-in-list">
		 <div class="segment">      
		 <div class="checkbox-custom checkbox-primary">          
		 <input type="checkbox" name="albumsRemoval[]" value="<?php echo $video->id; ?>" /> 
		 <label></label>    
		 </div>  
		 </div> 
		 <div class="segment"><img src="<?php echo thumb_fix($video->thumb); ?>" style="">          
		  <span class="thetitles"><?php echo _html($video->title); ?></span>       
		 </div> 
		 <div class="segment"> 
		 <a class="btn btn-sm btn-default btn-outline" href="<?php echo site_url().me;?>?sk=manage-albums&album=<?php echo _get("album");?>&remove=<?php echo $video->id;?>"> <i class="icon-trash" style=""></i> </a>          
		 </div> 
		 </div>
		  <?php } ?>  
	</div>   
 </div>  
 </div>  
 </form> 
 <?php  $a->show_pages($ps); } 
 break; 
 case "edit-video":    
    
 if(isset($_POST['edited-video'])) { echo '     
 <div class="msg-hint mleft20 mright20 mtop20 top10 bottom10">'.$_POST['title']._lang(" updated.").'
 </div>  
   '; } 
  
 if(!_get("vid")){ die(_lang("Missing video id")); } 
  
 if((get_option('uploadrule') <> 1 )&&  !is_moderator()) { die(_lang("Video editing has been disabled by the administrator")); } 
 $video = $db->get_row("SELECT * from ".DB_PREFIX."videos where user_id= '".user_id()."' and id = '".intval(_get("vid"))."' "); 
 if($video) { ?>     
 <div class="row odet mbot20 text-center"> 
<iframe id="previewer" width="853" height="480" style="max-width:100%" src="<?php echo site_url().embedcode.'/'._mHash($video->id).'/';?>" frameborder="0" allowfullscreen></iframe> 
 <div id="thumbus" class="row odet mtop20 text-center"> 
<?php
 if(not_empty($video->token)) {
$tp = ABSPATH.'/storage/'.get_option('mediafolder','media')."/thumbs/";
$pattern = "{*".$video->token."*}";
$vl = glob($tp.$pattern, GLOB_BRACE);

 if($vl) {
foreach($vl as $vidid) {
$cls='';	
$vidid = str_replace(ABSPATH.'/' ,'',$vidid);

 if( $video->thumb == $vidid ) {$cls='img-selected';}	
echo '<a href="#" class="thumb-selects" data-url="'.urlencode($vidid).'">
<img src="'.thumb_fix($vidid).'" class="'.$cls.'"/>
</a>
';
}	
}
 }
 ?>
 </div>
  <script>
 $(document).ready(function() {
	 var ew = $("#previewer").width();
     var eh = Math.round((ew/16)*9) + 25;
     $("#previewer").height(eh); 
	 $('.img-selected').parent('a').addClass('tcc');
	  $('#thumbus > a').click(function() {
		  $('#thumbus > a').find('img').removeClass('img-selected');
		  $('#thumbus > a').removeClass('tcc');
		  $(this).addClass('tcc');
		  $(this).find('img').addClass('img-selected');
                        var valoare = $(this).attr("data-url");
                        $("#remote-image").val(valoare);
                        return false;
                    }); 
	 });
 </script>
 </div>
 <div class="row odet mbot20"> 
 <form id="validate" class="styled" action="<?php echo site_url().me;?>?sk=edit-video&vid=<?php echo $video->id; ?>" enctype="multipart/form-data" method="post">  
 <input type="hidden" name="edited-video" id="edited-video" value = "<?php echo $video->id; ?>"/>  
  <input type="hidden" name="edited-token" id="edited-token" value = "<?php echo $video->token; ?>"/>  
   <input type="hidden" name="remote-thumb" id="remote-image" value = ""/>  
 <div class="control-group blc row mtop10">          
 <div class="controls"> 
 <?php echo ' 
 <div class="form-group form-material"> 
 <label class="control-label" for="inputFile">'._lang("Upload a custom thumbnail:").'</label> 
 <input type="text" class="form-control" placeholder="'._lang("Browse for image...").'" readonly="" /> 
 <input type="file" name="play-img" id="play-img" /> 
 </div>  
'; ?> <span class="help-block" id="limit-text"> <?php echo _lang("Select only if you wish to change the image");?> </span>          
 </div>  
 </div> 
 <div class="control-group blc row mtop10">      
 <label class="control-label">      <?php echo _lang("Title"); ?> </label>    
 <div class="controls"> 
 <input type="text" name="title" class="validate[required] form-control col-md-12" value="<?php echo $video->title; ?>" required/>          
 </div>  
 </div>  
 <div class="control-group blc row mtop10">          
 <label class="control-label"> <?php echo _lang("Description"); ?></label>    
 <div class="controls"> 
 <textarea rows="5" cols="5" name="description" class="auto validate[required] form-control col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"><?php echo $video->description; ?></textarea>          
 </div>  
 </div>  
 <div class="control-group blc row mtop10">          
 <label class="control-label"> <?php echo _lang("Duration (in seconds):") ?></label>    
 <div class="controls"> 
 <input type="text" id="duration" name="duration" class="validate[required] form-control col-md-12" value="<?php echo $video->duration; ?>"> 
 </div>  
 </div>  
 <div class="control-group blc row mtop10"> 
 <label class="control-label"> <?php echo _lang("Category:"); ?> </label> 
 <div class="controls"> 
 <?php echo cats_select('categ','select','validate[required] form-control', $video->media); ?> <?php  
 if(isset($hint)) { ?> 
 <span class="help-block"> <?php echo $hint; ?> </span> <?php } ?> 
 </div>  
 </div>  
          <script> $(document).ready(function(){ $('.select').find('option[value="<?php echo $video->category;?>"]').attr("selected",true); });          </script>          
 <div class="control-group blc row mtop10"> 
 <label class="control-label"> <?php echo _lang("Tags:"); ?> </label> 
 <div class="controls"> 
 <input type="text" id="tags" name="tags" class="tags col-md-12" value="<?php echo $video->tags; ?>"> 
 </div>  
 </div>  
 <div class="row">
 <div class="col-md-6">
 <div class="control-group blc row mtop10"> 
 <label class="control-label"> <?php echo _lang("NSFW:"); ?> </label> 
 <div class="controls form-inline"> 
 <div class="radio-custom radio-primary right10">
 <input type="radio" name="nsfw" class="styled" value="1"      <?php 
 if($video->nsfw > 0 ) { echo "checked"; } ?>>
 <label> <?php echo _lang("Not safe"); ?></label>    
 </div>  
 <div class="radio-custom radio-primary">
 <input type="radio" name="nsfw" class="styled" value="0"<?php 
 if($video->nsfw < 1 ) { echo "checked"; } ?>> 
 <label> <?php echo _lang("Safe"); ?> </label>    
 </div>  
 </div>  
 </div>  
 </div>
 <div class="col-md-6">
 <div class="control-group blc row mtop10">
 <label class="control-label"><?php echo _lang("Visibility"); ?></label>    
 <div class="controls form-inline">         
 <div class="radio-custom radio-primary right10"> 
 <input type="radio" name="priv" class="styled" value="1"<?php 
 if($video->privacy > 0 ) { echo "checked"; } ?>> 
 <label><?php echo _lang("Followers only");?> </label> 
 </div>  
 <div class="radio-custom radio-primary"> 
 <input type="radio" name="priv" class="styled" value="0"<?php 
 if($video->privacy < 1 ) { echo "checked"; } ?>>  
 <label>  <?php echo _lang("Everybody");?>  </label> 
 </div>  
 </div>  
 </div>
</div> 
 </div>
 <?php echo'
 <div class="form-group form-material mtop10">
		<label class="control-label" for="inputFile"><span class="badge">'._lang("Optional").'</span> '. _lang("Subtitle").'</label>
		<input type="text" class="form-control" placeholder="'._lang("Browse for .vtt or .srt file").'" readonly="" />
		<input type="file" name="subtitle" id="subtitle" />
			</div>
 ';
 if(not_empty($video->srt)) {
	 echo "<span class=\"badge\">"._lang("This will overwrite existing"). " ".$video->srt."</span>";
 }
 
 ?>
 <div class="control-group blc row mtop10"> 
 <button class="btn btn-primary pull-right" type="submit"> 
 <?php echo _lang("Update video"); ?>  
 </button>         
 </div>  
 </form> 
 </div>  
 <?php } else { 
 echo ' 
 <div class="msg-warning">'._lang("This video does not belong to you").'
 </div>  
'; } 
 break; 
 case "edit-image":    
 
 if(isset($_POST['edited-image'])) { echo ' 
 <div class="msg-hint mleft20 mright20 mtop20 mtop10 mbot10">'.$_POST['title']._lang(" updated.").'
 </div>  
'; } 
 if(!_get("vid")){ die(_lang("Missing image id")); } 
 if((get_option('uploadrule') <> 1 )&&  !is_moderator()) { die(_lang("Image editing has been disabled by the administrator")); } 
 $image = $db->get_row("SELECT * from ".DB_PREFIX."images where user_id= '".user_id()."' and id = '".intval(_get("vid"))."' "); 
 if($image) { ?> 
 <div class="row odet mbot20">     
 <form id="validate" class="styled" action="<?php echo site_url().me;?>?sk=edit-image&vid=<?php echo $image->id; ?>" enctype="multipart/form-data" method="post"> 
 <input type="hidden" name="edited-image" id="edited-image" value = "<?php echo $image->id; ?>"/> 
 <div class="control-group blc row mtop10">  
 <label class="control-label">  <?php echo _lang("Title"); ?>  </label>    
 <div class="controls"> 
 <input type="text" name="title" class="validate[required] form-control col-md-12" value="<?php echo $image->title; ?>" required/>      
 </div>  
 </div>  
 <div class="control-group blc row mtop10">      
 <label class="control-label">      <?php echo _lang("Description"); ?>      </label>    
 <div class="controls"> 
          <textarea rows="5" cols="5" name="description" class="auto validate[required] form-control col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"> <?php echo $image->description; ?></textarea>      
 </div>  
 </div>  
 <div class="control-group blc row mtop10">      
 <label class="control-label">      <?php echo _lang("Category:"); ?>      </label>    
 <div class="controls"> 
      <?php echo cats_select('categ','select','validate[required] form-control', 3); ?>      <?php  
 if(isset($hint)) { ?>          
 <span class="help-block"> <?php echo $hint; ?></span>      <?php } ?>      
 </div>  
 </div>  
  <script> $(document).ready(function(){ $('.select').find('option[value="<?php echo $image->category;?>"]').attr("selected",true);	 });  </script>  
 <div class="control-group blc row mtop10">      
 <label class="control-label">      <?php echo _lang("Tags:"); ?>      </label>    
 <div class="controls"> 
 <input type="text" id="tags" name="tags" class="tags col-md-12" value="<?php echo $image->tags; ?>">          
 </div>  
 </div>  
 <div class="control-group blc row mtop10">          
 <label class="control-label"> <?php echo _lang("NSFW:"); ?></label>    
 <div class="controls form-inline"> 
 <div class="radio-custom radio-primary right10"> 
 <input type="radio" name="nsfw" class="styled" value="1"<?php 
 if($image->nsfw > 0 ) { echo "checked"; } ?>>   
 <label><?php echo _lang("Not safe"); ?></label> 
 </div>  
 <div class="radio-custom radio-primary">   
 <input type="radio" name="nsfw" class="styled" value="0"    <?php 
 if($image->nsfw < 1 ) { echo "checked"; } ?>>       
 <label>       <?php echo _lang("Safe"); ?>       </label>    
 </div>  
 </div>  
 </div>  
 <div class="control-group blc row mtop10"> 
 <label class="control-label"> <?php echo _lang("Visibility"); ?> </label> 
 <div class="controls form-inline">   
 <div class="radio-custom radio-primary right10">       
 <input type="radio" name="priv" class="styled" value="1"<?php 
 if($image->privacy > 0 ) { echo "checked"; } ?>>           
 <label> <?php echo _lang("Followers only");?>           </label>    
 </div>  
 <div class="radio-custom radio-primary">           
 <input type="radio" name="priv" class="styled" value="0"<?php 
 if($image->privacy < 1 ) { echo "checked"; } ?>> 
 <label> <?php echo _lang("Everybody");?> </label>    
 </div>  
 </div>  
 </div>  
 <div class="control-group blc row mtop10">       
 <button class="btn btn-primary pull-right" type="submit"> 
 <?php echo _lang("Update image"); ?>
 </button>   
 </div>  
 </form> 
 </div>  
 <?php } else { 
 echo ' 
 <div class="msg-warning">'._lang("This image does not belong to you").'
 </div>  
'; } 
 break; 
 case "new-album":    
 
 if(isset($_POST['album-name'])) { $picture ='storage/uploads/noimage.png'; 
 $formInputName   = 'album-img';							
$savePath	     = ABSPATH.'/storage/uploads';	
 $saveName        = md5(time()).'-'.user_id();									
 $allowedExtArray = array('.jpg', '.png', '.gif');	
$imageQuality    = 100; 
 $uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray); if ($uploader->getIsSuccessful()) { 
$uploader -> resizeImage(200, 200, 'crop'); 
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality); 
$thumb  = $uploader->getTargetPath(); 
$picture  = str_replace(ABSPATH.'/' ,'',$thumb); } else { 
 $picture  = 'storage/uploads/noimage.png'; 	} 
 $db->query("INSERT INTO ".DB_PREFIX."playlists (`ptype`,`owner`, `title`, `picture`, `description`) VALUES (2, '".user_id()."','".toDb($_POST['album-name'])."', '".toDb($picture)."' , '".toDb($_POST['album-desc'])."')"); echo ' 
 <div class="msg-hint mleft20 mright20 mtop20 top10 bottom10">'.$_POST['album-name']._lang(" created.").'
 </div>  
'; } ?> 
 <div class="row odet"> 
 <form id="validate" action="<?php echo site_url().me;?>?sk=new-album" enctype="multipart/form-data" method="post">       
 <div class="control-group">  
 <label class="control-label"> <?php echo _lang("Title"); ?>           </label>    
 <div class="controls"> 
 <input type="text" name="album-name" class="validate[required] form-control col-md-12" placeholder="<?php echo _lang("Your album's title"); ?> required" /> 
 </div>  
 </div>  
 <div class="control-group mtop20 row"> 
 <label class="control-label"> <?php echo _lang("Description"); ?> </label> 
 <div class="controls"> 
 <textarea rows="5" cols="5" name="album-desc" class="auto validate[required] form-control col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"></textarea> 
 </div>  
 </div>  
 <div class="control-group mtop20 row"> 
 <label class="control-label"> <?php echo _lang("Album picture"); ?> </label> 
 <div class="controls"> 
 <?php echo ' 
 <div class="form-group form-material">  
 <label class="control-label" for="inputFile">'._lang("Choose thumbnail:").'</label>    
 <input type="text" class="form-control" placeholder="'._lang("Browse...").'" readonly="" />  
 <input type="file" name="album-img" id="album-img" /> 
 </div>  
'; ?> 
 </div>  
 </div>  
 <div class="control-group mtop10 mbot20"> 
 <button class="btn btn-primary pull-right" type="submit"> 
 <?php echo _lang("Create album"); ?>  
 </button>           
 </div>  
 </form> 
 </div>  
 <?php 
 break; 
 case "new-playlist":    
 
 if(isset($_POST['play-name'])) { echo ' 
 <div class="msg-hint mleft20 mright20 mtop20 top10 bottom10">'.$_POST['play-name']._lang(" created.").'
 </div>  
'; } ?> 
 <div class="row odet">   
 <form id="validate" action="<?php echo site_url().me;?>?sk=new-playlist" enctype="multipart/form-data" method="post">               
 <div class="control-group">  
 <label class="control-label"> <?php echo _lang("Title"); ?> </label> 
 <div class="controls"> 
 <input type="text" name="play-name" required class=" form-control col-md-12" placeholder="<?php echo _lang("Your playlist's title"); ?>" /> 
 </div>  
 </div>  
 <div class="control-group mtop20 row"> 
 <label class="control-label"><?php echo _lang("Description"); ?> </label> 
 <div class="controls"> 
  <textarea rows="5" cols="5" name="play-desc" class="auto  form-control col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"></textarea> 
 </div>  
 </div>  
 <div class="control-group mtop20 row"> 
 <label class="control-label"><?php echo _lang("Playlist image"); ?> </label> 
 <div class="controls"> 
<?php echo '  
 <div class="form-group form-material">      
 <label class="control-label" for="inputFile">'._lang("Choose thumbnail:").'</label>    
 <input type="text" class="form-control" placeholder="'._lang("Browse...").'" readonly="" />      
 <input type="file" name="play-img" id="play-img" />  
 </div>  
'; ?> 
 </div>  
 </div>  
 <div class="control-group mtop10 mbot20"> 
 <button class="btn btn-primary pull-right" type="submit"> 
<?php echo _lang("Create playlist"); ?>  
 </button> 
 </div>  
 </form> 
 </div>  
 <?php 
 break; 
 } ?> 
 </div>  
  <div id="DashSidebar" class="col-md-2 col-xs-12"> <?php   do_action('dashSide-top'); ?> 
 <div class="nav-tabs-vertical">
	<ul class="nav nav-tabs nav-tabs-line">
		<li class=""><a href="<?php echo site_url(); ?>dashboard/"><i class="icon icon-hashtag"></i><?php echo _lang("Overview");?></a></li>
		<li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=activity"><i class="material-icons">&#xE7F7;</i><?php echo _lang("Activities");?></a></li>
	    <li class=""><a href="<?php echo site_url().me; ?>?sk=subscriptions"><i class="material-icons">&#xE8A1;</i><?php echo _lang("Payments");?></a></li>
		<li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=edit"><i class="icon icon-cogs"></i><?php echo _lang("Channel Settings");?></a></li>
		<li class=""><a href="<?php echo site_url().me; ?>"><i class="icon icon-film"></i><?php echo _lang("Videos");?></a></li>
		<li class="left20"><a href="<?php echo site_url().me; ?>?sk=playlists"><i class="icon icon-bars"></i><?php echo _lang("Playlists");?></a></li>
		<li class=""><a href="<?php echo site_url().me; ?>?sk=images"><i class="icon icon-camera"></i><?php echo _lang("Images");?></a></li>
		<li class="left20"><a href="<?php echo site_url().me; ?>?sk=albums"><i class="icon icon-bars"></i><?php echo _lang("Albums");?></a></li>
		<li class="left20"><a href="<?php echo site_url().me; ?>?sk=hearts"><i class="icon icon-heart"></i><?php echo _lang("Loved");?></a></li>
	<li class=""><a href="<?php echo site_url().me; ?>?sk=music"><i class="icon icon-headphones"></i><?php echo _lang("Music");?></a></li>
	</ul>
</div>
 <?php do_action('dashSide-bottom'); ?> </span> 
 </div>  
 </div>