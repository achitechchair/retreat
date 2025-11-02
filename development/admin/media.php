<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	
	$MediaIcons = array(
		"doc" => "m_icon_doc",
		"docx" => "m_icon_doc",
		"xls" => "m_icon_excel",
		"xlsx" => "m_icon_excel",
		"file" => "m_icon_file",
		"jpg" => "m_icon_image",
		"jpeg" => "m_icon_image",
		"png" => "m_icon_image",
		"gif" => "m_icon_image",
		"pdf" => "m_icon_pdf",
		"ppt" => "m_icon_powerpoint",
		"pptx" => "m_icon_powerpoint",
		"txt" => "m_icon_text",
		"zip" => "m_icon_zip"
	);
	
	if(empty($_GET['action'])==false && $_GET['action']=='Delete')
	{
		$Id = $_GET['Id'];
		
		$FilePath = "../".MEDIA."/".base64_decode($_GET['file']);
		
		$sql = "DELETE FROM `tbl_media` WHERE `media_id`='".$Id."'";
		$db->get($sql, __FILE__, __LINE__);
					
		$f->DeleteFile($FilePath);										
		$f->Redirect(CP."?msg=del");
	}
	
	if(empty($_GET['msg'])==false)
	{
		require_once('common-msg.php');											
	}	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<link href="<?php echo MAIN_WEBSITE_URL;?>/css/uploadfile.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var PullUploadFormJs = "<?php echo MAIN_WEBSITE_URL?>/admin/jupload/jquery.form.js"
