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
	$total_persion = $row['total_persion'];
	$number_of_child_0_5 = $row['number_of_child_0_5'];
	$max_chield = 100 + ($number_of_child_0_5);
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		 $sql_del = "DELETE FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$reg_id;
		 $db->get($sql_del);
			
		 // Adult entry =============================================================================================================	
		 for($i=0; $i<$total_persion; $i++)
       {
			$data_array = array(
					"reg_id" => $reg_id, 
					"mem_type" => $f->setValue($_POST['mem_type'][$i]),
					"first_name" => ($_POST['first_name'][$i]) ? ($f->setValue($_POST['first_name'][$i])) : ('NULL'),
					"middle_name" => ($_POST['middle_name'][$i]) ? ($f->setValue($_POST['middle_name'][$i])) : ('NULL'),
					"last_name" => ($_POST['last_name'][$i]) ? ($f->setValue($_POST['last_name'][$i])) : ('NULL'),
					"gender" => ($_POST['gender'][$i]) ? ($f->setValue($_POST['gender'][$i])) : ('NULL'),
					"age" => ($_POST['age'][$i]) ? ($f->setValue($_POST['age'][$i])) : ('NULL'),
					"food_preference" => ($_POST['food_preference'][$i]) ? ($f->setValue($_POST['food_preference'][$i])) : ('NULL'),
					
					"youth_activity" => $f->setValue($_POST['youth_activity_'.$i]),					
					"youth_dance" => ($_POST['youth_dance'][$i]) ? ($f->setValue($_POST['youth_dance'][$i])) : ('NULL'), 
					
					"tshirt_size" => ($_POST['tshirt_size'][$i]) ? ($f->setValue($_POST['tshirt_size'][$i])) : ('NULL'),
					"phone" => ($_POST['phone'][$i]) ? ($f->setValue($_POST['phone'][$i])) : ('NULL'),
					"email" => ($_POST['email'][$i]) ? ($f->setValue($_POST['email'][$i])) : ('NULL'),
				);
				
				$db->insert("tbl_reg_members_in_your_party", $data_array);
		 		unset($data_array);
		 }
		 
		 // Child entry ================================================================================================================
		 if($number_of_child_0_5 > 0)
		 {
			 for($ii=100; $ii<=($max_chield-1); $ii++)
			 {
				$data_array_2 = array(
						"reg_id" => $reg_id, 
						"mem_type" => $f->setValue($_POST['mem_type_'.$ii]),
						"first_name" => ($f->setValue($_POST['first_name_'.$ii])) ? ($f->setValue($_POST['first_name_'.$ii])) : ('NULL'),
						"middle_name" => ($f->setValue($_POST['middle_name_'.$ii])) ? ($f->setValue($_POST['middle_name_'.$ii])) : ('NULL'),
						"last_name" => ($f->setValue($_POST['last_name_'.$ii])) ? ($f->setValue($_POST['last_name_'.$ii])) : ('NULL'),
						"gender" => ($f->setValue($_POST['gender_'.$ii])) ? ($f->setValue($_POST['gender_'.$ii])) : ('NULL'),
						"age" => ($f->setValue($_POST['age_'.$ii])) ? ($f->setValue($_POST['age_'.$ii])) : ('NULL'),
						"food_preference" => ($f->setValue($_POST['food_preference_'.$ii])) ? ($f->setValue($_POST['food_preference_'.$ii])) : ('NULL'),
						
						"youth_activity" => 'No'
					);
					
					$db->insert("tbl_reg_members_in_your_party", $data_array_2);
					unset($data_array);
			 }
		 }
		 
		 $f->Redirect(MAIN_WEBSITE_URL."/registration-family-info");
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
		<div class="inner_container" style="max-width:100%;">
			<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:30px;">Members in your party</div>
          <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-member-info">
            <div class="register_area">
               <?php 
                  for($i=0; $i<$total_persion; $i++)
                  {
                     if($i == 0)
                     {
               ?>
               <div style="font-size:25px; color: #2F2D9B; font-weight: 600; text-transform: uppercase;">Primary Guest</div>
               <div style="font-size:18px; padding-bottom: 30px;">Information about the Primary Guest Responsible for this Registration</div>
               <?php 
                     }else{
               ?>	
              <div style="font-size:25px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom: 20px;">Guest <?php echo ($i + 1);?></div>
               <?php 
                     }
               ?>	
               <input type="hidden" name="mem_type[]" value="Adult">	
               <div class="row">
                  <div class="col-md-4"><p class="style1">First Name <span>*</span></p><input name="first_name[]" type="text" value="" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Middle Name</p><input name="middle_name[]" type="text" value="" placeholder="" class="input3" /></div>	
                  <div class="col-md-4"><p class="style1">Last Name <span>*</span></p><input name="last_name[]" type="text" value="" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Gender <span>*</span></p>
                     <select name="gender[]" class="input3" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
                  <div class="col-md-4"><p class="style1">Age <span>*</span></p>                  
                  <select name="age[]" id="age" class="input3" required>
                        <option value="">Select</option>
                           <?php 
										for($age=0; $age<=18; $age++)
										{
                           ?>
                           <option value="<?php echo $age;?>"<?php if($age == $_POST['age'] && $_SERVER["REQUEST_METHOD"] == "POST") echo ' selected';?>><?php echo $age;?><?php if($age == '18') echo '+';?></option>
                           <?php
                           	}
                           ?>                           
                  </select>
                  </div>	
                  <div class="col-md-4"><p class="style1">Food Preference <span>*</span></p>
                     <select name="food_preference[]" class="input3" required>
                        <option value="">Select</option>
                        <option value="Veg">Veg</option>
                        <option value="Non-Veg">Non-Veg</option>
                     </select>
                  </div>
                  <div class="col-md-12"><p class="style1">Is this person participating in Youth Activities? (Only if age is 18 to 29) <span>*</span></p>
                     <div><input name="youth_activity_<?php echo $i;?>" type="radio" value="Yes" class="youth_activity_<?php echo $i;?>" />  YES &nbsp;&nbsp;&nbsp; <input name="youth_activity_<?php echo $i;?>" type="radio" value="No" class="youth_activity_<?php echo $i;?>" checked />  NO</div>
                  </div>
                  <div class="clear"></div>
                  
                  <div id="id_youth_activity_<?php echo $i;?>" style="display:none;">
                  <br>
                        <div class="col-md-6"><p class="style1">Are you interested in participating in youth dance <span>*</span></p>
                           <select name="youth_dance[]" class="input3 all_youth_activity_<?php echo $i;?>">
                              <option value="">Select</option>
                              <option value="Yes">YES</option>
                              <option value="No">NO</option>
                           </select>
                        </div>
                        <div class="col-md-6"><p class="style1">T Shirt Size <span>*</span></p>
                           <select name="tshirt_size[]" class="input3 all_youth_activity_<?php echo $i;?>">
                              <option value="">Select</option>
                              <option value="X Small">X Small</option>
                              <option value="Small">Small</option>
                              <option value="Medium">Medium</option>
                              <option value="Large">Large</option>
                              <option value="X Large">X Large</option>
                           </select>
                        </div>
                        <div class="col-md-6"><p class="style1">Phone Number of the Youth Member <span>*</span></p><input name="phone[]" type="tel" value="" placeholder="" class="input3 all_youth_activity_<?php echo $i;?>" /></div>
                        <div class="col-md-6"><p class="style1">Email of the Youth Member <span>*</span></p><input name="email[]" type="email" value="" placeholder="" class="input3 email all_youth_activity_<?php echo $i;?>" /></div>
               	</div>
                  <script type="text/javascript">
							 $(document).ready(function() {
								 
								 $(".all_youth_activity_<?php echo $i;?>").attr("required", false);
								  
								 $(".youth_activity_<?php echo $i?>").click(function(){
									 var is_youth_activity = $(this).val();
									 if(is_youth_activity == 'No')
									 {
										 $(".all_youth_activity_<?php echo $i;?>").val("");
										 $(".all_youth_activity_<?php echo $i;?>").attr("required", false);
										 
										 $("#id_youth_activity_<?php echo $i;?>").css("display", "none");
									 }else{
										 $("#id_youth_activity_<?php echo $i;?>").css("display", "");
										 $(".all_youth_activity_<?php echo $i;?>").attr("required", true);
									 }
								 });
							 });
						</script>
               </div>
               <br>
               <?php
                     if($i != ($total_persion-1))
                     {
               ?>
               <hr>
               <br>            
               <?php
                     }
                  }
               ?> 
               <?php					
						if($number_of_child_0_5 > 0)
						{							
							$count = 1;
							for($ii=100; $ii<=($max_chield-1); $ii++)
							{
					?>	
               <hr>
               <br> 
               <div style="font-size:25px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom: 20px;">Child Guest <?php echo ($count);?></div>
               <input type="hidden" name="mem_type_<?php echo $ii;?>" value="Child">
               <div class="row">
                  <div class="col-md-4"><p class="style1">First Name <span>*</span></p><input name="first_name_<?php echo $ii;?>" type="text" value="" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Middle Name</p><input name="middle_name_<?php echo $ii;?>" type="text" value="" placeholder="" class="input3" /></div>	
                  <div class="col-md-4"><p class="style1">Last Name <span>*</span></p><input name="last_name_<?php echo $ii;?>" type="text" value="" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Gender <span>*</span></p>
                     <select name="gender_<?php echo $ii;?>" class="input3" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
                  <div class="col-md-4"><p class="style1">Age <span>*</span></p>                  
                  <select name="age_<?php echo $ii;?>" id="age" class="input3" required>
                        <option value="">Select</option>
                           <?php 
										for($age=0; $age<=5; $age++)
										{
                           ?>
                           <option value="<?php echo $age;?>"<?php if($age == $_POST['age'] && $_SERVER["REQUEST_METHOD"] == "POST") echo ' selected';?>><?php echo $age;?><?php if($age == '18') echo '+';?></option>
                           <?php
                           	}
                           ?>                           
                  </select>
                  </div>	
                  <div class="col-md-4"><p class="style1">Food Preference <span>*</span></p>
                     <select name="food_preference_<?php echo $ii;?>" class="input3" required>
                        <option value="">Select</option>
                        <option value="Veg">Veg</option>
                        <option value="Non-Veg">Non-Veg</option>
                     </select>
                  </div>
                  <div class="clear"></div>
               </div>
               <br />
					<?php
								$count++;
							}
						}
					?>	
               <div class="clear"></div>
               <div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-packages'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="Next" class="submit" /></div>
            </div>
         </form>
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