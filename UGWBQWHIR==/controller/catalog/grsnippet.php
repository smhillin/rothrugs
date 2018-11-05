<?php  
class ControllerCatalogGrsnippet extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('catalog/grsnippet');       
    	$this->document->setTitle($this->language->get('heading_title'));
    	$this->getForm();
  	}
  	
    public function insert() {    
		$this->load->language('catalog/grsnippet');
		$this->load->model('setting/setting');
		$this->document->setTitle($this->language->get('heading_title'));
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_setting_setting->editSetting('grsnippet', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->getForm();
	}
	
	private function getForm() {
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		$this->document->addLink("view/stylesheet/allseo.css","stylesheet");
		$data['heading_title'] = $this->language->get('heading_title');
		$this->load->language('catalog/grsnippet');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled'); 
		$data['text_about'] = $this->language->get('text_about'); 
		$data['business_type'] = $this->language->get('business_type');
		$data['payment_method'] = $this->language->get('payment_method');
		$data['tab_achieve'] = $this->language->get('tab_achieve');
		$data['tab_verify'] = $this->language->get('tab_verify');
		$data['tab_company'] = $this->language->get('tab_company');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel'); 
		$data['entry_google_status'] = $this->language->get('entry_google_status'); 
		$data['entry_facebook_status'] = $this->language->get('entry_facebook_status'); 
		$data['entry_twitter_status'] = $this->language->get('entry_twitter_status'); 
		$data['entry_pinterest_status'] = $this->language->get('entry_pinterest_status'); 
		$data['text_status'] = $this->language->get('text_status');
		$data['grsnippet'] = $this->language->get('grsnippet');
		$data['store_help'] = $this->language->get('store_help');
		$data['verify_help'] = $this->language->get('verify_help');
		$data['achieve_help'] = $this->language->get('achieve_help');
		$data['text_twitterusername'] = $this->language->get('text_twitterusername');
		$data['text_googlepageid'] = $this->language->get('text_googlepageid');
		$data['text_facebookadminid'] = $this->language->get('text_facebookadminid');
		$data['text_googlepageid_help'] = $this->language->get('text_googlepageid_help');
		$data['text_facebookadminid_help'] = $this->language->get('text_facebookadminid_help');
		$data['text_twitterusername_help'] = $this->language->get('text_twitterusername_help');
		$data['text_form'] = $this->language->get('text_form');
		$data['check_impact'] = $this->language->get('check_impact');
		$data['company_name'] = $this->language->get('company_name');
		$data['country_name'] = $this->language->get('country_name');
		$data['postal_code'] = $this->language->get('postal_code'); 
		$data['locality_name'] = $this->language->get('locality_name'); 
		$data['street_address'] = $this->language->get('street_address');
		$data['telephone_number'] = $this->language->get('telephone_number');

		$data['token'] = $this->session->data['token'];
		$data['types'] = array('Sell','LeaseOut','Repair','Maintain','ConstructionInstallation','ProvideService','Dispose');
		$data['payments'] = array('ByBankTransferInAdvance','ByInvoice','Cash','CheckInAdvance','COD','DirectDebit','GoogleCheckOut','PayPal','PaySwarm','AmericanExpress','DinersClub','Discover','JCB','MasterCard','VISA');
		$data['deliveries'] = array('DeliveryModeDirectDownload','DeliveryModeFreight','DeliveryModeMail','DeliveryModeOwnFleet','DeliveryModePickUp','DHL','FederalExpress','UPS');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

      	$data['action'] = $this->url->link('catalog/grsnippet/insert', 'token=' . $this->session->data['token'], 'SSL');
		
	 	if (isset($this->request->post['grsnippet_config_payment_methods'])) {
			$data['payment_methods'] = $this->request->post['grsnippet_config_payment_methods'];
		} elseif ($this->config->get('grsnippet_config_payment_methods')) {
			$data['payment_methods'] = $this->config->get('grsnippet_config_payment_methods');
		} else {
			$data['payment_methods'] = array();
		}
		
	 	if (isset($this->request->post['grsnippet_config_delivery_methods'])) {
			$data['delivery_methods'] = $this->request->post['grsnippet_config_delivery_methods'];
		} elseif ($this->config->get('grsnippet_config_delivery_methods')) {
			$data['delivery_methods'] = $this->config->get('grsnippet_config_delivery_methods');
		} else {
			$data['delivery_methods'] = array();
		}

	    if (isset($this->request->post['grsnippet_config_gr_status'])) {
			$data['grsnippet_config_gr_status'] = $this->request->post['grsnippet_config_gr_status'];
		} elseif ($this->config->get('grsnippet_config_gr_status')) {
			$data['grsnippet_config_gr_status'] = $this->config->get('grsnippet_config_gr_status');
		} else {
			$data['grsnippet_config_gr_status'] = '';
		}

		if (isset($this->request->post['grsnippet_config_twitter_status'])) {
			$data['grsnippet_config_twitter_status'] = $this->request->post['grsnippet_config_twitter_status'];
		} elseif ($this->config->get('grsnippet_config_twitter_status')) {
			$data['grsnippet_config_twitter_status'] = $this->config->get('grsnippet_config_twitter_status');
		} else {
			$data['grsnippet_config_twitter_status'] = '';
		}

		if (isset($this->request->post['grsnippet_config_pinterest_status'])) {
			$data['grsnippet_config_pinterest_status'] = $this->request->post['grsnippet_config_pinterest_status'];
		} elseif ($this->config->get('grsnippet_config_pinterest_status')) {
			$data['grsnippet_config_pinterest_status'] = $this->config->get('grsnippet_config_pinterest_status');
		} else {
			$data['grsnippet_config_pinterest_status'] = '';
		}

		if (isset($this->request->post['grsnippet_config_facebook_status'])) {
			$data['grsnippet_config_facebook_status'] = $this->request->post['grsnippet_config_facebook_status'];
		} elseif ($this->config->get('grsnippet_config_facebook_status')) {
			$data['grsnippet_config_facebook_status'] = $this->config->get('grsnippet_config_facebook_status');
		} else {
			$data['grsnippet_config_facebook_status'] = '';
		}


		if (isset($this->request->post['grsnippet_config_google_pageid'])) {
			$data['grsnippet_config_google_pageid'] = $this->request->post['grsnippet_config_google_pageid'];
		} elseif ($this->config->get('grsnippet_config_google_pageid')) {
			$data['grsnippet_config_google_pageid'] = $this->config->get('grsnippet_config_google_pageid');
		} else {
			$data['grsnippet_config_google_pageid'] = '';
		}

		if (isset($this->request->post['grsnippet_config_facebook_adminid'])) {
			$data['grsnippet_config_facebook_adminid'] = $this->request->post['grsnippet_config_facebook_adminid'];
		} elseif ($this->config->get('grsnippet_config_facebook_adminid')) {
			$data['grsnippet_config_facebook_adminid'] = $this->config->get('grsnippet_config_facebook_adminid');
		} else {
			$data['grsnippet_config_facebook_adminid'] = '';
		}

		if (isset($this->request->post['grsnippet_config_twitter_uername'])) {
			$data['grsnippet_config_twitter_uername'] = $this->request->post['grsnippet_config_twitter_uername'];
		} elseif ($this->config->get('grsnippet_config_twitter_uername')) {
			$data['grsnippet_config_twitter_uername'] = $this->config->get('grsnippet_config_twitter_uername');
		} else {
			$data['grsnippet_config_twitter_uername'] = '';
		}
		 
		$data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'); 
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['links'][] = array(
        		'text'      => "Auto Generate Tool",
            	'href'      => $this->url->link('catalog/seo/autogenerate', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-cogs"></i>'
        );

         $data['links'][] = array(
        		'text'      => "Seo Advanced Editor",
            	'href'      => $this->url->link('catalog/seo/customize', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-pencil"></i>'
        );

        $data['links'][] = array(
        		'text'      => "Dynamic Seo Report",
            	'href'      => $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-file-text"></i>'
        );

        $data['links'][] = array(
        		'text'      => "Complete Rich Snippet",
            	'href'      => $this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-heart"></i>'
        );

        $data['links'][] = array(
        		'text'      => "Sitemap Generator Pro",
            	'href'      => $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-sitemap"></i>'
        );

        $data['links'][] = array(
        		'text'      => "Clear Seo Tool",
            	'href'      => $this->url->link('catalog/clearseo', 'token=' . $this->session->data['token'], 'SSL'),
            	'image'     => '<i class="fa fa-times"></i>'
        );

        $data['links'][] = array(
                    'text'      => "Seo Redirect Manager",
                    'href'      => $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => '<i class="fa fa-random"></i>'
        );

        $data['links'][] = array(
                    'text'      => "General Setting",
                    'href'      => $this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => '<i class="fa fa-gear"></i>'
        );
 
		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/grsnippet.tpl', $data));
	}
}
?>