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
      	<div class="StaticContent">
         	<b>REGISTRATION HAS CLOSED BUT YOU CAN STILL ATTEND THE EVENT WITH THE FOLLOWING RESTRICTIONS.</b>
			</div> 
         <P>&nbsp;</P>
         <p><span>&#10803;</span> Cost per person attending is <b>$545</b> which includes banquet dinner on Thursday, <b>July 4th</b>.</p>   	
         <p><span>&#10803;</span> Room is not included.</p>  
         <p><span>&#10803;</span> You photo or name won't get into the Retreat Souvenir book.</p>  
         <p><span>&#10803;</span> Please reach out to <a href="mailto:miretreat.registration@achi.org">miretreat.registration@achi.org</a> for further assistance.</p> 
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