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

//error handler function
function custom_error($errno, $errstr) {
	echo "\n<br><b>Error:</b> [$errno] $errstr";
}

function is_dir_or_image($file)
{
	if (right($file, 2) == '..' || right($file, 1) == '.') return false;
	if(is_dir($file)) return true;
	$info = pathinfo($file);
	if (isset($info['extension'])) {
		if(in_array(strtolower($info['extension']), $GLOBALS["img_exts"])) 
			return true;
	}
	
	return false;
}

/////////////////////////// MAIN ///////////////////////////

// Set number of elements per page
define("PAGE_SIZE", 20);

//set error handler
set_error_handler("custom_error");

// Allowed file extensions. We only want pictures at this stage. Every other file type is ignored.
$img_exts = array("jpg", "jpeg", "gif", "bmp", "png");

// Initialise directory for current request.
$dir = get_param("dir", "./pics/");
$page = get_param("page", 0);

// Get the files in the directory
$files = scandir($dir);
// Concatenate the directory to the file
$filenames = array_map(function($f) { return $GLOBALS["dir"] . $f; }, $files);
// Filter out everything except directories and images
$filtered = array_filter($filenames, "is_dir_or_image");
// Get the actual page requested in the parameters
$sliced = array_slice ($filtered, $page * PAGE_SIZE, PAGE_SIZE, true);
// Generate links for directories and img tags for images
foreach($sliced as $file) {
	if (is_dir($file)) { // if current item is a directory
		echo '<a class="folder" href="?dir=' . urlencode($file) . '/">' . $file . "</a>\n"; // create a link to its contents
	} else {
		echo '<img src="' . $file . '" alt="' . $file . '" title="' . $file . "\" />\n"; // otherwise display image on page.
	}
}
// Create a button to load more elements at the bottom of the page.
if (count($sliced) == PAGE_SIZE && count($filtered) > ($page + 1) * PAGE_SIZE) {
	echo "\n<div class=\"loadmorediv\"><a class=\"loadmorelnk\" href=\"?page=" . ($page + 1) . "&dir=" . urlencode($dir) . "\">LOAD MORE</a></div>";
} else {
	echo "\n<div class=\"loadmorediv\"><span>You have reached the end of the directory.</span></div>";
}

?>
