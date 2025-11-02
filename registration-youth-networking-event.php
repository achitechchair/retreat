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
	
	// Skip Page code **************************************************************************************************************************************
		
	$total_price = ($row['total_price_pack']) + ($row['youth_activity_price']) + ($row['banquet_dinner_price']);
	
	$data_update = array(			
		"total_price" => $total_price 
	);
	$db->update(TABLE, $data_update, "reg_id", $_SESSION['reg_id']);
	
	$f->Redirect(MAIN_WEBSITE_URL."/registration-payment-bill");
		
	// End Skip page code **********************************************************************************************************************************
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		$sql_del = "DELETE FROM `tbl_reg_youth_networking_event` WHERE `reg_id`=".$reg_id;
		$db->get($sql_del);
		
		$price = 50;
		$mem = 0;
		if(empty($_POST['mem_name']) == FALSE)
		{						
			foreach($_POST['mem_name'] as $mem_name_val)
			{				
				$data_array = array(
					"reg_id" => $reg_id,
					"reg_members_in_your_party_id" => $mem_name_val,
					"price" => '50'
				);
				$db->insert("tbl_reg_youth_networking_event", $data_array);
				unset($data_array);
				$mem++;
			}
		}
		
		$youth_networking_event_total_people = $mem;
		$youth_networking_event_total_price = $mem * $price;
		$total_price = ($row['total_price_pack']) + ($row['youth_activity_price']) + ($row['banquet_dinner_price']) + ($youth_networking_event_total_price);
		
		$data_update = array(			
			"youth_networking_event_total_people" => ($youth_networking_event_total_people) ? ($youth_networking_event_total_people) : ('0'),
			"youth_networking_event_total_price" => ($youth_networking_event_total_price) ? ($youth_networking_event_total_price) : ('0'),
			"total_price" => $total_price 
		);
		$db->update(TABLE, $data_update, "reg_id", $_SESSION['reg_id']);
		
		$f->Redirect(MAIN_WEBSITE_URL."/registration-payment-bill");			
	}
	
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
	$('.fancybox-iframe').fancybox();
	
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
		<div class="inner_container" style="max-width: 700px;"> 
			<div style="font-size:25px;color:#2F2D9B; font-weight:600; text-transform:uppercase; padding-bottom:30px;">Small group youth networking event <span style="font-size:18px; text-transform:none;">($50 per person)</span> WAITING LIST.</div>
         
         <form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/registration-youth-networking-event">
            <div class="register_area">
               <p>Hi, Any Member in your party interested in the Small Group Youth Networking Event (Age 22+ Single) Sunday July 7th 9 am - 1 pm (<a href=" https://www.canva.com/design/DAF53WqxPr4/bWfskuLjhwVCynmkAceN3g/view?utm_content=DAF53WqxPr4&utm_campaign=designshare&utm_medium=link&utm_source=editor" target="_blank">Click Here</a> for More Info). We have reached the event capacity. Join the limited waitlist. We'll notify you of your status, in case of cancellations. If not selected, $50 will be refunded after the retreat. </p>
               <?php 
                  $sql_mem = "SELECT * FROM `tbl_reg_members_in_your_party` WHERE `reg_id`=".$reg_id." AND `age`>=18";
                  $res_mem = $db->get($sql_mem);
                  while($row_mem = $db->fetch_array($res_mem))
                  {
               ?>
               <div class="input_radio"><input name="mem_name[]" id="" type="checkbox" value="<?php echo $row_mem['reg_members_in_your_party_id'];?>" class="all_package" />  <?php echo $f->getValue($row_mem['first_name']." ".$row_mem['last_name']);?></div>
               <?php 
                  }
               ?>					
               <br>
               <div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-driving'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" id="" type="submit" value="Next" class="submit" /></div>
           		
            </div>
         </form>
		</div>
     
	<div class="clear"></div>
   </div> 
</section> 


<div class="clear"></div>
<footer>
     <?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>

</body>
</html>