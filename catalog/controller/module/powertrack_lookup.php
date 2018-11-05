<?php
class ControllerExtensionModulePowertrackLookup extends Controller {

    private $error = array();

    public function index() {
	    $this->load->language('information/contact');
	    $this->load->language('module/powertrack');
	
	    $this->document->setTitle($this->language->get('powertrack_heading_title'));
	
        $data['powertrack_grid'] = array();
        
        $data['error'] = "";
        
	    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	        
	        /*
	         * Get tracking info and inject them.
	         */
	        $this->load->model('extension/module/powertrack_lookup');
	        $rows = $this->model_extension_module_powertrack_lookup->get_tracking_info_by_order_id($this->request->post['order_id']);
	        
	        if(empty($rows)){
	            //order_id not found
	            $this->error['no_match'] = "The email / OrderID don't match any record. Please check your orderID and enter the email address used to place the order.";
	            

            }else {
                
                $firstRow = reset($rows);    //some PHP versions require to do this variable assignation. 
                if(utf8_strtolower($firstRow['email']) !== utf8_strtolower($this->request->post['email'])){
                    //email doesn't match
                    $this->error['no_match'] = "The email / OrderID don't match any record. Please check your orderID and use the same email address you used when you placed the order.";
                 
    	        }else{

    	            /*
    	             * Get order info
    	             */
    	            $this->load->model('account/order');
    	            $order_info = $this->model_account_order->getOrder($this->request->post['order_id']);
    	            
        	        $this->load->helper('powertrack');
        	        foreach ($rows as $row){
        	            
        	            if(empty($row['powertrack_trackcode']) && empty($row['powertrack_carrier'])) continue;
        	            
        	            if($this->config->get('powertrack_cfg_log')) $this->log->write("[Powertrack] Order found.");
        	            
        	            $args = array(
    	                    'trackingCode'      => $row['powertrack_trackcode'], 
    	                    'carrierCode'       => $row['powertrack_carrier']   ,
    	                    'shipping_postcode' => $order_info['shipping_postcode'],
    	                    'payment_postcode'  => $order_info['payment_postcode'],
        	            );
        	            $comapny_url_and_name = get_tracking_url_and_carrier_name($args, $this->config);
        	            
        	            $data['powertrack_grid'][] = array(
                            "powertrack_tracking_code" => $row['powertrack_trackcode'], 
                            "powertrack_carrier_name"  => $comapny_url_and_name['carrier_name'], 
                            "powertrack_tracking_url"  => $comapny_url_and_name['tracking_url']
        	            );
        	        }
    	        }
	        }
	        
	        
	    }
	
	    $data['breadcrumbs'] = array();
	
	    $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
	    );
	
	    $data['breadcrumbs'][] = array(
            'text' => $this->language->get('powertrack_heading_title'),
            'href' => $this->url->link('information/contact')
	    );
	
	    $data['heading_title'] = $this->language->get('powertrack_heading_title');
	
	    $data['entry_email'] = $this->language->get('entry_email');
	

	    /*
	     * Errors
	     */
	    
	    if (isset($this->error['order_id'])) {
	        $data['error_order_id'] = $this->error['order_id'];
	    } else {
	        $data['error_order_id'] = '';
	    }
	
	    if (isset($this->error['email'])) {
	        $data['error_email'] = $this->error['email'];
	    } else {
	        $data['error_email'] = '';
	    }
	    if (isset($this->error['no_match'])) {
	        $data['error_no_match'] = $this->error['no_match'];
	    } else {
	        $data['error_no_match'] = '';
	    }
	
	    
	
	
	    $data['button_submit'] = $this->language->get('button_submit');
	
	    $data['action'] = $this->url->link('extension/module/powertrack_lookup');
	
	
	
	    //Fill again just to have the form filled while showing results (in case of a POST)
	    if (isset($this->request->post['order_id'])) {
	        $data['order_id'] = $this->request->post['order_id'];
	    } else {
	        $data['order_id'] = '';
	    }
	
	    if (isset($this->request->post['email'])) {
	        $data['email'] = $this->request->post['email'];
	    } else {
	        $data['email'] = $this->customer->getEmail();
	    }
	
	
	    $data['column_left'] = $this->load->controller('common/column_left');
	    $data['column_right'] = $this->load->controller('common/column_right');
	    $data['content_top'] = $this->load->controller('common/content_top');
	    $data['content_bottom'] = $this->load->controller('common/content_bottom');
	    $data['footer'] = $this->load->controller('common/footer');
	    $data['header'] = $this->load->controller('common/header');
	
	    if(version_compare(VERSION, '2.2.0.0', 'ge')) {
	        $this->response->setOutput($this->load->view('module/powertrack_lookup.tpl', $data));
	         
	    }else{
	        
    	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/powertrack_lookup.tpl')) {
    	        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/powertrack_lookup.tpl', $data));
    	    } else {
    	        $this->response->setOutput($this->load->view('default/template/module/powertrack_lookup.tpl', $data));
    	    }
    	    
	    }
	}

    function validate(){
        if(!isset($this->request->post['order_id'])){
            $this->error['order_id'] = "Order ID is required";
            return false;
        }
        
        return true;
    }
}