<?php 
	require_once("includes/config.inc.php");

	/*$sql_chart = "SELECT a.`reg_id`, b.`state`, 
						(SELECT COUNT(*) FROM `tbl_reg_profile_info` WHERE `state`=b.`state` AND `status`='Active' AND `mark_for_deleted`='No') AS `count_state`
						FROM `tbl_registration` AS a
						INNER JOIN `tbl_reg_profile_info` AS b ON `a`.`reg_id`=`b`.`reg_id`
						WHERE `a`.`status`='Active' AND `a`.`mark_for_deleted`='No' AND `b`.`state`<>''
						GROUP BY b.`state` ORDER BY `count_state` ASC";*/
						
	$sql_chart = "SELECT a.`reg_id`, b.`state`, 
						(SELECT COUNT(*) FROM `tbl_reg_profile_info` 
							INNER JOIN `tbl_registration` ON `tbl_registration`.`reg_id`=`tbl_reg_profile_info`.`reg_id`
							WHERE `tbl_reg_profile_info`.`state`=b.`state` AND `tbl_reg_profile_info`.`status`='Active' AND `tbl_registration`.`status`='Active' AND `tbl_registration`.`mark_for_deleted`='No') AS `count_state`,
						(SELECT COUNT(*) FROM `tbl_reg_profile_info`
							INNER JOIN `tbl_registration` ON `tbl_registration`.`reg_id`=`tbl_reg_profile_info`.`reg_id` 							
							WHERE (`tbl_reg_profile_info`.`state` IS NULL) AND `tbl_reg_profile_info`.`status`='Active' AND `tbl_registration`.`status`='Active' AND `tbl_registration`.`mark_for_deleted`='No') AS `count_no_state` 	
						FROM `tbl_registration` AS a
						INNER JOIN `tbl_reg_profile_info` AS b ON `a`.`reg_id`=`b`.`reg_id`
						WHERE `a`.`status`='Active' AND `a`.`mark_for_deleted`='No' AND `b`.`state` IS NOT NULL AND `b`.`status`='Active' AND `b`.`mark_for_deleted`='No'
						GROUP BY b.`state` ORDER BY `count_state` ASC";					
	$res_chart = $db->get($sql_chart);
	$i = 0;
	
	$get_i_count = 0;
	$count_no_state = 0;
	$total_count_state = 0;
	$dataPoints = array();
	while($row_chart = $db->fetch_array($res_chart))
	{
		$dataPoints[$i]["label"] = $f->getValue($row_chart['state']);
		$dataPoints[$i]["y"] = $f->getValue($row_chart['count_state']);
		$get_i_count = $i;
		$i++;
		
		$total_count_state = ($total_count_state) + ($row_chart['count_state']);
		
		$count_no_state = (empty($row_chart['count_no_state']) == FALSE) ? ($row_chart['count_no_state']) : (0);
	}
	

	if($count_no_state > 0)
	{
		$get_i_count = $get_i_count + 1;
		$dataPoints[$get_i_count]["label"] = "Rest Of The World";
		$dataPoints[$get_i_count]["y"] = $count_no_state;
	}
	
	//ksort($dataPoints);
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
		text: ""
	},
	subtitles: [{
		text: ""
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
		<div class="heading1 font_35" align="center" style="padding-bottom:10px;">Registration Counts by State<!-- <span class="font_25">(US &amp; Canada)</span>--></div>
      <div class="InfoText1" align="center">(Switch to Landscape mode if you are using a phone)</div>   
      <!--<div class="InfoText2" align="center">Total US &amp; Canada Registrations - <?php //echo $total_count_state;?></div> 
      <div class="InfoText2" align="center">Rest Of The World - <?php //echo $count_no_state;?></div> -->
      <div class="InfoText3" align="center">Total Registrations - <?php echo ($total_count_state) + ($count_no_state);?></div>  
		<div>
			<div id="chartContainer" style="height: 600px; width: 100%;"></div>			
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