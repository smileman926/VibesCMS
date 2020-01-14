<?php echo the_sidebar(); ?>
<div class="pad-holder col-md-8 nomargin">
<?php 
$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."activity where user in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."')");
$vq = "Select ".DB_PREFIX."activity.*, ".DB_PREFIX."users.avatar,".DB_PREFIX."users.id as pid, ".DB_PREFIX."users.name from ".DB_PREFIX."activity left join ".DB_PREFIX."users on ".DB_PREFIX."activity.user=".DB_PREFIX."users.id where ".DB_PREFIX."activity.user in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') ORDER BY ".DB_PREFIX."activity.id DESC ".this_limit();
include_once(TPL.'/layouts/global_activity.php');	
$pagestructure = canonical().'&p=';
$a = new pagination;	
$a->set_current(this_page());
$a->set_first_page(true);
$a->set_pages_items(7);
$a->set_per_page(bpp());
$a->set_values($count->nr);
$a->show_pages($pagestructure);

?>
</div>
