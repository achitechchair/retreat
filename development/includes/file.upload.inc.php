<?php
	class FileUpload extends Functions 
	{
		// Upload mode (Add / Edit)
		public $UploadMode = "Add";
		// If upload mode is Edit
		public $OldFileName;
		// Upload contain variable
		// Example: $_FILES['html_control_name'];
		public $UploadContent;
		// Folder name where the file will be uploaded
		public $UploadFolder;
		// Save the file by using random name or not
		public $IsSaveByRandomName = true;
		// Need return statement
		public $NeedReturnStatement = true;		
		// Upload function		
		public function Upload() 
		{
			$filename = $this->UploadContent['name'];
			if($this->IsSaveByRandomName == true) 
			{
				$filename = $this->getRandomName($filename);	
			}
			if(is_dir($this->UploadFolder)==false) 
			{
				$this->CreateFolder($this->UploadFolder,0777);
			}
			if($this->UploadMode == "Edit") 
			{
				$OldFilePath = $this->UploadFolder."/".$this->OldFileName;
				if(file_exists($OldFilePath)==true && is_file($OldFilePath)==true) @unlink($OldFilePath);
			}
			
			$filepath = $this->UploadFolder."/".$filename;
			move_uploaded_file($this->UploadContent['tmp_name'], $filepath);
			chmod($filepath, 0777);
			
			$this->ImageOrientationGD($filepath);
			
			if($this->NeedReturnStatement == true) 
			{
				return $this->ReturnStatement($filename);
			}
		}
		
		public function ReturnStatement($FileName) 
		{
			$return = array(
				"original_name" => $this->UploadContent['name'],
				"server_name" => $FileName,
				"file_type" => $this->UploadContent['type'],
				"file_size" => $this->UploadContent['size']
			);
			return $return;
		}
	}
?>