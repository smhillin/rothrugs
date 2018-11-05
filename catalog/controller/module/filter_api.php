<?php

/**
 * API controller Product
 *
 * @author Lenovo
 */
class ControllerModuleFilterApi extends Controller{

	private $error = array();
	private $_api;

	public function index(){
		$this->load->language('api/manual');
		$this->load->library('api');
		$this->_api = new Api($this->registry);

		if(!$this->checkToken($this->request->get['token'])){
			$data['status'] = false;
			$data['error'] = 'token  unvalivable!';
			echo json_encode($data);
			exit();
		}
		// if(!$this->_api->checkApiKey($this->request->get['api_key'])){
		// 	$data['status'] = false;
		// 	$data['error'] = 'api key  unvalivable!';
		// 	echo json_encode($data);
		// 	exit();
		// }else{
			if(isset($this->request->get['action'])){
				switch($this->request->get['action']){
					case 'list':
						$this->getList();
						break;
					case 'insert':
					
						$this->insert();
						break;
					case 'autocomplete':
						$this->autocomplete();
						break;
					case 'getInfoGroup':
						$this->getGroupFilterInfo();
						break;
					default :
						$this->getList();
						break;
				}
			}
		// }
	}
	protected function getGroupFilterInfo(){
		if(!isset($this->request->get['filter_group_id'])){
			$data = array('status'=>false,'error'=>array('error_id' => 'Don\'t have filter group id'));
			return $this->response->setOutput(json_encode($data));
		}
		$this->load->model('api/catalog/filter');
		$data['filter_group_info'] = $this->model_api_catalog_filter->getFilterGroup($this->request->get['filter_group_id']);
		$data['filters'] = $this->model_api_catalog_filter->getFilterDescriptions($this->request->get['filter_group_id']);
		return $this->response->setOutput(json_encode($data));
	}
	protected function getList(){
		$this->load->model('api/catalog/filter');
		if(isset($this->request->get['sort'])){
			$sort = $this->request->get['sort'];
		}else{
			$sort = 'fgd.name';
		}

		if(isset($this->request->get['order'])){
			$order = $this->request->get['order'];
		}else{
			$order = 'ASC';
		}

		if(isset($this->request->get['page'])){
			$page = $this->request->get['page'];
		}else{
			$page = 1;
		}

		if(isset($this->request->get['limit'])){
			$limit = $this->request->get['page'];
		}else{
			$limit = $this->config->get('config_limit_admin');
		}
		$data['filters'] = array();

		$filter_data = array(
			'sort'	 => $sort,
			'order'	 => $order,
			'start'	 => ($page - 1) * $limit,
			'limit'	 => $limit
		);

		$results = $this->model_api_catalog_filter->getFilterGroups($filter_data);

		foreach($results as $result){
			$data['filters'][] = array(
				'filter_group_id'	 => $result['filter_group_id'],
				'name'				 => $result['name'],
				'sort_order'		 => $result['sort_order'],
			);
		}

		$data['error'] = array();
		$data['status'] = true;
		if(isset($this->error['warning'])){
			$data['error']['error_warning'] = $this->error['warning'];
		}

		if(!empty($data['error'])){
			$data['status'] = false;
		}

		$this->response->setOutput(json_encode($data));
	}

	public function insert(){
		$this->load->language('api/catalog/filter');
		$this->load->model('api/catalog/filter');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()){
			$insert = $this->request->post;
			if(!isset($insert['sort_order'])){
				$insert['sort_order'] = 0;
			}
			$filter_group_id = $this->model_api_catalog_filter->addFilter($insert);
			$data['filter_group_info'] = $this->model_api_catalog_filter->getFilterGroup($filter_group_id);
			$data['filters'] = $this->model_api_catalog_filter->getFilterDescriptions($filter_group_id);
			$message = $this->language->get('text_success');
			$data['status'] = true;
			$data['message'] = $message;
			return $this->response->setOutput(json_encode($data));
		}

		$this->getError();
	}

	public function update(){
		$this->load->language('api/catalog/filter');
		$this->load->model('api/catalog/filter');
		if(!isset($this->request->get['filter_group_id'])){
			$data = array(
				'status' => false,
				'error'	 => array(
					'error_id' => 'Don\'t have filter group id',
				)
			);
			return $this->response->setOutput(json_encode($data));
		}
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()){
			$data['filter_group_info'] = $this->model_api_catalog_filter->getFilterGroup($this->request->get['filter_group_id']);
			$data['filter_group_description'] = $this->model_api_catalog_filter->getFilterGroupDescriptions($this->request->get['filter_group_id']);
			$message = $this->language->get('text_success');
			$data['status'] = true;
			$data['message'] = $message;
			return $this->response->setOutput(json_encode($data));
		}

		$this->getError();
	}

	protected function validateForm(){
			$name = $this->request->post['filter_group_name'];
			if((utf8_strlen($name) < 1) || (utf8_strlen($name) > 64)){
				$this->error['group'] = $this->language->get('error_group');
			}
		

		if(isset($this->request->post['filter'])){
			foreach($this->request->post['filter'] as $filter_id => $filter){
					if((utf8_strlen($filter['filter_name']) < 1) || (utf8_strlen($filter['filter_name']) > 64)){
						$this->error['filter'][$filter_id] = $this->language->get('error_name');
					}

			}
		}

		return !$this->error;
	}

	protected function getError(){
		$data['error'] = array();
		$data['status'] = true;

		if(isset($this->error['warning'])){
			$data['error']['error_warning'] = $this->error['warning'];
		}

		if(isset($this->error['group'])){
			$data['error']['error_group'] = $this->error['group'];
		}

		if(isset($this->error['filter'])){
			$data['error']['error_filter'] = $this->error['filter'];
		}
		if(!empty($data['error'])){
			$data['status'] = false;
		}
		$this->response->setOutput(json_encode($data));
	}

	protected function autocomplete(){
		$json = array();
		$filter_name = '';
		if(isset($this->request->get['filter_name'])){
			$filter_name = $this->request->get['filter_name'];
		}
			$this->load->model('api/catalog/filter');

			$filter_data = array(
				'filter_name'	 =>$filter_name,
				'start'			 => 0,
				'limit'			 => 5
			);

			$filters = $this->model_api_catalog_filter->getFilters($filter_data);

			foreach($filters as $filter){
				$json[] = array(
					'filter_id'	 => $filter['filter_id'],
					'name'		 => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		

		$sort_order = array();

		foreach($json as $key => $value){
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}
	private function  checkToken($token){
		$data = 	$this->_api->getByToken($token);
		if(empty($data)){
			return false;
		}else{
			return true;
		}
	}
}