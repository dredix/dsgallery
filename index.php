<html>
<head>
	<title>Dead Simple Gallery</title>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" >
	<link rel="stylesheet" type="text/css" href="http://current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css">
	<link rel="stylesheet" href="fancybox/jquery.fancybox.css?v=2.0.6.33" type="text/css" media="screen" />
	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox.pack.js?v=2.0.6.33"></script>

	<link rel="stylesheet" href="fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.2" type="text/css" media="screen" />
	<script type="text/javascript" src="fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<style type="text/css">
		<!--
			body {padding-top: 60px;padding-bottom: 40px; width:90%;margin-left:auto;margin-right:auto}
			h1 { margin-bottom: 1em; }
			div.item { display: block; float: left; margin:0 0 .5em .5em; width: 150px; height:150px; border: 1px solid #999 }
			div.item img {display: block; max-height: 100%; max-width: 100%; overflow: hidden }
			div.item a {display: block; height: 100%; width: 100%; overflow: hidden }
			a.folder {background:#fff url('folder.png') no-repeat center center; text-align: center; }
			a:hover {text-decoration: none; color: black; border-color: black;}
			div.loadmorediv { clear: both; display: block; padding-top: 2em; text-align: center; font-size: 130% }
			div.link-to-top { clear: both; display: block; text-align: center }
			div.link-to-top, div.breadcrumbs { font-size: 150%; margin-bottom: 1em; }
			div#content {text-align: center }
		-->
	</style>
</head>
<body>
<a name="TOP"></a>
<h1><a href="?dir=">Dead Simple Gallery</a></h1>
<div class="breadcrumbs">
<?php
	echo '<p><a href="?dir=">Home</a>';
	if (isset($_GET) && isset($_GET['dir']) && strlen($_GET['dir']) > 0) {
		$crumbs = explode("/", $_GET['dir']);
		$path = "";
		$count = count($crumbs);
		if ($count > 0) {
			for($i = 0; $i < $count - 1; $i++) {
				if ($path == "") $path = $crumbs[$i]; else $path .= '/' . $crumbs[$i];
				echo '<span class="sepr">/</span><a href="?dir=' . rawurlencode($path) . '">' . $crumbs[$i] . '</a>';
			}
			echo '<span class="sepr">/</span>' . $crumbs[$count - 1] . "\n";
		}
		echo "</p>\n<p><a href=\"?page=-1&dir=" . $_GET['dir'] . '">LOAD THEM ALL</a>';
	}
	echo "</p>\n";
?>
</div>
<div id="content">
	<img id="loading" src="ajax-loader.gif" alt="Loading"/>
</div>
<div class="link-to-top">
<p><a href="#TOP">BACK TO TOP</a></p>
</div>

<script>
	var appendingContent = 0;
	$().ready(function() {
		$('<div/>').load('load_pictures.php' + window.location.search, function() {
			$(this).appendTo('#content');
			$('img#loading').remove();
		});
		$(".image").fancybox({
			openEffect	: 'none',
			closeEffect	: 'none',
			prevEffect	: 'none',
			nextEffect	: 'none',
			closeBtn	: false,
			helpers		: {
				title	: { type : 'inside' },
				buttons	: {}
			}
		});
		
	});

	$(window).scroll(function () {
		if (appendingContent == 0 && $(window).scrollTop() >= $(document).height() - $(window).height() - 80) {
			if ($("a.loadmorelnk").length > 0) {
				appendingContent = 1;
				var appending = "load_pictures.php" + $("a.loadmorelnk").attr("href") + " #items";
				$('<div/>').load(appending, function() {
					$("div.loadmorediv").remove();
					$(this).appendTo('#content');
					appendingContent = 0;
				});
			}
		}
	});
</script>

</body>
</html>
