<div id="myAlert" class="modal hide">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" type="button">×</button>
		<h3>Alert</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to Logout?</p>
	</div>
	<div class="modal-footer"> <a class="btn btn-primary" href="<?php echo WEBSITE_URL;?>/logout.php">Confirm</a> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
</div>
<?php
	$DataTableSort = array(
		"default" => array("0", "asc"),		
		"seo.php" => array("0", "asc"),	
		"registration-packages.php" => array("0", "desc")
		
	);
?>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/jquery.ui.custom.js"></script> 
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/jquery.uniform.js"></script>
<?php if(basename($_SERVER['PHP_SELF']) != 'coupon.php'):?>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/bootstrap-datepicker.js"></script> 
<?php endif;?>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/matrix.js"></script> 
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/jquery.validate.js"></script> 
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/additional-methods.js"></script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/matrix.form_validation.js"></script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/jquery.dataTables.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {	
	$('.data-table').dataTable({
		"iDisplayLength": 25,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>',
		"aaSorting": [[ <?php echo (array_key_exists(basename(CP), $DataTableSort) == TRUE) ? $DataTableSort[basename(CP)][0].', "'.$DataTableSort[basename(CP)][1].'"' : $DataTableSort['default'][0].', "'.$DataTableSort['default'][1].'"';?> ]]
	});
});
</script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/js/matrix.tables.js"></script>
