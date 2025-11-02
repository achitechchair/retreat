<?php 
	require_once("includes/config.inc.php");	
	
	//Registration closed redirect ------------------------------------------------
	$f->Redirect(MAIN_WEBSITE_URL."/registration-home");
	// ----------------------------------------------------------------------------
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");	
		
	if(empty($_POST['btnSubmit']) == FALSE)
	{	
	    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']) && empty($_POST['_ACALycv0weh3qfgpm1rx8j42kisb9atMMIzo7']) == TRUE)
		{
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.CAPTCHA_PRIVATE_KEY.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if($responseData->success)
			{
			    $ref_number = $f->setValue($_POST['ref_number']);	
				 $email_id = $f->setValue($_POST['email_id']);				
				   
			    $sql ="SELECT a.*, b.* FROM `tbl_registration` AS a, `tbl_reg_profile_info` AS b
				 	    WHERE a.`reg_id`=b.`reg_id` AND a.`ref_number`='".$ref_number."' AND b.`email_id`='".$email_id."'
						 AND a.`mark_for_deleted`='No' AND (a.`status`='Active' OR a.`status`='Cancel')";
			   
				 $res = $db->get($sql, __FILE__, __LINE__);
			    $record = $db->num_rows($res);
			  
			    if($record > 0)
			    {
					  $row = $db->fetch_array($res);
					  $reg_id = $f->getValue($row['reg_id']);
					  
					  $_SESSION['reg_id'] = "";
					  $_SESSION['reg_id_edit'] = $reg_id;						 
					  		    	
					  $gotoURL = MAIN_WEBSITE_URL."/registration-dashboard";	
					  
					  if(empty($_POST['redirect'])==false)
					  {
						 $gotoURL = base64_decode($_POST['redirect']);
					  }		 
					  $f->Redirect($gotoURL);	
					  
			    }else{
					  $msg = $f->getHtmlErrorSmall("Your credantial is invalid.");
			    }
			   }else{
			$msg = $f->getHtmlErrorSmall("Robot verification failed, please try again.");
		}
	  }else{
		  $msg = $f->getHtmlErrorSmall("Please click on the reCAPTCHA box.");
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
          <div class="inner_container" style="max-width: 600px;">
               <form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/login-to-edit-registration">
                    <input name="redirect" type="hidden" id="redirect" value="<?php echo $_REQUEST['redirect'] ?? '';?>" />
                    <input type="text" name="_ACALycv0weh3qfgpm1rx8j42kisb9atMMIzo7" class="DispNone">
                    <div class="register_area">
                         <div class="heading1">Modify Your Registration</div>
                         <?php if(empty($msg) == false){ ?>
                         <div align="center"><?php echo $msg;?></div>
                         <?php } ?>
                         <?php 
										if(empty($status_message) == FALSE)
										{
								?>
								<div align="center"><?php echo $f->getHtmlMessage($status_message);?></div>
								<?php 
										}
								?>
                         <div>
                              <p class="style1">Enter Your Registration Number</p>
                              <input name="ref_number" id="ref_number" value="<?php echo $f->POST_VAL('ref_number');?>" type="text" placeholder="" class="input1" required />
                         </div>
                          <div>
                              <p class="style1">Enter Your Email</p>
                              <input name="email_id" id="email_id" value="<?php echo $f->POST_VAL('email_id');?>" type="email" placeholder="" class="input1 email" required />
                         </div>
                         <div class="recaptcha">
                              <div class="g-recaptcha res_captcha" id="grecaptcha"></div>
                         </div>
                         <div>
                              <input name="btnSubmit" type="submit" value="Submit" class="submit" />
                         </div>
                         <div class="clear"></div>
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