<?php
include("header.php");

///////////////////////筛选出可以显示的广告
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

$where=" where id in(".$idStr.") ";
$pay_type=$_GET['pay_type'];

if($pay_type!=""){
	$where = $where." and pay_type='".trim($pay_type)."'";
}

$perNumber = 20; // 每页显示的记录数
$page = $_GET ['page']; // 获得当前的页面值
$count = mysql_query ( "select count(*) from advertise ".$where ); // 获得记录总数

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs [0];

$totalPage = ceil ( $totalNumber / $perNumber ); // 计算出总页数
if (! isset ( $page )) {
	$page = 1;
} // 如果没有值,则赋值1
$startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录

// 根据前面的计算出开始的记录和记录数
$sql="select * from advertise ".$where." order by price asc limit $startCount,$perNumber";
$res=mysql_query($sql);

//echo $sql;


?>

<div class="container mainbody">
	<div class="panel panel-info">
		<div class="panel-heading"><?php echo BuyNav?>	
			<span style="padding:0 10px;color:#F00;"><?php echo Advertisements?>：<?php echo $totalNumber;?></span>
			<span style="margin:-20px 0 20px 20px;text-align:center;font-size:12px;color:#666"><?php echo QueryCondition?>：<span style="color:#428bca;"><?php echo payType($pay_type);?></span></span>
			
		</div>
		<div class="panel-body" style="background:#e3f2fd;padding:20px 15px;border:1px solid #ddd;border-top:0">
			<div class="" style="width:100%;">
				<?php echo payMethod;?>：
				<a href="/buy_bitcoins.html"><?php echo All?></a>
				| <a href="/buy_bitcoins/bank.html"><?php echo Bank?></a>
				| <a href="/buy_bitcoins/zfb.html"><?php echo Zfb?></a>
				| <a href="/buy_bitcoins/weixin.html"><?php echo Weixin?></a>
			</div>
		</div>
	</div>


		<?php if($totalNumber>0){
			while($row=mysql_fetch_array ( $res ) ){
				$btc=btcDetail($row['userid']);
				//确定价格
				$myprice=ADpriceNow($row['id']);

				$fee=seller_trade($row['userid']);//卖家交易手续费
				//扣除手续费后的可用
				$avail=floor($btc['avail']*Zero8()*(1-$fee/100))/Zero8();
				if($avail>=set1()){
					
					//确定最大限额
					if($avail*$myprice>$row['max_amount']){
						$max=$row['max_amount'];
					}else{
						$max=intval($avail*$myprice);
					}
					//确定最小限额
					if($avail*$myprice>$row['min_amount']){
						$min=$row['min_amount'];
					}else{
						$min=intval($avail*$myprice);
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
			<div colspan="6" class="text-center"><?php echo NoAdv?></div>
			<?php }?>

	
	<?php if($totalPage>1){?>
	<ul class="pagination">
		<?php
		if ($page != 1) { //页数不等于1
		?>
		<li><a href="/buy_bitcoins/<?php echo $page - 1;  if($pay_type){ echo "/".$pay_type; } ?>.html">&laquo;</a></li>

		<?php
		}
		for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
		?>
		<li><a href="/buy_bitcoins/<?php echo $i; if($pay_type){ echo "/".$pay_type; } ?>.html"><?php echo $i ;?></a></li>
		<?php
		}
		if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
		?>
		<li><a href="/buy_bitcoins/<?php echo $page + 1; if($pay_type){ echo "/".$pay_type; } ?>.html">&raquo;</a></li>
		<?php
		} 

		mysql_close( $link );
		?>
	</ul>
	<?php }?>

</div>





<script>

var i=<?php echo $page;?>;
if(i==1){
	$(".pagination a:eq(0)").css("color", "red");
}else{
	$(".pagination a:eq(<?php echo $page;?>)").css("color", "red");
}
</script>
<?php
include("footer.php");?>