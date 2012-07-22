<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" >
</head><body><div id="items">
<?php

function right($string, $chars) {
    return substr($string, strlen($string) - $chars, $chars);
}

function get_param($key, $default) {
	$var = "";
	if(isset($_GET) && isset($_GET[$key])) $var = $_GET[$key];
	if (strlen($var) == 0) $var = $default;
	return $var;
}

function get_prop($properties, $key, $default) {
	$var = "";
	if(isset($properties) && isset($properties[$key])) $var = $properties[$key];
	if (strlen($var) == 0) $var = $default;
	return $var;
}

function prefix($prefix, $str) {
	if (isset($str) && strlen($str) > 0) {
		return $prefix . $str;
	}
	return "";
}

function suffix($str, $suffix) {
	if (isset($str) && strlen($str) > 0) {
		return $str . $suffix;
	}
	return "";
}

//error handler function
function custom_error($errno, $errstr) {
	echo "\n<br><b>Error:</b> [$errno] $errstr";
}

function ftp_get_files($con, $path) {
	ftp_chdir($con, $path);
	$contents = ftp_rawlist($con, ".");
	$items = array();
	
	if(count($contents)){
		foreach($contents as $line){
			if (substr($line, 0, 1) === 'd') {
				$items[] = array('folder', substr($line, 56));
			}
			elseif (substr($line, 0, 1) === '-' && in_array(strtolower(right($line, 3)), $GLOBALS["img_exts"]) ) {
				$items[] = array('file', substr($line, 56));
			}
		}
	}
	return $items;
}

/////////////////////////////////////// MAIN ///////////////////////////////////////

// Set number of elements per page
define("PAGE_SIZE", 50);

//set error handler
set_error_handler("custom_error");

// Allowed file extensions. We only want pictures at this stage. Every other file type is ignored.
$img_exts = array("jpg", "jpeg", "gif", "bmp", "png");

// Load ftp parameters
$ftp_cfg = parse_ini_file("config.ini");

$ftp_server	 = get_prop($ftp_cfg, 'ftp_server',  '127.0.0.1');
$ftp_user	 = get_prop($ftp_cfg, 'ftp_user',    'user');
$ftp_passwd	 = get_prop($ftp_cfg, 'ftp_passwd',  'password');
$ftp_basedir = get_prop($ftp_cfg, 'ftp_basedir', '/Files/Pictures');

// Base thumbnail directory
$thumb_basedir = get_prop($ftp_cfg, 'thumb_basedir', './pics');

// Initialise directory for current request.
$dir = get_param("dir", "");
$page = get_param("page", 0);

// set up basic connection
$conn_id = ftp_connect($ftp_server); 
// login with username and password
$login_result = ftp_login($conn_id, $ftp_user, $ftp_passwd); 
// Retrieve folders and images from current ftp directory
$files = ftp_get_files($conn_id, $ftp_basedir . prefix('/', $dir));
// close the FTP stream 
ftp_close($conn_id); 

// Get the actual page requested in the parameters
$sliced = array_slice ($files, $page * PAGE_SIZE, PAGE_SIZE, true);

// Generate links for directories and img tags for images
foreach($sliced as $file) {
	if ($file[0] == 'folder') { // if current item is a directory
		$fpath = suffix($dir, '/') . $file[1];
		echo '<div class="item"><a class="folder" href="?dir=' . rawurlencode($fpath) . '">' .
			$file[1] . "</a></div>\n"; // create a link to its contents
	} else {
		$thumbpath = rawurlencode($thumb_basedir . '/' .  suffix($dir, '/') . $file[1]);
		$filename = $ftp_basedir . prefix('/', $dir) . '/' . $file[1];
		$ftppath = "ftp://$ftp_user:$ftp_passwd@$ftp_server" . $filename;
		echo "<div class=\"item\"><a href=\"$ftppath\"><img src=\"$thumbpath\" alt=\"$filename\" title=\"$filename\" /></a></div>\n";
	}
}

// Create a button to load more elements at the bottom of the page.
if (count($sliced) == PAGE_SIZE && count($files) > ($page + 1) * PAGE_SIZE) {
	echo "\n<div class=\"loadmorediv\"><a class=\"loadmorelnk\" href=\"?page=" . ($page + 1) . "&dir=" . rawurlencode($dir) . "\">LOAD MORE</a></div>";
} else {
	echo "\n<div class=\"loadmorediv\"> </div>";
}

?>
</div></body></html>