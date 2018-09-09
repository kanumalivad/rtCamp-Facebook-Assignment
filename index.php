<?php
  
  require_once('fb-config.php');
  session_start();

  $fb = new Facebook\Facebook([
    'app_id' => APP_ID, // Replace {app-id} with your app id
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.2',
    'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token']  : APP_SECRET
  ]);
  $helper = $fb->getRedirectLoginHelper();
  $permissions=['email','user_photos'];
  $loginUrl= $helper->getLoginUrl(REDIRECT_URL,$permissions);

  $accessToken= $helper->getAccessToken();
  if(isset($accessToken))
      $_SESSION['facebook_access_token']= (string) $accessToken;

  if(isset($_SESSION['facebook_access_token']))
      header('location:'.DOMAIN.'my-albums.php');
    
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="lib\css\bootstrap.min.css" rel="stylesheet">
    <link href="lib\css\custome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script type="text/javascript" src="lib\js\bootstrap.min.js"></script>
    <title>rtCamp Facebook Assignment</title>
</head>
<style type="text/css">
  body {
    background-image: url("images/bg-img.png");
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>
<body>
	<div class="text-center" id="body">
        <div class= "login-btn-container">
            <a href="<?php echo $loginUrl ?>">
                <button type="button" class="btn btn-lg" style="background-color: #3B5998; color: white">
                    <i class="fa fa-facebook" style="font-size: 30px;"></i>
                     &nbsp; Login With Facebook
                </button>
            </a>
        </div>
    </div>
</body>
</html>