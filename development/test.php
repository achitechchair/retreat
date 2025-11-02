<?php
	include_once('includes/config.inc.php');
	
	$email_content = "Hi";
	
	$objMail = new PHPMailer();
	$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
	$objMail->Subject = $f->getValue($AdminSettings['company_name'])." :: Payment Confirmation";
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
	$objMail->AddAddress("biswadip.dasgupta@gmail.com", "");	
	//$objMail->AddCC($AdminSettings['your_email_address'],$AdminSettings['your_name']);
	$objMail->Send();
?>