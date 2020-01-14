<?php //echo $vq;
$activity = $db->get_results($vq);
if ($activity) {
$did =  array();
echo '<div class="row-fluid wookdiv">
<ul id="user-timeline" class="user-activity list-unstyled">
'; 
$licon = array();
$licon["1"] = "icon-heart";
$licon["2"] = "icon-share";
$licon["3"] = "icon-eye-open";
$licon["4"] = "icon-play";
$licon["5"] = "icon-rss";
$licon["6"] = "icon-comments";
$licon["7"] = "icon-thumbs-up";
foreach ($activity as $buzz) {
$did = get_activity($buzz);	
if(isset($did["content"]) && !nullval($did["content"])) {
/* quick dirty hack to remove spans from PHPVibe's timeline */
$did["content"] = str_replace(array("span4","span8"),array("","") ,$did["content"]);
echo '
<li class="cul-'.$buzz->type.' t-item">

<div class="block block-inline">
<div class="box-generic">
<div class="timeline-top-info">
<img class="timeline-av" src="'.thumb_fix($buzz->avatar, true, 40, 40).'"/>
<div class="timeline-head">
<a href="'.canonical().'">'._html($buzz->name).'</a>  '.$did["what"].'
<span class="timer">     '.time_ago($buzz->date).' </span>
</div>		
</div>	
<div class="media innerAll margin-none">
'.$did["content"].'
</div></div>

</div></li>';
unset($did);
}
}
echo '<ul><br style="clear:both;"/></div>';
}
?>
