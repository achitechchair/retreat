<?php 
	require_once("includes/config.inc.php");
	
	
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo MAIN_WEBSITE_URL;?>/star-rating/star-rating-svg.css">
<link href="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox.js?v=2.1.5"></script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/lightbox/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
$(document).ready(function() {	
	$('.fancybox').fancybox();
});
</script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery.waitforimages.min.js"></script>
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
                <div class="flexcaption_style4">Sponsors</div>
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
		<div class="sponsors_block_area">
      	<?php
				$count = 1;
				$sql_cat = "SELECT `cat_id`, `cat_title`
							  FROM `tbl_sponsors_category` 
							  WHERE `status`='Active' 
							  AND `mark_for_deleted`='No' 
							  AND (SELECT COUNT(*) FROM `tbl_sponsors` WHERE `cat_id`=`tbl_sponsors_category`.`cat_id` AND `status`='Active' AND `mark_for_deleted`='No')>0 
							  ORDER BY `display_order` ASC";
				$res_cat = $db->get($sql_cat);
				$rec_cat = $db->num_rows($res_cat);
				while($row_cat = $db->fetch_array($res_cat))
				{
			?>
      	<div class="heading1"><?php echo $f->getValue($row_cat['cat_title']);?></div>
			<div class="row">
				<?php 
					$sql = "SELECT * FROM `tbl_sponsors` WHERE `cat_id`=".$row_cat['cat_id']." AND `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
					$res = $db->get($sql);
					while($row = $db->fetch_array($res)) 
					{		
						$sponsors_img = SPONSOR_IMG.'/'.$row['sponsors_img'];									
						if(!file_exists($sponsors_img) || empty($row['sponsors_img']) == true)
						{
							$sponsors_img = "images/no_image.png";
						}
						
						if(empty($row['sponsors_link']) == false)
						{
							$sponsors_link = $f->getValue($row['sponsors_link']);
							$target_link = ' target="_blank"';
						}else{
							$sponsors_link = "javascript:;";
							$target_link = '';
						}
				?>
            <div class="col-md-4">
					<div class="sponsors_block">
               	<a href="<?php echo MAIN_WEBSITE_URL;?>/<?php echo $sponsors_img;?>" class="fancybox" data-fancybox-group="gallery" title="<?php echo $f->getValue($row['sponsors_title']);?>"><img src="<?php echo MAIN_WEBSITE_URL;?>/phpThumb/phpThumb.php?src=<?php echo MAIN_WEBSITE_URL;?>/<?php echo $sponsors_img;?>&h=250&far=1&bg=FFFFFF" alt=""></a>
               	<div class="sponsors_block_heading block_title_<?php echo $row_cat['cat_id']?>"><a href="<?php echo $sponsors_link?>"<?php echo $target_link;?>><?php echo $f->getValue($row['sponsors_title']);?></a></div>
						<div class="sponsors_block_heading1 block_desc_<?php echo $row_cat['cat_id']?>"><?php if(empty($f->getValue($row['sponsors_desc'])) == FALSE) echo nl2br($f->getValue($row['sponsors_desc']));?></div>
               </div>
				</div>
				<?php
					}
				?>
			</div>
         <?php				
				if($count < $rec_cat)
				{
			?>	
         <hr>
         <?php
         	}
         ?>
         <script type="text/javascript">
				$(document).ready(function() {
					$('body').waitForImages(function() {
						var ScreenWidth = $(document).width();
						
						if(ScreenWidth >= 992)
							{
								var HdArr_membership_block_<?php echo $row_cat['cat_id']?> = new Array();
								$('.block_title_<?php echo $row_cat['cat_id']?>').each(function() {
									HdArr_membership_block_<?php echo $row_cat['cat_id']?>.push($(this).height());
								});
						
								var MaxWwdHeight_membership_block_<?php echo $row_cat['cat_id']?> = Math.max(...HdArr_membership_block_<?php echo $row_cat['cat_id']?>)
								$('.block_title_<?php echo $row_cat['cat_id']?>').each(function() {
									$(this).height(MaxWwdHeight_membership_block_<?php echo $row_cat['cat_id']?>);
								});
								
								var HdArr_membership_block2_<?php echo $row_cat['cat_id']?> = new Array();
								$('.block_desc_<?php echo $row_cat['cat_id']?>').each(function() {
									HdArr_membership_block2_<?php echo $row_cat['cat_id']?>.push($(this).height());
								});
						
								var MaxWwdHeight_membership_block2_<?php echo $row_cat['cat_id']?> = Math.max(...HdArr_membership_block2_<?php echo $row_cat['cat_id']?>)
								$('.block_desc_<?php echo $row_cat['cat_id']?>').each(function() {
									$(this).height(MaxWwdHeight_membership_block2_<?php echo $row_cat['cat_id']?>);
								});
								
							}	
						});
					});	
			</script>
         <?php
					$count++;
				}
			?>
		</div>
	</div>
	<div class="clear"></div>
</section>

<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>


</body>
</html>