<?php
include("header.php");
include("include/checkSession.php");
$userid=$_SESSION['id'];
$id=$_GET['id'];//订单id
$sqq="select d.*,a.pay_type,a.info,a.userid from dingdan as d,advertise as a where a.id=d.advid and d.id=".$id;
$res=mysql_query($sqq);
$row=mysql_fetch_array($res);
if($row['sellid']!=$userid && $row['buyid']!=$userid){
	echo "<script>alert ('".Error.Illegalrequest."');</script>";
	echo "<script language='javascript'>window.location.href='/index.html';</script>";
	exit();
}

if($row['buyid']==$userid){
	$user_type="buyer";
	$hbid=$row['sellid'];
	mysql_query("update dingdan set msgbuy=0 where id=".$id);
}elseif($row['sellid']==$userid){
	$user_type="seller";
	$hbid=$row['buyid'];
	mysql_query("update dingdan set msgsell=0 where id=".$id);
}
$userRes=userInfoDetail($hbid);
//var_dump($userRes);

//dd_type:0未支付，1已支付，2收币完成
if($row['dd_type']==0&&$row['buyid']==$userid){
	$status=StatusB0;
	$statusTip=StatustipB0;
	$statusMessage=StatusMessageB0;
}
if($row['dd_type']==0&&$row['sellid']==$userid){
	$status=StatusS0;
	$statusTip=StatustipS0;
	$statusMessage=StatusMessageS0;
}

if($row['dd_type']==1&&$row['buyid']==$userid){
	$status=StatusB1;
	$statusTip=StatustipB1;
	$statusMessage=StatusMessageB1;
}
if($row['dd_type']==1&&$row['sellid']==$userid){
	$status=StatusS1;
	$statusTip=StatustipS1;
	$statusMessage=StatusMessageS1;
}

if($row['dd_type']==2&&$row['buyid']==$userid){
	$status=StatusB2;
	$statusTip=StatustipB2;
	$statusMessage=StatusMessageB2;
}
if($row['dd_type']==2&&$row['sellid']==$userid){
	$status=StatusS2;
	$statusTip=StatustipS2;
	$statusMessage=StatusMessageS2;
}

if($row['dd_type']==3&&$row['buyid']==$userid){
	$status=StatusB3;
	$statusTip=StatustipB3;
}
if($row['dd_type']==3&&$row['sellid']==$userid){
	$status=StatusS3;
	$statusTip=StatustipS3;
}

if($row['dd_type']==4&&$row['buyid']==$userid){
	$status=StatusB4;
	$statusTip=StatustipB4;
}
if($row['dd_type']==4&&$row['sellid']==$userid){
	$status=StatusS4;
	$statusTip=StatustipS4;
}
if($row['dd_type']==5){
	$status=Status5;
	$statusTip=Statustip5;
}
if($row['dd_type']==6){
	$status=Status6;
	$statusTip=Statustip6;
}

$sqq2="select * from chat where dd_id=".$row['id']." order by id asc";
$res2=mysql_query($sqq2);
$num2=mysql_num_rows($res2);

