<?php
	//m = main file name
     //s = server file name
     //f = file type
     //d = directory
	
	$m = base64_decode($_GET['m']);
	$s = base64_decode($_GET['s']);
	$f = base64_decode($_GET['f']);
	$d = base64_decode($_GET['d']);
	
	if(empty($m) == false)
	{
		$file = $d.'/'.$s;
		header('Content-type: '.$f);
		header('Content-Disposition: attachment; filename="'.$m.'"');
		header("Content-length: ".filesize($file));
		readfile($file);
	}
	else
	{
		 $file = $d.'/'.$s;
		 header("Content-type: application/force-download");
		 header("Content-Transfer-Encoding: Binary");
		 header("Content-length: ".filesize($file));
		 header("Content-disposition: attachment; filename=\"".basename($file)."\"");
		 readfile($file); 
	}
?>