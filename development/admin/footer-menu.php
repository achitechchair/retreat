<?php
	require_once("../includes/config.inc.php");
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_footer_menu");
	$index = $_GET['index'];
	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />
<script type="text/javascript">

$(document).ready(function() {
	$("#btnSeq").click(function() {
	   window.document.frmSQ.submit(); 
	});
	
	$(".numeric").numeric();
});

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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Menu</a></div>
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
				$v = $rowQ['footer_menu_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"])
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `footer_menu_id`='".$rowQ['footer_menu_id']."'";
					$res0 = $db->get($sql0);
					$row0 = $db->fetch_array($res0);
									
					/*$sqlS = "UPDATE ".T." SET `display_order`='".$row0['display_order']."' WHERE `display_order`='".$_POST["seq$v"]."'";
					$resS = $db->get($sqlS);*/
					
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `footer_menu_id`='".$rowQ['footer_menu_id']."'";
					$RESss = $db->get($SQLss);
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if($_GET['action']=='Active' || $_GET['action']=='Inactive') 
		{
			$Id = $_GET['Id'];
			$sql = "UPDATE `".T."` SET `status`='".$_GET['action']."' WHERE `footer_menu_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=status");			
		}
		
		if($_GET['action']=='Bulk') 
		{			
			foreach($_POST['all_check_bx'] as $val):
				$sql = "UPDATE `".T."` SET `status`='".$_GET['status']."' WHERE `footer_menu_id`=".$val;
				$db->get($sql,__FILE__,__LINE__);							
			endforeach;
			
			$f->Redirect(CP."?index=List&msg=status");
			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `footer_menu_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);			
			
			/*$files = '../'.NEWS_IMG.'/'.$_GET['file'];
			$f->DeleteFile($files);		*/	
								
			$f->Redirect(CP."?index=List&msg=del");			
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT a.*, b.`cat_title` FROM `".T."` AS a, `tbl_footer_menu_category` AS b 
				 WHERE a.`footer_menu_cat_id`=b.`footer_menu_cat_id` 
				 AND a.`mark_for_deleted`='No' ORDER BY a.`display_order` ASC, b.`cat_title` ASC";			
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
			<h5>List of Menu (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table width="100%" class="table table-bordered <?php if($records > 0) echo ' data-table';?>">
					<thead>
						<tr>							
							 <th width="3%">Seq.</th>
							 <th width="42%"><div align="left">Title</div></th>
                      <th width="39%"><div align="left">Category Name</div></th>							
                      <th width="8%"><div align="center">Status</div></th>	
							 <th width="8%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$footer_menu_id = $row['footer_menu_id'];
								
						 ?>
						<tr>							
							<td><div align="center"><input name="seq<?php echo $footer_menu_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></div></td>
							
							<td><?php echo $f->getValue($row['menu_title']);?></td>
							<td><?php echo $f->getValue($row['cat_title']);?></td>
                     						
							<td><div align="center"<?php if($row['status'] == 'Inactive') echo ' style="color:#F00;"';?>><?php echo $f->getValue($row['status']);?></div></td>
							<td><div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $footer_menu_id;?>" class="btn btn-primary btn-mini">Edit</a> <a href="#myAlert<?php echo $footer_menu_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a></div></td>
						</tr>
					<div id="myAlert<?php echo $row['footer_menu_id'];?>" class="modal hide">
						<div class="modal-header">
							<button data-dismiss="modal" class="close" type="button">×</button>
							<h3>Alert</h3>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to Delete? </p>
						</div>
						<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $footer_menu_id;?>&file=<?php echo $row['news_image'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
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
			$data = array(	
				'footer_menu_cat_id' => $f->setValue($_POST['footer_menu_cat_id']),		
				'menu_title' => $f->setValue($_POST['menu_title']),
				'status' => $f->setValue($_POST['status']),
				'menu_url' => $f->setValue($_POST['menu_url'])					
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
					<h5>Add New Menu</h5>
				</div>
				<div class="widget-content nopadding">
            	
               <div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="footer_menu_cat_id" id="footer_menu_cat_id" class="span11 required">			
							<?php 
								$sql_cat = "SELECT * FROM `tbl_footer_menu_category` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";
								$res_cat = $db->get($sql_cat);
								while($row_cat = $db->fetch_array($res_cat))
								{
							?>
							<option value="<?php echo $f->getValue($row_cat['footer_menu_cat_id']);?>"<?php if($row_cat['footer_menu_cat_id'] == $_POST['footer_menu_cat_id']) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_cat['cat_title']);?></option>
							<?php 
								} 
								$db->free_result($res_cat);
							?>
							</select>								
						</div>
					</div>
               
					<div class="control-group">
						<label class="control-label">Menu Title:</label>
						<div class="controls">
							<input name="menu_title" type="text" id="menu_title" value="<?php echo $_POST['menu_title'];?>" class="span11 required" />								
						</div>
					</div>
               
               <div class="control-group">
						<label class="control-label">Menu URL:</label>
						<div class="controls">
							<input name="menu_url" type="text" id="menu_url" value="<?php echo $_POST['menu_url'];?>" class="span11 url" />								
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
								<input name="btnCreate" id="btnCreate" type="submit" value="Add Menu" class="btn btn-success" />
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
			$data = array(	
				'footer_menu_cat_id' => $f->setValue($_POST['footer_menu_cat_id']),		
				'menu_title' => $f->setValue($_POST['menu_title']),
				'status' => $f->setValue($_POST['status']),
				'menu_url' => $f->setValue($_POST['menu_url'])					
			);
				
			$db->update(T, $data, "footer_menu_id", $Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");
		}		
		
		$sql = "SELECT * FROM `".T."` WHERE `footer_menu_id`=".$Id;
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
					<h5>Modify Menu</h5>
				</div>
				<div class="widget-content nopadding">
					
					<div class="control-group">
						<label class="control-label">Menu Title:</label>
						<div class="controls">
							<input name="menu_title" type="text" id="menu_title" value="<?php echo $f->getValue($row['menu_title']);?>" class="span11 required" />								
						</div>
					</div>	
					
					<div class="control-group">
						<label class="control-label">Category:</label>
						<div class="controls controls_2" style="margin-bottom:0px;">
							<select name="footer_menu_cat_id" id="footer_menu_cat_id" class="span11 required">			
							<?php 
								$sql_cat = "SELECT * FROM `tbl_footer_menu_category` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";
								$res_cat = $db->get($sql_cat);
								while($row_cat = $db->fetch_array($res_cat))
								{
							?>
							<option value="<?php echo $f->getValue($row_cat['footer_menu_cat_id']);?>"<?php if($row_cat['footer_menu_cat_id'] == $row['footer_menu_cat_id']) echo ' selected';?>>&nbsp;<?php echo $f->getValue($row_cat['cat_title']);?></option>
							<?php 
								} 
								$db->free_result($res_cat);
							?>
							</select>								
						</div>
					</div>
             
               <div class="control-group">
						<label class="control-label">Menu URL:</label>
						<div class="controls">
							<input name="menu_url" type="text" id="menu_url" value="<?php echo $f->getValue($row['menu_url']);?>" class="span11 url" />								
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
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Menu" class="btn btn-success" />
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
