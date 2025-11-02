<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	
	$AutoIdPrefix = $_GET['AutoIdPrefix'];
	$Id = $_GET['Id'];
	
	switch($AutoIdPrefix)
	{
		case "AS":
			$TableName = "tbl_app_admin_support_staff_attached";
			$PkId = "app_admin_support_staff_id";
			$ZipFileName = "APP_Administration_Support_Services.zip";	
			
			$FileNameArr = array(
				1 => "School Leaving Certificate Class X",
				2 => "Graduate Post Graduate Certificate",
				3 => "Professional Degree Diploma Certificate",
				4 => "Experience Certificate",
				5 => "Internship Completion Certificate",																
				6 => "PAN ADHAR Passport Voter ID Card"
			);
			
			break;
		case "CO":
			$TableName = "tbl_app_consultant_attached";
			$PkId = "app_consultant_id";
			$ZipFileName = "APP_Medical_Consultants.zip";
			
			$FileNameArr = array(
				1 => "MBBS Certificate",
				2 => "MD MS DNB Certificate",
				3 => "MD MS DNB Pass Out Documents",
				4 => "DM MCh DNB Super Specialty Certificate",
				5 => "DM MCh DNB Super Specialty Pass Out Doc",
				6 => "Medical Council Registration Certificate",
				7 => "Experience Certificate",
				8 => "PAN ADHAR Passport Voter ID Card",
				9 => "Any Other Certificates"
			); 
			
			break;
		case "FE":
			$TableName = "tbl_app_fellowship_attached";
			$PkId = "app_fellowship_id";
			$ZipFileName = "App_Fellowship.zip";
			
			$FileNameArr = array(
				1 => "MBBS Certificate",
				2 => "MD MS DNB Certificate",
				3 => "MD MS DNB Pass Out Documents",
				4 => "DM MCh DNB Super Specialty Certificate",
				5 => "DM MCh DNB Super Specialty Pass Out Doc",
				6 => "Medical Council Registration Certificate",
				7 => "Experience Certificate",
				8 => "PAN ADHAR Passport Voter ID Card",
				9 => "Any Other Certificates"
			);
			
			break;
		case "MO":
			$TableName = " tbl_app_mo_registrar_attached";
			$PkId = "app_mo_registrar_id";
			$ZipFileName = "APP_Medical_Officer_Registrar.zip";
			
			$FileNameArr = array(
				1 => "MBBS Certificate",
				2 => "MD MS DNB Certificate",
				3 => "MD MS DNB Pass Out Documents",
				4 => "DM MCh DNB Super Specialty Certificate",
				5 => "DM MCh DNB Super Specialty Pass Out Doc",
				6 => "Medical Council Registration Certificate",
				7 => "Experience Certificate",
				8 => "PAN ADHAR Passport Voter ID Card",
				9 => "Any Other Certificates"
			);
			
			break;
		case "NU":
			$TableName = "tbl_app_nursing_attached";
			$PkId = "app_nursing_id";
			$ZipFileName = "APP_Nursing.zip";
			
			$FileNameArr = array(
				1 => "School Leaving Certificate Class X",
				2 => "Graduate Post Graduate Certificate",
				3 => "Professional Degree Diploma Certificate",
				4 => "Nursing Council Registration Certificate",
				5 => "Experience Certificate",	
				6 => "Course Completion Certificate",															
				7 => "PAN ADHAR Passport Voter ID Card"
			); 
			
			break;
		case "SO":
			$TableName = "tbl_app_scientific_technologist_attached";
			$PkId = "app_scientific_officer_and_technologist_id";
			$ZipFileName = "APP_Scientific_Officer_Technologist.zip";
			
			$FileNameArr = array(
				1 => "School Leaving Certificate Class X",
				2 => "Graduate Post Graduate Certificate",
				3 => "Professional Degree Diploma Certificate",																
				4 => "Experience Certificate",	
				5 => "Internship Completion Certificate",																														
				6 => "PAN ADHAR Passport Voter ID Card"
			);
			
			break;
	}
	
	$sql = "SELECT * FROM `".$TableName."` WHERE `".$PkId."`='".$Id."'";
	$res = $db->get($sql, __FILE__, __LINE__);
	$row = $db->fetch_array($res);
	$db->free_result($res);
	
	for($k=1; $k<=count($FileNameArr); $k++)
	{
		if(empty($row['attached_'.$k.'_file']) == false)
		{													
			$files[$f->RenameFile($row['attached_'.$k.'_file'], str_replace(" ", "_", $FileNameArr[$k]))] = '../'.APP_ATTACHED.'/'.$row['attached_'.$k.'_file'];
			$flag_zip = 1;
		}
	}
	
	if($flag_zip == 1)
	{
		require_once('../includes/zip.manager.php');
		$DirZip = '../uploads/download-zip';	
		$objZip = new ZipManager();
		
		// Zip File Path
		$zip_file = $DirZip."/".$ZipFileName;
		// If the file is already existing then delete it
		$f->DeleteFile($zip_file);
		// Creating the Zip File
		$objZip->CreateZip($files, $zip_file);
		ob_clean();
		ob_end_flush();
		// Giving option for download after successfully created
		header("Content-Type: application/zip");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding: Binary");
		header("Content-length: ".filesize($zip_file));
		header("Content-disposition: attachment; filename=\"".basename($zip_file)."\"");
		readfile($zip_file);
	}
	else
	{
		echo '<center>No file found.</center>';	
	}
?>