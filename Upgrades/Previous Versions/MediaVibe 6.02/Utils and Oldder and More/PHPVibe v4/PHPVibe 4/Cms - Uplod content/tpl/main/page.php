<?php the_sidebar(); ?>
<div class="main-holder pad-holder row-fluid nomargin">
<div class="span9 nomargin"  style="padding-right:5px; border-right: 1px solid #ededed;">
<?php do_action('page-start');
echo default_content(); 
do_action('page-end');
?>
</div>
<?php include_once('blog-sidebar.php'); ?>
</div>
