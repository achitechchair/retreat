<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	define("T","tbl_registration");
	
	if(empty($_GET['ref_reg_id']) == false)
	{
		$_SESSION['this_ref_reg'] = $_GET['ref_reg_id'];
	}	
	if(empty($_SESSION['this_ref_reg']) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registrations.php?index=List");
	
	$sql = "SELECT * FROM `".T."` WHERE `reg_id`='".$_SESSION['this_ref_reg']."'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);	
	
	$admin_room_type = $f->getValue($row['admin_room_type']);
	$admin_room_allotted = $f->getValue($row['admin_room_allotted']);
	$admin_souvenir_distribution = $f->getValue($row['admin_souvenir_distribution']);
	$admin_goodie_bag = $f->getValue($row['admin_goodie_bag']);
	$admin_general_registration = $f->getValue($row['admin_general_registration']);
	$admin_treasury_notes = $f->getValue($row['admin_treasury_notes']);
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{	
			$data_array = array(				
				"admin_room_type" => ($_POST['admin_room_type']) ? ($f->setValue($_POST['admin_room_type'])) : ('NULL'),
				"admin_room_type_2" => ($_POST['admin_room_type_2']) ? ($f->setValue($_POST['admin_room_type_2'])) : ('NULL'),
				"admin_floor_numbers" => ($_POST['admin_floor_numbers']) ? ($f->setValue($_POST['admin_floor_numbers'])) : ('NULL'),
				"admin_room_allotted" => ($_POST['admin_room_allotted']) ? ($f->setValue($_POST['admin_room_allotted'])) : ('NULL'),
				"admin_souvenir_distribution" => $f->setValue($_POST['admin_souvenir_distribution']),
				"admin_goodie_bag" => ($_POST['admin_goodie_bag']) ? ($f->setValue($_POST['admin_goodie_bag'])) : ('NULL'),
				"admin_general_registration" => ($_POST['admin_general_registration']) ? ($f->setValue($_POST['admin_general_registration'])) : ('NULL'),
				"admin_treasury_notes" => ($_POST['admin_treasury_notes']) ? ($f->setValue($_POST['admin_treasury_notes'])) : ('NULL')	
			);		
				
			$db->update(T, $data_array, "reg_id", $_SESSION['this_ref_reg']);
			$f->Redirect(CP."?index=List&msg=update");
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i>Registration Admin Notes</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
		<table class="table" cellpadding="0" cellspacing="0" border="0" style="padding-bottom:0px; margin:0px;">
			<tr>
				<td>					
					<a href="registrations.php?index=List" class="btn btn-inverse"> <i class="icon-step-backward"></i> Back to Registration</a>
				</td>
			</tr>
		</table>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
      <form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Admin Notes</h5>
				</div>
				<div class="widget-content nopadding">
               
             <div class="control-group">
						<label class="control-label">Room Type 1:</label>
						<div class="controls">							
							<select name="admin_room_type" class="span11">
                     	<option value="0">---</option>
                         <?php 
									 $file = file("../includes/roomtype.inc");
									 foreach($file as $val) 
									 {										
										$val = trim($val);  							
								?>
                        <option value="<?php echo $val;?>"<?php if($val == $f->getValue($row['admin_room_type'])) echo ' selected';?>><?php echo $val;?></option>
                        <?php
									 }
								?>	 
                     </select>
                  </div>                  
					</div> 
               
                <div class="control-group">
						<label class="control-label">Room Type 2:</label>
						<div class="controls">							
							<select name="admin_room_type_2" class="span11">
                     	<option value="None">None</option>
                         <?php 
									 $file = file("../includes/roomtype.inc");
									 foreach($file as $val) 
									 {										
										$val = trim($val);  							
								?>
                        <option value="<?php echo $val;?>"<?php if($val == $f->getValue($row['admin_room_type_2'])) echo ' selected';?>><?php echo $val;?></option>
                        <?php
									 }
								?>	 
                     </select>
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">Floor Numbers:</label>
						<div class="controls">							
							<input name="admin_floor_numbers" id="admin_floor_numbers" type="text" class="span11" value="<?php echo $f->getValue($row['admin_floor_numbers']);?>" />
                  </div>                  
					</div>
               
              
               <div class="control-group">
						<label class="control-label">Rooms Allotted:</label>
						<div class="controls">							
							<input name="admin_room_allotted" id="admin_room_allotted" type="text" class="span11" value="<?php echo $f->getValue($row['admin_room_allotted']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Souvenir Distributed?:</label>
						<div class="controls">							
							<select name="admin_souvenir_distribution" class="span11">
                     	<option value="No"<?php if($f->getValue($row['admin_souvenir_distribution']) == 'No') echo ' selected';?>>No</option>
                        <option value="Yes"<?php if($f->getValue($row['admin_souvenir_distribution']) == 'Yes') echo ' selected';?>>Yes</option>                        
                     </select>
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Goodie Bag picked up by:</label>
						<div class="controls">							
							<input name="admin_goodie_bag" id="admin_goodie_bag" type="text" class="span11" value="<?php echo $f->getValue($row['admin_goodie_bag']);?>" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">General Notes:</label>
						<div class="controls">	
                  	<textarea name="admin_general_registration" id="admin_general_registration" placeholder="" class="span11 textarea_height_150"><?php echo $f->getValue($row['admin_general_registration']);?></textarea>
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Treasury Notes:</label>
						<div class="controls">	
                  	<textarea name="admin_treasury_notes" id="admin_treasury_notes" placeholder="" class="span11 textarea_height_150"><?php echo $f->getValue($row['admin_treasury_notes']);?></textarea>
                  </div>                  
					</div>
               
               
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnSubmit" id="btnSubmit" type="submit" value="Update" class="btn btn-success" />
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
</form>	
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<!--<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
-->
<script type="text/javascript">
$(document).ready(function(){	
	$('#country').change(function() {
			
		var country_id_array = $(this).val();
		var country_array = country_id_array.split("|");	
		var country_id = country_array[0]; 
		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'GetState', CountryId: country_id},
			dataType: 'html',
			success: function(data) {					
					$('#spinner-div').hide();							
					$("#st").html(data);				
			}			
		});		
	});	
});
</script>

<div id="spinner-div" class="pt-5">
    <div class="spinner-grow text-danger" role="status">
    		<p>&nbsp;</p>
         <p>&nbsp;</p>
    		<span class="visually-hidden">Loading...</span>
    </div>
</div>
</body>
</html>
