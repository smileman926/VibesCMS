<?php  if(is_user()){
  redirect(site_url().'dashboard/');	  
  }
$error='';
require_once(INC.'/recaptchalib.php');
$publickey = "6Lc-A84SAAAAAD3btrvWyQUi7MI6EX1fH_RE6p0U"; 
$privatekey = "6Lc-A84SAAAAAONAhS-azqRi9Dyqkkzz5XZ4FvMb";
function add_rec($text) {
$text = $text.'<script type="text/javascript">
 var RecaptchaOptions = {     theme : \'clean\'  };
 </script>';
return $text;
}
add_filter( 'filter_extracss', 'add_rec' );

						 
//If submited
if(get_option('allowlocalreg') == 1 ) {
if(_post('name') && _post('password') && _post('email')){
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
   $error = '<div class="msg-warning">The reCAPTCHA wasn\'t entered correctly. Go back and try it again
         reCAPTCHA error: ' . $resp->error.'</div>';
  } else {
	  if (filter_var(_post('email'), FILTER_VALIDATE_EMAIL)) {

  if(_post('password') == _post('password2')) {
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
		if(user::CheckMail(_post('email')) < 1) {
		$keys_values = array(
                                "avatar"=> $avatar,
								"local"=> _post('city'),
								"country"=> _post('country'),
                                "name"=> _post('name'),								
								"email"=> _post('email'),
                                "password"	 => sha1(_post('password')),							
                                "type"=> "core"  );
		$id = user::AddUser($keys_values);
        user::LoginUser($id);
	    redirect(site_url().'dashboard/');
		} else {
		$error = '<div class="msg-warning">' . _lang('Email already in use').'</div>';
		}						
	
	} else {
	$error = '<div class="msg-warning">' . _lang('Passwords are not the same').'</div>';
  }
  
  } else {
$error = '<div class="msg-warning">' . _lang('Invalid e-mail detected.').'</div>';
  }
  if(is_user()){
  redirect(site_url().'dashboard/');	  
  }
  
}
}
}
//if (is_user()) { redirect(site_url().me);}
if(get_option('allowlocalreg') == 0 ) { redirect(site_url()); }
$captcha =  recaptcha_get_html($publickey);

// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('registration')));
}
$socials = '';
if(get_option('allowfb') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=facebook" class="signin-btn fbcolor">'._lang("Signin with <strong>Facebook</strong>").'</a>';	
}
if(get_option('allowg') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=google" class="signin-btn googlecolor mtop10">'._lang("Signin with <strong>Google</strong>").'</a>';
}
if(get_option('allowtw') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=twitter" class="signin-btn twittercolor mtop10">'._lang("Signin with <strong>Twitter</strong>").'</a>';
	
}
function modify_content( $text ) {
global $error , $captcha,$socials;
return $error.'
<div id="validate" class="form-signin">
		
<div class="span5">
				<h3>'._lang("Create a new Account").'</h3>
				'._lang("Thank you for deciding to join the fun!").'
				<br>
				<br>
				'._lang("Complete the form in the right or choose a fast social registration and let's get started!").' 
				<div class="row-fluid mtop20 signin-with">
'.$socials.'
						</div>
				</div>
				<div class="span6">
	
		<div class="row-fluid row-merge clearfix">
		
				<div class="inner">
				<!-- Form -->
					<form class="styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
					    <div class="span12" style="height:1px;"></div>
						<input type="text" name="name" class="validate[required] span12 mtop10" placeholder="'._lang("Your stage name").' "> 						
						
						<input type="text" name="email" class="validate[required] span12 mtop10" placeholder="'._lang("Email address").' "> 
						
						<input type="password" name="password" class="validate[required] span12 mtop10" placeholder="'._lang("Choose Password").'"> 
						
						<input type="password" name="password2" class="validate[required] span12 mtop10" placeholder="'._lang("Repeat Password").'"> 
						
						<input type="text" name="city" class="validate[required] span12 mtop10" placeholder="'._lang("City").'"> 
						
						<input type="text" name="country" class="validate[required] span12 mtop10" placeholder="'._lang("Country").'"> 
						<div class="span12">
						<p>'._lang("Select your avatar").'</p>
	                       <input type="file" name="avatar" placeholder="'._lang("Select your avatar").'" class="styled">
						  </div> 
	                           <div class="span12 mtop20">      '.$captcha.'</div>
						<div class="span12 mtop20">
							<div class="span5 center">
								<button class="btn btn-large btn-primary" type="submit">'._lang("Create account").'</button>
							</div>
						</div>
					</form>
				</div>
			
			
			</div>
			
		</div>
		</div>
		
      </div>
	</div>
';
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'the_defaults', 'modify_content' );

//Time for design
 the_header();
include_once(TPL.'/default-full.php');
the_footer();
?>