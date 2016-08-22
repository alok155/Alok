<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	session_start();
	//$this->db();
	//die();
	$this->abc();
	$this->load->helper('url');
	require_once APPPATH."/third_party/google.php";
	}
	public function db()
	{
	//error_reporting(E_ALL);
	
//$output = file_get_contents('error.txt', true);
$output = explode("\n", file_get_contents('error.txt',true));
?>
<Table border="1">
    <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Client</th>
        <th>Message</th>
    </tr>
<?php
 //echo "<Pre>@@";print_r($output);
    foreach($output as $line) {
	//echo $line;
	$array = explode('PHP', $line);
	$key = $array[0];
	$value= $array[1];
	$whatIWant = substr($value, strpos($value, "main") + 1);    
   $final_array = explode('/',$whatIWant);
   echo "<pre>";print_r($final_array)."<br/>";
	//echo $key."  ".$whatIWant."<br/>";
	
    	// sample line: [Wed Oct 01 15:07:23 2008] [alok: error] [client 76.246.51.127] PHP 99. Debugger->handleError() /home/gsmcms/public_html/central/cake/libs/debugger.php:0
		
		//[Thu Feb 18 23:55:34.245487 2016] [ssl:warn] [pid 728:tid 280] AH01909: www.example.com:443:0 server certificate does NOT include an ID which matches the server name
		
		//[Sat Apr 23 00:13:26.673604 2016] [access_compat:error] [pid 2704:tid 1648] [client ::1:63042] AH01797: client denied by server configuration: D:/xampp/htdocs/facebook/application/
		// 10 PHP Notice:  Undefined index: profileImage in /var/www/html/index.php on line 265
    	preg_match('~^\[(.*?)\]~', $line, $date);
    	if(empty($date[1])) {
    		continue;
    	}
    	preg_match('~\] \[([a-z]*?)\] \[~', $line, $type);
    	preg_match('~\] \[client ([0-9.]*)\]~', $line, $client);
    	preg_match('~\] (.*)$~', $line, $message);
    	?>
    <tr>
        <td><?=$date[1]?></td>
        <td><?=$type[1]?></td>
        <td><?=$client[1]?></td>
        <td><?=$message[1]?></td>
    </tr>
    	<?php
    }
	echo "<pre>@@";print_r($array);
?>
</table>
	<?php 
	}
	
	public function alok(){
		session_start();
		require_once APPPATH."/third_party/facebook/autoload.php";
$fb = new Facebook\Facebook([
  'app_id' => '229497827410254',
  'app_secret' => '7a3315115f56b9a5f1cd3cd8e6cdbed9',
  'default_graph_version' => 'v2.5',
]); 

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
//$loginUrl = $helper->getLoginUrl('http://www.testsite.dev/callback.php', $permissions);
$loginUrl = $helper->getLoginUrl('http://testsite.dev/index.php/welcome/index_test', $permissions);

header("location: ".$loginUrl);
		$this->load->view('welcome_message');
	}
	
	public function index_test()
	{
		/////
		session_start();
	require_once APPPATH."/third_party/facebook/autoload.php";	

$fb = new Facebook\Facebook([
  'app_id' => '229497827410254',
  'app_secret' => '7a3315115f56b9a5f1cd3cd8e6cdbed9',
  'default_graph_version' => 'v2.5',
]);  
  
$helper = $fb->getRedirectLoginHelper();  
  
try {  
  $accessToken = $helper->getAccessToken();  
} catch(Facebook\Exceptions\FacebookResponseException $e) {  
  // When Graph returns an error  
  
  echo 'Graph returned an error: ' . $e->getMessage();  
  exit;  
} catch(Facebook\Exceptions\FacebookSDKException $e) {  
  // When validation fails or other local issues  

  echo 'Facebook SDK returned an error: ' . $e->getMessage();  
  exit;  
}  


try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me?fields=id,name,email,first_name,last_name', $accessToken->getValue());
//  print_r($response);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'ERROR: Graph ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'ERROR: validation fails ' . $e->getMessage();
  exit;
}
$me = $response->getGraphUser();
//print_r($me);
echo "Full Name: ".$me->getProperty('name')."<br>";
echo "First Name: ".$me->getProperty('first_name')."<br>";
echo "Last Name: ".$me->getProperty('last_name')."<br>";
echo "Email: ".$me->getProperty('email')."<br>";
echo "Facebook ID: <a href='https://www.facebook.com/".$me->getProperty('id')."' target='_blank'>".$me->getProperty('id')."</a>";
		////
	}
	
	function linkedin()
	{
		if(isset($_GET['code'])){
			$this->getAccessToken();

			$User = $this->fetch('GET', '/v1/people/~:(id,first-name,last-name,headline,email-address,picture-url,industry,site-standard-profile-request,interests,summary,main-address,phone-numbers,location,skills:(skill))');
//echo '<pre>';print_r($User);

			$id             = isset($User->id) ? $User->id : '';
			$firstName      = isset($User->firstName) ? $User->firstName : '';
			$lastName       = isset($User->lastName) ? $User->lastName : '';
			$emailAddress   = isset($User->emailAddress) ? $User->emailAddress : '';
			$headline       = isset($User->headline) ? $User->headline : '';
			$pictureUrls    = isset($User->pictureUrl) ? $User->pictureUrl : '';
			$location       = isset($User->location->name) ? $User->location->name : '';
			$positions      = isset($User->positions->values[0]->company->name) ? $User->positions->values[0]->company->name : '';
			$positionstitle = isset($User->positions->values[0]->title) ? $User->positions->values[0]->title : '';
			$publicProfileUrl = isset($User->siteStandardProfileRequest->url) ? $User->siteStandardProfileRequest->url : '';
		   
			echo "
			<table border='1' cellpadding='7' style='border-collapse: collapse;'>
				<tr style='text-align: center;'>
					<td colspan='2'><img src='".$pictureUrls."' width='100' /><br>".$headline."</td>
				</tr>
				<tr>
					<td>ID: </td>
					<td>".$id."</td>
				</tr>
				<tr>
					<td>First Name: </td>
					<td>".$firstName."</td>
				</tr>
				<tr>
					<td>last Name: </td>
					<td>".$lastName."</td>
				</tr>
				<tr>
					<td>Email: </td>
					<td>".$emailAddress."</td>
				</tr>
				<tr>
					<td>Job Position: </td>
					<td>".$positionstitle.": ".$positions."</td>
				</tr>
				<tr>
					<td>Location: </td>
					<td>".$location."</td>
				</tr>
				<tr>
					<td>Profile Link: </td>
					<td><a href='".$publicProfileUrl."' target='_blank'>".$publicProfileUrl."</a></td>
				</tr>
			</table>
			";    
    
		}
	}
	function google()
	{
	
	require_once APPPATH."/third_party/google.php";
	if(isset($_REQUEST['code'])){
	$gClient->authenticate();
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectUrl, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	$userProfile = $google_oauthV2->userinfo->get();
	
	echo "<pre>@@@";print_r($userProfile);die();
	//DB Insert
	// $gUser = new Users();
	// $gUser->checkUser('google',$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email'],$userProfile['gender'],$userProfile['locale'],$userProfile['link'],$userProfile['picture']);
	// $_SESSION['google_data'] = $userProfile; // Storing Google User Data in Session
	// header("location: account.php");
	$_SESSION['token'] = $gClient->getAccessToken();
} else {
	$authUrl = $gClient->createAuthUrl();
}
	
	}
	
	function twitter()
	{
     session_start();
	require_once APPPATH."/third_party/twitter_app.php";
	require_once APPPATH."/third_party/twitter/twitteroauth.php";
	// echo "<pre>";print_r($_REQUEST);
	// echo "<pre>";print_r($_SESSION);die();
	
	
	
if(isset($_REQUEST['oauth_token']) && $_SESSION['token']  !== $_REQUEST['oauth_token']) {

	//If token is old, distroy session and redirect user to index.php
	session_destroy();
	header('Location: index.php');
	
}elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {


	//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code == '200')
	{
		//Redirect user to twitter
		$_SESSION['status'] = 'verified';
		$_SESSION['request_vars'] = $access_token;
		
		//Insert user into the database
		$user_info = $connection->get('account/verify_credentials', ['include_email' => 'true']);
		//echo "email--->".$user_info->email;die();
		$name = explode(" ",$user_info->name);
		$fname = isset($name[0])?$name[0]:'';
		$lname = isset($name[1])?$name[1]:'';
		// $db_user = new Users();
		// $db_user->checkUser('twitter',$user_info->id,$user_info->screen_name,$fname,$lname,$user_info->lang,$access_token['oauth_token'],$access_token['oauth_token_secret'],$user_info->profile_image_url);
		
		//Unset no longer needed request tokens
		unset($_SESSION['token']);
		unset($_SESSION['token_secret']);
		$this->showtwitter();
	}else{
		die("error, try again later!");
	}
		
}
    //Fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	//Received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	
	//Any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code == '200')
	{
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		//echo "twitter url".$twitter_url;die();
		header('Location: ' . $twitter_url); 
	}else{
		die("error connecting to twitter! try again later!");
	}
	
	}
	function showtwitter()
	{
	session_start();
	require_once APPPATH."/third_party/twitter_app.php";
	require_once APPPATH."/third_party/twitter/twitteroauth.php";
	 echo "<pre>";print_r($_REQUEST);
	 echo "<pre>";print_r($_SESSION);die();
	if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified') 
	{
		//Retrive variables
		$screen_name 		= $_SESSION['request_vars']['screen_name'];
		$twitter_id			= $_SESSION['request_vars']['user_id'];
		$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
		$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
	
		//Show welcome message
		echo '<div class="welcome_txt">Welcome <strong>'.$screen_name.'</strong> (Twitter ID : '.$twitter_id.'). <a href="logout.php?logout">Logout</a>!</div>';
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		
		//If user wants to tweet using form.
		if(isset($_POST["updateme"])) 
		{
			//Post text to twitter
			$my_update = $connection->post('statuses/update', array('status' => $_POST["updateme"]));
			die('<script type="text/javascript">window.top.location="index.php"</script>'); //redirect back to index.php
		}
		
		//show tweet form
		echo '<div class="tweet_box">';
		echo '<form method="post" action="index.php"><table width="200" border="0" cellpadding="3">';
		echo '<tr>';
		echo '<td><textarea name="updateme" cols="60" rows="4"></textarea></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><input type="submit" value="Tweet" /></td>';
		echo '</tr></table></form>';
		echo '</div>';
		
		//Get latest tweets
		$my_tweets = $connection->get('statuses/user_timeline', array('screen_name' => $screen_name, 'count' => 5));
		
		echo '<div class="tweet_list"><strong>Latest Tweets : </strong>';
		echo '<ul>';
		foreach ($my_tweets  as $my_tweet) {
			echo '<li>'.$my_tweet->text.' <br />-<i>'.$my_tweet->created_at.'</i></li>';
		}
		echo '</ul></div>';
			
	}
	
	
	
	}
	
	
	function abc()
	{
	$this->load->helper('url'); 
	$this->config->load('linkedin');
	
	require_once APPPATH."/third_party/google.php";
	require_once APPPATH."/third_party/twitter_app.php";
	require_once APPPATH."/third_party/twitter/twitteroauth.php";
	
	?>
	<script type="text/javascript" src="<?php echo base_url("assets/js/jQuery-1.12.3.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/amazon.js"); ?>"></script>

<?php
	 //echo  base_url();die();
	 //echo phpinfo();die();
	  $image_source	="http://testsite.dev/images/facebook.png";
	  $image_source_linkedin	="http://testsite.dev/images/linkedin_connect_button.png";
	  $image_source_google	="http://testsite.dev/images/glogin.png";
	  $image_source_twitter	="http://testsite.dev/images/sign-in-with-twitter.png";
	  //linkedin_connect_button
	  //echo $image_source;die();$this->config->item('callback_url');
		echo "<a href='http://testsite.dev/index.php/welcome/alok'><img src='$image_source'></a>";
		 echo '<a href="https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id='.$this->config->item('Client_ID').'&redirect_uri='.$this->config->item('callback_url').'&state=98765EeFWf45A53sdfKef4233&scope=r_basicprofile r_emailaddress"><img src="'.$image_source_linkedin.'" alt="Sign in with LinkedIn"/></a>';
		$authUrl = $gClient->createAuthUrl();
		echo '<a href="'.$authUrl.'"><img src="'.$image_source_google.'" alt=""/></a>';
		echo '<a href="http://testsite.dev/index.php/welcome/twitter"><img src="'.$image_source_twitter.'" width="151" height="24" border="0" /></a>';
		?>
		
<a href="?login">Login with Yahoo</a>

		<a href='https://www.amazon.com/ap/oa?client_id="amzn1.application-oa2-client.9d33f605dce343a183c57cee69d27396&scope"&scope="profile"&response_type="code"&state="7162637623476234"&redirect_uri="http://testsite.dev/index.php/welcome/amazon"' id="LoginWithAmazon">
  <img border="0" alt="Login with Amazon"
    src="https://images-na.ssl-images-amazon.com/images/G/01/lwa/btnLWA_gold_156x32.png"
    width="156" height="32" />
</a>
<script type="text/javascript">

  document.getElementById('LoginWithAmazon').onclick = function() {
   options = {} ;
options.scope = 'profile';
options.response_type='code'; 
    amazon.Login.authorize(options, function(response) {
if ( response.error ) {
alert('oauth error ' + response.error);
return;
}

});

</script>
	<?php }

	function getAccessToken() {
		$params = array('grant_type' => 'authorization_code',
						'client_id' => '7543m54l849l0v',
						'client_secret' => 'tfTWkJF8BCFpugPU',
						'code' => $_GET['code'],
						'redirect_uri' => 'http://testsite.dev/index.php/welcome/linkedin',
				  );
		$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
	 
		// Tell streams to make a POST request
		$context = stream_context_create(
						array('http' => 
							array('method' => 'POST',
							)
						)
					);
	 
		// Retrieve access token information
		$response = file_get_contents($url, false, $context);
	 
		// Native PHP object, please
		$token = json_decode($response);
	 
		// Store access token and expiration time
		$_SESSION['access_token'] = $token->access_token; // guard this! 
		$_SESSION['expires_in']   = $token->expires_in; // relative time (in seconds)
		$_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time
	 
		return true;
	}
	
	
	function fetch($method, $resource, $body = '') {
		$params = array('oauth2_access_token' => $_SESSION['access_token'],
						'format' => 'json',
				  );
	 
		// Need to use HTTPS
		$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
		// Tell streams to make a (GET, POST, PUT, or DELETE) request
		$context = stream_context_create(
						array('http' => 
							array('method' => $method,
							)
						)
					);
	 
		// Hocus Pocus
		$response = file_get_contents($url, false, $context);
	 
		// Native PHP object, please
		return json_decode($response);
	}
	
	
	function amazon(){
	echo "<pre>";print_r($_REQUEST);die();
	// verify that the access token belongs to us
$c = curl_init('https://api.amazon.com/auth/o2/tokeninfo?access_token=' . urlencode($_REQUEST['access_token']));
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
 
$r = curl_exec($c);
curl_close($c);
$d = json_decode($r);
 
if ($d->aud != 'amzn1.application-oa2-client.9d33f605dce343a183c57cee69d27396') {
  // the access token does not belong to us
  header('HTTP/1.1 404 Not Found');
  echo 'Page not found';
  exit;
}
 
// exchange the access token for user profile
$c = curl_init('https://api.amazon.com/user/profile');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: bearer ' . $_REQUEST['access_token']));
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
 
$r = curl_exec($c);
curl_close($c);
$d = json_decode($r);
 
echo sprintf('%s %s %s', $d->name, $d->email, $d->user_id);
	
	}
}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

?>
<html>
<body>
<div id="amazon-root"></div>

<script type="text/javascript">

  window.onAmazonLoginReady = function() {
    amazon.Login.setClientId('amzn1.application-oa2-client.9d33f605dce343a183c57cee69d27396');
  };
  (function(d) {
    var a = d.createElement('script'); a.type = 'text/javascript';
    a.async = true; a.id = 'amazon-login-sdk';
    a.src = 'https://api-cdn.amazon.com/sdk/login1.js';
    d.getElementById('amazon-root').appendChild(a);
  })(document);

  
  
</script>


</body>
</html>

