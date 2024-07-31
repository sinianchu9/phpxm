<?php
include("phpqrcode/phpqrcode.php");
include("header.php");
include("include/checkSession.php");
$id = $_SESSION['id'];
//如果没有登录，跳转到登录页
//eid是加密过的用户id

	$obj = new XDeode(9);
	$eid = $obj->encode($id);

$url='http://'.$_SERVER['HTTP_HOST']."/sign_up/".$eid.".html";
$content=$url;//二维码内容 
$errorCorrectionLevel = 'L';//容错级别
$matrixPointSize = 9;//生成图片大小
//生成二维码图片
$path = 'image/tuiguang/tgewm'.$id.'.png';
QRcode::png($content, $path, $errorCorrectionLevel, $matrixPointSize, 2);

//获取推荐id是自己的数据记录总条数
$sqlcus = "select count(*) as cus from `user` where tj_userid=".$id;
$resc = mysql_query ($sqlcus);
$rowc = mysql_fetch_array($resc);

$sqlsum = "select sum(jl_btc) as earnings from jiangli where tjr_userid=".$id;
$ressum = mysql_query ($sqlsum);
$rowsum = mysql_fetch_array($ressum);
$earnings2 = $rowsum['earnings']/Zero8();
?>

<div class="container mainbody">
	<div class="panel panel-info">
		<div class="panel-heading"><?php echo Invite?>	
			<span style="padding:0 10px;color:#F00;"><?php echo Mycustomer?>：<?php echo $rowc['cus'];?>&nbsp;&nbsp;<?php echo Myearnings?>：<?php echo $earnings2;?></span>
			
		</div>
		<div class="panel-body text-center" style="padding:20px 15px;border:1px solid #ddd;border-top:0">
			<div class="" style="width:100%;">
				<?php echo Invitelink;?>：
				<a href=<?php echo $url;?>><?php echo $url;?></a>
				<!-- <a href="javascript:;" hidefocus="none" class="btn btn-info btn-xs" style="margin:0 0 8px 7px;" id="btnCopy" onclick="clipboard('<?php echo $url;?>','btnCopy','<?php echo Copysuccess?>','btnCopy')"><?php echo Copybtn?></a><br/> -->
			</div>
			<div><img src="<?php echo $path?>"></div>
		</div>
	</div>

	<div class="panel panel-info">
		<div class="panel-heading"><?php echo Inviterule?></div>
		<div class="panel-body" style="background:#e3f2fd;padding:20px 15px;border:1px solid #ddd;border-top:0">
			<div class="" style="width:100%;">
				<?php echo Invite1;?><br>
				<?php echo Invite2;?><br>
				<?php echo Invite3;?><br>
				<?php echo Invite4;?><br>
				<?php echo Invite5;?><br>
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/ZeroClipboard.js"></script>
<script>
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
</script>

<?php
include("footer.php");?>