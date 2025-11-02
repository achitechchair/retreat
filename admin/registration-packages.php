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

$(document).ready(function() {	
	$(".numeric").numeric(); 
	$(".number_only").numeric(false);
	
	<?php if($index == "List"):?>	
	$("#btnSeq").click(function() {
	   window.document.frmSQ.submit(); 
	});
	<?php endif; ?>
	
});

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
									
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `reg_pack_id`='".$rowQ['reg_pack_id']."'";
					$RESss = $db->get($SQLss);
			
				}		
				
				if($rowQ['display_order_segregate']!=$_POST["seg$v"] && $_POST["seg$v"] > 0)
				{			
									
					$SQLss = "UPDATE ".T." SET `display_order_segregate`='".$_POST["seg$v"]."' WHERE `reg_pack_id`='".$rowQ['reg_pack_id']."'";
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
			<h5>List of Packages (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 
                  	<th width="3%">Seq.</th>
                     <th width="3%">Seg.</th>
                     <th width="47%"><div align="left">Package Name</div></th>
                     <th width="8%"><div align="center">Package Price</div></th>
                     <th width="6%"><div align="center">Room / N</div></th>
                     <th width="7%"><div align="center">Total People</div></th>
                     <th width="6%"><div align="center">Y.Activity</div></th>
                     <th width="6%"><div align="center">B.Dinner</div></th>                     
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
									$reg_pack_id = $row['reg_pack_id'];	
									
						 ?>
						<tr>
                  	<td><div align="center"><input name="seq<?php echo $reg_pack_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></div></td>
                     <td><div align="center"><input name="seg<?php echo $reg_pack_id;?>" type="text" value="<?php echo $row['display_order_segregate'];?>" size="2"  class="input1 Sequ numeric" /></div></td>
							<td><?php echo ($f->getValue($row['package_name'])) ? ($f->getValue($row['package_name'])) : ('-');?></td>
                     <td><div align="center"><?php echo ($f->getValue($row['package_price'])) ? ("$".$f->getValue($row['package_price'])) : ('---');?></div></td>
                     
                     <td><div align="center"><?php echo ($f->getValue($row['room_for_nights'])) ? ($f->getValue($row['room_for_nights'])) : ('---');?></div></td>
                     <td><div align="center"><?php echo ($f->getValue($row['no_of_people'])) ? ($f->getValue($row['no_of_people'])) : ('---');?></div></td>
                     <td><div align="center"><?php echo ($f->getValue($row['youth_include_people'])) ? ($f->getValue($row['youth_include_people'])) : ('---');?></div></td>
                     <td><div align="center"><?php echo ($f->getValue($row['banquet_dinner_include_people'])) ? ($f->getValue($row['banquet_dinner_include_people'])) : ('---');?></div></td>
                      
                     <td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
                     <td><div align="center">
                     	<a href="<?php echo CP;?>?index=Edit&Id=<?php echo $reg_pack_id;?>" class="btn btn-primary btn-mini">Edit</a>                     	                       
                        <a href="#myAlert<?php echo $reg_pack_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>                       
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
						<td height="50" colspan="10" class="NoRecord">No Record Found</td>
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
			$no_of_people = $_POST['no_of_people'];
			
			if(empty($no_of_people) == TRUE)
			{
				$msg = $f->getHtmlError('Please enter valid Total People');
			}else{
				$package_name = $f->setValue($_POST['package_name']);
				$sql = "SELECT * FROM `".T."` WHERE `package_name`='".$package_name."' AND `mark_for_deleted`='No'";
				$res = $db->get($sql,__FILE__,__LINE__);
				if($db->num_rows($res) > 0) 
				{
					$msg = $f->getHtmlError('Package Name "'.$f->getValue($package_name).'" already exist !!!');
				} else {
					
					$data = array(	
						'package_name' => $package_name,
						'package_title' => ($_POST['package_title']) ? ($f->setValue($_POST['package_title'])) : ('NULL'), 
						'package_price' => ($_POST['package_price']) ? ($f->setValue($_POST['package_price'])) : (0),
						'package_price_old' => ($_POST['package_price_old']) ? ($f->setValue($_POST['package_price_old'])) : (0),	
						'no_of_people' => ($_POST['no_of_people']) ? ($f->setValue($_POST['no_of_people'])) : (0),					
						'youth_include_people' => ($_POST['youth_include_people']) ? ($f->setValue($_POST['youth_include_people'])) : (0),
						'banquet_dinner_include_people' => ($_POST['banquet_dinner_include_people']) ? ($f->setValue($_POST['banquet_dinner_include_people'])) : (0),	
						'room_for_nights' => ($_POST['room_for_nights']) ? ($f->setValue($_POST['room_for_nights'])) : (0),
						'package_desc' => ($_POST['package_desc']) ? ($f->setValue($_POST['package_desc'])) : ('NULL'), 
						'status' => $f->setValue($_POST['status']) 
					);
					
					$db->insert(T,$data);			
					$f->Redirect(CP."?index=List&msg=success");
				}
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
							<input type="text" name="package_name" id="package_name" class="span11 required" value="<?php echo $f->POST_VAL('package_name');?>" />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Box Title:</label>
						<div class="controls">					
                     <textarea name="package_title" class="span11 required ckeditor" id="package_title"><?php echo $f->POST_VAL('package_title');?></textarea>
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Package Price ($):</label>
						<div class="controls">							
							<input type="text" name="package_price" id="package_price" class="span11 required numeric" value="<?php echo $f->POST_VAL('package_price');?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Package Price Old ($):</label>
						<div class="controls">							
							<input type="text" name="package_price_old" id="package_price_old" class="span11 numeric" value="<?php echo $f->POST_VAL('package_price_old');?>" />
                  </div>                  
					</div>
               
                <div class="control-group">
						<label class="control-label">Package Description:</label>
						<div class="controls">							
							<textarea name="package_desc" class="span11 required ckeditor" id="package_desc"><?php echo $f->POST_VAL('package_desc');?></textarea>
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">Total People:</label>
						<div class="controls">							
							<input type="text" name="no_of_people" id="no_of_people" class="span11 required number_only" value="<?php echo $f->POST_VAL('no_of_people');?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Y.Activity Include People:</label>
						<div class="controls">							
							<input type="text" name="youth_include_people" id="youth_include_people" class="span11 required number_only" value="<?php echo $f->POST_VAL('youth_include_people');?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">B.Dinner Include People:</label>
						<div class="controls">							
							<input type="text" name="banquet_dinner_include_people" id="banquet_dinner_include_people" class="span11 required number_only" value="<?php echo $f->POST_VAL('banquet_dinner_include_people');?>" />
                  </div>                  
					</div>   
               
               <div class="control-group">
						<label class="control-label">Room for Night:</label>
						<div class="controls">							
							<input type="text" name="room_for_nights" id="room_for_nights" class="span11 required number_only" value="<?php echo $f->POST_VAL('room_for_nights');?>" />
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
			$no_of_people = $_POST['no_of_people'];
			
			if(empty($no_of_people) == TRUE)
			{
				$msg = $f->getHtmlError('Please enter valid Total People');
			}else{
				$package_name = $f->setValue($_POST['package_name']);
				$sql = "SELECT * FROM `".T."` WHERE `package_name`='".$package_name."' AND `mark_for_deleted`='No' AND `reg_pack_id`<>".$Id;
				$res = $db->get($sql,__FILE__,__LINE__);
				if($db->num_rows($res) > 0) 
				{
					$msg = $f->getHtmlError('Package Name "'.$f->getValue($package_name).'" already exist !!!');
				} else {
					
					$data = array(	
						'package_name' => $package_name,
						'package_title' => ($_POST['package_title']) ? ($f->setValue($_POST['package_title'])) : ('NULL'),
						'package_price' => ($_POST['package_price']) ? ($f->setValue($_POST['package_price'])) : (0),
						'package_price_old' => ($_POST['package_price_old']) ? ($f->setValue($_POST['package_price_old'])) : (0),
						'no_of_people' => ($_POST['no_of_people']) ? ($f->setValue($_POST['no_of_people'])) : (0),
						'youth_include_people' => ($_POST['youth_include_people']) ? ($f->setValue($_POST['youth_include_people'])) : (0),
						'banquet_dinner_include_people' => ($_POST['banquet_dinner_include_people']) ? ($f->setValue($_POST['banquet_dinner_include_people'])) : (0),
						'room_for_nights' => ($_POST['room_for_nights']) ? ($f->setValue($_POST['room_for_nights'])) : (0),	
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
		}	
		
		$sql = "SELECT * FROM `".T."` WHERE `reg_pack_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
			
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
							<input type="text" name="package_name" id="package_name" class="span11 required" value="<?php echo $f->getValue($row['package_name']);?>" />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Box Title:</label>
						<div class="controls">					
                     <textarea name="package_title" class="span11 required ckeditor" id="package_title"><?php echo $f->getValue($row['package_title']);?></textarea>
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Package Price ($):</label>
						<div class="controls">							
							<input type="text" name="package_price" id="package_price" class="span11 required numeric" value="<?php echo $f->getValue($row['package_price']);?>" />
							
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Package Price Old ($):</label>
						<div class="controls">							
							<input type="text" name="package_price_old" id="package_price_old" class="span11 numeric" value="<?php echo $f->getValue($row['package_price_old']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Total People:</label>
						<div class="controls">							
							<input type="text" name="no_of_people" id="no_of_people" class="span11 required number_only" value="<?php echo $f->getValue($row['no_of_people']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Y.Activity Include People:</label>
						<div class="controls">							
							<input type="text" name="youth_include_people" id="youth_include_people" class="span11 required number_only" value="<?php echo $f->getValue($row['youth_include_people']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">B.Dinner Include People:</label>
						<div class="controls">							
							<input type="text" name="banquet_dinner_include_people" id="banquet_dinner_include_people" class="span11 required number_only" value="<?php echo $f->getValue($row['banquet_dinner_include_people']);?>" />
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">Room for Night:</label>
						<div class="controls">							
							<input type="text" name="room_for_nights" id="room_for_nights" class="span11 required number_only" value="<?php echo $f->getValue($row['room_for_nights']);?>" />
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">Package Description:</label>
						<div class="controls">							
							<textarea name="package_desc" class="span11 required ckeditor" id="package_desc"><?php echo $f->getValue($row['package_desc']);?></textarea>
                  </div>                  
					</div>
              
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
