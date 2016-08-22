<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class abc extends CI_Controller {

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
		require_once APPPATH."/third_party/facebook/autoload.php";
$fb = new Facebook\Facebook([
  'app_id' => '229497827410254',
  'app_secret' => '7a3315115f56b9a5f1cd3cd8e6cdbed9',
  'default_graph_version' => 'v2.5',
]); 

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
//$loginUrl = $helper->getLoginUrl('http://www.testsite.dev/callback.php', $permissions);
$loginUrl = $helper->getLoginUrl('http://www.testsite.dev/welcome/index_test', $permissions);

//header("location: ".$loginUrl);
		$this->load->view('welcome_message');
	}
	
	public function index_test()
	{
		/////
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
	
	function abc()
	{
		echo 'aaaaaaaaaa';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */