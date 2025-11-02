<?php 
	require_once("includes/config.inc.php");	
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');	
	
	if($_SESSION['reg_status']!='Active') $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$sql = "SELECT * FROM `tbl_reg_profile_info` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mark_for_deleted`='No' AND `status`='Active'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);
	
	$reg_profile_info_id = $f->getValue($row['reg_profile_info_id']);
	$email_id = $f->getValue($row['email_id']);
	$phone = $f->getValue($row['phone']);
	$address_1 = $f->getValue($row['address_1']);
	$address_2 = $f->getValue($row['address_2']);
	$city = $f->getValue($row['city']);
	$state = $f->getValue($row['state']);
	$zip_code = $f->getValue($row['zip_code']);
	$country = $f->getValue($row['country']);
	$kovil = $f->getValue($row['kovil']);
	$native_place = $f->getValue($row['native_place']);
	$wheelchair_accessible = $f->getValue($row['wheelchair_accessible']);
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
			$country = $_POST['country'];
			$country_array = explode("|", $country);
			$country = $country_array[1];
			
			$data_array = array(
				/*"reg_id" => $reg_id, 
				"email_id" => ($_POST['email_id']) ? ($f->setValue($_POST['email_id'])) : ('NULL'),*/
				"phone" => ($_POST['phone']) ? ($f->setValue($_POST['phone'])) : ('NULL'),
				"address_1" => ($_POST['address_1']) ? ($f->setValue($_POST['address_1'])) : ('NULL'),
				"address_2" => ($_POST['address_2']) ? ($f->setValue($_POST['address_2'])) : ('NULL'),
				"city" => ($_POST['city']) ? ($f->setValue($_POST['city'])) : ('NULL'),
				"state" => ($_POST['state']) ? ($f->setValue($_POST['state'])) : ('NULL'),
				"zip_code" => ($_POST['zip_code']) ? ($f->setValue($_POST['zip_code'])) : ('NULL'),		
				"country" => ($country) ? ($country) : ('NULL'),
				"kovil" => ($_POST['kovil']) ? ($f->setValue($_POST['kovil'])) : ('NULL'),
				"native_place" => ($_POST['native_place']) ? ($f->setValue($_POST['native_place'])) : ('NULL'),
				"wheelchair_accessible" => ($_POST['wheelchair_accessible']) ? ($f->setValue($_POST['wheelchair_accessible'])) : ('NULL')		
			);
			$db->update("tbl_reg_profile_info", $data_array, "reg_profile_info_id", $reg_profile_info_id);
			
			// Sending email for update ===========================================================================================================
			
			$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name`, c.`ref_number` 
							FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b, `tbl_registration` AS c
				  			WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$_SESSION['reg_id_edit']." AND c.`reg_id`=a.`reg_id`
							GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
			$res_cus = $db->get($sql_cus);
			$row_cus = $db->fetch_array($res_cus);
			$email_id = $f->getValue($row_cus['email_id']);
			$first_name = $f->getValue($row_cus['first_name']);
			$last_name = $f->getValue($row_cus['last_name']);
			$ref_number = $f->getValue($row_cus['ref_number']);
		
			$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
			$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
			$email_message.= "Your NSNA retreat registration has been successfully updated.<br /><br />";			
			//$email_message.= "<List the information that has changed><br /><br />";
			$email_message.= "Please contact us at miretreat.registration@achi.org if you have any questions.<br /><br />";			
			$email_message.= "Best regards,<br />NSNA Michigan Retreat Committee";
			$email_message.= "</font>";
			
			$objMail = new PHPMailer();
			$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
			$objMail->Subject = "Your Retreat Registration has been updated - ".$ref_number;
			if($AdminSettings['smtp']=='Yes'):
				$objMail->IsSMTP();
				$objMail->Host = $f->getValue($AdminSettings['smtp_hostname']);
				$objMail->SMTPAuth = true;
				if($AdminSettings['smtp_type'] == "tls" || $AdminSettings['smtp_type'] == "ssl")
				{
					$objMail->SMTPSecure = $AdminSettings['smtp_type'];
					$objMail->Port = ($AdminSettings['smtp_type'] == 'tls') ? 587 : 465;
				}
				$objMail->Username = $f->getValue($AdminSettings['smtp_username']);
				$objMail->Password = $f->getValue($AdminSettings['smtp_password']);
			endif;
			$objMail->IsHTML(true);
			$objMail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			$objMail->MsgHTML($email_message);
			$objMail->CharSet = 'UTF-8';
			$objMail->AddAddress($email_id, "");	
			$objMail->AddCC($AdminSettings['your_email_address'],$AdminSettings['your_name']);
			$objMail->AddCC($AdminSettings['your_email_address_2'],$AdminSettings['your_name']);
			$objMail->Send();
				
			// End Sending email for update ========================================================================================================
		
			$f->Redirect(MAIN_WEBSITE_URL."/edit-registration-profile-info/?success=1");	
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
                <div class="flexcaption_style4">Modify Profile Information</div>
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
				<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:10px;">Profile Information</div>
			<div style="font-size:20px; padding-bottom: 30px;">Modify following information for the primary guest responsible for this registration</div>
          <?php if(empty($msg) == false){ ?>
            <div align="center" class="display_message"><?php echo $msg;?></div>
            <p></p>
        	<?php } ?>
          <?php 
				if(empty($_GET['success']) == FALSE && $_GET['success'] == '1')
				{
		   ?>
		    <div align="center"><?php echo $f->getHtmlMessage('The form has been successfully updated.');?></div>
		    <?php } ?>
         <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/edit-registration-profile-info">
            <div class="register_area">
               <div class="row">
                  <div class="col-md-6"><p class="style1">Email <span>*</span></p><input name="email_id" id="email_id" type="email" value="<?php echo $email_id;?>" placeholder="" class="input3 email text_disable" required disabled/></div>
                  <div class="col-md-6"><p class="style1">Mobile <span>*</span></p><input name="phone" id="phone" type="tel" value="<?php echo $phone?>" placeholder="" class="input3" maxlength="20" required /></div>	
               </div>
               <div><p class="style1">Address Line 1 <span>*</span></p><input name="address_1" id="address_1" type="text" value="<?php echo $address_1;?>" placeholder="" class="input3" required /></div>
               <div><p class="style1"> Address Line 2</p><input name="address_2" id="address_2" type="text" value="<?php echo $address_2;?>" placeholder="" class="input3" /></div>
               <div class="row">
               	  <div class="col-md-6"><p class="style1">Country <span>*</span></p>
                     <select name="country" id="country" class="input3" required>
                         <option value="">---</option>                         
                         <?php 
								 		 $reg_country_id = "";
                               $sql_country = "SELECT * FROM `tbl_country` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
                               $res_country = $db->get($sql_country, __FILE__, __LINE__);
                               while($row_country = $db->fetch_array($res_country))
                               {    
										 	if($country == $f->getValue($row_country['country_name']))
											{
												$reg_country_id = $row_country['country_id'];
												$selected = ' selected';
											}else{
												$selected = '';
											}
                           ?>
                           <option value="<?php echo $row_country['country_id']."|".$row_country['country_name'];?>"<?php echo $selected;?>><?php echo $f->getValue($row_country['country_name']);?></option>
                           <?php
                              }								
                           ?>
                    </select>
                  </div>
                  <div class="col-md-6"><p class="style1">State <span>*</span></p>
                  	<div id="st">
                     <select name="state" id="state" class="input3">
                        <option value="">---</option>   
                        <?php 
									if(empty($reg_country_id) == FALSE)
									{
										$sql_state = "SELECT * FROM `tbl_state` WHERE `country_id`='".$reg_country_id."' AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `state_name` ASC";
										$res_state = $db->get($sql_state);
										while($row_state = $db->fetch_array($res_state))
										{
								?>
                        <option value="<?php echo $f->getValue($row_state['state_name'])?>"<?php if($f->getValue($row_state['state_name']) == $state) echo ' selected';?>><?php echo $f->getValue($row_state['state_name'])?></option> 
                        <?php
										}
									}
								?>	
                    </select>
                    </div>
                  </div>   
                  
                  <div class="col-md-6"><p class="style1">City <span>*</span></p><input name="city" id="city" type="text" value="<?php echo $city;?>" placeholder="" class="input3" required /></div>
                  
                  <div class="col-md-6"><p class="style1">Zip Code <span>*</span></p><input name="zip_code" id="zip_code" type="text" value="<?php echo $zip_code;?>" placeholder="" class="input3" maxlength="6" required /></div>
                 
                  <div class="col-md-6"><p class="style1">Kovil <span>*</span></p>
                     <select name="kovil" id="kovil" class="input3" required>
                        <option value="">---</option>
                        <option value="Ilayatrankudi Kovil"<?php if($kovil == 'Ilayatrankudi Kovil') echo ' selected';?>>Ilayatrankudi Kovil</option>
                        <option value="Mathur"<?php if($kovil == 'Mathur') echo ' selected';?>>Mathur</option>
                        <option value="Irani Kovil"<?php if($kovil == 'Irani Kovil') echo ' selected';?>>Irani Kovil</option>
                        <option value="Nemam Kovil"<?php if($kovil == 'Nemam Kovil') echo ' selected';?>>Nemam Kovil</option>
                        <option value="Pillayarpatti"<?php if($kovil == 'Pillayarpatti') echo ' selected';?>>Pillayarpatti</option>
                        <option value="Illuppakkudi Kovil"<?php if($kovil == 'Illuppakkudi Kovil') echo ' selected';?>>Illuppakkudi Kovil</option>
                        <option value="Soorakkudi Kovil"<?php if($kovil == 'Soorakkudi Kovil') echo ' selected';?>>Soorakkudi Kovil</option>
                        <option value="Vairavan Kovil"<?php if($kovil == 'Vairavan Kovil') echo ' selected';?>>Vairavan Kovil</option>
                        <option value="Velankudi Kovil"<?php if($kovil == 'Velankudi Kovil') echo ' selected';?>>Velankudi Kovil</option>
                 	   </select>
                  </div>
                  <div class="col-md-6"><p class="style1">Native (Ooru) <span>*</span></p>
                     <select name="native_place" id="native_place" class="input3" required>
                       		<option value="">---</option>                      		
									<option value="Alavakkottai"<?php if($native_place == 'Alavakkottai') echo ' selected';?>>Alavakkottai</option>
                           <option value="Amaravathiputhur"<?php if($native_place == 'Amaravathiputhur') echo ' selected';?>>Amaravathiputhur</option>
                           <option value="Aranmanai Siruvayal"<?php if($native_place == 'Aranmanai Siruvayal') echo ' selected';?>>Aranmanai Siruvayal</option>
                           <!--<option value="Aravayal"<?php //if($native_place == 'Aravayal') echo ' selected';?>>Aravayal</option>-->
                           <option value="Arimalam"<?php if($native_place == 'Arimalam') echo ' selected';?>>Arimalam</option>
                           <option value="Ariyakkudi"<?php if($native_place == 'Ariyakkudi') echo ' selected';?>>Ariyakkudi</option>
                           <option value="Athangudi"<?php if($native_place == 'Athangudi') echo ' selected';?>>Athangudi</option>
                           <option value="Athangudi Muthupattinam"<?php if($native_place == 'Athangudi Muthupattinam') echo ' selected';?>>Athangudi Muthupattinam</option>
                           <option value="Athikadu Thekkur"<?php if($native_place == 'Athikadu Thekkur') echo ' selected';?>>Athikadu Thekkur</option>
                           <option value="Avanipatti"<?php if($native_place == 'Avanipatti') echo ' selected';?>>Avanipatti</option>
                           <option value="Chockalingamputhur"<?php if($native_place == 'Chockalingamputhur') echo ' selected';?>>Chockalingamputhur</option>
                           <option value="Chockanathapuram"<?php if($native_place == 'Chockanathapuram') echo ' selected';?>>Chockanathapuram</option>
                           <option value="Cholapuram"<?php if($native_place == 'Cholapuram') echo ' selected';?>>Cholapuram</option>
                           <option value="Devakottai"<?php if($native_place == 'Devakottai') echo ' selected';?>>Devakottai</option>
                           <option value="K. Lakshmipuram"<?php if($native_place == 'K. Lakshmipuram') echo ' selected';?>>K. Lakshmipuram</option>
                           <option value="Kadiyapatti"<?php if($native_place == 'Kadiyapatti') echo ' selected';?>>Kadiyapatti</option>
                           <option value="Kalaiyar Koil"<?php if($native_place == 'Kalaiyar Koil') echo ' selected';?>>Kalaiyar Koil</option>
                           <option value="Kalaiyarmangalam"<?php if($native_place == 'Kalaiyarmangalam') echo ' selected';?>>Kalaiyarmangalam</option>
                           <option value="Kallal"<?php if($native_place == 'Kallal') echo ' selected';?>>Kallal</option>
                           <option value="Kalluppatti"<?php if($native_place == 'Kalluppatti') echo ' selected';?>>Kalluppatti</option>
                           <option value="Kanadukathan"<?php if($native_place == 'Kanadukathan') echo ' selected';?>>Kanadukathan</option>
                           <option value="Kandanur"<?php if($native_place == 'Kandanur') echo ' selected';?>>Kandanur</option>
                           <option value="Kandaramanickam"<?php if($native_place == 'Kandaramanickam') echo ' selected';?>>Kandaramanickam</option>
                           <option value="Kandavarayanpatti"<?php if($native_place == 'Kandavarayanpatti') echo ' selected';?>>Kandavarayanpatti</option>
                           <option value="Karaikudi"<?php if($native_place == 'Karaikudi') echo ' selected';?>>Karaikudi</option>
                           <option value="Karaikudi Muthupatinam"<?php if($native_place == 'Karaikudi Muthupatinam') echo ' selected';?>>Karaikudi Muthupatinam</option>
                           <option value="Karungulam"<?php if($native_place == 'Karungulam') echo ' selected';?>>Karungulam</option>
                           <option value="Kilapoonkudi"<?php if($native_place == 'Kilapoonkudi') echo ' selected';?>>Kilapoonkudi</option>
                           <option value="Kilasevalpatti"<?php if($native_place == 'Kilasevalpatti') echo ' selected';?>>Kilasevalpatti</option>
                           <option value="Kollangudi Alagapuri"<?php if($native_place == 'Kollangudi Alagapuri') echo ' selected';?>>Kollangudi Alagapuri</option>
                           <option value="Konapet"<?php if($native_place == 'Konapet') echo ' selected';?>>Konapet</option>
                           <option value="Koppanapatti"<?php if($native_place == 'Koppanapatti') echo ' selected';?>>Koppanapatti</option>
                           <option value="Kothamangalam"<?php if($native_place == 'Kothamangalam') echo ' selected';?>>Kothamangalam</option>
                           <option value="Kottaiyur"<?php if($native_place == 'Kottaiyur') echo ' selected';?>>Kottaiyur</option>
                           <option value="Kulipirai"<?php if($native_place == 'Kulipirai') echo ' selected';?>>Kulipirai</option>
                           <option value="Kuruvikondanpatti"<?php if($native_place == 'Kuruvikondanpatti') echo ' selected';?>>Kuruvikondanpatti</option>
                           <option value="Madagupatti"<?php if($native_place == 'Madagupatti') echo ' selected';?>>Madagupatti</option>
                           <option value="Mahibalanpatti"<?php if($native_place == 'Mahibalanpatti') echo ' selected';?>>Mahibalanpatti</option>
                           <option value="Managiri"<?php if($native_place == 'Managiri') echo ' selected';?>>Managiri</option>
                           <option value="Mangalam"<?php if($native_place == 'Mangalam') echo ' selected';?>>Mangalam</option>
                           <option value="Melasivapuri"<?php if($native_place == 'Melasivapuri') echo ' selected';?>>Melasivapuri</option>
                           <option value="Mithilaipatti"<?php if($native_place == 'Mithilaipatti') echo ' selected';?>>Mithilaipatti</option>
                           <option value="N. Alagapuri"<?php if($native_place == 'N. Alagapuri') echo ' selected';?>>N. Alagapuri</option>
                           <option value="Nachandupatti"<?php if($native_place == 'Nachandupatti') echo ' selected';?>>Nachandupatti</option>
                           <option value="Nachiapuram"<?php if($native_place == 'Nachiapuram') echo ' selected';?>>Nachiapuram</option>
                           <option value="Natarajapuram"<?php if($native_place == 'Natarajapuram') echo ' selected';?>>Natarajapuram</option>
                           <option value="Nattarasankottai"<?php if($native_place == 'Nattarasankottai') echo ' selected';?>>Nattarasankottai</option>
                           <option value="Nemathanpatti"<?php if($native_place == 'Nemathanpatti') echo ' selected';?>>Nemathanpatti</option>
                           <option value="Nerkuppai"<?php if($native_place == 'Nerkuppai') echo ' selected';?>>Nerkuppai</option>
                           <option value="O. Siruvayal"<?php if($native_place == 'O. Siruvayal') echo ' selected';?>>O. Siruvayal</option>
                           <option value="Okkur"<?php if($native_place == 'Okkur') echo ' selected';?>>Okkur</option>
                           <option value="P. Alagapuri"<?php if($native_place == 'P. Alagapuri') echo ' selected';?>>P. Alagapuri</option>
                           <option value="Paganeri"<?php if($native_place == 'Paganeri') echo ' selected';?>>Paganeri</option>
                           <option value="Palavangudi"<?php if($native_place == 'Palavangudi') echo ' selected';?>>Palavangudi</option>
                           <option value="Pallathur"<?php if($native_place == 'Pallathur') echo ' selected';?>>Pallathur</option>
                           <option value="Panagudi"<?php if($native_place == 'Panagudi') echo ' selected';?>>Panagudi</option>
                           <option value="Panayapatti"<?php if($native_place == 'Panayapatti') echo ' selected';?>>Panayapatti</option>
                           <option value="Pattamangalam"<?php if($native_place == 'Pattamangalam') echo ' selected';?>>Pattamangalam</option>
                           <option value="Pon. Pudupatti"<?php if($native_place == 'Pon. Pudupatti') echo ' selected';?>>Pon. Pudupatti</option>
                           <option value="Puduvayal"<?php if($native_place == 'Puduvayal') echo ' selected';?>>Puduvayal</option>
                           <option value="Pulangkurichi"<?php if($native_place == 'Pulangkurichi') echo ' selected';?>>Pulangkurichi</option>
                           <option value="Ramachandrapuram"<?php if($native_place == 'Ramachandrapuram') echo ' selected';?>>Ramachandrapuram</option>
                           <option value="Rangiem"<?php if($native_place == 'Rangiem') echo ' selected';?>>Rangiem</option>
                           <option value="Rayavaram"<?php if($native_place == 'Rayavaram') echo ' selected';?>>Rayavaram</option>
                           <option value="Sakkandhi"<?php if($native_place == 'Sakkandhi') echo ' selected';?>>Sakkandhi</option>
                           <option value="Sembanur"<?php if($native_place == 'Sembanur') echo ' selected';?>>Sembanur</option>
                           <option value="Sevvur"<?php if($native_place == 'Sevvur') echo ' selected';?>>Sevvur</option>
                           <option value="Shanmuganathapuram"<?php if($native_place == 'Shanmuganathapuram') echo ' selected';?>>Shanmuganathapuram</option>
                           <option value="Siravayal"<?php if($native_place == 'Siravayal') echo ' selected';?>>Siravayal</option>
                           <option value="Sirukudalpatti"<?php if($native_place == 'Sirukudalpatti') echo ' selected';?>>Sirukudalpatti</option>
                           <option value="Siruvayal"<?php if($native_place == 'Siruvayal') echo ' selected';?>>Siruvayal</option>
                           <option value="Sivayogapuram"<?php if($native_place == 'Sivayogapuram') echo ' selected';?>>Sivayogapuram</option>
                           <option value="Thanichavoorani"<?php if($native_place == 'Thanichavoorani') echo ' selected';?>>Thanichavoorani</option>
                           <option value="Thenipatti"<?php if($native_place == 'Thenipatti') echo ' selected';?>>Thenipatti</option>
                           <option value="Ulagampatti"<?php if($native_place == 'Ulagampatti') echo ' selected';?>>Ulagampatti</option>
                           <option value="V. Lakshmipuram"<?php if($native_place == 'V. Lakshmipuram') echo ' selected';?>>V. Lakshmipuram</option>
                           <option value="Valayapatti"<?php if($native_place == 'Valayapatti') echo ' selected';?>>Valayapatti</option>
                           <option value="Vegupatti"<?php if($native_place == 'Vegupatti') echo ' selected';?>>Vegupatti</option>
                           <option value="Venthanpatti"<?php if($native_place == 'Venthanpatti') echo ' selected';?>>Venthanpatti</option>
                           <option value="Vetriyur"<?php if($native_place == 'Vetriyur') echo ' selected';?>>Vetriyur</option>
                           <option value="Virachilai"<?php if($native_place == 'Virachilai') echo ' selected';?>>Virachilai</option>
                           <option value="Viramathi"<?php if($native_place == 'Viramathi') echo ' selected';?>>Viramathi</option>
                           <option value="Viswanathapuram"<?php if($native_place == 'Viswanathapuram') echo ' selected';?>>Viswanathapuram</option>
                           <option value="Others - not listed here"<?php if($native_place == 'Others - not listed here') echo ' selected';?>>Others - not listed here</option>	
                     </select>
                  </div>
                  <div class="col-md-12"><p class="style1">Do you need a Wheelchair Accessible room for you or any member in your party? <span>*</span></p>
                     <div><input name="wheelchair_accessible" type="radio" value="Yes"<?php if($wheelchair_accessible == 'Yes') echo ' checked';?> />  YES &nbsp;&nbsp;&nbsp; <input name="wheelchair_accessible" type="radio" value="No"<?php if($wheelchair_accessible == 'No') echo ' checked';?> />  NO</div>
                  </div>
               </div>
               <br>
               <div class="clear"></div>
               <div class=""><input name="btnSubmit" type="submit" value="Submit" class="submit1" /></div>
               <!--<p></p>
               <p>YOUR REFERENCE NUMBER IS <b><?php echo $ref_number;?></b><br />If you want to modify your registration, please use your email and the above registration number.</p>-->
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
<script type="text/javascript">
$(document).ready(function(){	
	$('#country').change(function() {
			
		var country_id_array = $(this).val();
		var country_array = country_id_array.split("|");	
		var country_id = country_array[0]; 
		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'GetState', CountryId: country_id},
			dataType: 'html',
			success: function(data) {					
					$('#spinner-div').hide();							
					$("#st").html(data);				
			}			
		});		
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