<?php 
class ControllerPOMgmtRestcall extends Controller { 
	public function index() {
		//file_put_contents("c:\\test.txt",$_POST['jTemplateID']);
		$return = $this->CallAPI("POST",$this->config->get('config_adworks_restfulurl')."/RegisterService/register", array("UID"=>$this->config->get('config_access_username'),"password"=>$this->config->get('config_access_password'),"clientSessionID"=>"LETMEINNOW123"));
		if ($return=="success") {
			$return = $this->CallAPI("POST",$this->config->get('config_adworks_restfulurl')."/FileService/downloadPDFArtwork",
			array("UID"=>$this->config->get('config_access_username'),"clientSessionID"=>"LETMEINNOW123","jTemplateID"=>$_GET['jTemplateID'],
			"includeCropMarks"=>"true","printBackground"=>"true","lowRes"=>"false"));
			$filename = DIR_APPLICATION."//downloads//".$_GET['jTemplateID'].".pdf";
			file_put_contents($filename,$return);
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename='.$_GET['jTemplateID'].'.pdf');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			flush();
			readfile($filename);
			exit;
		}
		//echo ("<script>window.close();</script>");
	}
	
	// Method: POST, PUT, GET etc
	// Data: array("param" => "value") ==> index.php?param=value

	public function CallAPI($method, $url, $data = false)
	{
		$curl = curl_init();
		$data_string = json_encode($data);  
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// Optional Authentication:
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string)));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		return curl_exec($curl);
	}
}
?>