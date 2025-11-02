<?php 
	require_once("includes/config.inc.php");	
	require_once('includes/file.upload.inc.php');
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
	$ref_number = $f->getValue($row['ref_number']);
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		$sql_del = "DELETE FROM `tbl_reg_family_info` WHERE `reg_id`=".$reg_id;
		$db->get($sql_del);
		
		if(empty($_FILES['family_photo']['name'])==FALSE)
		{
			$objFileUpload = new FileUpload();
			
			$objFileUpload->IsSaveByRandomName = false;
			$objFileUpload->NewFileName = $ref_number;			
			
			$objFileUpload->UploadContent = $_FILES['family_photo'];
			$objFileUpload->UploadFolder = REG_DOCUMENT;
			$image_return = $objFileUpload->Upload();
			$family_photo = $image_return['server_name'];
			unset($objFileUpload);			
		}
		
		$data_array = array(
				"reg_id" => $reg_id, 
				"photo_in_retreat_book" => $f->setValue($_POST['f_photo']),
				"photo_in_sponsors" => $f->setValue($_POST['s_photo']),
				"family_photo" => ($family_photo) ? ($f->setValue($family_photo)) : ('NULL'),
				"full_name" => ($_POST['full_name']) ? ($f->setValue($_POST['full_name'])) : ('NULL'),
				"sp_requirement" => ($_POST['sp_requirement']) ? ($f->setValue($_POST['sp_requirement'])) : ('NULL')
			); 
		
		$db->insert("tbl_reg_family_info", $data_array);	
		$f->Redirect(MAIN_WEBSITE_URL."/registration-driving");
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
		<div class="inner_container" style="max-width:100%;">
			<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:30px;">Family Photo Book</div>
			<form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-family-info" enctype="multipart/form-data">
            <div class="register_area">
            	<p style="font-weight:600;"><span>&#10803;</span>&nbsp;It is recommended that you have your photo ready during registration if you want your family photo to be included in the retreat souvenir book (.jpeg, .png). If you do not have it, you can still submit your registration and return to the portal to edit your registration and provide your photo at a later time. Please make sure to submit your photo by 5-24-2024.</p>
               <hr>
               <div class="row">
               	
                  <div class="col-md-12" style="padding-bottom: 20px;"><p class="style1">Are you consenting to publishing your family photo in the Souvenir book?  <span>*</span></p>
                     <div><input name="f_photo" class="photo_y_n photo_y AllYN" type="radio" value="Yes" required />  YES &nbsp;&nbsp;&nbsp; <input name="f_photo" type="radio" value="No" class="photo_y_n AllYN" required />  NO</div>
                     <div class="clear"></div>
                  </div>
                  
                  <div class="col-md-12" style="padding-bottom: 20px;"><p class="style1">If you have chosen Platinum, Diamond or a Gold package, you have the option of getting your family photo published in the Sponsors section of the portal. Do you consent to having your photo published? <span>*</span></p>
                     <div><input name="s_photo" class="sponsors_y_n sponsors_y AllYN" type="radio" value="Yes" required />  YES &nbsp;&nbsp;&nbsp; <input name="s_photo" type="radio" value="No" class="sponsors_y_n AllYN" required />  NO</div>
                     <div class="clear"></div>
                  </div>
                  <div class="">
                     <div class="col-md-12 FPhoto"><p class="style1">If you answered Yes to any of the above questions, upload your image below. If you are not able to submit your photo today, please remember to return to the portal and submit it by 5-24-2024. <!--<span>*</span>--></p><input name="family_photo" id="family_photo" type="file" value="" class="input3 AllPhoto" accept=".png, .jpg, .jpeg" style="margin-bottom:0px;" /><div class="style_info">Please upload jpg, jpeg, png files only</div></div>
                     <div class="col-md-12"><p class="style1">If you have chosen Platinum, Diamond or Gold packages, please enter the Full Names of each member (comma separated) in your group to be published in the Souvenir book. For all other packages, please enter the Full Name of the primary member of the group and your entry in the Souvenir will be listed as 'First_Name Last_Name Family' <!--<span>*</span>--></p><textarea name="full_name" id="full_name" placeholder="" class="input4"></textarea></div>
                  </div>
                  <br>                  
                  <br>
                  <div class="col-md-12"><p class="style1">Any special requirements that the retreat committee needs to know?</p><textarea name="sp_requirement" placeholder="" class="input4"></textarea></div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>
               <input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-member-info'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="Next" class="submit" />
            </div>
         </form>
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
	//$("#family_photo").attr("required", true);
	//$("#full_name").attr("required", true);
	
	//$(".photo_y").prop('checked', true);
	//$(".sponsors_y").prop('checked', true);
		
	/*$(".photo_y_n").click(function() {
		var y_n_val = $(this).val();
		if(y_n_val == 'Yes')
		{
			$("#family_photo").attr("required", true);
			$("#full_name").attr("required", true);
			$(".FPhoto").css("display", "");
		}else{
			$("#family_photo").attr('required',false);
			$("#full_name").attr("required", false);
			$(".FPhoto").css("display", "none");
			$(".AllPhoto").val('');
		}
	});*/
	
	
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