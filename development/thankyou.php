<?php 
	require_once("includes/config.inc.php");	
	
	$messg = $_GET['messg'];
	switch($messg)
	{
		case "contact":
			$msg = "<span class='font_30'>&#10003;</span> Your message has been received, and someone will contact you shortly.";
			break;
							
		case "Registration":
			
			if($_GET['payby'] == 'PayPal' && empty($_GET['reg_id']) == FALSE)
			{
				$reg_id = $f->EncryptDecrypt($_GET['reg_id'], "decrypt");
				$sql = "SELECT `transaction_id` FROM `tbl_registration` WHERE `reg_id`=".$f->setValue($reg_id);
				$res = $db->get($sql);	
				$rec = $db->num_rows($res);			
				$row = $db->fetch_array($res);
				$transaction_id = $f->getValue($row['transaction_id']);
				if($rec == 0 || empty($transaction_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-payment-options/?msg=PaymentErrorPayPal");
			}
			
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
			
			$msg = "<span class='font_30'>&#10003;</span> Your payment has been successfully confirmed.<br />Please check your email for the confirmation.";
			break;
				
		case "forgotpassword":
			$msg = "<span class='font_30'>&#10003;</span> Your change password link has been sent to your email address.";	
			break;
			
		case "changepassword":
			$msg = "<span class='font_30'>&#10003;</span> Your password has been successfully changed.";			
		break;
		
		case "logout":
			$msg = "<span class='font_30'>&#10003;</span> You have successfully logged out.";
		break;
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
<img src="images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Thank You</div>
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
   	<div class="empty_no_record"><p class="info_text_no_record" align="center"><span class="font_30"><?php echo $f->getValue($msg);?></span></p></div>		
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