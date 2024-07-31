<?php
session_start( );

$_SESSION['username'] = "";
$_SESSION['id'] = "";
echo "<script language='javascript'>window.location.href='/index.html';</script>";

?>