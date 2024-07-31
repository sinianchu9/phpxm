<?php
include("header.php");




?>

<div class="container mainbody">
	<div class="row">
		<div class="col-xs-offset-1 col-xs-10">
			<div id="register-body">
				<div class="text-right" style="margin-bottom:20px;">
					<h2><?php echo findpass?></h2>
					
				</div>
				<form method="post" class="form-horizontal" action="find2.html">
					<div class="form-group"> 
						<label for="id_you" class="control-label"><?php echo email?><span class="asteriskField">*</span> </label> 
						<input class="form-control" id="id_you" maxlength="30" name="you" type="text"> 
					</div> 
				
			


					<div class="form-group"> 
						<label for="verifyCode" class="control-label"><?php echo Verification?><span class="asteriskField">*</span> </label> 
						<div class="row">
							<div class="col-xs-6"><input class="form-control" id="verifyCode" name="verifyCode" type="text"></div> 
							<div class="col-xs-6"><img src="/testCaptcha.php" id="verifyImage" onclick="document.getElementById('verifyImage').src='/testCaptcha.php?r='+Math.random()"></div> 
						</div>
					</div>


				

									
					<div class="form-group"><div class="col-xs-offset-2 col-xs-10"> <button type="submit" class="btn btn-info" style="float:right;"><?php echo Tijiao?></button></div></div>

				</form>
			</div>
			
		
		</div>
	</div>
</div>


<?php
include("footer.php");?>