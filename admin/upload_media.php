<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	//$output_dir = "uploads/";
	$OutputDir = "../".MEDIA;
	
	$YearFolder = $OutputDir."/".date("Y");
	$f->CreateFolder($YearFolder, 0777);
	
	$MonthFolder = $YearFolder."/".date("m");
	$f->CreateFolder($MonthFolder, 0777);
	
	$FileUploadFolder = $MonthFolder;
	
	$FolerNameToDB = date("Y")."/".date("m");
	
	$m->ReloadMedia();
	
	if(isset($_FILES["myfile"]))
	{
		$ret = array();
	
		//	This is for custom errors;	
		/*	$custom_error= array();
		$custom_error['jquery-upload-file-error']="File already exists";
		echo json_encode($custom_error);
		die();
		*/
		$error = $_FILES["myfile"]["error"];
		//You need to handle  both cases
		//If Any browser does not support serializing of multiple files using FormData() 
		if(!is_array($_FILES["myfile"]["name"])) //single file
		{
			//$fileName = $_FILES["myfile"]["name"];
			$ActualFileName = $_FILES["myfile"]["name"];
			$FileType = $_FILES["myfile"]["type"];
			$FileSize = $_FILES["myfile"]["size"];
			$ServerFileName = $f->CreateMediaFileName($ActualFileName);
			$ServerFileName = $m->CheckMediaExist($ServerFileName);
			move_uploaded_file($_FILES["myfile"]["tmp_name"], $FileUploadFolder."/".$ServerFileName);
			//move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir."/".$fileName);
			
			$data = array(
				'media_fname' => $ServerFileName,
				'media_ftype' => $FileType,
				'media_fsize' => $FileSize,
				'media_folder' => $FolerNameToDB,
				'create_date_time' => date('Y-m-d H:i:s')
			);
			
			/*if($FileType == 'image/jpg' || $FileType == 'image/jpeg' || $FileType == 'image/png' || $FileType == 'image/gif')
			{
				$FileName = $FileUploadFolder."/".$ServerFileName;
				list($width, $height, $type, $attr) = @getimagesize($FileName);
				if($width > 0 && $height > 0)
				{
					$data['media_width'] = $width;
					$data['media_height'] = $height;
				}
			}*/
			
			$FileName = $FileUploadFolder."/".$ServerFileName;
			list($width, $height, $type, $attr) = @getimagesize($FileName);
			if($width > 0 && $height > 0)
			{
				$data['media_width'] = $width;
				$data['media_height'] = $height;
			}
			
			$db->insert('tbl_media', $data);
			
			$ret[]= $ServerFileName;
		}
		else  //Multiple files, file[]
		{
			$fileCount = count($_FILES["myfile"]["name"]);
			for($i=0; $i < $fileCount; $i++)
			{
				$ActualFileName = $_FILES["myfile"]["name"][$i];
				$FileType = $_FILES["myfile"]["type"][$i];
				$FileSize = $_FILES["myfile"]["size"][$i];
				$ServerFileName = $f->CreateMediaFileName($ActualFileName);
				$ServerFileName = $m->CheckMediaExist($ServerFileName);
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $FileUploadFolder."/".$ServerFileName);
			
				$ret[]= $ServerFileName;
				
				$data = array(
					'media_fname' => $ServerFileName,
					'media_ftype' => $FileType,
					'media_fsize' => $FileSize,
					'media_folder' => $FolerNameToDB,
					'create_date_time' => date('Y-m-d H:i:s')
				);
				
				/*if($FileType == 'image/jpg' || $FileType == 'image/jpeg' || $FileType == 'image/png' || $FileType == 'image/gif')
				{
					$FileName = $FileUploadFolder."/".$ServerFileName;
					list($width, $height, $type, $attr) = @getimagesize($FileName);
					if($width > 0 && $height > 0)
					{
						$data['media_width'] = $width;
						$data['media_height'] = $height;
					}
				}*/
				
				$FileName = $FileUploadFolder."/".$ServerFileName;
					list($width, $height, $type, $attr) = @getimagesize($FileName);
					if($width > 0 && $height > 0)
					{
						$data['media_width'] = $width;
						$data['media_height'] = $height;
					}
				
				$db->insert('tbl_media', $data);
			}		
		}
		echo json_encode($ret);
	}
?>
 