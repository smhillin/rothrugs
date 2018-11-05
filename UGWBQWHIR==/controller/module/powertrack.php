<?php
class ControllerModulePowertrack extends Controller {
    
	private $error = array();

    /**
     * 
     */
	public function install() {
		$this->load->model('extension/module/powertrack');
		$this->model_extension_module_powertrack->install();
	}

    /**
     * 
     */
	public function index() {
		
        $module_route = 'module/powertrack';
	    if(version_compare(VERSION, "2.2.0.0", "le")){
	        $modules_route = 'extension/module';
	    }else{
	        $modules_route = 'extension/extension';
	    }
	     
		
		$this->load->language('module/powertrack');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		//Load the settings model. You can also add any other models you want to load here.
		$this->load->model('setting/setting');
		
		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		    
			$powertrack_company_names   = $this->request->post['powertrack_company_name'];
			$powertrack_company_urls    = $this->request->post['powertrack_company_url'];
			$powertrack_company_ids     = $this->request->post['powertrack_company_id'];
			$powertrack_aftership_slugs = $this->request->post['powertrack_aftership_slugs'];

		    $companies = array();
		    foreach($powertrack_company_names as $key => $company_name){
		        if(! empty($company_name)){

		            $company_id = $powertrack_company_ids[$key];
		            if(empty($company_id)){
    		            $this->load->helper('powertrack');
    		            $company_id = generateRandomString();
		            }
		            
		            $company_url = htmlspecialchars_decode($powertrack_company_urls[$key]);    //the '&' is received as "&amp;" so let's decode it.

		            $aftership_slug = $powertrack_aftership_slugs[$key];

		            $companies[] = array('company_name' => $company_name, 'company_url' => $company_url, 'company_id' => $company_id, 'aftership_slug' => $aftership_slug);
		        }
		    }
		    
		    $this->request->post['powertrack_companies'] = $companies;
		    
			$this->model_setting_setting->editSetting('powertrack', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link($modules_route, 'token=' . $this->session->data['token'], 'SSL'));
		}

		$text_strings = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'text_home',
			'text_yes',
			'text_no',
			'button_save',
			'button_cancel'
		);
		
		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
   		        'href'      => $this->url->link($modules_route, 'token=' . $this->session->data['token'], 'SSL'),
   		        'text'      => $this->language->get('text_module'),
	      		'separator' => ' :: '
   		);
   		
   		$data['breadcrumbs'][] = array(
   		        'href'      => $this->url->link($module_route, 'token=' . $this->session->data['token'], 'SSL'),
   		        'text'      => $this->language->get('heading_title'),
	      		'separator' => ' :: '
   		);
   		
   		 
   		$data['action'] = $this->url->link($module_route, 'token=' . $this->session->data['token'], 'SSL');
   		
   		$data['cancel'] = $this->url->link($modules_route, 'token=' . $this->session->data['token'], 'SSL');
   		
   		$data['url_to_add_deliverydate_column'] = $this->url->link($module_route . "/create_deliverydate_column", 'token=' . $this->session->data['token'], 'SSL');

   		   		
   		/*
   		 * 
   		 */
		$config_data = array(
			'powertrack_companies',
			'powertrack_cfg_show_tracking_column_in_order_list',
			'powertrack_cfg_default_company_id',
			'powertrack_cfg_log',
	        'powertrack_cfg_goo_gl_api_key',
	        'powertrack_cfg_aftership_api_key',
	        'powertrack_cfg_show_popup_for_these_statuses',
		);
		
		foreach ($config_data as $conf) {
		    if (isset($this->request->post[$conf])) {
		        $data[$conf] = $this->request->post[$conf];
		    } else {
		        $data[$conf] = $this->config->get($conf);
		    }
		    
		    if($conf === 'powertrack_companies'){
		        $data[$conf][] = array('company_name' => '', 'company_url' => '', 'company_id' => '', 'aftership_slug' => '' );
		    }
		    
		    if($conf == 'powertrack_cfg_show_tracking_column_in_order_list'){
		        if($this->config->get($conf) === null){
		            $data[$conf] = true;
		        }
		    }

		    if($conf == 'powertrack_cfg_show_popup_for_these_statuses'){
		        if($this->config->get($conf) === null){
		            $data[$conf] = array();
		        }
		    }
		}
		
		
		/*
		 * Inject order statuses
		 */
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		/*
		 * aftership in the 20/30 common model
		 */
        $this->load->model('extension/module/powertrack');
        $common_data = $this->model_extension_module_powertrack->get_common_data_for_the_settings_page_controller();
        $data = array_merge($data, $common_data);
        

		/*
		 * 
		 */
		$data['powertrack_tab_companies']    = $this->load->view('module/powertrack_tab_companies.tpl', $data);
		$data['powertrack_tab_popup']        = $this->load->view('module/powertrack_tab_popup.tpl', $data);
		$data['powertrack_tab_smshare']      = $this->load->view('module/powertrack_tab_smshare.tpl', $data);
		$data['powertrack_tab_aftership']    = $this->load->view('module/powertrack_tab_aftership.tpl', $data);
		$data['powertrack_tab_deliverydate'] = $this->load->view('module/powertrack_tab_deliverydate.tpl', $data);
		$data['powertrack_tab_other']        = $this->load->view('module/powertrack_tab_other.tpl', $data);

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('module/powertrack.tpl', $data));

	}
	
	
	
	/**
	 * 
	 */
	public function create_deliverydate_column(){
	    $this->load->model('extension/module/powertrack');
	    $column_exists = $this->model_extension_module_powertrack->create_deliverydate_column();
	    if($column_exists){
    	    echo "Already updated. Do not click again :)";
	    }else{
    	    echo "updated. Do not click again :)";
	    }
	}
	
}