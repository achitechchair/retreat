<?php
	require_once("../includes/config.inc.php");
	//require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	
	define("T","tbl_registration");
	define("T2","tbl_reg_profile_info");
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

<?php 
	if($index == 'List')
	{ 
?>
$(document).ready(function() {
	
	$('#btnSearch').click(function() {
		
		var form_date = $('#form_date').val();
		var to_date = $('#to_date').val();
		var sctext = $("#sctext").val();
		var pay_mode = $("#pay_mode").val();		
	
		if(form_date == "" && to_date == "" && sctext == "" && pay_mode == 'all') 
		{
			alert('Please select at least one search criteria.');
			
		}
		else if(form_date == "" && to_date != "")
		{
			alert('<b>Please select from date.</b>');
			
		} 
		else 
		{
			var url_location = "<?php echo CP;?>?index=List&action=Search";
			if(form_date != '')
			{
				url_location+= '&form_date=' + form_date;
			}
			if(to_date != '')
			{
				url_location+= '&to_date=' + to_date;
			}
			if(sctext != '')
			{
				url_location+= '&sctext=' + sctext;
			}			
			
			if(pay_mode != '' && pay_mode != 'all')
			{							
				url_location+= '&pay_mode=' + pay_mode;
			} 			
				
			window.location.href = url_location;
		}
	});
	
	$('#btnReset').click(function() {
		window.location.href = '<?php echo CP;?>?index=List';									
	});	
});
<?php
	}
