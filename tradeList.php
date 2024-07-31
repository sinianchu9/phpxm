<?php
include("header.php");
include("include/checkSession.php");

$where=" where (d.buyid=".$_SESSION['id']." or d.sellid=".$_SESSION['id'].") and d.advid=a.id";



$perNumber = 20; // 每页显示的记录数
$page = $_GET ['page']; // 获得当前的页面值
$count = mysql_query ( "select count(*) from dingdan as d ,advertise as a ".$where ); // 获得记录总数

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs [0];

$totalPage = ceil ( $totalNumber / $perNumber ); // 计算出总页数
if (! isset ( $page )) {
	$page = 1;
} // 如果没有值,则赋值1
$startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录

// 根据前面的计算出开始的记录和记录数
$sql="select d.*,a.price as price,a.pay_type as pay_type from dingdan as d ,advertise as a".$where." order by id desc limit $startCount,$perNumber";
$res=mysql_query($sql);

//echo $sql;



?>

<div class="container mainbody">
	<div class="panel panel-info">
		<div class="panel-heading"><?php echo TradeManager?>		
			<span style="padding:0 10px;color:#F00;"><?php echo Numoftrades?>：<?php echo $totalNumber;?></span>
			
		</div>
	</div>


	<?php if($totalNumber>0){
		while($row=mysql_fetch_array ( $res ) ){
			if(($row['buyid']==$_SESSION['id']&&$row['msgbuy']==1) || ($row['sellid']==$_SESSION['id']&&$row['msgsell']==1)){
				$style="style='background:#bce8f1'";
				$span="<span class='message-count' style='display: inline-block'></span>";
			}else{
				$style="";
				$span="";
			}
	?>
		<a href="/ddContent/<?php echo $row['id']?>.html">
		<div class="trade_list_trem" <?php echo $style;?>>
			<div><?php echo Partner?>：<?php if($row['buyid']==$_SESSION['id']){ echo username($row['sellid']); }elseif($row['sellid']==$_SESSION['id']){ echo username($row['buyid']); }?></div>
			<!-- <div><?php echo TradeID?><?php echo $row['id']?><?php echo $span;?></div> -->
			<!-- <div><?php echo Type?><?php if($row['buyid']==$_SESSION['id']){ echo BuyBtn; }elseif($row['sellid']==$_SESSION['id']){ echo SellBtn; }?></div> -->
			<div><?php echo Amount?>：<?php echo $row['money'];?>CNY</div>
			<div><?php echo payType($row['pay_type'])."："; if($row['buyid']==$_SESSION['id']){ echo BuyBtn; }elseif($row['sellid']==$_SESSION['id']){ echo SellBtn; }  echo " ".$row['btc_num'];?>BTC</div>
			<div><?php echo Price?>：<?php echo $row['price'];?>CNY</div>
			<div><?php echo Created?>：<?php echo date("Y-m-d H:i:s",$row['dd_time']);?></div>
			<div><?php echo Tradestatus?>：<?php if($row['dd_type']==0){ echo "<span class='label label-default'>".DDStatus($row['dd_type'])."</span>"; }elseif($row['dd_type']==1){ echo "<span class='label label-warning'>".DDStatus($row['dd_type'])."</span>"; }elseif($row['dd_type']==2||$row['dd_type']==6){ echo "<span class='label label-success'>".DDStatus($row['dd_type'])."</span>"; }elseif($row['dd_type']==3||$row['dd_type']==4||$row['dd_type']==5){ echo "<span class='label label-danger'>".DDStatus($row['dd_type'])."</span>"; } ?></div>
		</div>
		</a>
		<?php }
		}else{?>
		<div class="text-center"><?php echo Nomorerecord?></div>
		<?php }?>

	
	<?php if($totalPage>1){?>
	<ul class="pagination">
		<?php
		if ($page != 1) { //页数不等于1
		?>
		<li><a href="/tradeList/<?php echo $page - 1;?>.html">&laquo;</a></li>

		<?php
		}
		for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
		?>
		<li><a href="/tradeList/<?php echo $i;?>.html"><?php echo $i ;?></a></li>
		<?php
		}
		if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
		?>
		<li><a href="/tradeList/<?php echo $page + 1;?>.html">&raquo;</a></li>
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