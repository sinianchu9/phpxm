<?php
include("include/Captcha.class.php");

$config=array(
	'fontfile'=>'assets/fonts/7.ttf',
	'pixel'=>100,
	'line'=>5
	);

$captcha=new Captcha($config);
session_start();
$_SESSION['verifyCode']=$captcha->getCaptcha();

?>