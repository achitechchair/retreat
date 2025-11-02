<?php 
	require_once("includes/config.inc.php");
	
	$sql_content = "SELECT * FROM `tbl_static_page_content` WHERE `page_id`='1'";
	$res_content = $db->get($sql_content,__FILE__,__LINE__);
	$row_content = $db->fetch_array($res_content);
	$db->free_result($res_content);
	
	$seo_page_title = $f->getValue($row_content['seo_page_title']);	
	$seo_meta_description = $f->getValue($row_content['seo_meta_desc']);
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
                <div class="flexcaption_style4">Terms &amp; Conditions</div>
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
		<!--<div class="heading1">Lorem ipsum dolor sit amet</div>-->
		<div class="StaticContent">
			<?php echo $f->getHTMLDecode($row_content['page_content']);?> 
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