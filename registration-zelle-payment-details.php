<?php 
	require_once("includes/config.inc.php");	
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');
	
	if(empty($_GET['action']) == false && $_GET['action'] == "Delete")
	{
		$sqlDelete = "DELETE FROM `tbl_reg_zelle_transaction` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `transaction_id`=".$_GET['transaction_id'];
		$db->get($sqlDelete);
		$f->Redirect(MAIN_WEBSITE_URL."/registration-zelle-payment-details/?del=1");	
	}		
	
	$sql = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mark_for_deleted`='No' AND `status`='Active'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);
	
	$ref_number = $row['ref_number'];
	$total_price = $row['total_price'];
	//$geteway_charge = $row['geteway_charge'];
	
	$sql_pay = "SELECT SUM(`amount`) as `sum_amount` FROM `tbl_reg_zelle_transaction` WHERE `reg_id`=".$_SESSION['reg_id_edit'];
	$res_pay = $db->get($sql_pay);
	$row_pay = $db->fetch_array($res_pay);
	
	$already_paid = $row_pay['sum_amount'];
	if(empty($already_paid) == TRUE) $already_paid = 0;
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		$total_pay_price = $_POST['total_pay_price'];
		$already_paid_price = $_POST['already_paid_price'];
		
		$total_due = ($total_pay_price) - ($already_paid_price);
		
		$refno = $_POST['refno'];
		$amount = $_POST['amount'];
		$dated = $_POST['dated'];
		
		$sql_pay_check = "SELECT `refno` FROM `tbl_reg_zelle_transaction` WHERE `refno`='".$f->setValue($refno)."' AND `reg_id`=".$_SESSION['reg_id_edit'];
		$res_pay_check = $db->get($sql_pay_check);
		$rec_pay_check = $db->num_rows($res_pay_check);
		if($rec_pay_check > 0)
		{
			$msg = $f->getHtmlErrorSmall("Reference number already exist.");	
		}
		elseif($amount > $total_due)
		{
			$msg = $f->getHtmlErrorSmall("Your due amount should be ".$total_due.".");
		}
		else
		{
			$data_array = array(
				"reg_id" => $_SESSION['reg_id_edit'], 
				"refno" => ($_POST['refno']) ? ($f->setValue($_POST['refno'])) : ('NULL'),
				"amount" => ($_POST['amount']) ? ($f->setValue($_POST['amount'])) : ('0'),
				"dated" => ($_POST['dated']) ? ($f->setValue($_POST['dated'])) : ('NULL')	
			);
			$db->insert("tbl_reg_zelle_transaction", $data_array);
			$f->Redirect(MAIN_WEBSITE_URL."/registration-zelle-payment-details/?success=1");
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
<!------------------- Slider area------------------->
<section class="inner_banner" style="background-image:url(<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg);">
<img src="images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Zelle Payment Details</div>
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
					$total_due_today = ($total_price) - ($already_paid);
				?>
				<div style="font-size:25px; color: #2F2D9B; font-weight: 600; padding-bottom:10px;">Total Payment: <span style="color: black;">$<?php echo $total_price;?></span></div>
				<div style="font-size:25px; color: #2F2D9B; font-weight: 600; padding-bottom:10px;">Already Paid: <span style="color: black;">$<?php echo $already_paid;?></span></div>
				<div style="font-size:25px; color: #2F2D9B; font-weight: 600; padding-bottom:30px;">Payment Due: <span style="color: black;">$<?php echo $total_due_today;?></span></div>
				<div class="zelle_table">
					<div class="zelle_table_inner">
               	<?php if(empty($msg) == false){ ?>
                  <div align="center"><?php echo $msg;?></div>
                  <?php } ?>
                  <?php 
							if($_GET['success'] == '1')
							{
						?>
						<div align="center"><?php echo $f->getHtmlMessageSmall('Record has been successfully added');?></div>
             		<?php } ?>
                  <?php 
								if($_GET['del'] == '1')
								{
						  ?>
						 <div align="center"><?php echo $f->getHtmlErrorSmall('Record has been successfully deleted.');?></div>
						 <?php } ?>	
                  <?php 
							$sql_pay_info = "SELECT * FROM `tbl_reg_zelle_transaction` WHERE `reg_id`=".$_SESSION['reg_id_edit'];
							$res_pay_info = $db->get($sql_pay_info);
							$rec_pay_info = $db->num_rows($res_pay_info);
							//$row_pay = $db->fetch_array($res_pay);
						?>
						<div style="font-size:20px; font-weight: 600; padding-bottom:10px;">Transaction Details</div>
						<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td width="40%" align="left" valign="middle" style="font-weight: 600;">Zelle Referance No.</td>
								<td width="25%" align="left" valign="middle" style=" font-weight: 600;">Amount</td>
								<td width="25%" align="left" valign="middle" style=" font-weight: 600;">Date of Transaction</td>
								<td width="10%" align="center" valign="middle">&nbsp;</td>
							</tr>
                     <?php 
								if($rec_pay_info > 0)
								{
									while($row_pay_info = $db->fetch_array($res_pay_info))
									{
							?>
							<tr>
								<td align="left" valign="middle"><?php echo $f->getValue($row_pay_info['refno']);?></td>
								<td align="left" valign="middle">$<?php echo $f->getValue($row_pay_info['amount']);?></td>
								<td align="left" valign="middle"><?php echo date("m/d/Y", strtotime($f->getValue($row_pay_info['dated'])));?></td>
								<td align="center" valign="middle"><?php if($total_due_today > 0){?><div class="button"><a href="<?php echo WEBSITE_URL;?>/registration-zelle-payment-details/?action=Delete&transaction_id=<?php echo $row_pay_info['transaction_id'];?>" class="delrecord" title="Confirm Delete">Delete</a></div><?php }else{ echo '---';}?></td>
							</tr>
							<?php
									}
								}else{
							?>		
							<tr>
								<td align="center" valign="middle" colspan="4">No Record Found</td>
							</tr>
                     <?php
								}
							?>	
						</table>	
					</div>
					<div class="clear"></div>
				</div>
            <?php if($total_due_today > 0){?>
            <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-zelle-payment-details">
            	<input type="hidden" name="total_pay_price" value="<?php echo $total_price?>">
               <input type="hidden" name="already_paid_price" value="<?php echo $already_paid?>">
               <div class="register_area">
                  <div style="font-size:25px; color: #2F2D9B; font-weight: 600; padding-bottom:30px;">Submit Transaction</div>
                  <div class="row">
                     <div class="col-md-4"><p class="style1">Registration No. <span>*</span></p><input name="refno" type="text" value="<?php echo $_POST['refno']?>" placeholder="" class="input3" required /></div>
                     <div class="col-md-4"><p class="style1">Amount <span>*</span></p><input name="amount" type="text" value="<?php echo $_POST['amount']?>" placeholder="" class="input3 numeric" required /></div>
                     <div class="col-md-4"><p class="style1">Date of Transaction <span>*</span></p><input name="dated" type="date" value="<?php echo $_POST['dated']?>" placeholder="" class="input3" required /></div>		
                  </div>
                  <div class="clear"></div>
                  <div><input name="btnSubmit" type="submit" value="Submit" class="submit1" /></div>
               </div>
            </form>
            <?php
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
	$('.delrecord').click(function() {	
		var href = $(this).attr('href');
		$.jAlert({
			'type': 'confirm',
			'title':'Alert',
			'confirmQuestion':' Are you sure you want to Delete? ',
			'theme': 'dark_red',
			'closeBtn': false,
			'onConfirm': function(){				
				window.location.href = href;
			}
		});
		return false;
	});	
});
</script>
</body>
</html>