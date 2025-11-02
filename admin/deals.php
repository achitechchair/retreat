<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_deals");
	$index = $_GET['index'];
	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />
<script type="text/javascript">
<?php if($index != "List"):?>
$(document).ready(function() {
	$(".numeric").numeric();
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Deal</a></div>
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
				$v = $rowQ['deal_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `deal_id`='".$rowQ['deal_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					/*$sqlS = "UPDATE ".T." SET `display_order`='".$row0['display_order']."' WHERE `display_order`='".$_POST["seq$v"]."'";
					$resS = $db->get($sqlS);*/
					
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `deal_id`='".$rowQ['deal_id']."'";
					$RESss = $db->get($SQLss);
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if($_GET['action']=='Active' || $_GET['action']=='Inactive') 
		{
			$Id = $_GET['Id'];
			$sql = "UPDATE `".T."` SET `status`='".$_GET['action']."' WHERE `deal_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=status");			
		}
		
		if($_GET['action']=='Bulk') 
		{			
			foreach($_POST['all_check_bx'] as $val):
				$sql = "UPDATE `".T."` SET `status`='".$_GET['status']."' WHERE `deal_id`=".$val;
				$db->get($sql,__FILE__,__LINE__);							
			endforeach;
			
			$f->Redirect(CP."?index=List&msg=status");
			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `deal_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);			
			
			$files = '../'.DEAL_IMG.'/'.$_GET['file'];
			$f->DeleteFile($files);			
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` WHERE `mark_for_deleted`='No' ORDER BY `deal_id` DESC";		
		/*$sql = "SELECT a.*, b.`deal_cat_name`, c.`country_name`, d.`store_name` 
				 FROM `".T."` AS a 
				 INNER JOIN `tbl_deal_category` AS b ON b.`cat_id`=a.`cat_id` AND b.`deal_id`=a.`deal_id`
				 INNER JOIN `tbl_deals_country` AS c ON c.`country_id`=a.`country_id` AND c.`deal_id`=a.`deal_id`
				 WHERE a.`mark_for_deleted`='No' ORDER BY a.`deal_id` DESC";	*/	
		$res = $db->get($sql);
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
				<!--<input type="button" class="btn btn-inverse" name="btnSeq" id="btnSeq" value="Change Seq. Order" />	-->			
				<?php //} ?>
			</td>
		</tr>
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Deal (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table width="100%" class="table table-bordered <?php if($records > 0) echo ' data-table';?>">
					<thead>
						<tr>							
							<!-- <th width="2%">Seq.</th>-->
							 <th width="5%">Image</th>							
							 <th width="59%"><div align="left">Title</div></th>
							 <th width="8%"><div align="center">Price</div></th>							
							 <!--<th width="21%"><div align="left">Category</div></th> 							  
							 <th width="19%"><div align="left">Country</div></th> -->
                      <th width="9%">Additional Images</th>	  
                      <th width="8%"><div align="center">Status</div></th>	
							 <th width="11%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$deal_id = $row['deal_id'];
											
									$deal_image = '../'.DEAL_IMG.'/'.$row['deal_image'];									
									if(!file_exists($deal_image) || empty($row['deal_image']) == true)
									{
										$deal_image = "../images/no_image.png";
									}								
																										
									
						 ?>
						<tr>							
							<!--<td><input name="seq<?php echo $deal_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></td>-->
							<td><div align="center"><a href="<?php echo $deal_image;?>" class="lightbox_trigger" title=""><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo $deal_image;?>&w=75&h=75&zc=1" border="0" /></a></div></td>
							<td><?php echo $f->getValue($row['deal_title']);?></td>
							<td><div align="center">$<?php echo $f->getValue($row['deal_price']);?></div></td>
							<td><div align="center"><a href="deal-additional-images.php?index=List&ref_deal_id=<?php echo $deal_id;?>"><img src="images/additional_image.png" width="16" height="16" border="0" /></a></div></td>						
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
							<td><div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $deal_id;?>" class="btn btn-primary btn-mini">Edit</a> 
							<?php 
								if($_SESSION['_admin_user_type'] != 'AdminUser')
								{
							?>
							<a href="#myAlert<?php echo $deal_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>
							<?php } ?>
							</div></td>
						</tr>
					<div id="myAlert<?php echo $row['deal_id'];?>" class="modal hide">
						<div class="modal-header">
							<button data-dismiss="modal" class="close" type="button">×</button>
							<h3>Alert</h3>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to Delete? </p>
						</div>
						<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $deal_id;?>&file=<?php echo $row['deal_image'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
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
			$deal_title = $f->setValue($_POST['deal_title']);
			
			$sql = "SELECT * FROM `".T."` WHERE `deal_title`='".$deal_title."' AND `mark_for_deleted`='No'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($deal_title).'" already exist !!!');
			} else {	
			
				if(empty($_FILES['deal_image']['name'])==false) 
				{		
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadContent = $_FILES['deal_image'];
					$objFileUpload->UploadFolder = "../".DEAL_IMG;
					$image_return = $objFileUpload->Upload();
					$is_deal_image = $image_return['server_name'];	
				}else{
					$is_deal_image = "";
				}	
				
				$deal_slug = $f->slugify($_POST['deal_title']);		
				
				$data = array(				
					'deal_title' => $f->setValue($_POST['deal_title']),
					/*'deal_slug' => $deal_slug,*/					
					'deal_price' => $f->setValue($_POST['deal_price']),
					'deal_desc' => $f->setValue($_POST['deal_desc']),
					'status' => $f->setValue($_POST['status']),
					'deal_image' => $is_deal_image,
					'read_more_url' => $f->setValue($_POST['read_more_url']),
					'deal_date' => $f->setValue($_POST['deal_date'])
				);
						
				$db->insert(T,$data);
				$deal_id = $db->last_insert_id();
				$deal_slug = $deal_slug."-".$deal_id;
				$data = array('deal_slug' => $deal_slug);
				$db->update(T, $data, "deal_id", $deal_id);
				
				// Insert data into deal category table ==================================================================
				$cat_id = $_POST['cat_id'];
				$sql = "DELETE FROM `tbl_deals_category` WHERE `deal_id`='".$deal_id."'";
				$db->get($sql,__FILE__,__LINE__);
				
				foreach($cat_id as $val)
				{
					$data_1 = array(
						'deal_id' => $deal_id,
						'cat_id' => $val
					);					
					$db->insert('tbl_deals_category', $data_1);	
				}
				
				// Insert data into deal county table ===================================================================
				$country_id = $_POST['country_id'];
				$sql = "DELETE FROM `tbl_deals_country` WHERE `deal_id`='".$deal_id."'";
				$db->get($sql,__FILE__,__LINE__);
				
				foreach($country_id as $val)
				{
					$data_2 = array(
						'deal_id' => $deal_id,
						'country_id' => $val
					);
					$db->insert('tbl_deals_country', $data_2);	
				}
				
				$f->Redirect(CP."?index=List&msg=success");
			}
		}
?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frm" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Add New Deal</h5>
				</div>
				<div class="widget-content nopadding">
					
					<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<input type="file" name="deal_image" id="deal_image" class="required" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>950px X 451px</b></div>-->
						</div>
					</div>					
					<!--<div class="control-group">
						<label class="control-label">Image ALT Tag:</label>
						<div class="controls">
							<input name="img_alt_tag" type="text" id="img_alt_tag" value="<?php echo $_POST['img_alt_tag'];?>" class="span11" />								
						</div>
					</div>-->
					<div class="control-group">
						<label class="control-label">Deal Title:</label>
						<div class="controls">
							<input name="deal_title" type="text" id="deal_title" value="<?php echo $_POST['deal_title'];?>" class="span11 required" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Deal Price ($):</label>
						<div class="controls">
							<input name="deal_price" type="text" id="deal_price" value="<?php echo $_POST['deal_price'];?>" class="span11 numeric required" />								
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="cat_id[]" id="cat_id" class="span11 required" multiple>			
							<?php 
								$sql_cat = "SELECT * FROM `tbl_category` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";
								$res_cat = $db->get($sql_cat);
								while($row_cat = $db->fetch_array($res_cat))
								{
							?>
							<option value="<?php echo $f->getValue($row_cat['cat_id']);?>"<?php if($row_cat['cat_id'] == $_POST['cat_id']) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_cat['cat_title']);?></option>
							<?php 
								} 
								$db->free_result($res_cat);
							?>
							</select>								
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Country:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="country_id[]" id="country_id" class="span11 required" multiple>			
							<?php 
								$sql_country = "SELECT * FROM `tbl_country` WHERE `mark_for_deleted`='No' ORDER BY `country_title` ASC";
								$res_country = $db->get($sql_country);
								while($row_country = $db->fetch_array($res_country))
								{
							?>
							<option value="<?php echo $f->getValue($row_country['country_id']);?>"<?php if($row_country['country_id'] == $_POST['country_id']) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_country['country_title']);?></option>
							<?php 
								} 
								$db->free_result($res_country);
							?>
							</select>								
						</div>
					</div>				
										
					<div class="control-group">
						<label class="control-label">Text:</label>
						<div class="controls">
							<textarea name="deal_desc" id="deal_desc" class="required span11 ckeditor"><?php echo $_POST['deal_desc'];?></textarea>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Read More URL:</label>
						<div class="controls">
							<input name="read_more_url" type="text" id="read_more_url" value="<?php echo $_POST['read_more_url'];?>" class="span11 url" />								
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Deal Date:</label>
						<div class="controls">
							<input name="deal_date" type="date" id="deal_date" value="<?php echo $_POST['deal_date'];?>" class="span11 required" />								
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
					
					<!--<div class="control-group">
						<label class="control-label">SEO Page Title:</label>
						<div class="controls">
							<input name="seo_page_title" type="text" id="seo_page_title" value="<?php echo $_POST['seo_page_title'];?>" class="span11" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">SEO Meta Description:</label>
						<div class="controls">
							<textarea name="seo_page_desc" class="span11 no_enter textarea_height_80" id="seo_page_desc"><?php echo $_POST['seo_page_desc'];?></textarea>
						</div>
					</div>-->
					
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Deal" class="btn btn-success" />
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
			
			$deal_title = $f->setValue($_POST['deal_title']);
			
			$sql = "SELECT * FROM `".T."` WHERE `deal_title`='".$deal_title."' AND `mark_for_deleted`='No' AND `deal_id`<>'".$Id."'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($deal_title).'" already exist !!!');
			} else {			
			
				if(empty($_FILES['deal_image']['name'])==false) 
				{		
					$objFileUpload = new FileUpload();
					$objFileUpload->UploadMode = 'Edit';
					$objFileUpload->OldFileName = $_POST['old_deal_image'];
					$objFileUpload->UploadContent = $_FILES['deal_image'];
					$objFileUpload->UploadFolder = "../".DEAL_IMG;
					$image_return = $objFileUpload->Upload();
					$is_deal_image = $image_return['server_name'];	
				}else{
					$is_deal_image = $_POST['old_deal_image'];
				}		
				
				$deal_desc_search = $f->getOnlyText($_POST['deal_desc']);
				$deal_desc_search = str_replace("\r\n", " ", $deal_desc_search);
				$deal_desc_search = str_replace("\n", " ", $deal_desc_search);					
				$deal_search_text = $deal_title." ".$deal_desc_search;
								
				$deal_slug = $f->slugify($_POST['deal_title']).'-'.$Id;	
				
				$data = array(				
					'deal_title' => $f->setValue($_POST['deal_title']),
					'deal_slug' => $deal_slug,
					/*'deal_slug' => $deal_slug,*/					
					'deal_price' => $f->setValue($_POST['deal_price']),
					'deal_desc' => $f->setValue($_POST['deal_desc']),
					'status' => $f->setValue($_POST['status']),
					'deal_image' => $is_deal_image,
					'read_more_url' => $f->setValue($_POST['read_more_url']),
					'deal_date' => $f->setValue($_POST['deal_date'])
				);
					
				$db->update(T, $data, "deal_id", $Id);
				$deal_id = $Id;
				
				
				// Insert data into deal category table ======================================================================
				$cat_id = $_POST['cat_id'];
				$sql = "DELETE FROM `tbl_deals_category` WHERE `deal_id`='".$deal_id."'";
				$db->get($sql,__FILE__,__LINE__);
				
				foreach($cat_id as $val)
				{
					$data_1 = array(
						'deal_id' => $deal_id,
						'cat_id' => $val
					);					
					$db->insert('tbl_deals_category', $data_1);	
				}
				
				// Insert data into deal county table ========================================================================
				$country_id = $_POST['country_id'];
				$sql = "DELETE FROM `tbl_deals_country` WHERE `deal_id`='".$deal_id."'";
				$db->get($sql,__FILE__,__LINE__);
				
				foreach($country_id as $val)
				{
					$data_2 = array(
						'deal_id' => $deal_id,
						'country_id' => $val
					);
					$db->insert('tbl_deals_country', $data_2);	
				}
				
				
				$msg = $f->getHtmlMessage("Record has been successfully updated");
			}
		}		
		
		$sql = "SELECT * FROM `".T."` WHERE `deal_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$deal_image = '../'.DEAL_IMG.'/'.$row['deal_image'];									
		if(!file_exists($deal_image) || empty($row['deal_image']) == true)
		{
			$deal_image = "../images/no_image.png";
		}
		
		// Get Category
		$sql = "SELECT `cat_id` FROM `tbl_deals_category` WHERE `deal_id`='".$Id."'";
		$res = $db->get($sql,__FILE__,__LINE__);
		$CatArr = array();
		if($db->num_rows($res) > 0)
		{
			while($CatRow = $db->fetch_array($res))
			{
				array_push($CatArr, $CatRow['cat_id']);
			}	
		}		
		$db->free_result($res);	
		
		// Get Country
		$sql = "SELECT `country_id` FROM `tbl_deals_country` WHERE `deal_id`='".$Id."'";
		$res = $db->get($sql,__FILE__,__LINE__);
		$CountryArr = array();
		if($db->num_rows($res) > 0)
		{
			while($CountryRow = $db->fetch_array($res))
			{
				array_push($CountryArr, $CountryRow['country_id']);
			}	
		}		
		$db->free_result($res);	

	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Deal</h5>
				</div>
				<div class="widget-content nopadding">
					
					<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo $deal_image;?>&w=140" border="0" />
							<input type="hidden" name="old_deal_image" value="<?php echo $row['deal_image'];?>" />	
							<input type="file" name="deal_image" id="deal_image" class="" accept=".png, .jpg, .jpeg" />
							<!--<div class="info-text">Image size should be <b>950px X 451px</b></div>-->
						</div>
					</div>					
					<!--<div class="control-group">
						<label class="control-label">Image ALT Tag:</label>
						<div class="controls">
							<input name="img_alt_tag" type="text" id="img_alt_tag" value="<?php echo $f->getValue($row['img_alt_tag']);?>" class="span11" />								
						</div>
					</div>-->
					
					<div class="control-group">
						<label class="control-label">Deal Title:</label>
						<div class="controls">
							<input name="deal_title" type="text" id="deal_title" value="<?php echo $f->getValue($row['deal_title']);?>" class="span11 required" />								
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">Deal Price ($):</label>
						<div class="controls">
							<input name="deal_price" type="text" id="deal_price" value="<?php echo $f->getValue($row['deal_price']);?>" class="span11 numeric required" />								
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="cat_id[]" id="cat_id" class="span11 required" multiple>			
							<?php 
								$sql_cat = "SELECT * FROM `tbl_category` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";
								$res_cat = $db->get($sql_cat);
								while($row_cat = $db->fetch_array($res_cat))
								{
							?>
							<option value="<?php echo $f->getValue($row_cat['cat_id']);?>"<?php if(in_array($row_cat['cat_id'], $CatArr) == TRUE) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_cat['cat_title']);?></option>
							<?php 
								} 
								$db->free_result($res_cat);
							?>
							</select>								
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Country:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="country_id[]" id="country_id" class="span11 required" multiple>			
							<?php 
								$sql_country = "SELECT * FROM `tbl_country` WHERE `mark_for_deleted`='No' ORDER BY `country_title` ASC";
								$res_country = $db->get($sql_country);
								while($row_country = $db->fetch_array($res_country))
								{
							?>
							<option value="<?php echo $f->getValue($row_country['country_id']);?>"<?php if(in_array($row_country['country_id'], $CountryArr) == TRUE) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_country['country_title']);?></option>
							<?php 
								} 
								$db->free_result($res_country);
							?>
							</select>								
						</div>
					</div>
										
					<div class="control-group">
						<label class="control-label">Text:</label>
						<div class="controls">
							<textarea name="deal_desc" id="deal_desc" class="required span11 ckeditor"><?php echo $f->getValue($row['deal_desc']);?></textarea>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Read More URL:</label>
						<div class="controls">
							<input name="read_more_url" type="text" id="read_more_url" value="<?php echo $f->getValue($row['read_more_url']);?>" class="span11 url" />								
						</div>
					</div>
					
                <div class="control-group">
						<label class="control-label">Deal Date:</label>
						<div class="controls">
							<input name="deal_date" type="date" id="deal_date" value="<?php echo $f->getValue($row['deal_date']);?>" class="span11 required" />								
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
					
					<!--<div class="control-group">
						<label class="control-label">SEO Page Title:</label>
						<div class="controls">
							<input name="seo_page_title" type="text" id="seo_page_title" value="<?php echo $f->getValue($row['seo_page_title']);?>" class="span11" />								
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">SEO Meta Description:</label>
						<div class="controls">
							<textarea name="seo_page_desc" class="span11 no_enter textarea_height_80" id="seo_page_desc"><?php echo $f->getValue($row['seo_page_desc']);?></textarea>
						</div>
					</div>-->
					
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Deal" class="btn btn-success" />
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
