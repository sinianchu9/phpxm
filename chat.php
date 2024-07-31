<?php 
//error_reporting(0);
session_start();
include("include/connect.php");
include("include/common.php");

$ddid=$_POST['ddid'];
$userid=$_POST['userid'];
$user_type=$_POST['user_type'];
$msg=$_POST['msg'];

if ($msg!=""){
	$sqq="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",".$userid.",'".$user_type."','".$msg."',".time().")";
	mysql_query($sqq);
	$sq="select sellid,buyid from dingdan where id=".$ddid;
	$re=mysql_query($sq);
	$ro=mysql_fetch_array($re);
	if($userid==$ro['sellid']){
		mysql_query("update dingdan set msgbuy=1 where id=".$ddid);
	}elseif($userid==$ro['buyid']){
		mysql_query("update dingdan set msgsell=1 where id=".$ddid);
	}
	$arr['msg']='';
	echo json_encode($arr);
}


?>