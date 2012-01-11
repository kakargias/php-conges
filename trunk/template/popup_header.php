<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $title; ?></title>
		<link href="<?php echo TEMPLATE_PATH .$_SESSION['config']['stylesheet_file']; ?>" rel="stylesheet" type="text/css">
		<link type="text/css" href="<?php echo TEMPLATE_PATH;?>jquery/css/custom-theme/jquery-ui-1.8.17.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="<?php echo TEMPLATE_PATH;?>jquery/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="<?php echo TEMPLATE_PATH;?>jquery/js/jquery-ui-1.8.17.custom.min.js"></script>
		<?php include ROOT_PATH .'fonctions_javascript.php' ;?>
		<?php echo $additional_head; ?>
	</head>
	<body>
		<center>