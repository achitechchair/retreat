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
<img src="<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Registration FAQ</div>
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
		<div class="faqarea">
      	<?php 
				$sql_faq = "SELECT * FROM `tbl_registration_faq` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
				$res_faq = $db->get($sql_faq);
				while($row_faq = $db->fetch_array($res_faq))
				{
			?>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading"><?php echo $f->getValue($row_faq['faq_title']);?></div>
				<div class="faq_details"><?php echo $f->getHTMLDecode($row_faq['faq_desc']);?></div>
			</div>
			<?php 
				}
			?>	
		</div>	
	</div>
	<div class="clear"></div>
</section>

<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
</body>
</html>