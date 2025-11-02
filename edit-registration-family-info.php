<?php 
	require_once("includes/config.inc.php");	
	require_once('includes/file.upload.inc.php');
	$f->redirectBase = MAIN_WEBSITE_URL;
	$f->isLogin('reg_id_edit', MAIN_WEBSITE_URL.'/login-to-edit-registration','frontend');	
	
	if($_SESSION['reg_status']!='Active') $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$sql = "SELECT * FROM `tbl_reg_family_info` WHERE `reg_id`=".$_SESSION['reg_id_edit']." AND `mark_for_deleted`='No' AND `status`='Active'";
	$res = $db->get($sql);	
	$row = $db->fetch_array($res);
	
	$reg_family_info_id = $row['reg_family_info_id'];
	$family_photos = REG_DOCUMENT."/".$row['family_photo'];
	
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		if(empty($_FILES['family_photo']['name'])==FALSE)
		{
			$sql_reg = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['reg_id_edit'];
			$res_reg = $db->get($sql_reg);			
			$row_reg = $db->fetch_array($res_reg);
			$ref_number = $f->getValue($row_reg['ref_number']);
			
			$objFileUpload = new FileUpload();	
			
			$objFileUpload->IsSaveByRandomName = false;
			$objFileUpload->NewFileName = $ref_number;	
				
			$objFileUpload->UploadMode = 'Edit';
			$objFileUpload->OldFileName = $_POST['old_family_photo'];	
			$objFileUpload->UploadContent = $_FILES['family_photo'];
			$objFileUpload->UploadFolder = REG_DOCUMENT;
			$image_return = $objFileUpload->Upload();
			$family_photo = $image_return['server_name'];
			unset($objFileUpload);			
		}else{
			$family_photo = $f->POST_VAL('old_family_photo');
		}
		
		if($f->POST_VAL('f_photo') == 'No' && $f->POST_VAL('s_photo') == 'No')
		{
			$family_photo = '';
			$files = REG_DOCUMENT.'/'.$f->POST_VAL('old_family_photo');
			$f->DeleteFile($files);	
		}
		
		$data_array = array(				 
				"photo_in_retreat_book" => $f->setValue($_POST['f_photo']),
				"photo_in_sponsors" => $f->setValue($_POST['s_photo']),
				"family_photo" => ($family_photo) ? ($f->setValue($family_photo)) : ('NULL'),
				"full_name" => ($_POST['full_name']) ? ($f->setValue($_POST['full_name'])) : ('NULL'),
				"sp_requirement" => ($_POST['sp_requirement']) ? ($f->setValue($_POST['sp_requirement'])) : ('NULL')
			); 
		
		$db->update("tbl_reg_family_info", $data_array, "reg_family_info_id", $reg_family_info_id);
		
		// Sending email for update ===========================================================================================================
			
			$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name`, c.`ref_number` 
							FROM `tbl_reg_profile_info` AS a, `tbl_reg_members_in_your_party` AS b, `tbl_registration` AS c
				  			WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`=".$_SESSION['reg_id_edit']." AND c.`reg_id`=a.`reg_id`
							GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
			$res_cus = $db->get($sql_cus);
			$row_cus = $db->fetch_array($res_cus);
			$email_id = $f->getValue($row_cus['email_id']);
			$first_name = $f->getValue($row_cus['first_name']);
			$last_name = $f->getValue($row_cus['last_name']);
			$ref_number = $f->getValue($row_cus['ref_number']);
		
			$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
			$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
			$email_message.= "Your NSNA retreat registration has been successfully updated.<br /><br />";			
			//$email_message.= "<List the information that has changed><br /><br />";
			$email_message.= "Please contact us at miretreat.registration@achi.org if you have any questions.<br /><br />";			
			$email_message.= "Best regards,<br />NSNA Michigan Retreat Committee";
			$email_message.= "</font>";
			
			$objMail = new PHPMailer();
			$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
			$objMail->Subject = "Your Retreat Registration has been updated - ".$ref_number;
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
				
			// End Sending email for update ========================================================================================================
			
		$f->Redirect(MAIN_WEBSITE_URL."/edit-registration-family-info/?success=1");
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
<img src="<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Modify Member Family Photo Book</div>
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
				<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:30px;">Family Photo Book</div>			
				 <?php if(empty($msg) == false){ ?>
               <div align="center" class="display_message"><?php echo $msg;?></div>
               <p></p>
            <?php } ?>
             <?php 
               if(empty($_GET['success']) == FALSE && $_GET['success'] == '1')
               {
            ?>
             <div align="center"><?php echo $f->getHtmlMessage('The form has been successfully updated.');?></div>
             <?php } ?>
              <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/edit-registration-family-info" enctype="multipart/form-data">
               <div class="register_area">
               	<p style="font-weight:600;"><span>&#10803;</span>&nbsp;It is recommended that you have your photo ready during registration if you want your family photo to be included in the retreat souvenir book (.jpeg, .png). If you do not have it, you can still submit your registration and return to the portal to edit your registration and provide your photo at a later time. Please make sure to submit your photo by 5-24-2024.</p>
               	<hr>
                  <div class="row">                 
                     
                     <div class="col-md-12" style="padding-bottom: 20px;"><p class="style1">Are you consenting to publishing your family photo in the Souvenir book? <span>*</span></p>
                       <div><input name="f_photo" class="photo_y_n photo_y AllYN" type="radio" value="Yes"<?php if($row['photo_in_retreat_book'] == 'Yes') echo 'checked';?> required />  YES &nbsp;&nbsp;&nbsp; <input name="f_photo" type="radio" value="No" class="photo_y_n AllYN" <?php if($row['photo_in_retreat_book'] == 'No') echo 'checked';?> required />  NO</div>
                        <div class="clear"></div>
                     </div>
                     
                     <div class="col-md-12" style="padding-bottom: 20px;"><p class="style1">If you have chosen Platinum, Diamond or a Gold package, you have the option of getting your family photo published in the Sponsors section of the portal. Do you consent to having your photo published? <span>*</span></p>
                        <div><input name="s_photo" class="sponsors_y_n sponsors_y AllYN" type="radio" value="Yes"<?php if($row['photo_in_sponsors'] == 'Yes') echo 'checked';?> required />  YES &nbsp;&nbsp;&nbsp; <input name="s_photo" type="radio" value="No" class="sponsors_y_n AllYN"<?php if($row['photo_in_sponsors'] == 'No') echo 'checked';?> required />  NO</div>
                        <div class="clear"></div>
                     </div>
                     
                     <div class="col-md-12" style="padding-bottom: 20px;">
                     	<?php if($row['photo_in_retreat_book'] == 'Yes' && empty($row['family_photo']) == FALSE){ ?>
                        <img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $family_photos;?>?<?php echo rand(10000,99999)?>&w=140&h=140&zc=1" alt="" class="FPhoto" />
                        <?php } ?>
                        <input type="hidden" name="old_family_photo" value="<?php echo $f->getValue($row['family_photo']);?>">                        
                        <div class="clear"></div>
                     </div>
                     
                     <div class="">
                        <div class="col-md-12 FPhoto"><p class="style1">If you answered Yes to any of the above questions, upload your image below. <!--<span>*</span>--></p><input name="family_photo" id="family_photo" type="file" value="" class="input3 AllPhoto" accept=".png, .jpg, .jpeg" style="margin-bottom:0px;" /><div class="style_info">Please upload jpg, jpeg, png files only</div></div>
                        <div class="col-md-12"><p class="style1">If you have chosen Platinum, Diamond or Gold packages, please enter the Full Names of each member (comma separated) in your group to be published in the Souvenir book. For all other packages, please enter the Full Name of the primary member of the group and your entry in the Souvenir will be listed as 'First_Name Last_Name Family' <!--<span>*</span>--></p><textarea name="full_name" id="full_name" placeholder="" class="input4"><?php echo $f->getValue($row['full_name']);?></textarea></div>
                     </div>
                     <br>
                     
                     <br>
                     <div class="col-md-12"><p class="style1">Any special requirements that the retreat committee needs to know?</p><textarea name="sp_requirement" placeholder="" class="input4"><?php echo $f->getValue($row['sp_requirement']);?></textarea></div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                  <input name="btnSubmit" type="submit" value="Submit" class="submit1" />
               </div>
            </form>
				
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
$(document).ready(function(){
	//$("#full_name").attr("required", true);
	<?php 
		if($row['photo_in_retreat_book'] == 'No' && $row['photo_in_sponsors'] == 'No')
		{
	?>
		$(".FPhoto").css("display", "none");
		//$("#full_name").attr("required", false);
	
	<?php
		}else{
	?>	
		$(".FPhoto").css("display", "");
		//$("#full_name").attr("required", true);
	<?php 
		}
	?>
		
	$(".AllYN").click(function() {
		var f_photo = $("input[name='f_photo']:checked").val();
		var s_photo = $("input[name='s_photo']:checked").val();
		
		if(f_photo == 'No' && s_photo == 'No')
		{
			//$("#family_photo").attr('required',false);
			//$("#full_name").attr("required", false);
			$(".FPhoto").css("display", "none");
			$(".AllPhoto").val('');
						
		}else{
			
			//$("#family_photo").attr("required", true);
			//$("#full_name").attr("required", true);
			$(".FPhoto").css("display", "");
		}
	});
	
});
</script>
</body>
</html>