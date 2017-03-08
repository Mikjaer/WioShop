<?php

	class SimpleApiClient
	{
		public $endpoint = NULL;
		public $request_type = "GET";		
		public $result = NULL;
		public $payload = NULL;
		public $headers = array("Content-Type: application/json");

		public function endpoint($endpoint)
		{
			$this->endpoint = $endpoint;
			return $this;
		}

		public function addHeader($header)
		{
			$this->headers[] = $header;
		}

		public function requestTypeGet() { 	$this->request_type = "GET"; 	return $this; }
		public function requestTypePost() { 	$this->request_type = "POST"; 	return $this; }
		public function requestTypePut() { 	$this->request_type = "PUT"; 	return $this; }
		public function requestTypeDelete() { 	$this->request_type = "DELETE"; return $this; }


		public function perform()
		{
##			$data = array("name" => "Hagrid", "age" => "36");
##			$data_string = json_encode($data);
			$ch = curl_init($this->endpoint);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->request_type);

			if ($this->payload != NULL)
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->payload);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			#curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
			
			$this->result = curl_exec($ch);
			
			if ($ret = json_decode($this->result,true))
				return $ret;
			else
				die("ERROR: $this->result");;
			return json_decode($this->result,true);
		}
	}



#	$request = new SimpleApiClient();
#	$request->endpoint("https://mike42.wrtcloud.com/api/v1.0/");

#	$request->requestTypeDelete();

#	$reply = $request->perform();

#	print_r($reply);
?>
