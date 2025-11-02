<?php 
	require_once("includes/config.inc.php");
	
	if(empty($_POST['btnSubmit']) == false)
	{
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']) && empty($_POST['_ARcv0weh4043qfgpm1rx8j42kisb9atzo7Cbary']) == TRUE)
		{
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.CAPTCHA_PRIVATE_KEY.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if($responseData->success)
			{		
				$yname = $_POST['yname'];										
				$email = $_POST['email'];
				$tel = $_POST['tel'];										
				$msg = $_POST['msg'];				
				
				$message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
				$message.= "<b>Name: </b>".$yname."<br />";
				$message.= "<b>Email Address: </b>".$email."<br />";
				if(empty($tel) == FALSE)
				{
					$message.= "<b>Phone: </b>".$tel."<br />";		
				}				
				$message.= "<b>Message: </b>".nl2br($msg);
				$message.= "</font>";
				
				//echo $message; exit();
						
				$objMail = new PHPMailer();
				$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getHTMLDecode($AdminSettings['email_from_name']));
				$objMail->Subject = $f->getValue($AdminSettings['company_name'])." :: Contact Us";				
				if($AdminSettings['smtp']=='Yes'):
					$objMail->IsSMTP();
					$objMail->Host = $f->getValue($AdminSettings['smtp_hostname']);
					$objMail->SMTPAuth = true;
					if($AdminSettings['smtp_type'] == "tls" || $AdminSettings['smtp_type'] == "ssl")
					{
						$objMail->SMTPSecure = $AdminSettings['smtp_type'];
						$objMail->Port = ($AdminSettings['smtp_type'] == 'tls') ? 587 : 465;
						//$objMail->SMTPDebug = 1;
					}
					$objMail->Username = $f->getValue($AdminSettings['smtp_username']);
					$objMail->Password = $f->getValue($AdminSettings['smtp_password']);
				endif;
				$objMail->IsHTML(true);
				$objMail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
				$objMail->MsgHTML($message);
				$objMail->CharSet = 'UTF-8';
								
				$objMail->AddAddress("retreatchair@achi.org", "");			
				$objMail->AddAddress("retreatcochair@achi.org", "");		
				
				$objMail->Send();					
				$f->Redirect(MAIN_WEBSITE_URL."/contact-us/?success=1");
			
		}else{
			$msg = $f->getHtmlError("Invalid CAPTCHA code");
		}
	  }else{
		  $msg = $f->getHtmlError("Please click on the reCAPTCHA box.");
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
                <div class="flexcaption_style4">Contact Us</div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
<div class="flexcaption_darkshade"></div>    
</section>
<!------------- Slider area end----------------->

<div class="clear"></div>

<section class="inner_area contact_section">
	<div class="container">
			<div class="contact_area">
					<div class="contact_area_left">
						<div>
							<div class="contact_area_leftblock">
								<div class="contact_heading">Registered Address</div>
								<div class="contact_heading1">Nagarathar Sangam of North America</div>
								<div>
									<div class="contact_area_block"><i class="fa fa-map-marker" aria-hidden="true"></i> C/O 29 Periwinkle Drive	<br>Monmouth Junction, NJ, 08852</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="contact_area_leftblock">
								<div class="contact_heading">Mailing Address</div>
								<div class="contact_heading1">Nagarathar Sangam of North America</div>
								<div>
									<div class="contact_area_block"><i class="fa fa-map-marker" aria-hidden="true"></i> 4598 Appletree Ct,	<br>West Bloomfield, MI 48323</div>
									<!-- <div class="contact_area_block"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel: +1 (630) 385-0490"> +1 (630) 385-0490</a></div> -->
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="contact_imagearea"><img src="images/contact.png" alt=""></div>
					<div class="clear"></div>
				</div>
            	
				<div class="contact_area_right">
            	 <?php 
							if(empty($_GET['success']) == FALSE && $_GET['success'] == '1')
							{
					?>
					<div align="center"><?php echo $f->getHtmlMessage('Your message has been submited.');?></div>
					<?php } ?>
					<?php if(empty($msg) == false){ ?>
					<div align="center"><?php echo $msg;?></div>
					<?php } ?>
               <form name="frm" id="frm" method="post" action="<?php echo MAIN_WEBSITE_URL;?>/contact-us" enctype="multipart/form-data">
               <input type="text" name="_ARcv0weh4043qfgpm1rx8j42kisb9atzo7Cbary" class="DispNone">
                  <div><p class="style1">Name <span>*</span></p><input name="yname" id="yname" type="text" placeholder="" value="<?php echo $f->POST_VAL('yname');?>" class="input3" required /></div>
                  <div><p class="style1">Email <span>*</span></p><input name="email" id="" type="email" placeholder="" value="<?php echo $f->POST_VAL('email');?>" class="input3 email" required /></div>
                  <div><p class="style1">Phone <span>*</span></p><input name="tel" id="" type="tel" placeholder="" value="<?php echo $f->POST_VAL('tel');?>" class="input3 email" required /></div>
                  <div><p class="style1">Message <span>*</span></p><textarea name="msg" cols="" rows="" placeholder="" class="input4" required><?php echo $f->POST_VAL('msg');?></textarea></div>
                  <div class="recaptcha">
                        <div class="g-recaptcha res_captcha" id="grecaptcha"></div>								
                  </div>
                  <div><input name="btnSubmit" type="submit" value="Submit" class="submit" /></div>
                  <div class="clear"></div>
               </form>
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
</body>
</html>