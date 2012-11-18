<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" >
</head><body><div id="items">
<?php
// Get the n characters to the right of a string.
function right($string, $chars) {
	return substr($string, strlen($string) - $chars, $chars);
}

// Return a parameter from a get request if it exists, or a default value otherwise.
function get_param($key, $default) {
	$var = "";
	if(isset($_GET) && isset($_GET[$key])) $var = $_GET[$key];
	if (strlen($var) == 0) $var = $default;
	return $var;
}

// Return a property from the contents of a config file.
function get_prop($properties, $key, $default) {
	$var = "";
	if(isset($properties) && isset($properties[$key])) $var = $properties[$key];
	if (strlen($var) == 0) $var = $default;
	return $var;
}

// Insert a prefix on a nonempty string
function prefix($prefix, $str) {
	if (isset($str) && strlen($str) > 0) {
		return $prefix . $str;
	}
	return "";
}

// Append a suffix to a nonempty string
function suffix($str, $suffix) {
	if (isset($str) && strlen($str) > 0) {
		return $str . $suffix;
	}
	return "";
}

// Error handler function
function custom_error($errno, $errstr) {
	echo "\n<br><b>Error:</b> [$errno] $errstr";
}

// Encode all characters for URL, except the path separator.
function url_encode($str) {
	$ret = rawurlencode($str);
	return str_replace('%2F','/',$ret);
}

// Get the files from a given directory on an ftp server.
function get_files_from_ftp($cfg, $path, $img_exts) {

	// Load ftp parameters
	$ftp_server	 = get_prop($cfg, 'ftp_server',  '127.0.0.1');
	$ftp_user	 = get_prop($cfg, 'ftp_user',    'user');
	$ftp_passwd	 = get_prop($cfg, 'ftp_passwd',  'password');

	// Set up basic connection
	$conn_id = ftp_connect($ftp_server);
	// Login with username and password
	$login_result = ftp_login($conn_id, $ftp_user, $ftp_passwd);
	// Change to the desired folder
	ftp_chdir($conn_id, $path);
	// List contents
	$contents = ftp_rawlist($conn_id, ".");
	// Close the FTP stream
	ftp_close($conn_id);
	// Initialise arrays for organising results
	$folders = array();
	$pictures = array();
	// Identify folders and pictures, then add them to the proper arrays
	if(count($contents)) {
		foreach($contents as $line) {
			if (substr($line, 0, 1) === 'd') { // if directory
				$folders[] = array('folder', substr($line, 56));
			}
			elseif (substr($line, 0, 1) === '-' &&
				in_array(strtolower(right($line, 3)), $img_exts) ) { // if picture
				$filename = substr($line, 56);
				$fullname = $path . '/' . $filename;
				$ftppath = "ftp://$ftp_user:$ftp_passwd@$ftp_server" . url_encode($fullname);
				$pictures[] = array('file', $filename, $fullname, $ftppath);
			}
		}
	}
	return array_merge($folders, $pictures);
}

// Get the files from a given folder on the filesystem.
function get_files_from_fs($path, $img_exts) {
	// Get the files in the directory
	$files = scandir($path);
	// Initialise arrays for organising results
	$folders = array();
	$pictures = array();

	// If there are any folders or pictures, add them to the proper arrays.
	if (count($files)) {
		foreach($files as $filename) {
			if ($filename == '..' || $filename == '.') continue;
			$fullname = $path . '/' . $filename;
			if(is_dir($fullname)) { // if directory
				$folders[] = array('folder', $filename);
			} else {
				$info = pathinfo($fullname);
				if (isset($info['extension']) &&
					in_array(strtolower($info['extension']), $img_exts)) { // if picture
					$pictures[] = array('file', $filename, $fullname, $fullname);
				}
			}
		}
	}
	return array_merge($folders, $pictures);
}

//////////////////////////////////// MAIN ////////////////////////////////////

// Set number of elements per page
define("PAGE_SIZE", 50);

//set error handler
set_error_handler("custom_error");

// Allowed file extensions. We only want pictures at this stage.
// Every other file type is ignored.
$img_exts = array("jpg", "jpeg", "gif", "bmp", "png");

// Load configuration from ini file.
$cfg = parse_ini_file("config.ini");

// Read storage type. Accepted values at the moment are ftp or fs (filesystem)
$sto_type    = get_prop($cfg, 'sto_type',  'fs');
// Read base directory.
$sto_basedir = get_prop($cfg, 'sto_basedir', './pics/gallery');

// Base thumbnail directory
$thumb_basedir = get_prop($cfg, 'thumb_basedir', './pics/thumbs');

// Initialise directory and page for current request.
$dir = get_param("dir", "");
$page = get_param("page", 0);
$path = $sto_basedir . prefix('/', $dir);

// Get the files from the current directory
if ($sto_type == 'ftp') {
	$files = get_files_from_ftp($cfg, $path, $img_exts);
} else {
	$files = get_files_from_fs($path, $img_exts);
}

// Get the actual page requested in the parameters.
// If page is -1 then return the whole array (no slicing).
$sliced = $page == -1 ? $files :
	array_slice ($files, $page * PAGE_SIZE, PAGE_SIZE, true);

// Generate links for directories and img tags for images
foreach($sliced as $file) {
	if ($file[0] == 'folder') { // If current item is a directory
		$fpath = suffix($dir, '/') . $file[1];
		echo '<div class="item"><a class="folder" href="?dir=' . url_encode($fpath) . '">' .
			$file[1] . "</a></div>\n"; // create a link to its contents
	} else {
		// Otherwise it's an image so create a link to the picture
		// and load a thumbnail if there is one.
		$thumbpath = $thumb_basedir . '/' .  suffix($dir, '/') . $file[1];
		echo "<div class=\"item\"><a class=\"image\" href=\"$file[3]\" rel=\"Gallery\" title=\"$file[2]\"><img src=\"$thumbpath\" alt=\"$file[1]\" title=\"$file[2]\" /></a></div>\n";
	}
}

// Create a button to load more elements at the bottom of the page.
if ($page >= 0 && count($sliced) == PAGE_SIZE && count($files) > ($page + 1) * PAGE_SIZE) {
	echo "\n<div class=\"loadmorediv\"><a class=\"loadmorelnk\" href=\"?page=" . ($page + 1) . "&dir=" . url_encode($dir) . "\">LOAD MORE</a></div>";
} else {
	echo "\n<div class=\"loadmorediv\"> </div>";
}

?>
</div></body></html>
