<?php 
	require_once("includes/config.inc.php");
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
</head>
<body>
<!-------------- Header ------------------->
<header class="inner_header">
	<?php include_once('header.php');?>
</header>
<div class="header_mobilenav"></div>

<!------------------- Header end------------------->
<div class="clear"></div>
<!------------------- Slider area------------------->
<!------------------- Slider area------------------->
<section class="inner_banner" style="background-image:url(<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg);">
<img src="images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Frequentlt Ask Questions</div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
<div class="flexcaption_darkshade"></div>    
</section>
<!------------- Slider area end----------------->

<div class="clear"></div>

<section class="inner_area">
	<div class="container">
		<div class="heading1" align="center">Coming Soon</div>
		<div>
			
		</div>
	</div>
	<div class="clear"></div>
</section>
<!--<section class="inner_area">
	<div class="container">
		<div class="faqarea">
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Lorem ipsum dolor sit amet, consectetur adipiscing elit</div>
				<div class="faq_details">Aliquam malesuada tortor eget dolor iaculis dictum. Sed ultricies cursus justo, rhoncus accumsan justo. Donec varius leo a tincidunt tincidunt. Curabitur molestie blandit scelerisque. In hac habitasse platea dictumst. Vestibulum malesuada elementum eleifend.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Integer elementum venenatis nisi et malesuada</div>
				<div class="faq_details">Aenean maximus consequat nisl in luctus. Sed vel ultrices diam. Nulla auctor euismod nisl quis congue. Nulla vitae suscipit turpis, vitae volutpat lectus.</div>
			</div>	
		</div>	
	</div>
	<div class="clear"></div>
</section>-->
<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
</body>
</html>