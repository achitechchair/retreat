<?php 
	require_once("includes/config.inc.php");	
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');
		
	$sql = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mark_for_deleted`='No'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);
	
	$ref_number = $row['ref_number'];
	$total_price = $row['total_price'];
	$geteway_charge = $row['geteway_charge'];
	$pay_status = $f->getValue($row['pay_status']);
	$pay_mode = $f->getValue($row['pay_mode']);	
	
	$total_pay_price = $total_price + $geteway_charge;
	$_SESSION['pay_mode'] = $f->getValue($row['pay_mode']);
	
	$cancellation_charges = $row['cancellation_charges'];
	$_SESSION['reg_status'] = $f->getValue($row['status']);
	$refund_price = ($total_pay_price) - ($cancellation_charges);
	
	$cancel = $_GET['cancel'];
	if($cancel == 'Y')
	{
		$ref_number_old = $ref_number;
		$cancellation_charges = 0;
		$ref_number = substr($ref_number,1);
		if(strtotime(date('Y-m-d'))>=strtotime(CANCEL_DATE_FROM) && strtotime(date('Y-m-d'))<=strtotime(CANCEL_DATE_TO))
		{
			$cancellation_charges = CANCEL_CHARGE;
		}
		
		$data_array = array(
			"cancellation_charges" => $cancellation_charges,
			"status" => "Cancel",
			"ref_number" => "C".$ref_number,
			"cancel_dt" => date('Y-m-d H:i:s')
		);
		
		$db->update("tbl_registration", $data_array, "reg_id", $_SESSION['reg_id_edit']);
		
		// Sending Email ===========================================================================
		$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name` FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b
				  		WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$_SESSION['reg_id_edit']." GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
		$res_cus = $db->get($sql_cus);
		$row_cus = $db->fetch_array($res_cus);
		$email_id = $f->getValue($row_cus['email_id']);
		$first_name = $f->getValue($row_cus['first_name']);
		$last_name = $f->getValue($row_cus['last_name']);
		
		$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
		$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
		$email_message.= "Your NSNA registration has been successfully canceled. 
								Your refund amount is $".$total_pay_price.". 
								NSNA Treasury will process your refund and issue you a check after the event. 
								Please contact us at miretreat.registration@achi.org if you have any questions.
								<br /><br />";
		$email_message.= "Best regards,<br />NSNA Michigan Retreat Committee";
		$email_message.= "</font>";		
		
		$objMail = new PHPMailer();
		$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
		$objMail->Subject = "Your Retreat Registration has been canceled - ".$ref_number_old;
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
		
		$f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard/?cancellation=Y");
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
                <div class="flexcaption_style4">Modify Registration</div>
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
         	<?php
					if($_SESSION['reg_status'] == 'Active')
					{
				?>
				<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:10px;">Your Registration Information</div>
				<div style="font-size:20px; padding-bottom: 10px;">From update your registration information process from the left hand navigation.</div>
				<div style="font-size:20px; padding-bottom: 10px;">Your Registration Number: <b><?php echo $ref_number;?></b></div>
            <div style="font-size:20px; padding-bottom: 10px;">Total Payment Amount: <b>$<?php echo $total_pay_price;?></b></div>
            <div style="font-size:20px; padding-bottom: 25px;">Payment Status: <b><?php echo $pay_status;?> (<?php echo $pay_mode;?>)</b></div>
            <?php
					if(strtotime(date('Y-m-d'))<=strtotime(CANCEL_DATE_TO))
					{
				?>
            <p>&nbsp;</p>
            <div><input name="CancelReg" id="CancelReg" type="button" value="Cancel Registration" class="submit1" /></div>
            <?php
					}
				?>	
            <?php
					}else{
				?>
            <div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:10px;">Your Registration has been Cancelled</div>
            <?php		
						if(strtotime(date('Y-m-d'))>=strtotime(CANCEL_DATE_FROM) && strtotime(date('Y-m-d'))<=strtotime(CANCEL_DATE_TO))
						{
				?>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your registration has been canceled successfully.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> NSNA will withhold $<?php echo CANCEL_CHARGE;?>. This hold back is for contingency and is solely to protect NSNA from significant financial loss and could be refunded back to you after the completion of the Retreat and full accounting for the same.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your minimum refund amount is $<?php echo $total_pay_price;?>.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> NSNA Treasury will process your refund and issue you a check after the event.</div>
            <?php 
						}else{
				?>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your registration has been canceled successfully.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your refund amount is $<?php echo $refund_price;?>.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> NSNA Treasury will process your refund and issue you a check after the event.</div>
				<?php
						}
					}
				?>	
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
$(document).ready(function() {		
	$('#CancelReg').click(confirm);	
	function confirm() 
	{ 
		$.jAlert({
			'type': 'confirm',
			'title':'Alert',
			'confirmQuestion':' Are you sure you want to Cancel? ',
			'theme': 'red',
			
			'closeBtn': false,
			'onConfirm': function(){
				window.location.href = "<?php echo MAIN_WEBSITE_URL;?>/registration-dashboard/?cancel=Y";
			}
		});
		return false;
	}	
});
</script>

</body>
</html>