<?php
class ControllerModuleOption extends Controller {
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
		// if(!$this->_api->checkApiKey($this->request->get['api_key'])){
		// 	$data['status'] = false;
		// 	$data['error'] = 'api key  unvalivable!';
		// 	echo json_encode($data);
		// 	exit();
		// }else{
			if(isset($this->request->get['action'])){
				switch($this->request->get['action']){
					case 'getlist':	
						$this->get_list();
						break;
					case 'insert':	
						$this->insert();
						break;
					case 'getRelatedShape':
						$this->get_related_option_shape();
						break;
					case 'getRelatedSize':
					$this->get_related_option_size();
						break;
				}
			}
		// }
	}
	private function  checkToken($token){
		$data = $this->_api->getByToken($token);
		if(empty($data)){
			return false;
		}else{
			return true;
		}
	}
	protected function get_related_option_shape(){
		$this->load->model('api/catalog/option');
		$option_module =  $this->config->get('option_module');
		$data['option_shapes'] = $this->model_api_catalog_option->getAllOptionsById($option_module['op_shape_id']);
		$this->response->setOutput(json_encode($data));
	}
	protected function get_related_option_size(){
		$this->load->model('api/catalog/option');
		$option_module =  $this->config->get('option_module');
		$data['option_sizes'] = $this->model_api_catalog_option->getAllOptionsById($option_module['op_size_id']);
		$this->response->setOutput(json_encode($data));
	}

	protected function get_list() {
		$json = array();

			$this->load->language('catalog/option');

			$this->load->model('api/catalog/option');

			$this->load->model('tool/image');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);
			$options = $this->model_api_catalog_option->getOptions($filter_data);
			foreach ($options as $option) {
				$option_value_data = array();

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_values = $this->model_api_catalog_option->getOptionValues($option['option_id']);

					foreach ($option_values as $option_value) {
						if (is_file(DIR_IMAGE . $option_value['image'])) {
							$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
						} else {
							$image = '';
						}

						$option_value_data[] = array(
							'option_value_id' => $option_value['option_value_id'],
							'name'            => strip_tags(html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')),
							// 'image'           => $image
						);
					}

					$sort_order = array();

					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $option_value_data);
				}

				$type = '';

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$type = $this->language->get('text_choose');
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = $this->language->get('text_input');
				}

				if ($option['type'] == 'file') {
					$type = $this->language->get('text_file');
				}

				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = $this->language->get('text_date');
				}

				$json[] = array(
					// 'option_id'    => $option['option_id'],
					// 'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					// 'category'     => $type,
					// 'type'         => $option['type'],
					'option_value' => $option_value_data
				);
			}
		
		// $sort_order = array();

		// foreach ($json as $key => $value) {
		// 	$sort_order[$key] = $value['name'];
		// }

		// array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}

	protected function insert()
	{
		$this->load->language('catalog/option');
		$this->load->model('api/catalog/option');
		$this->load->model('tool/image');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
			$produc_insert = $this->request->post;
			// var_dump($produc_insert); die;
			$produc_insert['product_store'] = array(0);
			$product_ids = $this->model_api_catalog_option->addOption($this->request->post);
			$data = $this->model_api_catalog_option->getOptionDescription();			
			$json = array(
				'id' => $data['option_value_id']
			);
			$this->response->setOutput(json_encode($json));
		}
		
	}

	protected function validate() {

		if (isset($this->request->post['option_module'])) {
			foreach ($this->request->post['option_module'] as $key => $value) {
				if (!$value) {
					$this->error['option'][$key] = $this->language->get('error_option');
				}else{
					
				}
			}
		}

		return !$this->error;
	}

	protected function validaaate() {

		$product_description = $this->request->post['product_description'];
		// var_dump($product_description);die('dd');
	
			if((utf8_strlen($product_description['name']) < 3) || (utf8_strlen($product_description['name']) > 255)){
				$this->error['name']= $this->language->get('error_name');
			}

			if((utf8_strlen($product_description['meta_title']) < 3) || (utf8_strlen($product_description['meta_title']) > 255)){
				$this->error['meta_title']= $this->language->get('error_meta_title');
			}
		
		if((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)){
			$this->error['model'] = $this->language->get('error_model');
		}

		if($this->error && !isset($this->error['warning'])){
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}