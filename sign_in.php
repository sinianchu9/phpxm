<?php
include("header.php");

if($_POST){
	$email=$_POST['email'];
	$password=$_POST['password'];
	$verifyCode=$_POST['verifyCode'];

	if ($verifyCode == "" || $verifyCode != $_SESSION ['verifyCode']) {
		echo "<script>alert ('".Error.ErrorVerify."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}

	if ($email == "" || $password == "") {
		echo "<script>alert ('".Error.Competeinfo."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$email)){
		echo "<script>alert ('".Error.Emailtip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+$/",$password)){
		echo "<script>alert ('".Error.Password.Usernametip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}

	//检测用户
	$sql = "select * from `user` where email='" . $email . "' and password='" . md5 ( $password ) . "'";
	$res = mysql_query ( $sql );
	$num = mysql_num_rows ( $res );
	$row = mysql_fetch_array ( $res );
	if ($num == 0) {
		echo "<script>alert ('".Error.ErrorIn."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if($row['status']==0){ //被关闭
		echo "<script>alert ('".Error.ErrorInClose."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}

	$ip = $_SERVER ['REMOTE_ADDR'];
	$time=time();
	$sqq2="insert into loginlog(ip,time,userid)values('".$ip."',".$time.",".$row['id'].")";
	$res2=mysql_query($sqq2);
	$_SESSION ['username'] = $row['username'];
	$_SESSION ['id'] = $row['id'];
    echo "<script language='javascript'>window.location.href='/index.html';</script>";
	
}


?>

<div class="container mainbody">
	<div class="row">
		<div class="col-xs-offset-1 col-xs-10">
			<div id="register-body">
				<div class="text-right" style="margin-bottom:20px;">
					<h2><?php echo Login?></h2>
					<!-- <p>注册一个帐户，并开始购买或出售比特币</p> -->
				</div>
				<form method="post" class="form-horizontal" action="">
					<div class="form-group"> 
						<label for="id_username" class="control-label"><?php echo email?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_username" maxlength="30" name="email" type="text"> 
					</div> 
					<div class="form-group"> 
						<label for="id_password1" class="control-label"><?php echo Password?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_password1" name="password" type="password"><span class="pwstr"></span>
					</div> 
					<div class="form-group"> 
						<label for="verifyCode" class="control-label"><?php echo Verification?><span class="asteriskField">*</span> </label> 
						<div class="row">
							<div class="col-xs-6"><input class="form-control" id="verifyCode" name="verifyCode" type="text"></div> 
							<div class="col-xs-6"><img src="/testCaptcha.php" onclick="document.getElementById('verifyImage').src='/testCaptcha.php?r='+Math.random()" id="verifyImage"></div>
						</div> 
					</div>

									
					<div class="form-group"><div class="col-xs-offset-2 col-xs-10"> <button type="submit" class="btn btn-info" style="float:right;"><?php echo Login?></button></div></div>

				</form>
			</div>
			<hr/>
			<div class="text-right"><?php echo Newuser?> <a href="/sign_up.html"><?php echo RegisterNow?></a>  <a href="/find.html">&nbsp;&nbsp;(<?php echo forgetpass?>)</a></div>
		
		</div>
	</div>
</div>
<?php
include("footer.php");?>