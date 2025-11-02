<?php 
	require_once("includes/config.inc.php");

	$sql_chart = "SELECT a.`reg_id`, b.`state`, 
						(SELECT COUNT(*) FROM `tbl_reg_profile_info` WHERE `state`=b.`state` AND `status`='Active' AND `mark_for_deleted`='No') AS `count_state`
						FROM `tbl_registration` AS a
						INNER JOIN `tbl_reg_profile_info` AS b ON `a`.`reg_id`=`b`.`reg_id`
						WHERE `a`.`status`='Active' AND `a`.`mark_for_deleted`='No' AND `b`.`state`<>''
						GROUP BY b.`state` ORDER BY `count_state` ASC";
	$res_chart = $db->get($sql_chart);
	$i = 0;
	
	$dataPoints = array();
	while($row_chart = $db->fetch_array($res_chart))
	{
		$dataPoints[$i]["label"] = $f->getValue($row_chart['state']);
		$dataPoints[$i]["y"] = $f->getValue($row_chart['count_state']);
		$i++;
	}			
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<script type="text/javascript">
window.onload = function() {  
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Registration Counts by State"
	},
	subtitles: [{
		text: " "
	}],
	data: [{
		type: "pie",
		//yValueFormatString: "#,##0.00\"\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render(); 
}
</script>
</head>
<body>
<!-------------- Header ------------------->
<header class="inner_header">
	<?php include_once('header.php');?>
</header>
<div class="header_mobilenav"></div>

<!------------------- Header end------------------->
<div class="clear"></div>
<!------------------- Slider area------------------->
<!------------------- Slider area------------------->
<section class="inner_banner" style="background-image:url(<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg);">
<img src="<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Registration Statistics</div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
<div class="flexcaption_darkshade"></div>    
</section>
<!------------- Slider area end----------------->

<div class="clear"></div>

<section class="inner_area">
	<div class="container">
		<!--<div class="heading1" align="center">Coming Soon</div>-->
		<div>
			<div id="chartContainer" style="height: 500px; width: 100%;"></div>			
		</div>
	</div>
	<div class="clear"></div>
</section>
<div class="clear"></div>
<script src="<?php echo MAIN_WEBSITE_URL;?>/js/canvasjs.min.js"></script>
<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
</body>
</html>