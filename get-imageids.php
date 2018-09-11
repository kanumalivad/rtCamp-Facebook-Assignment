
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
          $userInfo= $fb->get('/me?fields=id,name',$accessToken);
          $userDetails = $userInfo->getGraphUser();

          $uname=str_replace(" ","_",$userDetails['name'].'_'.$userDetails['id']);

          if(!file_exists('tmp/'.$uname))
            mkdir('tmp/'.$uname,0777);

          $albumIds=explode("_",$_GET['albumid']);
          for($i=0;$i<count($albumIds)-1;$i+=2)
          {
            if($albumIds[$i]!=""){
                $album_img2= $fb->get('/'.$albumIds[$i].'/photos?limit=500',$accessToken);
                $user2 = $album_img2->getGraphEdge();

                $album_img= $fb->get( '/'.$albumIds[$i].'?fields=photo_count',$accessToken);
                $user1 = $album_img->getGraphNode();
                $count=$user1['photo_count'];

                    for($a=0;$a<count($user2);$a++)
                      echo $albumIds[$i+1]."_".$user2[$a]['id'].',';
                  $count=$count-100;
                  while($count>0)
                   {
                      $nextFeed = $fb->next($user2);
                      for($j=0;$j<count($nextFeed);$j++)
                        echo $albumIds[$i+1]."_".$nextFeed[$j]['id'].',';
                      $count=$count-100;
                  }
          
            }
          }
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}