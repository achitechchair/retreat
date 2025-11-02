<?php
	require_once("../includes/config.inc.php");
	
	if(empty($_SESSION['_admin']) == false)
	{
		$f->Redirect('dashboard.php');
	}
	
	$msg = '';
	define("T","`tbl_admin`");
	define("T2","`tbl_admin_user`");
	
	if(!empty($_GET['action']) && $_GET['action']=="1")
	{
		$msg = $f->getHtmlMessage('You have been successfully logged out');
	}
	
	if(isset($_POST['btnLogin']) == TRUE && $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$username = $f->setValue($_POST['username']);
		$password = $f->setValue($_POST['password']);
				
		
		if($username == "admin")
		{
			$sql = "SELECT * FROM ".T." WHERE `username`='".$username."' AND `password`='".$f->makePassword($password)."'";
		}
		else
		{
			$sql = "SELECT * FROM ".T2." WHERE `email`='".$username."' AND `password`='".$f->EncryptDecrypt($password)."' AND `status`='Active' AND `mark_for_deleted`='No'";
		}	
		
		$res = $db->get($sql,__FILE__,__LINE__);
		
		if($db->num_rows($res) > 0):
			$row = $db->fetch_array($res);
			$_SESSION['_admin'] = $username;
			if($username=="admin")
			{
				$_SESSION['_admin_id'] = $row['admin_id'];
				$_SESSION['_admin_user_type'] = "ADMIN";
			}
			else
			{
				$_SESSION['_admin_id'] = $row['admin_user_id'];
				$_SESSION['_admin_fname'] = $f->getValue($row['fname'])." ".$f->getValue($row['lname']);				
				$_SESSION['_admin_user_type'] = $f->getValue($row['user_type']);
			}		
			
			if(empty($_POST['redirect'])==false) {
				$gotoURL = base64_decode($_POST['redirect']);
			} else {
				$gotoURL = "dashboard.php";
			}
			$f->Redirect($gotoURL);
		else:
			$msg = $f->getHtmlError('Invalid User ID / Email or bad Password');
		endif;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>

</head>
<body>
<div id="loginbox">
	<form action="<?php echo CP;?>" method="post" name="frmLogin" id="basic_validate" novalidate>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="control-group normal_text">
			<h4 style="margin-bottom:25px;">Welcome to <?php echo HEADER;?></h4>
			<!--<h3><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $WEBSITE_LOGO;?>&w=200&h=100" border="0" style="border:1px solid #999;" alt="" /></h3>-->
			
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="main_input_box"> <span class="add-on bg_lg"><i class="icon-user"> </i></span>
					<input name="username" type="text" id="username" value="<?php echo $f->POST_VAL('username');?>" class="required" placeholder="User Name" />
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="main_input_box"> <span class="add-on bg_ly"><i class="icon-lock"></i></span>
					<input name="password" type="password" id="password" size="35" class="required" placeholder="Password" />
				</div>
			</div>
		</div>
		<div class="form-actions"> <span class="pull-left"><a href="forgot_password.php" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span> 
		<span class="pull-right">
			<!--<a type="submit" class="btn btn-success" /> Login</a>-->
			<input name="redirect" type="hidden" id="redirect" value="<?php echo $return = (empty($_GET['redirectTo'])==false) ? $_GET['redirectTo'] : $f->POST_VAL('redirect');?>" />
			<input name="btnLogin" id="btnLogin" type="submit" value="Submit" class="btn btn-success" />
		</span> </div>
	</form>	
</div>

<?php include('admin-footer.php');?>
</body>
</html>
