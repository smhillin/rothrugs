<?php 
class ControllerUserChangePwd extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('user/changepwd');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_default'] = $this->language->get('text_default');
		$data['text_success'] = $this->language->get('text_success');
		$data['entry_old_password'] = $this->language->get('entry_old_password');
		$data['entry_new_password'] = $this->language->get('entry_new_password');	
		$data['entry_retype_password'] = $this->language->get('entry_retype_password');	

		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_donotmatch'] = $this->language->get('error_donotmatch');
		$data['error_oldpwdempty'] = $this->language->get('error_oldpwdempty');
		$data['error_newpwdempty'] = $this->language->get('error_newpwdempty');
		$data['error_retypepwdempty'] = $this->language->get('error_retypepwdempty');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['token'] = $this->session->data['token'];

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/changepwd', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
    	$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/changepwd.tpl', $data));
	}
	
	public function changepass() {		
		$this->load->model('user/changepwd');					
		$this->model_user_changepwd->changePassword($this->user->getId(),$this->request->post);		
		//$this->session->data['success'] = $this->language->get('text_success');
		$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		
	}
}
?>