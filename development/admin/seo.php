<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');	
	define("T","tbl_seo_tkd");
	$index = $_GET['index'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />

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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> SEO Management</a></div>
	</div>
	<!--End-breadcrumbs-->
	<?php 
	if($index == 'List')
	{
		
		if(empty($_GET['msg'])==false)
		{
			require_once('common-msg.php');									
		}	

		$sql = "SELECT * FROM `".T."` ORDER BY `seo_tkd_id` ASC";		
		$res = $db->get($sql);
		$records = $db->num_rows($res);
		
  ?>	
	<?php if(empty($msg) == false){ ?>
	<div align="center"><?php echo $msg;?></div>
	<?php } ?>
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5>List of Pages (<?php echo $records;?>)</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">				
				<table class="table table-bordered <?php //if($records > 0) echo ' data-table';?>" width="100%">
					<thead>
						<tr> 							
							<th width="23%"><div align="left">Page Name</div></th>
							<th width="23%">SEO Title</th>							
							<th width="48%">SEO Description</th>
							<th width="6%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$seo_tkd_id = $row['seo_tkd_id'];											
									if($cnt==0):
										$class = "alterClass1";
										$cnt++;
									else:
										$class = "alterClass2";
										$cnt--;
									endif;
									
									$seo_page_title = $f->getValue($row['seo_page_title']);
									$seo_meta_description = $f->getValue($row['seo_meta_description']);
									
									if(strlen($seo_page_title) > 100) $seo_page_title = substr($seo_page_title, 0, 100)." ...";
									if(strlen($seo_meta_description) > 100) $seo_meta_description = substr($seo_meta_description, 0, 100)." ...";
									
						 ?>
						<tr>							
							<td><?php echo $f->getValue($row['admin_display_title']);?></td>
							<td><?php echo $seo_page_title;?></td>
							<td><?php echo $seo_meta_description;?></td>
							<td>								
								<div align="center"><a href="<?php echo CP;?>?index=Edit&Id=<?php echo $seo_tkd_id;?>" class="btn btn-primary btn-mini">Edit</a> <!--<a href="#myAlert<?php echo $row['seo_tkd_id'];?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a>--></div>
							</td>
						</tr>
						<!--<div id="myAlert<?php echo $row['seo_tkd_id'];?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $seo_tkd_id;?>&lang_name=<?php echo $f->getValue($row['language_name']);?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>-->
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
			$language_name = $f->setValue($_POST['language_name']);
			$seo_language_name = $f->slugify($language_name);
																		
			$sql = "SELECT * FROM `".T."` WHERE `language_name`='".$language_name."'";
			$res = $db->get($sql,__FILE__,__LINE__);
			if($db->num_rows($res) > 0) 
			{
				$msg = $f->getHtmlError('Language Name "'.$f->getValue($language_name).'" already exist !!!');
			} else {							
				$data = array(
					'language_name' => $language_name,	
					'create_date_time' => date('Y-m-d H:i:s')
				);
				
				try
				{
					$db->start_transaction();		
					$db->insert(T,$data);					
					$last_insert_id = $db->last_insert_id();
					$lang_name_lower = $f->slugify($_POST['language_name']);
					require_once("insert_field.php");					
					
					$db->commit();
					$f->Redirect(CP."?index=List&msg=success");
				}
				catch(Exception $e)
				{
					$db->rollback();
					$msg = $f->getHtmlErrorNew($e->getMessage());
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
					<h5>Add New Site Language</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">Image:</label>
						<div class="controls">
							<img src="../phpThumb/phpThumb.php?src=<?php echo $banner_img;?>&amp;w=500" border="0" />
							<input type="hidden" name="old_banner_img" value="<?php echo $row['banner_img'];?>" />
							<div class="br_div">&nbsp;</div>
							<input type="file" name="banner_img" id="banner_img" class="" accept=".png, .jpg, .jpeg" />
							<div class="info-text">Image size should be <b>1600px X 344px</b></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Title:</label>
						<div class="controls">						
							<input type="text" name="banner_title_eng" id="banner_title_eng" class="span11" value="<?php echo $row['banner_title_eng'];?>" />
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
			$seo_page_title = $f->NoEnter($_POST['seo_page_title']);			
			$seo_meta_description = $f->NoEnter($_POST['seo_meta_description']);	
			
			$data = array(							
				'seo_page_title' => $seo_page_title,				
				'seo_meta_description' => $seo_meta_description
			);		
					
			$db->update(T,$data,"seo_tkd_id",$Id);
			$msg = $f->getHtmlMessage("Record has been successfully updated");			
		}
		
		$sql = "SELECT * FROM `".T."` WHERE `seo_tkd_id`=".$Id;
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
					<h5>Modify :: <?php echo $f->getValue($row['admin_display_title']);?></h5>
				</div>
				<div class="widget-content nopadding">
					<div class="control-group">
						<label class="control-label">SEO Page Title:</label>
						<div class="controls">							
							<textarea name="seo_page_title" class="span11 no_enter textarea_height_80" id="seo_page_title"><?php echo $f->getValue($row['seo_page_title']);?></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">SEO Page Description:</label>
						<div class="controls">							
							<textarea name="seo_meta_description" id="seo_meta_description" class="span11 no_enter textarea_height_120"><?php echo $f->getValue($row['seo_meta_description']);?></textarea>
						</div>
					</div>
					
				</div>
				
				<div class="widget-content nopadding">
					<div class="control-group">						
						<div class="controls">
							<input name="btnEdit" id="btnEdit" type="submit" value="Modify" class="btn btn-success" />
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
