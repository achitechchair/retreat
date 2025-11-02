<?php 
	require_once("includes/config.inc.php");
	
	//$_SESSION['reg_id'] = "";
	
	$sql_home = "SELECT * FROM `tbl_reg_home_page` WHERE `reg_home_page_id`='1'";
	$res_home = $db->get($sql_home,__FILE__,__LINE__);
	$row_home = $db->fetch_array($res_home);
	$db->free_result($res_home);
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
<section class="inner_area"> 
	<div class="container"> 
		<div class="inner_container">
      	<div class="StaticContent"><?php echo $f->getHTMLDecode($row_home['page_desc_1']);?></div>      	
         <div class="registration_buttonarea">
            <input class="submit1" type="button" name="btnsubmit" value="New Registration" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-step1'" style="background-image:none;" />
            <input class="submit1" type="button" name="btnsubmit" value="Edit Registration" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/login-to-edit-registration'" style="background-image:none;" />
		</div>		
		</div>
		<div class="clear"></div>
   </div>
</section>
<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
</body>
</html>