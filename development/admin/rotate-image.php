<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	
	$ImageFile = $_GET['ImageFile'];
	$ReturnURL = $_GET['ReturnURL'];
	$Rotate = $_GET['Rotate'];
	
	if(empty($Rotate) == TRUE) $Rotate = 90;
	
	$image_info = getimagesize($ImageFile);
	
	if($image_info['mime'] == 'image/jpeg')
	{
		$source_image = imagecreatefromjpeg($ImageFile);
	}
	
	if($image_info['mime'] == 'image/gif') 
	{
		$source_image = imagecreatefromgif($ImageFile);
	}
	
	if($image_info['mime'] == 'image/png')
	{
		$source_image = imagecreatefrompng($ImageFile);
	}
	
	$image = imagerotate($source_image, $Rotate, 0);
	
	if($image_info['mime'] == 'image/jpeg')
	{
		imagejpeg($image, $ImageFile, 90);
	}
	
	if($image_info['mime'] == 'image/gif') 
	{
		imagegif($image, $ImageFile);
	}
	
	if($image_info['mime'] == 'image/png')
	{
		imagepng($image, $ImageFile, 9);
	}
	
	//$_SESSION['location_reload'] = '1';
	//$f->Redirect($ReturnURL.'?msg=rotate');
?>
