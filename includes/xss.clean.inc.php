<?php
	if(empty($_SERVER['QUERY_STRING'])==FALSE)
	{
		foreach($_GET as $key => $val)
		{
			$_GET[$key] = $db->escape_string($f->XssClean($val));
		}
	}
?>