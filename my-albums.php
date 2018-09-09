<?php
  session_start();
  require_once('fb-config.php');

  if(!isset($_SESSION['facebook_access_token']))
        header('location:'.DOMAIN);

  $fb = new Facebook\Facebook([
    'app_id' => APP_ID, // Replace {app-id} with your app id
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.2',
    'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token']  : APP_SECRET
    ]);
  try
  { 
    $accessToken= $_SESSION['facebook_access_token'];
    $response= $fb->get('/me?fields=albums',$accessToken);
    $user = $response->getGraphUser();

    if(isset($_GET['albumid']))
    {
      $downloadLinks="";
      $albumIds=explode("_",$_GET['albumid']);

        if($albumIds[0]!=""){
          $album_img2= $fb->get('/'.$albumIds[0].'/photos?limit=500',$accessToken);
          $user2 = $album_img2->getGraphEdge();

          $zip = new ZipArchive;
          
          if ($zip->open('tmp/'.str_replace(" ","_",$albumIds[1]).'.zip', ZIPARCHIVE::CREATE) != TRUE) {
              die ("Could not open archive");
          }

          for($j=0;$j<count($user2);$j++){
            $album_img3= $fb->get('/'.$user2[$j]['id'].'?fields=images',$accessToken);
            $user3 = $album_img3->getGraphNode();
            $im=$user3['images'][0];
            $zip->addFromString($j.'.jpg', file_get_contents($im['source']));
          }
          $zip->close();
          $downloadLinks=$downloadLinks.'_tmp/'.$albumIds[1].'.zip';
          $downloadLinks=str_replace(" ","_",$downloadLinks);
          
          echo '<iframe src="download.php?link='.basename($downloadLinks).'" id="ifame" style="display : none"></iframe>';
          }
    }
    else if(isset($_GET['albumids']))
    {
      $downloadLinks="tmp/MyGallery.zip";
      $albumIds=explode("_",$_GET['albumids']);

       $zip = new ZipArchive;
          
      if ($zip->open('tmp/MyGallery.zip', ZIPARCHIVE::CREATE) != TRUE) {
          die ("Could not open archive");
      }

      for($i=0;$i<count($albumIds)-1;$i+=2)
      {
        $zip->addEmptyDir($albumIds[$i+1]);

        if($albumIds[0]!=""){
          $album_img2= $fb->get('/'.$albumIds[$i].'/photos?limit=500',$accessToken);
          $user2 = $album_img2->getGraphEdge();

          for($j=0;$j<count($user2);$j++){
            $album_img3= $fb->get('/'.$user2[$j]['id'].'?fields=images',$accessToken);
            $user3 = $album_img3->getGraphNode();
            $im=$user3['images'][0];
            $zip->addFromString($albumIds[$i+1].'/'.$j.'.jpg', file_get_contents($im['source']));
          }
          }
        }
         $zip->close();
        echo '<iframe src="download.php?link='.basename($downloadLinks).'" id="ifame" style="display : none"></iframe>';
      }
}
  catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo $e->getMessage();
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
  }
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Bootstrap Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/Mycss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="lib/js/bootstrap.min.js"></script>

  </head>
  <style type="text/css">
  body {
      background-color:#81D4FA;
  }
    .items {
    margin: 2%;
    overflow: hidden;
  }
  .items img {
    max-width: 100%;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
  }
  .items:hover img {
    -moz-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
  #btngroup {
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 99;
    font-size: 18px;
    border: none;
    outline: none;
  }
  #myCarousel{
    height: 100%;
    width:100%;
  }
  .carousel-inner img {
    margin: auto;
} 
  .card-img-top{
    height: 350px;
  }
   .modal-full {
    min-width: 100%;
    margin: 0;
}

.modal-full .modal-content {
    min-height: 100vh;
}
  </style>
  <body>
    <div class="text-center">
      <h1 class="jumbotron-heading">My Albums</h1>
    </div>
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
            <?php

              for($i=0;$i<count($user['albums']);$i++){ 
                $cp = $fb->get('/'.$user['albums'][$i]['id'].'?fields=cover_photo',$accessToken);
                $gn=$cp->getGraphNode();

                if(isset($gn['cover_photo']['id'])){
                  $ree = $fb->get(
                    '/'. $gn['cover_photo']['id'].'?fields=images',
                    $accessToken
                  );
                  $graphNode = $ree->getGraphNode();
                  $coverPhoto = $graphNode['images'][0];
                  
                  ?>
                  <div class="col-md-4">
                    <div class="card mb-4 box-shadow items" >
                      <img class="card-img-top" src="<?php echo $coverPhoto['source'] ?>" alt="Card image cap" onclick="displaySlider('<?php echo $user['albums'][$i]['id']  ?>')">
                      <div class="card-body">
                        <p class="card-text" style="font-size: 18px;">
                        <input type="checkbox" name="chk" value="<?php echo $user['albums'][$i]['id'].'_'.$user['albums'][$i]['name']  ?>">&nbsp;
                        <?php echo $user['albums'][$i]['name'] ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadAlbum('<?php echo $user['albums'][$i]['id'].'_'.$user['albums'][$i]['name']  ?>')"></span>Download</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="moveAlbum('<?php echo $user['albums'][$i]['id'].'_'.$user['albums'][$i]['name']  ?>')">Move to Drive</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php  
                }
              }
            ?>
          </div>
        </div>
      </div>
      <div id="btngroup">
        <button type="button" class="btn btn-primary" id="download_seleted" onclick="downloadSelectedAlbums()">Download Selected</button>
        <button  class="btn btn-primary" onclick="downloadAllAlbums()">Download All</button>
        <button  class="btn btn-primary" onclick="moveSelectedAlbums()">Move Selected</button>
        <button  class="btn btn-primary" onclick="moveAllAlbums()">Move All</button>
        <button  class="btn btn-danger" onclick="logout()">Logout</button>
      </div>

      <center style="margin:auto;" class="h-100 row align-items-center">
        <div class="modal fade " id="myModal"> 
          <div class="modal-dialog" style=" padding-top: 200px">
            <div class="modal-content" style="width: 230px;">
            <i class="fa fa-spinner fa-spin align-items-center" style="font-size:100px;"></i>
            <h2 id="msg"></h2>
          </div>
          </div>
        </div>
      </center>

  <div class="modal" id="mySlider">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div id="demo" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" id="img-container">
  
            </div>
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
              <span class="carousel-control-next-icon"></span>
            </a>
          </div>
        </div>
    </div>
