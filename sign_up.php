<?php
include("header.php");

//$System = java("java.lang.System");

//获取推荐人id,默认为1
$did = 1;
if ($_GET){
	$tid=$_GET['ref'];
$obj = new XDeode(9);
$did = $obj->decode($tid);
//echo "xxxxxxxxxxxxxxxxxxxxxxxxx".$did;
}

if($_POST){
	$username=$_POST['username'];
	$email=$_POST['email'];
	$password=$_POST['password1'];
	$password2=$_POST['password2'];
	$verifyCode=$_POST['verifyCode'];


	if ($verifyCode == "" || $verifyCode != $_SESSION ['verifyCode']) {
		echo "<script>alert ('".Error.ErrorVerify."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}

	if ($username == "" || $password == ""||$password2=="") {
		echo "<script>alert ('".Error.Competeinfo."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if($password != $password2){
		echo "<script>alert ('".Error.Twopass."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$email)){
		echo "<script>alert ('".Error.Emailtip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+$/",$username)){
		echo "<script>alert ('".Error.Username.Usernametip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+$/",$password)){
		echo "<script>alert ('".Error.Password.Usernametip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	//检测用户名重复性
	$sql = "select * from `user` where username='" . $username . "'";
	$res = mysql_query ( $sql );
	$num = mysql_num_rows ( $res );
	if ($num != 0) {
		echo "<script>alert ('".Error.Useduser."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}//
	//检测邮箱重复性
	$sql2 = "select * from `user` where email='" . $email . "'";
	$res2 = mysql_query ( $sql2 );
	$num2 = mysql_num_rows ( $res2 );
	if ($num2 != 0) {
		echo "<script>alert ('".Error.Usedemail."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}//
	//可以注册时创建钱包
	/*
	$walletName = md5($username+$time);

	$walletPath = 'C:\\Users\\Administrator\\Desktop\\custom\\'.$username.'\\'.$walletName.'.wallet';

	$myj = new Java("com.xin.wallet.BitCoin");
	$address=$myj->createNewWallet($walletPath);*/

	$sqq2="insert into `user`(username,email,password,reg_time,tj_userid)values('".$username."','".$email."','".md5($password)."',".time().",".$did.")";
	//echo $sqq2;exit;
	$res2=mysql_query($sqq2);
	$id=mysql_insert_id();
	$_SESSION ['username'] = $username;
	$_SESSION ['id'] = $id;

	//创建钱包后将钱包信息写入数据库
	/*$sq="insert into wallet(`userid`,`address`,`path`)values(".$id.",'".$address."','".addslashes($walletPath)."')";
	$re=mysql_query($sq);*/

	//$myj = new Java("com.xin.wallet.BitCoin");
	//$address=$myj->createNewWallet($id);
	//我在register中写入userid，create_time，state
	$sq="insert into register(`userid`,`create_time`,`state`)values(".$id.",".(time()*1000).",'0')";
	$re=mysql_query($sq);


	//echo "<script>alert ('注册成功');</script>";
	if($res2){
		echo "<script language='javascript'>window.location.href='/index.html';</script>";
	}else{
		echo "<script>alert ('".Error."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
    
	
}


?>

<div class="container mainbody">
	<div class="row">
		<div class="col-xs-offset-1 col-xs-10">
			<div id="register-body">
				<div class="text-right" style="margin-bottom:20px;">
					<h2><?php echo Register?></h2>
					<!-- <p>注册一个帐户，并开始购买或出售比特币</p> -->
				</div>
				<form method="post" class="form-horizontal" action="">
					<div class="form-group"> 
						<label for="id_username" class="control-label"><?php echo Username?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_username" maxlength="30" name="username" type="text" placeholder="<?php echo Usernametip?>"> 
					</div> 

					<!-- add email--> 

					<div class="form-group"> 
						<label for="id_email" class="control-label"><?php echo email?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_email" maxlength="30" name="email" type="email" placeholder="<?php echo emailtip?>"> 
					</div> 

					<div class="form-group"> 
						<label for="id_password1" class="control-label"><?php echo Password?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_password1" name="password1" type="password" placeholder="<?php echo Usernametip?>"></span>
					</div> 
					<div class="form-group"> 
						<label for="id_password2" class="control-label"><?php echo PasswordAgain?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_password2" name="password2" type="password">
					</div>
					<div class="form-group"> 
						<label for="verifyCode" class="control-label"><?php echo Verification?><span class="asteriskField">*</span> </label> 
						<div class="row">
							<div class="col-xs-5"><input class="form-control" id="verifyCode" name="verifyCode" type="text"></div> 
							<div class="col-xs-4"><img src="/testCaptcha.php" id="verifyImage" onclick="document.getElementById('verifyImage').src='/testCaptcha.php?r='+Math.random()"></div>
						</div>
					</div>
				
					<!-- <p></p>
					<div>
						<label>请验证您是一个人。</label>
						<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
						<div class="g-recaptcha" data-sitekey="6Le95uoSAAAAAH3LKzssY-LHQOMu6eBag0yqlA6O"></div>
					</div> -->

									
					<div class="form-group"><div class="col-xs-offset-2 col-xs-10"> <button type="submit" class="btn btn-info" style="float:right;"><?php echo Register?></button></div></div>

				</form>
			</div>
			<hr/>
			<div class="text-right"><?php echo Olduser?> <a href="/sign_in.html"><?php echo Login?></a></div>
			<!-- <p>忘记了密码? <a href="/password_reset/">重置您的密码</a></p> -->
		</div>
	</div>
</div>
<?php
include("footer.php");?>