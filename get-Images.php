
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
  $re = $fb->get(
            '/'.$_GET['albumid'].'/photos?limit=500',
            $accessToken
          );
        $graphEdge = $re->getGraphEdge();

        $album_img= $fb->get( '/'.$_GET['albumid'].'?fields=photo_count',$accessToken);
        $user1 = $album_img->getGraphNode();
        $count=$user1['photo_count'];

        for($i=0;$i<count($graphEdge);$i++)
        	echo $graphEdge[$i]['id'].',';
        $count=$count-100;
        while($count>0)
         {
            $nextFeed = $fb->next($graphEdge);
            for($j=0;$j<count($nextFeed);$j++)
              echo $nextFeed[$j]['id'].',';
            $count=$count-100;
        }
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}