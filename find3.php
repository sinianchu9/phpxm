<?php
include("header.php");



if($_POST['vcode']){
	$vcode=$_POST['vcode'];
	$password=$_POST['newpass'];
	//$verifyCode=$_POST['verifyCode'];



	if ($vcode == "" || $vcode != $_SESSION ['passcode']) {
		echo "<script>alert ('".Error.ErrorVerify."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}

	if ($password == "" ) {
		echo "<script>alert ('".Error.ErrorNoNull."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	$newpass=md5($password);
	$sql = "update user set password='".$newpass."' where email='".$_SESSION ['you']."'";
	$query = mysql_query($sql); 
	echo "<script>alert ('".Error.ErrorModifyOK."');</script>";
    echo "<script language='javascript'>window.location.href='/sign_in.html';</script>";
    exit();
	
}


?>

<?php
include("footer.php");?>