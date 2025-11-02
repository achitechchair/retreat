<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
		
	if(isset($_POST['btnUpdate']) == TRUE)
	{
		$settings = $_POST['settings'];
		$data = array();
		foreach($settings as $field => $value)
		{
			if($field == 'global_page_title' || $field == 'global_meta_desc')			
			{
				$data[$field] = $f->NoEnter($value);
			}
			else
			{
				$data[$field] = $f->setValue($value);
			}
		}		
		
		
		if(empty($_FILES['site_logo_img']['name'])==false)
		{
			usleep(500);
			$objFileUpload = new FileUpload();
			$objFileUpload->UploadMode = 'Edit';
			$objFileUpload->IsSaveByRandomName = false;
			$objFileUpload->OldFileName = $_POST['old_site_logo_img'];
			$objFileUpload->UploadContent = $_FILES['site_logo_img'];
			$objFileUpload->UploadFolder = '../'.SITE_LOGO_IMG."/";
			$image_return = $objFileUpload->Upload();
			$data['site_logo_img'] = $image_return['server_name'];
			unset($objFileUpload);
		} else {
			$data['site_logo_img'] = $_POST['old_site_logo_img'];				
		}	
		
			
		
		try
		{
			$db->start_transaction();
			$db->update('tbl_admin',$data,"admin_id",1);
			$db->commit();
			$msg = $f->getHtmlMessage("Settings has been successfully updated");
		}
		catch(Exception $e)
		{
			$db->rollback();
			$msg = $f->getHtmlError($e->getMessage());
		}		
	}
	
	$sql = "SELECT * FROM `tbl_admin` WHERE `admin_id`=1";
	$res = $db->get($sql,__FILE__,__LINE__);
	$row = $db->fetch_array($res);
	
	$site_logo_img = '../'.SITE_LOGO_IMG.'/'.$row['site_logo_img'];	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<script type="text/javascript">
