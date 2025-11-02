<?php 
	require_once("includes/config.inc.php");

	echo $sql_chart = "SELECT a.`reg_id`, b.`state`, 
						(SELECT COUNT(*) FROM `tbl_reg_profile_info` WHERE `state`=b.`state` AND `status`='Active' AND `mark_for_deleted`='No') AS `count_state`
						FROM `tbl_registration` AS a
						INNER JOIN `tbl_reg_profile_info` AS b ON `a`.`reg_id`=`b`.`reg_id`
						WHERE `a`.`status`='Active' AND `a`.`mark_for_deleted`='No' AND `b`.`state`<>''
						GROUP BY b.`state` ORDER BY count_state DESC";
	$res_chart = $db->get($sql_chart);
					
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "Top Registration"
      },
      data: [
      {
        dataPoints: [
		  	  <?php 
			  	$count = 1;
			  	while($row_chart = $db->fetch_array($res_chart))
				{
			  ?>
		  	  { x: <?php echo $count?>, y: <?php echo $f->getValue($row_chart['count_state']);?>, label: '<?php echo $f->getValue($row_chart['state']);?>'},
			  <?php 
			  		$count++;
				}
			  ?>
        ]
      }
      ]
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
			<div id="chartContainer" style="height: 400px; width: 100%;"></div>
			
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