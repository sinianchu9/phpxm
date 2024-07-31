<?php
include("header.php");
$userid=$_SESSION['id'];
$id=$_GET['id'];
$btc=btcDetail($userid);


$sqq="select * from advertise where id=".$id." and ad_type='buy'";
$res=mysql_query($sqq);
$num=mysql_num_rows($res);
if($num<1){
	echo "<script>alert ('".Error.Illegalrequest."');</script>";
	echo "<script language='javascript'>window.location.href='/sell_bitcoins.html';</script>";
	exit();
}
$row=mysql_fetch_array($res);
if($row['is_show']!=1 ){
	echo "<script>alert ('".Error.ADexpired."');</script>";
	echo "<script language='javascript'>window.location.href='/sell_bitcoins.html';</script>";
	exit();
}
$price=round(($row['margin_price']/100+1)*$priceArray['CNY']['15m'],2);//溢价定价



?>

<div class="container mainbody">
	<h2><?php if($_SESSION['lang']=='cn'||$_SESSION['lang']=='hk'){echo Useing.payType($row['pay_type'])." ".SellNav;}elseif($_SESSION['lang']=='en'){echo SellNav.Useing.payType($row['pay_type']);}?></h2>
	<p><?php echo User?> <i><?php echo username($row['userid']);?></i> <?php echo Wanttobuy?></p>
	
	
	<div class="sell_buy_con">  
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Price?>：</h4> <!--价格-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo $price?>CNY/BTC</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo payMethod?>：</h4> <!--付款方式-->
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo payType($row['pay_type']);?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Buyer?>：</h4>
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo username($row['userid']);?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 text-right">
				<h4><?php echo Limits?>：</h4>
			</div>
			<div class="col-xs-7">
				<h5 class="price" id="ad_price"><?php echo $row['min_amount']." - ".$row['max_amount']."CNY";?></h4>
			</div>
		</div>
		<div class="row terms">
			<div class="col-xs-12">
				<h4><?php if($_SESSION['lang']=='cn'||$_SESSION['lang']=='hk'){echo User;?><i><?php echo username($row['userid']);?></i><?php echo Buyterm;}elseif($_SESSION['lang']=='en'){echo Buyterm;?><i><?php echo username($row['userid']);?></i><?php }?></h4>
				<p class="messagetext"><?php echo $row['info']?></p>
			</div>
		</div>
	</div>

	<div class="well" id="cactus-wrapper">
		<h4><?php echo Howmanysell?></h4>
		
			<input name="buyid" type="hidden" id="buyid" value="<?php echo $row['userid'];?>">
			<input name="advid" type="hidden" id="advid" value="<?php echo $row['id'];?>">
			<input name="price" type="hidden" id="priceid" value="<?php echo $price;?>">
			<span class="formerror"></span>
			<span class="formerror"></span>
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group"><input autocomplete="off" onkeyup="value=value.replace(/[^\-?\d.]/g,'')" class="form-control " id="amountid" name="amount" placeholder="0.00" title="Remember to suggest trade sum amount between limit" type="text"><span class="input-group-addon">CNY</span></div>
				</div>
				<div class="col-xs-12">
					<div class="input-group"><input class="form-control" id="btcid" name="btc" type="text" readonly><span class="input-group-addon">BTC</span></div>
				</div>
			</div>

			<div style="height:10px;width:100%"></div>

			<div id="under_min_error" style="display: none;color:red;"><?php echo Sellmin?> <?php echo $row['min_amount'];?> CNY。</div>
			<div id="over_max_error" style="display: none;color:red;"><?php echo Sellmax?> <?php echo $row['max_amount'];?> CNY。</div>

			<div style="height:20px;width:100%"></div>

			<?php if($_SESSION['username']==""){?>
			<p><a id="ad-register-here" class="btn btn-success" href="/sign_up.html">
			  <i class="fa fa-check-square-o"></i>
			  <?php echo RegisterNow?>
			</a></p>
			<?php }else{ ?>
			<p><button type="submit" id="sellBtn" disabled="disabled" class="btn btn-info"><?php echo SellBtn?></button></p>
			<?php } ?>
			<p style="font-size:12px;color:#555;line-height:20px;"><?php echo Buytip?></p>
		
	</div>
	
	
	
		


<script>
var min=<?php echo $row['min_amount'];?>;
var max=<?php echo $row['max_amount'];?>;
var price=<?php echo $price?>;
$("#amountid").on("input propertychange",function(){  
	if($(this).val()==""){  
		$("#under_min_error").css("display","none");
		$("#over_max_error").css("display","none");//amount-error
		$("#amountid").removeClass("amount-error");
		$("#btcid").val(0);
		$('#sellBtn').attr('disabled',"true");
	}else if(isNaN($(this).val())){
		$("#under_min_error").css("display","block");
		$("#over_max_error").css("display","none");
		$("#amountid").addClass("amount-error");
		$('#sellBtn').attr('disabled',"true");
	}else{
		var btc=$(this).val()/price;
		btc=Math.round(btc*<?php echo Zero8()?>)/<?php echo Zero8()?>;
		$("#btcid").val(btc);
			//parseFloat(num.tofixed(2));
		if($(this).val()<min ){
			$("#under_min_error").css("display","block");
			$("#over_max_error").css("display","none");
			$("#amountid").addClass("amount-error");
			$('#sellBtn').attr('disabled',"true");
		}else if($(this).val()>max){
			$("#under_min_error").css("display","none");
			$("#over_max_error").css("display","block");
			$("#amountid").addClass("amount-error");
			$('#sellBtn').attr('disabled',"true");
		}else{
			$("#under_min_error").css("display","none");
			$("#over_max_error").css("display","none");
			$("#amountid").removeClass("amount-error");
			$('#sellBtn').removeAttr('disabled');
		}
	}
}); 
/*/*/
$("#sellBtn").click(function(){
	var price=<?php echo $price?>;
	var btc_num=$("#btcid").val();
	var money=$("#amountid").val();
	var buyid=$("#buyid").val();
	var advid=$("#advid").val();
	$.ajax({
		type: "POST",
		url: "/sellRecord.php",
		dataType: "json",
		data: {
			'price':price,
			'btc_num':btc_num,
			'money':money,
			'buyid':buyid,
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