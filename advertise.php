<?php
include("header.php");

if($_GET['id']){
	$id=$_GET['id'];
	$sqq="select * from advertise where id=".$id." and userid=".$_SESSION ['id'] ;//增加userid条件，进行了权限限制，无法修改非本人发布的广告。还需要代码进行补充限制提交
	$res=mysql_query($sqq);
	$num=mysql_num_rows($res);
	if($num<1){  //广告主不是本人不能修改
		echo "<script>alert ('".Error.Illegalrequest."');</script>";
		echo "<script language='javascript'>window.location.href='/manage/buy.html';</script>";
		exit();
	}
	$row=mysql_fetch_array($res);
	$htmltitle=EditAD;
}else{
	$htmltitle=CreateAD;
}

if($_POST){
	$id=$_POST['id'];
	$ad_type=$_POST['ad_type'];
	$pay_type=$_POST['pay_type'];
	$price=$_POST['price'];
	$margin_price=$_POST['margin_price'];
	$min_price=$_POST['min_price'];
	$min_amount=$_POST['min_amount'];
	$max_amount=$_POST['max_amount'];
	$info=$_POST['info'];
	$fixed=$_POST['fixed'];
	$is_show=$_POST['is_show'];
	//var_dump($_POST);//exit;
	if($margin_price==""){
		$margin_price=0;
	}

	if($fixed==""){
		$fixed=0;
	}

	if($min_price==""){
		$min_price=0;
	}
	if($ad_type==""||$pay_type==""||$price==""||$margin_price==""||$min_amount==""||$max_amount==""||$info==""){
		echo "<script>alert ('".Error.Competeinfo."');</script>";
        echo "<script language='javascript'>window.history.go(-1);</script>";
        exit();
	}
	if($id){
		if(useridOFid($id)!=$_SESSION ['id']){//广告主不是本人不能修改
			echo "<script>alert ('".Error.Illegalrequest."');</script>";
			echo "<script language='javascript'>window.location.href='/manage/buy.html';</script>";
			exit();
		}
		$sqq="update advertise set ad_type='".$ad_type."',pay_type='".$pay_type."',margin_price=".$margin_price.",price=".$price.",min_price=".$min_price.",min_amount=".$min_amount.",max_amount=".$max_amount.",info='".$info."',is_show=".$is_show.",fixed=".$fixed." where id=".$id;
	}else{
		$sqq="insert into advertise(userid,ad_type,pay_type,margin_price,price,min_price,min_amount,max_amount,info,ad_time,is_show,fixed)value(".$_SESSION['id'].",'".$ad_type."','".$pay_type."',".$margin_price.",".$price.",".$min_price.",".$min_amount.",".$max_amount.",'".$info."',".time().",".$is_show.",".$fixed.")";
	}
	
	
	//echo $sqq;exit;
	
	$res=mysql_query($sqq);
	echo "<script language='javascript'>window.location.href='/index.html';</script>";	
}

?>



