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


$money=$_POST['money'];
$btc_num=$_POST['btc_num'];
$sellid=$_POST['sellid'];
$advid=$_POST['advid'];
$price=$_POST['price'];

//广告详情
$sqq="select * from advertise where ad_type='sell' and id=".$advid;
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
//var_dump($row);
$btc=btcDetail($row['userid']);//获取卖家的比特币详情


//确定价格
$myprice=ADpriceNow($advid);

if($btc['avail']>=set1()){
	//确定最大限额
	if($btc['avail']*$myprice>$row['max_amount']){
		$max=$row['max_amount'];
	}else{
		$max=intval($btc['avail']*$myprice);
	}
	//确定最大限额
	if($btc['avail']*$myprice>$row['min_amount']){
		$min=$row['min_amount'];
	}else{
		$min=intval($btc['avail']*$myprice);
	}
}else{
	$max=0;
	$min=0;
}
//echo $myprice;
if($myprice!=$price){
	$arr['status']=false;
	$arr['msg']=Pricechange;
	echo json_encode($arr);
	exit;
}
if($money<$min||$money>$max){
	$arr['status']=false;
	$arr['msg']=AmountOutLimit;
	echo json_encode($arr);
	exit;
}

$jiaoyi_id=$_SESSION['id']."_".$sellid."_".date("YmdHis").mt_rand(1000,9999);
//记录交易订单
$fee=seller_trade($sellid);//卖家交易手续费
//卖家是广告主，付手续费
$trade_fee=ceil($btc_num*Zero8()*$fee/100);//交易手续费
$seller_real=ceil($btc_num*Zero8()*(1+$fee/100));//卖家实付数额
$sq="insert into dingdan(`jiaoyi_id`,`createid`,`advid`,`buyid`,`sellid`,`money`,`price`,`btc_num`,`trade_fee`,`seller_real_pay`,`buyer_real_gain`,`dd_time`,`dd_type`,`msgbuy`,`msgsell`)value('".$jiaoyi_id."',".$_SESSION['id'].",".$advid.",".$_SESSION['id'].",".$sellid.",".$money.",".$price.",".$btc_num.",".($trade_fee/Zero8()).",".($seller_real/Zero8()).",".$btc_num.",".time().",0,1,1)";
$re=mysql_query($sq);
$ddid=mysql_insert_id();
//记录交易系统对话
$sqq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','created',".time().")";
$res2=mysql_query($sqq2);
//将比特币托管，广告主是卖方，托管交易额和手续费
$sq3="update `user` set btc_tuoguan=btc_tuoguan+".$seller_real." where id=".$sellid;
$re3=mysql_query($sq3);

$arr['status']=true;
$arr['msg']=$ddid;
echo json_encode($arr);

?>