<?php 
	$current_page = basename(CP);
	
	$seo_sql = "SELECT `seo_page_title`, `seo_meta_keywords`, `seo_meta_description` FROM `tbl_seo_tkd` WHERE `page_basename`='".$current_page."'";
	
	if(empty($seo_sql) == false)
	{	
		$seo_res = $db->get($seo_sql, __FILE__, __LINE__);
		$seo_row = $db->fetch_array($seo_res);
		$db->free_result($seo_res);
	}
	
	if(empty($seo_page_title) == TRUE)
	{
		$seo_page_title = $f->getValue($seo_row[0]);
	}
	if(empty($seo_meta_keywords) == TRUE)
	{
		$seo_meta_keywords = $f->getValue($seo_row[1]);
	}
	if(empty($seo_meta_description) == TRUE)
	{
		$seo_meta_description = $f->NoEnter($f->getValue($seo_row[2]), FALSE);
	}	
	
	$seo_page_title = (empty($seo_page_title) == TRUE) ? $f->getValue($AdminSettings['global_page_title']) : $seo_page_title;
	
	if(empty($seo_meta_keywords) == TRUE && empty($seo_meta_description) == TRUE)
	{
		$seo_meta_keywords = $f->getValue($AdminSettings['global_meta_key']);
		$seo_meta_description = $f->getValue($AdminSettings['global_meta_desc']);
	}	
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<title><?php echo $seo_page_title?></title>
<meta name="description" content="<?php echo $seo_meta_description;?>" />

<link rel="shortcut icon" href="<?php echo MAIN_WEBSITE_URL;?>/images/favicon.ico" type="image/x-icon" />
<link rel="icon" href="<?php echo MAIN_WEBSITE_URL;?>/images/favicon.ico" type="image/x-icon" />
<link rel="canonical" href="<?php echo CANONICAL_URL;?>" />
<meta name="robots" content="index, follow, archive" />
<meta name="language" content="en-us" />
<meta name="rating" content="general" />
<meta name="coverage" content="Worldwide" />
<meta name="distribution" content="global" />
<meta name="revisit-after" content="2 days" />
<meta http-equiv="Cache-control" content="No-Cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta http-equiv="X-UA-Compatible" content="IE=11" />
<meta property="og:description" content="<?php echo $seo_meta_description;?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo CANONICAL_URL;?>" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="366" />
<meta property="og:image:height" content="345" />
<meta property="og:title" content="<?php echo $seo_page_title;?>" />
<meta property="og:image" content="<?php echo MAIN_WEBSITE_URL;?>/<?php echo $WEBSITE_LOGO;?>" />
<meta name="twitter:title" content="<?php echo $seo_page_title;?>" />
<meta name="twitter:image" content="<?php echo MAIN_WEBSITE_URL;?>/<?php echo $WEBSITE_LOGO;?>" /> 
<meta name="twitter:url" content="<?php echo CANONICAL_URL;?>" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="<?php echo $seo_meta_description;?>" />
