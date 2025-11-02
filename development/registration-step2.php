<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	if(empty($_SESSION['reg_id']) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	$no_of_people_youth_activities = "";
	$reg_id = $_SESSION['reg_id'];
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	$number_of_people_adult = $row['number_of_people_adult'];
	$number_of_child_6_17 = $row['number_of_child_6_17'];
	$total_y_persion = $number_of_people_adult + $number_of_child_6_17;
	
	if(empty($row['no_of_people_youth_activities']) == FALSE)
	{
		$no_of_people_youth_activities = $row['no_of_people_youth_activities'];
	}
	
	$sql_home = "SELECT * FROM `tbl_reg_home_page` WHERE `reg_home_page_id`='1'";
	$res_home = $db->get($sql_home,__FILE__,__LINE__);
	$row_home = $db->fetch_array($res_home);
	$db->free_result($res_home);	
	
	if(empty($_POST['btnSubmitSearch']) == FALSE)
	{
		$no_of_people_youth_activities = $_POST['no_of_people_youth_activities'];
		if($total_y_persion >= $_POST['no_of_people_youth_activities'])
		{
			$data = array(						
					'no_of_people_youth_activities' => ($_POST['no_of_people_youth_activities']) ? ($f->setValue($_POST['no_of_people_youth_activities'])) : ('0')
			);				
			$db->update(TABLE, $data, "reg_id", $_SESSION['reg_id']);
			$f->Redirect(MAIN_WEBSITE_URL."/registration-step3");
			
		}else{
			$msg = $f->getHtmlErrorSmall("Please enter valid people.");	
		}
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
		<div class="inner_container" style="max-width: 600px;">			
			<form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-step2">
         <div class="register_area">
         	<?php if(empty($msg) == false){ ?>
            <div align="center" class="display_message"><?php echo $msg;?></div>
            <p></p>
        		<?php } ?>
				<div class="row">
					<div class="col-md-12">
						<p class="style1 font_17">How many people are planning to register for Youth Activities (age between 18 and 29 inclusive)</p>						          
                  <input type="text" name="no_of_people_youth_activities" value="<?php echo $no_of_people_youth_activities;?>" class="input3 number_only">
					</div>
				</div>
				<div><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-step1'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmitSearch" type="submit" value="Next" class="submit" /></div>
			</div>
         </form>        
         <div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-top: 30px; text-align:justify;"><?php echo $f->getHTMLDecode($row_home['page_desc_2']);?></div>
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