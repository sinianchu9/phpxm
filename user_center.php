<?php
include("header.php");
include("include/checkSession.php");
$userid=$_SESSION['id'];
$type="Basicinformation";
if($_GET['type']){
	$type=$_GET['type'];
}
if($type=='Basicinformation'){
	$Adtitle=Basicinformation;
}elseif($type=='Settings'){
	$Adtitle=Settings;
}elseif($type=='Changepassword'){
	$Adtitle=Changepassword;
}


$userid=$_SESSION['id'];
$sql="select * from `user` where id=".$userid;
$res=mysql_query($sql);
$num=mysql_num_rows($res);
$row=mysql_fetch_array($res);

$userRes=userInfoDetail($userid);


if($_POST['email']){
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	if ($email == "" || $phone == "") {
		echo "<script>alert ('".Error.Competeinfo."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$email)){
		echo "<script>alert ('".Error.Emailtip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^1(3|4|5|7|8)\d{9}$/",$phone)){
		echo "<script>alert ('".Error.Phonetip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	//检测邮箱重复性
	$sql2 = "select email from `user` where email='" . $email . "' and id!=".$userid;
	$res2 = mysql_query ( $sql2 );
	$num2 = mysql_num_rows ( $res2 );
	if ($num2 != 0) {
		echo "<script>alert ('".Error.Usedemail."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	mysql_query("update user set email='".$email."',mobile='".$phone."' where id=".$userid);
	echo "<script language='javascript'>window.location.href='/user_center/".$_GET['type'].".html';</script>";
}
if($_POST['oldpass']){
	$oldpass=$_POST['oldpass'];
	$pass1=$_POST['pass1'];
	$pass2=$_POST['pass2'];
	
	if ($oldpass == "" || $pass1 == "" || $pass2 == "") {
		echo "<script>alert ('".Error.Competeinfo."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(md5($oldpass) != $row['password']){
		echo "<script>alert ('".Error.ErrorOldPass."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if(!preg_match("/^\w+$/",$pass1)){
		echo "<script>alert ('".Error.Password.Usernametip."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if($pass1 != $pass2){
		echo "<script>alert ('".Error.Twopass."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	$res=mysql_query("update user set password='".md5($pass1)."' where id=".$userid);
	if($res){
		echo "<script>alert ('".PassSuccess."');</script>";
	}
	echo "<script language='javascript'>window.location.href='/user_center/Basicinformation.html';</script>";
}



?>


<div class="container mainbody user_center" style="width:100%;padding:0;">
	
	<div class="tabs clear-float">
		<div class="tab <?php if($type=='Basicinformation'){?>actived<?php }?>" id="Basicinformation"><?php echo Basicinformation?></div>
		<div class="tab <?php if($type=='Settings'){?>actived<?php }?>" id="Settings"><?php echo Settings?></div>
		<div class="tab <?php if($type=='Changepassword'){?>actived<?php }?>" id="Changepassword"><?php echo Changepassword?></div>
	</div>
	<div class="panel clear-float">

		<?php if($type=='Basicinformation'){ ?>
		<div class="btc-send" id="Basicinformationid" style="display: block;">
			<div class="head-cont">
				<img class="user-logo" src="<?php echo $row['headimg']?>" alt="">
				<a class="edit-logo"><?php echo Edithead?></a>
				
				<input class="avatar-input" id="headimgid" name="headimg" type="file" accept="image/jpg,image/jpeg,image/png,image/gif">

			</div>
			<div class="user-name"><?php echo $row['username']?></div>
			<div class="user-profile">
				
				<!-- <div class="grey-text">VIP 666</div> -->
				<ul class="profile-detail">
					<li class="profile-item">
						<span class="item-name"><?php echo Email?>：</span>
						<span class="item-text"><?php echo $row['email']?></span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Phone?>：</span>
						<span class="item-text"><?php echo $row['mobile'];?></span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Signuptime?>：</span>
						<span class="item-text"><?php echo date('Y-m-d H:i:d',$row['reg_time']);?></span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Fristtimetrader?>：</span>
						<span class="item-text"><?php echo $userRes['first']?></span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Rating?>：</span>
						<span class="item-text"><?php echo $userRes['pingjia'];?>%</span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Confirmedtrades?>： </span>
						<span class="item-text"><?php echo $userRes['jiaoyi'];?></span>
					</li>
					<li class="profile-item">
						<span class="item-name"><?php echo Volume;?>：</span>
						<span class="item-text"><?php echo btcShow($userRes['btc']);?>BTC</span>
					</li>
				</ul>
			</div>
		</div>
		<?php } ?>
		<?php if($type=='Settings'){ ?>
		<div class="btc-send" id="Settingsid" style="display: block;">
			<form action="/user_center/Settings.html" method="POST">
				<div class="form-cont" id="step1">
					<div class="input-cont">
						<span class="icon form-email"></span>
						<input class="input" validate="" type="email" check-type="email" value="<?php echo $row['email']?>" name="email" autocomplete="off" placeholder="<?php echo Emailplace?>">

					</div>
					<div class="input-cont">
						<span class="icon form-tel"></span>
						<input class="input" validate="" type="text" check-type="phone" name="phone" value="<?php echo $row['mobile']?>" autocomplete="off" placeholder="<?php echo Phoneplace?>">

					</div>
					<button type="sumit" style="float:right;margin-right:40px;" class="btn btn-primary"><?php echo BtnOK?></button>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<?php } ?>
		<?php if($type=='Changepassword'){ ?>
		<div class="btc-send" id="Changepasswordid" style="display: block;">
			<form action="/user_center/Changepassword.html" method="POST">
				<div class="form-cont" id="step1">
					<div class="form-text"><?php echo Passmodify?></div>
					<div class="input-cont">
						<span class="icon form-password"></span>
						<input class="input" validate="" type="password" name="oldpass" placeholder="<?php echo OldPass?>">
					</div>
					<div class="input-cont">
						<span class="icon form-password"></span>
						<input class="input" validate="" type="password" name="pass1"  placeholder="<?php echo NewPass?>">
					</div>
					<div class="input-cont">
						<span class="icon form-password"></span>
						<input class="input" validate="" type="password" name="pass2"  placeholder="<?php echo NewPass2?>">
					</div>
					<button type="sumit" style="float:right;margin-right:40px;" class="btn btn-primary"><?php echo BtnOK?></button>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<?php } ?>
	</div>
</div>





<script>
$(window).load(function(){
	$("#headimgid").mouseover(function(){
		console.log('over');
		$(".edit-logo").css('display','block');
	});//mouseover
	$("#headimgid").mouseout(function(){
		console.log('out');
		$(".edit-logo").css('display','none');
	});//mouseout
	$("#Basicinformation").click(function(){
		location.href="/user_center/Basicinformation.html";
	});
	$("#Settings").click(function(){
		location.href="/user_center/Settings.html";
	});
	$("#Changepassword").click(function(){
		location.href="/user_center/Changepassword.html";
	});

	$('#headimgid').unbind().change(function(){
		//<form id='myupload' action='headimgUplode.php' method='post' enctype='multipart/form-data'>
		$("#headimgid").wrap("<form id='myupload' action='/headimgUplode.php' method='post' enctype='multipart/form-data'></form>");
		$("#myupload").ajaxSubmit({ 
			dataType:  'json',
			success: function(data){
				if(data.success){
					location.reload(true);
				}else{
					$(".dialog-msg").html(data.msg);
					$("#closeid").html("");
					$("#okid").html("<?php echo Gotit?>");
					$("#alertID").show();
					$("#tabindex0").show();

					$("#okid").click(function(){
						$("#alertID").hide();
						$("#tabindex0").hide();
					});
				}
			  
			  //$("#aa").show();
		  }
		});
	});
});

</script>
<?php
include("footer.php");?>