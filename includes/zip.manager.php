<?php
	class ZipManager {
		/* creates a compressed zip file */
		function CreateZip($files = array(), $destination = '',$overwrite = false) {
			//if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && $overwrite == false) { return false; }
			//vars
			$valid_files = array();
			//if files were passed in...
			if(is_array($files)) {
				//cycle through each file
				foreach($files as $filename => $filepath) {
					//make sure the file exists
					if(file_exists($filepath)==true && is_file($filepath)==true) {
						$valid_files[$filename] = $filepath;
					}
				}
			}
						
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new ZipArchive();
				if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				//add the files
				foreach($valid_files as $filename => $filepath) {
					$zip->addFile($filepath, $filename);
				}
				//debug
				//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
				
				//close the zip -- done!
				$zip->close();
				
				//check to make sure the file exists
				return file_exists($destination);
			}
			else
			{
				return false;
			}
		}	
	}
?>