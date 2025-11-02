<?php
	require_once("../includes/config.inc.php");
	//require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	
	define("T","tbl_state");
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage State</a></div>
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
				$v = $rowQ['state_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `state_id`='".$rowQ['state_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `state_id`='".$rowQ['state_id']."'";
					$RESss = $db->get($SQLss);
					
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `state_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			
			/*$files = '../'.PRODUCT_IMG.'/'.$_GET['file'];
			$f->DeleteFile($files);	*/		
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT a.*, b.`country_id`, b.`country_name` FROM `".T."` AS a, `tbl_country` AS b 
				  WHERE a.`country_id`=b.`country_id`
				  AND a.`mark_for_deleted`='No' ORDER BY b.`country_name` ASC, a.`state_name` ASC";			
		$res = $db->get($sql);
		$records = $db->num_rows($res);
		
  ?>
	<table class="table" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<a href="<?php echo CP.'?index=Add';?>" class="btn btn-inverse"> <i class="icon-plus"></i> Add New </a>
				
			</td>
		</tr>
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of State (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 
                  	<th style="display:none"></th>                    
                     <th width="43%"><div align="left">State Name</div></th>
                     <th width="40%"><div align="left">Country Name</div></th>
							<th width="9%">Status</th>
							<th width="8%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$state_id = $row['state_id'];	
									
						 ?>
						<tr>
                  	<td style="display:none;"><?php echo $row['country_id'];?></td>
							<td><?php echo ($f->getValue($row['state_name'])) ? ($f->getValue($row['state_name'])) : ('-');?></td>   
                     <td><?php echo ($f->getValue($row['country_name'])) ? ($f->getValue($row['country_name'])) : ('-');?></td>                                       
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
                     <td><div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $state_id;?>" class="btn btn-primary btn-mini">Edit</a> <a href="#myAlert<?php echo $state_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a></div></td>
						</tr>
						<div id="myAlert<?php echo $row['state_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $state_id;?>&lang_name=<?php echo $f->getValue($row['language_name']);?>&file=<?php echo $row['pro_img'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="4" class="NoRecord">No Record Found</td>
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
			$state_name = $f->setValue($_POST['state_name']);
			$country_id  = $f->setValue($_POST['country_id']);
			
			$sql = "SELECT * FROM `".T."` WHERE `state_name`='".$state_name."' AND `country_id`=".$country_id." AND `mark_for_deleted`='No'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($state_name).'" already exist !!!');
			} else {
				
				$data = array(	
					'country_id' => $country_id,
					'state_name' => $state_name,
					'status' => $f->setValue($_POST['status']) 
				);
				
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
					<h5>Add New State</h5>
				</div>
				<div class="widget-content nopadding">	 
            	<div class="control-group">
						<label class="control-label">Country:</label>
						<div class="controls">
							<select name="country_id" id="country_id" class="span11 required">                     	
                        <?php 
									$sql_cat = "SELECT * FROM `tbl_country` WHERE `mark_for_deleted`='No' ORDER BY `country_name` ASC";
									$res_cat = $db->get($sql_cat);
									while($row_cat = $db->fetch_array($res_cat))
									{
								?>
                        <option value="<?php echo $f->getValue($row_cat['country_id']);?>"><?php echo $f->getValue($row_cat['country_name']);?></option>
                        <?php 
									} 
								?>
                     </select>
						</div>
					</div>           				
					<div class="control-group">
						<label class="control-label">State Name:</label>
						<div class="controls">							
							<input type="text" name="state_name" id="state_name" class="span11 required" value="<?php echo $_POST['state_name'];?>" />
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
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnCreate" id="btnCreate" type="submit" value="Add State" class="btn btn-success" />
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
			$state_name = $f->setValue($_POST['state_name']);
			$country_id  = $f->setValue($_POST['country_id']);
			
			$sql = "SELECT * FROM `".T."` WHERE `state_name`='".$state_name."'  AND `country_id`=".$country_id." AND `mark_for_deleted`='No' AND `state_id`<>".$Id;
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Title "'.$f->getValue($state_name).'" already exist !!!');
			} else {
								
				$data = array(	
					'country_id' => $country_id,
					'state_name' => $state_name,
					'status' => $f->setValue($_POST['status']) 		
				);
			
								
				$db->update(T, $data, "state_id", $Id);					
				$msg = $f->getHtmlMessage("Record has been successfully updated");
			}
		}	
		
		$sql = "SELECT * FROM `".T."` WHERE `state_id`=".$Id;
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
					<h5>Modify State</h5>
				</div>
				<div class="widget-content nopadding">	
            	 <div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls">
							<select name="country_id" id="country_id" class="span11 required">                     	
                        <?php 
									$sql_cat = "SELECT * FROM `tbl_country` WHERE `mark_for_deleted`='No' ORDER BY `country_name` ASC";
									$res_cat = $db->get($sql_cat);
									while($row_cat = $db->fetch_array($res_cat))
									{
								?>
                        <option value="<?php echo $f->getValue($row_cat['country_id']);?>"<?php if($row_cat['country_id'] == $row['country_id']) echo ' selected';?>><?php echo $f->getValue($row_cat['country_name']);?></option>
                        <?php 
									} 
								?>
                     </select>
						</div>
					</div>			
					<div class="control-group">
						<label class="control-label">State Name:</label>
						<div class="controls">							
							<input type="text" name="state_name" id="state_name" class="span11 required" value="<?php echo $f->getValue($row['state_name']);?>" />
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
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify State" class="btn btn-success" />
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
