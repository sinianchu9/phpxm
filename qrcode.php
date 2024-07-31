<?php 
//error_reporting(0);
session_start();
include("include/connect.php");
include("include/common.php");
include("phpqrcode/phpqrcode.php");

$address=$_POST['address'];
$sqq="select * from wallet where address='".$address."'";
$res=mysql_query($sqq);
$row=mysql_fetch_array($res);
if($row['qrcode']){
	$arr['msg']=$row['qrcode'];
	echo json_encode($arr);
}else{
	$content=$address;//二维码内容 
	$errorCorrectionLevel = 'L';//容错级别
	$matrixPointSize = 9;//生成图片大小
	//生成二维码图片
	$path = 'image/qrcode/'.$address.'.png';
	QRcode::png($content, $path, $errorCorrectionLevel, $matrixPointSize, 2);

	$sq2=mysql_query("update wallet set qrcode='".$path."' where address='".$address."'");
	$arr['msg']=$path;
	echo json_encode($arr);
}


?>