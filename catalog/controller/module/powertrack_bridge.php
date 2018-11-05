<?php
class ControllerExtensionModulePowertrackBridge extends Controller {
    
	private $error = array();

	/**
	 * Used for companies that require form submit.
	 */
	public function index() {
	    
	    $trackingUrl = $this->request->get['tracking_url'];
	    //$this->log->write('[powertrack] tracking url: ' . $trackingUrl);
	    
	    $data['tracking_url'] = $trackingUrl;
	    $data['tracking_num'] = $this->request->get['tracking_num'];
	    
	    //enctype=multipart
	    $data['enctype'] = "";
	    if(isset($this->request->get['enctype'])){
	        $data['enctype'] = "enctype='multipart/form-data'";
	    }
	    
	    if(version_compare(VERSION, '2.2.0.0', 'ge')) {
	        $this->response->setOutput($this->load->view('module/powertrack.tpl', $data));
	    }else{
    	    //$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/powertrack.tpl', $data));	        
    	    
    	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/powertrack_bridge.tpl')) {
    	        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/powertrack_bridge.tpl', $data));
    	    } else {
    	        $this->response->setOutput($this->load->view('default/template/module/powertrack_bridge.tpl', $data));
    	    }
	    }
	}
}