<?php
	require_once("../includes/config.inc.php");
	require_once('../phpSpreadsheet/vendor/autoload.php');
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	
	$sql = $_SESSION['sql_download_registration'];
	$res = $db->get($sql, __FILE__, __LINE__);
	if($db->num_rows($res) > 0)
	{
		/** Include Spreadsheet */		
		$objPHPExcel = new Spreadsheet();

		$objPHPExcel->getProperties()->setCreator(EXCEL_TITLE);
		$objPHPExcel->getProperties()->setLastModifiedBy(EXCEL_TITLE);
		$objPHPExcel->getProperties()->setTitle(EXCEL_TITLE);
		$objPHPExcel->getProperties()->setSubject(EXCEL_TITLE);
		$objPHPExcel->getProperties()->setDescription(EXCEL_TITLE);
		$objPHPExcel->getProperties()->setCategory("");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->mergeCells('B2:Z2');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', EXCEL_TITLE);
		
		$objPHPExcel->getActiveSheet()->mergeCells('AA2:AM2');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA2', "Spouse Details");
		
		
		
		$styleArrayStatus = array(
		'font'  => array(
			  'bold'  => true,
			  'color' => array('rgb' => 'FF0000')			  
		 ));
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					 'color' => array('argb' => '000000'),
				 ),
			),
			'font' => array(
				'bold' => true,
				'size' => 16,
				'color' => array('argb' => 'FFFFFF')
			),
			'fill' => array(
				 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				 'startColor' => array('argb' => '2f2d9b')
			)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('B2:AM2')->applyFromArray($styleArray);	
		$objPHPExcel->getActiveSheet()->getStyle('B2:AM2')->getAlignment()->setHorizontal('left');	
		
		$styleArray = array(			
			'font' => array(
				'bold' => true,
				'size' => 16,
				'color' => array('argb' => 'FFFFFF')
			)
		);
		$objPHPExcel->getActiveSheet()->getStyle('AA3:AM3')->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'First Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Last Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'Address 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'Address 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'City');
		$objPHPExcel->getActiveSheet()->SetCellValue('I3', 'State');
		$objPHPExcel->getActiveSheet()->SetCellValue('J3', 'Zip');
		$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'Phone');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('M3', 'Kovil');
		$objPHPExcel->getActiveSheet()->SetCellValue('N3', 'Native Village');
		$objPHPExcel->getActiveSheet()->SetCellValue('O3', 'Vilasam');
		$objPHPExcel->getActiveSheet()->SetCellValue('P3', 'Spouse Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q3', 'Membership Type');
		$objPHPExcel->getActiveSheet()->SetCellValue('R3', 'Membership Price');
		$objPHPExcel->getActiveSheet()->SetCellValue('S3', 'Invoice');
		$objPHPExcel->getActiveSheet()->SetCellValue('T3', 'Transaction Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('U3', 'EXP Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('V3', 'Nanal Hard Copy');
		$objPHPExcel->getActiveSheet()->SetCellValue('W3', 'Nanal Soft Copy');
		$objPHPExcel->getActiveSheet()->SetCellValue('X3', 'Status');
		$objPHPExcel->getActiveSheet()->SetCellValue('Y3', 'Date');		
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AA3', 'Email');		
		$objPHPExcel->getActiveSheet()->SetCellValue('AB3', 'First Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC3', 'Last Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('AD3', 'Address');		
		$objPHPExcel->getActiveSheet()->SetCellValue('AE3', 'City');
		$objPHPExcel->getActiveSheet()->SetCellValue('AF3', 'State');
		$objPHPExcel->getActiveSheet()->SetCellValue('AG3', 'Zip');
		$objPHPExcel->getActiveSheet()->SetCellValue('AH3', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('AI3', 'Phone');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AJ3', 'Kovil');
		$objPHPExcel->getActiveSheet()->SetCellValue('AK3', 'Native Village');
		$objPHPExcel->getActiveSheet()->SetCellValue('AL3', 'Vilasam');
		$objPHPExcel->getActiveSheet()->SetCellValue('AM3', 'Date');
		
		$objPHPExcel->getActiveSheet()->getStyle('B3:AM3')->getAlignment()->setVertical('LEFT');	
		
		
		$styleArray = array(
			'borders' => array(
				'outline' => array(
					 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					 'color' => array('argb' => 'FFFF00'),
				 ),
			),
			'font' => array(
				'bold' => true,
				'size' => 12
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				 'startColor' => array('argb' => 'DEDEDE')
			)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('B3:AM3')->applyFromArray($styleArray);

		$styleArray = array(
			'borders' => array(
				'outline' => array(
					 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					 'color' => array('argb' => 'FFFF00'),
				 ),
			),
			'font' => array(
				'bold' => true,
				'size' => 12
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				 'startColor' => array('argb' => '000000')
			)
		);
		$objPHPExcel->getActiveSheet()->getStyle('AA3:AM3')->applyFromArray($styleArray);
		
		$cell_row = 4;
				
		while($row = $db->fetch_array($res))
		{
			$address2 = $f->getValue($row['address2']);
			if(empty($address2) == true) $address2 = '---';
			
			$phone = $f->getValue($row['phone']);
			if(empty($phone) == true) $phone = '---';		
			
			$spouse_email = $f->getValue($row['spouse_email']);
			if(empty($spouse_email) == true) $spouse_email = '---';	
			
			$transaction_id = $f->getValue($row['transaction_id']);
			if(empty($transaction_id) == true) $transaction_id = '---';	
			
			$vilasam = $f->getValue($row['vilasam']);	
			if(empty($vilasam) == true) $vilasam = '---';	
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cell_row, $f->getValue($row['user_id']));					
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cell_row, $f->getValue($row['email_address']));
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cell_row, $f->getValue($row['first_name']));
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cell_row, $f->getValue($row['last_name']));
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cell_row, $f->getValue($row['address1']));
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cell_row, $address2);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cell_row, $f->getValue($row['city']));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cell_row, $f->getValue($row['state']));
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$cell_row, $f->getValue($row['zip']));
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$cell_row, $f->getValue($row['country']));
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cell_row, $phone);			
		
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cell_row, $f->getValue($row['kovil']));
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$cell_row, $f->getValue($row['native_village']));
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$cell_row, $vilasam);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$cell_row, $spouse_email);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$cell_row, $f->getValue($row['membership_type']));
			$objPHPExcel->getActiveSheet()->SetCellValue('R'.$cell_row, '$'.$f->getValue($row['membership_price']));
			$objPHPExcel->getActiveSheet()->SetCellValue('S'.$cell_row, $f->getValue($row['inv_no']));
			$objPHPExcel->getActiveSheet()->SetCellValue('T'.$cell_row, $transaction_id);
			if($row['membership_type'] != 'Life')
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('U'.$cell_row, date("m/d/Y", strtotime($row['exp_date']))); 
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('U'.$cell_row, '---'); 
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('V'.$cell_row, $f->getValue($row['nanal_hard_copy']));
			$objPHPExcel->getActiveSheet()->SetCellValue('W'.$cell_row, $f->getValue($row['nanal_soft_copy']));
			$objPHPExcel->getActiveSheet()->SetCellValue('X'.$cell_row, $f->getValue($row['status']));
			$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$cell_row, date("m/d/Y", strtotime($row['create_dt']))); 
			
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$cell_row)->getAlignment()->setHorizontal('left');
			$objPHPExcel->getActiveSheet()->getStyle('I'.$cell_row)->getAlignment()->setHorizontal('left');
			$objPHPExcel->getActiveSheet()->getStyle('K'.$cell_row)->getAlignment()->setHorizontal('left');
			$objPHPExcel->getActiveSheet()->getStyle('L'.$cell_row)->getAlignment()->setHorizontal('left');
			
			if($row['status'] == 'Pending' || $row['status'] == 'Denied')
			{
				$objPHPExcel->getActiveSheet()->getStyle('X'.$cell_row)->getFont()->getColor()->setRGB ('FF0000');
			}
			
			$sql_spouse = "SELECT * FROM `tbl_user` WHERE `user_parent_id`=".$row['user_id'];
			$res_spouse = $db->get($sql_spouse);
			$rec_spouse = $db->num_rows($res_spouse);
			if($rec_spouse > 0)
			{
				$row_spouse = $db->fetch_array($res_spouse);	
				$phone_sp = $row_spouse['phone'];
				if(empty($phone_sp) == true) $phone_sp = '---';	
				
				$vilasam = $f->getValue($row_spouse['vilasam']);	
				if(empty($vilasam) == true) $vilasam = '---';				
								
				$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$cell_row, $f->getValue($row_spouse['email_address']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$cell_row, $f->getValue($row_spouse['first_name']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$cell_row, $f->getValue($row_spouse['last_name']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$cell_row, $f->getValue($row_spouse['address1']));				
				$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$cell_row, $f->getValue($row_spouse['city']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$cell_row, $f->getValue($row_spouse['state']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$cell_row, $f->getValue($row_spouse['zip']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$cell_row, $f->getValue($row_spouse['country']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$cell_row, $phone_sp);				
				$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$cell_row, $f->getValue($row_spouse['kovil']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$cell_row, $f->getValue($row_spouse['native_village']));
				$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$cell_row, $vilasam);
				$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$cell_row, date("m/d/Y", strtotime($row_spouse['create_dt'])));
			}else{
				
				$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$cell_row, '---');				
				$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$cell_row, '---');					
				$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$cell_row, '---');
				$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$cell_row, '---');
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('L'.$cell_row)->getAlignment()->setHorizontal('left');
			$objPHPExcel->getActiveSheet()->getStyle('AI'.$cell_row)->getAlignment()->setHorizontal('left');
			
			$cell_row++;
			//unset($styleArray);			
		}
		
		$cell_row = $cell_row - 1;
		
		$styleArray = array(
			'borders' => array(
				
			),
			'font' => array(
				'size' => 11
			)
		);
		
		//$objPHPExcel->getActiveSheet()->getStyle('B4:R'.$cell_row)->applyFromArray($styleArray);
		//$objPHPExcel->getActiveSheet()->getStyle('K4:R'.$cell_row)->getAlignment()->setHorizontal('LEFT');
		
		
		$alphabets = range('B', 'AM');
		foreach($alphabets as $alpha)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension($alpha)->setAutoSize(true);
		}
		$page_title = EXCEL_TITLE;
		$objPHPExcel->getActiveSheet()->setTitle($page_title);
		$page_title = EXCEL_TITLE;			
		$objWriter = new Xlsx($objPHPExcel);
		$excel_file_name = str_replace(" ", "-", $page_title);
		$excel_file = $excel_file_name.".xlsx";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename='.$excel_file);
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit;
	}
	else
	{
		$f->alert('No Record Found');
		$f->location($_SERVER['HTTP_REFERER']);
		exit();
	}
?>