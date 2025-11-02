<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$reg_id = $_SESSION['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	}
	$row = $db->fetch_array($res);
	
	$number_of_people_adult = $row['number_of_people_adult'];
	$number_of_child_6_17 = $row['number_of_child_6_17'];
	$number_of_child_0_5 = $row['number_of_child_0_5'];
	
	$no_of_people_youth_activities = $row['no_of_people_youth_activities'];
	$no_of_dinner_people = $row['no_of_dinner_people'];
	$no_of_dinner_people_children = $row['no_of_dinner_people_children'];		
	
	$total_persions_cal = ($number_of_people_adult + $number_of_child_6_17);	
		
	if(empty($_POST['btnSubmit']) == FALSE)
	{		
		$data = array(						
				'total_price_pack' => ($_POST['total_price_pack_hd']) ? ($f->setValue($_POST['total_price_pack_hd'])) : ('0'),
				'total_persion' => ($_POST['total_persion_hd']) ? ($f->setValue($_POST['total_persion_hd'])) : ('0'),
				'youth_activity_price' => ($_POST['youth_activity_price_hd']) ? ($f->setValue($_POST['youth_activity_price_hd'])) : ('0'),
				'banquet_dinner_price' => ($_POST['banquet_dinner_price_hd']) ? ($f->setValue($_POST['banquet_dinner_price_hd'])) : ('0'),
				'no_of_people_youth_extra' => ($_POST['no_of_people_youth_extra']) ? ($f->setValue($_POST['no_of_people_youth_extra'])) : ('0'),
				'no_of_dinner_people_extra' => ($_POST['no_of_dinner_people_extra']) ? ($f->setValue($_POST['no_of_dinner_people_extra'])) : ('0'),
				'total_price' => ($_POST['total_price_hd']) ? ($f->setValue($_POST['total_price_hd'])) : ('0')									
		);	
		
		$db->update(TABLE, $data, "reg_id", $_SESSION['reg_id']);
		
		if(empty($_POST['pack_name']) == FALSE)
		{
			$sql_del = "DELETE FROM `tbl_registered_package` WHERE `reg_id`=".$_SESSION['reg_id'];
			$db->get($sql_del);
			
			foreach($_POST['pack_name'] as $reg_pack_id)
			{
				$sql_pack_name = "SELECT `package_name`, `package_price` FROM `tbl_registration_packages` WHERE `reg_pack_id`=".$reg_pack_id;
				$res_pack_name = $db->get($sql_pack_name);
				$row_pack_name = $db->fetch_array($res_pack_name);
				$db->free_result($res_pack_name);
				
				$data_array = array(
					"reg_id" => $_SESSION['reg_id'],
					"reg_pack_id" => $reg_pack_id,
					"pack_name" => $row_pack_name['package_name'],
					"pack_price" => $row_pack_name['package_price']
				);
				$db->insert("tbl_registered_package", $data_array);
				unset($data_array);
			}
		}				
		$f->Redirect(MAIN_WEBSITE_URL."/registration-profile-info");	
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
		<div class="inner_container">
			<div class="">
				<div style="font-size: 22px; color: #2F2D9B; font-weight: 600; padding-bottom: 30px;">Determine the cost based on the number of people. Please select more than one package as needed to meet the total number of people in your group. Click 'View Details' for description about each package.</div>
				<!-- <div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-bottom: 10px;">Besed on your information <b>"FAMILY"</b> is the best plan for you</div>
				<div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-bottom: 30px;">For rest of the person go for <b>"General Registration"</b></div> -->
			</div>
         <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-packages">
            <div class="register_area">
               <div style="font-size: 20px; color: #2F2D9B; font-weight: 600; padding-bottom: 10px;">Select your plan from below list</div>
               <?php
                  $sql_package = "SELECT * FROM `tbl_registration_packages` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
                  $res_package = $db->get($sql_package);
                  while($row_package = $db->fetch_array($res_package))
                  {
               ?>
               <div class="input_radio"><input name="pack_name[]" id="pack_<?php echo $row_package['reg_pack_id'];?>" type="checkbox" value="<?php echo $row_package['reg_pack_id'];?>" class="all_package" required />  <?php echo $f->getValue($row_package['package_name']);?>&nbsp;-&nbsp;$<?php echo round($row_package['package_price']);?><?php if($row_package['package_price_old'] > 0) echo " <span class='old_price'>$".round($row_package['package_price_old'])."</span>";?> <a href="" data-toggle="modal" data-target="#<?php echo $row_package['reg_pack_id'];?>">View Details</a></div>
                   
               <div class="modal fade" id="<?php echo $row_package['reg_pack_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                     <div class="modal-content">
                        <div class="modal-body">
                           <div data-dismiss="modal" aria-label="Close" class="package_close" style="cursor:pointer;"><img src="<?php echo MAIN_WEBSITE_URL?>/images/close.png" alt="" /></div>
                           <div class="pricing_block">
                              <div class="pricing_block_heading1"><?php echo $f->getHTMLDecode($row_package['package_title']);?></div>
                              <div class="pricing_block_heading2">$<?php echo floatval($f->getValue($row_package['package_price']));?></div>
                              <div class="pricing_block_content">
                                 <div>
                                    <?php echo $f->getHTMLDecode($row_package['package_desc']);?>
                                 </div>
                                 <div class="clear"></div>
                              </div>
                              <div class="clear"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>                     
               <?php
                  }
               ?>  
               <div class="package_tablearea" >
               <table width="600" border="0" align="left" cellpadding="0" cellspacing="0" id="display_pack">
                		<tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
                             <tr>
                             		<td id="display_pack_ajax" colspan="2">
                                 
                                 </td>
                             </tr> 
                             <!--<tr id="YouthActivity" style="display:none;">
                                 <td width="75%" align="left" valign="middle">Youth Activity</td>
                                 <td width="25%" align="center" valign="middle" id="YouthActivity_P"></td>
                              </tr>
                              <tr id="BanquetDinner" style="display:none;">
                                 <td width="100%" align="left" valign="middle">Banquet Dinner</td>
                                  <td width="25%" align="center" valign="middle" id="BanquetDinner_P"></td>
                              </tr>                              
                              <tr>
                                 <td width="75%" align="left" valign="middle">Total Cost if paid by Zelle/Check</td>                              
                                 <td width="25%" align="center" valign="middle" id="final_cost_zelle">--</td>
                              </tr>
                              <tr>
                                 <td width="75%" align="left" valign="middle">Total Cost if paid by PayPal (Add 3%)</td>                                 
                                 <td width="25%" align="center" valign="middle" id="final_cost_paypal">--</td>
                              </tr>         -->                
                                                  		
                           </table>
                        </td>
                    	 </tr>
                    
                      <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                 </table>
               </div>            
               
               <div class="clear"></div>
              <!-- <div style="font-size: 30px; color: #2F2D9B; font-weight: 700; padding-bottom: 10px;">Total Due Today - $<span id="tot_price">0</span></div>-->
               <div style="text-align:justify; margin-bottom:15px; font-size:16px;">* Rooms will be allotted on a first come first serve basis among 3 hotels after receipt of payment (Eagle Crest Resort, Hampton Inn & Suites, and Fairfield Inn & Suites in that order).</div>
               <div style="text-align:justify; margin-bottom:10px; font-size:16px;">* All 2-night packages will be allotted to Fairfield Inn &amp; Suites, are limited and subject to availability.</div>
               
               <div class="input_radio" style="margin-bottom: 20px;"><input name="t_and_m" type="checkbox" value="Y" required />  Agree to <a href="<?php echo MAIN_WEBSITE_URL;?>/terms-and-conditions" target="_blank">Terms & Conditions</a></div>
               <div class="registration_buttonarea" style="text-align:left;">
               	<input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-step3'" />
                  <input name="bt" type="button" value="Reset" class="submit1" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-packages'" style="padding:0px 60px 0px 60px" />
                  <input name="btnSubmit" type="submit" value="Next" class="submit" />
                  <div class="clear"></div>
               </div>
               <input type="hidden" class="price_class" name="total_price_pack_hd" id="total_price_pack_hd">
               <input type="hidden" class="price_class" name="total_persion_hd" id="total_persion_hd" value="<?php echo $total_persions_cal;?>">
               
               <input type="hidden" class="price_class" name="youth_activity_price_hd" id="youth_activity_price_hd" value="">
               <input type="hidden" class="price_class" name="banquet_dinner_price_hd" id="banquet_dinner_price_hd" value="">             
               
               <input type="hidden" name="total_package_no_of_people_hd" id="total_package_no_of_people_hd" value="0">
               <input type="hidden" class="price_class" name="total_price_hd" id="total_price_hd" value="0">
               
               <input type="hidden" name="no_of_people_youth_extra" id="no_of_people_youth_extra" value="0">
               <input type="hidden" name="no_of_dinner_people_extra" id="no_of_dinner_people_extra" value="0">
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

<script type="text/javascript">
$(document).ready(function(){	
	
	var checkboxes = $('.all_package');
    checkboxes.change(function(){
        if($('.all_package:checked').length>0) {
            checkboxes.removeAttr('required');
        } else {
            checkboxes.attr('required', 'required');
        }
   });
	 
	$("#reg_frm").submit(function(){		
		var total_price = $("#total_price_hd").val();
		if(total_price == 0 || total_price == "")
		{
			infoAlert("Alert", "Please choose any option for registration");
			return false;
		}
	});
	
	$('.all_package').click(function() {
		var pack_name_array = [];
  		$("input:checkbox[name='pack_name[]']:checked").each(function(){    
  			pack_name_array.push($(this).val());    		
  		});
  		var all_pack_select = pack_name_array.join(",");
				
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'PackageCal', all_pack_select: all_pack_select},
			dataType: 'html',
			success: function(data) {
				$('#spinner-div').hide();							
				if(data == 'NA')
				{
					errorAlert("Your session has timed out. Please click reset button.");
				}else{
										
					var data_array = data.split("~");
					var total_price_pack = data_array[0];
					if(total_price_pack=="") total_price_pack = 0;
					
					var no_of_people_youth_extra = data_array[1];
					var youth_activity_price = data_array[2];
					if(youth_activity_price=="") youth_activity_price = 0;
					
					var no_of_dinner_people_extra = data_array[3];
					var dinner_price = data_array[4];
					if(dinner_price=="") dinner_price = 0;
					
					var total_package_no_of_people = data_array[5];
					$("#total_package_no_of_people_hd").val(total_package_no_of_people);
					
					$("#display_pack").css("display", "");
					var display_pack_loop = data_array[6];
					$("#display_pack_ajax").html(display_pack_loop);
					
					var total_cost = parseInt(total_price_pack) + parseInt(youth_activity_price) + parseInt(dinner_price);
					var total_cost_paypal = parseInt(total_cost) + parseInt(total_cost * 3) / 100;
					total_cost_paypal = Math.round(total_cost_paypal);
					
					if(total_cost > 0)
					{					
						$("#final_cost_zelle").html("<b>$"+total_cost+"</b>");
					}else{
						$(".Cost_tr").css("display", "none");
						$("#final_cost_zelle").text("---");
					}
					
					if(total_cost_paypal > 0)
					{
						$("#final_cost_paypal").html("<b>$"+total_cost_paypal+"</b>");
					}else{
						$(".Cost_tr").css("display", "none");
						$("#final_cost_paypal").text("---");
					}
					
					$("#total_price_hd").val(total_cost);
					
					$("#total_price_pack_hd").val(total_price_pack);
					$("#youth_activity_price_hd").val(youth_activity_price);
					$("#banquet_dinner_price_hd").val(dinner_price);
					
					if(youth_activity_price > 0 || dinner_price > 0)
					{
						$("#activity").css("display", "");
						
						if(youth_activity_price > 0)
						{
							$("#YouthActivity").css("display", "");
							
							$("#no_of_people_youth_extra").val(no_of_people_youth_extra);
							$("#YouthActivity_Text").text("Activities for "+no_of_people_youth_extra+" Youth");
							//$("#YouthActivity_PE").text(no_of_people_youth_extra);
							$("#YouthActivity_P").text("$"+youth_activity_price);
						}else{
							
							$("#no_of_people_youth_extra").val(0);
							$("#YouthActivity").css("display", "none");
							//$("#YouthActivity_PE").text("");
							$("#YouthActivity_P").text("");
						}
						
						if(dinner_price > 0)
						{
							$("#BanquetDinner").css("display", "");
							
							$("#no_of_dinner_people_extra").val(no_of_dinner_people_extra);
							$("#BanquetDinner_Text").text(no_of_dinner_people_extra+" Banquet Dinner");
							//$("#BanquetDinner_PE").text(no_of_dinner_people_extra);
							$("#BanquetDinner_P").text("$"+dinner_price);
						}else{
							
							$("#no_of_dinner_people_extra").val(0);							
							$("#BanquetDinner").css("display", "none");
							//$("#BanquetDinner_PE").text("");
							$("#BanquetDinner_P").text("");
						}
						
					}else{
						$("#activity").css("display", "none");
					}
				}
			}			
		});
		
	});
	
	$("#reg_frm").submit(function(){
	   var total_package_no_of_people = $("#total_package_no_of_people_hd").val();
		var total_persion = $("#total_persion_hd").val();		
		if(parseInt(total_package_no_of_people)>=parseInt(total_persion))
		{
			return true
		}else{
			infoAlert('"Please choose another package. your total person and package person is not same." to "The total number of people entered in the first screen and the total number of people across packages don\'t match. Please select another package or additional packages to match the total number of people in your group"');
			return false;
		}		
	});
	
});
</script>

<div id="spinner-div" class="pt-5">
    <div class="spinner-grow text-danger" role="status">
    		<p>&nbsp;</p>
         <p>&nbsp;</p>
    		<span class="visually-hidden">Loading...</span>
    </div>
</div>
</body>
</html>