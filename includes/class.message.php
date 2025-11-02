<?php
	class Message {
		private $FontName;
		private $FontSize;
		private $Content;
		private $Body = array();
		
		public function setFontName($font="Verdana") 
		{
			$this->FontName = $font;
		}
		
		public function setFontSize($size="12px") 
		{
			$this->FontSize = $size;
		}
		
		public function setBodyElement($eName,$eValue) 
		{
			$this->Body[$eName] = $eValue;
		}
		
		public function getContent() 
		{
			$this->Content = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family: ".$this->FontName."; font-size: ".$this->FontSize.";\">\n";
			foreach($this->Body as $key => $val) 
			{
				$this->Content.= "\t<tr>\n";
				$this->Content.= "\t\t<td height=\"24\" valign=\"top\"><b>".$key.":</b></td>\n";
				$this->Content.= "\t\t<td height=\"24\" valign=\"top\">&nbsp;</td>\n";
				$this->Content.= "\t\t<td height=\"24\" valign=\"top\">".$val."</td>\n";
				$this->Content.= "\t</tr>\n";
			}
			$this->Content.= "</table>";
			return $this->Content;
		}
	}
?>