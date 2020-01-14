<?php the_sidebar(); ?>
<div class="tabs-area">
    <div class="tabs-wrapper">
      <div class="tabs-container">
          <a href="<?php echo images_url('browse'); ?>" class="tab <?php aTab(browse);?>">
            <span class="content">
              <span class="title"><?php echo _lang('Recent'); ?></span>
			   <span class="count"><?php echo _lang('New videos'); ?></span>
            </span>
          </a>
          <a href="<?php echo images_url(mostviewed); ?>" class="tab <?php aTab(mostviewed);?>">
            <span class="content">
              <span class="title"><?php echo _lang('Most Viewed'); ?></span>
			   <span class="count"><?php echo _lang('Selected by views'); ?></span>
            </span>
          </a>
          <a href="<?php echo images_url(mostliked); ?>" class="tab <?php aTab(mostliked);?>">
            <span class="content">
              <span class="title"><?php echo _lang('Most Liked'); ?></span>
			   <span class="count"><?php echo _lang('Selected by user likes'); ?></span>
            </span>
          </a>
           <a href="<?php echo images_url(mostcom); ?>" class="tab <?php aTab(mostcom);?>">
            <span class="content">
              <span class="title"><?php echo _lang('Discussed'); ?></span>
			   <span class="count"><?php echo _lang('Most commented on'); ?></span>
            </span>
          </a>
		
      </div>
    </div>
  </div>
<div id="videolist-content" class="main-holder pad-holder span12 nomargin">
<?php echo _ad('0','video-list-top');
include_once(TPL.'/video-loop.php');
 echo _ad('0','video-list-bottom');
?>
</div>
