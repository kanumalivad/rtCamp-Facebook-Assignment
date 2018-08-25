<?php
  session_start();
  require_once('fb-config.php');

  if(!isset($_SESSION['facebook_access_token']))
        header('location:http://localhost/RtCamp/index.php');

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
                      <img class="card-img-top" src="<?php echo $coverPhoto['source'] ?>" alt="Card image cap" onclick="openSlider('<?php echo $user['albums'][$i]['id']  ?>')">
                      <div class="card-body">
                        <p class="card-text" style="font-size: 18px;">
                        <input type="checkbox" name="chk" value="<?php echo $user['albums'][$i]['id'] ?>">&nbsp;
                        <?php echo $user['albums'][$i]['name'] ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadAlbum('<?php echo $user['albums'][$i]['id']  ?>')"></span>Download</button>
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
        <button type="button" class="btn btn-primary" onclick="downloadSelectedAlbums()">Download Selected</button>
        <button type="button" class="btn btn-primary" onclick="downloadAllAlbums()">Download All</button>
        <button type="button" class="btn btn-primary" onclick="moveSelectedAlbums()">Move Selected</button>
        <button type="button" class="btn btn-primary" onclick="moveAllAlbums()">Move All</button>
        <button type="button" class="btn btn-danger" onclick="logout()">Logout</button>
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


</body>
</html>
<script type="text/javascript">
  //disableAllButton();
  function logout(){
    window.location="https://localhost/rtCamp/logout.php";
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
    alert(id);
    $("#msg").html("Downloading....");
    $('#myModal').modal('toggle');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $('#myModal').modal('toggle');
        var links=this.responseText.split("_");
        for(var i=0;i<links.length;i++){
          if(links[i]!=""){
            window.open(links[i],"_blank");
          }
        }
      }
    };
    xhttp.open("GET", "download.php?albumid="+id, true);
    xhttp.send();
  }
  function downloadSelectedAlbums(){
    var selected_chk=document.querySelectorAll('input[name=chk]:checked');
    $("#msg").html("Downloading....");
    $('#myModal').modal('toggle');
    var selctedAlbums="";
    for(var i=0;i<selected_chk.length;i++){
      selctedAlbums=selctedAlbums+selected_chk[i].value+"_";
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var links=this.responseText.split("_");
        for(var i=0;i<links.length;i++){
          if(links[i]!=""){
            window.open(links[i],"_blank");
          }
        }
        $('#myModal').modal('toggle');
      }
    };
    xhttp.open("GET", "download.php?albumid="+selctedAlbums, true);
    xhttp.send();
  }
  function downloadAllAlbums(){
    var selected_chk=document.querySelectorAll('input[name=chk]');
    $("#msg").html("Downloading....");
    $('#myModal').modal('toggle');
    var selctedAlbums="";
    for(var i=0;i<selected_chk.length;i++)
    {
      selctedAlbums=selctedAlbums+selected_chk[i].value+"_";
    }
    $('#myModal').modal('toggle');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $('#myModal').modal('toggle');
        var links=this.responseText.split("_");
        for(var i=0;i<links.length;i++){
          if(links[i]!=""){
            window.open(links[i],"_blank");
          }
        }
      }
    };
    xhttp.open("GET", "download.php?albumid="+selctedAlbums, true);
    xhttp.send();
  }
  function openSlider(id){
    window.location="http://localhost/rtCamp/slider.php?albumid="+id;
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
      window.location="http://localhost/rtCamp/save-credentials.php";
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
</script>
