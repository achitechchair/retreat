<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	$reg_id = $f->EncryptDecrypt($_GET['reg_id'], 'decrypt');
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	$pay_mode = $f->getValue($row['pay_mode']);
	$ref_number = $f->getValue($row['ref_number']);
	$payment_amount = $f->getValue($row['total_price']);
	
	if(empty($_SESSION['landing']) == TRUE)
	{
	
		if($pay_mode == 'Check')
		{
			$sql = "UPDATE `tbl_registration` SET 
					`status`='Active', 
					`pay_status`='Pending', 
					`pay_mode`='Check'
					WHERE `reg_id`='".$reg_id."'";
			$db->get($sql);
		}
		
		if($pay_mode == 'Zelle')
		{
			$sql = "UPDATE `tbl_registration` SET 
					`status`='Active', 
					`pay_status`='Partially Paid', 
					`pay_mode`='Zelle'
					WHERE `reg_id`='".$reg_id."'";
			$db->get($sql);
		}
		
		$sql_2 = "UPDATE `tbl_reg_family_info` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_2);
		
		$sql_2 = "UPDATE `tbl_reg_members_in_your_party` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_2);
		
		$sql_2 = "UPDATE `tbl_reg_profile_info` SET `status`='Active' WHERE `reg_id`=".$reg_id;
		$db->get($sql_2);	
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
          		<?php 
						if($pay_mode == 'Check')
						{
					?>
               <h1 class="heading1">Pay by Check</h1>
               <div class="margin_25 StaticContent_new">
               	<p>Your NSNA Retreat Registration has been received</p>
                  <p><b>Registration Number:</b> <?php echo $ref_number;?></p>
                  <p><b>Pay:</b> $<?php echo $payment_amount;?></p>
                  <p><b>Payable To:</b> NSNA</p>
                  <p><b>Memo:</b> Put the <b>registration number</b> above</p>                
                  <p>Make the check payable to NSNA and mail the check to 'Nagarathar Sangam of North America, 4598 Appletree Court, West Bloomfield, MI 48323</p>
              </div>
              <?php
				  			$email_content = '<p><b>Registration Number:</b> '.$ref_number.'</p>';
							$email_content.= '<p><b>Pay:</b> $'.$payment_amount.'</p>';
							$email_content.= '<p>Make the check payable to NSNA and mail the check to Nagarathar Sangam of North America, 4598 Appletree Court, West Bloomfield, MI 48323.</p>';
						}
						elseif($pay_mode == 'Zelle')
						{
				  ?>
               <h1 class="heading1">Pay by Zelle</h1>
               <div class="margin_25 StaticContent_new">
               	<p>Your NSNA Retreat Registration has been received</p>
                  <p><b>Registration Number:</b> <?php echo $ref_number;?></p>
                  <p><b>Pay:</b> $<?php echo $payment_amount;?></p>
                  <p>Pay by zelle to this email id - <b>jointtreasurer@achi.org</b> and include the above <b>registration number</b> in the memo field. </p>                  
                 <!-- <p align="center"><img src="<?php echo MAIN_WEBSITE_URL;?>/images/zella.jpg" alt="" width="163" height="218"></p>-->
              </div>
              <?php 				  		
						$email_content = '<p><b>Registration Number:</b> '.$ref_number.'</p>';
						$email_content.= '<p><b>Pay:</b> $'.$payment_amount.'</p>';
						$email_content.= '<p>Pay by zelle to this email id - <b>jointtreasurer@achi.org</b> and include the above <b>registration number</b> in the memo field.</p>';
							
						}
					?>
               <p style="margin-top:20px;"><span>&#10803;</span> If you want to modify your registration, please use your email and the above <b>registration number</b>.</p>
          </div>
          <div class="clear"></div>
     </div>
</section>
<div class="clear"></div>
<footer>
     <?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
<?php
if(empty($_SESSION['landing']) == TRUE)
{	
	$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name` FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b
				  WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$reg_id." AND a.`status`='Active'
				  GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
	$res_cus = $db->get($sql_cus);
	$row_cus = $db->fetch_array($res_cus);
	$email_id = $f->getValue($row_cus['email_id']);
	$first_name = $f->getValue($row_cus['first_name']);
	$last_name = $f->getValue($row_cus['last_name']);
	
	$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
	$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
	$email_message.= "Thank you for registering for the 2024 Michigan Retreat!<br /> 
							Your NSNA Retreat Registration has been received.<br />
							Your Registration number is <b>".$ref_number."</b> and the Amount due is <b>$".$payment_amount."</b>.<br /> 
							To secure your spot and fully complete your registration, please make the payment through your selected mode of payment. Once the payment is successfully transferred, your registration will be fully confirmed.<br /> 
							If you have any questions or need further assistance, please reach out to
							miretreat.registration@achi.org.
							<br /><br />";
	$email_message.= "Best regards,<br />NSNA Michigan Retreat Committee";
	$email_message.= "</font>";
	
	$objMail = new PHPMailer();
	$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
	$objMail->Subject = "Your Retreat Registration is almost complete! #".$ref_number;
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
	
	// ======================================================================================================================

	$_SESSION['reg_id'] = "";
   
	// Unset all of the session variables.
	$_SESSION = array();	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if(isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}	
	// Finally, destroy the session.
	session_destroy();
	
	session_start();
   session_regenerate_id();
}
$_SESSION['landing'] = 1;
?>
</body>
</html>