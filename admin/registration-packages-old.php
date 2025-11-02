<?php
	require_once("../includes/config.inc.php");
	//require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	
	define("T","tbl_registration_packages");
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
	$(".number_only").numeric(false);
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Packages</a></div>
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
				$v = $rowQ['reg_pack_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `reg_pack_id`='".$rowQ['reg_pack_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `reg_pack_id`='".$rowQ['reg_pack_id']."'";
					$RESss = $db->get($SQLss);
			
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `reg_pack_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=del");	
					
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` WHERE `mark_for_deleted`='No' ORDER BY `package_price` ASC";		
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
			<h5>List of Packages (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 
                     <th width="53%"><div align="left">Package Name</div></th>
                     <th width="12%"><div align="center">Package Price</div></th>                     
							<th width="7%">Status</th>
							<th width="11%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$reg_pack_id = $row['reg_pack_id'];	
									
						 ?>
						<tr>
							<td><?php echo ($f->getValue($row['package_name'])) ? ($f->getValue($row['package_name'])) : ('-');?></td>
                     <td><div align="center"><?php echo ($f->getValue($row['package_price'])) ? ("$".$f->getValue($row['package_price'])) : ('-');?></div></td> 
                     <td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
                     <td><div align="center">
                     	<a href="<?php echo CP;?>?index=Edit&Id=<?php echo $reg_pack_id;?>" class="btn btn-primary btn-mini">Edit</a>
                     	<?php
									if($reg_pack_id != 1)
									{
								?>                         
                        <a href="#myAlert<?php echo $reg_pack_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>
                        <?php 
									}
								?>	
                        </div>
                     </td>
						</tr>
						<div id="myAlert<?php echo $row['reg_pack_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $reg_pack_id;?>&file=">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
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
			$package_name = $f->setValue($_POST['package_name']);
			$sql = "SELECT * FROM `".T."` WHERE `package_name`='".$package_name."' AND `mark_for_deleted`='No'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Package Name "'.$f->getValue($package_name).'" already exist !!!');
			} else {
				
				$data = array(	
					'package_name' => $package_name,
					'package_price' => ($_POST['package_price']) ? ($f->setValue($_POST['package_price'])) : (0),
					/*'no_audult' => ($_POST['no_audult']) ? ($f->setValue($_POST['no_audult'])) : (0),
					'no_child' => ($_POST['no_child']) ? ($f->setValue($_POST['no_child'])) : (0),*/
					'youth_activity_price' => ($_POST['youth_activity_price']) ? ($f->setValue($_POST['youth_activity_price'])) : (0),
					'banquet_dinner_price' => ($_POST['banquet_dinner_price']) ? ($f->setValue($_POST['banquet_dinner_price'])) : (0),
					'room_price_1' => ($_POST['room_price_1']) ? ($f->setValue($_POST['room_price_1'])) : (0),
					'room_price_2' => ($_POST['room_price_2']) ? ($f->setValue($_POST['room_price_2'])) : (0),
					'package_desc' => ($_POST['package_desc']) ? ($f->setValue($_POST['package_desc'])) : ('NULL'), 
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
					<h5>Add New Packages</h5>
				</div>
				<div class="widget-content nopadding">	            				
					<div class="control-group">
						<label class="control-label">Package Name:</label>
						<div class="controls">							
							<input type="text" name="package_name" id="package_name" class="span11 required" value="<?php echo $_POST['package_name'];?>" />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Package Price ($):</label>
						<div class="controls">							
							<input type="text" name="package_price" id="package_price" class="span11 required numeric" value="<?php echo $_POST['package_price'];?>" />
                  </div>                  
					</div>
               
                <div class="control-group">
						<label class="control-label">Package Description:</label>
						<div class="controls">							
							<textarea name="package_desc" class="span11 required ckeditor" id="package_desc"><?php echo $_POST['package_desc'];?></textarea>
                  </div>                  
					</div> 
              
               <!--<div class="control-group">
						<label class="control-label">Room For 2 Night ($):</label>
						<div class="controls">							
							<input type="text" name="room_price_1" id="room_price_1" class="span11 required number_only" value="<?php echo $_POST['room_price_1'];?>" />                 		
                  </div>                  
					</div>  
               
                <div class="control-group">
						<label class="control-label">Room For 3 Night ($):</label>
						<div class="controls">							
							<input type="text" name="room_price_2" id="room_price_2" class="span11 required number_only" value="<?php echo $_POST['room_price_2'];?>" />                 		
                  </div>                  
					</div>  
               
               <div class="control-group">
						<label class="control-label">Youth Activity Price ($):</label>
						<div class="controls">							
							<input type="text" name="youth_activity_price" id="youth_activity_price" class="span11 required number_only" value="<?php echo $_POST['youth_activity_price'];?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Banquet Dinner Price ($):</label>
						<div class="controls">							
							<input type="text" name="banquet_dinner_price" id="banquet_dinner_price" class="span11 required number_only" value="<?php echo $_POST['banquet_dinner_price'];?>" />
                  </div>                  
					</div>  -->                   
           
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
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Packages" class="btn btn-success" />
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
			$package_name = $f->setValue($_POST['package_name']);
			$sql = "SELECT * FROM `".T."` WHERE `package_name`='".$package_name."' AND `mark_for_deleted`='No' AND `reg_pack_id`<>".$Id;
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Package Name "'.$f->getValue($package_name).'" already exist !!!');
			} else {
				
				$data = array(	
					'package_name' => $package_name,
					'package_price' => ($_POST['package_price']) ? ($f->setValue($_POST['package_price'])) : (0),
					/*'no_audult' => ($_POST['no_audult']) ? ($f->setValue($_POST['no_audult'])) : (0),
					'no_child' => ($_POST['no_child']) ? ($f->setValue($_POST['no_child'])) : (0),*/
					'youth_activity_price' => ($_POST['youth_activity_price']) ? ($f->setValue($_POST['youth_activity_price'])) : (0),
					'banquet_dinner_price' => ($_POST['banquet_dinner_price']) ? ($f->setValue($_POST['banquet_dinner_price'])) : (0),
					'room_price_1' => ($_POST['room_price_1']) ? ($f->setValue($_POST['room_price_1'])) : (0),
					'room_price_2' => ($_POST['room_price_2']) ? ($f->setValue($_POST['room_price_2'])) : (0),
					'package_desc' => ($_POST['package_desc']) ? ($f->setValue($_POST['package_desc'])) : ('NULL')
				);
				
				if($_POST['reg_pack_id'] != 1)
				{
					$data['status'] = $f->setValue($_POST['status']);
				}
								
				$db->update(T, $data, "reg_pack_id", $Id);					
				$msg = $f->getHtmlMessage("Record has been successfully updated");
			}
		}	
		
		$sql = "SELECT * FROM `".T."` WHERE `reg_pack_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$text_readonly = "";
		$disabled = "";
		$reg_pack_id = $row['reg_pack_id'];
		if($reg_pack_id == 1)
		{
			$text_readonly = " readonly";
			$disabled = " disabled";
		}								
	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
   	<input type="hidden" name="reg_pack_id" value="<?php echo $reg_pack_id;?>">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Packages</h5>
				</div>
				<div class="widget-content nopadding">	
            				
					<div class="control-group">
						<label class="control-label">Package Name:</label>
						<div class="controls">							
							<input type="text" name="package_name" id="package_name" class="span11 required" value="<?php echo $f->getValue($row['package_name']);?>"<?php echo $text_readonly;?> />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Package Price ($):</label>
						<div class="controls">							
							<input type="text" name="package_price" id="package_price" class="span11 required numeric" value="<?php echo $f->getValue($row['package_price']);?>" />
							
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Package Description:</label>
						<div class="controls">							
							<textarea name="package_desc" class="span11 required ckeditor" id="package_desc"><?php echo $f->getValue($row['package_desc']);?></textarea>
                  </div>                  
					</div>
               <?php 
						if($reg_pack_id == 1)
						{
					?>
               <div class="control-group">
						<label class="control-label">Room For 2 Night ($):</label>
						<div class="controls">							
							<input type="text" name="room_price_1" id="room_price_1" class="span11 required number_only" value="<?php echo $f->getValue($row['room_price_1']);?>" />                 		
                  </div>                  
					</div>  
               
                <div class="control-group">
						<label class="control-label">Room For 3 Night ($):</label>
						<div class="controls">							
							<input type="text" name="room_price_2" id="room_price_2" class="span11 required number_only" value="<?php echo $f->getValue($row['room_price_2']);?>" />                 		
                  </div>                  
					</div>  
               
               <div class="control-group">
						<label class="control-label">Youth Retreat Price ($):</label>
						<div class="controls">							
							<input type="text" name="youth_activity_price" id="youth_activity_price" class="span11 required number_only" value="<?php echo $f->getValue($row['youth_activity_price']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Banquet Dinner Price ($):</label>
						<div class="controls">							
							<input type="text" name="banquet_dinner_price" id="banquet_dinner_price" class="span11 required number_only" value="<?php echo $f->getValue($row['banquet_dinner_price']);?>" />
                  </div>                  
					</div> 
               <?php
						}
               ?>
               <div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required"<?php echo $disabled;?>>
								<option value="Active"<?php if($row['status'] == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($row['status'] == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Packages" class="btn btn-success" />
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
