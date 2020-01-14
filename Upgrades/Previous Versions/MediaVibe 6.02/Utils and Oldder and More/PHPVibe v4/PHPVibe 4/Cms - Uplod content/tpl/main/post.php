<?php the_sidebar(); ?>
<div class="main-holder pad-holder row-fluid nomargin">
<div class="span9 nomargin"  style="padding-right:5px; border-right: 1px solid #ededed;">
<?php do_action('blogpost-start');
echo default_content(); 
echo comments('art'.token_id());
do_action('blogpost-end');
?>
</div>
<?php include_once('blog-sidebar.php'); ?>
</div>
