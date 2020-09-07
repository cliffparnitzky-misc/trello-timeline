<?php require_once("includes/login.inc"); ?>
<?php if ($_SESSION['login']): ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="de" />
	<title><?php echo APP_TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="assets/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body>
	<h1><?php echo APP_TITLE; ?></h1>
	<p style="text-align: center; font-size: 12px;">
		Welcome to the Trello timelime tool.
	</p>
	<p style="text-align: center; font-size: 12px;">
		Please choose one of the function below.
	</p>
	<div id="logout-form">
		<form name="input" action="logout.php" method="post">
			<input type="hidden" name="FORM_SUBMIT" value="logout_form">
			<input type="submit" value="Logout" name="logout" />
		</form>
	</div>
<?php include "includes/footer.inc"; ?>
</body>
</html>
<?php endif; ?>