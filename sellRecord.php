<?php 
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

$userid=$_SESSION['id'];
$btc=btcDetail($userid);



$money=$_POST['money'];
$btc_num=$_POST['btc_num'];
$buyid=$_POST['buyid'];
$advid=$_POST['advid'];
$price=$_POST['price'];
//var_dump($_POST);

//广告详情
$sqq="select * from advertise where ad_type='buy' and id=".$advid;
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
if($num<1){
	$arr['status']=false;
	$arr['msg']=Illegalrequest;
	echo json_encode($arr);
	exit;
}
$row=mysql_fetch_array($res);
if($row['is_show']!=1){//广告已下架
	$arr['status']=false;
	$arr['msg']=ADexpired;
	echo json_encode($arr);
	exit;
}

//$btc['avail']<$('#btcid')
if($btc['avail']<$btc_num){ //比特币余额不足
	$arr['status']=false;
	$arr['msg']=Inssuficcientbalance;
	echo json_encode($arr);
	exit;
}
if($price!=ADpriceNow($advid)){
	$arr['status']=false;
	$arr['msg']=Pricechange;
	echo json_encode($arr);
	exit;
}
if($money<$row['min_amount']||$money>$row['max_amount']){
	$arr['status']=false;
	$arr['msg']=AmountOutLimit;
	echo json_encode($arr);
	exit;
}
$jiaoyi_id=$_SESSION['id']."_".$buyid."_".date("YmdHis").mt_rand(1000,9999);
//记录交易订单
if(vip($_SESSION['id'])){//特约用户
	$fee=trade_special_fee();
}else{
	$fee=trade_fee_buy();
}
//广告主是买家，买家付手续费
$trade_fee=ceil($btc_num*Zero8()*$fee/100);//交易手续费
$buyer_real=floor($rod['btc_num']*Zero8()*(1-$fee/100));//买家实收数额
$sq="insert into dingdan(`jiaoyi_id`,`createid`,`advid`,`buyid`,`sellid`,`money`,`price`,`btc_num`,`trade_fee`,`seller_real_pay`,`buyer_real_gain`,`dd_time`,`dd_type`,`msgbuy`,`msgsell`)value('".$jiaoyi_id."',".$_SESSION['id'].",".$advid.",".$_SESSION['id'].",".$sellid.",".$money.",".$price.",".$btc_num.",".($trade_fee/Zero8()).",".($seller_real/Zero8()).",".$btc_num.",".time().",0,1,1)";

$sq="insert into dingdan(`jiaoyi_id`,`createid`,`advid`,`buyid`,`sellid`,`money`,`price`,`btc_num`,`trade_fee`,`seller_real_pay`,`buyer_real_gain`,`dd_time`,`dd_type`,`msgbuy`,`msgsell`)value('".$jiaoyi_id."',".$_SESSION['id'].",".$advid.",".$buyid.",".$_SESSION['id'].",".$money.",".$price.",".$btc_num.",".($trade_fee/Zero8()).",".$btc_num.",".($buyer_real/Zero8()).",".time().",0,1,1)";
$re=mysql_query($sq);
$ddid=mysql_insert_id();
//记录系统对话
$sqq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','createdS',".time().")";
$res2=mysql_query($sqq2);
//将比特币托管，广告主是买方，托管交易额
$sq3="update `user` set btc_tuoguan=btc_tuoguan+".($btc_num*Zero8())." where id=".$_SESSION['id'];
$re3=mysql_query($sq3);
$arr['status']=true;
$arr['msg']=$ddid;
echo json_encode($arr);

?>