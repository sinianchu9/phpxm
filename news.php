<?php
include("header.php");

$perNumber = 20; // ÿҳ��ʾ�ļ�¼��
$page = $_GET ['page']; // ��õ�ǰ��ҳ��ֵ
$count = mysql_query ( "select count(*) from artical where `show`=1" ); // ��ü�¼����

$rs = mysql_fetch_array ( $count );
$totalNumber = $rs [0];

$totalPage = ceil ( $totalNumber / $perNumber ); // �������ҳ��
if (! isset ( $page )) {
	$page = 1;
} // ���û��ֵ,��ֵ1
$startCount = ($page - 1) * $perNumber; // ��ҳ��ʼ,���ݴ˷����������ʼ�ļ�¼

// ����ǰ��ļ������ʼ�ļ�¼�ͼ�¼��
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
		if ($page != 1) { //ҳ��������1
		?>
		<li><a href="/news/<?php echo $page - 1;?>.html">&laquo;</a></li>

		<?php
		}
		for ($i=1;$i<=$totalPage;$i++) {  //ѭ����ʾ��ҳ��
		?>
		<li><a href="/news/<?php echo $i;?>.html"><?php echo $i ;?></a></li>
		<?php
		}
		if ($page<$totalPage) { //���pageС����ҳ��,��ʾ��һҳ����
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