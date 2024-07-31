<?php
include("header.php");

$id=$_GET['id'];
$sqq="select * from artical where `show`=1 and id=".$id;
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
if($num<1){
	echo "<script>alert ('".Error.Illegalrequest."');</script>";
	echo "<script language='javascript'>window.location.href='/news.html';</script>";
	exit();
}
$row=mysql_fetch_array($res);
?>
<style>
.con_head{padding:10px;}
.con_head .con_title{font-size: 24px;margin-top:10px;line-height:30px;}
.con_head .time{float:right;}
.ar_con{margin-top:20px;}
.ar_con .con img{max-width:100%;}
blockquote {
    border-width: 0;
}
</style>
<div class="container mainbody">
	<div class="con_head">
		<div class="text-center con_title"><?php echo $row['title']?></div>
		<div class="time"><?php echo date("Y-m-d H:i:s",$row['time']);?></div>
	</div>
	<hr/>
	<div class="ar_con">
		<div id="content" class="con"><?php echo $row['content']?></div>
	</div>
</div>
<script>
//遍历文章内容的图片
$(document).ready(function(){  
	var con_imgs=$("#content").find("img");  
	con_imgs.each(function(){  
		var mSrc=$(this)[0].src;
		mSrc=mSrc.replace('https://www.omybtc.com','http://btc.zm78.net');
		$(this).attr('src',mSrc); 
	});  
});  
</script>
<?php
include("footer.php");?>