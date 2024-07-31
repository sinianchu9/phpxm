<?php
include("header.php");
//require_once("java/Java.inc");
include("include/checkSession.php");
include("phpqrcode/phpqrcode.php");

///////////////刷新钱包信息
/*
function updateWallet($userid){
	$myj = new Java("com.xin.wallet.BitCoin");
	$res=$myj->updateWalletInfo($userid);
	return $res;
}
*/
///////////////////
$op="receive";
if($_GET['op']){
	$op=$_GET['op'];
}

$userid=$_SESSION['id'];
$btc=btcDetail($userid);//$btc['sum']总资产，$btc['dj']被冻结，$btc['avail']可用，$btc['unconfirmed']未确认

$sqq="select * from wallet where userid=".$userid;
$res=mysql_query($sqq);
$rr=mysql_query($sqq);
$num=mysql_num_rows($res);


////////////////////////////////////////////////////////////////////
//////////////////刷新钱包信息
if($num>0){
	while($w=mysql_fetch_array($rr)){
		$file=http_curl_get("https://chain.api.btc.com/v3/address/".$w['address']);//
		//$file=http_curl_get("https://chain.api.btc.com/v3/address/1GceitQkbumhN3KMi5P6z3KZJKJPz6VUJT");//
		$infoArr=json_decode($file,true);
		//var_dump($infoArr);
		if($infoArr['data']!=null){
			if(!($w['balance']==$infoArr['data']['balance']&&$w['unconfirmed_received']==$infoArr['data']['unconfirmed_received'])){
				$sq="update wallet set received=".$infoArr['data']['received'].",sent=".$infoArr['data']['sent'].",balance=".$infoArr['data']['balance'].",tx_count=".$infoArr['data']['tx_count'].",unconfirmed_tx_count=".$infoArr['data']['unconfirmed_tx_count'].",unconfirmed_received=".$infoArr['data']['unconfirmed_received'].",unconfirmed_sent=".$infoArr['data']['unconfirmed_sent'].",unspent_tx_count=".$infoArr['data']['unspent_tx_count'].",first_tx='".$infoArr['data']['first_tx']."',last_tx='".$infoArr['data']['last_tx']."' where address='".$w['address']."'"; //更新钱包信息
				$re=mysql_query($sq);

				if($w['unconfirmed_received']!=$infoArr['data']['unconfirmed_received']){  //钱包未确认不同时，更新用户账户未确认
					$sq1="update `user` set btc_unconfirmed=btc_unconfirmed+".($infoArr['data']['unconfirmed_received']-$w['unconfirmed_received'])." where id=".$userid;//账户未确认
					$re1=mysql_query($sq1);
				}

				if($w['balance']<$infoArr['data']['balance']){
					$btc_add=$infoArr['data']['balance']-$w['balance'];//充值数额，手续费recharge_fee()
					$add_sxf=recharge_fee();//充值手续费recharge_fee()
					if($btc_add>$add_sxf){
						$sq2="update `user` set btc_sum=btc_sum+".($btc_add-$add_sxf)." where id=".$userid;//账户总额
						$re2=mysql_query($sq2);

						//写入充值表
						mysql_query("insert into chongzhi(`userid`,`cz_btc`,`cz_tip`,`real_btc`,`cz_time`)values(".$userid.",".$btc_add.",".$add_sxf.",".($btc_add-$add_sxf).",".time().")");

						////记录btc明细
						$sq3="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(1,".$userid.",'".username($userid)."',".$btc_add.",".time().")";//转入
						$re3=mysql_query($sq3);
						$sq4="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(8,".$userid.",'".username($userid)."',".$add_sxf.",".time().")";//转入手续费
						$re4=mysql_query($sq4);
					}
				}
			}
		}
	}
}
//////////////////////////////////////////////////////////////////



//提现手续费
$tt_sxf=set2()/Zero8();

//$jj_sxf=set3()/Zero8();//加急，暂时去除

$max_tt=floatval($btc['avail']-$tt_sxf);
if($max_tt<=0){
	$max_tt=0;
}