$(document).ready(function() {
	$('#frmSettings').validate();
	$('.numeric').numeric();
	$('#smtp').change(function() {
		if($(this).val() == "Yes")
		{
			$('.smtp_tr').css('display', '');
			$("#smtp_hostname").rules("add", {
				required: true,
			});
			$("#smtp_username").rules("add", {
				required: true,
				email: true
			});
			$("#smtp_password").rules("add", {
				required: true,
				minlength: 6,
				messages: {
					minlength: "Minimum input 6 characters"
				}
			});
		}
		else
		{
			$('.smtp_control').rules('remove');
			//$('.smtp_control').val('');
			$('.smtp_tr').css('display', 'none');
		}	
	});
	
});
</script>
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Settings</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	<form action="<?php echo CP;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Personal Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Your Name:</label>
						<div class="controls">
							<input name="settings[your_name]" type="text" class="span11 required" id="your_name" value="<?php echo $f->getValue($row['your_name']);?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Your Email Address:</label>
						<div class="controls">
							<input name="settings[your_email_address]" type="text" class="span11 required email" id="your_email_address" value="<?php echo $f->getValue($row['your_email_address']);?>" />
							<div class="info-text" style="padding-top:5px;">(Your name and email address required to send you the contact email and forgot password email.)</div>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Your Email Address 2:</label>
						<div class="controls">
							<input name="settings[your_email_address_2]" type="text" class="span11 required email" id="your_email_address_2" value="<?php echo $f->getValue($row['your_email_address_2']);?>" />
							
						</div>
					</div>
               
				</div>
				<div class="widget-title">
					<h5>SMTP Email Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">From Email Name:</label>
						<div class="controls">
							<input name="settings[email_from_name]" type="text" class="span11 required" id="email_from_name" value="<?php echo $f->getValue($row['email_from_name']);?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">From Email Address:</label>
						<div class="controls">
							<input name="settings[email_from_address]" type="text" class="span11 required email" id="email_from_address" value="<?php echo $f->getValue($row['email_from_address']);?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">SMTP:</label>
						<div class="controls">
							<select name="settings[smtp]" id="smtp">
								<option value="Yes"<?php if($row['smtp']=='Yes') echo ' selected';?>>Yes</option>
								<option value="No"<?php if($row['smtp']=='No') echo ' selected';?>>No</option>
							</select>
						</div>
					</div>
					<div class="control-group smtp_tr"<?php if($row['smtp']=='No') echo ' style="display: none;"';?>>
						<label class="control-label">SMTP Hostname:</label>
						<div class="controls">
							<input name="settings[smtp_hostname]" type="text" class="span11 smtp_control" id="smtp_hostname" value="<?php echo $f->getValue($row['smtp_hostname']);?>" />
						</div>
					</div>
					<div class="control-group smtp_tr"<?php if($row['smtp']=='No') echo ' style="display: none;"';?>>
						<label class="control-label">SMTP Username:</label>
						<div class="controls">
							<input name="settings[smtp_username]" type="text" class="span11 smtp_control" id="smtp_username" value="<?php echo $f->getValue($row['smtp_username']);?>" />
						</div>
					</div>
					<div class="control-group smtp_tr"<?php if($row['smtp']=='No') echo ' style="display: none;"';?>>
						<label class="control-label">SMTP Password:</label>
						<div class="controls">
							<input name="settings[smtp_password]" type="text" class="span11 smtp_control" id="smtp_password" value="<?php echo $f->getValue($row['smtp_password']);?>" />
						</div>
					</div>
					<div class="control-group smtp_tr"<?php if($row['smtp']=='No') echo ' style="display: none;"';?>>
						<label class="control-label">SMTP Type:</label>
						<div class="controls">
							<select name="settings[smtp_type]" id="smtp_type" class="">
								<option value="normal"<?php if($row['smtp_type']=='normal') echo ' selected';?>>Normal</option>
								<option value="ssl"<?php if($row['smtp_type']=='ssl') echo ' selected';?>>SSL</option>
								<option value="tls"<?php if($row['smtp_type']=='tls') echo ' selected';?>>TLS</option>
							</select>
						</div>
					</div>
				</div>
				<div class="widget-title">
					<h5>Company Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Company Name:</label>
						<div class="controls">
							<input name="settings[company_name]" type="text" class="span11 required" id="company_name" value="<?php echo $f->getValue($row['company_name']);?>" />
						</div>
					</div>
					<!--<div class="control-group">
						<label class="control-label">Company Address:</label>
						<div class="controls">							
							<input name="settings[company_address]" type="text" class="span11 required" id="company_address" value="<?php echo $f->getValue($row['company_address']);?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Company Email:</label>
						<div class="controls">
							<input name="settings[company_email]" type="text" class="span11 required email" id="company_email" value="<?php echo $f->getValue($row['company_email']);?>" />
						</div>
					</div>	
               <div class="control-group">
						<label class="control-label">Company Phone:</label>
						<div class="controls">
							<input name="settings[company_phone]" type="text" class="span11 required" id="company_phone" value="<?php echo $f->getValue($row['company_phone']);?>" />
						</div>
					</div>					-->
				</div>
              
            
				<!--<div class="widget-title">
					<h5>Social Media Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Facebook:</label>
						<div class="controls">
							<input name="settings[facebook]" type="text" class="span11" id="facebook" value="<?php echo $f->getValue($row['facebook']);?>" size="45" />
						</div>
					</div>					 	
					<div class="control-group">
						<label class="control-label">Twitter:</label>
						<div class="controls">
							<input name="settings[twitter]" type="text" class="span11" id="twitter" value="<?php echo $f->getValue($row['twitter']);?>" size="45" />
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Instagram:</label>
						<div class="controls">
							<input name="settings[instagram]" type="text" class="span11" id="instagram" value="<?php echo $f->getValue($row['instagram']);?>" size="45" />
						</div>
					</div>					
				</div>-->
             <div class="widget-title">
                  <h5>Prices</h5>
             </div>
            <div class="widget-content nopadding">
               <div class="control-group">
                  <label class="control-label">Youth Activity Price ($):</label>
                  <div class="controls">
                     <input name="settings[youth_activity_price]" type="text" class="span11 numeric required" id="youth_activity_price" value="<?php echo $f->getValue($row['youth_activity_price']);?>"/>				
                  </div>
               </div>
             </div> 
              <div class="widget-content nopadding">
               <div class="control-group">
                  <label class="control-label">Banquet Dinner Price ($):</label>
                  <div class="controls">
                     <input name="settings[banquet_dinner_price]" type="text" class="span11 numeric required" id="banquet_dinner_price" value="<?php echo $f->getValue($row['banquet_dinner_price']);?>"/>				
                  </div>
               </div>
             </div>   
             
            <div class="widget-title">
                  <h5>Payment Settings</h5>
               </div>
            <div class="widget-content nopadding">
               <div class="control-group">
                  <label class="control-label">PayPal ID:</label>
                  <div class="controls">
                     <input name="settings[paypal_id]" type="text" class="span11 required" id="paypal_id" value="<?php echo $f->getValue($row['paypal_id']);?>" maxlength="100" />				
                  </div>
               </div>
             </div>          				
				
				<div class="widget-title">
					<h5>SEO Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Global Page Title:</label>
						<div class="controls">
							<textarea name="settings[global_page_title]" class="span11 required no_enter textarea_height_80" id="global_page_title"><?php echo $f->getValue($row['global_page_title']);?></textarea>
						</div>
					</div>
				</div>
				
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Global Meta Description:</label>
						<div class="controls">
							<textarea name="settings[global_meta_desc]" class="span11 required no_enter textarea_height_80" id="global_meta_desc"><?php echo $f->getValue($row['global_meta_desc']);?></textarea>
						</div>
					</div>
				</div>
				<div class="widget-title">
					<h5>Code Embed Settings</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">&lt;head&gt;&lt;/head&gt; Tag:</label>
						<div class="controls">
							<textarea name="settings[code_embed_head]" class="span11 no_enter textarea_height_120" id="code_embed_head"><?php echo $f->getValue($row['code_embed_head']);?></textarea>
						</div>
					</div>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">After &lt;body&gt; Tag:</label>
						<div class="controls">
							<textarea name="settings[code_embed_body]" class="span11 no_enter textarea_height_120" id="code_embed_body"><?php echo $f->getValue($row['code_embed_body']);?></textarea>
						</div>
					</div>
				</div>
				
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Site Logo:</label>
						<div class="controls"><img src="../phpThumb/phpThumb.php?src=<?php echo $site_logo_img;?>&w=200&h=200&sia=<?php echo time();?>" border="0" style="border:1px solid #999;" />
							<input type="hidden" name="old_site_logo_img" value="<?php echo $row['site_logo_img'];?>" />
							<input type="file" name="site_logo_img" id="site_logo_img" class="input2" />
							<p></p>
							<div class="info-text">Image size should be <b>366px X 345px</b></div>
						</div>
					</div>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<div class="controls">
							<input name="btnUpdate" id="btnUpdate" type="submit" value="Submit" class="btn btn-inverse" />
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
</body>
</html>
