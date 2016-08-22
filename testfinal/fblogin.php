<?php
session_start();
require_once( 'Facebook/autoload.php' );

/*
$config['appID']	= '229497827410254';


$config['appSecret']	= '7a3315115f56b9a5f1cd3cd8e6cdbed9';

*/

$fb = new Facebook\Facebook([
  'app_id' => '229497827410254',
  'app_secret' => '7a3315115f56b9a5f1cd3cd8e6cdbed9',
  'default_graph_version' => 'v2.5',
]); 

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
$loginUrl = $helper->getLoginUrl('http://www.testsite.dev/callback.php', $permissions);

header("location: ".$loginUrl);

?>