if($_POST){
	//var_dump($_POST);exit;
	$tt_address=$_POST['tt_address'];
	$tt_btc=$_POST['tt_btc'];
	$tt_beizhu=$_POST['tt_beizhu'];
	$tt_tip=$_POST['tt_tip'];
	if($_POST['urgent']){
		$urgent=1;
	}else{
		$urgent=0;
	}

	//查询是否是站内转帐
	$sq22="select userid,address from wallet where address='".$tt_address."'";
	$re22=mysql_query($sq22);
	$nu22=mysql_num_rows($re22);
	if($nu22>0){  //如果是站内
		$ro22=mysql_fetch_array($re22);
		if($tt_btc>$btc['avail']){
			echo "<script>alert ('".Error.Amounttip.floatval($btc['avail'])."BTC');</script>";
			echo "<script language='javascript'>window.history.go(-1);</script>";
			exit();
		}
		//用户总额减少
		$sq3="update `user` set btc_sum=btc_sum-".(($tt_btc)*Zero8())." where id=".$userid;//站内转出
		$re3=mysql_query($sq3);
		$sq4="update `user` set btc_sum=btc_sum+".(($tt_btc)*Zero8())." where id=".$ro22['userid'];//站内转入
		$re4=mysql_query($sq4);
		if($res){
			//写入提现记录
			$sqq2="insert into tixian(`userid`,tt_time1,tt_address,tt_btc,tt_tip,tt_status,urgent,tt_beizhu)values(".$userid.",".time().",'".$tt_address."',".($tt_btc*Zero8()).",0,2,".$urgent.",'".$tt_beizhu."')";
			$res2=mysql_query($sqq2);
			////记录btc明细
			$sq5="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(12,".$userid.",'".username($userid)."',".($tt_btc*Zero8()).",".time().")";////站内转出
			$re5=mysql_query($sq5);
			$sq6="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(13,".$ro22['userid'].",'".username($ro22['userid'])."',".($tt_btc*Zero8()).",".time().")";//站内转入
			$re6=mysql_query($sq6);

		}
	}else{
		if($tt_btc>($btc['avail']-$tt_tip)){
			echo "<script>alert ('".Error.Amounttip.floatval($btc['avail']-$tt_tip)."BTC');</script>";
			echo "<script language='javascript'>window.history.go(-1);</script>";
			exit();
		}
		
		//用户总额减少
		$sqq="update `user` set btc_sum=btc_sum-".(($tt_btc+$tt_tip)*Zero8())." where id=".$userid;
		$res=mysql_query($sqq);
		if($res){
			//写入提现记录
			$sqq2="insert into tixian(`userid`,tt_time1,tt_address,tt_btc,tt_tip,tt_status,urgent,tt_beizhu)values(".$userid.",".time().",'".$tt_address."',".($tt_btc*Zero8()).",".($tt_tip*Zero8()).",0,".$urgent.",'".$tt_beizhu."')";
			$res2=mysql_query($sqq2);
			////记录btc明细
			$sq5="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(2,".$userid.",'".username($userid)."',".($tt_btc*Zero8()).",".time().")";//提现
			$re5=mysql_query($sq5);
			$sq6="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(3,".$userid.",'".username($userid)."',".($tt_tip*Zero8()).",".time().")";//提现手续费
			$re6=mysql_query($sq6);

		}
	}

	

	
	if($op){
		echo "<script language='javascript'>window.location.href='/wallet/".$op.".html';</script>";
	}else{
		echo "<script language='javascript'>window.location.href='/wallet.html';</script>";
	}
	

	
}
if($_GET['repeal']){
	$sqq="select * from tixian where id=".$_GET['repeal']." and userid=".$_SESSION['id']." and tt_status=0";
	$res=mysql_query($sqq);
	$num=mysql_num_rows($res);
	if($num<1){
		echo "<script>alert ('".Error.Illegalrequest."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	$row=mysql_fetch_array($res);
	$sqq2="update `user` set btc_sum=btc_sum+".(($row['tt_btc']+$row['tt_tip']))." where id=".$row['userid'];
	$res2=mysql_query($sqq2);
	$sqq3="update tixian set tt_status=99 where id=".$_GET['repeal'];
	$res3=mysql_query($sqq3);
	////记录btc明细
	$sq5="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(6,".$row['userid'].",'".username($row['userid'])."',".($row['tt_btc']).",".time().")";//撤销提现
	$re5=mysql_query($sq5);
	$sq6="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(7,".$row['userid'].",'".username($row['userid'])."',".($row['tt_tip']).",".time().")";//撤销提现手续费
	$re6=mysql_query($sq6);
	echo "<script language='javascript'>window.location.href='/wallet/".$_GET['op'].".html';</script>";
}

$where=" where userid=".$userid;
$type="all";
if($_GET['type']){
	$type=$_GET['type'];
}

if($type=="deposit"){
	$where=$where." and type=1";
}elseif($type=="withdraw"){
	$where=$where." and type in(2,6)";
}elseif($type=="indeposit"){
	$where=$where." and type=13";
}elseif($type=="inwithdraw"){
	$where=$where." and type=12";
}elseif($type=="buy"){
	$where=$where." and type=4";
}elseif($type=="sell"){
	$where=$where." and type=5";
}elseif($type=="award"){
	$where=$where." and type=11";
}elseif($type=="fee"){
	$where=$where." and type in(3,7,8,9,10)";
}

$perNumber = 20; // 每页显示的记录数
$page = $_GET ['page']; // 获得当前的页面值
$count = mysql_query ( "select count(*) from logg ".$where ); // 获得记录总数

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs [0];

$totalPage = ceil ( $totalNumber / $perNumber ); // 计算出总页数
if (! isset ( $page )) {
	$page = 1;
} // 如果没有值,则赋值1
$startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录

// 根据前面的计算出开始的记录和记录数
$sq="select * from logg ".$where." order by id desc limit $startCount,$perNumber";
$re=mysql_query($sq);


$sqtt="select * from tixian where tt_status=0 and userid=".$userid;
$rett=mysql_query($sqtt);
$nutt=mysql_num_rows($rett);

?>



<style>
 
.panel {
    border: 1px solid #e7ecf0;
	background: #fff;
    padding: 15px;
}
</style>
<div class="container mainbody">
	<div class="panel">
		<div class="h3"><?php echo Available?>: <span class="link available"><?php echo sctonum($btc['avail'])<0? 0:sctonum($btc['avail']);?></span> BTC</div>
		<div class="wallet-view"><span class="s-text available"><?php echo Available2?>: <span><?php echo sctonum($btc['avail'])<0? 0:sctonum($btc['avail']);?></span> BTC</span><span class="s-text locked"><?php echo Frozenassets?>: <span><?php echo sctonum($btc['dj'])?></span> BTC</span><span class="s-text locked"><?php echo Unconfirmed?>: <span><?php echo sctonum($btc['unconfirmed'])?></span> BTC</span><span class="s-text total"><?php echo Total?>: <span><?php echo sctonum($btc['sum'])?></span> BTC</span></div>
	</div>
	<div class="tabs clear-float">
		<div class="tab get actived" id="receive"><?php echo Receive?></div>
		<div class="tab send " id="send"><?php echo Send?></div>
	</div>
	<div class="panel clear-float">
		<div class="btc-get" id="getid" style="display: block;">
			<div class="address">
				<div><?php echo Receiveaddress?>：</div>
				<?php while($row=mysql_fetch_array($res)){ ?>
				<div style="vertical-align: middle;">
				<span class="blue-text" ><a href="https://blockchain.info/zh-cn/address/<?php echo $row['address'];?>"><?php echo $row['address'];?></a></span>
				<a class="icon-qrcode" onclick="showContent('<?php echo $row['address']?>')"></a>
				<a href="javascript:;" hidefocus="none" class="btn btn-info btn-xs" style="margin:0 0 8px 7px;" id="btnCopy" onclick="clipboard('<?php echo $row['address']?>','btnCopy','<?php echo Copysuccess?>','btnCopy')"><?php echo Copybtn?></a><br/>
				</div>
				<?php }?><!-- <a class="icon-qrcode"></a> -->
			</div>
    		<div class="tips-panel"><?php echo Receivetip?></div>		
		</div>

		<div class="btc-send" id="sendid" style="display: none;">
			<div class="tips-panel"><?php echo Sendtip?></div>
			<form method="post" class="form-horizontal" action="">
				<div class="form-group"> 
					<label for="" class="control-label"><?php echo Address?> </label> <!--发送地址-->
					<input class="form-control" id="" name="tt_address" placeholder="<?php echo Address_placeholder?>" type="text"> 
				</div>
				<!-- <div class="form-group"> 
					<label for="" class="control-label  col-sm-2"><?php echo Urgent?> </label> 
					<div class="col-sm-4 ">
						<div class="checkbox">
							<label>
							<input class="" id="urgent"  name="urgent" type="checkbox" value="1"><?php echo Urgenttip?>
							</label>
						</div>
					</div> 
					<div class="col-md-6 beizhu" ><div  style="color:red;line-height:34px;"><?php echo Urgentalert.$jj_sxf.'BTC';?></div></div>
				</div> -->
				<div class="form-group"> 
					<label for="" class="control-label"><?php echo Amount?> </label> 
					<input class="form-control" id="amount"  name="tt_btc" type="text" placeholder="<?php echo Amount_placeholder.$max_tt."BTC";?>">
					<div class="beizhu" id="amount_error" style="display: none;color:red;line-height:34px;"><?php echo Amounttip.$max_tt."BTC"?></div>
				</div>
				
				<div class="form-group"> 
					<label for="" class="control-label"><?php echo Remark?> </label> 
					<input class="form-control"  name="tt_beizhu" type="text">
				</div>
				<input class="form-control"  name="tt_tip" id="tt_tip" value="<?php echo $tt_sxf?>" type="hidden">
				<div class="form-group"><button type="submit" disabled="disabled" id="publishBtn" class="btn btn-info"><?php echo Submit?></button></div>
			</form>
			<?php if($nutt>0){
				  while($ro=mysql_fetch_array ( $rett ) ){ ?>
					<div class="tixian_list">
						<div><?php echo Address?>：<?php echo $ro['tt_address'];?></div>
						<div><?php echo Created?>：<?php echo date('Y-m-d H:i:s',$ro['tt_time1']);?></div>
						<div><?php echo Amount?>：<?php echo floatval($ro['tt_btc']/Zero8());?></div>
						<div><?php if($ro['tt_status']==0){ ?> <a href="/wallet/<?php echo $ro['id'];?>/<?php echo $userid;?>/<?php echo $op;?>.html" style="width:100%" class="btn btn-info"><?php echo Repeal;?></a> <?php }?></div>
					</div>
				<?php }?>
			<?php } ?>
		</div>
	</div>
	<div class="panel-head"><?php echo Record?></div>
	<div class="panel transactions-panel">

		<div class="filter" id="trademenu">
			<a href="/wallet/<?php echo $op;?>/all.html"><span id="all" class="filter-item actived"><?php echo All?></span></a>
			<a href="/wallet/<?php echo $op;?>/deposit.html"><span id="deposit" class="filter-item"><?php echo Deposit?></span></a><!--充值-->
			<a href="/wallet/<?php echo $op;?>/withdraw.html"><span id="withdraw" class="filter-item"><?php echo Withdraw?></span></a><!--提现-->
			<a href="/wallet/<?php echo $op;?>/buy.html"><span id="buy" class="filter-item"><?php echo BuyBtn?></span></a><!--购买-->
			<a href="/wallet/<?php echo $op;?>/sell.html"><span id="sell" class="filter-item"><?php echo SellBtn?></span></a><!--出售-->
			<a href="/wallet/<?php echo $op;?>/indeposit.html"><span id="indeposit" class="filter-item"><?php echo Internaldeposit?></span></a><!--平台转入-->
			<a href="/wallet/<?php echo $op;?>/inwithdraw.html"><span id="inwithdraw" class="filter-item"><?php echo Internalwithdraw?></span></a><!--平台转出-->
			<a href="/wallet/<?php echo $op;?>/award.html"><span id="award" class="filter-item"><?php echo Award?></span></a><!--奖励-->
			<a href="/wallet/<?php echo $op;?>/fee.html"><span id="fee" class="filter-item"><?php echo Fee?></span></a><!--手续费-->
		</div>
		<?php if($totalNumber>0){
			  while($ro=mysql_fetch_array ( $re ) ){ ?>
				<div class="logg_list_item" style="">
					<div class="logg_num"><?php echo floatval($ro['num']/Zero8());?>BTC</div>
					<div class="logg_list_left">
						<div class="type"><?php echo loggType($ro['type']);?></div>
						<div class="time"><?php echo date('Y-m-d H:i:s',$ro['time']);?></div>
					</div>
					<div class="clear"></div>
				</div>
			<?php $i++; }?>

		
		<?php if($totalPage>1){?>
		<ul class="pagination">
			<?php
			if ($page != 1) { //页数不等于1
			?>
			<li><a href="/wallet/<?php echo $page - 1;?>/<?php echo $op?>/<?php echo $type?>.html">&laquo;</a></li>

			<?php
			}
			for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
			?>
			<li><a href="/wallet/<?php echo $i;?>/<?php echo $op?>/<?php echo $type?>.html"><?php echo $i ;?></a></li>
			<?php
			}
			if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
			?>
			<li><a href="/wallet/<?php echo $page + 1;?>/<?php echo $op?>/<?php echo $type?>.html">&raquo;</a></li>
			<?php
			} 

			mysql_close( $link );
			?>
		</ul>
		<?php }?>
		<?php }else{?>
		<div class="no-record">
			<span class="icon-address-no-record"></span><span><?php echo Norecord?></span>
		</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript" src="/js/ZeroClipboard.js"></script>
<script>

//交易记录
var menu=$("#trademenu");
var currentLink=menu.find(".actived");  
var currentId="<?php echo $type?>";
if(currentLink.attr("id")!=currentId){  
	currentLink.removeClass("actived");  
	menu.find("[id="+currentId+"]").addClass("actived");  
} 

var i=<?php echo $page;?>;
if(i==1){
	$(".pagination a:eq(0)").css("color", "red");
}else{
	$(".pagination a:eq(<?php echo $page;?>)").css("color", "red");
}

var op='<?php echo $op;?>';
if(op=='receive'){
	$("#send").removeClass("actived");
	$("#receive").addClass("actived");
	$("#getid").show();
	$("#sendid").hide();
}else if(op=='send'){
	$("#receive").removeClass("actived");
	$("#send").addClass("actived");
	$("#sendid").show();
	$("#getid").hide();
}

$("#receive").click(function(){
	$("#send").removeClass("actived");
	$("#receive").addClass("actived");
	$("#getid").show();
	$("#sendid").hide();
});
$("#send").click(function(){
	$("#receive").removeClass("actived");
	$("#send").addClass("actived");
	$("#sendid").show();
	$("#getid").hide();
});
$("#amount").on("input propertychange",function(){  
	if($(this).val()==""||$(this).val()<=0||$(this).val()>(<?php echo $btc['avail'];?>-$("#tt_tip").val())){  
		$("#amount_error").css("display","block");
		$("#amount").addClass("amount-error");
		$('#publishBtn').attr('disabled',"true");
	}else if(isNaN($(this).val())){
		$("#amount_error").css("display","block");
		$("#amount").addClass("amount-error");
		$('#publishBtn').attr('disabled',"true");
	}else{
		$("#amount_error").css("display","none");
		$("#amount").removeClass("amount-error");
		$('#publishBtn').removeAttr('disabled');
	}
});
function showContent(address){
	$.ajax({
		type:'post',
		url:'/qrcode.php',
		data : {'address' : address},
		dataType : 'json',

		success : function(data) {
			$(".dialog-msg").html("<image src='/"+data.msg+"'/>");
			$("#closeid").html("");
			$("#okid").html("<?php echo Gotit?>");
			$("#alertID").show();
			$("#tabindex0").show();

			$("#okid").click(function(){
				$("#alertID").hide();
				$("#tabindex0").hide();
			});
		},
		error : function(jqXHR) {
			alert("<?php echo Error?>：" + jqXHR.status);
		},
	});
}
/////实现一键复制
function clipboard(text, button, msg, parent) {
    if (window.clipboardData) {//如果是IE浏览器
        var copyBtn = document.getElementById(button);
        copyBtn.onclick = function() {
            window.clipboardData.setData('text', text);
            alert(msg);
        }
    } else {//非IE浏览器
        var clip = new ZeroClipboard.Client();//初始化一个剪切板对象
        clip.setHandCursor(true);//设置手型游标
        clip.setText(text);//设置待复制的文本内容
        clip.addEventListener("mouseUp", function(client) {//绑定mouseUp事件触发复制
            alert(msg);
        });
        clip.glue(button,parent);//调用ZeroClipboard.js的内置方法处理flash的位置的问题
    }
    return false;
}


// $("#urgent").click(function(){
//	 
//	 if($(this).is(':checked')){
//		$(".dialog-msg").html("<?php echo Urgentalert.$jj_sxf.'BTC';?>");
//		$("#closeid").html("");
//		$("#okid").html("<?php echo Gotit?>");
//		$("#tt_tip").val(<?php echo $tt_sxf+$jj_sxf;?>);
//		$("#amount").attr('placeholder',"<?php echo Amount_placeholder.floatval($btc['avail']-$tt_sxf-$jj_sxf).'BTC';?>");
//		$("#amount_error").html("<?php echo Amounttip.floatval($btc['avail']-$tt_sxf-$jj_sxf).'BTC'?>");
//		$("#alertID").show();
//		$("#tabindex0").show();
//
//		$("#okid").click(function(){
//			$("#alertID").hide();
//			$("#tabindex0").hide();
//		});
//	 }else{
//		 $("#tt_tip").val(<?php echo $tt_sxf;?>);
//		$("#amount").attr('placeholder',"<?php echo Amount_placeholder.floatval($btc['avail']-$tt_sxf).'BTC';?>");
//		$("#amount_error").html("<?php echo Amounttip.floatval($btc['avail']-$tt_sxf).'BTC'?>");
//	 }
// });
</script>
<?php
include("footer.php");?>