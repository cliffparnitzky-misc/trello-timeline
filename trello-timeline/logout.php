<?php

session_start();
session_destroy();
session_unset();

if($_SESSION['login'] && isset($_POST['FORM_SUBMIT']) && $_POST['FORM_SUBMIT'] == "logout_form"){
	header ("Location: index.php");
}
?>