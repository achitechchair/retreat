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
                <div class="flexcaption_style4">Updates</div>
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
		<div class="updates_area">
			<?php
				$sql = "SELECT * FROM `tbl_testimonials` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `dated` DESC";
				$res = $db->get($sql);
				while($row = $db->fetch_array($res))
				{
			?>
			<div class="updates_block">
				<div class="updates_block_heading"><?php echo $f->getValue($row['testimonials_title']);?> <span>(<?php echo date("F d, Y", strtotime($row['dated']))?>)</span></div>
				<div>
					<?php echo nl2br($f->getValue($row['testimonials_text']));?>
				</div>
				<div class="clear"></div>
				<div class="button"><a href="<?php echo MAIN_WEBSITE_URL;?>/update-details/?id=<?php echo $f->EncryptDecrypt($row['testimonials_id']);?>">More</a></div>
				<div class="clear"></div>
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