<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_inner_banner");
	$index = $_GET['index'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />

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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Inner Banner</a></div>
	</div>
	<!--End-breadcrumbs-->
	<?php 
	if($index == 'List')
	{
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` ORDER BY `page_name` ASC";		
		$res = $db->get($sql, __FILE__, __LINE__);
		$records = $db->num_rows($res);
		
  ?>	
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Inner Banner (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">				
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>">
					<thead>
						<tr> 							
							<th width="65%"><div align="left">Page Name</div></th>
							<th width="25%">Image</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$inner_banner_id = $row['inner_banner_id'];	
									$banner_img = INNER_BANNER.'/'.$row['banner_img'];
									
						 ?>
						<tr>							
							<td><?php echo $f->getValue($row['page_name']);?></td>
							<td><div align="center"><a href="<?php echo MAIN_WEBSITE_URL;?>/<?php echo $banner_img;?>" class="lightbox_trigger" title=""><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $banner_img;?>&amp;w=200" border="0" /></a></div></td>
							<td>								
								<div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $inner_banner_id;?>" class="btn btn-primary btn-mini">Edit</a> <!--<a href="#myAlert<?php echo $row['inner_banner_id'];?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>--></div>
							</td>
						</tr>
						<!--<div id="myAlert<?php echo $row['inner_banner_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $inner_banner_id;?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>-->
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="3" class="NoRecord">No Record Found</td>
					</tr>
					<?php 	
						}						 
					?>
						</tbody>
					
				</table>
			</form>
		</div>
	</div>
	
	
	<?php 
	}else{
		
		$Id = $_GET['Id'];
					
		if(isset($_POST['btnEdit'])) {		
			
			if(empty($_FILES['banner_img']['name'])==false) {	
				require_once('../includes/file.upload.inc.php');
											
				$objFileUpload = new FileUpload();
				$objFileUpload->UploadMode = 'Edit';
				$objFileUpload->OldFileName = $_POST['old_banner_img'];
				$objFileUpload->UploadContent = $_FILES['banner_img'];
				$objFileUpload->UploadFolder = "../".INNER_BANNER;
				$image_return = $objFileUpload->Upload();
				$banner_img = $image_return['server_name'];				
			} else {
				$banner_img = $_POST['old_banner_img'];				
			}
			
			$data = array(
				'banner_img' => $banner_img,	
				'img_alt_tag' => $f->setValue($_POST['img_alt_tag']),			
				'slide_title_1' => $f->setValue($_POST['slide_title_1']),
				'slide_title_2' => $f->setValue($_POST['slide_title_2']),
				'slide_url' => $f->setValue($_POST['slide_url'])
			);
					
			$db->update(T,$data,"inner_banner_id",$Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");			
		}
		
		$sql = "SELECT * FROM `".T."` WHERE `inner_banner_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$banner_img = INNER_BANNER.'/'.$row['banner_img'];
	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Inner Banner</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $banner_img;?>&amp;w=500" border="0" />
							<input type="hidden" name="old_banner_img" value="<?php echo $row['banner_img'];?>" />							
							<input type="file" name="banner_img" id="banner_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>1920px X 554px</b></div>						
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Image ALT Tag:</label>
						<div class="controls">
							<input name="img_alt_tag" type="text" id="img_alt_tag" value="<?php echo $f->getValue($row['img_alt_tag']);?>" class="span11" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Title 1:</label>
						<div class="controls">
							<input type="text" name="slide_title_1" id="slide_title_1" class="span11" value="<?php echo $f->getValue($row['slide_title_1']);?>" />
						</div>
					</div>
					
					
					<div class="control-group">
						<label class="control-label">Title 2:</label>
						<div class="controls">
							<input type="text" name="slide_title_2" id="slide_title_2" class="span11" value="<?php echo $f->getValue($row['slide_title_2']);?>" />
						</div>
					</div>					
										
					<div class="control-group">
						<label class="control-label">URL:</label>
						<div class="controls">
							<input type="text" name="slide_url" id="slide_url" class="span11" value="<?php echo $f->getValue($row['slide_url']);?>" />
						</div>
					</div>
										
				</div>
				
				<div class="widget-content nopadding">
					<div class="control-group">						
						<div class="controls">
							<input name="btnEdit" id="btnEdit" type="submit" value="Modify Inner Banner" class="btn btn-success" />
							<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
						</div>
					</div>
				</div>	
				
			</div>
		</div>
	</form>
	<?php } ?>
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<script src="<?php echo WEBSITE_URL;?>/js/select2.min.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/matrix.tables.js"></script>
</body>
</html>
