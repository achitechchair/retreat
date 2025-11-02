<?php
	include_once('includes/config.inc.php');
		
	//$payby = $_GET['payby'];
	$reg_id = $_GET['reg_id'];
	//$sessid = $_GET['sessid'];		
	$transaction_id = $_POST['txn_id'];
		
	// Update the status to active
	if($_GET['action'] == 'y')
	{	
		// Fetching records from the "tbl_registration" table to send a confirmation email
		$sql_reg = "SELECT * FROM `tbl_registration` WHERE `reg_id`='".$reg_id."'";
		$res_reg = $db->get($sql_reg);
		$row_reg = $db->fetch_array($res_reg);
		
		$payment_amount = $row_reg['total_price'];
		$geteway_charge = 0;
		$ref_number = $f->getValue($row_reg['ref_number']);
		
		if($payment_amount > 0)
		{
			$geteway_charge = round($payment_amount * (3/100));		
		}
		
		$sql = "UPDATE `tbl_registration` SET 
				`status`='Active',
				`sent_reg_email`='Yes', 
				`pay_status`='Paid', 
				`pay_mode`='PayPal',
				`geteway_charge`=".$geteway_charge.",
				`transaction_id`='".$transaction_id."',
				`pay_date`='".date('Y-m-d')."'     
				  WHERE `reg_id`='".$reg_id."'";
		$db->get($sql);
		
						
		$sql_2 = "UPDATE `tbl_reg_family_info` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_2);
		
		$sql_3 = "UPDATE `tbl_reg_members_in_your_party` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_3);
		
		$sql_4 = "UPDATE `tbl_reg_profile_info` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_4);	
		
		$sql_profile = "SELECT a.`email_id`, b.`first_name`, b.`last_name` FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b
							  WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$reg_id." AND a.`status`='Active'
							  GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
		$res_profile = $db->get($sql_profile);
		$row_profile = $db->fetch_array($res_profile); 	
		$ToUserInfo = array();	
		$ToUserInfo['Email'] = $f->getValue($row_profile['email_id']);	
		$first_name = $f->getValue($row_profile['first_name']);
		$last_name = $f->getValue($row_profile['last_name']);
		
		$email_content = '<table align="center" width="700" cellspacing="1" cellpadding="0" style="border: 1px solid #CCCCCC; border-collapse: collapse; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;">						  
							  <tr>
								<td valign="middle" align="center" style="background-color:#f2f4f4;">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td height="10" style="background-color:#f2f4f4; position:relative; -moz-transition:all 1s ease-in 0s; -webkit-transition:all 1s; -o-transition:all 1s;"></td>
										</tr>
										<tr>
											<td align="center" style="background-color:#f2f4f4; position:relative; -moz-transition:all 1s ease-in 0s; -webkit-transition:all 1s; -o-transition:all 1s;"><a href="'.WEBSITE_URL.'" target="_blank"><img src="'.WEBSITE_URL.'/images/logo.png" border="0" width="200" height="180" /></a></td>
										</tr>
										<tr>
											<td height="10" style="background-color:#f2f4f4; position:relative; -moz-transition:all 1s ease-in 0s; -webkit-transition:all 1s; -o-transition:all 1s;"></td>
										</tr>									
									</table>
								</td>
							  </tr>';
						
		$email_content.= '<tr>
						  		 <td height="10">&nbsp;</td>
						 	   </tr>								
								<tr>
						  		 <td style="padding:10px;">Dear '.$first_name.' '.$last_name.',<br /><br />Your registration is successful.<br /><br />Your Registration Number is: <b>'.$ref_number.'</b>.<br /><br /> If you want to modify your registration, please use your email and the above registration number.</td>
						 	   </tr>
								<tr>
									<td style="padding:10px;">Thanks,<br />'.$f->getValue($AdminSettings['company_name']).'</td>
								</tr>								
								<tr>
									<td>&nbsp;</td>
								</tr>					 
							</table>';
	 				
	//$email_content.= "<br/><br/>".nl2br($f->getValue($AdminSettings['email_signature']));		
	
	//echo $email_content;exit();
		
	//$_SESSION['the_payment_details'] = $email_content;
	
	// Generating Mail Content
			
	$objMail = new PHPMailer();
	$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
	$objMail->Subject = $f->getHTMLDecode("Congratulations! Your Retreat Registration is Confirmed! #".$ref_number);
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
	$objMail->MsgHTML($email_content);
	$objMail->CharSet = 'UTF-8';
	$objMail->AddAddress($ToUserInfo['Email'],"");	
	$objMail->AddCC($AdminSettings['your_email_address'],$AdminSettings['your_name']);
	$objMail->AddCC($AdminSettings['your_email_address_2'],$AdminSettings['your_name']);
	$objMail->Send();
		
	$f->Redirect(WEBSITE_URL."/thankyou.php?messg=Registration");	
}
?>
