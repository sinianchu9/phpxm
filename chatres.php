<?php 
//error_reporting(0);
session_start();
include("include/connect.php");
include("include/common.php");
if($_SESSION['lang']=='cn'){
	include("include/lang_cn.php");
}elseif($_SESSION['lang']=='en'){
	include("include/lang_en.php");
}elseif($_SESSION['lang']=='hk'){
	include("include/lang_hk.php");
}
////////////查询数据库中的未付款过期订单
$sq="select * from dingdan where dd_type=0";
$re=mysql_query($sq);
while($ro=mysql_fetch_array($re)){
	if($ro['dd_time']<(time()-1800)){
		$re2=mysql_query("update dingdan set dd_type=3,msgbuy=1,msgsell=1 where id=".$ro['id']);
		$re3=mysql_query("insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ro['id'].",0,'system','expired',".time().")");
		$re4=mysql_query("update `user` set btc_tuoguan=btc_tuoguan-".($ro['seller_real_pay']*Zero8())." where id=".$ro['sellid']);
	}
}
//////////////
$ddid=$_POST['ddid'];
$userid=$_POST['userid'];
$chatnum=$_POST['chatnum'];

$sqq2="select * from dingdan where id=".$ddid;
$res2=mysql_query($sqq2);
$row2=mysql_fetch_array($res2);

$sqq="select * from chat where dd_id=".$ddid." order by id asc";
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
if($num!=$chatnum){
	
	$res3=mysql_query("update dingdan set mess_num=".$num." where id=".$ddid);
	$result="";
	while($row=mysql_fetch_array($res)){
		if($row['user_type']=='system'){
			$result.="<div class='chat sys'><div class='title'>".SysMesTitle($row['message'])."</div><div class='content'>".SysMesContent($row['message'])."</div><span class='time'>".date('Y-m-d H:i:s',$row['time'])."</span></div>";
		}elseif($row['userid']==$userid){
			$result.="<div class='chat ta-r'><span class='chat-message'>".$row['message']."<span class='time'>".date('Y-m-d H:i:s',$row['time'])."</span></span><img class='user-logo' src='".headimg($row['userid'])." '></div>";
		}else{
			$result.="<div class='chat ta-l'><img class='user-logo' src='". headimg($row['userid'])."' ><span class='chat-message'>". $row['message']."<span class='time'>". date('Y-m-d H:i:s',$row['time'])."</span></span></div>";
		}
	}
	$arr['status']=true;
	$arr['msg']=$result;
	$arr['num']=$num;
	echo json_encode($arr);
}else{
	$arr['status']=false;
	echo json_encode($arr);
}

