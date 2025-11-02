<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	//require_once('../includes/file.upload.inc.php');
	define("T","tbl_registration");
	
	$db->get("CALL UpdatePrimaryMember()", __FILE__, __LINE__); 
		
	/*$sql = "SELECT 
				SUM(`number_of_people_adult`) AS `total_adult`, 
				SUM(`number_of_child_6_17`) AS `6_17`,
				SUM(`number_of_child_0_5`) AS `0_5`,
				SUM(`no_of_people_youth_activities`) AS `youth_activities`,
				SUM(`no_of_dinner_people`) AS `dinner_people`,
				SUM(`no_of_dinner_people_children`) AS `dinner_people_children`,
				FunGetTotalVeg() AS `total_veg`,
				FunGetTotalNonVeg() AS `total_non_veg`, 
				FunNetworkingEvent() AS `total_networking_event`,
				FunNetworkingEventM() AS `total_networking_event_Male`,
				FunNetworkingEventF() AS `total_networking_event_Female` 
				FROM `".T."` WHERE `status`='Active' AND `mark_for_deleted`='No' ";*/		
	$sql = "SELECT 
				SUM(`number_of_people_adult`) AS `total_adult`, 
				SUM(`number_of_child_6_17`) AS `6_17`,
				SUM(`number_of_child_0_5`) AS `0_5`,
								
				FunGetTotalYouthActivities() AS `youth_activities`,
				
				SUM(`no_of_dinner_people`) AS `dinner_people`,
				SUM(`no_of_dinner_people_children`) AS `dinner_people_children`,
				FunGetTotalVeg() AS `total_veg`,
				FunGetTotalNonVeg() AS `total_non_veg`, 
				FunNetworkingEvent() AS `total_networking_event`,
				FunNetworkingEventM() AS `total_networking_event_Male`,
				FunNetworkingEventF() AS `total_networking_event_Female` 
				FROM `".T."` WHERE `status`='Active' AND `mark_for_deleted`='No' ";				 
	$res = $db->get($sql,__FILE__,__LINE__);
	$row = $db->fetch_array($res);
	
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
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Manage Registration Report</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	<form action="<?php echo CP;?>" method="post" name="frm" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>View Report</h5>
				</div>
            
            <div class="widget-content" style="padding-left:50px !important;">
            	<div class="widget-title">
						<h5>Booking Packages Report</h5>
					</div>
               <p></p>
               <?php
						$sql_package = "SELECT a.*,
											 FunGetPackageCount(a.`reg_pack_id`) AS `package_count` 
											 FROM `tbl_registration_packages` AS a WHERE a.`status`='Active' AND a.`mark_for_deleted`='No' ORDER BY a.`display_order_segregate` ASC, a.`display_order` ASC";
                  $res_package = $db->get($sql_package);
                  while($row_package = $db->fetch_array($res_package))
                  {	
					?>
            	<div class="views">
						<b><?php echo $f->getValue($row_package['package_name']);?>:</b> <?php echo $row_package['package_count'];?>
					</div>	
               <?php
						}
					?>
               <p>&nbsp;</p>
            	<div class="widget-title">
						<h5>Registration Report</h5>
					</div>
               <p></p>
            	<div class="views">
						<b>Adult:</b> <?php echo $f->getValue($row['total_adult']);?>
					</div>
               <div class="views">
						<b>Age (6 to 17):</b> <?php echo $f->getValue($row['6_17']);?>
					</div>
               <div class="views">
						<b>Age (0 to 5):</b> <?php echo $f->getValue($row['0_5']);?>
					</div>
               <div class="views">
						<b>Total Youth Activities:</b> <?php echo $f->getValue($row['youth_activities']);?>
					</div>
               <div class="views">
						<b>Total Banquet Dinner:</b> <?php echo $f->getValue($row['dinner_people']);?>
					</div>
               <div class="views">
						<b>Total Banquet Dinner (Children):</b> <?php echo $f->getValue($row['dinner_people_children']);?>
					</div>
               <div class="views">
						<b>Total Veg:</b> <?php echo $f->getValue($row['total_veg']);?>
					</div>
               <div class="views">
						<b>Total Non Veg:</b> <?php echo $f->getValue($row['total_non_veg']);?>
					</div>
               <div class="views">
						<b>Total Networking Event:</b> <?php echo $f->getValue($row['total_networking_event']);?> (Male: <?php echo $f->getValue($row['total_networking_event_Male']);?>, Female: <?php echo $f->getValue($row['total_networking_event_Female']);?>)
					</div>
            	<p>&nbsp;</p>
					<div class="control-group">
						<div class="">								
							<input name="csv_download" id="csv_download" type="button" value="Total Report 1 (CSV) &darr;" class="btn-primary btn"  />&nbsp;&nbsp;&nbsp;
                     <input name="csv_download_2" id="csv_download_2" type="button" value="Total Report 2 (CSV) &darr;" class="btn-primary btn"  />&nbsp;&nbsp;&nbsp;
                     <input name="csv_download_youth_networking_event" id="download_youth_networking_event" type="button" value="Youth Networking Event (CSV) &darr;" class="btn-primary btn"  />&nbsp;&nbsp;&nbsp;
                     <input name="csv_download_youth_activity" id="download_youth_activity" type="button" value="Youth Activities (CSV) &darr;" class="btn-primary btn"  />&nbsp;&nbsp;&nbsp;
                     <input name="zip_download" id="zip_download" type="button" value="Photo Download (Zip) &darr;" class="btn-info btn"  />
						</div>
					</div>
               <p>&nbsp;</p>
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
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 

<script type="text/javascript">
$(document).ready(function(){	
	
	$('#zip_download').click(function() {		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'ZipDownload'},
			dataType: 'html',
			success: function(response) {						
				$('#spinner-div').hide();
				window.location = response;
			}			
		});		
	});
	
	$('#csv_download').click(function() {	
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'TotalCSV'},
			dataType: 'html',
			success: function(response) {						
				$('#spinner-div').hide();
				window.location = response;
			}			
		});		
	});
	
	$('#csv_download_2').click(function() {	
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'TotalCSV_2'},
			dataType: 'html',
			success: function(response) {						
				$('#spinner-div').hide();
				window.location = response;
			}			
		});		
	});
	
	$('#download_youth_networking_event').click(function() {		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'All_Youth_Networking_Event'},
			dataType: 'html',
			success: function(response) {						
				$('#spinner-div').hide();
				window.location = response;
			}			
		});		
	});
	
	
	$('#download_youth_activity').click(function() {		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'All_Youth_Activity'},
			dataType: 'html',
			success: function(response) {						
				$('#spinner-div').hide();
				window.location = response;
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
