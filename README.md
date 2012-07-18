About dsgallery
=========

This project is a dead simple web photo gallery for displaying pictures from the file system. It has been tested on Windows, but with the right binaries there is no reason why it shouldn't work in other platforms.

It uses [Mongoose](http://code.google.com/p/mongoose/) (a lightweight, portable, cross-platform web server) and [php](http://www.php.net/) for serving a single page site that shows thumbnails of pictures in the file system.

Only pre-generated square thumbnails within the pics folder are displayed in this version. In the future I might include autogeneration of thumbnails, and perhaps some jquery. Because everyone loves jquery.

How to use
----------

1. Create a folder called pics and copy some 150x150 images inside it.
2. Run mongoose-3.1.exe. This will start the web server.
3. Point your favourite web browser to [http://localhost:8080/](http://localhost:8080/).
4. That's pretty much it.
