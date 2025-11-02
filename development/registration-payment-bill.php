<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$reg_id = $_SESSION['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	
	
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<link href="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox.js?v=2.1.5"></script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
$(document).ready(function() {	
	$('.fancybox').fancybox();
});
</script>
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
		<div class="inner_container" style="max-width: 800px;"> 
			<div style="font-size:25px;color:#2F2D9B; font-weight:600; text-transform:uppercase; padding-bottom:30px;">Total Payment Information</div>
         
            <div class="register_area">
               <div class="package_tablearea">
               <table width="730" border="0" align="left" cellpadding="0" cellspacing="0" id="display_pack">
                		<tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                     <tr>
                        <td align="center" valign="middle">
                           <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" >
                           	<?php
											$sql_pack = "SELECT * FROM `tbl_registered_package` WHERE `reg_id`=".$reg_id;
											$res_pack = $db->get($sql_pack);
											while($row_pack = $db->fetch_array($res_pack))
											{
										?>
                              <tr id="YouthActivity">
                                 <td width="82%" align="left" valign="middle"><?php echo $f->getValue($row_pack['pack_name']);?></td>
                                 <td width="18%" align="center" valign="middle" id="">$<?php echo $f->getValue($row_pack['pack_price']);?></td>
                              </tr>
                              <?php 
											}
										?>	
                               <?php 
											if($row['youth_activity_price'] > 0)
											{
										?>
                             <tr id="YouthActivity">
                                 <td width="82%" align="left" valign="middle">Activities for <?php echo $f->getValue($row['no_of_people_youth_extra']);?> Youth</td>
                                 <td width="18%" align="center" valign="middle">$<?php echo round($row['youth_activity_price']);?></td>
                              </tr>
                              <?php 
											}
										?>	
                              <?php 
											if($row['banquet_dinner_price'] > 0)
											{
										?>
                              <tr id="BanquetDinner">
                                 <td width="82%" align="left" valign="middle"><?php echo $f->getValue($row['no_of_dinner_people_extra']);?> Banquet Dinner</td>
                                  <td width="18%" align="center" valign="middle">$<?php echo round($row['banquet_dinner_price']);?></td>
                              </tr>
                              <?php
											}
										?>	
                              <?php 
											if($row['youth_networking_event_total_price'] > 0)
											{
										?>
                              <tr>
                                 <td width="82%" align="left" valign="middle"><?php echo $row['youth_networking_event_total_people'];?> Youth Networking Event</td>                              
                                 <td width="18%" align="center" valign="middle">$<?php echo round($row['youth_networking_event_total_price']);?></td>
                              </tr>
                              <?php
											}
										?>	
                                                            
                              <tr>
                                 <td width="82%" align="left" valign="middle">Total Cost if paid by Zelle/Check</td>                              
                                 <td width="18%" align="center" valign="middle"><b>$<?php echo round($row['total_price']);?></b></td>
                              </tr>
                              
                              <?php 
											$total_price = $row['total_price'];
											$geteway_charge = $total_price * (3/100);
											$paypal_amount = $total_price + round($geteway_charge);
										?>
                              <tr>
                                 <td width="82%" align="left" valign="middle">Total Cost if paid by PayPal (Add 3%)</td>                                 
                                 <td width="18%" align="center" valign="middle"><b>$<?php echo round($paypal_amount);?></b></td>
                              </tr>                         
                                                  		
                           </table>
                        </td>
                    	 </tr>
                    
                      <tr>
                        <td align="center" valign="middle">&nbsp;</td>
                     </tr>
                 </table>
               <div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-youth-networking-event'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="button" value="Next" class="submit" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-payment-options'" /></div>
            </div>
        
		</div>
     
	<div class="clear"></div>
   </div> 
   </div>
</section> 


<div class="clear"></div>
<footer>
     <?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>

</body>
</html>