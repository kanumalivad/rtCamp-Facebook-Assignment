<?php

session_start();
require_once('fb-config.php');
 $accessToken = $_SESSION['facebook_access_token'];
  if (isset($accessToken)) {
      $_SESSION['facebook_access_token'] = (string) $accessToken;
  }
  $fb =
   new Facebook\Facebook([
    'app_id' => APP_ID, // Replace {app-id} with your app id
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.2',
    'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token']  : APP_SECRET
    ]);

ini_set('max_execution_time', 600);
ini_set('memory_limit','1024M');

  $userInfo= $fb->get('/me?fields=id,name',$accessToken);
  $userDetails = $userInfo->getGraphUser();

  $uname=str_replace(" ","_",str_replace(" ","_",$userDetails['name']).'_'.$userDetails['id']);
function zip_r($from, $zip, $base=false) {
    if (!$base) {$base = $from;}
    $base = trim($base, '/');
    $zip->addEmptyDir($base);
    $dir = opendir($from);
    while (false !== ($file = readdir($dir))) {
        if ($file == '.' OR $file == '..') {continue;}

        if (is_dir($from . '/' . $file)) {
            zip_r($from . '/' . $file, $zip, $base . '/' . $file);
        } else {
            $zip->addFile($from . '/' . $file, $base . '/' . $file);
        }
    }
    return $zip;
}
$from = "tmp/".$uname;
$base = $uname;
$zip = new ZipArchive();
$zip->open('tmp/'.$uname.'.zip', ZIPARCHIVE::CREATE);
$zip = zip_r($from, $zip, $base);
$zip->close();

echo $uname.'.zip';
?>