<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	$msg = '&nbsp;';
	
	if(isset($_POST['btnChangePassword'])) 
	{		
		
		if($_SESSION['_admin_user_type']=="ADMIN")
		{
			$old_pwd = $f->makePassword($_POST['txtOldPassword']);
			$sql = "SELECT * FROM `tbl_admin` WHERE `username`='".$_SESSION['_admin']."' AND `password`='".$old_pwd."'";
		}
		else
		{
			$old_pwd = $f->EncryptDecrypt($_POST['txtOldPassword']);
			$sql = "SELECT * FROM `tbl_user` WHERE `user_id`='".$_SESSION['_admin_id']."' AND `password`='".$old_pwd."'";	
		}
		//echo $sql;
		$res = $db->get($sql,__FILE__,__LINE__);
		if($db->num_rows($res) > 0) {
			$db_table = ($_SESSION['_admin']=="admin") ? "tbl_admin" : "tbl_user";
			
			if($_SESSION['_admin_user_type']=="ADMIN")
			{
				$new_pwd = $f->makePassword($_POST['txtNewPassword']);
				$sql = "UPDATE `".$db_table."` SET `password`='".$new_pwd."' WHERE `username`='".$_SESSION['_admin']."'";
			}else{
				$new_pwd = $f->EncryptDecrypt($_POST['txtNewPassword']);
				$sql = "UPDATE `".$db_table."` SET `password`='".$new_pwd."' WHERE `user_id`='".$_SESSION['_admin_id']."'";
			}
			$db->get($sql,__FILE__,__LINE__);
			$msg = $f->getHtmlMessage('Password has been successfully changed');
		} else {
			$msg = $f->getHtmlError('Old Password does not matching');
		}
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Change Password</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	<form action="<?php echo CP;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Change Your Password</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Old Password:</label>
						<div class="controls">
							<input name="txtOldPassword" type="password" id="txtOldPassword" class="span11 required" />	
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">New Password:</label>
						<div class="controls">
							<input name="txtNewPassword" type="password" id="txtNewPassword" class="span11 required" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Confirm New Password:</label>
						<div class="controls">
							<input name="txtConfPassword" type="password" id="txtConfPassword" size="30" class="span11 required" />
						</div>						
					</div>
					
					<div class="control-group">						
						<div class="controls">							
							<input name="btnChangePassword" id="btnChangePassword" type="submit" value="Change Password" class="btn btn-inverse" />
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
$(document).ready(function() {
	$('#frmChangePassword').validate();
	
	$("#txtOldPassword").rules("add", {
		minlength: 6,
		messages: {
			minlength: ""
		}
	});
	$("#txtNewPassword").rules("add", {
		minlength: 6,
		messages: {
			minlength: ""
		}
	});
	$("#txtConfPassword").rules("add", {
		minlength: 6,
		equalTo: "#txtNewPassword",
		messages: {
			minlength: "",
			equalTo: ""
		}
	});
	
});
</script>
</body>
</html>
