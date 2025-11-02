<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$reg_id = $_SESSION['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{	
		$data_array = array(
			"interested_in_driving" => $f->setValue($_POST['interested_in_driving'])
		);
		
		$db->update(TABLE, $data_array, "reg_id", $_SESSION['reg_id']);
		
		$f->Redirect(MAIN_WEBSITE_URL."/registration-youth-networking-event");
	}
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
   	<form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-driving">
		<div class="inner_container" style="max-width: 700px;">
			<div style="font-size:25px;color:#2F2D9B; font-weight:600; text-transform:uppercase; padding-bottom:30px;">Transportation</div>
         
			<div class="register_area">
         	<p>If you are arriving by road, would you be interested in driving your own car between the hotel and the auditorium (30 min drive approx)?</p>
				<div><input name="interested_in_driving" type="radio" value="Yes" checked />  YES &nbsp;&nbsp;&nbsp; <input name="interested_in_driving" type="radio" value="No" />  NO</div>
				<br>
				<div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-family-info'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" id="" type="submit" value="Next" class="submit" /></div>
            
           
			</div>
		</div>
      </form>
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