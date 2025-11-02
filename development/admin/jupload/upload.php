<?php
	require_once("../../includes/config.inc.php");
	$output_dir = "../../".SLIDE;
	
	if(isset($_FILES["myfile"]))
	{
		$ret = array();
		
	//	This is for custom errors;	
	/*	$custom_error= array();
		$custom_error['jquery-upload-file-error']="File already exists";
		echo json_encode($custom_error);
		die();
	*/
		$error =$_FILES["myfile"]["error"];
		// You need to handle  both cases
		// If Any browser does not support serializing of multiple files using FormData() 
		if(!is_array($_FILES["myfile"]["name"])) //single file
		{
			$fileName = $_FILES["myfile"]["name"];
			$fileName = $f->getRandomName($fileName);
			move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir."/".$fileName);
			$ret[]= $fileName;
			
			$f->ImageOrientationGD($output_dir."/".$fileName);
			
			$product_id = $_POST['product_id'];	
			$data = array(				
				'img_product_id' => $product_id,
				'addi_image' => $fileName,
				'create_date_time' => date('Y-m-d H:i:s')
			);
			$db->insert("tbl_slide_img",$data);
			
			usleep(500000);
		}
		else  //Multiple files, file[]
		{
			$fileCount = count($_FILES["myfile"]["name"]);
			for($i=0; $i < $fileCount; $i++)
			{
				$fileName = $_FILES["myfile"]["name"][$i];
				$fileName = $f->getRandomName($fileName);
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir."/".$fileName);
				$ret[] = $fileName;
				
				$f->ImageOrientationGD($output_dir."/".$fileName);
			
				$product_id = $_POST['product_id'];	
				$data = array(				
					'img_product_id' => $product_id,
					'addi_image' => $fileName,
					'create_date_time' => date('Y-m-d H:i:s')
				);
				$db->insert("tbl_slide_img",$data);
				
				usleep(500000);
			}	
		}
		echo json_encode($ret);
	}
?>
