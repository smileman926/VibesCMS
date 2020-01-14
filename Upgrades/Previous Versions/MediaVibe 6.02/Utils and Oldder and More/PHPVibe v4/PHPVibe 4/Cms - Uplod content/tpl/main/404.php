<?php  the_header(); 
the_sidebar();
?>
<div class="error-info main-holder pad-holder span8 nomargin top10">
<h3><?php echo _lang("404"); ?></h3>
<h4><?php echo _lang("oops! page not found"); ?></h4>
<a class="button-center" href="<?php echo site_url(); ?>"><?php echo _lang("Back to home"); ?></a>
</div>
<?php  the_footer(); ?>