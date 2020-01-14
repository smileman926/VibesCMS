<?php the_sidebar();
//Add sorter 
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i> <i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'&sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.list_url(token()).'&sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.list_url(token()).'&sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
}

 ?>
<div class="row main-holder">

<ul class="nav nav-tabs nav-tabs-line mtop20">
    <li class="<?php aTab(browse);?>" role="presentation"><a href="<?php echo list_url('browse'); ?>"> <i class="material-icons">play_circle_filled</i> <?php echo _lang('Recent'); ?> </li></a>
    <li class="<?php aTab(mostviewed);?>" role="presentation"><a href="<?php echo list_url(mostviewed); ?>"> <i class="material-icons">trending_up</i> <?php echo _lang('Top'); ?></a></li>
    <li class="<?php aTab(mostliked);?>" role="presentation"><a href="<?php echo list_url(mostliked); ?>"> <i class="material-icons">thumbs_up_down</i> <?php echo _lang('Liked'); ?></a></li>
    <li class="<?php aTab(mostcom);?>" role="presentation"><a href="<?php echo list_url(mostcom); ?>"> <i class="material-icons">chat</i> <?php echo _lang('Commented'); ?></a></li>
    <li class="<?php aTab(promoted);?>" role="presentation"><a href="<?php echo list_url(promoted); ?>"> <i class="material-icons">touch_app</i> <?php echo _lang('Picks'); ?></a></li>
	<?php if(_UpVideo()) { ?>
	<li class="pull-right" role="presentation"><a href="<?php echo site_url().add; ?>"> <i class="material-icons">cloud_upload</i> <?php echo _lang('Upload'); ?></a></li>
    <?php } ?>
	<?php if(_EmbedVideo()) { ?>
	<li class="pull-right" role="presentation"><a href="<?php echo site_url().share; ?>"> <i class="material-icons">screen_share</i> <?php echo _lang('Share'); ?></a></li>
    <?php } ?>
	</ul>
<div id="videolist-content">
<?php echo _ad('0','video-list-top');
include_once(TPL.'/video-loop.php');
 echo _ad('0','video-list-bottom');
?>



</div>
</div>
<div class="load-cats" data-type="1">
&nbsp;
</div>