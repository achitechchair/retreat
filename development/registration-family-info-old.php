<?php 
	require_once("includes/config.inc.php");	
	require_once('includes/file.upload.inc.php');
	define("TABLE", "tbl_registration");
	
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
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		if(empty($_FILES['family_photo']['name'])==FALSE)
		{
			$objFileUpload = new FileUpload();			
			$objFileUpload->UploadContent = $_FILES['family_photo'];
			$objFileUpload->UploadFolder = REG_DOCUMENT;
			$image_return = $objFileUpload->Upload();
			$family_photo = $image_return['server_name'];
			unset($objFileUpload);			
		}
		
		$data_array = array(
				"reg_id" => $reg_id, 
				"photo_in_retreat_book" => $f->setValue($_POST['f_photo']),
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
               <div class="row">
               	
                  <div class="col-md-12" style="padding-bottom: 20px;"><p class="style1">Upload family photo in the Sponsors section of the portal? <span>*</span></p>
                     <div><input name="f_photo" class="photo_y_n" type="radio" value="Yes" checked />  YES &nbsp;&nbsp;&nbsp; <input name="f_photo" type="radio" value="No" class="photo_y_n" />  NO</div>
                     <div class="clear"></div>
                  </div>
                  <div class="FPhoto">
                     <div class="col-md-12"><p class="style1">If Yes, Upload Image <span>*</span></p><input name="family_photo" id="family_photo" type="file" value="" class="input3 AllPhoto" /></div>
                     <div class="col-md-12"><p class="style1">Full Names of Members in Photo <span>*</span></p><textarea name="full_name" id="full_name" placeholder="" class="input4 AllPhoto"></textarea></div>
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
	$("#family_photo").attr("required", true);
	$("#full_name").attr("required", true);
			
	$(".photo_y_n").click(function() {
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
	});
});
</script>
</body>
</html>