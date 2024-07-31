<?php
include("header.php");

$userid=$_GET['userid'];
$sql="select * from `user` where id=".$userid;
$res=mysql_query($sql);
$num=mysql_num_rows($res);
$row=mysql_fetch_array($res);

$userRes=userInfoDetail($userid);

$where=" where userid=".$userid;

$feedback=$_GET['feedback'];

if($feedback!=""){
	$where = $where." and feedback='".$feedback."'";
}

$perNumber = 20; // 每页显示的记录数
$page = $_GET ['page']; // 获得当前的页面值

$count = mysql_query ( "select count(*) from pingjia ".$where ); // 获得记录总数

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs[0];

$totalPage = ceil ( $totalNumber / $perNumber ); // 计算出总页数
if (! isset ( $page )) {
	$page = 1;
} // 如果没有值,则赋值1
$startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录

// 根据前面的计算出开始的记录和记录数
$sq="select * from pingjia ".$where." order by id desc limit $startCount,$perNumber";
$re=mysql_query($sq);
	

?>


<div class="container mainbody">
	
	<?php if($num>0){?>
	<h3><?php echo Userinfo1?><i><?php echo $row['username'];?></i><?php echo Userinfo2?></h3>

	<div class="well">
		<div class="row">
			<div class="col-xs-12">
				<h5><?php echo Confirmedtrades;?>：<?php echo $userRes['jiaoyi'];?></h5>
			</div>
		</div>
		<!-- <div class="row">
			<div class="col-xs-4 text-right">
				<h5>交易总金额：</h5>
			</div>
			<div class="col-xs-8">
				<h5 class="con" id="ad_price"><?php echo floatval($userRes['amount']);?>CNY</h5>
			</div>
		</div> -->
		<div class="row">
			<div class="col-xs-12">
				<h5><?php echo Volume;?>：<?php echo btcShow($userRes['btc']);?>BTC</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h5><?php echo Rating?>：<?php echo $userRes['pingjia'];?>%</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h5><?php echo Fristtimetrader?>：<?php echo $userRes['first']?></h5>
			</div>
		</div>
	</div>



	<h3><?php echo Userfeed1?><i><?php echo $row['username'];?></i><?php echo Userfeed2?></h3>
	<div class="" style="width:100%;">
		<a href="/userInfo/<?php echo $userid?>.html"><?php echo All?></a>
		| <a href="/userInfo/<?php echo $userid?>/POSITIVE.html"><?php echo Positive?></a>
		| <a href="/userInfo/<?php echo $userid?>/NEUTRAL.html"><?php echo Neutral?></a>
		| <a href="/userInfo/<?php echo $userid?>/NEGATIVE.html"><?php echo Negative?></a>
	</div>
	<?php if($totalNumber>0){?>
	
	<div class="row">
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo Feedbacktime?></th>
					<th><?php echo Evaluation?></th>
					<?php if($_SESSION['id']==$userid){?><th><?php echo Evaluator?></th><?php }?>
				</tr>
			</thead>
			<tbody>
			<?php  while($row=mysql_fetch_array ( $re ) ){?>
				<tr style="line-height:51px;">
					<td><?php echo date("Y-m-d H:i:s",$row['pj_time']);?></td>
					<td><?php echo pingjia($row['feedback']);?></td>
					<?php if($_SESSION['id']==$userid){?><td><?php echo username($row['pingjiaid'])?></td><?php }?>
				</tr>
			<?php }?>
			</tbody>
		</table>
		<?php if($totalPage>1){?>
		<ul class="pagination">
			<?php
			if ($page != 1) { //页数不等于1
			?>
			<li><a href="/userInfo/<?php echo $page - 1; if($feedback){ echo "/".$feedback; }?>/<?php echo $userid?>.html">&laquo;</a></li>

			<?php
			}
			for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
			?>
			<li><a href="/userInfo/<?php echo $i; if($feedback){ echo "/".$feedback; } ?>/<?php echo $userid?>.html"><?php echo $i ;?></a></li>
			<?php
			}
			if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
			?>
			<li><a href="/userInfo/<?php echo $page + 1; if($feedback){ echo "/".$feedback; } ?>/<?php echo $userid?>.html">&raquo;</a></li>
			<?php
			} 

			mysql_close( $link );
			?>
		</ul>
		<?php }?>
	</div>
	<?php }else{?>
	<div class="alert alert-danger">
		<span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?php echo Noevaluation?>
	</div>
	<?php }?>
	

	

	



	<?php }else{?>
	<div class="alert alert-danger">
        <span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?php echo Illegalrequest?>
    </div>
	<?php }?>



	
		
<script>
var i=<?php echo $page;?>;
if(i==1){
	$(".pagination a:eq(0)").css("color", "red");
}else{
	$(".pagination a:eq(<?php echo $page;?>)").css("color", "red");
}
</script>
</div>
<?php
include("footer.php");?>