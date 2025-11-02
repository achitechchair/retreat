<?php
	// Predefine Constant Variable
	define("TITLE", "NSNA Retreat");
	define("HEADER", "NSNA Retreat");
	define("PAGE_TITLE", "NSNA Retreat");
	define("EXCEL_TITLE", "NSNA Members");
	
	define('HTTP_URL_PROTOCOL', (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) || (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443) || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')) ? 'https://' : 'http://'));
	$wu = HTTP_URL_PROTOCOL.$_SERVER['HTTP_HOST'];
	
	//$canonical_url = $wu.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$canonical_url = $wu.$_SERVER['REQUEST_URI'];
	$canonical_url = str_replace(":///", "://", $canonical_url);
	
	$paging_canon_url = explode("?", $canonical_url);
	$paging_canon_url = $paging_canon_url[0];
	
	if(substr($canonical_url, -1) == "/")
	{
		$canonical_url = substr($canonical_url, 0, strlen($canonical_url)-1);
	}	
	define("CANONICAL_URL", $canonical_url);
		
	$dirname = dirname($_SERVER['SCRIPT_NAME']);
	
	$folder_array = array('/admin', '/fb-login');
	$RootFolder = str_replace($folder_array, "", dirname($_SERVER['SCRIPT_NAME']));
	
	if($dirname == "/") $RootFolder = str_replace("/", "", $RootFolder);	
	$wuadmin = HTTP_URL_PROTOCOL.$_SERVER['HTTP_HOST'].$RootFolder;
	
	if(empty($dirname)==FALSE && $dirname!="/") $wu.= dirname($_SERVER['SCRIPT_NAME']);
	
	define("WEBSITE_URL",$wu);
	define("MAIN_WEBSITE_URL",$wuadmin);
	//define("CP",basename($_SERVER['PHP_SELF']),TRUE);
	define("CP", $wu."/".basename($_SERVER['PHP_SELF']));
	define("QS", $_SERVER['QUERY_STRING']);
	define("FRONT_RPP", 15);
	define("ADMIN_RPP", 25);
			
	define("CAPTCHA_PUBLIC_KEY", "6Lc5No4jAAAAAIq-lE6ZALBItE0-_Y6Hjah9o4QX");
	define("CAPTCHA_PRIVATE_KEY", "6Lc5No4jAAAAAM6McNXLd5ZEnwzbDwlLFBW0OE6h");	
	define("GoogleMapAPI", "AIzaSyDCUNDYxQXw0ECai_xSHxu65r4JKTRMKOs");
	
	define("SITE_LOGO_IMG", "uploads/site-logo-image");
	define("MEDIA", "uploads/media");
	define("HOME_BANNER","uploads/home-banner");
	define("INNER_BANNER","uploads/inner-banner");
	
	define("REG_DOCUMENT", "uploads/reg-document");
	define("SPONSOR_IMG", "uploads/sponsor-image");
	
	define("CANCEL_DATE_FROM", "2024-04-15");
	define("CANCEL_DATE_TO", "2024-08-15");
	define("CANCEL_CHARGE", "300");
	
?>