<?php
session_start();
include("include/connect.php");
include("include/common.php");
include("include/checkPost.php");

$useragent = addslashes(strtolower($_SERVER['HTTP_USER_AGENT']));
if (strpos($useragent,'baiduspider') !== false){
	$_SESSION['lang']='cn';
	$language="简体中文";
}

if($_SESSION['lang']==""){
	$_SESSION['lang']='hk';
	$language="繁體中文";
}
if($_SESSION['lang']=='cn'){
	include("include/lang_cn.php");
	$language="简体中文";
}elseif($_SESSION['lang']=='en'){
	include("include/lang_en.php");
	$language="English";
}elseif($_SESSION['lang']=='hk'){
	include("include/lang_hk.php");
	$language="繁體中文";
}

//比特币实时价格
$url="https://blockchain.info/ticker";
$priceContent=http_curl_get($url);
$priceArray=json_decode($priceContent,true);

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="refresh" content="300">
	<link rel="shortcut icon" href="/image/favicon.ico" type="image/x-icon">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title><?php echo '比特币场外交易平台,欧美亚比特币';?></title>
	<meta name="keywords" content="比特币场外交易平台,比特币交易平台,比特币场外交易,比特币otc交易平台,比特币otc,欧美亚比特币场外交易平台">
	<meta name="description" content="欧美亚比特币场外交易平台，是手续费最低，交易最便捷，财产最安全的比特币交易平台">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="/assets/css/demo.css" rel="stylesheet" />
    <link href="/assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	<link href="/assets/css/main.css" rel="stylesheet"/>
    <script src="/assets/js/jquery-2.1.0.min.js" type="text/javascript"></script>
	<script src="/assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="/assets/js/bootstrap-checkbox-radio-switch.js"></script>

	<!--  Charts Plugin -->
	<script src="/assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="/assets/js/bootstrap-notify.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="/assets/js/light-bootstrap-dashboard.js"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="/assets/js/demo.js"></script>
	<script src="/js/jquery-form.js"></script>



</head>
<body>

<div class="wrapper">
	<div class="sidebar" data-color="blue">
		<div class="sidebar-wrapper">
			<div class="logo">
				<a href="#" class="simple-text"><?php echo $_SESSION['username']?></a>
			</div>
			<ul class="nav">
				<?php if($_SESSION['username']==""){?>
				<li><a href="/sign_up.html"><i class="pe-7s-check"></i><?php echo Register;?></a></li>
				<li><a href="/sign_in.html"><i class="pe-7s-user"></i><?php echo Login;?></a></li>
				<?php }?>
				<li><a href="/index.html"><i class="pe-7s-home"></i><?php echo HomeNav;?></a></li>
				<li><a href="/buy_bitcoins.html"><i class="pe-7s-back-2"></i><?php echo BuyBtn;?></a></li>
				<li><a href="/sell_bitcoins.html"><i class="pe-7s-next-2"></i><?php echo SellBtn;?></a></li>
				<li><a href="/advertise.html"><i class="pe-7s-paperclip"></i><?php echo AdvertiseNav;?></a></li>
				<li><a href="/invite.html"><i class="pe-7s-share"></i><?php echo Invite;?></a></li>
				
				<?php if($_SESSION['username']!=""){?>
						<li><a href="/tradeList.html"><i class="pe-7s-albums"></i><?php echo Trades;?></a></li>
						<li class="dropdown open"><a href="/wallet.html" class=""><i class="pe-7s-wallet"></i><?php echo Wallet;?></a>
							<ul class="dropdown-menu">
								<li><a href="/wallet/receive.html"><i class="pe-7s-angle-right-circle"></i><?php echo Deposit;?></a></li>
								<li><a href="/wallet/send.html"><i class="pe-7s-angle-right-circle"></i><?php echo Withdraw;?></a></li>
							</ul>
						</li>
						<li><a href="/user_center.html"><i class="pe-7s-user"></i><?php echo Usercenter;?></a></li>
						<li><a href="/manage/buy.html"><i class="pe-7s-albums"></i><?php echo Myad;?></a></li>
						<li><a href="/sign_out.php"><i class="pe-7s-power"></i><?php echo Logout;?></a></li>
						
						<?php }?>
						<li><a href="/news.html" target="_blank"><i class="pe-7s-ribbon"></i><?php echo NEWS?></a></li>
						<li><a href="/about.html" target="_blank"><i class="pe-7s-ribbon"></i><?php echo ABOUT?></a></li>
						<li><a href="/fee.html" target="_blank"><i class="pe-7s-ribbon"></i><?php echo FEESM?></a></li>
						<li><a href="/help.html" target="_blank"><i class="pe-7s-ribbon"></i><?php echo FAQ?></a></li>
				
				
			</ul>
		</div>
	</div>
	<div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/index.html" style="padding:0"><img src="/image/logo1.png" style="height:50px;"></a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						
					</ul>
				</div>
			</div>
		</nav>
<audio id="myAudio">
  <source src="0.wav" type="audio/mpeg">
</audio>

<script>
$(window).load(function(){
	if("<?php echo $_SESSION['id']?>"!=""){
		setInterval("msgRequest1(<?php echo $_SESSION['id']?>)", 5000);
	}
});
function msgRequest1(userid) {  
	$.ajax({  
		url: "/msgAdd.php",  
		type: 'POST', 
		dataType : 'json',
		data: {  
			'userid': userid,
		},  
		success: function (data) {  
			if(data.status==true){
				var x = document.getElementById("myAudio"); 
				x.play();
				$("#trade_link").html(data.msg); 
				$(".message-count").show();
				$("#trade_link").mouseover(function(){
				  $("#trade_menu").show();
				});//mouseover
				$("#trade_link").mouseout(function(){
				  $("#trade_menu").hide();
				});//mouseout
			}else{
				$("#trade_link").html(data.msg); 
			}
		},  
		error : function(jqXHR) {
		},
	});  
} 
</script>