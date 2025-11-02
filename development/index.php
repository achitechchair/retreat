<?php 
	require_once("includes/config.inc.php");	
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<script type="text/javascript">
$(document).ready(function() { 
	$('.pricing_reg').click(function () {
 		$("html, body").animate({ scrollTop: $(".pricing_section").offset().top}, 1000);	
	});
	
	if (location.hash != '') 
	{
    	$("html, body").animate({ scrollTop: $(".pricing_section").offset().top}, 1000);	
	}
});
</script>
</head>
<body>
<!-------------- Header ------------------->
<header>
	<?php include_once('header.php');?>
</header>
<div class="header_mobilenav"></div>

<!------------------- Header end------------------->
<div class="clear"></div>
<!------------------- Slider area------------------->
<section class="banner_area">
	<div id="featured_slide">
		<section class="flexslider">
			<ul class="slides">
				<li style="background:url(images/banner/slide1.jpg) no-repeat center center; background-size: cover;"><img src="images/banner/slide1.jpg" />
					<div class="flexcaption">
						<div class="container">
							<div class="flexcaption_area">
								<div class="flexcaption_style1" style="padding-bottom: 0px;"><img src="images/banner/content.png" alt=""></div>
								<!--<div class="flexcaption_style2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In lorem enim, facilisis non sagittis non, pellentesque at sem.</div>-->
								<div class="flexcaption_button"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Register Now</a></div>
							</div>
						</div>
					</div>
					<div class="flexcaption_darkshade"></div>
				</li>
			</ul>
		</section>
	</div>
	<div class="clear"></div>
</section>
<!------------- Slider area end----------------->

<section class="signature_section">
	<div class="">
	    <div class="signature_left">
			<div class="signature_left_content">
				<div class="signature_left_content_area">
					<div class="signature_text1">Registration is Now Open</div>
					<!--<div class="signature_text2">Register early to ensure your region gets adequate stage time for performances</div>-->
					<div class="signature_text2">
						Register early:
						<p><span>&#10803;</span> To ensure you have your room at the resort</p> 
						<p><span>&#10803;</span> To ensure your region gets adequate stage time for performances</p>
						<p><span>&#10803;</span> We have very limited accommodation and we expect retreat to sellout</p>
					</div>
					<div class="signature_text3">Early Bird Discount <br>upto $180 ends on Mar 6<sup>th</sup></div>
					<!-- <div class="signature_text4">FEB 28<sup>th</sup> to Mar 6<sup>th</sup></div> -->
					<div class="button1"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Register Now</a></div>	
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="signature_right">
			<div class="signature_right_content">
				<div class="signature_right_heading">Signature Events</div>
				<ul>
					<li>MEENAKSHI THIRUKALYANAM</li>
					<li>Sambhavami Yuge Yuge - Dasavatharam</li>
					<li>CHETTINAD BEATS</li>
					<li>ENTERTAINING REGIONAL PERFORMANCES</li>
					<li>A PERFECT 4DAY WEEKEND</li>
					<li>SUMPTUOUS FOOD</li>
					<li>NETWORKING, BREAKOUTS, SPORTS, GAMES AND A LOT MORE..<!-- <span>3+ days, several breakout sessions </span>--></li>
				</ul>	
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</section>

<section class="home_about_section">
	<div class="container">
	    <div class="home_about_top">
			<!-- <div class="home_about_heading"><span>About</span>retreat 2024</div> -->
			<div class="home_about_heading">Retreat 2024</div>
			<!--<div class="home_about_content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In lorem enim, facilisis non sagittis non, pellentesque at sem. Duis eget velit tempus, rutrum sem sed, molestie ipsum. Praesent ac elit suscipit, molestie dui ac, tempus tortor. Sed luctus congue ipsum, faucibus posuere mauris hendrerit sed.</div>
			<br>
			<div class="button1"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Register Now</a></div>-->
			<!-- <div style="font-size: 25px; font-weight: 600; line-height: 1.2;">???????????????????????????????????</div> -->
			<img src="images/retreat-2024.png" alt="">
			<div class="clear"></div>
		</div>
		<div class="home_about_bottom">
			<img src="images/about1.png" alt="">
			<img src="images/about2.png" alt="">
			<img src="images/about3.png" alt="">
		</div>
	</div>
	<div class="clear"></div>
</section>

<section class="pricing_section">
	<div class="container">
	    <div class="home_about_top">
			<div class="pricing_heading">Packages<span>July 4-7, 2024 - Thu to Sun</span></div>
			<div class="clear"></div>
		</div>
		<div id="carosul_wrapper">
			<div id="carosul1">
				<div class="previous_button1" id="previous_button1"></div>
				<div class="next_button1" id="next_button1"></div>
				<div class="containerr">
					<ul>
						<?php
							$sql_package = "SELECT * FROM `tbl_registration_packages` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
							$res_package = $db->get($sql_package);
							while($row_package = $db->fetch_array($res_package))
							{
						?>
						<li>
							<div class="pricing_block">
								<div class="pricing_block_heading1"><?php echo $f->getHTMLDecode($row_package['package_title']);?></div>
								<div class="pricing_block_heading2">$<?php echo floatval($f->getValue($row_package['package_price']));?></div>
								<div class="pricing_block_content">
									<div>
										 <?php echo $f->getHTMLDecode($row_package['package_desc']);?>
									</div>
									<div class="pricing_button"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Choose Now</a></div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>	
						</li>
						<?php 
							}
						?>						
					</ul>
				</div>
			</div>
		</div>
		
	</div>
	<div class="clear"></div>
</section>

<section class="joinus_section">
	<div class="container">
		<div class="joinus_area">
			<div class="joinus_left">
				<div class="joinus_heading">NSNA <span>Retreat 2024</span></div>
				<!-- <div>
					Lorem ipsum dolor sit amet, consectetur <br>
					adipiscing elit. In lorem enim, facilisis non <br>
					sagittis non, pellentesque at sem.
				</div> -->
				<div class="joinus_heading1"><img src="images/date.png" alt=""/> July 4-7, 2024 - Thu to Sun</div>
				<div class="joinus_heading2"><img src="images/address.png" alt=""/> 
					Stage Events at Ford Community & Performing Arts Center
					<span>15801 Michigan Ave, Dearborn, MI 48126</span>
				</div>
				<div class="button1"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Register Now</a></div>	
				<div class="clear"></div>
			</div>
			<div class="joinus_right">&nbsp;</div>	
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</section>

<!--<section class="faq_section">
	<div class="faq_left">&nbsp;</div>
	<div class="faq_right">
		<div class="faq_right_content">
			<div class="heading">Frequently Ask Question</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Lorem ipsum dolor sit amet, consectetur adipiscing elit</div>
				<div class="faq_details">Aliquam malesuada tortor eget dolor iaculis dictum. Sed ultricies cursus justo, rhoncus accumsan justo. Donec varius leo a tincidunt tincidunt. Curabitur molestie blandit scelerisque. In hac habitasse platea dictumst. Vestibulum malesuada elementum eleifend.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Integer elementum venenatis nisi et malesuada</div>
				<div class="faq_details">Aenean maximus consequat nisl in luctus. Sed vel ultrices diam. Nulla auctor euismod nisl quis congue. Nulla vitae suscipit turpis, vitae volutpat lectus.</div>
			</div>	
         <div style="color:#FFF; font-size:25px; padding-bottom:50px; font-weight:bold;">Coming Soon</div>
			<div class="button1"><a href="">Read More</a></div>	
		</div>
		<div class="clear"></div>
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