?>
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Live Registration</a></div>
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
				$v = $rowQ['reg_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `reg_id`='".$rowQ['reg_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `reg_id`='".$rowQ['reg_id']."'";
					$RESss = $db->get($SQLss);
			
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `reg_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);			
		
			$sql_2 = "UPDATE `tbl_reg_family_info` SET `mark_for_deleted`='Yes' WHERE `reg_id`=".$Id;
			$db->get($sql_2);
			
			$sql_2 = "UPDATE `tbl_reg_members_in_your_party` SET `mark_for_deleted`='Yes' WHERE `reg_id`=".$Id;
			$db->get($sql_2);
			
			$sql_2 = "UPDATE `tbl_reg_profile_info` SET `mark_for_deleted`='Yes' WHERE `reg_id`=".$Id;
			$db->get($sql_2);
		
			$f->Redirect(CP."?index=List&msg=del");	
					
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	
		
		$form_date = $_GET['form_date'];
		$to_date = $_GET['to_date'];	
		$sctext = $_GET['sctext'];	
		$pay_mode = $_GET['pay_mode'];
		
			
		$sql = "SELECT a.*, b.`email_id`, b.`phone` FROM `".T."` AS a, `".T2."` AS b 
				  WHERE a.`reg_id`=b.`reg_id` AND a.`mark_for_deleted`='No' AND a.`status`='Active' ";
		if(empty($form_date) == false)
		{
			$sql.= " AND a.`create_dt`>='".$form_date."' ";
		}
		if(empty($to_date) == false)
		{
			$sql.= " AND a.`create_dt`<='".$to_date."' ";
		}
		if(empty($sctext) == false)
		{			
			$sql.= " AND (b.`email_id` LIKE '".$sctext."%' OR b.`phone` LIKE '".$sctext."%') ";
		}	
		if(empty($pay_mode) == false)
		{
			$sql.= " AND (a.`pay_mode`='".$pay_mode."') ";
		}	  
		$sql.= " ORDER BY a.`reg_id` ASC";
		$_SESSION['sql_download_registration'] = $sql;
		$res = $db->get($sql);
		$records = $db->num_rows($res);		
  ?>
	<table class="table" cellpadding="0" cellspacing="0" border="0">
   	<tr>
			<td width="200">
				<input class="span11" style="width:350px; " type="text" name="sctext" id="sctext" value="<?php echo urldecode($_GET['sctext']);?>" placeholder="Registration No, Email, Phone" />
			 </td>          
          <td width="150">
         	<select name="pay_mode" id="pay_mode" class="" style="width:150px;">
             <option value="all"<?php if($search_by_status == 'all' || $search_by_status == ''){ echo ' selected';}?>>Pay Status</option>
				 <option value="Zelle"<?php if($search_by_status == 'Zelle'){ echo ' selected';}?>>Zelle</option>
             <option value="Check"<?php if($search_by_status == 'Check'){ echo ' selected';}?>>Check</option>
             <option value="PayPal"<?php if($search_by_status == 'PayPal'){ echo ' selected';}?>>PayPal</option>          
            </select>
          </td>
          <td width="100"> 
            <input class="span11" style="width:120px;" type="date" name="form_date" id="form_date" placeholder="From Date" value="<?php echo urldecode($_GET['form_date']);?>" />
			</td>
         <td width="100">
         	<input class="span11" style="width:120px;  " type="date" name="to_date" id="to_date" placeholder="To Date" value="<?php echo urldecode($_GET['to_date']);?>" />
			</td>
         <td>
         	<input type="button" name="btnSearch" id="btnSearch" value="Search" class="btn btn-inverse"  />
				<input type="button" name="btnReset" id="btnReset" value="Reset" class="btn btn-inverse" /> 
            <!--<input type="button" name="download_excel" value="Download &darr;" class="btn btn-danger" onclick="javascript:window.document.location.href='download-registration-excel.php';" />-->
         </td>			
		</tr>
		<!--<tr>
			<td>
				<a href="<?php echo CP.'?index=Add';?>" class="btn btn-inverse"> <i class="icon-plus"></i> Add New </a>				
			</td>
		</tr>-->
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Registration (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 
                  	<th width="1%" style="display:none;"></th>
                  	<th width="6%"><div align="left">Reg No#</div></th>
                     <th width="18%"><div align="left">Email</div></th>
                     <th width="11%"><div align="left">Phone</div></th>   
                     <th width="4%"><div align="center">Persion</div></th>
                     <th width="6%"><div align="center">Price</div></th> 
                     <th width="8%"><div align="center">Pay Mode</div> 
                     <th width="11%"><div align="center">Pay Status</div>
                     <th width="5%"><div align="center">Profile Info</div>
                     <th width="6%"><div align="center">Member Info</div>
                     <th width="5%"><div align="center">Family Info</div>	
                     <th width="7%"><div align="center">Date</div></th>
                     <th width="4%"><div align="center">Notes</div></th>					
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$reg_id = $row['reg_id'];	
									$total_price = $row['total_price'];
									$geteway_charge = $row['geteway_charge'];
									$total_pay = $total_price + $geteway_charge;
									
									$pay_status = $f->getValue($row['pay_status']);
									if($pay_status == 'Partially Paid' || $pay_status == 'Pending' || $pay_status == 'Canceled')
									{
										$str = "<b>".$pay_status."</b>";
										$href = CP."?index=ChangeStatus&Id=".$reg_id;
										$title = "Click here to change status.";
										$color = "#FF0000";
									}else{
										$str = "<b>".$pay_status."</b>";
										//$href = "javascript:;";
										$href = CP."?index=ChangeStatus&Id=".$reg_id;
										$title = "";
										$color = "#336633";
									}
						 ?>
						<tr>
                  	<td style="display:none;"><?php echo $reg_id;?></th>
                  	<td><?php echo $f->getValue($row['ref_number']);?></td> 
							<td><a href="mailto:<?php echo $f->getValue($row['email_id']);?>"><?php echo $f->getValue($row['email_id']);?></a></td>
                     <td><a href="tel:<?php echo $f->getValue($row['phone']);?>"><?php echo $f->getValue($row['phone']);?></a></td>
                     <td><div align="center"><?php echo ($f->getValue($row['total_persion'])) ? ($f->getValue($row['total_persion'])) : ('---');?></div></td>
                     <td><div align="center"><?php echo ($total_pay) ? ($f->getValue("$".$total_pay)) : ('---');?></div></td>
                     <td><div align="center"><?php echo $f->getValue($row['pay_mode']);?></div></td> 
                     <td><div align="center"><a style="color: <?php echo $color;?>;" title="<?php echo $title;?>" href="<?php echo $href;?>" class="<?php echo $con_approve;?>"><?php echo $str;?></a></div></td>
                     <?php 
								if($_SESSION['_admin_user_type'] != 'Treasurer')
								{
							?>
                     <td><div align="center"><a href="registration-profile-info.php?index=List&ref_reg_id=<?php echo $reg_id;?>"><img src="images/Tutorials.gif" width="16" height="16" border="0" /></a></div></td>
                     <td><div align="center"><a href="registration-member-info.php?index=List&ref_reg_id=<?php echo $reg_id;?>"><img src="images/Tutorials.gif" width="16" height="16" border="0" /></a></div></td>
                     <td><div align="center"><a href="registration-family-info.php?index=List&ref_reg_id=<?php echo $reg_id;?>"><img src="images/Tutorials.gif" width="16" height="16" border="0" /></a></div></td>
                     <?php 
								}else{
							?>
                     <td><div align="center">----</div></td>
                     <td><div align="center">----</div></td>
                     <td><div align="center">---</div>
                     <?php
								}
							?>	
                     <td><div align="center"> <?php echo date("m/d/Y", strtotime($row['create_dt']));?></div>
                     <td><div align="center"><a href="registration-admin-notes.php?index=List&ref_reg_id=<?php echo $reg_id;?>"><img src="images/Tutorials.gif" width="16" height="16" border="0" /></a></div></td>
                     <td><div align="center">
                     	<a href="<?php echo CP;?>?index=View&Id=<?php echo $reg_id;?>" class="btn btn-primary btn-mini">View</a>&nbsp;                     	                       
                        <a href="#myAlert<?php echo $reg_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>                        
                        </div>
                     </td>
						</tr>
						<div id="myAlert<?php echo $row['reg_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $reg_id;?>&lang_name=<?php echo $f->getValue($row['language_name']);?>&file=<?php echo $row['pro_img'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="14" class="NoRecord">No Record Found</td>
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
					<h5>Add New Registration</h5>
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
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Registration" class="btn btn-success" />
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
			$package_name = $f->setValue($_POST['package_name']);
			$sql = "SELECT * FROM `".T."` WHERE `package_name`='".$package_name."' AND `mark_for_deleted`='No' AND `reg_id`<>".$Id;
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
				
				if($_POST['reg_id'] != 1)
				{
					$data['status'] = $f->setValue($_POST['status']);
				}
								
				$db->update(T, $data, "reg_id", $Id);					
				$msg = $f->getHtmlMessage("Record has been successfully updated");
			}
		}	
		
		$sql = "SELECT * FROM `".T."` WHERE `reg_id`=".$Id;
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res); 
		
		$text_readonly = "";
		$disabled = "";
		$reg_id = $row['reg_id'];
		if($reg_id == 1)
		{
			$text_readonly = " readonly";
			$disabled = " disabled";
		}								
	?>
	<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
   	<input type="hidden" name="reg_id" value="<?php echo $reg_id;?>">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Registration</h5>
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
						if($reg_id == 1)
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
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Registration" class="btn btn-success" />
								<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</form>
	<?php 
		}elseif($index == 'ChangeStatus'){ 
		
			$Id = $_GET['Id'];
			
			$sql = "SELECT * FROM `".T."` WHERE `reg_id`=".$Id;
			$res = $db->get($sql,__FILE__,__LINE__);
			$row = $db->fetch_array($res); 
			
			$pay_status = $f->getValue($row['pay_status']);
			$pay_mode = $f->getValue($row['pay_mode']);
			
			if(isset($_POST['ChangeStatus'])) 
			{	
				$pay_status_post = $_POST['pay_status'];
				if($pay_status_post == $pay_status)
				{
					$msg = $f->getHtmlError('Please change status');
				}else{
					$data = array(
						'pay_status' => $f->setValue($pay_status_post),
						'admin_note' => ($_POST['admin_note']) ? ($f->setValue($_POST['admin_note'])) : ('NULL'),
						'pay_date' => ($_POST['pay_date']) ? ($f->setValue($_POST['pay_date'])) : ('NULL')					
					);	
					$db->update(T, $data, "reg_id", $Id);
					
					// send email ============================================================================
					
					if($pay_status_post == 'Paid')
					{
						$sql_cus = "SELECT a.`email_id`, b.`first_name`, b.`last_name` , c.`ref_number`
										FROM `tbl_reg_profile_info` AS a
										INNER JOIN `tbl_reg_members_in_your_party` AS b ON a.`reg_id`=b.`reg_id`
										INNER JOIN `tbl_registration` AS c ON a.`reg_id`=c.`reg_id`
										WHERE  a.`reg_id`=".$Id." AND a.`status`='Active'
										GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
						$res_cus = $db->get($sql_cus);
						$row_cus = $db->fetch_array($res_cus);
						$email_id = $f->getValue($row_cus['email_id']);
						$first_name = $f->getValue($row_cus['first_name']);
						$last_name = $f->getValue($row_cus['last_name']);	
						$ref_number = $f->getValue($row_cus['ref_number']);					
						
						$email_message = "<font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;\">";		
						$email_message.= "Dear ".$first_name." ".$last_name.",<br /><br />";	
						$email_message.= "Your payment has been received, and we are delighted to inform you that your registration for the Retreat is now complete.<br /> 
												We can't wait to welcome you to the retreat and create unforgettable memories together. Stay tuned for more updates and exciting announcements leading up to the event. If you have any questions or need further assistance, please reach out to miretreat.registration@achi.org.
												<br /><br />";
						$email_message.= "Best regards,<br />NSNA Michigan Retreat Committee";
						$email_message.= "</font>";
						
						$objMail = new PHPMailer();
						$objMail->SetFrom($f->getValue($AdminSettings['email_from_address']),$f->getValue($AdminSettings['email_from_name']));
						$objMail->Subject = $f->getHTMLDecode("Congratulations! Your Retreat Registration is Confirmed! #".$ref_number);
						if($AdminSettings['smtp']=='Yes'):
							$objMail->IsSMTP();
							$objMail->Host = $f->getValue($AdminSettings['smtp_hostname']);
							$objMail->SMTPAuth = true;
							if($AdminSettings['smtp_type'] == "tls" || $AdminSettings['smtp_type'] == "ssl")
							{
								$objMail->SMTPSecure = $AdminSettings['smtp_type'];
								$objMail->Port = ($AdminSettings['smtp_type'] == 'tls') ? 587 : 465;
							}
							$objMail->Username = $f->getValue($AdminSettings['smtp_username']);
							$objMail->Password = $f->getValue($AdminSettings['smtp_password']);
						endif;
						$objMail->IsHTML(true);
						$objMail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
						$objMail->MsgHTML($f->getHTMLDecode($email_message));
						$objMail->CharSet = 'UTF-8';
						$objMail->AddAddress($email_id, "");	
						$objMail->AddCC($AdminSettings['your_email_address'], $AdminSettings['your_name']);
						$objMail->AddCC($AdminSettings['your_email_address_2'], $AdminSettings['your_name']);
						$objMail->Send();
					}
					
					$f->Redirect(CP."?index=ChangeStatus&Id=".$Id."&msg=update");
				}
			}
			
			if($pay_status == 'Paid')
			{
				$disable = ' disabled';
			}else{
				$disable = "";
			}
			
	?>
   <form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
   	<input type="hidden" name="reg_id" value="<?php echo $reg_id;?>">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Modify Status</h5>
				</div>
				<div class="widget-content nopadding">	            	
               <div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="pay_status" id="pay_status" class="span11 required">
                        <option value="<?php echo $pay_status?>"<?php if($row['pay_status'] == $pay_status) echo ' selected';?>><?php echo $pay_status;?></option>
								<?php if($row['pay_status'] != 'Paid'){?>
                        <option value="Paid"<?php if($row['pay_status'] == 'Paid') echo ' selected';?>>Paid</option>
                        <?php } ?>
                        <?php if($row['pay_status'] != 'Canceled'){?>
								<option value="Canceled"<?php if($row['pay_status'] == 'Canceled') echo ' selected';?>>Canceled</option>
                        <?php } ?>
							</select>
						</div>
					</div>
               <div class="control-group comment_block">
						<label class="control-label">Payment Date:</label>
						<div class="controls">
							<input type="date" name="pay_date" class="span11" id="pay_date" value="<?php echo $f->getValue($row['pay_date']);?>">
						</div>
					</div>
               <div class="control-group comment_block">
						<label class="control-label">Comment:</label>
						<div class="controls">
							<textarea name="admin_note" class="span11 textarea_height_200 required" id="admin_note"><?php echo $f->getValue($row['admin_note']);?></textarea>
						</div>
					</div>
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="ChangeStatus" id="ChangeStatus" type="submit" value="Change Status" class="btn btn-success"<?php echo $disable;?> />
								<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
							</div>
						</div>
					</div>
				</div>
            <?php 
					if($pay_mode == 'Zelle')
					{
				?>
            <div class="widget-content nopadding">
               <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                  <h5>Transaction Details</h5>
               </div>
               <table class="table table-bordered <?php if($records > 0) echo ' data-table';?>" style="width:100%;">
                  <thead>
                     <tr>				
                         <th width="60%"><div align="left">Zelle Referance No.</div></th>								
                         <th width="20%"><div align="left">Amount</div></th>
                         <th width="20%"><div align="left">Date of Transaction</div></th>                         
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                       	$sql_pay_info = "SELECT * FROM `tbl_reg_zelle_transaction` WHERE `reg_id`=".$Id;
								$res_pay_info = $db->get($sql_pay_info);
								$rec_pay_info = $db->num_rows($res_pay_info);
                        if($rec_pay_info > 0)
                        {
                           	while($row_pay_info = $db->fetch_array($res_pay_info))
                           {
                     ?>
                     <tr>
                        <td><?php echo $f->getValue($row_pay_info['refno']);?></td>
                        <td>$<?php echo $f->getValue($row_pay_info['amount']);?></td>
                        <td><?php echo date("m/d/Y", strtotime($f->getValue($row_pay_info['dated'])));?></td>
                       
                     </tr>
                     
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
            </div>
				<?php 
					}
				?>	
			</div>
		</div>
	</form>
   
   <?php 
	}else{
		
		$Id = $_GET['Id'];					
		
		$sql = "SELECT a.*, b.`first_name`, b.`last_name` FROM ".T." AS a, `tbl_reg_members_in_your_party` AS b  
				 WHERE a.`reg_id`=b.`reg_id` AND a.`reg_id`='".$Id."' 
				 GROUP BY b.`reg_id` ORDER BY b.`reg_id` ASC";
		$res = $db->get($sql,__FILE__,__LINE__);
		$row = $db->fetch_array($res);
		
		$total_price = $row['total_price'];
		$geteway_charge = $row['geteway_charge'];
		$total_pay = $total_price + $geteway_charge;
		
		$first_name = $f->getValue($row['first_name']);
		$last_name = $f->getValue($row['last_name']);
		
	?>	
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>View Registration Information</h5>
				</div>
            
            <div class="widget-content" style="padding-left:50px !important;">	
            	<div class="views">
						<b>Registration Number#:</b> <?php echo $f->getValue($row['ref_number']);?>
					</div>
            	<div class="views">
						<b>Adult:</b> <?php echo $f->getValue($row['number_of_people_adult']);?>
					</div>
               <?php 
						if($row['number_of_child_6_17'] > 0)
						{
					?>
               <div class="views">
						<b>Children (ages between 6 and 17):</b> <?php echo $f->getValue($row['number_of_child_6_17']);?>
					</div>
               <?php 
						}
					?>
               <?php 
						if($row['number_of_child_0_5'] > 0)
						{
					?>	
               <div class="views">
						<b>Children (ages 5 and below):</b> <?php echo $f->getValue($row['number_of_child_0_5']);?>
					</div>
               <?php
						}
					?>	
					<div class="views">
						<b>&nbsp;</b>                    
						 <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" >
							<?php
                        $sql_pack = "SELECT * FROM `tbl_registered_package` WHERE `reg_id`=".$Id;
                        $res_pack = $db->get($sql_pack);
                        while($row_pack = $db->fetch_array($res_pack))
                        {
                     ?>
                     <tr id="YouthActivity">
                        <td width="90%" align="left" valign="middle" height="30" style="padding-left:10px;"><?php echo $f->getValue($row_pack['pack_name']);?></td>
                        <td width="10%" align="center" valign="middle" id="">$<?php echo $f->getValue($row_pack['pack_price']);?></td>
                     </tr>
                     <?php 
                        }
                     ?>	
                      <?php 
                        if($row['youth_activity_price'] > 0)
                        {
                     ?>
                    <tr id="YouthActivity">
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px;">Activities for <?php echo $f->getValue($row['no_of_people_youth_extra']);?> Youth</td>
                        <td width="10%" align="center" valign="middle">$<?php echo round($row['youth_activity_price']);?></td>
                     </tr>
                     <?php 
                        }
                     ?>	
                     <?php 
                        if($row['banquet_dinner_price'] > 0)
                        {
                     ?>
                     <tr id="BanquetDinner">
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px;"><?php echo $f->getValue($row['no_of_dinner_people_extra']);?> Banquet Dinner</td>
                         <td width="10%" align="center" valign="middle">$<?php echo round($row['banquet_dinner_price']);?></td>
                     </tr>
                     <?php
                        }
                     ?>	
                     <?php 
                        if($row['youth_networking_event_total_price'] > 0)
                        {
                     ?>
                     <tr>
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px;"><?php echo $row['youth_networking_event_total_people'];?> Youth Networking Event</td>                              
                        <td width="10%" align="center" valign="middle">$<?php echo round($row['youth_networking_event_total_price']);?></td>
                     </tr>
                     <?php
                        }
                     ?>	
                     
                     <?php 
								$pay_mode = $row['pay_mode'];
								if($pay_mode == 'Zelle' || $pay_mode == 'Check')
								{
							?>
                                                   
                     <tr>
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px; font-weight:600;">Total Cost Paid By <?php echo $pay_mode;?></td>                              
                        <td width="10%" align="center" valign="middle"><b>$<?php echo round($row['total_price']);?></b></td>
                     </tr>
                     <?php
								}
							?>	
                     
                     <?php 
								if($pay_mode == 'PayPal')
								{
									$total_price = $row['total_price'];
									//$geteway_charge = $total_price * (3/100);
									$paypal_amount = $total_price + round($row['geteway_charge']);
                     ?>
                     <tr>
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px;">Geteway Charge (3%)</td>                                 
                        <td width="10%" align="center" valign="middle">$<?php echo round($row['geteway_charge']);?></td>
                     </tr>  
                     <tr>
                        <td width="90%" height="30" align="left" valign="middle" style="padding-left:10px; font-weight:600;">Total Cost Paid By PayPal</td>                                 
                        <td width="10%" align="center" valign="middle"><b>$<?php echo round($paypal_amount);?></b></td>
                     </tr>                         
                     <?php 
								}
							?>	
                  </table>
					</div>
               <div class="views">&nbsp;</div>
               <div class="views">
						<b>Name:</b> <?php echo $first_name." ".$last_name;?>			
					</div>
					<div class="views">
						<b>Payment Status:</b> <?php echo $f->getValue($row['pay_status']);?>				
					</div>
                <?php
						if(empty($row['pay_date']) == FALSE)
						{
					?>
               <div class="views">
						<b>Payment Date:</b> <?php echo date("m/d/Y", strtotime($row['pay_date']));?>	
					</div>
               <?php 
						}
					?>	
               <?php
						if(empty($row['transaction_id']) == FALSE)
						{
					?>
					<div class="views">
						<b>Transaction Id:</b> <?php echo $f->getValue($row['transaction_id']);?>
					</div>
					<?php
						}
               ?>             
					<div class="views">
						<b>Submitted At:</b> <?php echo date("m/d/Y h:i A", strtotime($row['create_dt']));?>	
					</div>	
					
					<div class="control-group">
						<div class="">								
							<input type="button" value="Cancel / Back" class="btn btn-warning" onclick="javascript:window.document.location.href='<?php echo CP.'?index=List';?>';" />
						</div>
					</div>
				
     	   </div>
      </div>         
				
	</div>
   <?php
	}
	?>
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
