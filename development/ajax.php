<?php
	require_once("includes/config.inc.php");
	$target = (empty($_GET['target'])===FALSE) ? $_GET['target'] : $_POST['target'];
	$target = urldecode($target);	
	
	switch($target)
	{	
		case "PackageCal":
			$reg_id = $_SESSION['reg_id'];
			if(empty($reg_id) == FALSE)
			{			
				$all_pack_select = $_GET['all_pack_select'];
				$total_price_pack = 0;
				$youth_include_people = 0;
				$banquet_dinner_include_people = 0;	
				
				$youth_activity_price = 0;
				$banquet_dinner_price = 0;						
				
				$sql_reg = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$reg_id;
				$res_reg = $db->get($sql_reg);
				$row_reg = $db->fetch_array($res_reg);
				
				$number_of_people_adult = $row_reg['number_of_people_adult'];
				$number_of_child_6_17 = $row_reg['number_of_child_6_17'];
				$number_of_child_0_5 = $row_reg['number_of_child_0_5'];
				$no_of_people_youth_activities = $row_reg['no_of_people_youth_activities'];
				$no_of_dinner_people = $row_reg['no_of_dinner_people'];
				$no_of_dinner_people_children = $row_reg['no_of_dinner_people_children'];
				$no_of_people = 0;
				
				$total_persion = ($number_of_people_adult) + ($number_of_child_6_17) + ($number_of_child_0_5);
				$total_persion_cal = ($number_of_people_adult) + ($number_of_child_6_17);
				
				if(empty($all_pack_select) == FALSE)
				{
					$display_packeges = '<table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">';
					
					$sql = "SELECT * FROM `tbl_registration_packages` WHERE `reg_pack_id` IN (".$all_pack_select.")";
					$res = $db->get($sql);
					while($row = $db->fetch_array($res))
					{
						$total_price_pack = ($total_price_pack) + ($row['package_price']);
						
						$youth_include_people = ($youth_include_people) + ($row['youth_include_people']);
						$banquet_dinner_include_people = ($banquet_dinner_include_people) + ($row['banquet_dinner_include_people']);
						
						$no_of_people = $no_of_people + $row['no_of_people'];
						
						$display_packeges.= '<tr>
														<td width="75%" align="left" valign="middle">'.$f->getValue($row['package_name']).'</td>                                 
														<td width="25%" align="right" valign="middle">$'.floatval($f->getValue($row['package_price'])).'</td>
													</tr>';	
					}
						
					if($no_of_people_youth_activities > $youth_include_people)
					{
						$no_of_people_youth_extra = ($no_of_people_youth_activities) - ($youth_include_people);
						$youth_activity_price = ($no_of_people_youth_extra) * ($AdminSettings['youth_activity_price']);
					}else{
						$no_of_people_youth_extra = 0;
						$youth_activity_price = 0;
					}
					
					if($no_of_dinner_people > $banquet_dinner_include_people)
					{
						$no_of_dinner_people_extra = ($no_of_dinner_people) - ($banquet_dinner_include_people);
						$dinner_price = ($no_of_dinner_people_extra) * ($AdminSettings['banquet_dinner_price']);
					}else{
						$no_of_dinner_people_extra = 0;
						$dinner_price = 0;
					}
					
				}else{
					
					$total_price_pac = 0;
					$no_of_people_youth_extra = 0;
					$youth_activity_price = 0;
					$no_of_dinner_people_extra = 0;
					$dinner_price = 0;
				}	
				
				$display_packeges.= '<tr id="YouthActivity" style="display:none;">
												<td width="75%" align="left" valign="middle" id="YouthActivity_Text"></td>
												<td width="25%" align="right" valign="middle" id="YouthActivity_P"></td>
											</tr>
											<tr id="BanquetDinner" style="display:none;">
												<td width="75%" align="left" valign="middle" id="BanquetDinner_Text"></td>
												 <td width="25%" align="right" valign="middle" id="BanquetDinner_P"></td>
											</tr>                              
											<tr class="Cost_tr">
												<td width="75%" align="left" valign="middle">Total Cost if paid by Zelle/Check</td>                              
												<td width="25%" align="right" valign="middle" id="final_cost_zelle">--</td>
											</tr>
											<tr class="Cost_tr">
												<td width="75%" align="left" valign="middle">Total Cost if paid by PayPal (Add 3%)</td>                                 
												<td width="25%" align="right" valign="middle" id="final_cost_paypal">--</td>
											</tr>';
				$display_packeges.= '</table>';				
				
				echo $total_price_pack."~".$no_of_people_youth_extra."~".$youth_activity_price."~".$no_of_dinner_people_extra."~".$dinner_price."~".$no_of_people."~".$display_packeges;				
				
			}else{
				echo 'NA';
			}		
			
		break;
		
		case "GetState":
				
			  $country_id = $_GET['CountryId'];
			  
			  $value = '<select name="state" id="state" class="input3">';	
			  
			  
			  $sql = "SELECT * FROM `tbl_state` WHERE `country_id`='".$country_id."' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `state_name` ASC";
			  $res = $db->get($sql);
			  $rec = $db->num_rows($res);
			  if($rec > 0)
			  {
				  while($row = $db->fetch_array($res))
				  {
					  $value.= '<option value="'.$f->getValue($row['state_name']).'">'.$f->getValue($row['state_name']).'</option>';
				  }
			  }else{
				  $value.= '<option value="">---</option>';
			  }
			  
			  $value.= '</select>';
			  
			  echo $value;
				
		break;
		
	}
?>