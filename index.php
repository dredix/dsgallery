<?php

// Initialise directory for current request.
$dir = "";
if(isset($_GET) && isset($_GET['dir'])) $dir = $_GET['dir'];
if (strlen($dir) == 0) $dir = "./pics/";

// Allowed file extensions. We only want pictures at this stage. Every other file type is ignored.
$img_exts = array("jpg", "jpeg", "gif", "bmp", "png");

//error handler function
function customError($errno, $errstr) {
	echo "<b>Error:</b> [$errno] $errstr";
}

//set error handler
set_error_handler("customError");

?>
<html>
<head>
	<title>Gallery</title>
	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="http://current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css">
    <style type="text/css">
	<!--
		body {padding-top: 60px;padding-bottom: 40px; width:90%;margin-left:auto;margin-right:auto}
		h1 { margin-bottom: 1em; }
		img, a.folder{display: block; float: left; overflow: hidden; margin:0 0 .5em .5em; width: 150px; height:150px; border: 1px solid #999}
		a.folder {background:#fff url('folder.png') no-repeat center center; text-align: center;}
		a:hover {text-decoration: none; color: black; border-color: black;}
		div.link-to-top { clear: both; display: block; padding-top: 2em; text-align: center }
	-->
    </style>	
<script>
$().ready(function() {
	$('<div/>').load('load_images ',function(){ 
	    $(this).appendTo('#content');    // once they're loaded, append them to our content area
	});
}

</script>
</head>
<body>
<a name="TOP"></a>
<h1><a href="?dir=./pics/">Gallery</a></h1>
<div id="content">
</div>
<div class="link-to-top">
<p><a href="#TOP">BACK TO TOP</a></p>
</div>
</body>
</html>
