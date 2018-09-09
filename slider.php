
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style type="text/css">
    #myCarousel{
      height: 100%;
      width: 100%;
    }
    .carousel-inner img{
      margin:auto;
    }
  </style>
<body onClick="doFS();">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner" id="img-container">
      <div class="item active">
        <img src="">
      </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <input type="hidden" id="aid" value="<?php echo  $_GET['albumid']; ?>">
</body>
</html>

<script type="text/javascript">
  loadImages(document.getElementById("aid").value);
  function loadImages(id)
  {
    albumid=id;
    var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var arr=this.responseText.split(',');
          for(var i=0;i<arr.length-1;i++)
          {
             var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            
           
                $("#img-container").append("<div class='item'><img src='"+this.responseText+"' style='height: 100vh;  position: absolute;  z-index: -1; width: 100%;  filter: blur(10px);'><img src='"+this.responseText+"' style='height: 100vh;'></div>");
              }
            };
            xhttp.open("GET", "http://localhost/rtCamp/load-album.php?imageid="+arr[i], true);
            xhttp.send();
          }
        }
      };
      xhttp.open("GET", "http://localhost/rtCamp/get-Images.php?albumid="+id, true);
      xhttp.send(); 
  }

  function doFS(){
    var elem = document.getElementById("myCarousel");
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


</script>


