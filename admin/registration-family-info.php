<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	define("T","tbl_reg_profile_info");
	
	if(empty($_GET['ref_reg_id']) == false)
	{
		$_SESSION['this_ref_reg'] = $_GET['ref_reg_id'];
	}	
	if(empty($_SESSION['this_ref_reg']) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registrations.php?index=List");
	
	$sql = "SELECT * FROM `tbl_reg_family_info` WHERE `reg_id`=".$_SESSION['this_ref_reg'];
	$res = $db->get($sql);	
	$row = $db->fetch_array($res);
	
	$reg_family_info_id = $row['reg_family_info_id'];
	$family_photos = REG_DOCUMENT."/".$row['family_photo'];
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		if(empty($_FILES['family_photo']['name'])==FALSE)
		{
			$sql_reg = "SELECT * FROM `tbl_registration` WHERE `reg_id`=".$_SESSION['this_ref_reg'];
			$res_reg = $db->get($sql_reg);			
			$row_reg = $db->fetch_array($res_reg);
			$ref_number = $f->getValue($row_reg['ref_number']);
			
			$objFileUpload = new FileUpload();	
			
			$objFileUpload->IsSaveByRandomName = false;
			$objFileUpload->NewFileName = $ref_number;	
				
			$objFileUpload->UploadMode = 'Edit';
			$objFileUpload->OldFileName = $_POST['old_family_photo'];	
			$objFileUpload->UploadContent = $_FILES['family_photo'];
			$objFileUpload->UploadFolder = "../".REG_DOCUMENT;
			$image_return = $objFileUpload->Upload();
			$family_photo = $image_return['server_name'];
			unset($objFileUpload);			
		}else{
			$family_photo = $f->POST_VAL('old_family_photo');
		}
		
		if($f->POST_VAL('f_photo') == 'No' && $f->POST_VAL('s_photo') == 'No')
		{
			$family_photo = '';
			$files = "../".REG_DOCUMENT.'/'.$f->POST_VAL('old_family_photo');
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
		$f->Redirect(CP."?index=List&msg=update");
			
	}
	
	if(empty($_GET['msg'])==false)
	{
		require_once('common-msg.php');											
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>

</head>
<body>
<!--Header-part-->
<div id="header">
	<?php include_once('header.php');?>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
	<?php include_once('header_menu.php');?>
</div>
<!--close-top-Header-menu--> 
<!--sidebar-menu-->
<div id="sidebar">
	<?php include_once('admin-left-menu.php');?>
</div>
<!--sidebar-menu--> 

<!--main-container-part-->
<div id="content"> 
	<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Member Family Photo Book</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
		<table class="table" cellpadding="0" cellspacing="0" border="0" style="padding-bottom:0px; margin:0px;">
			<tr>
				<td>					
					<a href="registrations.php?index=List" class="btn btn-inverse"> <i class="icon-step-backward"></i> Back to Registration</a>
				</td>
			</tr>
		</table>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
      <form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Family Photo Book</h5>
				</div>
				<div class="widget-content nopadding">	
            	            				
               <div class="control-group">
                 
                 <div class="controls">	
                     <p>Are you consenting to publishing your family photo in the Souvenir book?:</p>
                     <input class="span11 photo_y_n AllYN" name="f_photo" type="radio" value="Yes"<?php if($row['photo_in_retreat_book'] == 'Yes') echo 'checked';?> />  YES &nbsp;&nbsp;&nbsp; <input class="span11 photo_y_n AllYN" name="f_photo" type="radio" value="No" <?php if($row['photo_in_retreat_book'] == 'No') echo 'checked';?> />  NO
                  </div>
                </div>
                
                <div class="control-group">
                <div class="controls">	
                     <p>If you have chosen Platinum, Diamond or a Gold package, you have the option of getting your family photo published in the Sponsors section of the portal. Do you consent to having your photo published?:</p>
                     <input name="s_photo" class="span11 sponsors_y_n sponsors_y AllYN" type="radio" value="Yes"<?php if($row['photo_in_sponsors'] == 'Yes') echo 'checked';?> />  YES &nbsp;&nbsp;&nbsp; <input name="s_photo" class="span11 sponsors_y_n sponsors_y AllYN" type="radio" value="No"<?php if($row['photo_in_sponsors'] == 'No') echo 'checked';?> />  NO
                  </div>
                </div>
               	     
               <div class="FPhoto">            				
                  <div class="control-group">
                     <label class="control-label">Photo:</label>
                     <div class="controls">
                           <?php if($row['photo_in_retreat_book'] == 'Yes' && empty($row['family_photo']) == FALSE){ ?>
                           <img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $family_photos;?>?<?php echo rand(10000,99999)?>&w=140&h=140&zc=1" class="FPhoto" alt="" /><br /><br />
                           <?php } ?>
                           <input type="hidden" name="old_family_photo" value="<?php echo $f->getValue($row['family_photo']);?>">                           
                           <p></p>
                           <input name="family_photo" id="family_photo" type="file" value="" class="span11 AllPhoto" accept=".png, .jpg, .jpeg" />
                           <div class="info-text">Please upload jpg, jpeg, png files only</div>
                     </div>
                  </div>
                </div>  
                  <div class="control-group">
                     <!--<label class="control-label">Full Names of Members:</label>-->
                     
                     <div class="controls">	
                     		<p>If you have chosen Platinum, Diamond or Gold packages, please enter the Full Names of each member (comma separated) in your group to be published in the Souvenir book. For all other packages, please enter the Full Name of the primary member of the group and your entry in the Souvenir will be listed as 'First_Name Last_Name Family'</p>						
                           <textarea name="full_name" id="full_name" placeholder="" class="span11 textarea_height_200"><?php echo $f->getValue($row['full_name']);?></textarea>
                     </div>
                  </div>
               
             
               
               <div class="control-group">
						<!--<label class="control-label"></label>-->
						<div class="controls">	
                  		<p>Any special requirements that the retreat committee needs to know?:</p>						
								<textarea name="sp_requirement" placeholder="" class="span11 textarea_height_200"><?php echo $f->getValue($row['sp_requirement']);?></textarea>
                  </div>
					</div>
               
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnSubmit" id="btnSubmit" type="submit" value="Update" class="btn btn-success" />
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
</form>	
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<script type="text/javascript">
$(document).ready(function(){
	$("#full_name").attr("required", true);
	<?php 
		if($row['photo_in_retreat_book'] == 'No' && $row['photo_in_sponsors'] == 'No')
		{
	?>
		//$(".FPhoto").css("display", "none");
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