</div>

<form action="" id="myFormSignle" method="get">
  <input type="hidden" name="albumid" id="newid">
</form>

<form action="" id="myFormMultiple" method="get">
  <input type="hidden" name="albumids" id="newids">
</form>

</body>
</html>
<script type="text/javascript">
  //disableAllButton();
  function logout(){
    window.location="logout.php";
  }
  function onoff(){
    var selected_chk=document.querySelectorAll('input[name=chk]:checked');
    if(selected_chk.length>0)
      enableAllButton();
    else
      disableAllButton();
  }
  function enableAllButton(){
    document.getElementById("download_seleted").disabled = false; 
    document.getElementById("move_selected").disabled = false; 
  }
  function disableAllButton(){
    document.getElementById("download_seleted").disabled = true; 
    document.getElementById("move_selected").disabled = true; 
  }
  function downloadAlbum(id){
    $('#myModal').modal('toggle');
    var idField=document.getElementById("newid");
    idField.value=id;
     document.getElementById("myFormSignle").submit();
     $('#myModal').modal('toggle');
  }
  function downloadSelectedAlbums(){
       $('#myModal').modal('toggle');
      var selected_chk=document.querySelectorAll('input[name=chk]:checked');
      var selctedAlbums="";
      var idField=document.getElementById("newids");
      for(var i=0;i<selected_chk.length;i++)
      {
           selctedAlbums=selctedAlbums+selected_chk[i].value+"_";
      }

         idField.value=selctedAlbums;
         document.getElementById("myFormMultiple").submit();
         $('#myModal').modal('toggle');
   
  }
  function downloadAllAlbums(){
       $('#myModal').modal('toggle');
    var selected_chk=document.querySelectorAll('input[name=chk]');
    var selctedAlbums="";
     var idField=document.getElementById("newids");
    for(var i=0;i<selected_chk.length;i++)
    {
         selctedAlbums=selctedAlbums+selected_chk[i].value+"_";
    }

         idField.value=selctedAlbums;
         document.getElementById("myFormMultiple").submit();
         $('#myModal').modal('toggle');
   
  }
    function displaySlider(id){
    
     document.getElementById("img-container").innerHTML = '';

     $("#img-container").append("<div class='carousel-item active'><img src='images/wc.jpg' style='height : 100vh; width:100% '></div>");
    loadImages(id);
   // doFS();
    $('#mySlider').modal('toggle');
  }

  function loadImages(id)
  {
    albumid=id;
    var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
          var arr=this.responseText.split(',');
          for(var i=0;i<arr.length-1;i++)
          {
             var xhttp = new XMLHttpRequest();
             xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) 
              {     
                 $("#img-container").append(" <div class='carousel-item'> <img src='"+this.responseText+"' style='height : 100vh; position: absolute; z-index:-1; width:100%; filter: blur(10px);'><img src='"+this.responseText+"' class='mx-auto d-block' style='height:100vh;'> </div>");
               }
            };
            xhttp.open("GET", "load-album.php?imageid="+arr[i], true);
            xhttp.send();
          }
        }
      };
      xhttp.open("GET", "get-Images.php?albumid="+id, true);
      xhttp.send(); 
  }

  function moveAlbum(id){
    var c=getCookie('credentials');
    if(c!="")
    {
      $("#msg").html("Uploading....");
      $('#myModal').modal('toggle');
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if(this.responseText="sucess"){
              $('#myModal').modal('toggle');
              alert("Sucesss...");
            }
        }
        else if(this.readyState == 4 && this.status != 200)
        {
          alert(this.responseText);
        }
      };
      xhttp.open("GET", "save-to-drive.php?album="+id, true);
      xhttp.send();
    }
    else
      
    {
      window.location="save-credentials.php";
    }
  }
  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');

    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
  }
  function moveSelectedAlbums(){
    var selected_chk=document.querySelectorAll('input[name=chk]:checked');
    for(var i=0;i<selected_chk.length;i++){
      moveAlbum(selected_chk[i].value);
    }
  }
  function moveAllAlbums(){
    var selected_chk=document.querySelectorAll('input[name=chk]');
    for(var i=0;i<selected_chk.length;i++){
      moveAlbum(selected_chk[i].value);
    }
  }

  function doFS(){
    var elem = document.getElementById("mySlider");
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
 } 

if (document.addEventListener)
  {
    document.addEventListener('webkitfullscreenchange', exitHandler, false);
    document.addEventListener('mozfullscreenchange', exitHandler, false);
    document.addEventListener('fullscreenchange', exitHandler, false);
    document.addEventListener('MSFullscreenChange', exitHandler, false);
  }
  var a=0;
  function exitHandler()
  {
    if(document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null){
       if(a==0)
          a=1;
      else{
        $('#mySlider').modal('toggle');
        a=0;
      }
    }
  }

</script>
