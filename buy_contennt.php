<?php
include("header.php");

$id=$_GET['id'];

$sqq="select * from advertise where id=".$id." and ad_type='sell'";
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
if($num<1){
	echo "<script>alert ('".Error.Illegalrequest."');</script>";
	echo "<script language='javascript'>window.location.href='/buy_bitcoins.html';</script>";
	exit();
}
$row=mysql_fetch_array($res);
if($row['is_show']!=1 ){
	echo "<script>alert ('".Error.ADexpired."');</script>";
	echo "<script language='javascript'>window.location.href='/buy_bitcoins.html';</script>";
	exit();
}

$btc=btcDetail($row['userid']);//获取卖家的比特币详情
//确定价格
$myprice=ADpriceNow($id);
$fee=seller_trade($row['userid']);//卖家交易手续费
//去除手续费后可用余额
$avail=floor($btc['avail']*Zero8()*(1-$fee/100))/Zero8();
if($avail>=set1()){
	//确定最大限额
	if($avail*$myprice>$row['max_amount']){
		$max=$row['max_amount'];
	}else{
		$max=intval($avail*$myprice);
	}
	//确定最大限额
	if($avail*$myprice>$row['min_amount']){
		$min=$row['min_amount'];
	}else{
		$min=intval($avail*$myprice);
	}
}else{
	$max=0;
	$min=0;
}

?>

<div class="container mainbody">
	<h2><?php if($_SESSION['lang']=='cn'||$_SESSION['lang']=='hk'){echo Useing.payType($row['pay_type'])." ".BuyNav;}elseif($_SESSION['lang']=='en'){echo BuyNav.Useing.payType($row['pay_type']);}?></h2>
	<p><?php echo User?> <i style="color:#5bc0de"><?php echo username($row['userid']);?></i> <?php echo Wanttosell?></p>
	

	<div class="sell_buy_con">
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Price?>：</h4><!--价格-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo $myprice;?>CNY/BTC</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo payMethod?>：</h4><!--付款方式-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo payType($row['pay_type']);?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Seller?>：</h4><!--卖家-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo username($row['userid']);?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Limits?>：</h4><!--限额-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo $min." - ".$max."CNY";?></h4>
			</div>
		</div>
		<div class="row terms">
			<div class="col-xs-12">
				<h4><?php if($_SESSION['lang']=='cn'||$_SESSION['lang']=='hk'){echo User;?><i><?php echo username($row['userid']);?></i><?php echo Buyterm;}elseif($_SESSION['lang']=='en'){echo Buyterm;?><i><?php echo username($row['userid']);?></i><?php }?></h4>
				<p class="messagetext"><?php echo $row['info']?></p>
			</div>
		</div>
	</div>

		<?php if($btc['avail']>=set1()){?>

		<div class="well" id="cactus-wrapper">
			<h4><?php echo Howmanybuy?></h4><!--您想买多少？-->
			
				<input name="sellid" type="hidden" id="sellid" value="<?php echo $row['userid'];?>">
				<input name="advid" type="hidden" id="advid" value="<?php echo $row['id'];?>">
				<input name="price" type="hidden" id="priceid" value="<?php echo $myprice;?>">
				<div class="row">
					<div class="col-xs-12">
						<div class="input-group"><input autocomplete="off" onkeyup="value=value.replace(/[^\-?\d.]/g,'')" class="form-control " id="amountid" name="amount" placeholder="0.00" title="Remember to suggest trade sum amount between limit" type="text"><span class="input-group-addon">CNY</span></div>
					</div>
					<div class="col-xs-12">
						<div class="input-group"><input class="form-control" id="btcid" name="btc" type="text" readonly><span class="input-group-addon">BTC</span></div>
					</div>
				</div>

				<div style="height:10px;width:100%"></div>

				<div id="under_min_error" style="display: none;color:red;"><?php echo Buymin?> <?php echo $min;?> CNY。</div>
				<div id="over_max_error" style="display: none;color:red;"><?php echo Buymax?> <?php echo $max;?> CNY。</div>

				<div style="height:20px;width:100%"></div>

				<?php if($_SESSION['username']==""){?>
				<p><a id="ad-register-here" class="btn btn-success" href="/sign_up.html">
				  <i class="fa fa-check-square-o"></i>
				  <?php echo RegisterNow?>
				</a></p>
				<?php }else{ ?>
				<p><button type="submit" id="buyBtn" disabled="disabled" class="btn btn-info"><?php echo BuyBtn?></button></p>
				<?php } ?>
				<p style="font-size:12px;color:#555;line-height:20px;"><?php echo Buytip?></p>
			
		</div>
		<?php }else{?>
		<div class="well"><?php echo User?> <i style="color:#5bc0de"><?php echo username($row['userid']);?></i><?php echo Sellout;?></div>
		<?php }?>


<script>
var min=<?php echo $min;?>;
var max=<?php echo $max;?>;
var price=<?php echo $myprice?>;
$("#amountid").on("input propertychange",function(){  
	if($(this).val()==""){  
		$("#under_min_error").css("display","none");
		$("#over_max_error").css("display","none");//amount-error
		$("#amountid").removeClass("amount-error");
		$("#btcid").val(0);
		$('#buyBtn').attr('disabled',"true");
	}else if(isNaN($(this).val())){
		$("#under_min_error").css("display","block");
		$("#over_max_error").css("display","none");
		$("#amountid").addClass("amount-error");
		$('#buyBtn').attr('disabled',"true");
	}else{
		var btc=$(this).val()/price;
		btc=Math.round(btc*<?php echo Zero8()?>)/<?php echo Zero8()?>;
		$("#btcid").val(btc);
			//parseFloat(num.tofixed(2));
		if($(this).val()<min ){
			$("#under_min_error").css("display","block");
			$("#over_max_error").css("display","none");
			$("#amountid").addClass("amount-error");
			$('#buyBtn').attr('disabled',"true");
		}else if($(this).val()>max){
			$("#under_min_error").css("display","none");
			$("#over_max_error").css("display","block");
			$("#amountid").addClass("amount-error");
			$('#buyBtn').attr('disabled',"true");
		}else{
			$("#under_min_error").css("display","none");
			$("#over_max_error").css("display","none");
			$("#amountid").removeClass("amount-error");
			$('#buyBtn').removeAttr('disabled');
		}
	}
}); 

$("#buyBtn").click(function(){
	var price=<?php echo $price?>;
	var btc_num=$("#btcid").val();
	var money=$("#amountid").val();
	var sellid=$("#sellid").val();
	var advid=$("#advid").val();
	$.ajax({
		type: "POST",
		url: "/buyRecord.php",
		dataType: "json",
		data: {
			'price':price,
			'btc_num':btc_num,
			'money':money,
			'sellid':sellid,
			'advid':advid
		},
		success: function(data){
			if(data.status==true){
				window.location.href='/ddContent/'+data.msg+'.html';
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
		}
	});
	
});
</script>

</div>
<?php
include("footer.php");?>