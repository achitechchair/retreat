<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_testimonials");
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
	$('#main_check_box').change(function() {		
		if($(this).is(":checked")) 
		{
			$('.sub_check_box').prop('checked',true);
		}
		else
		{
			$('.sub_check_box').prop('checked',false);
		}
	});
	
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Updates</a></div>
	</div>
	<!--End-breadcrumbs-->
	<?php 
	if($index == 'List')
	{
		
		if(isset($_POST['btnseq']))
		{
			$sqlQ = "SELECT * FROM ".T;
			$resQ = $db->get($sqlQ, __FILE__, __LINE__);
			while($rowQ = $db->fetch_array($resQ))
			{
				$v = $rowQ['testimonials_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `testimonials_id`='".$rowQ['testimonials_id']."'";
					$res0 = $db->get($sql0, __FILE__, __LINE__);
					$row0 = $db->fetch_array($res0);
									
					/*$sqlS = "UPDATE ".T." SET `display_order`='".$row0['display_order']."' WHERE `display_order`='".$_POST["seq$v"]."'";
					$resS = $db->get($sqlS, __FILE__, __LINE__);*/
					
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `testimonials_id`='".$rowQ['testimonials_id']."'";
					$RESss = $db->get($SQLss, __FILE__, __LINE__);
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && ($_GET['action']=='Active' || $_GET['action']=='Inactive')) 
		{
			$Id = $_GET['Id'];
			$sql = "UPDATE `".T."` SET `status`='".$_GET['action']."' WHERE `testimonials_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=status");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Bulk') 
		{			
			foreach($_POST['all_check_bx'] as $val):
				$sql = "UPDATE `".T."` SET `status`='".$_GET['status']."' WHERE `testimonials_id`=".$val;
				$db->get($sql,__FILE__,__LINE__);							
			endforeach;
			
			$f->Redirect(CP."?index=List&msg=status");
			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `testimonials_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);			
			
			/*$files = '../'.TESTIMONIAL_IMG.'/'.$_GET['file'];
			$f->DeleteFile($files);		*/	
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` WHERE `mark_for_deleted`='No' ORDER BY `testimonials_id` DESC";		
		$res = $db->get($sql, __FILE__, __LINE__);
		$records = $db->num_rows($res);
		
  ?>
	<table class="table" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<a href="<?php echo CP.'?index=Add';?>" class="btn btn-inverse"> <i class="icon-plus"></i> Add New </a>
				<?php 
					/*if($records > 0) 
					{*/
				?>
				<!--<input type="button" class="btn btn-inverse" name="btnSeq" id="btnSeq" value="Change Seq. Order" />-->				
				<?php //} ?>
			</td>
		</tr>
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Updates (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr>							
							 <!--<th width="2%">Seq.</th>-->
							 <th width="22%"><div align="left">Title</div></th>							 
							 <th width="56%"><div align="left">Text</div></th>	
                      <th width="5%">Date</th>						
							 <th width="8%">Status</th> 
							 <th width="9%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$testimonials_id = $row['testimonials_id'];
											
									/*$testimonials_img = '../'.TESTIMONIAL_IMG.'/'.$row['testimonials_img'];									
									if(!file_exists($testimonials_img) || empty($row['testimonials_img']) == true)
									{
										$testimonials_img = "../images/fig.png";
									}*/					
									
									$dated = $f->getValue($row['dated']);
									$testimonials_text = $f->getHTMLDecode($row['testimonials_text']);
									$testimonials_text = str_replace('"', '', $testimonials_text);
									$testimonials_text = (strlen($testimonials_text) > 100) ? substr($testimonials_text, 0, 100)."..." : $testimonials_text;																		
									
						 ?>
						<tr>							
							<!--<td><input name="seq<?php echo $testimonials_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></td>-->
							<!--<td><img src="../phpThumb/phpThumb.php?src=<?php echo $testimonials_img;?>&w=92&h=92&zc=1" border="0" /></td>-->
							<td><?php echo $f->getValue($row['testimonials_title']);?></td>
                     <td><?php echo $testimonials_text;?></td>
                     <td><div align="center"><?php echo date("m/d/Y", strtotime($row['dated']));?></div></td>							
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><b><?php echo $f->getValue($row['status']);?></b></div></td>
							<td><div align="center"><!--<a href="<?php echo CP;?>?index=View&Id=<?php echo $testimonials_id;?>" class="btn btn-success btn-mini">View</a> --><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $testimonials_id;?>" class="btn btn-primary btn-mini">Edit</a> <a href="#myAlert<?php echo $testimonials_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a></div></td>
						</tr>
					<div id="myAlert<?php echo $row['testimonials_id'];?>" class="modal hide">
						<div class="modal-header">
							<button data-dismiss="modal" class="close" type="button">×</button>
							<h3>Alert</h3>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to Delete? </p>
						</div>
						<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $testimonials_id;?>&file=<?php echo $row['testimonials_img'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
					</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="5" class="NoRecord">No Record Found</td>
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
				/*if(empty($_FILES['testimonials_img']['name'])==false) 
				{		
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadContent = $_FILES['testimonials_img'];
					$objFileUpload->UploadFolder = "../".TESTIMONIAL_IMG;
					$image_return = $objFileUpload->Upload();
					$is_testimonials_img = $image_return['server_name'];	
				}else{
					$is_testimonials_img = "";
				}	*/	
				
				$data = array(
					'testimonials_title' => $f->setValue($_POST['testimonials_title']),
					'dated' => $f->setValue($_POST['dated']),
					'testimonials_text' => $f->setValue($_POST['testimonials_text']),
					'testimonials_text_long' => $f->setValue($_POST['testimonials_text_long']),
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
					<h5>Add New Updates</h5>
				</div>
				<div class="widget-content nopadding">
					
					<!--<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<input type="file" name="testimonials_img" id="testimonials_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>170px X 170px</b></div>
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Image ALT Tag:</label>
						<div class="controls">
							<input name="img_alt_tag" type="text" id="img_alt_tag" value="<?php echo $_POST['img_alt_tag'];?>" class="span11" />								
						</div>
					</div>-->
               <div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">
							<input name="testimonials_title" type="text" id="testimonials_title" value="<?php echo $f->POST_VAL('testimonials_title');?>" class="span11 required" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Date:</label>
						<div class="controls">
							<input name="dated" type="date" id="dated" value="<?php echo $f->POST_VAL('dated');?>" class="span11 required" />								
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Sort Description:</label>
						<div class="controls">
							<textarea name="testimonials_text" id="testimonials_text" class="required span11 textarea_height_200"><?php echo $f->POST_VAL('testimonials_text');?></textarea>
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Long Description:</label>
						<div class="controls">
							<textarea name="testimonials_text_long" id="testimonials_text_long" class="required span11 ckeditor"><?php echo $f->POST_VAL('testimonials_text_long');?></textarea>
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
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Updates" class="btn btn-success" />
								<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php 
	}elseif($index == 'Edit'){
		
		$Id = $_GET['Id'];
					
		if(isset($_POST['btnEdit'])) 
		{			
			
			/*if(empty($_FILES['testimonials_img']['name'])==false) 
			{		
				$objFileUpload = new FileUpload();
				$objFileUpload->UploadMode = 'Edit';
				$objFileUpload->OldFileName = $_POST['old_testimonials_img'];
				$objFileUpload->UploadContent = $_FILES['testimonials_img'];
				$objFileUpload->UploadFolder = "../".TESTIMONIAL_IMG;
				$image_return = $objFileUpload->Upload();
				$is_testimonials_img = $image_return['server_name'];	
			}else{
				$is_testimonials_img = $_POST['old_testimonials_img'];
			}*/
			
			$data = array(
				'testimonials_title' => $f->setValue($_POST['testimonials_title']),
				'dated' => $f->setValue($_POST['dated']),
				'testimonials_text' => $f->setValue($_POST['testimonials_text']),
				'testimonials_text_long' => $f->setValue($_POST['testimonials_text_long']),
				'status' => $f->setValue($_POST['status'])				
			);
				
			$db->update(T, $data, "testimonials_id", $Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");	
		}		
		
		$sql = "SELECT * FROM `".T."` WHERE `testimonials_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		/*$testimonials_img = '../'.TESTIMONIAL_IMG.'/'.$row['testimonials_img'];									
		if(!file_exists($testimonials_img) || empty($row['testimonials_img']) == true)
		{
			$testimonials_img = "../images/fig.png";
		}*/

	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Updates</h5>
				</div>
				<div class="widget-content nopadding">
					
					<!--<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<img src="../phpThumb/phpThumb.php?src=<?php echo $testimonials_img;?>&w=140" border="0" />
							<input type="hidden" name="old_testimonials_img" value="<?php echo $row['testimonials_img'];?>" />	
							<input type="file" name="testimonials_img" id="testimonials_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>170px X 170px</b></div>
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Image ALT Tag:</label>
						<div class="controls">
							<input name="img_alt_tag" type="text" id="img_alt_tag" value="<?php echo $_POST['img_alt_tag'];?>" class="span11" />								
						</div>
					</div>-->
					 <div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">
							<input name="testimonials_title" type="text" id="testimonials_title" value="<?php echo $f->getValue($row['testimonials_title']);?>" class="span11 required" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Date:</label>
						<div class="controls">
							<input name="dated" type="date" id="dated" value="<?php echo $f->getValue($row['dated']);?>" class="span11 required" />								
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Sort Description:</label>
						<div class="controls">
							<textarea name="testimonials_text" id="testimonials_text" class="required span11 textarea_height_200"><?php echo $f->getValue($row['testimonials_text']);?></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Long Description:</label>
						<div class="controls">
							<textarea name="testimonials_text_long" id="testimonials_text_long" class="required span11 ckeditor"><?php echo $f->getValue($row['testimonials_text_long']);?></textarea>
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
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Updates" class="btn btn-success" />
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
		
		if(isset($_POST['btnEditStatus'])) 
		{							
			$data = array(				
				'status' => $f->setValue($_POST['status'])				
			);
				
			$db->update(T, $data, "testimonials_id", $Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");	
		}			
		
		$sql = "SELECT * FROM `".T."` WHERE `testimonials_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>View Testimonials</h5>
				</div>				
				<div class="widget-content" style="padding-left:50px !important;">
                          
					<div class="views">
						<b>Posted By</b><br /> <p><?php echo $f->getValue($row['dated']);?></p>
					</div>
					<div class="views">
						<b>Text:</b><br /> <p><?php echo nl2br($f->getValue($row['testimonials_text']));?></p>				
					</div>
					
               <div class="views">
               	<b>Status:</b><br />
                  <select name="status" id="status" class="">
                     <option value="Active"<?php if($f->getValue($row['status']) == 'Active') echo ' selected';?>>Active</option>
                     <option value="Inactive"<?php if($f->getValue($row['status']) == 'Inactive') echo ' selected';?>>Inactive</option>
                  </select>
               </div>
					
					<div class="control-group">
						<div class="">	
                  	<input name="btnEditStatus" id="btnEditStatus" type="submit" value="Update Status" class="btn btn-success" />					
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
