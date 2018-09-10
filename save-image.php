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
/* PHP SDK v5.0.0 */
/* make the API call */
try
{
  $userInfo= $fb->get('/me?fields=id,name',$accessToken);
          $userDetails = $userInfo->getGraphUser();

          $uname=str_replace(" ","_",$userDetails['name'].'_'.$userDetails['id']);

     $imageId=explode("_",$_GET['imageid']);
    $response = $fb->get(
        '/'.$imageId[1].'?fields=images',
        $accessToken
      );
    $albumDir='tmp/'.$uname.'/'.str_replace(" ","_",$imageId[0]);
    echo $albumDir;
    $graphNode = $response->getGraphNode();
    $a0 = $graphNode['images'][0];

    if(!file_exists($albumDir))
      mkdir($albumDir,0777);

      $content = file_get_contents($a0['source']);
      file_put_contents($albumDir.'/'.$imageId[1].'.jpg', $content);
     
}catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

 function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
  }


?>