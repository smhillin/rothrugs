<?php    
class ControllerPomgmtFeedExport extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->model('pomgmt/feedexport');
		$order_info = $this->model_pomgmt_feedexport->getOrders();
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=file.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$this->outputCSV($order_info);
		
		
  	}	
	
	function outputCSV($data) {
    $outstream = fopen("php://output", "w");
	fputcsv($outstream,array('Order','Date','Customer First Name','Customer Last Name','Customer Email','Customer Telephone','Customer Address 1','Customer Address 2','Customer City','Customer Post Code','Customer Country','Customer Region','Product name','Model','Quantity','Price','Total'));

    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals); // add parameters if you want
    }
    array_walk($data, "__outputCSV", $outstream);
    fclose($outstream);
}
}
?>