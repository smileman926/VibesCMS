<?php $error='';
 if(is_user()){
  redirect(site_url().'dashboard/');	  
  }
/** Login via mail request **/

if(_get('do') && (_get('do') == "autologin") && _get('m') && _get('k')) {
$mail = base64_decode(_get('m'));
$key = base64_decode(_get('k'));
$check = user::CheckMail($mail);
if($check > 0 ) {
$result = $db->get_row("SELECT id FROM ".DB_PREFIX."users WHERE email ='" . toDb($mail) . "' and pass = '" . toDb($key) . "'");
if($result && $result->id) {
user::LoginUser($result->id);
if (is_user()) { redirect(site_url().me.'&sk=password');} else {
$error = '<div class="msg-warning">'._lang('Something went wrong. Try refreshing this page').'</div>';
}
} else {
$error = '<div class="msg-warning">'._lang('Something went wrong. Wrong credentials').'</div>';
}
} else {
$error = '<div class="msg-warning">'._lang('Something went wrong. That email is wrong').'</div>';
}
}
/** Actual login **/
if(_post('password') && _post('email')){
if(user::loginbymail(_post('email'),_post('password') )) {
if(_get('return')) {
redirect(site_url()._get('return').'/');
}elseif(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
redirect($_SERVER['HTTP_REFERER']);
} else {
redirect(site_url().'dashboard/');
}
} else {
$error = '<div class="msg-warning">'._lang('Wrong username or password.').'</div>';
}
}
/** New password request **/
if(_post('forgot-pass') && _post('remail')){
$check = user::CheckMail(_post('remail'));
if($check > 0 ) {
$omail = toDb(_post('remail'));
$result = $db->get_row("SELECT pass, name FROM ".DB_PREFIX."users WHERE email ='" . toDb($omail) . "'");
if($result) {
$key = base64_encode($result->pass);
$link = site_url().'login&do=autologin&m='.base64_encode($omail).'&k='.$key;
$message = _lang('In order to change your password please follow this link:');
$message .= '<br /> <a href="##link##">##link##</a> <br />';
$message .= 'Regards '.site_url();
$message = str_replace("##link##",$link,$message);	
//echo $link;
$mail = new PHPMailer;
$mail->From = "noreply@".ltrim(cookiedomain(),".");
$mail->FromName = get_option('site_logo-text');	
$mail->addAddress($omail, toDb($result->name));
$mail->WordWrap = 50;  
$mail->Subject = _lang('Password change for'). ' '.$result->name;
$mail->Body    = $message;
$mail->AltBody = $message;
if(!$mail->send()) {
$error = '<div class="msg-warning">'.toDb(_post('remail')).' '._lang('Message could not be sent.').$mail->ErrorInfo.'</div>';
} else {
$error = '<div class="msg-note">'._lang('An e-mail has been sent to your account. Please also check the "spam" folder.').'</div>';					
}
}
} else {
$error = '<div class="msg-warning">'.toDb(_post('remail')).' '._lang('is not registered to any account.').'</div>';
}
}

// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('login')));
}
$socials = '';
if(get_option('allowfb') == 1 ) {
$socials .= '<p><a href="'.site_url().'?action=login&type=facebook" class="btn btn-block btn-labeled  social-facebook"><span class="btn-label"><i class="icon icon-facebook" aria-hidden="true"></i></span>'._lang("Signin with <strong>Facebook</strong>").'</a></p>';	
}
if(get_option('allowg') == 1 ) {
$socials .= '<p><a href="'.site_url().'?action=login&type=google" class="btn btn-block btn-labeled  social-google-plus"><span class="btn-label"><i class="icon icon-google-plus" aria-hidden="true"></i></span>'._lang("Signin with <strong>Google</strong>").'</a></p>';
}

if(get_option('allowlocalreg') == 1 ) {
$registerhere = '<a href="'.site_url().'register"><strong>'._lang('register here').'</strong></a>';
} else {
$registerhere = _lang("by choosing a social service you use.");	
}
function modify_content( $text ) {
global $error, $socials,$registerhere;
if(_get('return')) { $rt ='&'._get('return'); } else { $rt = '';}; 
return $error.'
<div class="row text-center clearfix odet mbot20 mtop20">
'.((_get("justlogin") ? _lang("<h1>Thank you for joining!</h1>") : "")).'
<h2>'._lang("Login now").'</h2>
'._lang("Sign in to your account").' 
<script>
$(document).ready(function() {
showLogin();
 });
</script>
 <a class="btn btn-primary btn-block mtop20" href="javascript:showLogin()">'._lang("Show login box").'</a>
</div>
';
}
function forgot_content() {
global $error;
$socials = '';
if(get_option('allowfb') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=facebook" class="btn btn-labeled btn-lg social-facebook"><span class="btn-label"><i class="icon icon-facebook" aria-hidden="true"></i></span>'._lang("Signin with <strong>Facebook</strong>").'</a>';	
}
if(get_option('allowg') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=google" class="signin-btn googlecolor mtop10">'._lang("Signin with <strong>Google</strong>").'</a>';
}
if(get_option('allowtw') == 1 ) {
$socials .= '<a href="'.site_url().'?action=login&type=twitter" class="signin-btn twittercolor mtop10">'._lang("Signin with <strong>Twitter</strong>").'</a>';
	
}
return $error.'
<div id="validate" class="form-signin">
		
	
		<div class="row row-merge clearfix">
	<div class="col-md-5">
				<h3>'._lang("Recover password").'</h3>
				'._lang("Recover your password by e-mail. If you've used an social service, just click on the button for that service on the login page.").'
				<br>
				<br>
				'._lang("If you don't have an account yet you can").' <a href="'.site_url().'register"><strong>'._lang("register here").'</strong></a>.
				</div>
				<div class="col-md-6">
				<div class="inner signin-with">
				<!-- Form -->
					<form class="styled" action="'.canonical().'" enctype="multipart/form-data" method="post">
					<input type="hidden" name="forgot-pass" value="1"/>
						
						<input type="text" name="remail" class="validate[required] form-control" placeholder="'._lang("Email address").' "> 
						<div class="row">
							<div class="col-md-5 center">
								<button class="btn btn-large btn-primary" type="submit">'._lang("Recover").'</button>
							</div>
						</div>
						
					</form>
					<div class="row top20">
						'.$socials.'
						</div>
				</div>
			</div>
			
			
		
      </div>
	</div>';
}
add_filter( 'phpvibe_title', 'modify_title' );
if(_get('do') && (_get('do') == "forgot")) {
add_filter( 'the_defaults', 'forgot_content' );
} else {
add_filter( 'the_defaults', 'modify_content' );
}
//Time for design
 the_header();
include_once(TPL.'/default-full.php');
the_footer();
?>
