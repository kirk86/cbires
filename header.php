<?php
require_once('config/config.php');
require_once(CB_DB_DIR . 'DB.php');
require_once(CB_CORE_DIR . 'ColorSpaceConversion.php');
require_once(CB_CORE_DIR . 'Histogram.php');
require_once(CB_CORE_DIR . 'DistanceMetrics.php');
require_once(CB_CORE_DIR . 'Image.php');
require_once(CB_CORE_DIR . 'simple_html_dom.php');
require_once(CB_INCLUDES_DIR . 'Tools.php');
require_once(CB_INCLUDES_DIR . 'Validate.php');
require_once(CB_INCLUDES_DIR . 'PopulateImages.php');
require_once(CB_INCLUDES_DIR . 'ErrorHandler.php');
require_once(CB_INCLUDES_DIR . 'Crawler.php');
ErrorHandler::setHandler();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!--
		CBIRES v1.0.0

		Copyright 2012 CBIRES
		Licensed under the Apache License v2.0
		http://www.apache.org/licenses/LICENSE-2.0
	-->
	<meta charset="utf-8" />
	<title>CBIRES</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="CBIRES, a featured, responsive, Contenet Based Image Retrieval System." />
	<meta name="author" content="CBIRES" />

	<!-- The styles -->
	<link id="bs-css" href="css/bootstrap-cerulean.css" rel="stylesheet" />
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="css/cbires-app.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
	<link href='css/fullcalendar.css' rel='stylesheet' />
	<link href='css/fullcalendar.print.css' rel='stylesheet'  media='print' />
	<link href='css/chosen.css' rel='stylesheet' />
	<link href='css/uniform.default.css' rel='stylesheet' />
	<link href='css/colorbox.css' rel='stylesheet' />
	<link href='css/jquery.cleditor.css' rel='stylesheet' />
	<link href='css/jquery.noty.css' rel='stylesheet' />
	<link href='css/noty_theme_default.css' rel='stylesheet' />
	<link href='css/elfinder.min.css' rel='stylesheet' />
	<link href='css/elfinder.theme.css' rel='stylesheet' />
	<link href='css/jquery.iphone.toggle.css' rel='stylesheet' />
	<link href='css/opa-icons.css' rel='stylesheet' />
	<link href='css/uploadify.css' rel='stylesheet' />

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="img/favicon.ico" />
		
</head>

<body>
	<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="index.php"> <img alt="CBIRES Logo" src="img/logo20.png" /> <span>CBIRES</span></a>
				
				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> admin</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<!--<li><a href="profile.php">Profile</a></li>
						<li class="divider"></li>-->
						<li><a href="login.php">Logout</a></li>
					</ul>
				</div>
				<!-- user dropdown ends -->
                
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<?php } ?>
	<div class="container-fluid">
		<div class="row-fluid">
		<?php if(!isset($no_visible_elements) || !$no_visible_elements) { ?>
		
			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Main</li>
						<li><a class="ajax-link" href="index.php"><i class="icon-home"></i><span class="hidden-tablet"> Dashboard </span></a></li>
                        <li><a class="ajax-link" href="user.php"><i class="icon-user"></i><span class="hidden-tablet"> Members </span></a></li>
						<li><a class="ajax-link" href="gallery.php"><i class="icon-picture"></i><span class="hidden-tablet"> Gallery</span></a></li>
                        <li><a class="ajax-link" href="upload.php"><i class="icon-folder-close"></i><span class="hidden-tablet"> File Uploader</span></a></li>
                        <li><a class="ajax-link" href="crawler.php"><i class="icon-globe"></i><span class="hidden-tablet"> Web Crawler</span></a></li>
					</ul>
					<label id="for-is-ajax" class="hidden-tablet" for="is-ajax"><input id="is-ajax" type="checkbox"> Ajax on menu</label>
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			<?php } ?>
