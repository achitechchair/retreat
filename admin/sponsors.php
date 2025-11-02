<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_sponsors");
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
function __doSetStatus(param) {
	var flag = false;
	var comments = window.document.frmSQ.elements.length;
	for(j=0;j<comments;j++) {
		if(window.document.frmSQ.elements[j].type=="checkbox") {
			if(window.document.frmSQ.elements[j].checked==true) {
				flag = true;
			}
		}
	}
	if(flag == false) {
		alert("Please select at least one checkbox in order to do active/inactive");		
	} else {		
		var location = '<?php echo CP.'?index=List&action=Bulk&status=';?>' + param;		
		document.frmSQ.action = location;
		document.frmSQ.method = 'post';
		document.frmSQ.submit();
	}
}
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Sponsors</a></div>
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
				$v = $rowQ['sponsors_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `sponsors_id`='".$rowQ['sponsors_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
				/*	$sqlS = "UPDATE ".T." SET `display_order`='".$row0['display_order']."' WHERE `display_order`='".$_POST["seq$v"]."'";
					$resS = $db->get($sqlS);*/
					
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `sponsors_id`='".$rowQ['sponsors_id']."'";
					$RESss = $db->get($SQLss);
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && ($_GET['action']=='Active' || $_GET['action']=='Inactive'))
		{
			$Id = $_GET['Id'];
			$sql = "UPDATE `".T."` SET `status`='".$_GET['action']."' WHERE `sponsors_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=status");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Bulk') 
		{			
			foreach($_POST['all_check_bx'] as $val):
				$sql = "UPDATE `".T."` SET `status`='".$_GET['status']."' WHERE `sponsors_id`=".$val;
				$db->get($sql,__FILE__,__LINE__);							
			endforeach;
			
			$f->Redirect(CP."?index=List&msg=status");
			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `sponsors_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			
			$files = '../'.SPONSOR_IMG.'/'.$_GET['file'];
			$f->DeleteFile($files);			
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT a.*, b.`cat_title` FROM `".T."` AS a, `tbl_sponsors_category` AS b
				 WHERE a.`cat_id`=b.`cat_id` AND a.`mark_for_deleted`='No' AND b.`mark_for_deleted`='No' ORDER BY b.`display_order` ASC, a.`display_order` ASC";		
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
			<h5>List of Sponsors (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr>							
							 <th width="2%">Seq.</th>
							 <th width="8%"><div align="left">Image</div></th>							
							 <th width="54%"><div align="left">Title</div></th>
							 <th width="22%"><div align="left">Category</div></th>
							 <th width="6%">Status</th> 
							 <th width="8%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$sponsors_id = $row['sponsors_id'];
											
									$sponsors_img = SPONSOR_IMG.'/'.$row['sponsors_img'];									
									if(!file_exists("../".$sponsors_img) || empty($row['sponsors_img']) == true)
									{
										$sponsors_img = "images/no_image.png";
									}
						 ?>
						<tr>							
							<td><div align="center"><input name="seq<?php echo $sponsors_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></div></td>
							<td><div align="left"><a href="<?php echo MAIN_WEBSITE_URL;?>/<?php echo $sponsors_img;?>" class="lightbox_trigger" title=""><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $sponsors_img;?>&w=75&h=50&zc=1" border="0" /></a></div></td>
							<td><?php echo $f->getValue($row['sponsors_title']);?></td>
							<td><?php echo $f->getValue($row['cat_title']);?></td>
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
							<td><div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $sponsors_id;?>" class="btn btn-primary btn-mini">Edit</a> <a href="#myAlert<?php echo $sponsors_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a></div></td>
						</tr>
					<div id="myAlert<?php echo $row['sponsors_id'];?>" class="modal hide">
						<div class="modal-header">
							<button data-dismiss="modal" class="close" type="button">×</button>
							<h3>Alert</h3>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to Delete? </p>
						</div>
						<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $sponsors_id;?>&file=<?php echo $row['sponsors_img'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
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
			if(empty($_FILES['sponsors_img']['name'])==false) 
			{		
				$objFileUpload = new FileUpload();
				$objFileUpload->UploadContent = $_FILES['sponsors_img'];
				$objFileUpload->UploadFolder = "../".SPONSOR_IMG;
				$image_return = $objFileUpload->Upload();
				$is_sponsors_img = $image_return['server_name'];	
			}else{
				$is_sponsors_img = "";
			}		
			
			$data = array(
				'cat_id' => $f->setValue($_POST['cat_id']),				
				'sponsors_title' => $f->setValue($_POST['sponsors_title']),
				'sponsors_link' => $f->setValue($_POST['sponsors_link']),
				'sponsors_desc' => $f->setValue($_POST['sponsors_desc']),
				'sponsors_img' => $is_sponsors_img,
				'status' => $f->setValue($_POST['status'])
			);
					
			$db->insert(T,$data);
			$f->Redirect(CP."?index=List&msg=success");
		}
?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frm" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Add New Sponsors</h5>
				</div>
				<div class="widget-content nopadding">
            	
               <div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls">
							<select name="cat_id" id="cat_id" class="span11 required">                     	
                        <?php 									
									$first_cat_id = $row['cat_id'];
									$sql_cat = "SELECT * FROM `tbl_sponsors_category` WHERE `mark_for_deleted`='No' ORDER BY `cat_title` ASC";
									$res_cat = $db->get($sql_cat);
									while($row_cat = $db->fetch_array($res_cat))
									{										
								?>
                        <option value="<?php echo $f->getValue($row_cat['cat_id']);?>"<?php if($row_cat['cat_id'] == $f->POST_VAL('cat_id')) echo ' selected';?>><?php echo $f->getValue($row_cat['cat_title']);?></option>
                        <?php 
										$count++; 
									} 
								?>
                     </select>
						</div>
					</div> 
               <div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">							
							<input type="text" name="sponsors_title" id="sponsors_title" class="span11 required" value="<?php echo $f->POST_VAL('sponsors_title');?>" />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Title URL:</label>
						<div class="controls">							
							<input type="text" name="sponsors_link" id="sponsors_link" class="span11 url" value="<?php echo $f->POST_VAL('sponsors_link');?>" />
						</div>
					</div>
                <div class="control-group">
						<label class="control-label">Description:</label>
						<div class="controls">							
							<textarea name="sponsors_desc" id="sponsors_desc" class="span11"/><?php echo $f->POST_VAL('sponsors_desc');?></textarea>
						</div>
					</div>
               
					<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<input type="file" name="sponsors_img" id="sponsors_img" class="" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>136px X 136px</b></div>-->
						</div>
					</div>					
					
					<div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required">
								<option value="Active"<?php if($f->POST_VAL('status') == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($f->POST_VAL('status') == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>
					
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Sponsors" class="btn btn-success" />
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
			
			if(empty($_FILES['sponsors_img']['name'])==false) 
			{		
				$objFileUpload = new FileUpload();
				$objFileUpload->UploadMode = 'Edit';
				$objFileUpload->OldFileName = $_POST['old_sponsors_img'];
				$objFileUpload->UploadContent = $_FILES['sponsors_img'];
				$objFileUpload->UploadFolder = "../".SPONSOR_IMG;
				$image_return = $objFileUpload->Upload();
				$is_sponsors_img = $image_return['server_name'];	
			}else{
				$is_sponsors_img = $_POST['old_sponsors_img'];
			}		
			
			$data = array(
				'cat_id' => $f->setValue($_POST['cat_id']),				
				'sponsors_title' => $f->setValue($_POST['sponsors_title']),
				'sponsors_link' => $f->setValue($_POST['sponsors_link']),
				'sponsors_desc' => $f->setValue($_POST['sponsors_desc']),
				'sponsors_img' => $is_sponsors_img,
				'status' => $f->setValue($_POST['status'])
			);
				
			$db->update(T, $data, "sponsors_id", $Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");
		}		
		
		$sql = "SELECT * FROM `".T."` WHERE `sponsors_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$sponsors_img = SPONSOR_IMG.'/'.$row['sponsors_img'];									
		if(!file_exists('../'.$sponsors_img) || empty($row['sponsors_img']) == true)
		{
			$sponsors_img = "images/no_image.png";
		}

	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Sponsors</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls">
							<select name="cat_id" id="cat_id" class="span11 required">                     	
                        <?php 									
									$first_cat_id = $row['cat_id'];
									$sql_cat = "SELECT * FROM `tbl_sponsors_category` WHERE `mark_for_deleted`='No' ORDER BY `cat_title` ASC";
									$res_cat = $db->get($sql_cat);
									while($row_cat = $db->fetch_array($res_cat))
									{										
								?>
                        <option value="<?php echo $f->getValue($row_cat['cat_id']);?>"<?php if($row_cat['cat_id'] == $row['cat_id']) echo ' selected';?>><?php echo $f->getValue($row_cat['cat_title']);?></option>
                        <?php 
										$count++; 
									} 
								?>
                     </select>
						</div>
					</div> 
               <div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">							
							<input type="text" name="sponsors_title" id="sponsors_title" class="span11 required" value="<?php echo $f->getValue($row['sponsors_title']);?>" />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Title URL:</label>
						<div class="controls">							
							<input type="text" name="sponsors_link" id="sponsors_link" class="span11 url" value="<?php echo $f->getValue($row['sponsors_link']);?>" />
						</div>
					</div>
                <div class="control-group">
						<label class="control-label">Description:</label>
						<div class="controls">							
							<textarea name="sponsors_desc" id="sponsors_desc" class="span11"/><?php echo $f->getValue($row['sponsors_desc']);?></textarea>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $sponsors_img;?>&w=140" border="0" />
							<input type="hidden" name="old_sponsors_img" value="<?php echo $row['sponsors_img'];?>" />	
							<input type="file" name="sponsors_img" id="sponsors_img" class="" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>136px X 136px</b></div>-->
						</div>
					</div>					
										
					
					
					<div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required">
								<option value="Active"<?php if($f->getValue($row['status']) == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($f->getValue($row['status']) == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>
					
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Sponsors" class="btn btn-success" />
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
