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
class ControllerModuleCategoryApi extends Controller{
	private $error = array();
	private $_api ;
	function index(){
		$this->load->language('api/manual');
		$this->load->library('api');
		$this->_api =  new Api($this->registry);
		if(!$this->checkToken($this->request->get['token'])){
			$data['status'] = false;
			$data['error'] = 'token  unvalivable!';
			echo json_encode($data);
			exit();
		}
		if(!$this->_api->checkApiKey($this->request->get['api_key'])){
			$data['status'] = false;
			$data['error'] = 'api key  unvalivable!';
			echo json_encode($data);
			exit();
		}else{
			if(isset($this->request->get['action'])){
				switch($this->request->get['action']){
					case 'autocomplete':	
						$this->autocomplete();
						break;
				}
			}
		}
	}
	private function  checkToken($token){
		$data = $this->_api->getByToken($token);
		if(empty($data)){
			return false;
		}else{
			return true;
		}
	}
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('api/catalog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_api_catalog_category->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}


}



?>
