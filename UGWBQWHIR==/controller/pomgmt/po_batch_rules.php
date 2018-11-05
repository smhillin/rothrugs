<?php 
class ControllerPomgmtPoBatchRules extends Controller { 
	public function index(){
		$this->load->language('pomgmt/po_batch_rules');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
		'text'      => 'Home',
		'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => false
		);

		$data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('pomgmt/po_batch_rules', 'token=' . $this->session->data['token'] , 'SSL'),
		'separator' => ' :: '
		);
		
		$this->load->model('pomgmt/supplier');
		$data['suppliers'] = $this->model_pomgmt_supplier->getSuppliers();
		
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
		if (!$data['stores'])
			$data['stores'][] = array ('store_id'=>0,'name'=>'Default');
			
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_pobatch'] = $this->language->get('text_pobatch');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_supplier'] = $this->language->get('entry_supplier');
		$data['button_create'] = $this->language->get('button_create');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$url = '';
		$data['create'] = $this->url->link('pomgmt/po/create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pomgmt/po_batch_rules.tpl', $data));
	//	$this->response->setOutput($this->render());
	}
		
	public function createpo(){
		if($this->request->post){
			$this->load->model('pomgmt/po_batch_rules');
			$this->load->language('sale/order');
			$this->load->model('sale/order');
			foreach($this->request->post['supplier'] as $supplier_id){
				$store_id = $this->request->post['store'];
				
				$orderProducts = $this->model_pomgmt_po_batch_rules->get_supplier_order_product($supplier_id,$store_id);				
				foreach ($orderProducts as $orderProduct){
					$order_product_ids = explode(",",$orderProduct['order_product_ids']);
					//print_r($order_product_ids);die('done');
					if(count($order_product_ids) > 0){						
						$this->model_sale_order->generatepo($order_product_ids);
						$this->session->data['success'] = $this->language->get('text_posuccess');
					}					
				}
			}
		}
		$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>