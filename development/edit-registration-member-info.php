<?php 
	require_once("includes/config.inc.php");	
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');	
	
	if($_SESSION['reg_status']!='Active') $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$sql = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['reg_id_edit'];
	$res = $db->get($sql);	
	$row = $db->fetch_array($res);
	$total_persion = $row['total_persion'];
	$number_of_child_0_5 = $row['number_of_child_0_5'];
	$max_chield = 100 + ($number_of_child_0_5);	
	
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		for($i=0; $i<$total_persion; $i++)
      {
			$reg_members_in_your_party_id = $f->setValue($_POST['reg_members_in_your_party_id'][$i]);
			
			$data_array = array(					
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
				
				$db->update("tbl_reg_members_in_your_party", $data_array, "reg_members_in_your_party_id", $reg_members_in_your_party_id);
		 		unset($data_array);
		 }
		 
		 
		// Child entry ================================================================================================================
		 if($number_of_child_0_5 > 0)
		 {
			 for($ii=100; $ii<=($max_chield-1); $ii++)
			 {
					$reg_members_in_your_party_id = $f->setValue($_POST['reg_members_in_your_party_id_'.$ii]);
					
					$data_array = array(					
							"first_name" => ($_POST['first_name_'.$ii]) ? ($f->setValue($_POST['first_name_'.$ii])) : ('NULL'),
							"middle_name" => ($_POST['middle_name_'.$ii]) ? ($f->setValue($_POST['middle_name_'.$ii])) : ('NULL'),
							"last_name" => ($_POST['last_name_'.$ii]) ? ($f->setValue($_POST['last_name_'.$ii])) : ('NULL'),
							"gender" => ($_POST['gender_'.$ii]) ? ($f->setValue($_POST['gender_'.$ii])) : ('NULL'),
							"age" => ($_POST['age_'.$ii]) ? ($f->setValue($_POST['age_'.$ii])) : ('NULL'),
							"food_preference" => ($_POST['food_preference_'.$ii]) ? ($f->setValue($_POST['food_preference_'.$ii])) : ('NULL')
						);				
						
						$db->update("tbl_reg_members_in_your_party", $data_array, "reg_members_in_your_party_id", $reg_members_in_your_party_id);
						unset($data_array);
				}
		 }
			
		 $f->Redirect(MAIN_WEBSITE_URL."/edit-registration-member-info/?success=1");	
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
<!------------------- Slider area------------------->
<section class="inner_banner" style="background-image:url(<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg);">
<img src="<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Modify Member Information</div>
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
		<div class="message_area">
			<div class="message_right">
				<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:30px;">Members in your party</div>			
				 <?php if(empty($msg) == false){ ?>
               <div align="center" class="display_message"><?php echo $msg;?></div>
               <p></p>
            <?php } ?>
            <?php 
               if($_GET['success'] == '1')
               {
            ?>
             <div align="center"><?php echo $f->getHtmlMessage('The form has been successfully updated.');?></div>
             <?php } ?>
         	<form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/edit-registration-member-info">
            <div class="register_area">
                <?php 
                  $sql_mem = "SELECT * FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mem_type`='Adult' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `reg_members_in_your_party_id` ASC";
						$res_mem = $db->get($sql_mem);
						$i = 0;
						while($row_mem = $db->fetch_array($res_mem))
                  {
					 ?>
                <input type="hidden" name="reg_members_in_your_party_id[]" value="<?php echo $f->getValue($row_mem['reg_members_in_your_party_id']);?>">
                <?php		
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
               <div class="row">
                  <div class="col-md-4"><p class="style1">First Name <span>*</span></p><input name="first_name[]" type="text" value="<?php echo $f->getValue($row_mem['first_name']);?>" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Middle Name</p><input name="middle_name[]" type="text" value="<?php echo $f->getValue($row_mem['middle_name']);?>" placeholder="" class="input3" /></div>	
                  <div class="col-md-4"><p class="style1">Last Name <span>*</span></p><input name="last_name[]" type="text" value="<?php echo $f->getValue($row_mem['last_name']);?>" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Gender <span>*</span></p>
                     <select name="gender[]" class="input3" required>
                        <option value="">Select</option>
                        <option value="Male"<?php if($f->getValue($row_mem['gender']) == 'Male') echo ' selected';?>>Male</option>
                        <option value="Female"<?php if($f->getValue($row_mem['gender']) == 'Female') echo ' selected';?>>Female</option>
                     </select>
                  </div>
                  <div class="col-md-4"><p class="style1">Age <span>*</span></p>
                  	<select name="age[]" id="age" class="input3" required>
                        <option value="">Select</option>
                           <?php 
										for($age=0; $age<=18; $age++)
										{
                           ?>
                           <option value="<?php echo $age;?>"<?php if($age == $f->getValue($row_mem['age'])) echo ' selected';?>><?php echo $age;?><?php if($age == '18') echo '+';?></option>
                           <?php
                           	}
                           ?>
                  	</select>
                  </div>	
                  <div class="col-md-4"><p class="style1">Food Preference <span>*</span></p>
                     <select name="food_preference[]" class="input3" required>
                        <option value="">Select</option>
                        <option value="Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Veg') echo ' selected';?>>Veg</option>
                        <option value="Non-Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Non-Veg') echo ' selected';?>>Non-Veg</option>
                     </select>
                  </div>
                  <div class="col-md-12"><p class="style1">Is this person participating in Youth Activities? (Only if age is 18 to 29) <span>*</span></p>
                     <div><input name="youth_activity_<?php echo $i;?>" type="radio" value="Yes" class="youth_activity_<?php echo $i;?>"<?php if($f->getValue($row_mem['youth_activity']) == 'Yes') echo ' checked';?> />  YES &nbsp;&nbsp;&nbsp; <input name="youth_activity_<?php echo $i;?>" type="radio" value="No" class="youth_activity_<?php echo $i;?>"<?php if($f->getValue($row_mem['youth_activity']) == 'No') echo ' checked';?> />  NO</div>
                  </div>
                  <div class="clear"></div>                 
                  
                  <div id="id_youth_activity_<?php echo $i;?>">
                     <br>
                     <div class="col-md-6"><p class="style1">Are you interested in participating in youth dance <span>*</span></p>
                        <select name="youth_dance[]" class="input3 all_youth_activity_<?php echo $i;?>">
                           <option value="">Select</option>
                           <option value="Yes"<?php if($f->getValue($row_mem['youth_dance']) == 'Yes') echo ' selected';?>>YES</option>
                           <option value="No"<?php if($f->getValue($row_mem['youth_dance']) == 'No') echo ' selected';?>>NO</option>
                        </select>
                     </div>
                     <div class="col-md-6"><p class="style1">T Shirt Size <span>*</span></p>
                        <select name="tshirt_size[]" class="input3 all_youth_activity_<?php echo $i;?>">
                           <option value="">Select</option>
                           <option value="X Small"<?php if($f->getValue($row_mem['tshirt_size']) == 'X Small') echo ' selected';?>>X Small</option>
                           <option value="Small"<?php if($f->getValue($row_mem['tshirt_size']) == 'Small') echo ' selected';?>>Small</option>
                           <option value="Medium"<?php if($f->getValue($row_mem['tshirt_size']) == 'Medium') echo ' selected';?>>Medium</option>
                           <option value="Large"<?php if($f->getValue($row_mem['tshirt_size']) == 'Large') echo ' selected';?>>Large</option>
                           <option value="X Large"<?php if($f->getValue($row_mem['tshirt_size']) == 'X Large') echo ' selected';?>>X Large</option>
                        </select>
                     </div>
                     <div class="col-md-6"><p class="style1">Phone Number of the Youth Member <span>*</span></p><input name="phone[]" type="tel" value="<?php echo $f->getValue($row_mem['phone']);?>" placeholder="" class="input3 all_youth_activity_<?php echo $i;?>" /></div>
                     <div class="col-md-6"><p class="style1">Email of the Youth Member <span>*</span></p><input name="email[]" type="email" value="<?php echo $f->getValue($row_mem['email']);?>" placeholder="" class="input3 email all_youth_activity_<?php echo $i;?>" /></div>
              		</div>
               </div>
               <script type="text/javascript">
							 $(document).ready(function() {
								 <?php 
								 	if($f->getValue($row_mem['youth_activity']) == 'No')
									{
								 ?>
								  $("#id_youth_activity_<?php echo $i;?>").css("display", "none");
								 <?php 
									}
								 ?>
								  <?php 
								 	if($f->getValue($row_mem['youth_activity']) == 'Yes')
									{
								 ?>	
								 $(".all_youth_activity_<?php echo $i;?>").attr("required", true);
								 <?php 
									}
								  ?>
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
               <br>
               <?php
                     if($i != ($total_persion-1))
                     {
               ?>
               <hr>
               <br>            
               <?php
                     }
							$i++;
                  }
               ?>              
               <hr>
               <br>
               <?php
					if($number_of_child_0_5 > 0)
		 			{ 
                  $sql_mem = "SELECT * FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mem_type`='Child' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `reg_members_in_your_party_id` ASC";
						$res_mem = $db->get($sql_mem);
						$ii = 100;
						$count = 1;
						while($row_mem = $db->fetch_array($res_mem))
                  {
					 ?>
                <div style="font-size:25px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom: 20px;">Child Guest <?php echo ($count);?></div>
                <input type="hidden" name="reg_members_in_your_party_id_<?php echo $ii;?>" value="<?php echo $f->getValue($row_mem['reg_members_in_your_party_id']);?>">
                <div class="row">
                  <div class="col-md-4"><p class="style1">First Name <span>*</span></p><input name="first_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['first_name']);?>" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Middle Name</p><input name="middle_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['middle_name']);?>" placeholder="" class="input3" /></div>	
                  <div class="col-md-4"><p class="style1">Last Name <span>*</span></p><input name="last_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['last_name']);?>" placeholder="" class="input3" required /></div>
                  <div class="col-md-4"><p class="style1">Gender <span>*</span></p>
                     <select name="gender_<?php echo $ii;?>" class="input3" required>
                        <option value="">Select</option>
                        <option value="Male"<?php if($f->getValue($row_mem['gender']) == 'Male') echo ' selected';?>>Male</option>
                        <option value="Female"<?php if($f->getValue($row_mem['gender']) == 'Female') echo ' selected';?>>Female</option>
                     </select>
                  </div>
                  <div class="col-md-4"><p class="style1">Age <span>*</span></p>
                  	<select name="age_<?php echo $ii;?>" id="age" class="input3" required>
                        <option value="">Select</option>
                           <?php 
										for($age=0; $age<=5; $age++)
										{
                           ?>
                           <option value="<?php echo $age;?>"<?php if($age == $f->getValue($row_mem['age'])) echo ' selected';?>><?php echo $age;?><?php if($age == '18') echo '+';?></option>
                           <?php
                           	}
                           ?>
                  	</select>
                  </div>	
                  <div class="col-md-4"><p class="style1">Food Preference <span>*</span></p>
                     <select name="food_preference_<?php echo $ii;?>" class="input3" required>
                        <option value="">Select</option>
                        <option value="Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Veg') echo ' selected';?>>Veg</option>
                        <option value="Non-Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Non-Veg') echo ' selected';?>>Non-Veg</option>
                     </select>
                  </div>
                </div>  
                <br />
                <?php
								$ii++;
								$count++;
							}
						}
                ?>				
               <div class="clear"></div>
               <div class=""><input name="btnSubmit" type="submit" value="Submit" class="submit1" /></div>
            </div>
         </form>
				
				<div class="clear"></div>
			</div>
			<div class="message_left">
				<?php include_once('registration-edit-left-menu.php');?>
			</div>
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