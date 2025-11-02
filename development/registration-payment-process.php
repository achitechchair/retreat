<?php
	require_once("includes/config.inc.php");	
	define("TABLE", "tbl_registration");
	
	$reg_id = $_GET['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT a.*, b.* FROM `".TABLE."` AS a, `tbl_reg_profile_info` AS b
			  WHERE a.`reg_id`=".$reg_id." AND a.`reg_id`=b.`reg_id` ORDER BY b.`reg_profile_info_id` DESC LIMIT 0, 1";
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}	
	
	$row_order = $db->fetch_array($res);
	
	
	$bill_first_name = "";	
	$bill_last_name = "";	
	$bill_address_1 = "";	
	$bill_city = "";	
	$bill_state = "";	
	$bill_zip = "";	
	$bill_country = "";	
	
	$email = $f->getValue($row_order['email_id']);	
	$invoice_number = $f->getValue($row_order['ref_number']);
	$payment_amount = $f->getValue($row_order['total_price']);
	
	if($payment_amount > 0)
	{
		$geteway_charge = $payment_amount * (3/100);
		$payment_amount = $payment_amount + round($geteway_charge);
	}
	
	//$payment_amount='0.01';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<style type="text/css">
<!--
.text {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color:#000;
}
-->
</style>

<script language="javascript" type="text/javascript">
function _doPost() 
{	
	var f = window.document.frmPayPal;
	f.submit();
}
</script>


</head>

<body style="background-color:#FFF;" onload="javascript:_doPost();">

<div align="center"><br>
	<br />
	<br />
	<br />
	<br />
	<img src="<?php echo WEBSITE_URL;?>/images/loading.gif" /> </div>
	<br>
	<p align="center"  style="font-size:18px;">Please <b>do not refresh the page</b> and wait while we are processing your payment. This can take a few minutes.</p>
	<center>
      <font class="text">If this page do not get refreshed in 10 Seconds please</font> <a href="#" onclick="javascript:_doPost();" style="color:#06C;">Click Here</a>
   </center>
<?php 	
	$paypal_id = $f->getValue($AdminSettings['paypal_id']);
?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="frmPayPal" id="frmPayPal">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="<?php echo $paypal_id;?>">
	<input type="hidden" name="item_name" value="Payment For <?php echo $f->getValue($AdminSettings['company_name']);?>">
	<input type="hidden" name="cpp_payflow_color" value="#FFE4A3">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="return" value="<?php echo MAIN_WEBSITE_URL;?>/thankyou.php?reg_id=<?php echo $f->EncryptDecrypt($reg_id);?>&payby=PayPal&messg=Registration">
	<input type="hidden" name="notify_url" value="<?php echo MAIN_WEBSITE_URL;?>/registration-payment-confirm.php?reg_id=<?php echo $reg_id;?>&payby=PayPal&action=y">
	<input type="hidden" name="amount" value="<?php echo $payment_amount;?>">
	<input type="hidden" name="custom" value="">
	<input type="hidden" name="email" value="<?php echo $email;?>" />
	<input type="hidden" name="first_name" value="<?php echo $bill_first_name;?>" />
	<input type="hidden" name="last_name" value="<?php echo $bill_last_name;?>" />
	<input TYPE="hidden" name="address1" value="<?php echo $bill_address_1;?>" />
	<input TYPE="hidden" name="city" value="<?php echo $bill_city;?>" />
	<input TYPE="hidden" name="state" value="<?php echo $bill_state;?>" />
	<input TYPE="hidden" name="zip" value="<?php echo $bill_zip;?>" />
	<input type="hidden" name="invoice" value="<?php echo $invoice_number;?>" />
	<input type="hidden" name="cbt" value="Click Here To Complete The Transaction" />
	<input type="hidden" name="cancel_return" value="<?php echo MAIN_WEBSITE_URL;?>/registration-payment-options/?msg=PaymentErrorPayPal">
</form>

</body>
</html>
