<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>
<script type="text/javascript">
$(document).ready(function() {
	$('#frmLogin').validate();
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
<!--sidebar-menu-->
<div id="sidebar">
	<?php include_once('admin-left-menu.php');?>
</div>
<!--sidebar-menu--> 

<!--main-container-part-->
<div id="content"> 
	<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> <a href="<?php echo WEBSITE_URL;?>/dashboard.php" title="" class="tip-bottom"><i class="icon-th-large"></i> Home</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
	<div class="row-fluid">
		<div class="widget-box">
			<div class="widget-title bg_lg">
				<h5>Welcome Administrator</h5>
			</div>
			<div class="widget-content" >
				<div class="row-fluid">
					<p align="center">You are logged in from <?php echo $_SERVER['REMOTE_ADDR'];?></p>
					<p align="center">Navigate your site by the clicking of the link from the left navigation menu</p>
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
</body>
</html>
