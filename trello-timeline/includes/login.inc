<?php

require_once("config/config.inc");

session_start();

$username = '';
$password = '';
$error = '';

if(!$_SESSION['login'] && isset($_POST['FORM_SUBMIT']) && $_POST['FORM_SUBMIT'] == "login_form"){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($username === USERNAME && md5($password) === PASSWORD)
	{
		$_SESSION['login'] = true;
		$url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
		header("Location: $url");
		die();
	}
	else
	{
		$error = 'Invalid username or password.';
	}
}
?>

<?php if (!$_SESSION['login']): ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="de" />
	<title><?php echo APP_TITLE; ?> | Login</title>
	<link rel="stylesheet" type="text/css" href="assets/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body>
	<h1><?php echo APP_TITLE; ?> | Login</h1>
	<div id="login-form">
		<form name="input" action="<?php echo $_SERVER["PHP_SELF"]; ?><?php if (strlen(http_build_query($_GET)) > 0) : ?>?<?php echo http_build_query($_GET); ?><?php endif; ?>" method="post">
			<input type="hidden" name="FORM_SUBMIT" value="login_form">
		<?php if ($error): ?>
			<div class="error"><?php echo $error; ?></div>
		<?php endif; ?>
			<label for="username">Username</label><input type="text" value="" id="username" name="username" required />
			<label for="password">Password</label><input type="password" value="" id="password" name="password" required />
			<input type="submit" value="Login" name="login" />
		</form>
	</div>
</body>
</html>
<?php endif; ?>