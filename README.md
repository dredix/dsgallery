About dsgallery
=========

This project is a dead simple web photo gallery for displaying pictures from the file system or an FTP server. It has been tested on Windows, but with the right binaries there is no reason why it shouldn't work in other platforms.

It uses [Mongoose](http://code.google.com/p/mongoose/) (a lightweight, portable, cross-platform web server) and [php](http://www.php.net/) for serving a navigable list of folder and images, with pre-generated thumbnails and [fancyBox](http://fancyapps.com/fancybox/) for displaying the actual pictures.

The thumbnails are generated with the help of a horribly written C# program called Thumbinate.exe ([source code available here](https://gist.github.com/3162433)). This console app takes two directories as parameters (source and target folders) and generates 150x150 thumbnails for all the JPG pictures found, preserving the directory tree. At this stage only a Windows binary is provided and the program has to be called manually (or scheduled appropriately).

Configuration
-------------

A config.ini file should be created in the root folder for configuring the gallery. The available options are enumerated below.

<table border="1">
<tr><td>Option</td><td>Description</td><td>Default value</td></tr>
<tr><td><code>sto_type</code></td><td>Source type. Accepted values at the moment are <code>ftp</code> (for an ftp server) or <code>fs</code> (for a folder in the local filesystem).</td><td><code>fs</code></td></tr>
<tr><td><code>sto_basedir</code></td><td>The root folder of the pictures to be displayed. All the original pictures are within this folder and its subdirectories.</td><td><code>./pics/gallery</code></td></tr>
<tr><td><code>thumb_basedir</code></td><td>Thumbnail folder. The directory tree must be identical to the one on sto_basedir, and all the thumbnails must have the same name as the original picture.</td><td><code>./pics/thumbs</code></td></tr>
<tr><td><code>ftp_server</code></td><td>IP address or name of the FTP server (only used if sto_type is ftp).</td><td><code>127.0.0.1</code></td></tr>
<tr><td><code>ftp_user</code></td><td>User profile for accessing the FTP server.</td><td><code>user</code></td></tr>
<tr><td><code>ftp_passwd</code></td><td>Password for accessing the FTP server.</td><td><code>password</code></td></tr>
</table>

The following is an example of a config.ini file:

	[global]
	sto_type = "ftp"
	sto_basedir = "/Files/Pictures"
	thumb_basedir = "./thumbs"
	
	[ftp]
	ftp_server = "127.0.0.1"
	ftp_user = "user"
	ftp_passwd = "password"

How to use
----------

1. Generate 150x150 thumbnails by running Thumbinate.exe (or by any other means).
1. Create a text file called config.ini with the desired options in the root folder of the project. Make sure that sto\_basedir and thumb\_basedir point to the right directories.
1. Run mongoose-3.1.exe. This will start the web server.
1. Navigate to [http://localhost:8080/](http://localhost:8080/).
1. Profit.
