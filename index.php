<?php
include("header.php");

$sq="select * from advertise where is_show=1 and ad_type='sell'";
$re=mysql_query($sq);
while($ro=mysql_fetch_array($re)){
	$btc=btcDetail($ro['userid']);
	//确定价格
	$myprice=ADpriceNow($ro['id']);
	
	$ree=mysql_query("update advertise set price=".$myprice." where id=".$ro['id']);
	//扣除手续费后的可用
	$fee=seller_trade($ro['userid']);//卖家交易手续费
	$avail=floor($btc['avail']*Zero8()*(1-$fee/100))/Zero8();
	if($avail>=set1()){
		$showId[]=$ro['id'];
	}
}
$idStr=implode(',',$showId);
//////////////////////


$sql="select * from advertise where is_show=1 and ad_type='sell' and id in(".$idStr.") order by price asc limit 6";
$res=mysql_query($sql);
$num=mysql_num_rows($res);

//$sql2="select a.* from advertise as a,user as u where a.userid=u.id and u.btc_sum>".set1()." and a.is_show=1 and a.ad_type='buy' order by a.margin_price desc limit 6";
$sql2="select a.* from advertise as a,user as u where a.userid=u.id and a.is_show=1 and a.ad_type='buy' order by a.margin_price desc limit 6";
$res2=mysql_query($sql2);
$num2=mysql_num_rows($res2);

?>


<div class="container mainbody">
	
	<h3><?php echo BuyNav?></h3>
	
		<?php if($num>0){
			while($row=mysql_fetch_array ( $res ) ){
				$btc=btcDetail($row['userid']);
			
				$fee=seller_trade($row['userid']);//卖家交易手续费
				//扣除手续费后的可用
				$avail=floor($btc['avail']*Zero8()*(1-$fee/100))/Zero8();
				
				if($avail>=set1()){
					//确定价格
					$myprice=ADpriceNow($row['id']);

					//确定最小限额
					if($avail*$myprice>$row['min_amount']){
						$min=$row['min_amount'];
					}else{
						$min=intval($avail*$myprice);
					}
					//确定最大限额
					if($avail*$myprice>$row['max_amount']){
						$max=$row['max_amount'];
					}else{
						$max=intval($avail*$myprice);
					}
					
		?>
			<div class="buy_sell_list">
				<div class="list_user"><a href="/userInfo/<?php echo $row['userid'];?>.html"><img class="user_head" src="<?php echo headimg($row['userid']);?>"><?php echo username($row['userid']).userInfo($row['userid']);?></a></div>
				<div class="list_left">
					<div class="list_pay"><?php echo payMethod;?>:<a href="/buy_bitcoins/<?php echo $row['pay_type'];?>.html"><?php echo payType($row['pay_type']);?></a></div>
					<div class="btc_price"><?php echo Price?>:<?php echo $myprice;?>CNY</div>
					<div class="list_min_max"><?php echo Limits?>:<?php echo $min." - ".$max;?>CNY</div>
				</div>
				<div class="list_btn"><?php if($row['userid']==$_SESSION['id']){?><a class="btn btn-info" href="/advertise/<?php echo $row['id'];?>.html"><?php echo EditBtn?></a><?php }else{?><a class="btn btn-info" href="/buy_contennt/<?php echo $row['id'];?>.html"><?php echo BuyBtn?></a><?php }?></div>
			</div>
		<?php } }?>
			<?php }else{?>
			<div class="text-center"><?php echo NoAdv?></div>
			<?php }?>


	<div class="pull-right"><a href="/buy_bitcoins.html"><?php echo More?>-><?php echo BuyNav?></a></div>
	<div style="clear: both"><!-- --></div>


	<h3><?php echo SellNav?></h3>
		
	<?php if($num2>0){
		while($row2=mysql_fetch_array ( $res2 ) ){?>
			<div class="buy_sell_list">
				<div class="list_user"><a href="/userInfo/<?php echo $row2['userid'];?>.html"><img class="user_head" src="<?php echo headimg($row2['userid']);?>"><?php echo username($row2['userid']).userInfo($row2['userid']);?></a></div>
				<div class="list_left">
					<div class="list_pay"><?php echo payMethod;?>:<a href="/sell_bitcoins/<?php echo $row2['pay_type'];?>.html"><?php echo payType($row2['pay_type']);?></a></div>
					<div class="btc_price"><?php echo Price?>:<?php echo round(($row2['margin_price']/100+1)*$priceArray['CNY']['15m'],2);?>CNY</div>
					<div class="list_min_max"><?php echo Limits?>:<?php echo $row2['min_amount']." - ".$row2['max_amount']."CNY";?></div>
				</div>
				<div class="list_btn"><?php if($row2['userid']==$_SESSION['id']){?><a class="btn btn-info" href="/advertise/<?php echo $row2['id'];?>.html"><?php echo EditBtn?></a><?php }else{?><a class="btn btn-info" href="/sell_contennt/<?php echo $row2['id'];?>.html"><?php echo SellBtn?></a><?php }?></div>
			</div>
		<?php }?>
	<?php }else{?>
	<div class="text-center"><?php echo NoAdv?></div>
	<?php }?>


	<div class="pull-right"><a href="/sell_bitcoins.html"><?php echo More?>-><?php echo SellNav?></a></div>
	<div style="clear: both"><!-- --></div>

</div>
<?php
include("footer.php");?>