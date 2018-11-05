<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of product
 *
 * @author Lenovo
 */
class ControllerModuleUserApi extends Controller{
	private $error = array();
	private $_api ;
	function index(){
		$this->load->language('api/manual');
		$this->load->library('api');
		$this->_api =  new Api($this->registry);
			if(isset($this->request->get['action'])){
				switch($this->request->get['action']){
					case 'login':
					
						$this->login();
						break;
					case 'logout':
						$this->logout();
						break;	
					default :
						echo 'hello';
						break;
				}
			}
		
	}
	protected function login(){
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
			$lastToken = $this->_api->getByTokenByApiId($this->_api->api_id);
			if(!empty($lastToken)){
				$this->_api->deleteToken($this->_api->api_id);
			}
			$token = uniqid() . time();
			$this->_api->createToken($this->_api->username, $this->_api->api_id,$token);
			$this->response->setOutput(json_encode(array('status' => true, 'token'	 => $token)));
		}else{
			$this->response->setOutput(json_encode(array('status' => false, 'error'	 => $this->error)));
		}
	}
	protected function logout(){
		if($this->request->get('token')){
			$token = $this->request->get('token');
			$data = $this->_api->getByToken($token);
			if(!empty($data)){
				$this->_api->deleteToken($data['api_id']);
				$this->response->setOutput(json_encode(array('status' => true)));
			}
		}else{
			$this->response->setOutput(json_encode(array('status' => false, 'error'	 => array('error_token' => 'token do not exsist!'))));
		}
		exit();
	}
	protected function validate() {
			
		if (!$this->_api->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
}



?>
