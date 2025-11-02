<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	//require_once('../includes/file.upload.inc.php');
	define("T","tbl_reg_home_page");
		
	if(isset($_POST['btnUpdate']) == TRUE)
	{
		$data = array(					
			'page_desc_1' => $f->setValue($_POST['page_desc_1']),
			'page_desc_2' => $f->setValue($_POST['page_desc_2']),
			'page_desc_3' => $f->setValue($_POST['page_desc_3'])
		);		
		
		$db->update(T, $data, "reg_home_page_id", 1);	
		$msg = $f->getHtmlMessage("Record has been successfully updated");	
	}
	
	$sql = "SELECT * FROM `".T."` WHERE `reg_home_page_id`=1";
	$res = $db->get($sql,__FILE__,__LINE__);
	$row = $db->fetch_array($res);
	
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Registration Page Content</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	<form action="<?php echo CP;?>" method="post" name="frm" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">				
				<div class="widget-content nopadding">
					<div class="widget-title">
						<h5>1st Page Content</h5>
					</div>
               <div class="control-group">
						<label class="control-label">Content:</label>
						<div class="controls"> 
							<textarea name="page_desc_1" id="page_desc_1" class="span11 required ckeditor"><?php echo $f->getValue($row['page_desc_1']);?></textarea>	
						</div>
					</div>	
               
               <div class="widget-title">
						<h5>3rd Page Notes</h5>
					</div>
               <div class="control-group">
						<label class="control-label">Notes:</label>
						<div class="controls"> 
							<textarea name="page_desc_2" id="page_desc_2" class="span11 required ckeditor"><?php echo $f->getValue($row['page_desc_2']);?></textarea>	
						</div>
					</div>	
               
               <div class="widget-title">
						<h5>4th Page Notes</h5>
					</div>
               <div class="control-group">
						<label class="control-label">Notes:</label>
						<div class="controls"> 
							<textarea name="page_desc_3" id="page_desc_3" class="span11 required ckeditor"><?php echo $f->getValue($row['page_desc_3']);?></textarea>	
						</div>
					</div>	
               
					<div class="control-group">						
						<div class="controls">							
							<input name="btnUpdate" id="btnUpdate" type="submit" value="Modify" class="btn btn-success" />
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
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 

</body>
</html>
