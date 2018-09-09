<?php
	require_once("functions.php");
	session_start();
	require_once('fb-config.php');

	header('Content-Type: text/html; charset=utf-8');

	global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;

	$client = new Google_Client();
	$client->setClientId($CLIENT_ID);
	$client->setClientSecret($CLIENT_SECRET);
	$client->setRedirectUri($REDIRECT_URI);
	$client->setScopes('email');

	$authUrl = $client->createAuthUrl();	

	if(isset($_GET['code'])){
		getCredentials($_GET['code'], $authUrl);
<<<<<<< HEAD
		header("Location:".DOMAIN."my-albums.php");
=======
		header("Location:https://localhost/rtCamp/my-albums.php");
>>>>>>> 08d220881b4581cf07b5796bcb7c3fc6f425c2c7
	}
	else{
		header('Content-Type: text/html; charset=utf-8');
		$url = getAuthorizationUrl("", "");
		echo "<script> window.location= '".$url."'; </script>";
	}
?>