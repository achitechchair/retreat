<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	
	define("T","tbl_category");
	$index = $_GET['index'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />
<script type="text/javascript">
<?php if($index == "List"):?>
$(document).ready(function() {	
	$("#btnSeq").click(function() {
	   window.document.frmSQ.submit(); 
	});
	$(".numeric").numeric(false); 
});
<?php endif; ?>
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Category</a></div>
	</div>
	<!--End-breadcrumbs-->
	<?php 
	if($index == 'List')
	{
		
		if(isset($_POST['btnseq']))
		{
			$sqlQ = "SELECT * FROM ".T;
			$resQ = $db->get($sqlQ);
			while($rowQ = $db->fetch_array($resQ))
			{
				$v = $rowQ['cat_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `cat_id`='".$rowQ['cat_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `cat_id`='".$rowQ['cat_id']."'";
					$RESss = $db->get($SQLss);
			
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `cat_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			
			$file_1 = '../'.CAT_IMG.'/'.$_GET['file'];
			$f->DeleteFile($file_1);
			
			$file_2 = '../'.CAT_IMG.'/'.$_GET['file2'];
			$f->DeleteFile($file_2);			
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";		
		$res = $db->get($sql);
		$records = $db->num_rows($res);
		
  ?>
	<table class="table" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<a href="<?php echo CP.'?index=Add';?>" class="btn btn-inverse"> <i class="icon-plus"></i> Add New </a>
				 <?php 
					if($records > 0) 
					{
				 ?>
			   <input type="button" class="btn btn-inverse" name="btnSeq" id="btnSeq" value="Change Seq. Order" />
			   <?php } ?>
			</td>
		</tr>
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Categorys (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 
                  	<th width="2%">Seq.</th>
                     <th width="5%">Image</th>
                     <th width="72%"><div align="left">Category Name</div></th>
                     <th width="6%">Top Menu</th>
							<th width="6%">Status</th>
							<th width="9%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$cat_id = $row['cat_id'];
									
									$cat_main_img = '../'.CAT_IMG.'/'.$row['cat_main_img'];									
									if(!file_exists($cat_main_img) || empty($row['cat_main_img']) == true)
									{
										$cat_main_img = "../images/no_image.png";
									}
									
						 ?>
						<tr>
                  	<td><input name="seq<?php echo $cat_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" <?php if($cat_id == 8 || $cat_id == 9) echo 'readonly';?> /></td>
							<td><div align="center"><a href="<?php echo $cat_main_img;?>" class="lightbox_trigger" title=""><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo $cat_main_img;?>&w=75&h=75&zc=1" border="0" /></a></div></td>
                     <td><?php echo ($f->getValue($row['cat_title'])) ? ($f->getValue($row['cat_title'])) : ('-');?></td>   
                     <td><div align="center"><?php echo ($f->getValue($row['display_top_menu'])) ? ($f->getValue($row['display_top_menu'])) : ('-');?></div></td>                
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
                     <td><div align="center"><?php if($cat_id != 8 && $cat_id != 9){?><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $cat_id;?>" class="btn btn-primary btn-mini">Edit</a> <a href="#myAlert<?php echo $cat_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a><?php }else{ echo '---';} ?></div></td>
						</tr>
						<div id="myAlert<?php echo $row['cat_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $cat_id;?>&file=<?php echo $row['cat_main_img'];?>&file2=<?php echo $row['cat_logo_img'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="6" class="NoRecord">No Record Found</td>
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
	}
	elseif($index == 'Add')
	{
		if(isset($_POST['btnCreate'])) 
		{
			$cat_title = $f->setValue($_POST['cat_title']);
			$sql = "SELECT * FROM `".T."` WHERE `cat_title`='".$cat_title."' AND `mark_for_deleted`='No'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($cat_title).'" already exist !!!');
			} else {
				
				$data = array(	
					'cat_title' => $cat_title,
					'cat_slug' => $f->slugify($_POST["cat_title"]), 
					'display_top_menu' => $f->setValue($_POST['display_top_menu']), 
					/*'cat_desc' => $f->setValue($_POST['cat_desc']),*/
					'status' => $f->setValue($_POST['status']),
					'search_script' => ($_POST['search_script']) ? ($f->setValue($_POST['search_script'])) : ('NULL')
				);
				
				if(empty($_FILES['cat_main_img']['name'])==false) 
				{		
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadContent = $_FILES['cat_main_img'];
					$objFileUpload->UploadFolder = "../".CAT_IMG;
					$image_return = $objFileUpload->Upload();
					$cat_main_img = $image_return['server_name'];	
					$data['cat_main_img'] = $cat_main_img;
				}
				
				if(empty($_FILES['cat_logo_img']['name'])==false)
				{	
					usleep(500);
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadContent = $_FILES['cat_logo_img'];
					$objFileUpload->UploadFolder = '../'.CAT_IMG;
					$image_return = $objFileUpload->Upload();					
					$cat_logo_img = $image_return['server_name'];						
					$data['cat_logo_img'] = $cat_logo_img;
				}			
				
				$db->insert(T,$data);			
				$f->Redirect(CP."?index=List&msg=success");
			}
		}
?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Add New Category</h5>
				</div>
				<div class="widget-content nopadding">	
            	<div class="control-group">
						<label class="control-label">Main Image:</label>
						<div class="controls">
							<input type="file" name="cat_main_img" id="cat_main_img" class="required" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>157px X 157px</b></div>-->
						</div>
					</div>	
               <div class="control-group">
						<label class="control-label">Logo Image:</label>
						<div class="controls">
							<input type="file" name="cat_logo_img" id="cat_logo_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>50px X 57px</b></div>
						</div>
					</div>	
                           				
					<div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">							
							<input type="text" name="cat_title" id="cat_title" class="span11 required" value="<?php echo $_POST['cat_title'];?>" />
						</div>
					</div>
               <!--<div class="control-group">
						<label class="control-label">Description:</label>
						<div class="controls">
							<textarea name="cat_desc" id="cat_desc" class="required span11 textarea_height_120"><?php echo $_POST['cat_desc'];?></textarea>
						</div>
					</div>-->
               <div class="control-group">
						<label class="control-label">Display Top Menu:</label>
						<div class="controls">
							<select name="display_top_menu" id="display_top_menu" class="span11 required">
								<option value="Yes"<?php if($_POST['display_top_menu'] == 'Yes') echo ' selected';?>>Yes</option>
								<option value="No"<?php if($_POST['display_top_menu'] == 'No') echo ' selected';?>>No</option>
							</select>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required">
								<option value="Active"<?php if($_POST['status'] == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($_POST['status'] == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>
               
                <div class="control-group">
						<label class="control-label">Script Text:</label>
						<div class="controls">
							<textarea name="search_script" class="span11 no_enter textarea_height_200" id="search_script"><?php echo $_POST['search_script'];?></textarea>
						</div>
					</div>
               
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Category" class="btn btn-success" />
								<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php 
	}else{
		
		$Id = $_GET['Id'];
					
		if(isset($_POST['btnEdit'])) 
		{	
			$cat_title = $f->setValue($_POST['cat_title']);
			$sql = "SELECT * FROM `".T."` WHERE `cat_title`='".$cat_title."' AND `mark_for_deleted`='No' AND `cat_id`<>".$Id;
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($cat_title).'" already exist !!!');
			} else {
				
				$data = array(	
					'cat_title' => $cat_title,
					'cat_slug' => $f->slugify($_POST["cat_title"]),
					'display_top_menu' => $f->setValue($_POST['display_top_menu']), 
					/*'cat_desc' => $f->setValue($_POST['cat_desc']),*/
					'status' => $f->setValue($_POST['status']),
					'search_script' => ($_POST['search_script']) ? ($f->setValue($_POST['search_script'])) : ('NULL')	
				);
				
				if(empty($_FILES['cat_main_img']['name'])==false) 
				{		
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadMode = 'Edit';
					$objFileUpload->OldFileName = $_POST['old_cat_main_img'];
					$objFileUpload->UploadContent = $_FILES['cat_main_img'];
					$objFileUpload->UploadFolder = "../".CAT_IMG;
					$image_return = $objFileUpload->Upload();
					$cat_main_img = $image_return['server_name'];	
					$data['cat_main_img'] = $cat_main_img;
				}
				
				if(empty($_FILES['cat_logo_img']['name'])==false)
				{	
					usleep(500);
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadMode = 'Edit';
					$objFileUpload->OldFileName = $_POST['old_cat_logo_img'];
					$objFileUpload->UploadContent = $_FILES['cat_logo_img'];
					$objFileUpload->UploadFolder = '../'.CAT_IMG;
					$image_return = $objFileUpload->Upload();					
					$cat_logo_img = $image_return['server_name'];						
					$data['cat_logo_img'] = $cat_logo_img;
				}	
								
				$db->update(T, $data, "cat_id", $Id);					
				$msg = $f->getHtmlMessage("Record has been successfully updated");
			}
		}	
		
		$sql = "SELECT * FROM `".T."` WHERE `cat_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$flag = 1;
		
		$cat_main_img = '../'.CAT_IMG.'/'.$row['cat_main_img'];									
		if(!file_exists($cat_main_img) || empty($row['cat_main_img']) == true)
		{
			$cat_main_img = "../images/no_image.png";
		}
		
		$cat_logo_img = '../'.CAT_IMG.'/'.$row['cat_logo_img'];									
		if(!file_exists($cat_logo_img) || empty($row['cat_logo_img']) == true)
		{
			$cat_logo_img = "../images/no_image.png";
			$flag = 0;
		}
								
	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Category</h5>
				</div>
				<div class="widget-content nopadding">	
            	
               <div class="control-group">
						<label class="control-label">Main Image:</label>
						<div class="controls">
                  	<img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo $cat_main_img;?>&w=100" border="0" />
							<input type="hidden" name="old_cat_main_img" value="<?php echo $row['cat_main_img'];?>" />	
							<input type="file" name="cat_main_img" id="cat_main_img" class="" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>157px X 157px</b></div>-->
						</div>
					</div>	
               <div class="control-group">
						<label class="control-label">Logo Image:</label>
						<div class="controls">
                  	<?php if($flag == 1){?>
                     <img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo $cat_logo_img;?>&w=100&f=png" border="0" />
                     <?php } ?>
                     <input type="hidden" name="old_cat_logo_img" value="<?php echo $row['cat_logo_img'];?>" />
							<input type="file" name="cat_logo_img" id="cat_logo_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>50px X 57px</b></div>
						</div>
					</div>	
               			
					<div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">							
							<input type="text" name="cat_title" id="cat_title" class="span11 required" value="<?php echo $f->getValue($row['cat_title']);?>" />
						</div>
					</div>
                <!--<div class="control-group">
						<label class="control-label">Description:</label>
						<div class="controls">
							<textarea name="cat_desc" id="cat_desc" class="required span11 textarea_height_120"><?php echo $f->getValue($row['cat_desc']);?></textarea>
						</div>
					</div>-->
               
                <div class="control-group">
						<label class="control-label">Display Top Menu:</label>
						<div class="controls">
							<select name="display_top_menu" id="display_top_menu" class="span11 required">
								<option value="Yes"<?php if($row['display_top_menu'] == 'Yes') echo ' selected';?>>Yes</option>
								<option value="No"<?php if($row['display_top_menu'] == 'No') echo ' selected';?>>No</option>
							</select>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required">
								<option value="Active"<?php if($row['status'] == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($row['status'] == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Script Text:</label>
						<div class="controls">
							<textarea name="search_script" class="span11 no_enter textarea_height_200" id="search_script"><?php echo $f->getValue($row['search_script']);?></textarea>
						</div>
					</div>
               
               
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Category" class="btn btn-success" />
								<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
							</div>
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
