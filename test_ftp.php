<?php


// Allowed file extensions. We only want pictures at this stage. Every other file type is ignored.
$img_exts = array("jpg", "jpeg", "gif", "bmp", "png");

$ftp_cfg = parse_ini_file("config.ini");
print_r($ftp_cfg);

$ftp_server	 = $ftp_cfg['ftp_server'];
$ftp_user	 = $ftp_cfg['ftp_user'];
$ftp_passwd	 = $ftp_cfg['ftp_passwd'];
$ftp_basedir = $ftp_cfg['ftp_basedir'];

function ftp_get_filelist($con, $path, &$folders, &$images) {
	$contents = ftp_rawlist($con, $path);
	$folders = array();
	$images  = array();
	
	if(count($contents)){
		foreach($contents as $line){
			if (substr($line, 0, 1) === 'd') {
				$folders[] = substr($line, 56);
			}
			elseif (substr($line, 0, 1) === '-') {
				$images[] = substr($line, 56);
			}
		}
		return true;
	}
	return false;
}

// set up basic connection
$conn_id = ftp_connect($ftp_server); 
	
// login with username and password
$login_result = ftp_login($conn_id, $ftp_user, $ftp_passwd); 

// check connection
if ((!$conn_id) || (!$login_result)) { 
	echo "FTP connection has failed!";
	echo "Attempted to connect to $ftp_server for user $ftp_user"; 
	exit; 
} else {
	echo "Connected to $ftp_server, for user $ftp_user";
}


$result = ftp_get_filelist($conn_id, $ftp_basedir, $folders, $images);
echo "folders";
print_r($folders);
echo "images";
print_r($images);




// close the FTP stream 
ftp_close($conn_id); 

	/*



// upload the file
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 

// check upload status
if (!$upload) { 
	echo "FTP upload has failed!";
} else {
	echo "Uploaded $source_file to $ftp_server as $destination_file";
}


*/


?>