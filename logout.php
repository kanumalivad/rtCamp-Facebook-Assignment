<?php 
<<<<<<< HEAD

	session_start();
	require_once('fb-config.php');
	
=======
	session_start();

>>>>>>> 08d220881b4581cf07b5796bcb7c3fc6f425c2c7
	if(isset($_SESSION['facebook_access_token']))
		unset($_SESSION['facebook_access_token']);

	if(isset($_COOKIE["credentials"]))
	{
		unset($_COOKIE['credentials']);
		setcookie("credentials", "", time() - 3600);
	}
	header('Content-Type: text/html; charset=utf-8');
	echo "<script> 

	function delete_cookie(name) {
    document.cookie=name+'=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	};

	 function getCookie(cname) {
    var name = cname + '=';
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
    return '';
  }
	delete_cookie('credentials');
<<<<<<< HEAD
	window.location='".DOMAIN."'; 
=======
	window.location='https://localhost/rtCamp/index.php'; 
>>>>>>> 08d220881b4581cf07b5796bcb7c3fc6f425c2c7
	</script>";


?>