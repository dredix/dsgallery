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
	<meta name="viewport" content="user-scalable=no, width=device-width" />
	<link rel="stylesheet" type="text/css" href="http://current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css">
    <style type="text/css">
	<!--
		body {padding-top: 60px;padding-bottom: 40px; width:80%;margin-left:auto;margin-right:auto}
		img, a.folder{display: block; float: left; overflow: hidden; margin:0 0 .5em .5em; width: 150px; height:150px; border: 1px solid #999}
		a.folder {background:#fff url('folder.png') no-repeat center center; text-align: center;}
		a:hover {text-decoration: none; color: black; border-color: black;}
	-->
    </style>	

</head>
<body>
<h1><a href="?dir=./pics/">Gallery</a></h1>
<?php
if (is_dir($dir)) { // If we are in a directory
	if ($dir != './pics/') { // and it's not the root directory
		echo '<p><a href="?dir=' . dirname($dir) . "/\">Back</a></p>\n"; // add a back link.
	}
	// TODO: Add breadcrumbs.
	
	if ($dh = opendir($dir)) { // iterate through the files in the current directory.
		while (($file = readdir($dh)) !== false) {
			if ($file == '.' || $file == '..') continue;
			$filename = $dir . $file;
			if (is_dir($filename)) { // if current item is a directory
				echo '<a class="folder" href="?dir=' . urlencode($filename) . '/">' . $file . "</a>\n"; // create a link to display its contents.
			} elseif (in_array(strtolower(pathinfo($filename)['extension']), $img_exts)) { // if it's an image
				echo '<img src="' . $filename . '" alt="' . $filename . '" title="' . $filename . "\" />\n"; // display it on the page.
			}
		}
		closedir($dh);
	}
}
?>
</body>
</html>
