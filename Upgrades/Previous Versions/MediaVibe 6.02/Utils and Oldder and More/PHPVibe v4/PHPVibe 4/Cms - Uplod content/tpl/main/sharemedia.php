<?php the_sidebar(); ?>
<div class="tabs-area">
    <div class="tabs-wrapper">
      <div class="tabs-container tab-icons">
<?php 
echo '<a href="'.site_url().share.'" class="tab">
<span class="content">
<span class="title">
<i class="icon-youtube"></i>
</span>
  <span class="count">
'._lang('Web hosted video').'
</span>
</span>
</a>';
if((get_option('uploadrule') == 1) ||  is_moderator()) {
echo '<a href="'.site_url().add.'" class="tab">
<span class="content">
<span class="title">
<i class="icon-cloud-upload"></i>
</span>
  <span class="count">
 '. _lang('Upload a video file').'
 </span>
</span>
 </a>';
}
if((get_option('mp3rule') == 1) ||  is_moderator()) {
echo '<a href="'.site_url().upmusic.'" class="tab">
<span class="content">
<span class="title">
<i class="icon-music"></i>
</span>
  <span class="count">
'._lang('Share a melody').'
</span>
</span>
</a>';
}
if((get_option('imgrule') == 1) ||  is_moderator()) {
echo '<a href="'.site_url().upimage.'" class="tab">
<span class="content">
<span class="title">
<i class="icon-picture"></i>
</span>
  <span class="count">
'._lang("Upload a picture").'
</span>
</span>
</a>';
}

?>
</div>
 </div>
  </div>
<div id="default-content" class="share-media">
<div class="row-fluid">
<?php echo default_content(); ?>
</div>
</div>
