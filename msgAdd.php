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

$userid=$_POST['userid'];

$sqq="select id from dingdan where (sellid=".$userid." and msgsell=1) or (buyid=".$userid." and msgbuy=1)";
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
							
if($num>0){
	
	$result="<a  href='tradeList.php'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;".Trades."<span class='message-count' style='display: none'></span></a><ul id='trade_menu' class='dropdown-menu' style='display:none'>";
	while($row=mysql_fetch_array($res)){
		$result.="<li><a href='/ddContent/".$row['id'].".html'>".Message."</a></li>";
	}
	$result.="</ul>";
	$arr['status']=true;
	$arr['msg']=$result;
	echo json_encode($arr);
}else{
	$arr['msg']="<a  href='/tradeList.html'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;".Trades."<span class='message-count' style='display: none'></span></a>";
	$arr['status']=false;
	echo json_encode($arr);
}
?>
					