<div class="container mainbody">
	<h1><?php echo $htmltitle?></h1>
	<?php if($_SESSION['username']==""){?>
    <div class="alert alert-danger">
        <span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?php echo BeforeAdvertise?> <?php echo Please?> <a class="login-link" href="/sign_in.html"><?php echo Login;?></a> <?php echo Huo?> <a class="register-link" href="/sign_up.html"><?php echo Register;?></a>。
    </div>
	<?php }else{?>
	<div class="alert alert-danger">
        <span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?php echo Advtip?>
    </div>
	<?php }?>

	<form method="post" class="form-horizontal" action="">


		
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Adstype?> </label> 
			<div class="col-sm-5"> 
				<label class="radio-inline">
					<input type="radio" name="is_show" value="1" <?php if(empty($row)){?> checked="checked"<?php }elseif($row['is_show']==1){?>checked="checked"<?php }?> /><?php echo Show?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="is_show" value="0" <?php if(!empty($row) &&  $row['is_show']==0){?>checked="checked"<?php }?> /><?php echo Hide?>
				</label>
			</div> 
		</div> 
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Adstype?> </label> 
			<div class="col-sm-5"> 
				<label class="radio-inline">
					<input type="radio" name="ad_type" value="sell" <?php if($row['ad_type']=="sell" || empty($row)){?>checked="checked"<?php }?> /><?php echo SellNav?>	
				</label>
				<label class="radio-inline">
					<input type="radio" name="ad_type" value="buy" <?php if($row['ad_type']=="buy"){?>checked="checked"<?php }?> /><?php echo BuyNav?>	
				</label>
			</div> 
		</div>
		
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo '防洗錢策略';?> </label> 
			<div class="col-sm-3"> 
				<label class="radio-inline">
					<input type="radio" name="fanxiqian" value="kuansong" <?php if($row['ad_type']=="sell" || empty($row)){?>checked="checked"<?php }?> /><?php echo '寬松';?>	
				</label>
				<label class="radio-inline">
					<input type="radio" name="fanxiqian" value="biaozhun" <?php if($row['ad_type']=="buy"){?>checked="checked"<?php }?> /><?php echo '標準';?>	
				</label>
				<label class="radio-inline">
					<input type="radio" name="fanxiqian" value="yange" <?php if($row['ad_type']=="buy"){?>checked="checked"<?php }?> /><?php echo '緊縮';?>	
				</label>
				
			</div> 
			<div class="col-md-7 beizhu" ><?php echo '緊縮的反洗錢策略將會嚴格篩選對方條件與用戶模型';?></div>
			

		</div>

		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo '防惡意下單限購策略';?> </label> 
			<div class="col-sm-3"> 
				<label class="radio-inline">
					<input type="radio" name="fangexi" value="kuansong" <?php if($row['ad_type']=="sell" || empty($row)){?>checked="checked"<?php }?> /><?php echo '寬松';?>	
				</label>
				<label class="radio-inline">
					<input type="radio" name="fangexi" value="biaozhun" <?php if($row['ad_type']=="buy"){?>checked="checked"<?php }?> /><?php echo '標準';?>	
				</label>
				<label class="radio-inline">
					<input type="radio" name="fangexi" value="yange" <?php if($row['ad_type']=="buy"){?>checked="checked"<?php }?> /><?php echo '緊縮';?>	
				</label>
				
			</div> 
			<div class="col-md-7 beizhu" ><?php echo '緊縮的防惡意下單限購將會嚴格篩選對方條件與評級';?></div>
			

		</div>


		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo payMethod;?> </label> 
			<div class="col-sm-5 "> 
				<label class="radio-inline">
					<input type="radio" name="pay_type" value="zfb" <?php if($row['pay_type']=="zfb" || empty($row)){?>checked="checked"<?php }?> /><?php echo Zfb?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="pay_type" value="weixin" <?php if($row['pay_type']=="weixin"){?>checked="checked"<?php }?> /><?php echo Weixin?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="pay_type" value="bank" <?php if($row['pay_type']=="bank"){?>checked="checked"<?php }?> /><?php echo Bank?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="pay_type" value="cash" <?php if($row['pay_type']=="cash"){?>checked="checked"<?php }?> /><?php echo Cashd?>
				</label>
			</div>
		</div> 
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Margin?> </label> <!--溢价-->
			<div class="col-sm-3">
				<div class="input-group"><input class="form-control" value="<?php echo $row['margin_price']?>" id="marginid" name="margin_price" type="text"><span class="input-group-addon">%</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Margintip?></div>
			<div class="col-sm-offset-2 col-sm-10"><div id="marginerror" style="display: none;color:red;"><?php echo Marginerror?></div></div>
		</div>


		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Price?> </label> 
			<div class="col-sm-3">
				<div class="input-group"><input class="form-control" id="priceid"  name="price" type="text"><span class="input-group-addon">CNY</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Pricetip?></div>
		</div>

		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Fixed?> </label> <!--固定价格-->
				<div class="col-sm-3">
				<div class="input-group"><input class="form-control" id="fixedid" value="<?php echo $row['fixed']?>"  name="fixed" type="text"><span class="input-group-addon">CNY</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Fixedtip?></div>
		</div>

		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Minprice?> </label> 
			<div class="col-sm-3">
				<div class="input-group"><input class="form-control"  name="min_price" value="<?php echo $row['min_price']?>" type="text"><span class="input-group-addon">CNY</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Minpricetip?></div>
		</div>
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Minlimit?> </label> 
			<div class="col-sm-3">
				<div class="input-group"><input class="numberinput form-control"  name="min_amount" value="<?php echo $row['min_amount']?>" type="number"> <span class="input-group-addon">CNY</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Mintip?></div>
		</div>
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Maxlimit?> </label> 
			<div class="col-sm-3">
				<div class="input-group"><input class="numberinput form-control"  name="max_amount" value="<?php echo $row['max_amount']?>" type="number"> <span class="input-group-addon">CNY</span> </div>
			</div> 
			<div class="col-md-7 beizhu" ><?php echo Maxtip?></div>
		</div>
		<div class="form-group"> 
			<label for="" class="control-label  col-sm-2"><?php echo Terms?> </label> 
			<div class="col-sm-5">
				<textarea name="info" rows="5" class="form-control"><?php echo $row['info']?></textarea>
			</div> 
			<div class="col-md-5 beizhu" ><?php echo Termstip?></div>
		</div>
	
		<!-- <p></p>
		<div>
			<label>请验证您是一个人。</label>
			<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
			<div class="g-recaptcha" data-sitekey="6Le95uoSAAAAAH3LKzssY-LHQOMu6eBag0yqlA6O"></div>
		</div> -->
		
		<?php if($_SESSION['username']==""){?>
		<div class="form-group"><div class="col-sm-offset-2 col-sm-10"> <button disabled="disabled" id="publishBtn" class="btn btn-default"><?php echo PublishADBtn?></button></div></div>
		<?php }else{?>
		<div class="form-group"><div class="col-sm-offset-2 col-sm-10"> <button type="submit" id="publishBtn" class="btn btn-info"><?php echo PublishADBtn?></button></div></div>
		<input type="hidden" name="id" value="<?php echo $row['id']?>">
		<?php }?>
						
		

	</form>

</div>

<script>

var price=<?php echo $priceArray['CNY']['15m']?>;
var mar=$("#marginid").val();
var price1=(mar/100+1)*price;
price1=Math.round(price1*100)/100;
$("#priceid").val(price1);

$("#marginid").on("input propertychange",function(){  
	if($(this).val()==""){  
		$("#marginerror").css("display","none");
		$("#marginid").removeClass("amount-error");
		$("#priceid").val(price);
		$('#publishBtn').attr('disabled',"true");
	}else if(isNaN($(this).val())){
		$("#marginerror").css("display","block");
		$("#marginid").addClass("amount-error");
		$("#priceid").val(price);
		$('#publishBtn').attr('disabled',"true");
	}else{
		if($(this).val()<-99.99 || $(this).val()>99.99){
			$("#marginerror").css("display","block");
			$("#marginid").addClass("amount-error");
			$('#publishBtn').attr('disabled',"true");
			$("#priceid").val(price);
		}else{
			$("#marginerror").css("display","none");
			$("#marginid").removeClass("amount-error");
			$('#publishBtn').removeAttr('disabled');
			var priceNow=($(this).val()/100+1)*price;
			priceNow=Math.round(priceNow*100)/100;
			$("#priceid").val(priceNow);
		}
	}
});  

</script>
<?php
include("footer.php");?>