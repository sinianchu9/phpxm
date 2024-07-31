<?php
include("header.php");
?>

<style>
.dropdown-menu>li>a {
    white-space: inherit;
}
</style>
<div class="container mainbody" style="margin-top:0;margin-bottom: 150px;position:relative;">
	<div id="menu" class="dropup" style="width:90%;">
		<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo FAQ?><b class="caret"></b></a>
		<ul class="dropdown-menu" style="max-width:100%;"> 
			<li><a class="current" href="#Faq1"><?php echo Faq1?></a></li>  
			<li><a href="#Faq1_1"><?php echo Faq1_1?></a></li>  
			<li><a href="#Faq9"><?php echo Faq9?></a></li>  
			<li><a href="#Faq10"><?php echo Faq10?></a></li>  
			<li><a href="#Faq11"><?php echo Faq11_?></a></li>  
			<li><a href="#Faq22"><?php echo Faq22?></a></li>  
			<li><a href="#Faq23"><?php echo Faq23?></a></li>  
			<li><a href="#Faq24"><?php echo Faq24?></a></li> 
			<li><a href="#Faq25"><?php echo Faq25?></a></li>  
		</ul>  
	</div>
	<div class="faqContent">
		<div id="Faq1" class="about-cont">
			<h1 class="h1"><?php echo Faq1?></h1>
			<p class="h3"><?php echo Faq11?></p><p class="p"><?php echo Faq111?></p>
			<p class="h3"><?php echo Faq12?></p><p class="p"><?php echo Faq121?></p>
			<p class="h3"><?php echo Faq13?></p><p class="p"><?php echo Faq131?></p>
			<p class="h3"><?php echo Faq14?></p><p class="p"><?php echo Faq141?></p>
			<p class="h3"><?php echo Faq15?></p><p class="p"><?php echo Faq151?></p>
		</div>

		<div id="Faq1_1" class="about-cont">
			<h1 class="h1"><?php echo Faq1_1?></h1>
			<p class="h3"><?php echo Faq2?></p><p class="p"><?php echo Faq21?></p>
			<p class="h3"><?php echo Faq3?></p><p class="p"><?php echo Faq31?></p>
			<p class="h3"><?php echo Faq4?></p><p class="p"><?php echo Faq41?></p>
			<p class="h3"><?php echo Faq5?></p><p class="p"><?php echo Faq51?></p>
			<p class="h3"><?php echo Faq6?></p><p class="p"><?php echo Faq61?></p>
			<p class="h3"><?php echo Faq7?></p><p class="p"><?php echo Faq71?></p>
			<p class="h3"><?php echo Faq8?></p><p class="p"><?php echo Faq81?></p>
		</div>

		<div id="Faq9" class="about-cont">
			<h1 class="h1"><?php echo Faq9?></h1>
			<p class="h3"><?php echo Faq91?></p><p class="p"><?php echo Faq911?></p>
			<p class="h3"><?php echo Faq92?></p><p class="p"><?php echo Faq921?></p>
			<p class="h3"><?php echo Faq93?></p><p class="p"><?php echo Faq931?></p>
			<p class="h3"><?php echo Faq94?></p><p class="p"><?php echo Faq941?></p>
			<p class="h3"><?php echo Faq95?></p><p class="p"><?php echo Faq951?></p>
		</div>

		<div id="Faq10" class="about-cont">
			<h1 class="h1"><?php echo Faq10?></h1>
			<p class="p"><?php echo Faq101?></p>
		</div>

		<div id="Faq11" class="about-cont">
			<h1 class="h1"><?php echo Faq11_?></h1>
			<p class="p"><?php echo Faq11_1.set1().Faq11_1_1?></p>
			<p class="h3"><?php echo Faq112?></p><p class="p"><?php echo Faq1121?></p>
			<p class="h3"><?php echo Faq113?></p><p class="p"><?php echo Faq1131?></p>
			<p class="h3"><?php echo Faq114?></p><p class="p"><?php echo Faq1141?></p>
		</div>

		<div id="Faq22" class="about-cont">
			<h1 class="h1"><?php echo Faq22?></h1>
			<p class="p"><?php echo Faq221?></p>
		</div>

		<div id="Faq23" class="about-cont">
			<h1 class="h1"><?php echo Faq23?></h1>
			<p class="p"><?php echo Faq231?></p>
		</div>

		<div id="Faq24" class="about-cont">
			<h1 class="h1"><?php echo Faq24?></h1>
			<p class="p"><?php echo Faq241?></p>
		</div>

		<div id="Faq25" class="about-cont">
			<h1 class="h1"><?php echo Faq25?></h1>
			<p class="p"><?php echo Faq251?></p>
		</div>
	</div>
</div>
<?php
include("footer.php");?>