</script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery.uploadfile.js"></script>
<script type="text/javascript">
$(document).ready(function() {	
		
	$("#fileuploader").uploadFile({
		url:"upload_media.php",
		multiple:true,
		dragDrop:true,
		/*dragDropStr: "<span><b>&nbsp;Drag &amp; Drop Files</b></span>",*/
		fileName:"myfile",
		maxFileCount:10,
		/*maxFileSize:100*1024*/
		afterUploadAll:function(obj)
		{
		    //alert('Upload complete');
		    window.location.href = '<?php echo CP.'?msg=upload';?>';
		}
	});
	
	$('.MediaFullPath').focus(function() {
		$(this).select();
	});
	
	
	$('.ClassHand').click(function() {
		var MediaCtrl = $(this).attr('alt');
		MediaCtrl = MediaCtrl.replace("Copy", "");
		MediaCtrl = "Media" + MediaCtrl;
		//alert(MediaCtrl);
		$('#' + MediaCtrl).select();
		document.execCommand('copy');
	});
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Media</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-content">
					<div class="control-group">
						<div class="controls">
							<div id="fileuploader">Upload</div>
							<div class="clear"></div>							
						</div>	
					</div>
				</div>
				<div class="widget-box">
				<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
					<h5>List of Media Files (<?php echo $records;?>)</h5>
				</div>
		<?php
				$sql = "SELECT * FROM `tbl_media`";
				if(empty($_GET['action'])==false && $_GET['action']=='Search')
				{
					$txtSearch = urldecode($_GET['txtSearch']);
					$sql.= " WHERE `media_fname` LIKE '".$txtSearch."%'";	
				}
				$sql.= " ORDER BY `create_date_time` DESC";
				$res = $db->get($sql, __FILE__, __LINE__);
				//$res = $db->pagination($sql,RPP,$_GET['page']);
				$records = $db->num_rows($res);
		?>
		<div class="widget-content nopadding">
			<form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSQ" id="frmSQ">
			<input type="hidden" name="btnseq" value="ok">	
				<table class="table table-bordered <?php if($records > 0) echo ' data-table';?>">
					<thead>
						<tr>							
							 <th width="4%">&nbsp;</th>
							 <th width="18%">File Name</th>
							 <th width="25%" height="22" class="tblColHeader" align="center">File Path</th>							
							 <th width="15%" height="22" class="tblColHeader">&nbsp;Size</th>
							 <th width="5%" height="22" class="tblColHeader">&nbsp;Width</th>
							 <th width="5%" height="22" class="tblColHeader" align="center">Height</th> 
							 <th width="10%" height="22" class="tblColHeader" align="center">Upload Date</th>
							 <th width="10%" height="22" align="center" class="tblColHeader">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($records > 0) 
							{
								while($row = $db->fetch_array($res)) 
								{
									$media_id = $row['media_id'];																			
									if($cnt==0):
										$class = "alterClass1Media";
										$cnt++;
									else:
										$class = "alterClass2Media";
										$cnt--;
									endif;
									
									$FilePath = MAIN_WEBSITE_URL."/".MEDIA."/".$row['media_folder']."/".$row['media_fname'];
									
									$media_fname = $f->getValue($row['media_fname']);
									
									$MFN_Array = explode(".", $media_fname);
									$MF_Ext = end($MFN_Array);
									
									if(array_key_exists($MF_Ext, $MediaIcons) == TRUE)
									{
										$icon = $MediaIcons[$MF_Ext];
									}
									else
									{
										$icon = $MediaIcons['file'];	
									}
									
									if($MF_Ext == 'jpg' || $MF_Ext == 'jpeg' || $MF_Ext == 'gif' || $MF_Ext == 'png')
									{
										$media_fname = '<a href="'.$FilePath.'" class="lightbox_trigger"><u>'.$media_fname.'</u></a>';
									}																	
									
						 ?>
						<tr>
							<td valign="middle" class="TdLeftPad"><img src="<?php echo WEBSITE_URL;?>/images/media_icon/<?php echo $icon;?>.png" width="16" height="16" border="0" /></td>
							<td valign="middle" class="TdLeftPad"><?php echo $media_fname;?></td>
							<td valign="middle" class="TdLeftPad"><input type="text" name="Media<?php echo $media_id;?>" id="Media<?php echo $media_id;?>" value="<?php echo $FilePath;?>" readonly class="MediaFullPath" /></td>
							<td align="center" valign="middle"><div align="center"><?php echo $f->getSize($row['media_fsize']);?></div></td>
							<td align="center" valign="middle"><?php echo ($row['media_width'] > 0) ? $row['media_width'] : "&nbsp;";?></td>
							<td align="center" valign="middle"><?php echo ($row['media_height'] > 0) ? $row['media_height'] : "&nbsp;";?></td>
							<td align="center" valign="middle"><div align="center"><?php echo date("m/d/Y", strtotime($row['create_date_time']));?></div></td>
							<td><div align="center">
								<img title="Copy File Path" alt="Copy<?php echo $media_id;?>" src="images/icon_copy.png" width="16" height="16" border="0" class="ClassHand" />
								<a title="Download" href="download.php?m=<?php echo base64_encode($row['media_fname']);?>&s=<?php echo base64_encode($row['media_fname']);?>&f=<?php echo base64_encode($row['media_ftype']);?>&d=<?php echo base64_encode("../".MEDIA."/".$row['media_folder']);?>"><img src="images/icon_download.png" width="16" height="16" border="0" /></a>
								<a href="#myAlert<?php echo $media_id;?>" data-toggle="modal" class="btn btn-danger btn-mini">Delete</a></div>
							</td>
						</tr>
						<div id="myAlert<?php echo $media_id;?>" class="modal hide">
							<div class="modal-header">
								<button data-dismiss="modal" class="close" type="button">×</button>
								<h3>Alert</h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to Delete? </p>
							</div>
							<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo CP;?>?index=List&action=Delete&Id=<?php echo $media_id;?>&file=<?php echo base64_encode($row['media_folder']."/".$row['media_fname']);?>">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
						</div>
					<?php 
							}
						} else { 
					?>
					<tr>
						<td height="50" colspan="8" class="NoRecord">No Record Found</td>
					</tr>
					<?php 	
						}						 
					?>
						</tbody>
					
				</table>
			</form>
		</div>
	</div>
				
			</div>
		</div>		
	
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/select2.min.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/matrix.tables.js"></script>
</body>
</html>