if($_GET['type']!=""){
	$ddid=$_GET['id'];

	$sqd="select * from dingdan where id=".$ddid;
	$red=mysql_query($sqd);
	$rod=mysql_fetch_array($red);
	//卖家id   $rod['sellid']
	if($_GET['type']==9){//卖家重新开启交易
		if($rod['dd_type']!=3 || $rod['sellid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		//修改订单状态
		$sqq="update dingdan set dd_type=0,dd_time=".time().",msgbuy=1,msgsell=1 where id=".$ddid;
		$ree=mysql_query($sqq);
		//系统消息
		$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','restart',".time().")";
		$re2=mysql_query($sq2);
		//卖家BTC托管
		$sq3="update `user` set btc_tuoguan=btc_tuoguan+".($rod['seller_real_pay']*Zero8())." where id=".$rod['sellid']; 
		$re3=mysql_query($sq3);
		/////////////////////////////
	
	}elseif($_GET['type']==1){//付款
		if($rod['dd_type']!=0 || $rod['buyid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		//修改订单状态
		$sqq="update dingdan set dd_type=".$_GET['type'].",fk_time=".time().",msgbuy=1,msgsell=1 where id=".$ddid;
		$ree=mysql_query($sqq);
		//系统消息
		$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','paid',".time().")";
		$re2=mysql_query($sq2);
		/////////////////////////////
	
	}elseif($_GET['type']==4){//canncelled 买家取消
		if(($rod['dd_type']!=0&&$rod['dd_type']!=1) || $rod['buyid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		//修改订单状态
		$sqq="update dingdan set dd_type=".$_GET['type'].",msgbuy=1,msgsell=1 where id=".$ddid;
		$ree=mysql_query($sqq);
		//系统消息
		$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','canncelled',".time().")";
		$re2=mysql_query($sq2);
		//买家取消交易，释放托管的比特币，释放订单中记录的卖家实付数额
		$sq3="update `user` set btc_tuoguan=btc_tuoguan-".($rod['seller_real_pay']*Zero8())." where id=".$rod['sellid'];
		$re3=mysql_query($sq3);
		//////////////////////////////////////
	}elseif($_GET['type']==2){//released 放币
		if($rod['dd_type']!=1 || $rod['sellid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		//修改订单状态		
		$sqq="update dingdan set dd_type=".$_GET['type'].",release_time=".time().",msgbuy=1,msgsell=1 where id=".$ddid;
		$ree=mysql_query($sqq);
		if($ree){
			//系统消息
			$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$ddid.",0,'system','released',".time().")";
			$re2=mysql_query($sq2);

			/////卖家释放比特币，释放订单中记录的卖家实付数额
			/////将比特币发送给买家，买家账户增加订单记录的买家实收数额
			//更新卖家账户
			$sq3="update `user` set btc_tuoguan=btc_tuoguan-".($rod['seller_real_pay']*Zero8()).",btc_sum=btc_sum-".($rod['seller_real_pay']*Zero8())." where id=".$rod['sellid']; 
			$re3=mysql_query($sq3);
			//更新买家账户
			$sq4="update `user` set btc_sum=btc_sum+".($rod['buyer_real_gain']*Zero8())." where id=".$rod['buyid'];
			$re4=mysql_query($sq4);

			if($rod['sellid']==$row['userid']){//卖家是广告主，收取卖家手续费。将奖励给广告主上级
				$trade_fee=$rod['trade_fee']*Zero8();//卖家交易手续费
		
				if($trade_fee>0){//卖家卖出手续费 ,手续费大于0
					$sq7="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(9,".$rod['sellid'].",'".username($rod['sellid'])."',".$trade_fee.",".time().")";//卖家
					$re7=mysql_query($sq7);

					$tj_userid=tj_userid($rod['sellid']);//广告主上级用户id
					if(jiangli_fee()>0&&$tj_userid){//如果上级奖励>0切广告主有上级
						$jiangli_btc=floor($trade_fee*jiangli_fee()/100);
						mysql_query("update `user` set btc_sum=btc_sum+".$jiangli_btc." where id=".$tj_userid); //账户总额增加
						////记录btc明细，奖励
						mysql_query("insert into logg(`type`,`userid`,`username`,`useridb`,`usernameb`,`num`,`time`)values(11,".$tj_userid.",'".username($tj_userid)."',".$rod['sellid'].",'".username($rod['sellid'])."',".$jiangli_btc.",".time().")");
						
					}
				}
			}elseif($rod['buyid']==$row['userid']){//买家是广告主，收取买家手续费
				$trade_fee=$rod['trade_fee']*Zero8();;//交易手续费
				
				if($trade_fee>0){  //买家购买手续费，手续费大于0
					$sq8="insert into logg(`type`,`userid`,`username`,`num`,`time`)values(10,".$rod['buyid'].",'".username($rod['buyid'])."',".$trade_fee.",".time().")";//买家
					$re8=mysql_query($sq8);
					$tj_userid=tj_userid($rod['buyid']);//广告主上级用户id
					if(jiangli_fee()>0&&$tj_userid){//如果上级奖励>0切广告主有上级
						$jiangli_btc=floor($trade_fee*jiangli_fee()/100);
						mysql_query("update `user` set btc_sum=btc_sum+".$jiangli_btc." where id=".$tj_userid); //账户总额增加
						////记录btc明细，奖励
						mysql_query("insert into logg(`type`,`userid`,`username`,`useridb`,`usernameb`,`num`,`time`)values(11,".$tj_userid.",'".username($tj_userid)."',".$rod['buyid'].",'".username($rod['buyid'])."',".$jiangli_btc.",".time().")");
						
					}
				}
			}

			////记录btc明细
			$sq5="insert into logg(`type`,`userid`,`username`,`useridb`,`usernameb`,`num`,`time`)values(4,".$rod['buyid'].",'".username($rod['buyid'])."',".$rod['sellid'].",'".username($rod['sellid'])."',".($rod['btc_num']*Zero8()).",".time().")";//买家
			$re5=mysql_query($sq5);

			$sq6="insert into logg(`type`,`userid`,`username`,`useridb`,`usernameb`,`num`,`time`)values(5,".$rod['sellid'].",'".username($rod['sellid'])."',".$rod['buyid'].",'".username($rod['buyid'])."',".($rod['btc_num']*Zero8()).",".time().")";//卖家
			$re6=mysql_query($sq6);
		}
		//echo $ss;exit;
	}
	echo "<script language='javascript'>window.location.href='/ddContent/".$ddid.".html';</script>";
	
}
if($_POST['u']){		//评价
	if($_POST['u']=='s'){
		if($row['sellid']!=$_SESSION['id'] ){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		if($_POST{'pjid'}){
			mysql_query("update pingjia set feedback='".$_POST['feedback']."' where id=".$_POST['pjid']);
		}else{
			$userid=$row['buyid'];
			mysql_query("update dingdan set spingjia=1 where id=".$id);
			mysql_query("insert into pingjia(`jiaoyi_id`,`userid`,`pingjiaid`,`feedback`,`pj_time`)values(".$id.",".$userid.",".$_SESSION['id'].",'".$_POST['feedback']."',".time().")");
		}
		$sysmsg="sell".$_POST['feedback'];
		
	}elseif($_POST['u']=='b'){
		if($row['buyid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		if($_POST{'pjid'}){
			mysql_query("update pingjia set feedback='".$_POST['feedback']."' where id=".$_POST['pjid']);
		}else{
			$userid=$row['sellid'];
			mysql_query("update dingdan set bpingjia=1 where id=".$id);
			mysql_query("insert into pingjia(`jiaoyi_id`,`userid`,`pingjiaid`,`feedback`,`pj_time`)values(".$id.",".$userid.",".$_SESSION['id'].",'".$_POST['feedback']."',".time().")");
		}
		$sysmsg="buy".$_POST['feedback'];
	}
	//系统消息
	$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$id.",0,'system','".$sysmsg."',".time().")";
	$re2=mysql_query($sq2);
	echo "<script language='javascript'>window.location.href='/ddContent/".$id.".html';</script>";
}

if($_POST['reason']){		//申诉
	$reason=$_POST['reason'];
	$content=$_POST['content'];
	$user_type=$_POST['user_type'];
	$sqq="insert into dispute(userid,user_type,dd_id,reason,content,time)values(".$userid.",'".$user_type."',".$id.",'".$reason."','".$content."',".time().")";
	$res=mysql_query($sqq);

	//系统消息
	if($user_type=="buyer"){
		if($row['buyid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		$msg="disputeB";
		mysql_query("update dingdan set bdispute=1,msgbuy=1,msgsell=1 where id=".$id);
	}elseif($user_type=="seller"){
		if($row['sellid']!=$_SESSION['id']){
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/index.html';</script>";
			exit();
		}
		$msg="disputeS";
		mysql_query("update dingdan set sdispute=1,msgbuy=1,msgsell=1 where id=".$id);
	}
	$sq2="insert into chat(`dd_id`,`userid`,`user_type`,`message`,`time`)values(".$id.",0,'system','".$msg."',".time().")";
	$re2=mysql_query($sq2);
	echo "<script language='javascript'>window.location.href='/ddContent/".$id.".html';</script>";
}

?>

<div class="container mainbody">
	<div class="status-text-b">
		<span class="stage-text"><?php echo $status?></span>
		<span class="v-line"></span>
		<span class="status-decs"><?php echo $statusTip?></span>
	</div>
	<div class="order-info">
		<span class="item-name"><?php echo Tradeprice?>: <?php echo floatval($row['price'])?> CNY</span>
		<span class="item-name"> <?php echo Tradequantity?>: <?php echo floatval($row['btc_num']);?> BTC</span>
		<span class="item-name"><?php echo Tradeamount?>: <?php echo floatval($row['money']);?> CNY</span>
		<div class="clear"></div>
	</div>

	<div class="detail-left">
		<div class="chat-cont">
			<div class="chat-tab-cont clear-float">
				<a class="tab col-50 active" id="achat"><?php echo Message?></a>
				<a class="tab col-50" id="auserinfo"><?php if($row['buyid']==$userid){echo Sellerinfo;}elseif($row['sellid']==$userid){echo Buyerinfo;} ?></a>
			</div>
			<div class="tab-chat" id="chat">
				<div class="chating" id="chatres">
				<?php while($row2=mysql_fetch_array($res2)){
					if($row2['user_type']=='system'){
					?>
					<div class="chat sys"><div class="title"><?php echo SysMesTitle($row2['message']);?></div><div class="content"><?php echo SysMesContent($row2['message']);?></div><span class="time"><?php echo date('Y-m-d H:i:s',$row2['time']);?></span></div>
					<?php
					}elseif($row2['userid']==$userid){
					?>
					<div class="chat ta-r"><span class="chat-message"><?php echo $row2['message']?><span class="time"><?php echo date('Y-m-d H:i:s',$row2['time']);?></span></span><img class="user-logo" src="<?php echo headimg($row2['userid'])?> " alt=""></div>
					<?php
					}else{
					?>
					<div class="chat ta-l"><img class="user-logo" src="<?php echo headimg($row2['userid'])?>" alt=""><span class="chat-message"><?php echo $row2['message']?><span class="time"><?php echo date('Y-m-d H:i:s',$row2['time']);?></span></span></div>
					<?php
					}
				}?>
				</div>
				<input value="<?php echo $num2?>" type="hidden" id="chatNum"/>
				<div class="btn-cont">
					<div class="add-pic">
						<form id='myupload' action='/fileUplode.php' method='post' enctype='multipart/form-data'>
							<input type="hidden" name="ddid" value="<?php echo $id?>">
							<input type="hidden" name="userid" value="<?php echo $userid?>">
							<input type="hidden" name="user_type" value="<?php echo $user_type?>">
							<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" class="img-input" id="chatimgid" name="chatimg">
						</form>
					</div>
					<input class="message-input" type="text" id="msg" name="" value="" placeholder="<?php echo Messageplaceholder?>">
					<a type="SELL" stage="PAID_CLOSED" id="send" class="icon-send send"></a>
				</div>
			</div>
			<div class="tab-fellow-info" id="userinfo" style="display: none">
				<div>
					<img class="user-head" src="<?php echo headimg($hbid);?>">
					<a href="/userInfo/<?php echo $hbid;?>.html" class="user-name"><?php echo username($hbid);?></a>
				</div>
				<div class="user-info">
					<div class="info-item"><?php echo Volume."：".btcShow($userRes['btc']);?>BTC </div>
					<div class="info-item"><?php echo Confirmedtrades."：".$userRes['jiaoyi']?></div>
					<div class="info-item"><?php echo Rating."：".$userRes['pingjia']?>%</div>
					<div class="info-item"><?php echo Fristtimetrader."：".$userRes['first']?></div>
				</div>
				<!--<div class="user-info">
					<div class="info-item"> 语言: zh-TW</div>
					<div class="info-item"> 电子邮箱: 已验证</div>
					<div class="info-item"> 电话号码: 已验证</div>
					<div class="info-item"> 实名认证: 已验证</div>
					<div class="info-item"> 信任: 17</div>
					<div class="info-item"> 屏蔽: none</div>
				</div>-->

			</div>
		</div>
	</div>
	<div class="detail-right">
		<div class="form-title">
			<span class="form-name"><?php echo Tradeop?></span>
		</div>
		<div class="line mt-20 mb-10"></div>
		<div class="font-16"><?php echo TradeID?><p class="p"><?php echo $id?></p></div>
		
		<div class="font-16"><?php echo Partner?><p class="p"><?php echo username($hbid);?></p></div>
		
		<div class="font-16"><?php echo payMethod?><p class="p"><?php echo payType($row['pay_type']);?></p></div>
		
		<div class="font-16"><?php echo TradeMessage?></div>
		<p class="p mb-10 has-height"><?php echo $row['info']?></p>
		<?php if($row['dd_type']==0||$row['dd_type']==1||$row['dd_type']==2){?>
		<div class="font-16" style="display: block"><?php echo TIPS?></div>
		<?php }?>
		<p class="p tips-buyer-1" style="display: block"><?php echo $statusMessage?></p>

		<?php if($row['dd_type']==0&&$row['buyid']==$userid){?> <!--0且是买家-->
		<div class="btn-cont" style="display: block">
			<a id="btnPay" class="btn btn-primary"><?php echo Markascomplete?></a>
			<a id="btnCancel" class="btn btn-default"><?php echo Cancel?></a>
		</div>
		<?php }?>
		<?php if($row['dd_type']==1&&$row['buyid']==$userid){?><!--1且是买家-->
		<div class="btn-cont" style="display: block">
			<a id="btnPayStage" disabled="true" class="btn btn-default"><?php echo Paid?></a>
			<a id="btnCancel" class="btn btn-default"><?php echo Cancel?></a>
		</div>
		<?php }?>
		<?php if($row['dd_type']==1&&$row['sellid']==$userid){?><!--1且是卖家-->
		<div class="btn-cont" style="display: block">
			<a id="btnRelease" class="btn btn-primary"><?php echo Releasebtc?></a>
		</div>
		<?php }?>
		<?php if($row['dd_type']==3&&$row['sellid']==$userid){?><!--3且是卖家,逾期关闭,从新开启-->
		<div class="btn-cont" style="display: block">
			<a id="btnRestart" class="btn btn-primary"><?php echo Restarttrade?></a>
		</div>
		<?php }?>
		<!--交易双方评价-->
		<form method="post" action="">
		<?php if($row['dd_type']==2&&$row['sellid']==$userid){
				$sqp="select feedback,id from pingjia where jiaoyi_id=".$id." and pingjiaid=".$userid;
				$rep=mysql_query($sqp);
				$rop=mysql_fetch_array($rep);
		?><!--2且是卖家-->
		<div class="comment-cont" style="display: block">
			<div class="comment"><?php echo Review1?><strong><?php echo username($row['buyid']);?></strong><?php echo Review2?></div>
			<div class="radio-cont">
				<div class="fl"><input type="radio" <?php if($rop['feedback']=='POSITIVE'){?> checked="checked" <?php } ?> name="feedback" value="POSITIVE" id="POSITIVE"><label for="POSITIVE"><?php echo Positive?></label></div>
				<div class="fm"><input type="radio" <?php if($rop['feedback']=='NEUTRAL'){?> checked="checked" <?php } ?> name="feedback" value="NEUTRAL" id="NEUTRAL"><label for="NEUTRAL"><?php echo Neutral?></label></div>
				<div class="fr"><input type="radio" <?php if($rop['feedback']=='NEGATIVE'){?> checked="checked" <?php } ?> name="feedback" value="NEGATIVE" id="NEGATIVE"><label for="NEGATIVE"><?php echo Negative?></label></div>
			</div>
			<input type="hidden" name="u" value="s" />
			<input type="hidden" name="pjid" value="<?php echo $rop['id']?>">
			<?php if($rop['id']){?>
			<button class="btn btn-primary" type="submit" id="btnSComment"><?php echo EditReview?></button>
			<?php }else{ ?>
			<button class="btn btn-primary" type="submit" id="btnSComment"><?php echo Review?></button>
			<?php } ?>
		</div>
		<?php }?>
		<?php if($row['dd_type']==2&&$row['buyid']==$userid){
				$sqp="select feedback,id from pingjia where jiaoyi_id=".$id." and pingjiaid=".$userid;
				$rep=mysql_query($sqp);
				$rop=mysql_fetch_array($rep);
		?><!--2且是买家-->
		<div class="comment-cont" style="display: block">
			<div class="comment"><?php echo Review1?><strong><?php echo username($row['sellid']);?></strong><?php echo Review2?></div>
			<div class="radio-cont">
				<div class="fl"><input type="radio" <?php if($rop['feedback']=='POSITIVE'){?> checked="checked" <?php } ?> name="feedback" value="POSITIVE" id="POSITIVE"><label for="POSITIVE"><?php echo Positive?></label></div>
				<div class="fm"><input type="radio" <?php if($rop['feedback']=='NEUTRAL'){?> checked="checked" <?php } ?> name="feedback" value="NEUTRAL" id="NEUTRAL"><label for="NEUTRAL"><?php echo Neutral?></label></div>
				<div class="fr"><input type="radio" <?php if($rop['feedback']=='NEGATIVE'){?> checked="checked" <?php } ?> name="feedback" value="NEGATIVE" id="NEGATIVE"><label for="NEGATIVE"><?php echo Negative?></label></div>
			</div>
			<input type="hidden" name="u" value="b" />
			<input type="hidden" name="pjid" value="<?php echo $rop['id']?>">
			<?php if($rop['id']){?>
			<button class="btn btn-primary" type="submit" id="btnSComment"><?php echo EditReview?></button>
			<?php }else{ ?>
			<button class="btn btn-primary" type="submit" id="btnSComment"><?php echo Review?></button>
			<?php } ?>
		</div>
		<?php }?>
		</form>
		<!--交易双方评价-->

		<!--  申诉 -->
		<?php if($row['dd_type']==1 && ( ($row['buyid']==$userid&&$row['bdispute']==0) || ($row['sellid']==$userid&&$row['sdispute']==0))){?>   <!--  1 付款后显示 -->
		<div class="comment-cont" style="display:block;">
			<div class="comment">
				<div class="news-title blue-text"><?php echo Disputetitle?></div>
				<div class="news-text clear-float ">
					<div class="p">
						<p> <?php echo Disputetip?> </p>
						<!-- <a id="btnTipOffNext" class="btn tip-off"><?php echo Dispute?></a> -->
					</div>
					<div class="p">
					<form action="" method="post">
						<div><?php echo Reasondispute?></div>
						<input type="hidden" name="user_type" value="<?php echo $user_type?>"/>
						<select name="reason" class="tipoff-select mb-10">
							<?php if($row['sellid']==$userid){ ?>	<!--  1 我是卖家 -->
							<option value="Disputesell1"><?php echo Disputesell1?></option>
							<option value="Disputesell2"><?php echo Disputesell2?></option>
							<option value="Disputesell3"><?php echo Disputesell3?></option>
							<?php }elseif($row['buyid']==$userid){ ?>	<!--  1 我是买家 -->
							<option value="Disputebuy1"><?php echo Disputebuy1?></option>
							<option value="Disputebuy2"><?php echo Disputebuy2?></option>
							<?php }?>
							
						</select>
						<textarea name="content" class="textarea" placeholder="<?php echo Disputetextarea?>"></textarea>
						<!-- <div><a class="btn-add-pic">添加图片</a><span class="pic-placeholder">接受PNG或JPEG,最多选择4张图片</span></div> -->
						<button id="btnTipOff" type="submit" class="btn btn-primary" style="float:right;margin-right:8px;"><?php echo Dispute?></button>
					</form>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
</div>

<script type="text/javascript" language="javascript">
$(window).load(function(){
	

	$("#btnPay").click(function(){//标记为已付款
		$(".dialog-msg").html("<?php echo Ensurepay?>");
		$("#closeid").html("<?php echo Close?>");
		$("#okid").html("<?php echo OK?>");
		$("#alertID").show();
		$("#tabindex0").show();

		$("#closeid").click(function(){
			$("#alertID").hide();
			$("#tabindex0").hide();
		});
		$("#okid").click(function(){
			location.href="/ddContent/1/<?php echo $id?>.html";
		});
	});

	$("#btnCancel").click(function(){//买家取消btnCancel
		$(".dialog-msg").html("<?php echo Cancelconfirm?>");
		$("#closeid").html("<?php echo Close?>");
		$("#okid").html("<?php echo OK?>");
		$("#alertID").show();
		$("#tabindex0").show();

		$("#closeid").click(function(){
			$("#alertID").hide();
			$("#tabindex0").hide();
		});
		$("#okid").click(function(){
			location.href="/ddContent/4/<?php echo $id?>.html";
		});
	});
	$("#btnRelease").click(function(){//卖家释放比特币Releaseconfirm
		$(".dialog-msg").html("<?php echo Releaseconfirm?>");
		$("#closeid").html("<?php echo Close?>");
		$("#okid").html("<?php echo OK?>");
		$("#alertID").show();
		$("#tabindex0").show();

		$("#closeid").click(function(){
			$("#alertID").hide();
			$("#tabindex0").hide();
		});
		$("#okid").click(function(){
			location.href="/ddContent/2/<?php echo $id?>.html";
		});
	});

	$("#btnRestart").click(function(){//卖家重新开启交易
		$(".dialog-msg").html("<?php echo Restartconfirm?>");
		$("#closeid").html("<?php echo Close?>");
		$("#okid").html("<?php echo OK?>");
		$("#alertID").show();
		$("#tabindex0").show();

		$("#closeid").click(function(){
			$("#alertID").hide();
			$("#tabindex0").hide();
		});
		$("#okid").click(function(){
			location.href="/ddContent/9/<?php echo $id?>.html";
		});
	});
	
	var userid = <?php echo $userid?>;
	var ddid = <?php echo $id?>;
	var user_type = "<?php echo $user_type?>";

	$("#send").click(function() {
		var msg= $("#msg").val();
		$("#msg").val('');
		$.ajax({
			type : 'POST',
			url : '/chat.php',
			data : {'userid' : userid,'user_type' : user_type,'ddid' : ddid,'msg' : msg},
			dataType : 'json',

			success : function(data) {
			},
			error : function(jqXHR) {
				alert("发生错误：" + jqXHR.status);
			},
		});
	})

	$('#msg').bind('keypress',function(event){
		if(event.keyCode == "13"){
		    var msg= $("#msg").val();
			$("#msg").val('');
			$.ajax({
				type : 'POST',
				url : '/chat.php',
				data : {'userid' : userid,'user_type' : user_type,'ddid' : ddid,'msg' : msg},
				dataType : 'json',

				success : function(data) {
				},
				error : function(jqXHR) {
					alert("发生错误：" + jqXHR.status);
				},
			});
        }
    });
	$('#chatres').scrollTop( $('#chatres')[0].scrollHeight );
	setInterval("startRequest1()", 1000);  
});

function startRequest1() {  
	var userid = <?php echo $userid?>;
	var ddid = <?php echo $id?>;
	var chatnum = $("#chatNum").val();
	$.ajax({  
		url: "/chatres.php",  
		type: 'POST', 
		dataType : 'json',
		data: {  
			'userid': userid,
			'ddid': ddid,
			'chatnum': chatnum,
		},  
		success: function (data) {  
			if(data.status==true){
				$("#chatres").html(data.msg); 
				$("#chatNum").val(data.num); 
				$('#chatres').scrollTop( $('#chatres')[0].scrollHeight );
			}
		},  
		error : function(jqXHR) {
		},
	});  
} 
$('#chatimgid').unbind().change(function(){

	$("#myupload").ajaxSubmit({ 
		dataType:  'json',
		success: function(data){
			if(data.success){
				//$("#textimg")[0].src=data.msg;
			}else{
				$(".dialog-msg").html(data.msg);
				$("#closeid").html("");
				$("#okid").html("<?php echo Gotit?>");
				$("#alertID").show();
				$("#tabindex0").show();

				$("#okid").click(function(){
					$("#alertID").hide();
					$("#tabindex0").hide();
				});
			}
		  
		  //$("#aa").show();
	  }
	});
});
</script>

<script>


$("#achat").click(function(){
	$("#auserinfo").removeClass("active");
	$("#achat").addClass("active");
	$("#chat").show();
	$("#userinfo").hide();
});
$("#auserinfo").click(function(){
	$("#achat").removeClass("active");
	$("#auserinfo").addClass("active");
	$("#userinfo").show();
	$("#chat").hide();
});

</script>
<?php
include("footer.php");?>