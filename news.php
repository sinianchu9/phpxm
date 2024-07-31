<?php
include("header.php");

$perNumber = 20; // 每页显示的记录数
$page = $_GET ['page']; // 获得当前的页面值
$count = mysql_query ( "select count(*) from artical where `show`=1" ); // 获得记录总数

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs [0];

$totalPage = ceil ( $totalNumber / $perNumber ); // 计算出总页数
if (! isset ( $page )) {
	$page = 1;
} // 如果没有值,则赋值1
$startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录

// 根据前面的计算出开始的记录和记录数
$sqq="select title,time,pic,id from artical where `show`=1 order by time desc limit $startCount,$perNumber";
$res=mysql_query($sqq);
//echo $sqq;
?>
<div class="container mainbody">
	<div class="h1 text-center"><?php echo NEWS?></div>
	<?php while($row=mysql_fetch_array ( $res ) ){ ?>
	<a href="/artical/<?php echo $row['id']?>.html"><div class="artical">
		<img src="<?php echo $row['pic']?>">
		<div class="title"><?php echo $row['title']?></div>
		<div class="time"><?php echo date("Y-m-d H:i:s",$row['time']);?></div>
	</div></a>
	<?php }?>
	<?php if($totalPage>1){?>
	<div class="text-center">
	<ul class="pagination">
		<?php
		if ($page != 1) { //页数不等于1
		?>
		<li><a href="/news/<?php echo $page - 1;?>.html">&laquo;</a></li>

		<?php
		}
		for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
		?>
		<li><a href="/news/<?php echo $i;?>.html"><?php echo $i ;?></a></li>
		<?php
		}
		if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
		?>
		<li><a href="/news/<?php echo $page + 1;?>.html">&raquo;</a></li>
		<?php
		} 

		mysql_close( $link );
		?>
	</ul></div>
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