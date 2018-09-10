
<?php

session_start();
require_once('fb-config.php');
 $accessToken = $_SESSION['facebook_access_token'];
  if (isset($accessToken)) {
      $_SESSION['facebook_access_token'] = (string) $accessToken;
  }
  $fb = new Facebook\Facebook([
    'app_id' => APP_ID, // Replace {app-id} with your app id
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.2',
    'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token']  : APP_SECRET
    ]);
try {
      $count=0;
        $albumIds=explode("_",$_GET['albumid']);
          for($i=0;$i<count($albumIds)-1;$i+=2)
          {
                 $re = $fb->get(
                    '/'.$albumIds[$i].'?fields=count',
                $accessToken
              );
            $graphEdge = $re->getGraphNode();
            $count+= $graphEdge['count'];
          }
          echo $count;
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}