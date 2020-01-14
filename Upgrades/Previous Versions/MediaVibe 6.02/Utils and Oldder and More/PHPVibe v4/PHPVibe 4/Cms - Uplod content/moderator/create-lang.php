<?php /* We build it on top of Enghlish */
$en_terms = $db->get_results("SELECT DISTINCT term from ".DB_PREFIX."langs limit 0,100000", ARRAY_A );
//var_dump($en_terms);
if(isset($_POST["lang-code"])) {
$lang = $_POST["lang-code"];
$ar = array();
$ar["language-name"] = $_POST["language-name"];
foreach ($_POST["term"] as $key=>$value) {
$ar[$key] = $value;
}
add_language($lang ,$ar );
echo '<div class="msg-info">Language '.$lang.' was created.</div>';
}
?>
<div class="cleafix row-fluid">
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('create-lang');?>" enctype="multipart/form-data" method="post">
<div class="control-group">
<label class="control-label"><i class="icon-globe"></i>Language code</label>
<div class="controls">
<input type="text" name="lang-code" class=" span1" value="" /> 
<span class="help-block" id="limit-text">Ex: it, es, fr, ro, se. See <a href="http://www.worldatlas.com/aatlas/ctycodes.htm" target="_blank">country codes (2 letters)</a></span>						
</div>	
</div>
<div class="control-group">
<label class="control-label"><i class="icon-font"></i>Language name</label>
<div class="controls">
<input type="text" name="language-name" class=" span5" value="" /> 
<span class="help-block" id="limit-text">Ex: Italian, Romanian, Swedish</span>						
</div>	
</div>
<div class="table-overflow top10">
                        <table class="table table-bordered table-checks">
                          <thead>
                              <tr>
                                 
                                  <th>Term</th>
                                  <th >Translation</th>
								  
                               </tr>
                          </thead>
                          <tbody>
						  <?php foreach ($en_terms as $t) {
                             if($t["term"] !== "language-name") {
						  ?>
                              <tr>
                                   <td><?php echo stripslashes($t["term"]); ?></td>
                                  <td>
								  <input type="text" name="term[<?php echo stripslashes($t["term"]); ?>]" class="span12" value="<?php echo stripslashes($t["term"]); ?>" /> 	
								  </td>
                                                                
                              </tr>
							  <?php }} ?>
						</tbody>  
</table>
</div>
<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit">Create language</button>	
</div>	
</form>						
</div>						
<?php ?>