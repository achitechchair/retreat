<?php 
	require_once("includes/config.inc.php");	
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');
		
	$sql = "SELECT a.*, 
			   SUM(`number_of_people_adult`) AS `total_adult`, 
				SUM(`number_of_child_6_17`) AS `6_17`,
				SUM(`number_of_child_0_5`) AS `0_5`,
				SUM(`no_of_people_youth_activities`) AS `youth_activities`,
				SUM(`no_of_dinner_people`) AS `dinner_people`,
				SUM(`no_of_dinner_people_children`) AS `dinner_people_children`,
				FunGetTotalVegByID(a.`reg_id`) AS `total_veg`,
				FunGetTotalNonVegByID(a.`reg_id`) AS `total_non_veg`, 
				FunTotalNetworkingEventById(a.`reg_id`) AS `total_networking_event`,
				
			 	FunGroupPackageEditSection(a.`reg_id`) AS `packages` 
			 	FROM `tbl_registration` AS a WHERE a.`reg_id`=".$_SESSION['reg_id_edit']." AND a.`mark_for_deleted`='No'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);
	
	$ref_number = $f->getValue($row['ref_number']);
	$total_price = $row['total_price'];
	$geteway_charge = $row['geteway_charge'];
	$pay_status = $f->getValue($row['pay_status']);
	$pay_mode = $f->getValue($row['pay_mode']);	
	
	$packages = $f->getValue($row['packages']);	
	
	$total_pay_price = $total_price + $geteway_charge;
	$_SESSION['pay_mode'] = $f->getValue($row['pay_mode']);
	
	$cancellation_charges = $row['cancellation_charges'];
	$_SESSION['reg_status'] = $f->getValue($row['status']);
	$refund_price = ($total_pay_price) - ($cancellation_charges);
	
	$cancel = $_GET['cancel'] ?? '';
	if($cancel == 'Y')
	{
		$ref_number_old = $ref_number;
		$cancellation_charges = 0;
		$ref_number = substr($ref_number,1);
		if($pay_status!='Unpaid')
		{
			if(strtotime(date('Y-m-d'))>=strtotime(CANCEL_DATE_FROM) && strtotime(date('Y-m-d'))<=strtotime(CANCEL_DATE_TO))
			{
				$cancellation_charges = CANCEL_CHARGE;
			}
		}
		
		$data_array = array(
			"cancellation_charges" => $cancellation_charges,
			"status" => "Cancel",			
			"ref_number" => "C".$ref_number,
			"cancel_dt" => date('Y-m-d H:i:s')
		);
		
		$db->update("tbl_registration", $data_array, "reg_id", $_SESSION['reg_id_edit']);
		
		// Sending Email ===========================================================================
		$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name`
						FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b
				  		WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$_SESSION['reg_id_edit']." GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
		$res_cus = $db->get($sql_cus);
		$row_cus = $db->fetch_array($res_cus);
		$email_id = $f->getValue($row_cus['email_id']);
		$first_name = $f->getValue($row_cus['first_name']);
		$last_name = $f->getValue($row_cus['last_name']);
		
		$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
		$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
		/*if($pay_status == 'Unpaid')
		{
			$email_message.= "Your NSNA Retreat registration has been successfully canceled. 								
								  <br /><br />";
		}else{
			$email_message.= "Your NSNA Retreat registration has been successfully canceled.<br /><br />
									Your refund amount is $".$total_pay_price.". 
									NSNA Treasury will process your refund and issue you a check after the event. 
									Please contact us at miretreat.registration@achi.org if you have any questions.
									<br /><br />";								
		}*/
		
		$email_message.= "Your NSNA registration has been successfully canceled. 
									If you had made a payment already, NSNA Treasury will process your refund and issue you a check after the event. 
									Please contact us at miretreat.registration@achi.org if you have any questions.<br /><br />";	
		
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
                <div class="flexcaption_style4">Dashboard</div>
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
					if(empty($_SESSION['reg_status']) == FALSE && $_SESSION['reg_status'] == 'Active')
					{
				?>
				<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:10px;">Your Registration Information</div>
            <p></p> 
				<!--<div style="font-size:20px; padding-bottom: 10px;">From update your registration information process from the left hand navigation.</div>-->
            <div style="font-size:22px; padding-bottom: 10px; font-weight:700; border-bottom:1px solid #000; padding-bottom:15px; margin-bottom:15px;">For any modifications to your package, please email miretreat.registration@achi.org with your request.</div>
				<div style="font-size:20px; padding-bottom: 10px;">Your Registration Number: <b><?php echo $ref_number;?></b></div>
            <div style="font-size:20px; padding-bottom: 10px;">Total Payment Amount: <b>$<?php echo $total_pay_price;?></b></div>
            <div style="font-size:20px; padding-bottom: 25px;">Payment Status: <b><?php echo $pay_status;?> (<?php echo $pay_mode;?>)</b></div>
           	<p></p> 
            <div style="font-size:20px; padding-bottom: 10px;">Package Details: <b><?php echo $packages;?></b></div>
            
            <div style="font-size:20px; padding-bottom: 10px;">Adult: <b><?php echo $f->getValue($row['total_adult']);?></b></div>
            <?php 
					if($f->getValue($row['6_17']) > 0)
					{
				?>
            <div style="font-size:20px; padding-bottom: 10px;">Age (6 to 17): <b><?php echo $f->getValue($row['6_17']);?></b></div>
            <?php
					}
				?>
            <?php 
					if($f->getValue($row['0_5']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Age (0 to 5): <b><?php echo $f->getValue($row['0_5']);?></b></div>
            <?php
					}
				?>	
            <?php 
					if($f->getValue($row['youth_activities']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Youth Activities: <b><?php echo $f->getValue($row['youth_activities']);?></b></div>
            <?php
					}
				?>	
            <?php 
					if($f->getValue($row['dinner_people']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Banquet Dinner: <b><?php echo $f->getValue($row['dinner_people']);?></b></div>
            <?php
					}
				?>	
            <?php 
					if($f->getValue($row['dinner_people_children']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Banquet Dinner (Children): <b><?php echo $f->getValue($row['dinner_people_children']);?></b></div>
            <?php 
					}
				?>	
            <?php 
					if($f->getValue($row['total_veg']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Veg: <b><?php echo $f->getValue($row['total_veg']);?></b></div>
            <?php
					}
				?>	
            <?php 
					if($f->getValue($row['total_non_veg']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Non Veg: <b><?php echo $f->getValue($row['total_non_veg']);?></b></div>
            <?php 
					}
				?>	
             <?php 
					if($f->getValue($row['total_networking_event']) > 0)
					{
				?>	
            <div style="font-size:20px; padding-bottom: 10px;">Total Networking Event: <b><?php echo $f->getValue($row['total_networking_event']);?></b></div>
            <?php
					}
				?>	
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
            <!--<div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your registration has been canceled successfully.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your refund amount is $<?php //echo $refund_price;?>.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> NSNA Treasury will process your refund and issue you a check after the event.</div>-->
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> Your registration has been canceled successfully.</div>
            <div style="font-size:20px; padding-bottom: 10px;"><span>&#10803;</span> If you had made a payment already, NSNA Treasury will process your refund and issue you a check after the event.</div>
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