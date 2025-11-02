<?php 
	require_once("includes/config.inc.php");
	
	$testimonials_id = $f->EncryptDecrypt($_GET['id'], 'decrypt');
	if(empty($testimonials_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/updates");
	
	$sql = "SELECT * FROM `tbl_testimonials` WHERE `testimonials_id`=".$testimonials_id." AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `dated` DESC";
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	
	if($rec == 0) $f->Redirect(MAIN_WEBSITE_URL."/updates");
	$row = $db->fetch_array($res);
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
		<div class="StaticContent">
			<h1 class="heading1"><?php echo $f->getValue($row['testimonials_title']);?></h1>
			<p><?php echo $f->getHTMLDecode($row['testimonials_text_long']);?></p>
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