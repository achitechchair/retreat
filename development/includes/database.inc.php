<?php
	/*****************************************************************************
		Database Class for MySQL Server. Please do not change anything
	*****************************************************************************/
	class Database 
	{
		private $Con;
				
		public function __construct($db) 
		{
			//$this->Con = @mysql_connect($db['DATABASE_HOST'].":".$db['DATABASE_PORT'],$db['DATABASE_USER'],$db['DATABASE_PASSWORD']) or die($this->error(mysql_error(),__FILE__,__LINE__));
			//@mysql_select_db($db['DATABASE_NAME'],$this->Con) or die($this->error(mysql_error(),__FILE__,__LINE__));
			
			//$this->Con = @mysqli_connect($db['DATABASE_HOST'].":".$db['DATABASE_PORT'],$db['DATABASE_USER'],$db['DATABASE_PASSWORD'], $db['DATABASE_NAME']) or die($this->error(mysqli_connect_error(),__FILE__,__LINE__));
			$this->Con = @mysqli_connect($db['DATABASE_HOST'],$db['DATABASE_USER'],$db['DATABASE_PASSWORD'], $db['DATABASE_NAME']) or die($this->error(mysqli_connect_error(),__FILE__,__LINE__));
		}
		
		public function __destruct() 
		{
      		$this->close();
   		}
		
		public function escape_string($str)
		{
			return mysqli_real_escape_string($this->Con, $str);	
		}
		
		public function start_transaction()
		{
			//$this->get('START TRANSACTION',__FILE__,__LINE__);
			if(version_compare(PHP_VERSION, '5.5.0') >= 0 && mysqli_get_server_version($this->Con) >= 50600)
			{
				mysqli_begin_transaction($this->Con, MYSQLI_TRANS_START_READ_WRITE);
			}
			else
			{
				mysqli_autocommit($this->Con, FALSE);
				//$this->get('START TRANSACTION',__FILE__,__LINE__);
			}
		}
		
		public function commit()
		{
			//$this->get('COMMIT',__FILE__,__LINE__);
			mysqli_commit($this->Con);
		}
		
		public function rollback()
		{
			//$this->get('ROLLBACK',__FILE__,__LINE__);
			mysqli_rollback($this->Con);
		}
		
		public function get($sql,$errorFile = __FILE__,$errorLine = __LINE__) 
		{
			$query = $sql;
			//$result = @mysql_query($query,$this->Con) or die($this->error($query."<br />".mysql_error(),$errorFile,$errorLine));
			$result = mysqli_query($this->Con, $query) or die($this->error($query."<br />".mysqli_error($this->Con),$errorFile,$errorLine));
			return $result;
		}
		
		public function check($table,$arrFieldValue = NULL, $arrOrderBy = NULL, $print = false)
		{
			$sql = "SELECT * FROM `".$table."`";
			if($arrFieldValue!==NULL)
			{
				if(is_array($arrFieldValue)===true && empty($arrFieldValue)===false)
				{
					$sql.= " WHERE ";
					$counter = 1;
					foreach($arrFieldValue as $field => $value)
					{
						if($counter > 1) $sql.=" AND ";
						$sql.="`".$field."`='".$value."'";
						$counter++;
					}
				}
			}
			
			if($arrOrderBy!==NULL)
			{
				if(is_array($arrOrderBy)===true && empty($arrOrderBy)===false)
				{
					$sql.= " ORDER BY ";
					$counter = 1;
					foreach($arrOrderBy as $field => $value)
					{
						if($counter > 1) $sql.=", ";
						$sql.="`".$field."` ".$value;
						$counter++;
					}
				}
			}
			
			if($print === true)
			{
				echo $sql;
			}
			else
			{
				$res = $this->get($sql,__FILE__,__LINE__);
				return $this->num_rows($res);
			}
		}
		
		public function fetch_array($result) 
		{
			//return mysql_fetch_array($result);
			return mysqli_fetch_array($result, MYSQLI_BOTH);
		}
		
		public function fetch_assoc($result) 
		{
			return mysqli_fetch_assoc($result);
		}
		
		public function fetch_row($result) 
		{
			return mysqli_fetch_row($result);
		}
		
		public function last_insert_id() 
		{
			//return mysql_insert_id($this->Con);
			return mysqli_insert_id($this->Con);
		}
		
		public function result($result,$row = 0,$col = 0) 
		{
			//return mysql_result($result,$row,$column);
			$numrows = $this->num_rows($result); 
			if($numrows && $row <= ($numrows-1) && $row >=0)
			{
			   mysqli_data_seek($result, $row);
			   $resrow = (is_numeric($col)) ? $this->fetch_row($result) : $this->fetch_assoc($result);
			   if(isset($resrow[$col]) == TRUE)
			   {
				  return $resrow[$col];
			   }
			}
		}
		
		public function num_rows($result) 
		{
			//return mysql_num_rows($result);
			return mysqli_num_rows($result);
		}
		
		public function free_result($result) 
		{
			//mysql_free_result($result);
			mysqli_free_result($result);
		}
		
		public function next_result() 
		{
			mysqli_next_result($this->Con);
		}
		
		public function close() 
		{
			@mysqli_close($this->Con);
		}
		
		// ================================================================================================================================================================

		public function insert($table,$DataArray,$printSQL = false) 
		{
			if(count($DataArray) == 0) 
			{
				die($this->error("INSERT INTO statement has not been created",__FILE__,__LINE__));
			}
			foreach($DataArray as $key => $val) 
			{
				$strFields.= "`".$key."`,";
				if($val == "0") 
				{
					$strValues.= "0,";
				} 
				elseif($val == "CURDATE()") 
				{
					$strValues.= "CURDATE(),";
				} 
				elseif($val == "CURTIME()") 
				{
					$strValues.= "CURTIME(),";
				} 
				elseif($val == "NULL") 
				{
					$strValues.= "NULL,";
				}
				else 
				{
					//$strValues.= "'".$val."',";	
					$strValues.='"'.$val.'",';	
				}
			}
			$strFields = substr($strFields,0,strlen($strFields)-1);
			$strValues = substr($strValues,0,strlen($strValues)-1);
			$sql = "INSERT INTO `".$table."`(".$strFields.") VALUES(".$strValues.")";
			if($printSQL == true) 
			{
				echo $this->error($sql,__FILE__,__LINE__);
			} 
			else 
			{
				$this->get($sql,__FILE__,__LINE__);
			}
		}

		public function update($table,$DataArray,$updateOnField,$updateOnFieldValue,$printSQL = false) 
		{
			if(count($DataArray) == 0) 
			{
				die($this->error("UPDATE statement has not been created",__FILE__,__LINE__));
			}
			$sql = "UPDATE ".$table." SET ";
			foreach($DataArray as $key => $val) 
			{
				$strFields = "`".$key."`";
				if($val == "0") 
				{
					$strValues = "0";
				} 
				elseif($val == "CURDATE()") 
				{
					$strValues = "CURDATE()";
				} 
				elseif($val == "CURTIME()") 
				{
					$strValues = "CURTIME()";
				}
				elseif($val == "NULL") 
				{
					$strValues = "NULL";
				} 
				else 
				{
					$strValues = "'".$val."'";	
				}
				$sql.= $strFields."=".$strValues.", ";
			}
			$sql = substr($sql,0,strlen($sql)-2);
			$sql.= " WHERE `".$updateOnField."`='".$updateOnFieldValue."'";
			if($printSQL == true) 
			{
				echo $this->error($sql,__FILE__,__LINE__);
			} 
			else 
			{
				$this->get($sql,__FILE__,__LINE__);
			}
		}

		public function getDateDiff($coming_date) 
		{
			$diff_sql = "SELECT DATEDIFF('".$coming_date."','".date('Y-m-d')."')";
			$diff_res = $this->get($diff_sql);
			return $this->result($diff_res,0,0);
		}
		
		public function record_number($sql) 
		{
			$result = $this->get($sql);
			$cnt = $this->num_rows($result);
			return $cnt;
		}
		
		public function getFields($result)
		{
			$i = 0;
			$data = array();
			while($i < mysql_num_fields($result))
			{
				$meta = mysql_fetch_field($result,$i);
				$data[] = $meta->name;
				$i++;	
			}
			return $data;
		}
		
		public function pagination($sql,$DividedRecordNumber,$Page) 
		{
			$PageResult = $this->record_number($sql);
			if($Page == "" || $Page == 1) 
			{
				$Page = 0;
			} 
			else 
			{
				$Page = ($Page-1) * $DividedRecordNumber;
			}
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			$ReturnResult = $this->get($sql." LIMIT ".$Page.",".$DividedRecordNumber."");
			return $ReturnResult;
		}
		
		public function pagination_page_number($sql,$DividedRecordNumber,$Page,$PageName,$QueryString, $class='input1') 
		{
			$PageResult = $this->record_number($sql);
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			if($Page == "") 
			{
				$Page = 1;
			}
			$str = "<select name=\"cmbPage\" id=\"cmbPage\" class='".$class."' onchange=\"javascript:_doPagination('".$PageName."','".$QueryString."');\">\n";
			for($i = 1;$i <= $RecordPerPage;$i++) 
			{
				if($Page == $i) 
				{
					$selected = ' selected';
				} 
				else 
				{
					$selected = '';
				}
				$str.= "<option value=\"".$i."\"".$selected.">Page ".$i."</option>\n";
			}
			$str.= "</select>";
			echo $str;
		}
		
		public function paging($sql,$DividedRecordNumber,$Page,$PageName,$QueryStringName) 
		{
			$PageResult = $this->record_number($sql);
			if($PageResult > $DividedRecordNumber):
				echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
						<tr>";
				$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
				if($Page == "") 
				{
					$Page = 1;
				}
				$PageCount = $Page - 1;
				if($PageCount > 0) 
				{
					if(empty($QueryStringName)) 
					{
						echo "<td class=\"footermenulink\" width=\"100\" align=\"center\"><a href='".$PageName."?page=".$PageCount."'>&lt;&lt; Previous</a></td>";
					} 
					else 
					{
						echo "<td class=\"footermenulink\" width=\"100\" align=\"center\"><a href='".$PageName."?page=".$PageCount."&".$QueryStringName."'>&lt;&lt; Previous</a></td>";
					}
				} 
				else 
				{
					echo "";
				}

				if($RecordPerPage > 2)
				{
					echo "<td width=\"4\"></td>";
				}
				/*for($i = 1;$i <= $RecordPerPage;$i++) 
				{
					if($Page == $i) 
					{
						echo "<b>".$i."</b>&nbsp;";
					} 
					else 
					{
						if(empty($QueryStringName)) 
						{
							echo "<a href='".$PageName."?page=".$i."'>".$i."</a>&nbsp;";
						} 
						else 
						{
							echo "<a href='".$PageName."?page=".$i."&".$QueryStringName."'>".$i."</a>&nbsp;";
						}
					}
				}*/
				$PageCount = $Page + 1;
				if($PageCount < $RecordPerPage + 1) 
				{
					if(empty($QueryStringName)) 
					{
						echo "<td class=\"footermenulink\" width=\"100\" align=\"center\"><a href='".$PageName."?page=".$PageCount."'>Next &gt;&gt;</font></td>";
					} 
					else 
					{
						echo "<td class=\"footermenulink\" width=\"100\" align=\"center\"><a href='".$PageName."?page=".$PageCount."&".$QueryStringName."'>Next &gt;&gt;</a></td>";
					}
				} 
				else 
				{
					echo "";
				}
				echo "</tr>
					</table>";
			else:
				echo "&nbsp;";
			endif;
		}
		
		public function TablePagination($sql,$DividedRecordNumber,$Page,$PageName,$Class,$SelectedClass,$QueryString='',$NumberOfLinks=10) {
			// calculating basics
			$PageResult = $this->record_number($sql); // total number of rows in database
			$total_page = ceil($PageResult/$DividedRecordNumber); // calculating total number of pages
			$NumberOfLinks_half = ceil($NumberOfLinks/2);
			
			if(empty($QueryString)==true) {
				$QueryString = '';
			} else {
				$QueryString = '&'.$QueryString;
			}
			
			// starting the table structure
			
				 if($Page > 1) {
				 
				$PageCount = $Page - 1;
				$output.= '<a href="'.WEBSITE_URL."/".$PageName.'?page='.$PageCount.$QueryString.'">&laquo;</a>';
			} else {
				$output.= '<a href="javascript:;" class="active_page">&laquo;</a>';
			}
			
			
			
			if($PageResult > $DividedRecordNumber){ // if calculated number of pages are more than 1
				$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
				if($Page == "") {
					$Page = 1;
				}
				
				// Calculating if the page greater than record per page display
				if($Page>1) {
					$output.= '<a href="'.WEBSITE_URL."/".$PageName.'?page=1'.$QueryString.'">1</a>';
				}else{
					$output.= '<a href="javascript:;" class="active_page">1</a>';
				}
				
				$forward = $Page + $NumberOfLinks;
				
				if($forward <= $total_page) {
					$start = $Page-$NumberOfLinks_half;
					if($Page>=$NumberOfLinks_half){
						$end = $forward-$NumberOfLinks_half;
					}else{
						$end = $forward;
					}
				} else {
					$start = $total_page - $NumberOfLinks;
					$end = $total_page;
				}
				if($start<2){
					$start = 2;
				}
				
				//echo $start."|".$end;
				if($start>2){
					$output.= '&nbsp;...&nbsp;';
				}
				for($i = $start;$i < $end;$i++) {
					if($Page == $i) {
						$output.= '<a href="javascript:;" class="active_page">'.$i.'</a>';
					} else {
						if($i>0){
							$output.= '<a href="'.WEBSITE_URL."/".$PageName.'?page='.$i.$QueryString.'">'.$i.'</a>';
						}
					}
				}

				if($total_page!=$Page) {
					if($end<$total_page)
					$output.= '&nbsp;...&nbsp;';
					$output.= '<a href="'.WEBSITE_URL."/".$PageName.'?page='.$total_page.$QueryString.'">'.$total_page.'</a>';
				} else {
					$output.= '<a href="javascript:;" class="active_page">'.$total_page.'</a>';
				}
			}else{ // if there is only 1 page
				$output.= '<a href="javascript:;">1</a>';
			}
			
			// end of showing page number and start of showing next-previous links
			
			
			$PageCount = $Page + 1;
			if($PageCount < $RecordPerPage + 1) {
				$output.= '<a href="'.WEBSITE_URL."/".$PageName.'?page='.$PageCount.$QueryString.'">&raquo;</a>';
			} else {
				$output.= '<a href="javascript:;" class="active_page">&raquo;</a>';
			}
			// ending the table structure
			
			$output = trim($output);
			echo $output;
		}
		
		public function TablePaginationAdmin($sql,$DividedRecordNumber,$Page,$PageName,$Class,$SelectedClass,$QueryString='',$NumberOfLinks=10) {
			// calculating basics
			$PageResult = $this->record_number($sql); // total number of rows in database
			$total_page = ceil($PageResult/$DividedRecordNumber); // calculating total number of pages
			$NumberOfLinks_half = ceil($NumberOfLinks/2);
			
			// starting the table structure
			$output = '
				<table cellpadding="0" cellspacing="0" border="0">
				  <tr><td class="padingright">';
				 if($Page > 1) {
				 
				$PageCount = $Page - 1;
				$output.= '<span class="'.$Class.'"><a href="'.WEBSITE_URL."/".$PageName.'?page='.$PageCount."&".$QueryString.'">&laquo; Previous</a></span>&nbsp;';
			} else {
				$output.= '<span class="page_navs">&laquo; Previous</a></span>&nbsp;';
			}
			$output.= '</td><td valign="bottom" align="right">
			';
			
			if($PageResult > $DividedRecordNumber){ // if calculated number of pages are more than 1
				$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
				if($Page == "") {
					$Page = 1;
				}
				if(empty($QueryString)==true) {
					$QueryString = '';
				}else{
					$QueryString = '&amp;'.$QueryString;
				}
				// Calculating if the page greater than record per page display
				if($Page>1) {
					$output.= '<span class="'.$Class.'"><a href="'.WEBSITE_URL."/".$PageName.'?page=1'.$QueryString.'">1</a></span>&nbsp;';
				}else{
					$output.= '<span class="'.$SelectedClass.'">1</span>&nbsp;';
				}
				
				$forward = $Page + $NumberOfLinks;
				
				if($forward <= $total_page) {
					$start = $Page-$NumberOfLinks_half;
					if($Page>=$NumberOfLinks_half){
						$end = $forward-$NumberOfLinks_half;
					}else{
						$end = $forward;
					}
				} else {
					$start = $total_page - $NumberOfLinks;
					$end = $total_page;
				}
				if($start<2){
					$start = 2;
				}
				
				//echo $start."|".$end;
				if($start>2){
					$output.= '...&nbsp;';
				}
				for($i = $start;$i < $end;$i++) {
					if($Page == $i) {
						$output.= '<span class="'.$SelectedClass.'">'.$i.'</span>&nbsp;';
					} else {
						if($i>0){
							$output.= '<span class="'.$Class.'"><a href="'.WEBSITE_URL."/".$PageName.'?page='.$i.$QueryString.'">'.$i.'</a></span>&nbsp;';
						}
					}
				}

				if($total_page!=$Page) {
					if($end<$total_page)
					$output.= '...&nbsp;';
					$output.= '<span class="'.$Class.'"><a href="'.WEBSITE_URL."/".$PageName.'?page='.$total_page.$QueryString.'" class="'.$Class.'">'.$total_page.'</a></span>';
				} else {
					$output.= '<span class="'.$SelectedClass.'">'.$total_page.'</span>';
				}
			}else{ // if there is only 1 page
				$output.= '<span class="'.$SelectedClass.'">1</span>';
			}
			$output.= '
				</td>
				<td  valign="bottom" align="right" class="padingleft">
			';
			// end of showing page number and start of showing next-previous links
			
			
			$PageCount = $Page + 1;
			if($PageCount < $RecordPerPage + 1) {
				$output.= '&nbsp;<span class="'.$Class.'"><a href="'.WEBSITE_URL."/".$PageName.'?page='.$PageCount.$QueryString.'">Next &raquo;</a></span>';
			} else {
				$output.= '&nbsp;<span class="page_navs">Next &raquo;</a></span>';
			}
			// ending the table structure
			$output.= '
				</td>
			  </tr>
			</table>
			';
			$output = trim($output);
			echo $output;
		}
		
		function displayCart($type='Item') {
			if($type == 'Item'){
				if(empty($_SESSION['_user_id']) == false){					
					//$sql = "SELECT SUM(`quantity`) FROM `tbl_cart` WHERE `session_id`='".session_id()."' AND `customer_id`=".$_SESSION['_user_id'];
					$sql = "SELECT count(*) FROM `tbl_cart` WHERE `session_id`='".session_id()."' AND `customer_id`=".$_SESSION['_user_id'];					
				}else{
					$sql = "SELECT count(*) FROM `tbl_cart` WHERE `session_id`='".session_id()."'";
					//$sql = "SELECT SUM(`quantity`) FROM `tbl_cart` WHERE `session_id`='".session_id()."'";
				}
				$res = $this->get($sql);
				//return $this->num_rows($res);
				$row = $this->fetch_array($res);
				if(empty($row[0]) == true){
					return '0';
				}else{
					return $row[0];
				}	
			}elseif($type == "Amount"){
				if(empty($_SESSION['_user_id']) == false){
					$sql = "SELECT SUM(`total_price`) FROM `tbl_cart` WHERE `session_id`='".session_id()."' AND `customer_id`=".$_SESSION['_user_id'];
				}else{
					$sql = "SELECT SUM(`total_price`) FROM `tbl_cart` WHERE `session_id`='".session_id()."'";
				}				
				$res = $this->get($sql);
				$row = $this->fetch_array($res);
				return $row[0];
			}elseif($type == "Tax"){
				if(empty($_SESSION['_user_id']) == false){
					$sql = "SELECT SUM(`tax_amount`) FROM `tbl_cart` WHERE `session_id`='".session_id()."' AND `customer_id`=".$_SESSION['_user_id'];
				}else{
					$sql = "SELECT SUM(`tax_amount`) FROM `tbl_cart` WHERE `session_id`='".session_id()."'";
				}					
				$res = $this->get($sql);
				$row = $this->fetch_array($res);
				return $row[0];
			}
			
		}

		public function error($arg_error_msg) 
		{
			if(empty($arg_error_msg)==false) 
			{
				$error_msg = "<div style=\"font-family: Tahoma; font-size: 11px; padding: 10px; background-color: #FFD1C4; color: #990000; font-weight: bold; border: 1px solid #FF0000; text-align: center;\">";
				$error_msg.= $arg_error_msg;
				$error_msg.= "</div>";
				return $error_msg;
			}
		}	
		
	}
?>