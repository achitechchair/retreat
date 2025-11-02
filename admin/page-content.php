<?php
	require_once("../includes/config.inc.php");	
	require_once('../includes/file.upload.inc.php');	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_static_page_content");
	$index = $_GET['index'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />
<script type="text/javascript">
<?php //if($index == "List"):?>
$(document).ready(function() {	
	/*$("#btnSeq").click(function() {
	   window.document.frmSQ.submit(); 
	});
	$(".numeric").numeric(false); */
});
<?php //endif; ?>
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Page Content</a></div>
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
				$v = $rowQ['page_id'];
				//echo $_POST["seq$v"];	exit();		
				if($rowQ['display_order']!=$_POST["seq$v"] && $_POST["seq$v"] > 0)
				{
					//echo $rowQ['prod_name'];						
					$sql0 = "SELECT * FROM ".T." WHERE `page_id`='".$rowQ['page_id']."'";
					$res0 = $db->get($sql0, __FILE__, __LINE__);
					$row0 = $db->fetch_array($res0);
									
					$sqlS = "UPDATE ".T." SET `display_order`='".$row0['display_order']."' WHERE `display_order`='".$_POST["seq$v"]."'";
					$resS = $db->get($sqlS, __FILE__, __LINE__);
					
					$SQLss = "UPDATE ".T." SET `display_order`='".$_POST["seq$v"]."' WHERE `page_id`='".$rowQ['page_id']."'";
					$RESss = $db->get($SQLss, __FILE__, __LINE__);
				}				
			}
			
			$f->Redirect(CP."?index=List&msg=order");			
		}
		
		if(empty($_GET['action'])==false && ($_GET['action']=='Active' || $_GET['action']=='Inactive')) 
		{
			$Id = $_GET['Id'];
			$sql = "UPDATE `".T."` SET `status`='".$_GET['action']."' WHERE `page_id`=".$Id;
			$db->get($sql,__FILE__,__LINE__);
			$f->Redirect(CP."?index=List&msg=status");			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Bulk') 
		{			
			foreach($_POST['all_check_bx'] as $val):
				$sql = "UPDATE `".T."` SET `status`='".$_GET['status']."' WHERE `page_id`=".$val;
				$db->get($sql,__FILE__,__LINE__);							
			endforeach;
			
			$f->Redirect(CP."?index=List&msg=status");
			
		}
		
		if(empty($_GET['action'])==false && $_GET['action']=='Delete')
		{
			$Id = $_GET['Id'];
			
			$sql_sub = "SELECT * FROM `tbl_product` WHERE `page_id`='".$Id."'";			
			$res_subt = $db->get($sql_sub);
			$rec_sub = $db->num_rows($res_subt);
					
			if($rec_sub > 0)
			{
				$msg = $f->getHtmlError("Record exist, Artist cannot be deleted");
			}
			else
			{				
				/*$file1 = '../'.ARTIST_IMG.'/'.$_GET['file'];
				$f->DeleteFile($file1);*/
				
				$sql = "UPDATE ".T." SET `mark_for_deleted`='Yes' WHERE `page_id`=".$Id;
				$db->get($sql,__FILE__,__LINE__);
													
				$f->Redirect(CP."?index=List&msg=del");	
			}					
		
		}
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` ORDER BY `page_id` ASC";		
		$res = $db->get($sql, __FILE__, __LINE__);
		$records = $db->num_rows($res);
		
  ?>
	<table class="table" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<!--<a href="<?php echo CP.'?index=Add';?>" class="btn btn-inverse"> <i class="icon-plus"></i> Add New </a>-->
				<?php 
					/*if($records > 0) 
					{*/
				?>
				<!--<input type="button" class="btn btn-inverse" name="btnSeq" id="btnSeq" value="Change Seq. Order" />			-->	
				<?php //} ?>
			</td>
		</tr>
	</table>
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Page Content (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php //if($records > 0) echo ' data-table';?>">
					<thead>
						<tr>							
							<!-- <th width="2%">Seq.</th>-->							
							 <th width="90%"><div align="left">Title</div></th>												
							 <th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$page_id = $row['page_id'];						
									
						 ?>
						<tr>							
							<!--<td><input name="seq<?php echo $page_id;?>" type="text" value="<?php echo $row['display_order'];?>" size="2"  class="input1 Sequ numeric" /></td>-->
							<td><?php echo $f->getValue($row['page_title']);?></td>	
							<td><div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $page_id;?>" class="btn btn-primary btn-mini">Edit</a></div></td>
						</tr>
						<div id="myAlert<?php echo $row['page_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $page_id;?>&file=<?php echo $row['cat_image'];?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="2" class="NoRecord">No Record Found</td>
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
					
		if(isset($_POST['btnEdit'])) 
		{	
			
			$data = array(				
				/*'page_title' => $f->setValue($_POST['page_title']),*/	
				'page_content' => $f->setValue($_POST['page_content']),						
				'seo_page_title' => $f->NoEnter($_POST['seo_page_title']),
				'seo_meta_desc' => $f->NoEnter($_POST['seo_meta_desc'])					
				
			);
					
			$db->update(T, $data, "page_id", $Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");
		}		
		
		$sql = "SELECT * FROM `".T."` WHERE `page_id`=".$Id;
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
					<h5>Modify Page Content - <?php echo $f->getValue($row['page_title']);?></h5>
				</div>
				<div class="widget-content nopadding">								
					
					<!--<div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">
							<input name="page_title" type="text" id="page_title" value="<?php echo $f->getValue($row['page_title']);?>" class="span11 required" />								
						</div>
					</div>	-->				
					<div class="control-group">
						<label class="control-label">Content Text:</label>
						<div class="controls"> 
							<textarea name="page_content" id="page_content" class="span11 required ckeditor"><?php echo $f->getValue($row['page_content']);?></textarea>	
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">SEO Page Title:</label>
						<div class="controls">							
							<input name="seo_page_title" type="text" id="seo_page_title" value="<?php echo $f->getValue($row['seo_page_title']);?>" class="span11" />
						</div>
					</div>					
					<div class="control-group">
						<label class="control-label">SEO Page Description:</label>
						<div class="controls">							
							<textarea name="seo_meta_desc" id="seo_meta_desc" class="span11 no_enter textarea_height_120"><?php echo $f->getValue($row['seo_meta_desc']);?></textarea>
						</div>
					</div>
					
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnEdit" id="btnEdit" type="submit" value="Modify Page Content" class="btn btn-success" />
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
