<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	//Registration closed redirect ------------------------------------------------
	//$f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	// ----------------------------------------------------------------------------
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$_SESSION['landing'] = '';
	
	if(empty($_SESSION['reg_id']) == FALSE)	
	{
		$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$_SESSION['reg_id'];
		$res = $db->get($sql);		
		$row = $db->fetch_array($res);
		
		$number_of_people_adult = $row['number_of_people_adult'];
		$number_of_child_6_17 = $row['number_of_child_6_17'];
		$number_of_child_0_5 = $row['number_of_child_0_5'];
		
	}else{
		
		$number_of_people_adult = $f->POST_VAL('number_of_people_adult');
		$number_of_child_6_17 = $f->POST_VAL('number_of_child_6_17');
		$number_of_child_0_5 = $f->POST_VAL('number_of_child_0_5');
		
	}
	
	if(empty($_POST['btnSubmitSearch']) == FALSE)
	{
		$data = array(						
				'number_of_people_adult' => ($_POST['number_of_people_adult']) ? ($f->setValue($_POST['number_of_people_adult'])) : ('0'),
				'number_of_child_6_17' => ($_POST['number_of_child_6_17']) ? ($f->setValue($_POST['number_of_child_6_17'])) : ('0'),
				'number_of_child_0_5' => ($_POST['number_of_child_0_5']) ? ($f->setValue($_POST['number_of_child_0_5'])) : ('0'),
				'ip_addr' => $_SERVER['REMOTE_ADDR']				
		);	
		
		if(empty($_SESSION['reg_id']) == TRUE) 
		{
			$res_inv = $db->get("SELECT GenerateInvNo()");
			$row_inv = $db->fetch_array($res_inv);
			$db->free_result($res_inv);		
			$db->next_result();					
			//$ref_number = "R".date('Ymd').$row_inv[0];
			$ref_number = "R".$row_inv[0];
			
			$data['ref_number'] = $ref_number;
			
			$db->insert(TABLE, $data);
			$reg_id = $db->last_insert_id();
			$_SESSION['reg_id'] = $reg_id;
		}else{
			$db->update(TABLE, $data, "reg_id", $_SESSION['reg_id']);
		}
		
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step2");
	}
	
	
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<script type="text/javascript">
$(document).ready(function(){
	$("#reg_frm").submit(function(){		
		var number_of_people_adult = $("#number_of_people_adult").val();
		var number_of_child_6_17 = $("#number_of_child_6_17").val();
		//var number_of_child_0_5 = $("#number_of_child_0_5").val();
		
		if(number_of_people_adult == 0 && number_of_child_6_17 == 0)
		{
			infoAlert("Alert", "Please choose any option for adult or ages between 6 and 17 for registration.");
			return false;
		}
		
	});
});		
</script>
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
      	
			<div style="font-size: 30px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 500; padding-bottom: 20px;">Welcome to Retreat Registration</div>
			<div style="font-size: 20px; font-weight: 400; padding-bottom: 30px; text-align:justify;">Enter the total number of people attending the Retreat including adults and kids of all ages. We need to collect information about each and every person attending the retreat due to legal requirements. There is no registration cost for children 5 and below.</div>
			<form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-step1">
         <div class="register_area">
				<div class="row">
					<div class="col-md-12">
						<p class="style1 font_17">No. of adults attending the retreat</p>
						 <select name="number_of_people_adult" id="number_of_people_adult" class="input3">                   	   
								<?php 
                           for($i=0;$i<=8;$i++)
                           {
                        ?>
                       <option value="<?php echo $i;?>"<?php if($number_of_people_adult == $i) echo ' selected';?>><?php echo $i;?></option>
                       <?php 
                           }
                        ?>	
                  </select>
                  
                  <p class="style1 font_17">No. of children attending the retreat (ages between 6 and 17)</p>
						 <select name="number_of_child_6_17" id="number_of_child_6_17" class="input3">                   	   
								<?php 
                           for($i=0;$i<=6;$i++)
                           {
                        ?>
                       <option value="<?php echo $i;?>"<?php if($number_of_child_6_17 == $i) echo ' selected';?>><?php echo $i;?></option>
                       <?php 
                           }
                        ?>	
                  </select>
                  
                  <p class="style1 font_17">No. of children attending the retreat (ages 5 and below)</p>
						 <select name="number_of_child_0_5" id="number_of_child_0_5" class="input3">                   	 
								<?php 
                           for($i=0;$i<=4;$i++)
                           {
                        ?>
                       <option value="<?php echo $i;?>"<?php if($number_of_child_0_5 == $i) echo ' selected';?>><?php echo $i;?></option>
                       <?php 
                           }
                        ?>	
                  </select>
					</div>
				</div>
				<div><input name="btnSubmitSearch" id="btnSubmitSearch" type="submit" value="Next" class="submit" /></div>
			</div>
         </form>
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