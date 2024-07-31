<?php
include("header.php");



if($_POST['verifyCode']){
	$you=$_POST['you'];
	
	$verifyCode=$_POST['verifyCode'];

	//echo $email;
	//echo  $verifyCode;


	if ($verifyCode == "" || $verifyCode != $_SESSION ['verifyCode']) {
		echo "<script>alert ('".Error.ErrorVerify."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}


 
$sql = "select id  from `user` where `email`='".$you."'"; 
$query = mysql_query($sql); 
$num = mysql_num_rows($query); 
if($num==0){
		echo  "<script>alert ('".Error.ErrorNoEmail."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
       exit();
}
else
	{ 
	$code=rand(100000,999999);
	$_SESSION ['passcode'] = $code;
	$_SESSION ['you'] = $you;
	$url = 'http://v.3721p.net/phpmail/sendmail.php?you='.$you.'&vcode='.$code;
	$pds = array ("you" => $you,"code" => $code);
//var_dump($pds);

	$curl = curl_init();
	curl_setopt($curl,CURLOPT_TIMEOUT,5000);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	$res = curl_exec($curl);
	if($res){
		curl_close($curl);
		//return $res;
	}else {
		$error = curl_errno($curl);
		curl_close($curl);
		//return $error;
	}


} 
	
}



?>

<div class="container mainbody">
	<div class="row">
		<div class="col-xs-offset-1 col-xs-10">
			<div id="register-body">
				<div class="text-right" style="margin-bottom:20px;">
					<h2><?php echo Modifypass;?></h2>
					<!-- <p>注册一个帐户，并开始购买或出售比特币</p> -->
				</div>
				<form method="post" class="form-horizontal" action="find3.html">
					<div class="form-group"> 
						<label for="vcode" class="control-label"><?php echo Verification;?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="vcode" maxlength="30" name="vcode" type="text"> 
					</div> 
				
					<div class="form-group"> 
						<label for="newpass" class="control-label"><?php echo Newpass;?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="newpass" name="newpass" type="text">
					</div>			
					<div class="form-group"><div class="col-xs-offset-2 col-xs-10"> <button type="submit" class="btn btn-info" style="float:right;"><?php echo Tijiao?></button></div></div>

				</form>
			</div>
			
		</div>
	</div>
</div>


<?php
include("footer.php");?>