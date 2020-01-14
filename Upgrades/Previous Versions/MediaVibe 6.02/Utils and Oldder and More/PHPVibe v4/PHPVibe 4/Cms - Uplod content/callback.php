<?php error_reporting(E_ALL);
//ini_set('display_errors', '1');
include 'load.php';
ob_start();
if (is_user()) { redirect();}
//Check callback type(twitter, facebook, google)
if (!empty($_GET['type'])) {
    $cookieArr = array();
    switch ($_GET['type']) {
        case 'twitter':
            //Initialize twitter by using factory pattern over main class
			require_once( INC.'/twitter/EpiCurl.php' );
			require_once( INC.'/twitter/EpiOAuth.php' );
            require_once( INC.'/twitter/EpiTwitter.php' );
             $twitterObj = new EpiTwitter(Tw_Key, Tw_Secret);
			// var_dump($twitterObj);
			//  echo "<br /> ------------------------ <br />";
            $twitterObj->setToken($_GET['oauth_token']);
            $token = $twitterObj->getAccessToken();
            $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
            $twitterInfo= $twitterObj->get_accountVerify_credentials();	
            //var_dump($twitterInfo);
			// echo "<br /> ------------------------ <br />";			
			$keys_values = array(
                                "oauth_token" => $token->oauth_token,
								"oauth_token_secret" => $token->oauth_token_secret,
                                "name" => $twitterInfo->name,
                                "username" => $twitterInfo->screen_name,								
								"email"=> NULL,	
                                "avatar"=> $twitterInfo->profile_image_url,								
                                "type"=>"twitter"  );
            break;
        case 'facebook':
            //Initialize facebook by using factory pattern over main class
            require_once( INC.'/fb/facebook.php' );
			$facebook = new Facebook(array(
  'appId'  => Fb_Key,
  'secret' => Fb_Secret,
));
 // Get User ID
$user = $facebook->getUser();          
	if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me?fields=id,name,email,last_name,first_name,bio,location');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
$user_pic = user::getDataFromUrl('http://graph.facebook.com/'.$user_profile["id"].'/picture?type=large&redirect=false');
$avatarInfo = json_decode($user_pic);
$user_profile["img"] = $avatarInfo->data->url;
$loc = null;
if(!empty($user_profile["location"]["name"])) {
$loc = explode(",", $user_profile["location"]["name"]);
}
 $keys_values = array(
                                "fid"=>$user_profile["id"],
                                "name"=>$user_profile["name"],
								"username"=>nice_tag($user_profile["name"]),
								"email"=>$user_profile["email"],
								"local"=>$loc[0],
								"country"=>$loc[1],
								"email"=>$user_profile["email"],
								"bio"=>$user_profile["bio"],
								"gender"=>$user_profile["gender"],
								"avatar"=>$user_profile["img"],
                                "type"=>"facebook"                        
                             );
var_dump($keys_values); 							 
            break;
        case 'google':
                //Initialize google by using factory pattern over main class
               require_once(INC.'/google/Google/Client.php');
			   	$client = new Google_Client();
$client->setClientId(trim(get_option('GClientID')));
$client->setClientSecret(trim(get_option('GClientSecret')));
$client->setScopes("https://www.googleapis.com/auth/plus.login");
$client_key = trim(get_option('GClientID'));
$client_secret = trim(get_option('GClientSecret'));
$redirect_uri = $conf_google['return_url'];
// Check if the authorization code is received or not !
// Also, if the access token is received or not
	if (!isset($_REQUEST['code']) && !isset($_SESSION['access_token'])) {
		// Print the below message, if the code is not received !
		echo "Please Authorize your account: <br />";
		echo '<a href = "https://accounts.google.com/o/oauth2/auth?client_id='. $client_key. '&redirect_uri='.$redirect_uri .'&scope=https://www.googleapis.com/auth/plus.me&response_type=code">Click Here to Authorize</a>';
	}
	else {
    if(!isset($_SESSION['access_token'])) {
		  // Initialize a cURL session
		  $ch = curl_init();
		  // Set the cURL URL
		  curl_setopt($ch, CURLOPT_URL, "https://accounts.google.com/o/oauth2/token");
		  // The HTTP METHOD is set to POST
		  curl_setopt($ch, CURLOPT_POST, TRUE);
		  // This option is set to TRUE so that the response
		  // doesnot get printed and is stored directly in 
		  // the variable
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		  // The POST variables which need to be sent along with the HTTP request
		  curl_setopt($ch, CURLOPT_POSTFIELDS, "code=" . $_REQUEST['code'] . "&client_id=" . $client_key . "&client_secret=" . $client_secret . "&redirect_uri=".$redirect_uri."&grant_type=authorization_code");
		  // Execute the cURL request		
		  $data = curl_exec($ch);
		  // Close the cURL connection
		  curl_close($ch);
		  // Decode the JSON request and remove the access token from it
		  $data = json_decode($data);
		  $access_token = $data->access_token;
		  // Set the session access token
		  $_SESSION['access_token'] = $data->access_token;
    }
    else {
      // If session access token is set
      $access_token = $_SESSION['access_token'];
    }
	if(isset($access_token) && !empty($access_token)) {
		// Initialize another cURL session
		$ch = curl_init();
		// Set all the options and execute the session
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/plus/v1/people/me?access_token=" . $access_token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		// Get the data from the JSON response
		$data = json_decode($data);		
		//var_dump($data);
		foreach($data->emails as $em) {
  if($em->type == "account") {
    $email = $em->value;
  }
}
foreach($data->placesLived as $loc) {
  if($loc->primary) {
   $country = $loc->value;
  }
}		
$keys_values = array(
                                "gid"=>$data->id,
                                "name"=> $data->displayName,								
								"email"=>$email,
								"country"=>$country,
                                "avatar"=>$data->image->url,								
                                "type"=>"google"  );
	}	
}	
break;
              }
if(isset($keys_values) && is_array($keys_values)) {	
$id = user::checkUser($keys_values);
if(!$id || nullval($id)) {
$xid = user::AddUser($keys_values);
user::LoginUser($xid);
if (is_user()) { redirect(site_url().'index.php');}
} elseif(intval($id) > 0) {
user::LoginUser($id);
if (is_user()) { redirect(site_url().'index.php');}
} else {
die(_lang('Error. Please go back'));	
}
} else {
echo _lang('Error. Please go back');
}
} else {
echo _lang('Error. Please go back');
}
?>