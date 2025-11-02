<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	$number_of_people = $_POST['number_of_people'];
	if(empty($number_of_people) == FALSE)
	{
		$_SESSION['this_number_of_people'] = $number_of_people;
	}
	
	$_SESSION['this_number_of_people'];	
	
	$total_persions = ($_SESSION['this_number_of_people']);
	$total_persions_gen = 0;
	if($total_persions > 4)
	{
		$total_persions_gen = ($total_persions) - (4);
		$pack_required = " required";
	}else{
		$pack_required = "";
	}
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		
		
		$data = array(						
				'total_price_pack' => ($_POST['total_price_pack_hd']) ? ($f->setValue($_POST['total_price_pack_hd'])) : ('0'),
				'total_persion' => ($_POST['total_persion_hd']) ? ($f->setValue($_POST['total_persion_hd'])) : ('0'),
				'total_persions_gen' => ($_POST['total_persions_gen_hd']) ? ($f->setValue($_POST['total_persions_gen_hd'])) : ('0'),
				'price_gen' => ($_POST['price_gen_hd']) ? ($f->setValue($_POST['price_gen_hd'])) : ('0'),
				'room_price' => ($_POST['room_price_hd']) ? ($f->setValue($_POST['room_price_hd'])) : ('0'),
				'youth_activity_price' => ($_POST['youth_activity_price_hd']) ? ($f->setValue($_POST['youth_activity_price_hd'])) : ('0'),
				'total_youth_activity_price' => ($_POST['total_youth_activity_price_hd']) ? ($f->setValue($_POST['total_youth_activity_price_hd'])) : ('0'),
				'banquet_dinner_price' => ($_POST['banquet_dinner_price_hd']) ? ($f->setValue($_POST['banquet_dinner_price_hd'])) : ('0'),
				'total_banquet_dinner_pric' => ($_POST['total_banquet_dinner_pric_hd']) ? ($f->setValue($_POST['total_banquet_dinner_pric_hd'])) : ('0'),
				'total_price' => ($_POST['total_price_hd']) ? ($f->setValue($_POST['total_price_hd'])) : ('0'),
				'ip_addr' => $_SERVER['REMOTE_ADDR']				
		);	
		
		if(empty($_SESSION['reg_id']) == TRUE) 
		{
			$res_inv = $db->get("SELECT GenerateInvNo()");
			$row_inv = $db->fetch_array($res_inv);
			$db->free_result($res_inv);		
			$db->next_result();					
			$ref_number = "R".date('Ymd').$row_inv[0];
			
			$data['ref_number'] = $ref_number;
			
			$db->insert(TABLE, $data);
			$reg_id = $db->last_insert_id();
			$_SESSION['reg_id'] = $reg_id;
		}else{
			$db->update(TABLE,$data,"reg_id",$_SESSION['reg_id']);
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
<!--<script type="text/javascript">
window.onload = function() {
	 <?php 
	 	/*if($total_persions == 1)
		{*/
	 ?>
	 $("#show_gen").trigger("click");
	 <?php
		//}
	 ?>	
};
</script>-->
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
				<div style="font-size: 30px; color: #2F2D9B; font-weight: 600; padding-bottom: 30px;">Determine the cost based on the number of people</div>
				<!-- <div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-bottom: 10px;">Besed on your information <b>"FAMILY"</b> is the best plan for you</div>
				<div style="font-size: 20px; font-family: 'Poppins', sans-serif; color: #2F2D9B; font-weight: 400; padding-bottom: 30px;">For rest of the person go for <b>"General Registration"</b></div> -->
			</div>
         <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-step2">
            <div class="register_area">
               <div style="font-size: 20px; color: #2F2D9B; font-weight: 600; padding-bottom: 10px;">Select yout plan from below list</div>
               <?php
                  $sql_package = "SELECT * FROM `tbl_registration_packages` WHERE `reg_pack_id`<>'1' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `package_price` DESC";
                  $res_package = $db->get($sql_package);
                  while($row_package = $db->fetch_array($res_package))
                  {
               ?>
               <div class="input_radio"><input name="pack_name" id="pack_price_<?php echo $row_package['reg_pack_id'];?>" type="radio" value="<?php echo round($row_package['package_price']);?>" class="all_package"<?php echo $pack_required;?> />  <?php echo $f->getValue($row_package['package_name']);?> $<?php echo round($row_package['package_price']);?> <a href="" data-toggle="modal" data-target="#<?php echo $row_package['reg_pack_id'];?>">View Details</a></div>
                   
               <div class="modal fade" id="<?php echo $row_package['reg_pack_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                     <div class="modal-content">
                        <div class="modal-body">
                           <div data-dismiss="modal" aria-label="Close" class="package_close" style="cursor:pointer;"><img src="images/close.png" alt="" /></div>
                           <div class="pricing_block">
                              <div class="pricing_block_heading1"><?php echo $f->getValue($row_package['package_name']);?></div>
                              <div class="pricing_block_heading2">$<?php echo $f->getValue($row_package['package_price']);?></div>
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
               <script type="text/javascript">
                  $(document).ready(function() {						
                  
							$("#pack_price_<?php echo $row_package['reg_pack_id'];?>").click(function(){
                        
                        //$("#gen_block").css("display", "none");
                        //$(".price_class").val('');						
                        
                        var price = $(this).val();
                        var total_prices = parseInt(price);
                                             
                        $("#tot_price").text(total_prices);
                        $("#total_price_pack_hd").val(total_prices);
								$("#total_price_hd").val(total_prices);
                        
                        var total_persion = $("#total_persion_hd").val();
                        if(total_persion > 4)
                        {
                           $("#show_gen").trigger("click");
                        }else{
									
									var total_price_pack = $("#total_price_pack_hd").val();
									if(total_price_pack == '') total_price_pack = 0;	
										
									var total_persion = $("#total_persion_hd").val();
									if(total_persion == '') total_persion = 0;
									
									$("#final_cost_reg").text("$"+total_prices);
									$("#final_cost_zelle").text("$"+total_prices);
									
									var percent_val = parseInt(total_prices * 3) / 100;
									var total_paid = parseInt(total_prices) + parseInt(percent_val);
									$("#final_cost_paid").text("$"+total_paid);
		
								}                       
                     });
                  });
               </script>            
               <?php
                  }
               ?>
               
               <?php
                  $sql_package_gen = "SELECT * FROM `tbl_registration_packages` WHERE `reg_pack_id`='1'";
                  $res_package_gen = $db->get($sql_package_gen);
                  $row_package_gen = $db->fetch_array($res_package_gen);
                  
                  $gen_package_price = round($row_package_gen['package_price']); 
                  $total_gen_package_price = $gen_package_price * $total_persions_gen;
               ?>
               <br>
               <div class="button"><a href="javascript:;" id="show_gen">Show Me the General Registration Options</a></div>
               <br>
               <div class="package_tablearea" id="gen_block" style="display:none;">
                  <table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="0" align="left" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td width="60%" align="center" valign="middle">&nbsp;</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle"><strong>Amount</strong></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle">Registration Fees $<?php echo $gen_package_price;?> per person</td>
                                 <td width="15%" align="center" valign="middle" id="total_gen_per"><?php echo $total_persions_gen;?></td>
                                 <td width="25%" align="center" valign="middle" id="total_gen_pack_price">$<?php echo $total_gen_package_price;?></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle"><strong>Select Room</strong></td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle">&nbsp;</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle">Room for 2 night $<?php echo round($row_package_gen['room_price_1']);?></td>
                                 <td width="15%" align="center" valign="middle"><input name="room_price" id="room_price_1" type="radio" value="<?php echo $row_package_gen['room_price_1'];?>" class="" /></td>
                                 <td width="25%" align="center" valign="middle" id="room_1">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">Room for 3 night $<?php echo round($row_package_gen['room_price_2']);?></td>
                                 <td width="15%" align="center" valign="middle"><input name="room_price" id="room_price_2" type="radio" value="<?php echo $row_package_gen['room_price_2'];?>" class="" /></td>
                                 <td width="25%" align="center" valign="middle" id="room_2">--</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle">How many people planned to attend Youth Retreat ($<?php echo round($row_package_gen['youth_activity_price'])?>)</td>
                                 <td width="15%" align="center" valign="middle"><input name="youth_head" id="youth_head" type="text" value="" placeholder="" class="package_tablearea_box number_only" /></td>
                                 <td width="25%" align="center" valign="middle" id="youth_price_display">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">How many people planned to attend the Banquet Dinner ($<?php echo round($row_package_gen['banquet_dinner_price'])?>) on <strong style="display: block;">Thursday July 4th</strong></td>
                                 <td width="15%" align="center" valign="middle"><input name="banquet_head" id="banquet_head" type="text" value="" placeholder="" class="package_tablearea_box number_only" /></td>
                                 <td width="25%" align="center" valign="middle" id="banquet_price_display">--</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                    <!-- <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost of Registration</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_reg">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost if paid by Zelle/Check</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_zelle">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost if paid by PayPal (Add 3%)</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_paid">--</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>-->
                  </table>	
               </div>
                <div class="package_tablearea" >
               <table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost of Registration</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_reg">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost if paid by Zelle/Check</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_zelle">--</td>
                              </tr>
                              <tr>
                                 <td width="60%" align="left" valign="middle">Final Cost if paid by PayPal (Add 3%)</td>
                                 <td width="15%" align="center" valign="middle">&nbsp;</td>
                                 <td width="25%" align="center" valign="middle" id="final_cost_paid">--</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                      <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     </table>
               </div>
               <div class="clear"></div>
               <div style="font-size: 30px; color: #2F2D9B; font-weight: 700; padding-bottom: 10px;">Total Due Today - $<span id="tot_price">0</span></div>
               <div class="input_radio" style="margin-bottom: 20px;"><input name="t_and_m" type="checkbox" value="Y" required />  Agree to <a href="" data-toggle="modal" data-target="#termsconditions">Terms & Conditions</a></div>
               <div class=""><input name="btnSubmit" type="submit" value="Next" class="submit" />&nbsp;&nbsp;&nbsp;<span class="submit"><a href="<?php echo MAIN_WEBSITE_URL."/registration-step2"?>">Reset</a></span></div>
               <input type="hidden" class="price_class" name="total_price_pack_hd" id="total_price_pack_hd">
               <input type="hidden" class="price_class" name="total_persion_hd" id="total_persion_hd" value="<?php echo $total_persions;?>">
               <input type="hidden" class="price_class" name="total_persions_gen_hd" id="total_persions_gen_hd" value="<?php echo $total_persions_gen;?>">
               <input type="hidden" class="price_class" name="price_gen_hd" id="price_gen_hd" value="<?php echo $gen_package_price;?>">
               
               <input type="hidden" class="price_class" name="room_price_hd" id="room_price_hd"> 
               
               <input type="hidden" class="price_class" name="youth_activity_price_hd" id="youth_activity_price_hd" value="<?php echo round($row_package_gen['youth_activity_price']);?>">
               <input type="hidden" class="price_class" name="total_youth_activity_price_hd" id="total_youth_activity_price_hd" value="">
               
               <input type="hidden" class="price_class" name="banquet_dinner_price_hd" id="banquet_dinner_price_hd" value="<?php echo round($row_package_gen['banquet_dinner_price']);?>">
               <input type="hidden" class="price_class" name="total_banquet_dinner_price_hd" id="total_banquet_dinner_price_hd" value="">
               
               <input type="hidden" class="price_class" name="total_price_hd" id="total_price_hd" value="0">
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
function cal()
{
	var total_price_pack = $("#total_price_pack_hd").val();
	if(total_price_pack == '') total_price_pack = 0;	
		
	var total_persion = $("#total_persion_hd").val();
	if(total_persion == '') total_persion = 0;	
	
	var total_persion_gen = $("#total_persions_gen_hd").val();
	if(total_persion_gen == '') total_persion_gen = 0;	
	
	var gen_price = $("#price_gen_hd").val();
	if(gen_price == '') gen_price = 0;
	
	var tot_price = 0;
	
	var room_price_hd = $("#room_price_hd").val();
	if(room_price_hd == '') room_price_hd = 0;
	
	// ==========================================
	var youth_head = $("#youth_head").val();
	if(youth_head == '') youth_head = 0;
	
	var youth_activity_price = $("#youth_activity_price_hd").val();
	if(youth_activity_price == '') youth_activity_price = 0;
	
	var total_youth_activity_price = parseInt(youth_activity_price) * parseInt(youth_head);
	$("#total_youth_activity_price_hd").val(total_youth_activity_price);
	if(total_youth_activity_price > 0)
	{
		$("#youth_price_display").text("$"+total_youth_activity_price);
	}else{
		$("#youth_price_display").text("---");
	}
	
	// ==========================================
	
	var banquet_head = $("#banquet_head").val();
	if(banquet_head == '') banquet_head = 0;
	
	var banquet_dinner_price = $("#banquet_dinner_price_hd").val();
	if(banquet_dinner_price == '') banquet_dinner_price = 0;
	
	var total_banquet_dinner_price = parseInt(banquet_dinner_price) * parseInt(banquet_head);
	$("#total_banquet_dinner_price_hd").val(total_banquet_dinner_price);
	if(total_banquet_dinner_price > 0)
	{
		$("#banquet_price_display").text("$"+total_banquet_dinner_price);
	}else{
		$("#banquet_price_display").text("---");
	}
	
	// =========================================
	
	if(total_persion > 4 && total_price_pack == '')
	{
		infoAlert('You chose package first');
		//return false;
	}else{
		
		$("#gen_block").css("display", "");
		if(total_persion_gen=="")	$(".all_package").prop('checked', false);
		//$("#total_price_pack_hd").val('');
		//total_price_pack = 0;
		
		var total_price = parseInt(gen_price) * parseInt(total_persion_gen);
		total_price = parseInt(total_price_pack) + parseInt(total_price) + parseInt(room_price_hd) + parseInt(total_youth_activity_price) + parseInt(total_banquet_dinner_price);
		
		$("#tot_price").text(total_price);
		$("#total_price_hd").val(total_price);
		
		$("#final_cost_reg").text("$"+total_price);
		$("#final_cost_zelle").text("$"+total_price);
		
		var percent_val = parseInt(total_price * 3) / 100;
		var total_paid = parseInt(total_price) + parseInt(percent_val);
		$("#final_cost_paid").text("$"+total_paid);
	}
}

$(document).ready(function(){
	$("#show_gen").click(function(){	
		var total_price_pack = $("#total_price_pack_hd").val();
		var total_persion = $("#total_persion_hd").val();
		if(total_persion == '') total_persion = 0;
		if(total_persion <= 4)	
		{	
			if(total_price_pack == "")
			{		
				$("#total_persions_gen_hd").val(total_persion);
				$("#total_gen_per").text(total_persion);
				var price_gen_hd = $("#price_gen_hd").val();
				var total_gen_pack_price = parseInt(price_gen_hd) * parseInt(total_persion);
				$("#total_gen_pack_price").text("$"+total_gen_pack_price);
			}
		}
		cal();
		
	});
	
	$("#room_price_1").click(function(){	
		var room_price = $(this).val();
		$("#room_1").text("$"+room_price);
		$("#room_2").text("---");
		
		$("#room_price_hd").val(room_price);
		cal();
	});
	
	$("#room_price_2").click(function(){	
		var room_price = $(this).val();
		$("#room_2").text("$"+room_price);
		$("#room_1").text("---");
		
		$("#room_price_hd").val(room_price);
		cal();
	});
	
	$("#youth_head").keyup(function(){			
		cal();
	});
	
	$("#banquet_head").keyup(function(){			
		cal();
	});
	
	
	$("#reg_frm").submit(function(){
		
		var total_price = $("#total_price_hd").val();
		if(total_price == 0 || total_price == "")
		{
			infoAlert("Alert", "Please choose any option for registration");
			return false;
		}
	});	
	
});
</script>
</body>
</html>