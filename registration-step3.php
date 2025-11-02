<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	if(empty($_SESSION['reg_id']) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	
	$reg_id = $_SESSION['reg_id'];
	$no_of_dinner_people = "";
	$no_of_dinner_people_children = "";
	
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
	$number_of_child_0_5 = $row['number_of_child_0_5']; 
	
	$total_y_persion = $number_of_people_adult + $number_of_child_6_17;
	
	
	
	if(empty($row['no_of_dinner_people']) == FALSE)
	{
		$no_of_dinner_people = $row['no_of_dinner_people'];
	}
	if(empty($row['no_of_dinner_people_children']) == FALSE)
	{
		$no_of_dinner_people_children = $row['no_of_dinner_people_children'];
	}
	
	$sql_home = "SELECT * FROM `tbl_reg_home_page` WHERE `reg_home_page_id`='1'";
	$res_home = $db->get($sql_home,__FILE__,__LINE__);
	$row_home = $db->fetch_array($res_home);
	$db->free_result($res_home);
	
	if(empty($_POST['btnSubmitSearch']) == FALSE)
	{
		$no_of_dinner_people = $_POST['no_of_dinner_people'];
		$no_of_dinner_people_children = $_POST['no_of_dinner_people_children'];
		
		if($total_y_persion >= $_POST['no_of_dinner_people'] && $number_of_child_0_5 >= $_POST['no_of_dinner_people_children'])
		{
			$data = array(						
					'no_of_dinner_people' => ($_POST['no_of_dinner_people']) ? ($f->setValue($_POST['no_of_dinner_people'])) : ('0'),
					'no_of_dinner_people_children' => ($_POST['no_of_dinner_people_children']) ? ($f->setValue($_POST['no_of_dinner_people_children'])) : ('0')
			);	
			
			$db->update(TABLE, $data, "reg_id", $_SESSION['reg_id']);
			$f->Redirect(MAIN_WEBSITE_URL."/registration-packages");
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
			<form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-step3">
         <div class="register_area">
         	<?php if(empty($msg) == false){ ?>
            <div align="center" class="display_message"><?php echo $msg;?></div>
            <p></p>
        		<?php } ?>
				<div class="row">
					<div class="col-md-12">
						<p class="style1 font_17">Enter the number of people attending Banquet dinner on Thursday, July 4th</p>						          
                  <input type="text" placeholder="Enter total count (ages 6 and above - $<?php echo $f->getValue($AdminSettings['banquet_dinner_price'])?> per person)" name="no_of_dinner_people" value="<?php echo $no_of_dinner_people?>" class="input3 number_only" required>
                  <input type="text" placeholder="Enter total count  (age 5 and below)" name="no_of_dinner_people_children" value="<?php echo $no_of_dinner_people_children?>" class="input3 number_only">
					</div>
				</div>
				<div><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-step2'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmitSearch" type="submit" value="Next" class="submit" /></div>
			</div>
         </form>        
         <div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-top: 30px;"><?php echo $f->getHTMLDecode($row_home['page_desc_3']);?></div>
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