<?php 
error_reporting(-1);
session_start();
define('ROOT',dirname(__FILE__)); 
include("include/connect.php");
include("include/common.php");

if($_SESSION['lang']=='cn'){
	include("include/lang_cn.php");
}elseif($_SESSION['lang']=='en'){
	include("include/lang_en.php");
}elseif($_SESSION['lang']=='hk'){
	include("include/lang_hk.php");
}



$userid=$_SESSION['id'];

$file = $_FILES['headimg'];//得到传输的数据
//var_dump($file);exit;
if ($file['size']){
	//得到文件名称
	$name = $file['name'];
	$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
	$allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
	//判断文件类型是否被允许上传
	if(!in_array($type, $allow_type)){
		//如果不被允许，则直接停止程序运行
		$arr['success']=false;
		$arr['msg']=FiletypeError;//类型错误
		echo json_encode ( $arr );
		exit();
	}
	//判断是否是通过HTTP POST上传的
	if(!is_uploaded_file($file['tmp_name'])){
		//如果不是通过HTTP POST上传的
		$arr['success']=false;
		$arr['msg']=Illegalrequest;//系统错误
		echo json_encode ( $arr );
		exit();
	}
	$filename=explode(".",$file['name']);
	$uploaddir = "/image/headimg/";
	$filename[0]="headimg_".$userid; //设置名称
	$name=implode(".",$filename);
	$uploadfile=ROOT.$uploaddir.$name;
	$imgsrc=$uploaddir.$name;
	$aaa=move_uploaded_file($file['tmp_name'],$uploadfile);
	//var_dump($aaa);
	if(!$aaa){
		$arr['success']=false;
		$arr['msg']=Uplodefiled.$uploadfile;//上传失败
		echo json_encode ( $arr );
		exit();
	}
	
	
	mysql_query("update `user` set headimg='".$imgsrc."' where id=".$userid);

	$arr['success']=true;
	$arr['msg']='';
	echo json_encode ( $arr );
}


?>