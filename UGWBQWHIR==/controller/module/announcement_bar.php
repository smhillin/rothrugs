<?php

class ControllerModuleAnnouncementBar extends Controller {
	
	private $error = array(); 
	
	public function index() {   
	
		//Load language file
		$this->load->language('module/announcement_bar');

		//Set title from language file
		$this->document->setTitle($this->language->get('heading_title'));
		
		//Load settings model
		$this->load->model('setting/setting');
		
		//Save settings
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('announcement_bar', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (isset($this->error['error_name'])) {
			$data['error_name'] = $this->error['error_name'];
		} else {
			$data['error_name'] = '';
		}
		
		$text_strings = array(
				'heading_title',
				'button_save',
				'button_cancel',
				'button_add_module',
				'button_remove',
				'placeholder',
				'entry_bar_text1',
				'entry_bar_text2',
				'entry_bar_text3',
				'entry_status',
				'text_edit',
				'text_enabled',
				'text_disabled'
		);
		
		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}
		
	
		//error handling
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/announcement_bar', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/announcement_bar', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['announcement_bar_bar_text_1'])) {
			$data['announcement_bar_bar_text_1'] = $this->request->post['announcement_bar_bar_text_1'];
		} else {
			$data['announcement_bar_bar_text_1'] = $this->config->get('announcement_bar_bar_text_1');
		}

		if (isset($this->request->post['announcement_bar_bar_text_2'])) {
			$data['announcement_bar_bar_text_2'] = $this->request->post['announcement_bar_bar_text_2'];
		} else {
			$data['announcement_bar_bar_text_2'] = $this->config->get('announcement_bar_bar_text_2');
		}


		if (isset($this->request->post['announcement_bar_bar_text_3'])) {
			$data['announcement_bar_bar_text_3'] = $this->request->post['announcement_bar_bar_text_3'];
		} else {
			$data['announcement_bar_bar_text_3'] = $this->config->get('announcement_bar_bar_text_3');
		}

		if (isset($this->request->post['announcement_bar_status'])) {
			$data['announcement_bar_status'] = $this->request->post['announcement_bar_status'];
		} else {
			$data['announcement_bar_status'] = $this->config->get('announcement_bar_status');
		}

		//Check if multiple instances of this module
		$data['modules'] = array();
		
		if (isset($this->request->post['announcement_bar_module'])) {
			$data['modules'] = $this->request->post['announcement_bar_module'];
		} elseif ($this->config->get('announcement_bar_module')) { 
			$data['modules'] = $this->config->get('announcement_bar_module');
		}		

		//Prepare for display
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//Send the output
		$this->response->setOutput($this->load->view('module/announcement_bar.tpl', $data));
	}
	
	/*
	 * 
	 * Check that user actions are authorized
	 * 
	 */
	private function validate() {
		/*if (!$this->user->hasPermission('modify', 'module/announcement_bar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}*/
		
		if($_POST['announcement_bar_bar_text_1'] == "<p><br></p>"){
			$this->request->post['announcement_bar_bar_text_1']="";
		}

		if($_POST['announcement_bar_bar_text_2']=="<p><br></p>"){
		    $this->request->post['announcement_bar_bar_text_2']="";
		}

		if($_POST['announcement_bar_bar_text_3']=="<p><br></p>"){
		    $this->request->post['announcement_bar_bar_text_3']="";
		}
		
		if (!$this->request->post['announcement_bar_bar_text_1'] && !$this->request->post['announcement_bar_bar_text_2'] && !$this->request->post['announcement_bar_bar_text_3']) {
			$this->error['error_name'] = $this->language->get('error_name');
		}
		return !$this->error;	
	}
}
?>