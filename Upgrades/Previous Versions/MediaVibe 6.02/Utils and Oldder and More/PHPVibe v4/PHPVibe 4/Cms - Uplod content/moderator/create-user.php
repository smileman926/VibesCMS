<?php
if(_post('name') && _post('password') && _post('email')){
  $avatar = 'uploads/def-avatar.jpg';
	if($_FILES['avatar']){
	$formInputName   = 'avatar';							# This is the name given to the form's file input
	$savePath	     = ABSPATH.'/uploads';								# The folder to save the image
	$saveName        = md5(time()).'-'.user_id();									# Without ext
	$allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
	$imageQuality    = 100;
$uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
if ($uploader->getIsSuccessful()) {
//$uploader -> resizeImage(200, 200, 'crop');
$uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
$thumb  = $uploader->getTargetPath();
$avatar = str_replace(ABSPATH.'/' ,'',$thumb);
} 
	}
	$keys_values = array(
                                "avatar"=> $avatar,
								"local"=> _post('city'),
								"country"=> _post('country'),
                                "name"=> _post('name'),								
								"email"=> _post('email'),
                                "password"	 => sha1(_post('password')),							
                                "type"=> "core"  );
		$id = user::AddUser($keys_values);
		echo '<div class="msg-info">User '._post('name').' created with id: #'.$id.'</div>';
}
?>
<div class="row-fluid">
<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('create-user');?>" enctype="multipart/form-data" method="post">
<fieldset><!-- Form -->
<?php echo '			
<div class="control-group">
<label class="control-label">Name<span class="text-error">*</span></label>
<div class="controls">
<input type="text" name="name" class="validate[required] span12" placeholder="Visible name"> 						
</div>
</div>						
<div class="control-group">
<label class="control-label">'._lang("Email").'<span class="text-error">*</span></label>
<div class="controls">
<input type="text" name="email" class="validate[required] span12" placeholder="'._lang("Email address").' "> 
</div>
</div>	
<div class="control-group">
<label class="control-label">'._lang("Choose Password").' <span class="text-error">*</span></label>
<div class="controls">	
<input type="text" name="password" class="validate[required] span12" value="'.uniqid().'"> 
</div>
</div>
<div class="control-group">	
	<label class="control-label">'._lang("City").'</label>
	<div class="controls">	
<input type="text" name="city" class="span12" placeholder="'._lang("City").'">

</div>
</div>
<div class="control-group">							
<label class="control-label">'._lang("Country").'</label>
<div class="controls">							
<input type="text" name="country" class=" span12" placeholder="'._lang("Country").'"> 

</div>
</div>
<div class="control-group">	
						 <label class="control-label">'._lang("Avatar").'</label>
	             <div class="controls">	          <input type="file" name="avatar" class="styled">
	</div>
</div>                          
						<div class="row-fluid">
							
								<button class="btn btn-large btn-primary pull-right" type="submit">Create user</button>
							
						</div>';
?>		
					
</fieldset>						
</form>
</div>