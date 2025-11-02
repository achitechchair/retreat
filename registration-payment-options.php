<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$reg_id = $_SESSION['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	if(empty($_SESSION['landing']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	$ref_number = $row['ref_number'];
	$total_price = $row['total_price'];
	
	$geteway_charge = $total_price * (3/100);
	$total_price_paypal = $total_price + round($geteway_charge);
	
	
	if(empty($_POST['btnPayment']) == FALSE)
	{
		$pay_mode = $_POST['pay_mode'];
		
		$data_array = array(
			"pay_mode" => $f->setValue($pay_mode)
		);
		$db->update(TABLE, $data_array, "reg_id", $_SESSION['reg_id']);
		
		if($pay_mode == 'PayPal')
		{				
			$f->Redirect(MAIN_WEBSITE_URL."/registration-payment-process.php?reg_id=".$reg_id);
			
		}else{		
			
			$f->Redirect(MAIN_WEBSITE_URL."/registration-payment-process-others.php?reg_id=".$f->EncryptDecrypt($reg_id));
		}
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
   	<form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-payment-options">
		<div class="inner_container" style="max-width: 700px;">
			<div style="font-size:25px;color:#2F2D9B; font-weight:600; text-transform:uppercase; padding-bottom:30px;">Choose your Payment Option</div>
         <?php if(empty($_GET['msg']) == FALSE && $_GET['msg'] == 'PaymentErrorPayPal'){ ?>
               <div class="clear"></div>
               <div align="center" class=""><?php echo $f->getHtmlErrorSmall("Either you have cancelled the transaction or there is something wrong at PayPal.");?></div>
               <p></p>
         <?php } ?>
			<div class="register_area">
				<div class="input_radio"><input required type="radio" name="pay_mode" class="pay_opt" value="Zelle"<?php if(($_GET['msg'] ?? '') != 'PaymentErrorPayPal') echo ' checked'?>>  Zelle Pay - <span style="font-weight:400">jointtreasurer@achi.org</span> <span style="font-weight:500">($<?php echo round($total_price);?>)</span>&nbsp;<a href="" data-toggle="modal" data-target="#zelle_info">How to pay by zelle</a></div>
				<div class="input_radio"><input required type="radio" name="pay_mode" class="pay_opt" value="Check">  Check <span style="font-weight:500">($<?php echo round($total_price);?>)</span>&nbsp;<a href="" data-toggle="modal" data-target="#check_info">How to pay by Check</a></div>				
            <div class="input_radio"><input required type="radio" name="pay_mode" class="pay_opt" value="PayPal"<?php if(($_GET['msg'] ?? '') == 'PaymentErrorPayPal') echo ' checked'?>>  PayPal <span style="font-weight:500">($<?php echo $total_price_paypal;?>)</span></div>
				<br>
				<div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-payment-bill'" />&nbsp;&nbsp;&nbsp;<input name="btnPayment" id="btnPayment" type="submit" value="Submit Registration" class="submit" /></div>
           	<p style="margin-top:20px;"><span>&#10803;</span> <b>Please Note:</b> Registration is not marked complete until your payment is received.</p>
         </div>
		</div>
      </form>
	<div class="clear"></div>
   </div> 
</section> 

<div class="modal fade" id="zelle_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <div data-dismiss="modal" aria-label="Close" class="package_close" style="cursor:pointer;"><img src="<?php echo MAIN_WEBSITE_URL?>/images/close.png" alt="" /></div>
            <p>&nbsp;</p>
            <div style="margin:10px; text-align:justify;">
            	<?php 
						$sql_content = "SELECT * FROM `tbl_static_page_content` WHERE `page_id`='2'";
						$res_content = $db->get($sql_content,__FILE__,__LINE__);
						$row_content = $db->fetch_array($res_content);
						$db->free_result($res_content);
						echo $f->getHTMLDecode($row_content['page_content']);
					?>
            </div>            
         </div>
      </div>
   </div>
</div> 

<div class="modal fade" id="check_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <div data-dismiss="modal" aria-label="Close" class="package_close" style="cursor:pointer;"><img src="<?php echo MAIN_WEBSITE_URL?>/images/close.png" alt="" /></div>
            <p>&nbsp;</p>
            <div style="margin:10px; text-align:justify;">
            	<?php 
						$sql_content = "SELECT * FROM `tbl_static_page_content` WHERE `page_id`='3'";
						$res_content = $db->get($sql_content,__FILE__,__LINE__);
						$row_content = $db->fetch_array($res_content);
						$db->free_result($res_content);
						echo $f->getHTMLDecode($row_content['page_content']);
					?>
            </div>            
         </div>
      </div>
   </div>
</div> 

<div class="clear"></div>
<footer>
     <?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
<script type="text/javascript">
$(document).ready(function(){	
	$('.pay_opt').click(function() {
		var opt = $(this).val();
		if(opt == 'PayPal')
		{
			$("#btnPayment").val("Make Payment");
		}else{
			$("#btnPayment").val("Submit Registration");
		}
	});
});
</script>
</body>
</html>