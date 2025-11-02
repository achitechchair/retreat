<?php
	class PayPalDoDirect {
		public $api_username = "";
		public $api_password = "";
		public $api_signature = "";
		
		public $environment = "sandbox"; // live or sandbox
		public $recurring_billing = FALSE;
		
		// For Recurring Billing Information
		public $RecurringStartDate = "2009-9-6T0:0:0";
		public $RecurringBillingPeriod = "Month"; // or "Day", "Week", "SemiMonth", "Year"
		public $RecurringBillingFreq = "4"; // combination of this and billingPeriod must be at most a year 
		
		public $PaymentType = 'Sale'; // 'Authorization' or 'Sale'
		public $currency = 'USD';	// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		public $success_page_url = "";
		
		private $HttpResponse;
		private $HttpRequest;
		
		public function getCustomerData($data) {
			$this->HttpRequest = http_build_query($data);
		}
		
		public function HttpPost() {
			$api_enpoint = "https://api-3t.paypal.com/nvp";
			if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
				$api_enpoint = "https://api-3t.".$this->environment.".paypal.com/nvp";
			}
			
			$version = urlencode('51.0');
			
			// Set the curl parameters.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api_enpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			
			$method = ($this->recurring_billing == TRUE) ? "CreateRecurringPaymentsProfile" : "DoDirectPayment";
			
			$request = array(
				"METHOD" => $method,
				"VERSION" => $version,
				"PWD" => $this->api_password,
				"USER" => $this->api_username,
				"SIGNATURE" => $this->api_signature,
				"CURRENCYCODE" => $this->currency,
			);
			
			if($method == "CreateRecurringPaymentsProfile") {
				$request['PROFILESTARTDATE'] = $this->RecurringStartDate;
				$request['BILLINGPERIOD'] = $this->RecurringBillingPeriod;
				$request['BILLINGFREQUENCY'] = $this->RecurringBillingFreq;
			} else {
				$request['PAYMENTACTION'] = $this->PaymentType;	
			}
			
			$this->HttpRequest.= "&".http_build_query($request);
			
			// Set the request as a POST FIELD for curl.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->HttpRequest);
			
			// Get response from the server.
			$this->HttpResponse = curl_exec($ch);
			
			if(!$this->HttpResponse) {
				exit($method." failed: ".curl_error($ch)."(".curl_errno($ch).")");
			}
		
			// Extract the response details.
			$HttpResponseAr = explode("&", $this->HttpResponse);
		
			$HttpParsedResponseAr = array();
			foreach ($HttpResponseAr as $i => $value) {
				$TmpAr = explode("=", $value);
				if(sizeof($TmpAr) > 1) {
					$HttpParsedResponseAr[$TmpAr[0]] = $TmpAr[1];
				}
			}
		
			if((0 == sizeof($HttpParsedResponseAr)) || !array_key_exists("ACK", $HttpParsedResponseAr)) {
				exit("Invalid HTTP Response for POST request(".$this->HttpRequest.") to ".$api_enpoint.".");
			}
			
			if("SUCCESS" == strtoupper($HttpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($HttpParsedResponseAr["ACK"])) {
				header("Location: ".$this->success_page_url);
				exit();
			} else {
				$ErrorMessage =  "<b>Transaction Status:</b> ".$HttpParsedResponseAr['ACK']."<br />";
				$Error = str_replace('%20', " ", $HttpParsedResponseAr['L_LONGMESSAGE0']);
				$ErrorMessage.= "<b>Error Message:</b> ".str_replace('%2e', "<br />", $Error);
				$ErrorMessage.= "<b>Error No.:</b> ".$HttpParsedResponseAr['L_ERRORCODE0'];
				
				return $ErrorMessage;
			}
		
			//return $httpParsedResponseAr;
		}
		
		public function HttpError($txtError) {
			echo $txtError;
		}
	}
	
	/*$customer_data = array(
		"FIRSTNAME" => $_POST['bill_first_name'],
		"LASTNAME" => $_POST['bill_first_name'],
		"STREET" => $_POST['bill_address1'],
		//$bill_address2 = $_POST['bill_first_name'],
		"CITY" => $_POST['bill_city'],
		"STATE" => $_POST['bill_state'],
		"ZIP" => $_POST['bill_zip'],
		"COUNTRYCODE" => $_POST['bill_country'],
		"CREDITCARDTYPE" => $_POST['bill_cc_type'],
		"ACCT" => $_POST['bill_cc_number'],
		"EXPDATE" => str_pad($_POST['bill_cc_exp_month'], 2, '0', STR_PAD_LEFT).$_POST['bill_cc_exp_year'],
		"CVV2" => $_POST['bill_cc_cvv'],
		"IPADDRESS" => $_SERVER['REMOTE_ADDR'],
		"AMT" => "10"
	);*/
	
	$customer_data = array(
		"FIRSTNAME" => "Subhasish",
		"LASTNAME" => "Nag",
		"STREET" => "Barrackpore",
		//$bill_address2 = $_POST['bill_first_name'],
		"CITY" => "Kolkata",
		"STATE" => "WB",
		"ZIP" => "700123",
		"COUNTRYCODE" => "IN",
		"CREDITCARDTYPE" => "VISA",
		"ACCT" => "4088715620137984",
		"EXPDATE" => "072013",
		"CVV2" => "786",
		"IPADDRESS" => $_SERVER['REMOTE_ADDR'],
		"AMT" => "10"
	);
	
	$PayPalDoDirect = new PayPalDoDirect;
	$PayPalDoDirect->environment = "live";
	$PayPalDoDirect->api_username = 'dancelikemagic_api1.gmail.com';
	$PayPalDoDirect->api_password = 'QPHWFPHFXBEAS253';
	$PayPalDoDirect->api_signature = 'AMGhh.PvacwOrO6P-.iFUcgxcOYlAB9-RYbQ9BN9KDhixphH8HDYGa.Q';
	$PayPalDoDirect->getCustomerData($customer_data);
	
	// If recurring billing is true
	$PayPalDoDirect->recurring_billing = TRUE;
	$PayPalDoDirect->RecurringStartDate = '2009-9-6T0:0:0';
	$PayPalDoDirect->RecurringBillingPeriod = 'Month';
	$PayPalDoDirect->RecurringBillingFreq = '4';
	
	echo $PayPalDoDirect->HttpPost();
?>