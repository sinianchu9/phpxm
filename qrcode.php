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
	$content=$address;//��ά������ 
	$errorCorrectionLevel = 'L';//�ݴ���
	$matrixPointSize = 9;//����ͼƬ��С
	//���ɶ�ά��ͼƬ
	$path = 'image/qrcode/'.$address.'.png';
	QRcode::png($content, $path, $errorCorrectionLevel, $matrixPointSize, 2);

	$sq2=mysql_query("update wallet set qrcode='".$path."' where address='".$address."'");
	$arr['msg']=$path;
	echo json_encode($arr);
}


?>