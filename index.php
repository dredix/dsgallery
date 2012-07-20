<html>
<head>
	<title>Gallery</title>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" >
	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="http://current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css">
	<style type="text/css">
		<!--
			body {padding-top: 60px;padding-bottom: 40px; width:90%;margin-left:auto;margin-right:auto}
			h1 { margin-bottom: 1em; }
			div.item { display: block; float: left; margin:0 0 .5em .5em; width: 150px; height:150px; border: 1px solid #999 }
			img, a.folder{display: block; height: 100%; width: 100%; overflow: hidden }
			a.folder {background:#fff url('folder.png') no-repeat center center; text-align: center; }
			a:hover {text-decoration: none; color: black; border-color: black;}
			div.loadmorediv { clear: both; display: block; padding-top: 2em; text-align: center }
			div.link-to-top { clear: both; display: block; text-align: center }
		-->
	</style>
</head>
<body>
<a name="TOP"></a>
<h1><a href="?dir=./pics/">Gallery</a></h1>
<div id="content">
</div>
<div class="link-to-top">
<p><a href="#TOP">BACK TO TOP</a></p>
</div>

<script>
	$().ready(function() {
		$('<div/>').load('load_images.php' + window.location.search, function() {
			$(this).appendTo('#content');
		});
	});

	$(window).scroll(function () {
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 100) {
			if ($("a.loadmorelnk").length > 0) {
				var appending = "load_images.php" + $("a.loadmorelnk").attr("href") + " #items";
				$('<div/>').load(appending, function() {
					$("div.loadmorediv").remove();
					$(this).appendTo('#content');
				});
			}
		}
	});
</script>

</body>
</html>
