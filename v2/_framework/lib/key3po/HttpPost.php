<?php

/* Utility class for POSTing form data to a URL */
/* right now supports http:// and https:// protocols only */
class HttpPost {
	
	private $url = null;
	private $host = null;
	private $path = null;
	private $port = null;
	private $transport = null; //SSL?
	private $scheme = null;
	private $fields = array();
	
	private $response = null;
	
	/* takes an associative array $fields and encodes it to be correct for use as POST data */
	public static function encodeForm($fields){
		$encoded = array();
		foreach($fields as $field=>$value){
			$encoded[] = $field.'='.urlencode($value);
			}
		return implode("&",$encoded);
		}
	
	public function __construct($url, $fields=null){
		//store URL and then dissect it
		$this->url = $url;
		$urlParts = parse_url($url);
		//save host, path
		$this->host = $urlParts['host'];
		$this->path = $urlParts['path'];
		//figure out if scheme is HTTPS or HTTP, and set the appropriate port and transport (ssl://) for the socket
		$scheme = $urlParts['scheme'];
		if(isset($urlParts['port'])){ //specified a port via http://hostname:port/ in URL
			$this->port = $urlParts['port'];
		}else if($scheme == "http"){ //no specific port; port 80 is implied by HTTP
			$this->port = 80;
		}else if($scheme == "https"){ //no specific port; port 443 is implied by HTTPS
			$this->port = 443;
			}
		if($scheme=="https"){ //notify fsockopen that SSL is in use
			$this->transport = "ssl";
			}
		//merge in fields
		if($fields){ $this->addFields($fields); }
		}
	
	//add fields to the POST request, overriding existing fields
	public function addFields($fields){
		$this->fields = array_merge($this->fields, $fields);
		}
	
	//submit and get response
	public function submit(){
		if($this->response!=null){ return null; }
		//include transport in host if specified
		$host = $this->host;
		if($this->transport != null){
			$host = $this->transport . "://" . $host;
			}
		//connect to Key3PO 
		$sock = @fsockopen($host, $this->port, $errno, $errstr, 30);
		if(!$sock){ return null; }
		//encode query as POST data
		$data = self::encodeForm($this->fields);
		//POST data to sign-in form
		fwrite($sock, "POST ".$this->path." HTTP/1.0\r\n");
		fwrite($sock, "Host: ".$this->host."\r\n");
		fwrite($sock, "Content-type: application/x-www-form-urlencoded\r\n");
		fwrite($sock, "Content-length: " . strlen($data) . "\r\n");
		fwrite($sock, "Accept: */*\r\n");
		fwrite($sock, "\r\n");
		fwrite($sock, "$data\r\n");
		fwrite($sock, "\r\n");
		//skim off and discard the headers
		$headers = "";
		while ($str = trim(fgets($sock, 4096)))
		$headers .= "$str\n";
		//read out the body that is returned
		$body = "";
		while (!feof($sock)){
			$body .= fgets($sock, 4096);
			}
		$this->response = $body;
		//close the socket
		fclose($sock);
		return $this->response;
		}
	
	//get response
	public function getResponse(){
		return $this->response;
		}
	
	}

?>