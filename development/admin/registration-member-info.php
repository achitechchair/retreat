<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	define("T","tbl_reg_profile_info");
	
	if(empty($_GET['ref_reg_id']) == false)
	{
		$_SESSION['this_ref_reg'] = $_GET['ref_reg_id'];
	}	
	if(empty($_SESSION['this_ref_reg']) == TRUE) $f->Redirect(WEBSITE_URL."/registrations.php?index=List");
	
	$sql = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['this_ref_reg'];
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
			
		 $f->Redirect(CP."?index=List&msg=update");
	}
	
	if(empty($_GET['msg'])==false)
	{
		require_once('common-msg.php');											
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>

</head>
<body>
<!--Header-part-->
<div id="header">
	<?php include_once('header.php');?>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
	<?php include_once('header_menu.php');?>
</div>
<!--close-top-Header-menu--> 
<!--sidebar-menu-->
<div id="sidebar">
	<?php include_once('admin-left-menu.php');?>
</div>
<!--sidebar-menu--> 

<!--main-container-part-->
<div id="content"> 
	<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Members in your party</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
		<table class="table" cellpadding="0" cellspacing="0" border="0" style="padding-bottom:0px; margin:0px;">
			<tr>
				<td>					
					<a href="registrations.php?index=List" class="btn btn-inverse"> <i class="icon-step-backward"></i> Back to Registration</a>
				</td>
			</tr>
		</table>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
      <form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="widget-box">
				<!--<div class="widget-title">
					<h5>Profile Information</h5>
				</div>-->
				<div class="widget-content nopadding">	            				
					<?php 
						$sql_mem = "SELECT * FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$_SESSION['this_ref_reg']." AND `mem_type`='Adult' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `reg_members_in_your_party_id` ASC";
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
               <div class="widget-title">
                  <h5>Primary Guest</h5>
               </div>
               <?php 
							}else{
					?>
               <div class="widget-title">
                  <h5>Guest <?php echo ($i + 1);?></h5>
               </div>              
               <?php 
							}
					?>		
               <div class="control-group">
						<label class="control-label">First Name:</label>
						<div class="controls">	
                  	<input name="first_name[]" type="text" value="<?php echo $f->getValue($row_mem['first_name']);?>" placeholder="" class="span11" required />				
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Middle Name:</label>
						<div class="controls">	
                  	<input name="middle_name[]" type="text" value="<?php echo $f->getValue($row_mem['middle_name']);?>" placeholder="" class="span11" />				
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Last Name:</label>
						<div class="controls">	
                  	<input name="last_name[]" type="text" value="<?php echo $f->getValue($row_mem['last_name']);?>" placeholder="" class="span11" required />			
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Gender:</label>
						<div class="controls">	
                  	<select name="gender[]" class="span11" required>
                        <option value="">Select</option>
                        <option value="Male"<?php if($f->getValue($row_mem['gender']) == 'Male') echo ' selected';?>>Male</option>
                        <option value="Female"<?php if($f->getValue($row_mem['gender']) == 'Female') echo ' selected';?>>Female</option>
                     </select>			
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Age:</label>
						<div class="controls">                  	
                     <select name="age[]" id="age" class="span11" required>
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
					</div>
               
               <div class="control-group">
						<label class="control-label">Food Preference:</label>
						<div class="controls">	
                  	<select name="food_preference[]" class="span11" required>
                        <option value="">Select</option>
                        <option value="Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Veg') echo ' selected';?>>Veg</option>
                        <option value="Non-Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Non-Veg') echo ' selected';?>>Non-Veg</option>
                     </select>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">&nbsp;</label>
						<div class="controls">
                  	<p>Is this person participating in Youth Activities? (Only if age is 18 and 29)</p>
                  	<input name="youth_activity_<?php echo $i;?>" class="span11 youth_activity_<?php echo $i;?>" type="radio" value="Yes"<?php if($f->getValue($row_mem['youth_activity']) == 'Yes') echo ' checked';?> />  YES &nbsp;&nbsp;&nbsp; <input name="youth_activity_<?php echo $i;?>" class="span11 youth_activity_<?php echo $i;?>" type="radio" value="No" <?php if($f->getValue($row_mem['youth_activity']) == 'No') echo ' checked';?> />  NO
						</div>
					</div>
               <div id="id_youth_activity_<?php echo $i;?>">
                  <div class="control-group">
                     <label class="control-label">&nbsp;</label>
                     <div class="controls">	
                        <p>Are you interested in participating in youth dance?</p>
                        <select name="youth_dance[]" class="all_youth_activity_<?php echo $i;?>">
                           <option value="">Select</option>
                           <option value="Yes"<?php if($f->getValue($row_mem['youth_dance']) == 'Yes') echo ' selected';?>>YES</option>
                           <option value="No"<?php if($f->getValue($row_mem['youth_dance']) == 'No') echo ' selected';?>>NO</option>
                        </select>
                     </div>
                  </div>
                  
                  <div class="control-group">
                     <label class="control-label">T Shirt Size:</label>
                     <div class="controls">                  	
                         <select name="tshirt_size[]" class="span11 all_youth_activity_<?php echo $i;?>">
                           <option value="">Select</option>
                           <option value="X Small"<?php if($f->getValue($row_mem['tshirt_size']) == 'X Small') echo ' selected';?>>X Small</option>
                           <option value="Small"<?php if($f->getValue($row_mem['tshirt_size']) == 'Small') echo ' selected';?>>Small</option>
                           <option value="Medium"<?php if($f->getValue($row_mem['tshirt_size']) == 'Medium') echo ' selected';?>>Medium</option>
                           <option value="Large"<?php if($f->getValue($row_mem['tshirt_size']) == 'Large') echo ' selected';?>>Large</option>
                           <option value="X Large"<?php if($f->getValue($row_mem['tshirt_size']) == 'X Large') echo ' selected';?>>X Large</option>
                        </select>
                     </div>
                  </div>
                  
                  <div class="control-group">
                     <label class="control-label">Phone Number:</label>
                     <div class="controls">                  	
                        <input name="phone[]" type="tel" value="<?php echo $f->getValue($row_mem['phone']);?>" placeholder="" class="span11 all_youth_activity_<?php echo $i;?>" />
                     </div>
                  </div>
                  
                  <div class="control-group">
                     <label class="control-label">Email:</label>
                     <div class="controls">                  	
                        <input name="email[]" type="email" value="<?php echo $f->getValue($row_mem['email']);?>" placeholder="" class="span11 email all_youth_activity_<?php echo $i;?>" />
                     </div>
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
               <?php 
							$i++;
						}
					?>
               
               <?php
					if($number_of_child_0_5 > 0)
		 			{ 
						$ii = 100;
						$count = 1;
						$sql_mem = "SELECT * FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$_SESSION['this_ref_reg']." AND `mem_type`='Child' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `reg_members_in_your_party_id` ASC";
						$res_mem = $db->get($sql_mem);
						$i = 0;
						while($row_mem = $db->fetch_array($res_mem))
                  {
					?>
               <input type="hidden" name="reg_members_in_your_party_id_<?php echo $ii;?>" value="<?php echo $f->getValue($row_mem['reg_members_in_your_party_id']);?>">
                            
               <div class="widget-title">
                  <h5>Child Guest <?php echo ($count);?></h5>
               </div>              
              	
               <div class="control-group">
						<label class="control-label">First Name:</label>
						<div class="controls">	
                  	<input name="first_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['first_name']);?>" placeholder="" class="span11" required />				
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Middle Name:</label>
						<div class="controls">	
                  	<input name="middle_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['middle_name']);?>" placeholder="" class="span11" />				
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Last Name:</label>
						<div class="controls">	
                  	<input name="last_name_<?php echo $ii;?>" type="text" value="<?php echo $f->getValue($row_mem['last_name']);?>" placeholder="" class="span11" required />			
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Gender:</label>
						<div class="controls">	
                  	<select name="gender_<?php echo $ii;?>" class="span11" required>
                        <option value="">Select</option>
                        <option value="Male"<?php if($f->getValue($row_mem['gender']) == 'Male') echo ' selected';?>>Male</option>
                        <option value="Female"<?php if($f->getValue($row_mem['gender']) == 'Female') echo ' selected';?>>Female</option>
                     </select>			
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Age:</label>
						<div class="controls">                  	
                     <select name="age_<?php echo $ii;?>" id="age" class="span11" required>
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
					</div>
               
               <div class="control-group">
						<label class="control-label">Food Preference:</label>
						<div class="controls">	
                  	<select name="food_preference_<?php echo $ii;?>" class="span11" required>
                        <option value="">Select</option>
                        <option value="Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Veg') echo ' selected';?>>Veg</option>
                        <option value="Non-Veg"<?php if($f->getValue($row_mem['food_preference']) == 'Non-Veg') echo ' selected';?>>Non-Veg</option>
                     </select>
						</div>
					</div>
                <?php
								$ii++;
								$count++;
							}
						}
                ?>	
               <div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnSubmit" id="btnSubmit" type="submit" value="Update" class="btn btn-success" />
								
							</div>
						</div>
					</div>              
				</div>
			</div>
		</div>		
</form>	
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<!--<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
-->
</body>
</html>
