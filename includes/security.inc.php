<?php
	class Security
	{
		protected $_never_allowed_str = array(
			'document.cookie'	=> '[removed]',
			'document.write'	=> '[removed]',
			'.parentNode'		=> '[removed]',
			'.innerHTML'		=> '[removed]',
			'-moz-binding'		=> '[removed]',
			'<!--'				=> '&lt;!--',
			'-->'				=> '--&gt;',
			'<![CDATA['			=> '&lt;![CDATA[',
			'<comment>'			=> '&lt;comment&gt;'
		);
		
		protected $_never_allowed_regex = array(
			'javascript\s*:',
			'(document|(document\.)?window)\.(location|on\w*)',
			'expression\s*(\(|&\#40;)', // CSS and IE
			'vbscript\s*:', // IE, surprise!
			'wscript\s*:', // IE
			'jscript\s*:', // IE
			'vbs\s*:', // IE
			'Redirect\s+30\d:',
			"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
		);
		
		public function RemoveInvisibleCharacters($str, $url_encoded = TRUE)
		{
			$non_displayables = array();
			
			// every control character except newline (dec 10)
			// carriage return (dec 13), and horizontal tab (dec 09)
			
			if ($url_encoded)
			{
				$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
				$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
			}
			
			$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127
	
			do
			{
				$str = preg_replace($non_displayables, '', $str, -1, $count);
			}
			while ($count);
	
			return $str;
		}
		
		public function XssClean($str, $is_image = FALSE)
		{
			// Is the string an array?
			if(is_array($str) == TRUE)
			{
				while(list($key) = current($str))
				{
					$str[$key] = $this->XssClean($str[$key]);
					next($str);
				}
				
				/*print_r($str);
				
				foreach($str as $key)
				{
					$str[$key] = $this->XssClean($key);
					//$str[$key] = $this->XssClean($str[$key]);
				}*/
	
				return $str;
			}
			
			//Remove Invisible Characters
			$str = $this->RemoveInvisibleCharacters($str);
			
			do
			{
				$str = rawurldecode($str);
			}
			while (preg_match('/%[0-9a-f]{2,}/i', $str));
			
			$str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
			$str = preg_replace_callback('/<\w+.*/si', array($this, '_decode_entity'), $str);
	
			// Remove Invisible Characters Again!
			$str = $this->RemoveInvisibleCharacters($str);
			
			$str = str_replace("\t", ' ', $str);

			// Capture converted string for later comparison
			$converted_string = $str;
	
			// Remove Strings that are never allowed
			$str = $this->_do_never_allowed($str);
			
			if ($is_image === TRUE)
			{
				// Images have a tendency to have the PHP short opening and
				// closing tags every so often so we skip those and only
				// do the long opening tags.
				$str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
			}
			else
			{
				$str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
			}
			
			$words = array(
				'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
				'vbs', 'script', 'base64', 'applet', 'alert', 'document',
				'write', 'cookie', 'window', 'confirm', 'prompt'
			);
	
			foreach ($words as $word)
			{
				$word = implode('\s*', str_split($word)).'\s*';
	
				// We only want to do this when it is followed by a non-word character
				// That way valid stuff like "dealer to" does not become "dealerto"
				$str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
			}
			
			do
			{
				$original = $str;
	
				if (preg_match('/<a/i', $str))
				{
					$str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
				}
	
				if (preg_match('/<img/i', $str))
				{
					$str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
				}
	
				if (preg_match('/script|xss/i', $str))
				{
					$str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
				}
			}
			while($original !== $str);
	
			unset($original);
	
			// Remove evil attributes such as style, onclick and xmlns
			$str = $this->_remove_evil_attributes($str, $is_image);
			
			$naughty = 'alert|prompt|confirm|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|button|select|isindex|layer|link|meta|keygen|object|plaintext|style|script|textarea|title|math|video|svg|xml|xss';
			$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);
			
			$str = preg_replace(
				'#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
				'\\1\\2&#40;\\3&#41;',
				$str
			);
	
			// Final clean up
			// This adds a bit of extra precaution in case
			// something got through the above filters
			$str = $this->_do_never_allowed($str);
			
			if ($is_image === TRUE)
			{
				return ($str === $converted_string);
			}
	
			return $str;		
		}
		
		private function is_php($version = '5.0.0')
		{
			static $_is_php;
			$version = (string)$version;
	
			if ( ! isset($_is_php[$version]))
			{
				$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
			}
	
			return $_is_php[$version];
		}
		
		public function xss_hash()
		{
			mt_srand();
			$xss_hash = md5(time() + mt_rand(0, 1999999999));	
			return $xss_hash;
		}
		
		public function entity_decode($str, $charset='UTF-8')
		{
			if (strpos($str, '&') === FALSE)
			{
				return $str;
			}
	
			static $_entities;
	
			//isset($charset) OR $charset = strtoupper(config_item('charset'));
			$flag = $this->is_php('5.4')
				? ENT_COMPAT | ENT_HTML5
				: ENT_COMPAT;
	
			do
			{
				$str_compare = $str;
	
				// Decode standard entities, avoiding false positives
				if ($c = preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches))
				{
					if ( ! isset($_entities))
					{
						$_entities = array_map(
							'strtolower',
							$this->is_php('5.3.4')
								? get_html_translation_table(HTML_ENTITIES, $flag, $charset)
								: get_html_translation_table(HTML_ENTITIES, $flag)
						);
	
						// If we're not on PHP 5.4+, add the possibly dangerous HTML 5
						// entities to the array manually
						if ($flag === ENT_COMPAT)
						{
							$_entities[':'] = '&colon;';
							$_entities['('] = '&lpar;';
							$_entities[')'] = '&rpar';
							$_entities["\n"] = '&newline;';
							$_entities["\t"] = '&tab;';
						}
					}
	
					$replace = array();
					$matches = array_unique(array_map('strtolower', $matches[0]));
					for ($i = 0; $i < $c; $i++)
					{
						if (($char = array_search($matches[$i].';', $_entities, TRUE)) !== FALSE)
						{
							$replace[$matches[$i]] = $char;
						}
					}
	
					$str = str_ireplace(array_keys($replace), array_values($replace), $str);
				}
	
				// Decode numeric & UTF16 two byte entities
				$str = html_entity_decode(
					preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
					$flag,
					$charset
				);
			}
			while ($str_compare !== $str);
			return $str;
		}
		
		protected function _compact_exploded_words($matches)
		{
			return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
		}
		
		protected function _remove_evil_attributes($str, $is_image)
		{
			// All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
			$evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction', 'form', 'xlink:href');
	
			if ($is_image === TRUE)
			{
				/*
				 * Adobe Photoshop puts XML metadata into JFIF images,
				 * including namespacing, so we have to allow this for images.
				 */
				unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
			}
	
			do {
				$count = 0;
				$attribs = array();
	
				// find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
				preg_match_all('/(?<!\w)('.implode('|', $evil_attributes).')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', $str, $matches, PREG_SET_ORDER);
	
				foreach ($matches as $attr)
				{
					$attribs[] = preg_quote($attr[0], '/');
				}
	
				// find occurrences of illegal attribute strings without quotes
				preg_match_all('/(?<!\w)('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);
	
				foreach ($matches as $attr)
				{
					$attribs[] = preg_quote($attr[0], '/');
				}
	
				// replace illegal attribute strings that are inside an html tag
				if (count($attribs) > 0)
				{
					$str = preg_replace('/(<?)(\/?[^><]+?)([^A-Za-z<>\-])(.*?)('.implode('|', $attribs).')(.*?)([\s><]?)([><]*)/i', '$1$2 $4$6$7$8', $str, -1, $count);
				}
	
			}
			while ($count);	
			return $str;
		}
		
		protected function _sanitize_naughty_html($matches)
		{
			return '&lt;'.$matches[1].$matches[2].$matches[3] // encode opening brace
				// encode captured opening or closing brace to prevent recursive vectors:
				.str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
		}
		
		protected function _js_link_removal($match)
		{
			return str_replace(
				$match[1],
				preg_replace(
					'#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
					'',
					$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
				),
				$match[0]
			);
		}
		
		protected function _js_img_removal($match)
		{
			return str_replace(
				$match[1],
				preg_replace(
					'#src=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
					'',
					$this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]))
				),
				$match[0]
			);
		}
		
		protected function _convert_attribute($match)
		{
			return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
		}
		
		protected function _filter_attributes($str)
		{
			$out = '';
	
			if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
			{
				foreach ($matches[0] as $match)
				{
					$out .= preg_replace("#/\*.*?\*/#s", '', $match);
				}
			}
	
			return $out;
		}
		
		protected function _decode_entity($match)
		{
			// Protect GET variables in URLs
			// 901119URL5918AMP18930PROTECT8198
			$match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $this->xss_hash().'\\1=\\2', $match[0]);
	
			// Decode, then un-protect URL GET vars
			return str_replace(
				$this->xss_hash(),
				'&',
				$this->entity_decode($match, strtoupper('UTF-8'))
			);
		}
		
		protected function _do_never_allowed($str)
		{
			$str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);
	
			foreach ($this->_never_allowed_regex as $regex)
			{
				$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
			}
	
			return $str;
		}
	}
?>