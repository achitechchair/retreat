<?php
	class Methods extends Functions
	{
		private $db;
		private $MediaCounter = 0;
		public $TotalMediaFiles = array();	
		private $MediaMainFileName = "";
		
		
		
		public function __construct($param)
		{
			$this->db = $param;
		}
		
		public function ReloadMedia()
		{
			$sql = "SELECT `media_fname` FROM `tbl_media`";
			$res = $this->db->get($sql, __FILE__, __LINE__);
			if($this->db->num_rows($res) > 0)
			{
				while($row = $this->db->fetch_array($res))
				{
					array_push($this->TotalMediaFiles, $row['media_fname']);
				}
			}
			$this->db->free_result($res);
		}
		
		public function CheckMediaExist($FileName)
		{
			if($this->MediaMainFileName == "")
			{
				$this->MediaMainFileName = $FileName;	
			}
			
			if(in_array($FileName, $this->TotalMediaFiles) == TRUE)
			{
				$this->MediaCounter = $this->MediaCounter + 1;
				
				$file_array = explode(".", $this->MediaMainFileName);
				$file_ext = end($file_array);
				
				array_pop($file_array);
				
				$file_name = implode(".", $file_array);
				$file_name = $file_name."-".$this->MediaCounter;	
							
				$new_file_name = $file_name.".".$file_ext;
				$this->CheckMediaExist($new_file_name);			
			}
			else
			{
				$this->MediaCounter = 0;
				$new_file_name = $FileName;
				array_push($this->TotalMediaFiles, $new_file_name);
			}		
			
			//return $new_file_name;
			$this->MediaMainFileName = "";
			return end($this->TotalMediaFiles);
		}
		
		
		public function EmailTemplate($Email_content, $logo = '') 
		{
			
			$mail_content = '
						<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>
									<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="border-color:#36142a;border-width:1px;border-style:solid">
										<tr>
											<td style="padding: 20px;">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td align="center"><img src="'.MAIN_WEBSITE_URL.'/'.$logo.'" border="0" /></td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td><hr width="100%" size="1" color="#999999" /></td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td align="left" style="font-family: Verdana, Geneva, sans-serif; font-size: 14px;">'.$Email_content.'</td>
													</tr>
												</table>				
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					';
					
			return $mail_content; 
		}
		
		public function getStaticPageContent($PageId)
		{
			$sql = "SELECT `page_content` FROM `tbl_static_page_content` WHERE `page_id`='".$PageId."'";
			$res = $this->db->get($sql,__FILE__,__LINE__);
			$content = $this->getValue($this->db->result($res,0,0));
			return $content;
		}
		
		public function getSettings($fields = "*") 
		{
			$sql = "SELECT ".$fields." FROM `tbl_admin` WHERE `admin_id`=1";
			$res = $this->db->get($sql,__FILE__,__LINE__);
			return $this->db->fetch_array($res);
		}
		
		public function RandOnlyNum($len = 20)
		{
			srand((double)microtime()*1000000); 
			$data = "123456789"; 
			for($i = 0; $i < $len; $i++) 
			{
				$random.= substr($data,(rand()%(strlen($data))),1); 
			}
			return $random;
		}
		
		public function NewsLtrEmailExist($email)
		{
			$sql = "SELECT COUNT(*) FROM `tbl_newsletter_contacts` WHERE `email_address`='".$email."'";
			$res = $this->db->get($sql,__FILE__,__LINE__);
			$record = $this->db->result($res, 0, 0);
			
			return ($record > 0) ? TRUE  : FALSE;
		}
		
		public function getLanguage($language_id='1') 
		{
			$sql = "SELECT * FROM `tbl_language` WHERE `language_id`=".$language_id;
			$res = $this->db->get($sql,__FILE__,__LINE__);
			return $this->db->fetch_array($res);
		}	
		
		
		public function LikeDislike($video_id=0, $user_id=0, $likes_rec=0, $dislikes_rec=0)
		{
			$like_image = "like.png";
			$dislike_image = "dislike.png";
			
			$sql = "SELECT * FROM `tbl_video_likes` WHERE `video_id`=".$video_id." AND `user_id`=".$user_id;
			$res = $this->db->get($sql,__FILE__,__LINE__);
			$rec = $this->db->num_rows($res);
			if($rec > 0)
			{
				$row = $this->db->fetch_array($res);
				$like_group = $row['like_group'];
				if($like_group == '1')
				{
					$like_image = "like_con.png";
					$dislike_image = "dislike.png";
				}else{
					$like_image = "like.png";
					$dislike_image = "dislike_con.png";
				}
			}
			
			$return_html = '<span class="the_like"><a href="javascript:;"><img src="'.MAIN_WEBSITE_URL.'/images/question/'.$like_image.'" class="ajax_like" alt="Like"></a>  '.$likes_rec.'</span> 
						 <span class="the_dislike"><a href="javascript:;"><img src="'.MAIN_WEBSITE_URL.'/images/question/'.$dislike_image.'" class="ajax_like" alt="Dislike"></a> '.$dislikes_rec.'</span>';
			
			$return_html.= '<script type="text/javascript">
							$(document).ready(function() {
								$(".ajax_like").click(function() {		
									var img_alt_value = $(this).attr("alt");';
						if(empty($_SESSION['user_id']) == false)
						{
			$return_html.= ' $.ajax({ 
								type: "GET",
								url: "'.MAIN_WEBSITE_URL.'/ajax.php",
								data: {target: "LikeDislikeAjax", img_alt_value: img_alt_value, video_id:'.$video_id.'},
								dataType: "html",
								success: function(response) {
									var myArray = response.split("|");									
									$("the_like").innerHTML=myArray["0"];
									$("the_dislike").innerHTML=myArray["1"];
								},
								error: function (error) {					
									alert("Error: " + eval(error));
								}	
							});
							';
						}else{
							$return_html.= 'errorAlert("Please log in to continue.");';
						}
									
				$return_html.= '	});
							});
						</script>
						';
						
			return $return_html;
			
		}
	}
?>