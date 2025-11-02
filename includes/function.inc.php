<?php
	class Functions extends Security
	{
		public $redirectBase;
		
		public function Redirect($url,$msg = NULL) 
		{
			if($msg!=NULL)
			{
				if(strstr($url, '?') == true)
				{
					$char = "&";
				}
				else
				{
					$char = "?";
				}
				$url = $url.$char."msg=".urlencode($msg);
			}
			header("Location: ".$url);
			exit();
		}
		
		// If the language is not present in URL, it will be redirected to home page
		public function RedirectWithoutLanguage()
		{
			
		}
		
		function GetAllHeightSame($ArrName, $ClassName, $MaxHeight)
		{
			$tt = "var ".$ArrName." = new Array();\n";
			$tt.= "\t$('.".$ClassName."').each(function() {\n";
			$tt.= "\t\t".$ArrName.".push($(this).height());\n";
			$tt.= "\t});\n\n";
			
			$tt.= "\tvar ".$MaxHeight." = Math.max(...".$ArrName.")\n";
			$tt.= "\t$('.".$ClassName."').each(function() {\n";
			$tt.= "\t\t$(this).height(".$MaxHeight.");\n";
			$tt.= "\t});\n";
			
			echo $tt;
		}
		
		public function DispCustomError()
		{
			$error_dir = dirname(__FILE__);
			$error_dir = str_replace(DIRECTORY_SEPARATOR."includes", "", $error_dir);
			
			$error_text = file_get_contents($error_dir.DIRECTORY_SEPARATOR."_error.txt");
			
			$error_text = $this->getHtmlError($error_text);
			
			return $error_text;
		}
		
		public function SeoContentFilter($Content1, $Content2, $MaxLength = 255, $Type = 'C')
		{
			if(empty($Content1) == TRUE)
			{
				$str = $this->getOnlyText($Content2, FALSE);
				$str = str_replace("\r\n", " ", $str);
				$str = str_replace("\n", " ", $str);
				if($Type == 'C')
				{
					if(strlen($str) > $MaxLength) $str = substr($str, 0, $MaxLength);
				}
				else
				{
					$WordContent = str_word_count($str, 1);
					$WordContent = array_slice($WordContent, 0, $MaxLength, TRUE);
					$str = implode(" ", $WordContent);
				}
				
				$str = htmlspecialchars($str, ENT_COMPAT);
				return $str;
			}
			else
			{
				return $Content1;
			}
		}
		
		public function RandOtp($length = 6)
		{
			//$numbers = str_shuffle("1234567890");			
			$numbers = rand(10000000, 99999999);
			return substr($numbers, 0, $length);
		}
		
		public function RandPwdGtr($length, $count = 1, $characters = 'lower_case,upper_case,numbers,special_symbols')
		{
			$symbols = array();
			$passwords = array();
			$used_symbols = '';
			$pass = '';
			
			$symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
			$symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$symbols["numbers"] = '1234567890';
			$symbols["special_symbols"] = '!?~@#-_+<>[]{}';
			
			$characters = explode(",", $characters); // get characters types to be used for the passsword
			
			foreach($characters as $key=>$value)
			{
				$used_symbols .= $symbols[$value]; // build a string with all characters
			}
			$symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1
			
			for($p = 0; $p < $count; $p++)
			{
				$pass = '';
				for($i = 0; $i < $length; $i++)
				{
					$n = rand(0, $symbols_length); // get a random character from the string with all characters
					$pass.= $used_symbols[$n]; // add the character to the password string
				}
				$passwords[] = $pass;
			}
			
			return $passwords[0];
		}
		
		public function CreateCookie($CookieName, $CookieVal, $CookieExpTime = '0')
		{
			/*
				Timing is calculating using "strtotime" function. Please find the detail over Google.
				Examples are:
					+1 hour
					+1 month
					+1 day
					+1 year
			*/
			$Timining = ($CookieExpTime == '0') ? 0 : "+".strtotime($CookieExpTime);
			@setcookie($CookieName, $this->EncryptDecrypt($CookieVal), $Timining, "/", $_SERVER['HTTP_HOST']);
		}
		
		public function GetCookie($CookieName)
		{
			if(empty($_COOKIE[$CookieName]) == TRUE)
			{
				return "";
			}
			else
			{
				return $this->EncryptDecrypt($_COOKIE[$CookieName], 'decrypt');	
			}
		}
		
		public function DeleteCookie($CookieName)
		{
			@setcookie($CookieName, "", time()-42000, "/", $_SERVER['HTTP_HOST']);
		}
		
		public function CovtToFloat($number)
		{
			return number_format((float)$number, 2, '.', '');
		}
		
		public function ThumbImgZcOrFar($SrcImg, $ProvWidth, $ProvHeight, $FarBG = "FFFFFF")
		{
			list($width, $height, $type, $attr) = getimagesize($SrcImg);
			
			if($width > $ProvWidth && $height > $ProvHeight)
			{
				$phpThumbSettings = "w=".$ProvWidth."&h=".$ProvHeight."&zc=1";
			}
			else
			{
				$phpThumbSettings = "w=".$ProvWidth."&h=".$ProvHeight."&far=1&bg=".$FarBG;
			}
			
			return $phpThumbSettings;
		}
		
		public function RemoveSpecChar($Str)
		{
			$SpecStr = trim($Str);
			$CharArr = array(
				"`", "~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_",
				"+", "=", "{", "[", "]", "}", ":", ";", "<", ">", ",", "?", "/", "\\",
				"|", "'", '"', ".", "*"
			);
			
			$SpecStr = str_replace($CharArr, "", $SpecStr);
			
			return $SpecStr;
		}
		
		public function RemoveSpecCharNew($Str)
		{
			$SpecStr = trim($Str);
			$CharArr = array(
				"`"
			);
			
			$SpecStr = str_replace($CharArr, "", $SpecStr);
			
			return $SpecStr;
		}
		
		public function PrintArr($arr)
		{
			echo "<pre>\n";
			print_r($arr);
			echo "</pre>\n";
		}
		
		public function MoveToTop(&$array, $key) {
			$temp = array($key => $array[$key]);
			unset($array[$key]);
			$array = $temp + $array;
		}
		
		public function AdmMenuGetKeyToMove($SearchArr)
		{
			foreach($SearchArr as $key => $val)
			{
				if(is_array($val) == TRUE)
				{
					foreach($val as $SubKey => $SubVal)
					{
						$CurrPG = strtok($SubVal,'?');
						if(basename($_SERVER['PHP_SELF']) == $CurrPG)
						{
							$ReturnKey = $key;
						}
					}
				}
				else
				{
					$CurrPG = strtok($val,'?');
					if(basename($_SERVER['PHP_SELF']) == $CurrPG)
					{
						$ReturnKey = $key;
					}
				}
			}
			
			return $ReturnKey;
		}
		
		public function getBrowser() 
		{ 
			$u_agent = $_SERVER['HTTP_USER_AGENT']; 
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version= "";
			
			//First get the platform?
			if(preg_match('/linux/i', $u_agent))
			{
				$platform = 'Linux';
			}
			elseif(preg_match('/macintosh|mac os x/i', $u_agent))
			{
				$platform = 'Mac';
			}
			elseif(preg_match('/windows|win32/i', $u_agent))
			{
				$platform = 'Windows';
			}
		
			// Next get the name of the useragent yes seperately and for good reason
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Internet Explorer'; 
				$ub = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$bname = 'Mozilla Firefox'; 
				$ub = "Firefox"; 
			}
			elseif(preg_match('/OPR/i',$u_agent)) 
			{ 
				$bname = 'Opera'; 
				$ub = "Opera"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$bname = 'Google Chrome'; 
				$ub = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$bname = 'Apple Safari'; 
				$ub = "Safari"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$bname = 'Netscape'; 
				$ub = "Netscape"; 
			} 
			
			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			
			$pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			
			if (!preg_match_all($pattern, $u_agent, $matches))
			{
				// we have no matching number just continue
			}
			
			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1)
			{
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
				{
					$version= $matches['version'][0];
				}
				else
				{
					$version= $matches['version'][1];
				}
			}
			else
			{
				$version= $matches['version'][0];
			}
			
			// check if we have a number
			if ($version==null || $version=="") $version="?";
			
			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'   => $pattern
			);
		}
		
		public function ImageSizeResize($aWidth, $rWidth, $rHeight)
		{
			if($aWidth < $rWidth)
			{
				$perc = ceil(($aWidth / $rWidth) * 100);
				$rWidth = $aWidth;
				
				$rHeight = ceil(($rHeight * $perc) / 100);
			}
			
			return array($rWidth, $rHeight);		 
		}
		
		public function DispDate($StrDate)
		{
			//$RetVal = ($StrDate == "" || $StrDate == NULL) ? "&nbsp;" : date("d/m/Y", strtotime($StrDate));
			if($StrDate == "" || $StrDate == NULL)
			{
				$RetVal = "&nbsp;";
			}
			else
			{
				$DTArr = explode(" ", $StrDate);
				$DateArr = explode("-", $DTArr[0]);
				$RetVal = $DateArr[2]."/".$DateArr[1]."/".$DateArr[0];
			}
			
			return $RetVal;
		}
		
		public function StripTags($str)
		{
			/*$allow_tags_arr = array(
				'blockquote','ol','ul','li','br','span','b','i','u','strike','div','font','a','strong','p','sup','sub','h1','h2','h3','h4','h5','h6','pre'
			);*/
			
			$allow_tags_arr = array(
				'blockquote','ol','ul','li','br','span','b','i','u','strike','div','font','a','strong','p','sup','sub','hr','img', 'em', 'h1','h2','h3','h4','h5','h6'
			);
			
			$allow_tags = '<'.implode('><', $allow_tags_arr).'>';
			
			$output = strip_tags($str, $allow_tags);
			
			/*
			$output = preg_replace("/<p[^>]*?>/", "", $output);
			$output = str_replace("</p>", "<br /><br />", $output);
			*/
			
			return $output;
		}
		
		public function getYoutubeVideoId($iframeCode)
		{
			// Extract video url from embed code
			return preg_replace_callback('/<iframe\s+.*?\s+src=(".*?").*?<\/iframe>/', function ($matches) {
				// Remove quotes
				$youtubeUrl = $matches[1];
				$youtubeUrl = trim($youtubeUrl, '"');
				$youtubeUrl = trim($youtubeUrl, "'");
				// Extract id
				preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $youtubeUrl, $videoId);
				return $youtubeVideoId = isset($videoId[1]) ? $videoId[1] : "";
			}, $this->getHTMLDecode($iframeCode));		
		}
		
		public function ParseIframe($IframeCode, $Width, $Height)
		{
			$iframe = $this->getHTMLDecode($IframeCode);
			
			$iframe = preg_replace('/height="(.*?)"/i', 'height="'.$Height.'"', $iframe);
			$iframe = preg_replace('/width="(.*?)"/i', 'width="'.$Width.'"', $iframe);
			
			return $iframe;
		}
		
		public function EncryptDecrypt($string, $action = 'encrypt')
		{
			$output = false;
			
			$encrypt_method = "AES-256-CBC";
			$secret_key = 'in8vuvc8jma69szfx9zip3oj79zqmetjzjhc5x42rfsid21g';
			$secret_iv = ')mVa8hT&Ahr;yG;[fM';
			
			// hash
			$key = hash('sha256', $secret_key);
			
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
			
			if($action == 'encrypt')
			{
				$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
				$output = base64_encode($output);
			}
			
			if($action == 'decrypt')
			{
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
			
			return $output;
		}
		
		public function XssPOST($String, $decode = FALSE)
		{
			return $this->setValue($this->XssClean($String), $decode);	
		}
		
		/*public function get($variable)
		{
			return $this->XssClean($_GET[$variable]);	
		}*/
		
		public function post($variable, $decode = FALSE)
		{
			return $this->XssPOST($_POST[$variable], $decode);	
		}
		
		/*public function setValue($String, $decode = FALSE) 
		{
			$str = trim($String);
			if($decode === TRUE) $str = urldecode($str); 
			if(!get_magic_quotes_gpc()) 
			{
				$str = addslashes($str);
			}
			return $str;
		}*/
		
		public function setValue($String, $SpecialChar = TRUE, $decode = FALSE) 
		{
			if(empty($String) == TRUE && $String!=0)
			{
				return "";
			}
			else
			{
				//$str = $this->XssClean(trim($String));
				$str = trim($String);
			
				/*$str = str_replace(array("‘", "’", "´"), "'", $str);
				$str = str_replace(array(" ‘ ", " ’ ", " ´ "), "'", $str);
				$str = str_replace(array("“", "”"), '"', $str);				
				$str = str_replace(array("-", " - "), '-', $str);*/
				
				if($decode === TRUE) $str = urldecode($str);
				if($SpecialChar == TRUE)
				{
					$str = htmlspecialchars($str, ENT_COMPAT); 
				}
				
				/*if(!get_magic_quotes_gpc()) 
				{
					$str = addslashes($str);
				}*/
				$str = addslashes($str);
				return $str;
			}
		}
		
		public function PostCkEditor($String)
		{
			return $this->setValue($this->StripTags($String), TRUE);
		}
		
		public function NoEnter($String, $SetVal = TRUE)
		{
			$str = trim($String);
			if($SetVal == TRUE) $str = $this->setValue($str);
			$str = str_replace("\r\n", " ", $str);
			$str = str_replace("\n", " ", $str);
			return $str;
		}
		
		public function getHTMLDecode($String)
		{
			$str = '';
			if(empty($String) == FALSE)
			{
				$str = htmlspecialchars_decode($this->getValue($String), ENT_COMPAT);
				$str = preg_replace("#([^>])&nbsp;#ui", "$1 ", $str);				
			}
			return $str;
		}
		
		public function getOnlyText($String, $ForceRemoveSC = FALSE)
		{
			$str = $this->getValueST($String);
			$search_keywords = array('&ldquo;', '&rdquo;', '&quot;', '&lsquo;', '&rsquo;', '&#39;', '&Prime;', '&prime;');
			$str = str_replace($search_keywords, "", $str);
			if($ForceRemoveSC == TRUE) $str = $this->RemoveSpecChar($str);
			
			return $str;
		}
		
		public function getValueST($String, $HTMLDecode = "Y")
		{
			$String = ($HTMLDecode == 'Y') ? $this->getHTMLDecode($String) : $this->getValue($String);			
			return $String;
		}
		
		public function POST_VAL($variable)
		{
			if(empty($_POST[$variable]) == FALSE)
			{
				return $_POST[$variable];
			}else{
				return '';
			}
		}
		
		public function getValue($String) 
		{
			if(empty($String) == FALSE)
			{
				$String = stripslashes(trim($String));
			}
			//$String = $this->FormatLinksInText($String);
			return $String;
		}	
		
		public function getSmallerTxt($String, $MaxLength = 100)
		{
			//$str = strip_tags($this->getHTMLDecode($String));
			$str = $this->getOnlyText($String);
			$str = str_replace("\r\n", " ", $str);
			$str = str_replace("\n", " ", $str);
			
			if(strlen($str) > $MaxLength) $str = substr($str, 0, $MaxLength)." ...";
			
			return $str;
		}
		
		public function getSmallerTxtMB($String, $MaxLength = 100)
		{
			//$str = strip_tags($this->getHTMLDecode($String));
			$str = $this->getOnlyText($String);
			$str = str_replace("\r\n", " ", $str);
			$str = str_replace("\n", " ", $str);
			
			if(strlen($str) > $MaxLength) $str = mb_substr($str, 0, $MaxLength, "utf-8")." ...";
			
			return $str;
		}
		
		public function br($str) 
		{
			$str = $this->getValue(trim($str));
			return nl2br($str);
		}
		
		public function alert($msg) 
		{
			$scr = "<script language=\"javascript\" type=\"text/javascript\">\n";
			$scr.= "alert(\"".$msg."\");\n";
			$scr.= "</script>\n";
			echo $scr;
		}
		
		public function location($path,$opener=false) 
		{
			$scr = "<script type=\"text/javascript\">\n";
			if($opener===true) 
			{
				$scr.= "window.opener.location.href=\"".$path."\";\n";
			} else 
			{
				$scr.= "window.location.href=\"".$path."\";\n";
			}			
			$scr.= "</script>\n";
			echo $scr;
		}
		
		public function close() 
		{
			$scr = "<script type=\"text/javascript\">\n";
			$scr.= "self.close();\n";
			$scr.= "</script>\n";
			echo $scr;
		}
		
		public function reload($opener=false) 
		{
			$scr = "<script type=\"text/javascript\">\n";
			if($opener == true) 
			{
				$scr.= "window.opener.location.reload();\n";
			} 
			else 
			{
				$scr.= "window.location.reload();\n";
			}			
			$scr.= "</script>\n";
			echo $scr;
		}
		
		public function isLogin($getSessionName,$getBackPage,$callBack = "backend") 
		{
			//if(!isset($_SESSION[$getSessionName])) 
			if(empty($_SESSION[$getSessionName]) == TRUE)
			{
				if(empty($_SERVER['QUERY_STRING'])===FALSE) 
				{
					$redirectTo = basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'];
				} 
				else 
				{
					$redirectTo = basename($_SERVER['PHP_SELF']);
				}
				
				//$this->location($getBackPage."?redirectTo=".base64_encode($this->redirectBase."/".$redirectTo));
				//$this->location($getBackPage.base64_encode($this->redirectBase."/".$redirectTo));
				//$this->location($getBackPage);
				//exit();
				
				if($callBack == "frontend") 
				{
					
					$redirectTo = $_SERVER['REQUEST_URI']; //str_replace(".php","",$redirectTo);						
					$this->Redirect($getBackPage."?redirect=".base64_encode($redirectTo));	 				
				}
				else
				{
					$this->Redirect($getBackPage."?redirectTo=".base64_encode($this->redirectBase."/".$redirectTo));	
				}
			}
		}
		
		public function isLoginFB($getSessionName,$getBackPage) 
		{
			if(!isset($_SESSION[$getSessionName])) 
			{
				if(empty($_SERVER['QUERY_STRING'])===false) 
				{
					$redirectTo = basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'];
				} 
				else 
				{
					$redirectTo = basename($_SERVER['PHP_SELF']);
				}

				$this->location($getBackPage);
				exit();
			}
		}
		
		public function getHtmlError($msg) 
		{
			return "<div class=\"error-msg MsgDisp\"><i class='fa fa-times-circle'></i> ".$msg."</div>";
		}
		
		public function getHtmlMessage($msg) 
		{
			return "<div class=\"success-msg MsgDisp\"><i class='fa fa-check'></i> ".$msg."</div>";
		}
		
		public function getHtmlInfo($msg) 
		{
			return "<div class=\"info-msg MsgDisp\"><i class='fa fa-info-circle'></i> ".$msg."</div>";
		}
		
		public function getHtmlWarning($msg) 
		{
			return "<div class=\"warning-msg MsgDisp\"><i class='fa fa-warning'></i> ".$msg."</div>";
		}
		
		public function getHtmlErrorSmall($msg) 
		{
			return "<div class=\"error-msg-sm\"><i class='fa fa-times-circle'></i> ".$msg."</div>";
		}
		
		public function getHtmlMessageSmall($msg) 
		{
			return "<div class=\"success-msg-sm\"><i class='fa fa-check'></i> ".$msg."</div>";
		}
				
		public function CreateFolder($Path,$Permission) 
		{
			if(!is_dir($Path) == true) 
			{
				@mkdir($Path,$Permission) or $this->error($php_errormsg);
				@chmod($Path,$Permission) or $this->error($php_errormsg);
			}
		}

		public function CreateFile($filename,$path,$text) 
		{
			$output_file = $path."/".$filename;
			if(is_file($output_file)===true) @unlink($output_file);
			$handle = fopen($output_file,"w+");
			fwrite($handle,$text);
			@chmod($output_file,0777);
			fclose($handle);
		}

		public function DeleteFolder($path) 
		{
			/*
				While using this function make sure that the folder and all the
				sub folders and files must have 0777 permission otherwise
				the function may will give error
			*/
			if(is_dir($path)===true) 
			{
				$d = dir($path); 
				while($entry = $d->read()) 
				{ 
					if($entry!="." && $entry!="..") 
					{ 
						if(is_dir($path."/".$entry)===true) 
						{
							$this->DeleteFolder($path."/".$entry);
						} 
						else 
						{
							@unlink($path."/".$entry);
						}
					} 
				} 
				$d->close();
				@rmdir($path);
			}
		}

		public function DeleteFile($filename) 
		{
			if(file_exists($filename)===true && is_file($filename)===true) @unlink($filename);
		}

		public function getWebsiteFullURL($url) 
		{
			$url = $this->getValue($url);
			if($url == "#") 
			{
				$website = "#";
			} 
			else 
			{
				if(preg_match("/\bhttp\b/i",$url) == TRUE || preg_match("/\bhttps\b/i",$url) == TRUE)  
				{
					$website = $url;
				} 
				else 
				{
					$website = "http://".$url;
				}
			}
			return $website;
		}

		public function makePassword($pwd, $decode = FALSE) 
		{
			$pass = trim($pwd);
			if($decode === TRUE) $pass = urldecode($pass); 
			$pass = md5(strrev($pass));
			return $pass;
		}
		
		public function RandomName($filename) 
		{
			$file_array = explode(".",$filename);
			$file_ext = $file_array[count($file_array)-1];
			$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
			return $new_file_name;
		}
		
		public function getRandomNameImage($filename) 
		{
			$file_array = explode(".",$filename);
			$file_ext = $file_array[count($file_array)-1];
			$new_file_name = $this->RandomNumber(20).".".$file_ext;
			return $new_file_name;
		}

		public function getRandomName($filename) 
		{
			$file_array = explode(".",$filename);
			$file_ext = end($file_array);
			$new_file_name = $this->RandomNumber(10).".".$file_ext;
			return $new_file_name;
		}
		
		public function getRandomNameMicro($filename) 
		{
			$file_array = explode(".",$filename);
			$file_ext = end($file_array);
			//array_pop($file_array);
			//$new_file_name = implode("-", $file_array)."-".$this->RandomNumberMicro(10).".".$file_ext;
			$new_file_name = $this->RandomNumberMicro(10).".".$file_ext;
			return $new_file_name;
		}
		
		public function RandomNumber4() 
		{
			$random = date('y').date('m');			
			return $random;
		}
		
		public function RandomNumOnly($length='') 
		{
			srand((double)microtime()*1000000); 
			$data = "123456789"; 
			for($i = 0;$i < $length;$i++) 
			{
				$random.= substr($data,(rand()%(strlen($data))),1); 
			}
			return $random;
		}
		
		public function RandomNumber($length='', $datetime = TRUE) 
		{
			if(empty($length)===true) 
			{
				$random = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s');
			} 
			else 
			{				 
				srand((double)microtime()*1000000); 
				$data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
				for($i = 0;$i < $length;$i++) 
				{
					$random.= substr($data,(rand()%(strlen($data))),1); 
				}
				if($datetime === TRUE) $random = $random."-".date("Ymd")."-".date("His");
			}
			return $random;
		}
		
		public function MicrotimeFloat()
		{
		    usleep(9876);
		    list($usec, $sec) = explode(" ", microtime());
		    $time = ((float)$usec + (float)$sec);
		    
		    list($usec, $sec) = explode(".", $time);
		    $time = ((float)$usec + (float)$sec);
		    
		    return $time;
		}
		
		public function RandomNumberMicro($length=10) 
		{
			srand((double)microtime()*1000000);
			$data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
			for($i = 0;$i < $length;$i++){
				$random.= substr($data,(rand()%(strlen($data))),1); 
			}
			//$random = $random.date("Ymd").date("His");
			$random = $random."-".date("Ymd")."-".$this->MicrotimeFloat();
			return $random;
		}

		public function SendMail($MailToAddress,$MailMessage,$MailSubject,$Cc,$Bcc,$MailFromAddress) 
		{
			$header = "MIME-Version: 1.0\r\n";
			$header.= "Content-Type: text/html; charset=iso-8859-1\r\n";
			$header.= "From: ".$MailFromAddress."\r\n";
			if(empty($Cc)===false) 
			{
				$header.= "Cc: ".$Cc."\r\n";
			}
			if(empty($Bcc)===false) 
			{
				$header.= "Bcc: ".$Bcc."\r\n";
			}
			@mail($MailToAddress,$MailSubject,$MailMessage,$header) or die($this->error($php_errormsg));
		}
		
		public function ChangeFormatDate2($dt) 
		{
			/*$VarTotalDate = explode("/", $dt);
			$VarDate = $VarTotalDate[0];
			$VarMonth = $VarTotalDate[1];
			$VarYear = $VarTotalDate[2];
			$CompleteDate = $VarYear.'-'.$VarMonth.'-'.$VarDate;		
			return $CompleteDate;*/
			
			$VarTotalDate = explode("/", $dt);
			$VarDate = $VarTotalDate[1];
			$VarMonth = $VarTotalDate[0];
			$VarYear = $VarTotalDate[2];
			$CompleteDate = $VarYear.'-'.$VarMonth.'-'.$VarDate;
		}
		
		public function ChangeFormatDate($dt,$type="mysql") 
		{
			$VarTotalDate = explode("-",$dt);
			if($type == 'mysql') 
			{
				$VarDate = $VarTotalDate[1];
				$VarMonth = $VarTotalDate[0];
				$VarYear = $VarTotalDate[2];
				$CompleteDate = $VarYear.'-'.$VarMonth.'-'.$VarDate;
			} 
			elseif($type == 'expand') 
			{
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				$CompleteDate = date("F j, Y",mktime(0,0,0,$VarTotalDate[1],$VarTotalDate[2],$VarTotalDate[0]));
			} 
			elseif($type == 'IND') 
			{
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				// Indian Format
				$CompleteDate = $VarDate.'/'.$VarMonth.'/'.$VarYear;				
			} 
			elseif($type == 'short') 
			{
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				$CompleteDate = date("M j",mktime(0,0,0,$VarTotalDate[1],$VarTotalDate[2]));
			} 
			elseif($type == 'medium') 
			{
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				$CompleteDate = date("M j, Y",mktime(0,0,0,$VarTotalDate[1],$VarTotalDate[2],$VarTotalDate[0]));
			} 
			elseif($type == 'mysqformat') {
				$VarDate = $VarTotalDate[0];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[2];
				$CompleteDate = $VarYear.'-'.$VarMonth.'-'.$VarDate;
			}
			elseif($type == 'mysqlIND') {
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				// Indian Format
				$CompleteDate = $VarDate.'-'.$VarMonth.'-'.$VarYear;	
			}
			else 
			{
				$VarDate = $VarTotalDate[2];
				$VarMonth = $VarTotalDate[1];
				$VarYear = $VarTotalDate[0];
				// Indian Format
				//$CompleteDate = $VarDate.'-'.$VarMonth.'-'.$VarYear;
				// USA Format
				$CompleteDate = $VarMonth.'-'.$VarDate.'-'.$VarYear;
			}
			return $CompleteDate;
		}
		
		public function error($arg_error_msg) 
		{
			if(empty($arg_error_msg)===false) 
			{
				$error_msg = "<div style=\"font-family: Tahoma; font-size: 11px; padding: 10px; background-color: #FFD1C4; color: #990000; font-weight: bold; border: 1px solid #FF0000; text-align: center;\">";
				$error_msg.= $arg_error_msg;
				$error_msg.= "</div>";
				return $error_msg;
			}
		}
		
		public function TimeFormat($time)
		{
			$time_array = explode(":",$time);
			$mktime = mktime($time_array[0],$time_array[1],$time_array[2],0,0,0);
			return date("g:i A",$mktime);
		}
		
		public function getSize($size) 
		{
			if($size >= 1048576) 
			{
				$return = round($size / 1048576,2)." MB";
			} 
			elseif($size >= 1024) 
			{
				$return = round($size / 1024,2)." KB";
			} 
			else 
			{
				$return = $size." Bytes";
			}
			return $return;
		}
		
		 function calculateSize($size, $sep = ' '){
			$unit = null;
			$units = array('B', 'KB', 'MB', 'GB', 'TB');
			 
			for($i = 0, $c = count($units); $i < $c; $i++)
				{
					if ($size > 1024)
				{
					$size = $size / 1024;
				}
				else
				{
					$unit = $units[$i];
					break;
				}
			}			 
			return round($size, 2).$sep.$unit;
		}
		
		public function Round($amt, $display = TRUE) 
		{
			$amt = round($this->getValue($amt),2);
			if($display == TRUE) $amt = number_format($amt, 2, '.', '');
			return $amt;
		}
		
		public function RemovedHtmlString($str) 
		{
			$var = $this->getValue($str);
			$var = strip_tags($var);
			return $var;
		}
		
		public function getFileExt($file_name)
		{
			$file_array = explode(".", $file_name);
			$file_ext = end($file_array);
			return $file_ext;
		}
		
		public function IsImage($file_name)
		{
			$FileExt = $this->getFileExt($file_name);
			return ($FileExt == "jpg" || $FileExt == "jpeg" || $FileExt == "gif" || $FileExt == "png") ? TRUE : FALSE;
		}
		
		public function getQuery($RemoveArray = array('page')) 
		{
			$get = $_GET;
			 
			foreach($RemoveArray as $val)
			{
				if(array_key_exists($val,$get) === true)
				{
					unset($get[$val]);
				}
			}
			return http_build_query($get);
		}
		
		public function remove_qs_key($urls, $key)
		{
			$key = explode('|',$key);
			foreach($key as $valkey){
				$urls= preg_replace('/(?:&|(\?))'.$valkey.'=[^&]*(?(1)&|)?/i', "$1", $urls);
			}
			return $urls;
		}
		
		
		
		public function ConvertNumbertoWords($number=0) {   
					
		    $hyphen      = ' ';
		    $conjunction = ' ';
		    $separator   = ' ';
		    $negative    = 'negative ';
		    //$decimal     = ' point ';
		    $decimal     = '';
		    $dictionary  = array(
			   //0                   => 'zero',
			   1                   => 'one',
			   2                   => 'two',
			   3                   => 'three',
			   4                   => 'four',
			   5                   => 'five',
			   6                   => 'six',
			   7                   => 'seven',
			   8                   => 'eight',
			   9                   => 'nine',
			   10                  => 'ten',
			   11                  => 'eleven',
			   12                  => 'twelve',
			   13                  => 'thirteen',
			   14                  => 'fourteen',
			   15                  => 'fifteen',
			   16                  => 'sixteen',
			   17                  => 'seventeen',
			   18                  => 'eighteen',
			   19                  => 'nineteen',
			   20                  => 'twenty',
			   30                  => 'thirty',
			   40                  => 'fourty',
			   50                  => 'fifty',
			   60                  => 'sixty',
			   70                  => 'seventy',
			   80                  => 'eighty',
			   90                  => 'ninety',
			   100                 => 'hundred',
			   1000                => 'thousand',
			   100000             => 'lakh',
			   10000000           => 'crore'
			   /*1000000000000       => 'trillion',
			   1000000000000000    => 'quadrillion',
			   1000000000000000000 => 'quintillion'*/
		    );
		   
		    if (!is_numeric($number)) {
			   return false;
		    }
		   
		    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			   // overflow
			   trigger_error('convert_number_to_words only accepts numbers between -'.PHP_INT_MAX.' and '.PHP_INT_MAX, E_USER_WARNING);
			   return false;
		    }
		
		    if ($number < 0) {
			   return $negative.convert_number_to_words(abs($number));
		    }
		   
		    $string = $fraction = null;
		   
		    if (strpos($number, '.') !== false) {
			   list($number, $fraction) = explode('.', $number);
		    }
		   
		    switch (true) {
			   case $number < 21:
				  $string = $dictionary[$number];
				  break;
			   case $number < 100:
				  $tens   = ((int) ($number / 10)) * 10;
				  $units  = $number % 10;
				  $string = $dictionary[$tens];
				  if ($units) {
					 $string .= $hyphen . $dictionary[$units];
				  }
				  break;
			   case $number < 1000:
				  $hundreds  = (int)$number / 100;
				  $remainder = $number % 100;
				  $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				  if ($remainder) {
					 $string .= $conjunction . $this->ConvertNumbertoWords($remainder);
				  }
				  break;
		        case $number < 100000:
				  $thousands  =(int) $number / 1000;
				  
				  $tens   = ((int) ($thousands / 10)) * 10;
				  $units  = $thousands % 10;
				  $string = $dictionary[$tens];
				  if ($units) {
					 $string .= $hyphen . $dictionary[$units];
				  }
				  
				  $remainder = $number % 1000;
				  $string .= ' ' . $dictionary[1000];
				  if ($remainder) {
					 $string .= $conjunction . $this->ConvertNumbertoWords($remainder);
				  }
				  break;
			   case $number < 10000000:
				  $lakhs  = (int)$number / 100000;
				  
				  $tens   = ((int) ($lakhs / 10)) * 10;
				  $units  = $lakhs % 10;
				  $string = $dictionary[$tens];
				  if ($units) {
					 $string .= $hyphen . $dictionary[$units];
				  }
				  
				  $remainder = $number % 100000;
				  $string .= ' ' . $dictionary[100000];
				  if ($remainder) {
					 $string .= $conjunction . $this->ConvertNumbertoWords($remainder);
				  }
				  break;
			   default:
				  $baseUnit = pow(10000000, floor(log($number, 10000000)));
				  $numBaseUnits = (int) ($number / $baseUnit);
				  //exit();
				  $remainder = $number % $baseUnit;
				  $string = $this->ConvertNumbertoWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				  if ($remainder) {
					 $string .= $remainder < 100 ? $conjunction : $separator;
					 $string .= $this->ConvertNumbertoWords($remainder);
				  }
				  break;
		    }
		   
		    if (null !== $fraction && is_numeric($fraction)) {
			   $string .= $decimal;
			   $words = array();
			   foreach (str_split((string) $fraction) as $number) {
				  $words[] = $dictionary[$number];
			   }
			   $string .= implode(' ', $words);
		    }
		   
		    return trim($string);
		}
		
		public function CreateMediaFileName($FileName)
		{
			$file_array = explode(".", $FileName);
			$file_ext = end($file_array);
				
			array_pop($file_array);
			
			$file_name = implode(".", $file_array);
			
			// replace non letter or digits by -
			$file_name = preg_replace('~[^\pL\d]+~u', '-', $file_name);
			
			// transliterate
			$file_name = iconv('utf-8', 'us-ascii//TRANSLIT', $file_name);
			
			// remove unwanted characters
			$file_name = preg_replace('~[^-\w]+~', '', $file_name);
			
			// trim
			$file_name = trim($file_name, '-');
			
			// remove duplicate -
			$file_name = preg_replace('~-+~', '-', $file_name);
			
			if(empty($file_name))
			{
				return 'n-a';
			}
			
			$new_file_name = $file_name.".".$file_ext;
		
			return $new_file_name;	
		}
		
		public function slugify($text, $ForceLower = TRUE)
		{
			$text = $this->getHTMLDecode($text);
			
			// replace non letter or digits by -
			$text = preg_replace('~[^\pL\d]+~u', '-', $text);
			
			// transliterate
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
			
			// remove unwanted characters
			$text = preg_replace('~[^-\w]+~', '', $text);
			
			// trim
			$text = trim($text, '-');
			
			// remove duplicate -
			$text = preg_replace('~-+~', '-', $text);
			
			// lowercase
			if($ForceLower == TRUE) $text = strtolower($text);
			
			if(empty($text))
			{
				return 'n-a';
			}
		
			return $text;
		}
		
		public function base64_to_jpeg($base64_string, $output_file) 
		{
		    $ifp = @fopen($output_file, "wb");		
		    $data = explode(',', $base64_string);		
		    @fwrite($ifp, base64_decode($data[1]));
		    @fclose($ifp);
		    return $output_file;
		}
		
		public function RenameFile($filename, $rename, $reg_number = '')
		{
			$file_array = explode(".",$filename);
			$file_ext = end($file_array);
			$new_file_name = $rename.".".$file_ext;
			if(empty($reg_number)==FALSE)
			{
				$new_file_name = $reg_number."_".$new_file_name;
			}
			return $new_file_name;
		}
		
		public function create_zip($files = array(), $destination = '', $overwrite = false)
		{
			//if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && !$overwrite) { return false; }
			//vars
			$valid_files = array();
			//if files were passed in...
			if(is_array($files)) {
				//cycle through each file
				foreach($files as $file) {
					//make sure the file exists
					if(file_exists($file)) {
						$valid_files[] = $file;
					}
				}
			}
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new ZipArchive();
				if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				//add the files
				foreach($valid_files as $file) {
					$zip->addFile($file,$file);
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
		
		public function remove_querystring_var($url, $key)
		{
		    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
		    $url = substr($url, 0, -1);
		    return ($url);
		}
		
		public function ExtractKeyWords2($string) {
			mb_internal_encoding('UTF-8');
			$stopwords = array('quot');
			$string = preg_replace('/[\pP]/u', '', trim(preg_replace('/\s\s+/iu', '', mb_strtolower($string))));
			$matchWords = array_filter(explode(' ',$string) , function ($item) use ($stopwords) { return !($item == '' || in_array($item, $stopwords) || mb_strlen($item) <= 2 || is_numeric($item));});
			$wordCountArr = array_count_values($matchWords);
			arsort($wordCountArr);
			return array_keys(array_slice($wordCountArr, 0));
		}
		
		public function ExtractKeywords($str, $minWordLen = 2, $minWordOccurrences = 1, $asArray = true)
		{
			function keyword_count_sort($first, $sec)
			{
				return $sec[1] - $first[1];
			}
			
			$stopWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','home','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
			
			$str = preg_replace('/[^\p{L}0-9 ]/', ' ', $str);
			$str = trim(preg_replace('/\s+/', ' ', $str)); 	
			
			$words = explode(' ', $str);
			
			foreach($words as $key=>$item) 
			{
				if ($item == '' || in_array(strtolower($item), $stopWords) || preg_match('/^[0-9]{4}$/D', strtolower($item)) || preg_match('/^[0-9]{4}s$/D', strtolower($item))) 
				{
				    unset($words[$key]);
				}		
			}
			
			$keywords = array();
			while(($c_word = array_shift($words)) !== null)
			{
				if(strlen($c_word) < $minWordLen) continue;
		 
				$c_word = strtolower($c_word);
				if(array_key_exists($c_word, $keywords)) $keywords[$c_word][1]++;
				else $keywords[$c_word] = array($c_word, 1);
			}
			usort($keywords, 'keyword_count_sort');
		 
			$final_keywords = array();
			foreach($keywords as $keyword_det)
			{
				if($keyword_det[1] < $minWordOccurrences) break;
				array_push($final_keywords, $keyword_det[0]);
			}
			return $asArray ? $final_keywords : implode(', ', $final_keywords);
		}
		
		public function ImageOrientationGD($SourceFile)
		{
			$exif = @exif_read_data($SourceFile);
			if(empty($exif['Orientation']) == FALSE)
			{
				$image_info = getimagesize($SourceFile);
				if($image_info['mime'] == 'image/jpeg' || $image_info['mime'] == 'image/jpg')
				{
					$source_image = imagecreatefromjpeg($SourceFile);
				}
				
				if($image_info['mime'] == 'image/gif') 
				{
					$source_image = imagecreatefromgif($SourceFile);
				}
				
				if($image_info['mime'] == 'image/png')
				{
					$source_image = imagecreatefrompng($SourceFile);
					imagealphablending($source_image, FALSE);
        				imagesavealpha($source_image, TRUE);
				}
				
				switch($exif['Orientation'])
				{
					case 3:
						$image = imagerotate($source_image, 180, 0);
					break;
				
					case 6:
						$image = imagerotate($source_image, -90, 0);
					break;
				
					case 8:
						$image = imagerotate($source_image, 90, 0);
					break;
				}
				
				if($image_info['mime'] == 'image/jpeg' || $image_info['mime'] == 'image/jpg')
				{
					@imagejpeg($image, $SourceFile, 100);
				}
				
				if($image_info['mime'] == 'image/gif') 
				{
					@imagegif($image, $SourceFile);
				}
				
				if($image_info['mime'] == 'image/png')
				{
					@imagepng($image, $SourceFile, 9);
				}
			}
		}
		
		public function ImageOrientationImagic($SourceFile)
		{
			$image = new Imagick($SourceFile);
			$orientation = $image->getImageOrientation();
			
			switch($orientation)
			{
				case imagick::ORIENTATION_BOTTOMRIGHT: 
					$image->rotateimage("#000", 180); // rotate 180 degrees
				break;
	
				case imagick::ORIENTATION_RIGHTTOP:
					$image->rotateimage("#000", 90); // rotate 90 degrees CW
				break;
	
				case imagick::ORIENTATION_LEFTBOTTOM: 
					$image->rotateimage("#000", -90); // rotate 90 degrees CCW
				break;
			}
			
			$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
			$image->writeImage($SourceFile);
		}
		
		public function ApplyWaterMark($sourcefile, $watermarkfile, $position = 'CENTER')
		{
			#
			# $sourcefile = Filename of the picture to be watermarked.
			# $watermarkfile = Filename of the 24-bit PNG watermark file.
			#
	   
			//Get the resource ids of the pictures
			$watermarkfile_id = imagecreatefrompng($watermarkfile);
	   
			imagealphablending($watermarkfile_id, false);
			imagesavealpha($watermarkfile_id, true);
	
			$fileType = strtolower(substr($sourcefile, strlen($sourcefile)-3));
	
			switch($fileType)
			{
				case('gif'):
					$sourcefile_id = imagecreatefromgif($sourcefile);
				break;
			 
				case('png'):
					$sourcefile_id = imagecreatefrompng($sourcefile);
					//imagealphablending($sourcefile_id, FALSE);
        				//imagesavealpha($sourcefile_id, TRUE);
				break;
			 
				default:
					$sourcefile_id = imagecreatefromjpeg($sourcefile);
			}
	
			//Get the sizes of both pix  
			$sourcefile_width = imagesx($sourcefile_id);
			$sourcefile_height = imagesy($sourcefile_id);
			$watermarkfile_width = imagesx($watermarkfile_id);
			$watermarkfile_height = imagesy($watermarkfile_id);
	
			switch($position)
			{
				case "CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = ($sourcefile_height / 2) - ($watermarkfile_height / 2);
					break;
				case "BOTTOM_RIGHT":
					$dest_x = $sourcefile_width - $watermarkfile_width;
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				case "BOTTOM_LEFT":
					$dest_x = 0;
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				case "TOP_LEFT":
					$dest_x = 0;
					$dest_y = 0;
					break;
				case "TOP_RIGHT":
					$dest_x = $sourcefile_width - $watermarkfile_width;
					$dest_y = 0;
					break;
				case "TOP_CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = 0;
					break;
				case "BOTTOM_CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				default:
					// Default is center
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = ($sourcefile_height / 2) - ($watermarkfile_height / 2);
			}
	   
			// if a gif, we have to upsample it to a truecolor image
			if($fileType == 'gif')
			{
				// create an empty truecolor container
				$tempimage = imagecreatetruecolor($sourcefile_width, $sourcefile_height);
		  
				// copy the 8-bit gif into the truecolor image
				imagecopy($tempimage, $sourcefile_id, 0, 0, 0, 0, $sourcefile_width, $sourcefile_height);
		  
				// copy the source_id int
				$sourcefile_id = $tempimage;
			}
	
			imagecopy($sourcefile_id, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
				
			//Create a jpeg out of the modified picture
			switch($fileType)
			{
				// remember we don't need gif any more, so we use only png or jpeg.
				// See the upsaple code immediately above to see how we handle gifs
				case('png'):
					imagepng($sourcefile_id, $sourcefile, 9);
				break;
				
				case('gif'):
					imagegif($sourcefile_id, $sourcefile);
				break;
			 
				default:
					imagejpeg($sourcefile_id, $sourcefile, 100);
			}          
	 
			imagedestroy($sourcefile_id);
			imagedestroy($watermarkfile_id);
		}
		
		// Start ===============================================================================================================================================
		
		public function ApplyWaterMarkNew($sourcefile, $watermarkfile, $dest_x, $dest_y)
		{
			#
			# $sourcefile = Filename of the picture to be watermarked.
			# $watermarkfile = Filename of the 24-bit PNG watermark file.
			#
	   
			//Get the resource ids of the pictures
			$watermarkfile_id = imagecreatefrompng($watermarkfile);
	   
			imagealphablending($watermarkfile_id, false);
			imagesavealpha($watermarkfile_id, true);
	
			$fileType = strtolower(substr($sourcefile, strlen($sourcefile)-3));
	
			switch($fileType)
			{
				case('gif'):
					$sourcefile_id = imagecreatefromgif($sourcefile);
				break;
			 
				case('png'):
					$sourcefile_id = imagecreatefrompng($sourcefile);
					//imagealphablending($sourcefile_id, FALSE);
        				//imagesavealpha($sourcefile_id, TRUE);
				break;
			 
				default:
					$sourcefile_id = imagecreatefromjpeg($sourcefile);
			}
	
			//Get the sizes of both pix  
			$sourcefile_width = imagesx($sourcefile_id);
			$sourcefile_height = imagesy($sourcefile_id);
			$watermarkfile_width = imagesx($watermarkfile_id);
			$watermarkfile_height = imagesy($watermarkfile_id);
	
			/*switch($position)
			{
				case "CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = ($sourcefile_height / 2) - ($watermarkfile_height / 2);
					break;
				case "BOTTOM_RIGHT":
					$dest_x = $sourcefile_width - $watermarkfile_width;
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				case "BOTTOM_LEFT":
					$dest_x = 0;
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				case "TOP_LEFT":
					$dest_x = 0;
					$dest_y = 0;
					break;
				case "TOP_RIGHT":
					$dest_x = $sourcefile_width - $watermarkfile_width;
					$dest_y = 0;
					break;
				case "TOP_CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = 0;
					break;
				case "BOTTOM_CENTER":
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = $sourcefile_height - $watermarkfile_height;
					break;
				default:
					// Default is center
					$dest_x = ($sourcefile_width / 2) - ($watermarkfile_width / 2);
					$dest_y = ($sourcefile_height / 2) - ($watermarkfile_height / 2);
			}*/
			
			$dest_x = ($dest_x - 80); 
			$dest_y = ($dest_y - 50); 
	   
			// if a gif, we have to upsample it to a truecolor image
			if($fileType == 'gif')
			{
				// create an empty truecolor container
				$tempimage = imagecreatetruecolor($sourcefile_width, $sourcefile_height);
		  
				// copy the 8-bit gif into the truecolor image
				imagecopy($tempimage, $sourcefile_id, 0, 0, 0, 0, $sourcefile_width, $sourcefile_height);
		  
				// copy the source_id int
				$sourcefile_id = $tempimage;
			}
	
			imagecopy($sourcefile_id, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
				
			//Create a jpeg out of the modified picture
			switch($fileType)
			{
				// remember we don't need gif any more, so we use only png or jpeg.
				// See the upsaple code immediately above to see how we handle gifs
				case('png'):
					imagepng($sourcefile_id, $sourcefile, 9);
				break;
				
				case('gif'):
					imagegif($sourcefile_id, $sourcefile);
				break;
			 
				default:
					imagejpeg($sourcefile_id, $sourcefile, 100);
			}          
	 
			imagedestroy($sourcefile_id);
			imagedestroy($watermarkfile_id);
		}
		
		// End ================================================================================================================================================
		
		public function CompressImage($source_image, $compress_image, $force_new_wh = 'Y') 
		{
			$image_info = getimagesize($source_image);	
			list($Owidth, $Oheight) = getimagesize($source_image);	
			
			if($force_new_wh == 'Y')
			{
				$setWidthHeight = $this->NewWidthHeight($Owidth, $Oheight);
				$setWidthHeightArray = explode("|", $setWidthHeight);
				$newWidth = $setWidthHeightArray[0];
				$newHeight = $setWidthHeightArray[1];
			}
			else
			{
				$newWidth = $Owidth;
				$newHeight = $Oheight;
			}
			
			if($image_info['mime'] == 'image/jpeg' || $image_info['mime'] == 'image/jpg') 
			{
				$source_image = imagecreatefromjpeg($source_image);							
				$width = imagesx($source_image);
				$height = imagesy($source_image);
				$dst = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($dst, $source_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);							
				imagejpeg($dst, $compress_image, 100);
			} 
			elseif($image_info['mime'] == 'image/gif') 
			{
				$source_image = imagecreatefromgif($source_image);
				$width = imagesx($source_image);
				$height = imagesy($source_image);
				$dst = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($dst, $source_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);		
				imagegif($source_image, $compress_image);
			} 
			elseif($image_info['mime'] == 'image/png') 
			{
				$source_image = imagecreatefrompng($source_image);
				imagealphablending($source_image, FALSE);
        			imagesavealpha($source_image, TRUE);
				
				$width = imagesx($source_image);
				$height = imagesy($source_image);
				$dst = imagecreatetruecolor($newWidth, $newHeight);			
				imagecopyresampled($dst, $source_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);		
				imagepng($source_image, $compress_image, 9);
			}	
			
			//return $compress_image;
		}
		
		public function NewWidthHeight($OrgWidth, $OrgHeight) 	
		{			
			$NewWidth = 1200;
			$NewHeight = 900;	
			
			if($OrgWidth > $OrgHeight && $OrgWidth > $NewWidth)
			{
				$WidthPercentage = ceil(($NewWidth / $OrgWidth) * 100);		
				$NewHeight = ceil(($OrgHeight * $WidthPercentage) / 100);				
			}
			elseif($OrgHeight > $OrgWidth && $OrgHeight > $NewHeight)
			{
				$HeightPercentage = ceil(($NewHeight / $OrgHeight) * 100);		
				$NewWidth = ceil(($OrgWidth * $HeightPercentage) / 100);				
			}
			elseif($OrgWidth == $OrgHeight && $OrgWidth > $NewWidth)
			{
				$WidthPercentage = ceil(($NewWidth / $OrgWidth) * 100);		
				$NewHeight = ceil(($OrgHeight * $WidthPercentage) / 100);				
			}
			else
			{
				$NewWidth = $OrgWidth;
				$NewHeight = $OrgHeight;
			}
			
			return $NewWidth."|".$NewHeight;	
		}
		
		public function ConvertYoutube($string, $height) 
		{
			$String = $this->getHTMLDecode($string);
			
			return preg_replace(
				"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
				"<iframe width='100%' height='".$height."' src=\"https://www.youtube.com/embed/$2\" allowfullscreen></iframe>", $String				
			);
		}
		
		public function ParseGoogleMap($MapCode, $Width, $Height)
		{
			$iframe = preg_replace('/height="(.*?)"/i', 'height="'.$Height.'"', $MapCode);
			$iframe = preg_replace('/width="(.*?)"/i', 'width="'.$Width.'"', $iframe);
			
			return $this->getValue($iframe);
		}
		
		public function ParseYoutube($MapCode, $Height, $Width = '100%')
		{
			$iframe = $this->getHTMLDecode($MapCode);
			
			$iframe = preg_replace('/height="(.*?)"/i', 'height="'.$Height.'"', $iframe);
			$iframe = preg_replace('/width="(.*?)"/i', 'width="'.$Width.'"', $iframe);
			
			return $iframe;
		}
		
		public function CopyFile($FromFilePath, $DestDir, $NewRandName = TRUE)
		{
			$From_File_Arr = explode(DIRECTORY_SEPARATOR, $FromFilePath);
			$FileName = end($From_File_Arr);		
			
			if($NewRandName == TRUE)
			{
				$FileArr = explode(".", $FileName);
				$FileExt = end($FileArr);
				
				$NewFileName = $this->RandomNumber(10).".".$FileExt;
				$DestFilePath = $DestDir.DIRECTORY_SEPARATOR.$NewFileName;
			}
			else
			{			
				$NewFileName = $FileName;
				$DestFilePath = $DestDir.DIRECTORY_SEPARATOR.$FileName;
			}
			
			if(file_exists($FromFilePath) == TRUE && is_file($FromFilePath) == TRUE)
			{
				copy($FromFilePath, $DestFilePath);
			}
			
			return $NewFileName;
		}
		
		public function get_all_get()
		{
			   $output = "/?"; 
			   $firstRun = true; 
			   foreach($_GET as $key=>$val) 
			   { 
				if($key!= 'cat_slug' && $key!= 'sub_cat_slug' && $key!= 'brand_slug')
				{
				   if($key != $parameter) 
				   { 
					  if(!$firstRun) { 
						 $output .= "&"; 
					  } else { 
						 $firstRun = false; 
					  } 
					  $output .= $key."=".$val;
				    } 
				}
			   } 
		
		    return $output;
		} 
		
		public function NumberFormat($number, $country="")
		{
			if(empty($number) == true) $number = 0;
			if($country == "")
			{
				$val_number = number_format($number,2,".",",");
			}else{
				$val_number = number_format($number);
			}
			return $val_number;
		}
		
		public function NoResult($text1='', $text2='', $text3='')
		{
			$text ='<div align="center">
					<img src="'.MAIN_WEBSITE_URL.'/images/no-result.png" alt="" width="300" height="300" /><p>&nbsp;</p>';
					if(empty($text1)==false)
					{
					 $text.='<div style="font-size:30px; text-transform:uppercase;">'.$text1.'</div>';
					}
					if(empty($text2)==false)
					{
					 $text.='<div style="font-size:20px; font-weight:700;">'.$text2.'</div>';
					}
					if(empty($text3)==false)
					{
					 $text.='<div>'.$text3.'</div>';
					}
					
			$text.= '</div>';
			return $text;	  
		}
		
		public function RemoveMSWordChar($text1='')
		{
			$text = str_replace(chr(130), ',', $text1);    // Baseline single quote
			$text = str_replace(chr(132), '"', $text);    // Baseline double quote
			$text = str_replace(chr(133), '...', $text);  // Ellipsis
			$text = str_replace(chr(145), "'", $text);    // Left single quote
			$text = str_replace(chr(146), "'", $text);    // Right single quote
			$text = str_replace(chr(147), '"', $text);    // Left double quote
			$text = str_replace(chr(148), '"', $text);    // Right double quote
			$text = str_replace(chr(151), '-', $text);
			$text = str_replace("®", '', $text);
			$text = str_replace("&reg;", '', $text);
			
			$text = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'); 
			$text = str_replace("&#2013266093;", ' - ', $text);
			$text = str_replace("&#2013266070;", ' - ', $text);
			
			
			return $text;
		}
		
		public function RemoveHTMLFromText($str)
		{
			$allow_tags_arr = array(
				'blockquote','ol','ul','li','br','span','b','i','u','strike','div','a','strong','p','sup','sub','hr','img', 'em', 'h1','h2','h3','h4','h5','h6'
			);
			
			$allow_tags = '<'.implode('><', $allow_tags_arr).'>';
			
			$output = $this->getHTMLDecode($str);
			$output = strip_tags($output, $allow_tags);
			$output = preg_replace('/font-family.+?;/', "", $output);
			
			return $output;				
		}
		
		public function ConvertYoutubeEmbed($Url, $Width, $Height) {
		    return preg_replace(
			   "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
			   "<iframe src=\"//www.youtube.com/embed/$2\" width=\"".$Width."\" height=\"".$Height."\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>",
			   $Url);
		}
		
		public function FormatLinksInText($text)
		{
			$text = $this->getValue($text);
		    // Catch all links with protocol      
		    $reg = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,}(\/\S*)?/'; 
		    $formatText = preg_replace($reg, '<a href="$0" target="_blank" title="$0">$0</a>', $text);
		
		    // Catch all links without protocol
		    $reg2 = '/(?<=\s|\A)([0-9a-zA-Z\-\.]+\.[a-zA-Z0-9\/]{2,})(?=\s|$|\,|\.)/';
		    $formatText = preg_replace($reg2, '<a href="//$0" target="_blank" title="$0">$0</a>', $formatText);
		
		    // Catch all emails
		    $emailRegex = '/(\S+\@\S+\.\S+)\b/';
		    $formatText = preg_replace($emailRegex, '<a href="mailto:$1" target="_blank" title="$1">$1</a>', $formatText);
		    $formatText = nl2br($formatText);
		    return $formatText;
		}
		
		public function getToken($length)
		{
		  $token = "";
		  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		  $codeAlphabet.= "0123456789";
		  $max = strlen($codeAlphabet); // edited
		
		  for ($i=0; $i < $length; $i++) {
			 $token .= $codeAlphabet[random_int(0, $max-1)];
		  }
		
		  return $token;
		}
		
	}
?>