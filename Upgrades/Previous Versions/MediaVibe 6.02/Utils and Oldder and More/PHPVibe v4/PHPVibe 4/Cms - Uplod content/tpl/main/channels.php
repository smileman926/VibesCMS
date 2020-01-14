<div class="tabs-area">
    <div class="tabs-wrapper">
      <div class="tabs-container">
          <a href="<?php echo site_url().channels;?>" class="tab <?php aTab('video');?>">
            <span class="content">
              <span class="title"><?php echo _lang('Videos'); ?></span>
			   <span class="count"><?php echo _lang('Categories with videos'); ?></span>
            </span>
          </a>
		  <?php  if(get_option('musicmenu') == 1 ) { ?>
          <a href="<?php echo site_url().channels;?>/music/" class="tab <?php aTab('music');?>">
            <span class="content">
              <span class="title"><?php echo _lang('Music'); ?></span>
			   <span class="count"><?php echo _lang('Categories with music'); ?></span>
            </span>
          </a>
		<?php } ?> 

       <?php  if(get_option('imagesmenu') == 1 ) { ?>
          <a href="<?php echo site_url().channels;?>/images/" class="tab <?php aTab('images');?>">
            <span class="content">
              <span class="title"><?php echo _lang('Pictures'); ?></span>
			   <span class="count"><?php echo _lang('Categories with images'); ?></span>
            </span>
          </a>
		<?php } ?> 
		
      </div>
    </div>
  </div>
<div id="channel-content" class="main-holder pad-holder span12 top10 nomargin">
<h3 class="loop-heading"><span><?php echo _lang(ucfirst($type).' categories '); ?></span></h3>
<div class="content-nav blc">
<?php 
echo $content;
?>
</div>
</div>
<?php if (!is_ajax_call()) { right_sidebar();  } ?>