<?php
session_start();
$lang=$_POST['lang'];

$_SESSION['lang']=$lang;

echo 1;


?>