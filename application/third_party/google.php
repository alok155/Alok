<?php
session_start();
include_once("google/Google_Client.php");
include_once("google/contrib/Google_Oauth2Service.php");
######### edit details ##########
$clientId = '3670650534-vp77noreo84n3ep4c5q9p2qip48bhopp.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = '3iKdcrJuVOGlBnzNcPnJ3q9W'; //Google CLIENT SECRET
$redirectUrl = 'http://testsite.dev/index.php/welcome/google';  //return url (url to script)
$homeUrl = 'http://testsite.dev/index.php/welcome/';  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login Alok world');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>