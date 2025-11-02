<?php
	require_once("../includes/config.inc.php");
	set_time_limit(0);
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
				
				$total_persion = ($number_of_people_adult) + ($number_of_child_6_17) + ($number_of_child_0_5);
				$total_persion_cal = ($number_of_people_adult) + ($number_of_child_6_17);
				
				$sql = "SELECT * FROM `tbl_registration_packages` WHERE `reg_pack_id` IN (".$all_pack_select.")";
				$res = $db->get($sql);
				while($row = $db->fetch_array($res))
				{
					$total_price_pack = ($total_price_pack) + ($row['package_price']);
					
					$youth_include_people = ($youth_include_people) + ($row['youth_include_people']);
					$banquet_dinner_include_people = ($banquet_dinner_include_people) + ($row['banquet_dinner_include_people']);
				}
					
				if($no_of_people_youth_activities > $youth_include_people)
				{
					$no_of_people_youth_extra = ($no_of_people_youth_activities) - ($youth_include_people);
					$youth_activity_price = ($no_of_people_youth_extra) * ($AdminSettings['youth_activity_price']);
				}
				
				if($no_of_dinner_people > $banquet_dinner_include_people)
				{
					$no_of_dinner_people_extra = ($no_of_dinner_people) - ($banquet_dinner_include_people);
					$dinner_price = ($no_of_dinner_people_extra) * ($AdminSettings['banquet_dinner_price']);
				}
				
				echo $total_price_pack."~".$no_of_people_youth_extra."~".$youth_activity_price."~".$no_of_dinner_people_extra."~".$dinner_price;
				
			}else{
				echo 'NA';
			}		
			
		break;
		
		case "GetState":
				
			  $country_id = $_GET['CountryId'];
			  
			  $value = '<select name="state" id="state" class="span11">';	
			  
			  
			  $sql = "SELECT * FROM `tbl_state` WHERE `country_id`='".$country_id."' AND `mark_for_deleted`='No' ORDER BY `state_name` ASC";
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
		
		case "ZipDownload":
				
				$f->DeleteFolder('../uploads/download-document');
				$f->CreateFolder('../uploads/download-document', 0777);
				
				function copyDirectory($source, $destination) {
					if (!is_dir($destination)) {
						mkdir($destination, 0755, true);
					}
					$files = scandir($source);
					foreach ($files as $file) {
						if ($file !== '.' && $file !== '..') {
							$sourceFile = $source . '/' . $file;
							$destinationFile = $destination . '/' . $file;
							if (is_dir($sourceFile)) {
								copyDirectory($sourceFile, $destinationFile);
							} else {
								copy($sourceFile, $destinationFile);
							}
						}
					}
				}
				$sourceDirectory = '../uploads/reg-document';
				$destinationDirectory = '../uploads/download-document';
				copyDirectory($sourceDirectory, $destinationDirectory);
				
				$sql = "SELECT a.`family_photo`, b.`ref_number`, c.`first_name`, c.`last_name`
							FROM `tbl_reg_family_info` AS a, `tbl_registration` AS b, `tbl_reg_members_in_your_party` AS c
							WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=c.`reg_id` AND
							a.`family_photo`<>'' AND
							a.`status`='Active' AND a.`mark_for_deleted`='No' AND b.`status`='Active' AND b.`mark_for_deleted`='No'
							GROUP BY c.`reg_id` ORDER BY `reg_members_in_your_party_id` ASC ";
				$res = $db->get($sql);
				$rec = $db->num_rows($res);
				if($rec > 0)
				{
					$files = array();			
					while($row = $db->fetch_array($res))
					{
						$rename_file_to = $f->getValue($row['ref_number'])."_".$f->getValue($row['first_name'])."_".$f->getValue($row['last_name']);
						$files[$f->RenameFile($f->getValue($row['family_photo']), $rename_file_to)] = '../uploads/download-document/'.$f->getValue($row['family_photo']);
					}
					
					//print_r($files); exit();
							
					$ZipFileName = 'download-registration-document.zip';
					require_once('../includes/zip.manager.php');
					$DirZip = '../uploads';	
					$objZip = new ZipManager();
					
					// Zip File Path
					$zip_file = $DirZip."/".$ZipFileName;
					// If the file is already existing then delete it
					$f->DeleteFile($zip_file);
					// Creating the Zip File
					$objZip->CreateZip($files, $zip_file);
					//ob_clean();
					//ob_end_flush();
					//ob_end_clean();
					// Giving option for download after successfully created
							
					/*header("Content-Type: application/zip");
					header("Content-type: application/force-download");
					header("Content-Transfer-Encoding: Binary");
					header("Content-length: ".filesize($zip_file));
					header("Content-disposition: attachment; filename=\"".basename($zip_file)."\"");
					readfile($zip_file);	*/	
					echo $zip_file;
				}
	
		break;
		
		case "TotalCSV":			
			
			$CsvFileName = "csv/Registration-Report.csv";			
		
			$sql = "SELECT a.`ref_number` AS `Registration No`, 
						a.`number_of_people_adult` AS `Adult`, 
						a.`number_of_child_6_17` AS `Child 6-17`, 
						a.`number_of_child_0_5` AS `Child 0-5`, 
						a.`no_of_people_youth_activities` AS `Youth Activities`,
						a.`no_of_dinner_people` AS `Dinner`,
						a.`no_of_dinner_people_children` AS `Dinner-Children`,
						a.`total_persion` AS `Total Persion`,
						a.`youth_activity_price` AS `Youth Activity Price`,
						a.`banquet_dinner_price` AS `Banquet Dinner Price`,				
						a.`youth_networking_event_total_people` AS `Networking Event Total People`,
						a.`youth_networking_event_total_price` AS `Networking Event Total Price`,
						a.`total_price` AS `Total Price`,
						a.`geteway_charge` AS `Geteway Charge`,
						a.`ip_addr` AS `IP Address`,
						a.`pay_status` AS `Pay Status`,
						a.`transaction_id` AS `Transaction Id`,
						a.`admin_note` AS `Admin Note`,
						a.`pay_date` AS `Pay Date`,
						a.`interested_in_driving` AS `Interested in Driving`,
						a.`status` AS `Status`,
						a.`cancellation_charges` AS `Cancellation Charges`,
						a.`admin_room_type` AS `Admin Room Type`,
						a.`admin_room_allotted` AS `Admin Room Allotted`,
						a.`admin_souvenir_distribution` AS `Admin Souvenir Distribution`,
						a.`admin_goodie_bag` AS `Admin Goodie Bag`,
						a.`admin_general_registration` AS `Admin General Registration`,
						a.`admin_treasury_notes` AS `Admin Treasury Notes`,
						a.`create_dt` AS `Create Date`,
						a.`cancel_dt` AS `Cancel Date`,			
						
						FunGroupPackage(a.`reg_id`) AS `package_details`,
						
						c.`email_id` AS `Email Id`,
						c.`phone` AS `Phone`,
						c.`address_1` AS `Address 1`,
						c.`address_2` AS `Address 2`,
						c.`city` AS `City`,
						c.`state` AS `State`,
						c.`zip_code` AS `Zip Code`,
						c.`country` AS `Country`,
						c.`kovil` AS `Kovil`,
						c.`native_place` AS `Native Place`,
						c.`wheelchair_accessible` AS `Wheelchair Accessible`,			
						
						d.`first_name` AS `First name`,
						d.`middle_name` AS `Middle name`,
						d.`last_name` AS `Last name`,
						d.`gender` AS `Gender`,
						d.`age` AS `Age`,
						d.`food_preference` AS `Food Preference`,
						d.`youth_activity` AS `Youth Activity`,
						d.`youth_dance` AS `Youth Dance`,
						d.`tshirt_size` AS `T-shirt Size`,
						d.`phone` AS `Phone`,
						d.`email` AS `Email`,
								
						
						FunTotalDinner(a.`reg_id`) AS `Total Banquet Dinner`,
						FunTotalNetworkingEventById(a.`reg_id`) AS `Total Networking Event`,
							 
						e.`photo_in_retreat_book` AS `Photo in Retreat Book`,
						e.`photo_in_sponsors` AS `Photo in Sponsors`,
						e.`family_photo` AS `Family Photo`,
						e.`full_name` AS `Full Name`,
						e.`sp_requirement` AS `Sp Requirement`		
						
						FROM `tbl_registration` AS a
						
						INNER JOIN `tbl_reg_profile_info` AS c ON a.`reg_id`=c.`reg_id`
						INNER JOIN `tbl_reg_members_in_your_party` AS d ON a.`reg_id`=d.`reg_id`
						INNER JOIN `tbl_reg_family_info` AS e ON a.`reg_id`=e.`reg_id`				
						
						WHERE a.`mark_for_deleted`='No' AND a.`status`<>'Inactive'
						GROUP BY d.`reg_id` ORDER BY a.`reg_id` ASC, d.`reg_members_in_your_party_id` ASC";
			
			
						
			$dir = 'csv/';
			foreach(glob($dir.'*.*') as $v)
			{
				 $f->DeleteFile($v);
			}
			
			$FileToWrite = fopen($CsvFileName, 'w'); 
			chmod($CsvFileName, 0777);
			
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0)
			{
				$num_fields = $db->getNumberFields($res);
				$ii = 0;
				
				$getFieldNames = $db->getFields($res);
				$FieldNameArr = array();
						
				foreach($getFieldNames as $FldName)
				{
					$FieldNameArr[] = strtoupper(str_replace("_", " ", $FldName));			
				}
				
				$DataPut = implode(",", $FieldNameArr)."\n";
				fwrite($FileToWrite, $DataPut);
				unset($FieldNameArr);
				
				
				while($row = $db->fetch_array($res))
				{				
					$DataArr = array();
						
					for($fi=0; $fi<$num_fields; $fi++)
					{
						$field_val = $f->getHTMLDecode($row[$fi]);
						$field_val = str_replace(array(','), array(" "), $field_val);
						$field_val = preg_replace("/\r\n|\r|\n/", '<br/>', $field_val);
						$DataArr[] = $field_val;
					}
								
					$DataPut = implode(",", $DataArr)."\n";	
					fwrite($FileToWrite, $DataPut);
					
					unset($DataArr);
				}
				
				fclose($FileToWrite);			
				//$f->Redirect($CsvFileName); 
				echo WEBSITE_URL.'/'.$CsvFileName;
		}
		else
		{
			$f->alert('No Record Found');
			$f->location($_SERVER['HTTP_REFERER']);
			exit();
		}
		
		break;
		
			case "TotalCSV_2":
			
			$CsvFileName = "csv/Registration-Report_2.csv";
		
			$sql = "SELECT a.`ref_number` AS `Registration No`, 
						a.`number_of_people_adult` AS `Adult`, 
						a.`number_of_child_6_17` AS `Child 6-17`, 
						a.`number_of_child_0_5` AS `Child 0-5`, 
						a.`no_of_people_youth_activities` AS `Youth Activities`,
						a.`no_of_dinner_people` AS `Dinner`,
						a.`no_of_dinner_people_children` AS `Dinner-Children`,
						a.`total_persion` AS `Total Persion`,
						a.`youth_activity_price` AS `Youth Activity Price`,
						a.`banquet_dinner_price` AS `Banquet Dinner Price`,				
						a.`youth_networking_event_total_people` AS `Networking Event Total People`,
						a.`youth_networking_event_total_price` AS `Networking Event Total Price`,
						a.`total_price` AS `Total Price`,
						a.`geteway_charge` AS `Geteway Charge`,
						a.`ip_addr` AS `IP Address`,
						a.`pay_status` AS `Pay Status`,
						a.`transaction_id` AS `Transaction Id`,
						a.`admin_note` AS `Admin Note`,
						a.`pay_date` AS `Pay Date`,
						a.`interested_in_driving` AS `Interested in Driving`,
						a.`status` AS `Status`,
						a.`cancellation_charges` AS `Cancellation Charges`,
						a.`admin_room_type` AS `Admin Room Type`,
						a.`admin_room_allotted` AS `Admin Room Allotted`,
						a.`admin_souvenir_distribution` AS `Admin Souvenir Distribution`,
						a.`admin_goodie_bag` AS `Admin Goodie Bag`,
						a.`admin_general_registration` AS `Admin General Registration`,
						a.`admin_treasury_notes` AS `Admin Treasury Notes`,
						a.`create_dt` AS `Create Date`,
						a.`cancel_dt` AS `Cancel Date`,			
						
						FunGroupPackage(a.`reg_id`) AS `package_details`,
						
						c.`email_id` AS `Email Id`,
						c.`phone` AS `Phone`,
						c.`address_1` AS `Address 1`,
						c.`address_2` AS `Address 2`,
						c.`city` AS `City`,
						c.`state` AS `State`,
						c.`zip_code` AS `Zip Code`,
						c.`country` AS `Country`,
						c.`kovil` AS `Kovil`,
						c.`native_place` AS `Native Place`,
						c.`wheelchair_accessible` AS `Wheelchair Accessible`,			
						
						d.`first_name` AS `First name`,
						d.`middle_name` AS `Middle name`,
						d.`last_name` AS `Last name`,
						d.`gender` AS `Gender`,
						d.`age` AS `Age`,
						d.`food_preference` AS `Food Preference`,
						d.`youth_activity` AS `Youth Activity`,
						d.`youth_dance` AS `Youth Dance`,
						d.`tshirt_size` AS `T-shirt Size`,
						d.`phone` AS `Phone`,
						d.`email` AS `Email`,	
						d.`is_primary_mem` AS `Guest Member Type`,				
						
						FunTotalDinner(a.`reg_id`) AS `Total Banquet Dinner`,
						FunTotalNetworkingEventById(a.`reg_id`) AS `Total Networking Event`,
							 
						e.`photo_in_retreat_book` AS `Photo in Retreat Book`,
						e.`photo_in_sponsors` AS `Photo in Sponsors`,
						e.`family_photo` AS `Family Photo`,
						e.`full_name` AS `Full Name`,
						e.`sp_requirement` AS `Sp Requirement`		
						
						FROM `tbl_registration` AS a
						
						INNER JOIN `tbl_reg_profile_info` AS c ON a.`reg_id`=c.`reg_id`
						INNER JOIN `tbl_reg_members_in_your_party` AS d ON a.`reg_id`=d.`reg_id`
						INNER JOIN `tbl_reg_family_info` AS e ON a.`reg_id`=e.`reg_id`				
						
						WHERE a.`mark_for_deleted`='No' AND a.`status`<>'Inactive'
						ORDER BY a.`reg_id` ASC, d.`reg_members_in_your_party_id` ASC";
			
			
						
			$dir = 'csv/';
			foreach(glob($dir.'*.*') as $v)
			{
				 $f->DeleteFile($v);
			}
			
			$FileToWrite = fopen($CsvFileName, 'w'); 
			chmod($CsvFileName, 0777);
			
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0)
			{
				$num_fields = $db->getNumberFields($res);
				$ii = 0;
				
				$getFieldNames = $db->getFields($res);
				$FieldNameArr = array();
						
				foreach($getFieldNames as $FldName)
				{
					$FieldNameArr[] = strtoupper(str_replace("_", " ", $FldName));			
				}
				
				$DataPut = implode(",", $FieldNameArr)."\n";
				fwrite($FileToWrite, $DataPut);
				unset($FieldNameArr);
				
				
				while($row = $db->fetch_array($res))
				{				
					$DataArr = array();
						
					for($fi=0; $fi<$num_fields; $fi++)
					{
						$field_val = $f->getHTMLDecode($row[$fi]);
						$field_val = str_replace(array(','), array(" "), $field_val);
						$field_val = preg_replace("/\r\n|\r|\n/", '<br/>', $field_val);
						$DataArr[] = $field_val;
					}
								
					$DataPut = implode(",", $DataArr)."\n";	
					fwrite($FileToWrite, $DataPut);
					
					unset($DataArr);
				}
				
				fclose($FileToWrite);			
				//$f->Redirect($CsvFileName);
				echo WEBSITE_URL.'/'.$CsvFileName;
		}
		else
		{
			$f->alert('No Record Found');
			$f->location($_SERVER['HTTP_REFERER']);
			exit();
		}
		
		break;
		
		case "All_Youth_Networking_Event":
		
			$CsvFileName = "csv/Registration-Youth-Networking-Event.csv";
			$sql = "SELECT b.`first_name` , b.`last_name`, b.`gender`, b.`age`, b.`phone`, b.`email` 
						 FROM `tbl_reg_youth_networking_event` AS a, `tbl_reg_members_in_your_party` AS b,
						 `tbl_registration` AS c
						WHERE a.`reg_members_in_your_party_id`=b.`reg_members_in_your_party_id` AND `b`.`reg_id`=c.reg_id						
						AND c.`status`='Active' AND c.`mark_for_deleted`='No'";
					
			$dir = 'csv/';
			foreach(glob($dir.'*.*') as $v)
			{
				 $f->DeleteFile($v);
			}
			
			$FileToWrite = fopen($CsvFileName, 'w'); 
			chmod($CsvFileName, 0777);
			
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0)
			{
				$num_fields = $db->getNumberFields($res);
				$ii = 0;
				
				$getFieldNames = $db->getFields($res);
				$FieldNameArr = array();
						
				foreach($getFieldNames as $FldName)
				{
					$FieldNameArr[] = strtoupper(str_replace("_", " ", $FldName));			
				}
				
				$DataPut = implode(",", $FieldNameArr)."\n";
				fwrite($FileToWrite, $DataPut);
				unset($FieldNameArr);
				
				
				while($row = $db->fetch_array($res))
				{				
					$DataArr = array();
						
					for($fi=0; $fi<$num_fields; $fi++)
					{
						$field_val = $f->getHTMLDecode($row[$fi]);
						$field_val = str_replace(array(','), array(" "), $field_val);
						$field_val = preg_replace("/\r\n|\r|\n/", '<br/>', $field_val);
						$DataArr[] = $field_val;
					}
								
					$DataPut = implode(",", $DataArr)."\n";	
					fwrite($FileToWrite, $DataPut);
					
					unset($DataArr);
				}
				
				fclose($FileToWrite);			
				//$f->Redirect($CsvFileName);
				echo WEBSITE_URL.'/'.$CsvFileName;
			}
			else
			{
				$f->alert('No Record Found');
				$f->location($_SERVER['HTTP_REFERER']);
				exit();
			}
		
		break;
		
		case "All_Youth_Activity":
			
			
			$CsvFileName = "csv/Registration-Youth-Activity.csv";
			$sql = "SELECT a.`reg_members_in_your_party_id`,a.`first_name` , a.`last_name`, a.`gender`, a.`age`, a.`phone`, a.`email`
						 FROM `tbl_reg_members_in_your_party` AS a,
						 `tbl_registration` AS b
						WHERE `a`.`reg_id`=b.`reg_id`						
						AND a.`status`='Active' AND a.`mark_for_deleted`='No' 
						AND b.`status`='Active' AND b.`mark_for_deleted`='No'
						AND a.`youth_activity`='Yes'";
					
			$dir = 'csv/';
			foreach(glob($dir.'*.*') as $v)
			{
				 $f->DeleteFile($v);
			}
			
			$FileToWrite = fopen($CsvFileName, 'w'); 
			chmod($CsvFileName, 0777);
			
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0)
			{
				$num_fields = $db->getNumberFields($res);
				$ii = 0;
				
				$getFieldNames = $db->getFields($res);
				$FieldNameArr = array();
						
				foreach($getFieldNames as $FldName)
				{
					$FieldNameArr[] = strtoupper(str_replace("_", " ", $FldName));			
				}
				
				$DataPut = implode(",", $FieldNameArr)."\n";
				fwrite($FileToWrite, $DataPut);
				unset($FieldNameArr);
				
				
				while($row = $db->fetch_array($res))
				{				
					$DataArr = array();
						
					for($fi=0; $fi<$num_fields; $fi++)
					{
						$field_val = $f->getHTMLDecode($row[$fi]);
						$field_val = str_replace(array(','), array(" "), $field_val);
						$field_val = preg_replace("/\r\n|\r|\n/", '<br/>', $field_val);
						$DataArr[] = $field_val;
					}
								
					$DataPut = implode(",", $DataArr)."\n";	
					fwrite($FileToWrite, $DataPut);
					
					unset($DataArr);
				}
				
				fclose($FileToWrite);			
				//$f->Redirect($CsvFileName);
				echo WEBSITE_URL.'/'.$CsvFileName;
			}
			else
			{
				$f->alert('No Record Found');
				$f->location($_SERVER['HTTP_REFERER']);
				exit();
			}
				
		break;
		
	}
?>