<?php if ($_GET['pageTitle']) { $pageTitle = $_GET['pageTitle']; } ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="de" />
	<title><?php echo APP_TITLE; ?> | <?php echo $pageTitle; ?></title>
	<link rel="stylesheet" type="text/css" href="assets/default.css">
	<link rel="stylesheet" type="text/css" href="assets/timeline.css">
	<script src="vis.js/<?php echo VIS_JS; ?>/vis.min.js"></script>
	<link href="vis.js/<?php echo VIS_JS; ?>/vis.min.css" rel="stylesheet" type="text/css" />
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>