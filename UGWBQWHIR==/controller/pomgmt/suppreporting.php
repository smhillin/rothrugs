<?php
class ControllerPomgmtSuppReporting extends Controller {
	public function index() {     
		$this->load->language('pomgmt/suppreporting');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = "";
		}	
		
		if (isset($this->request->get['filter_orderwise'])) {
			$filter_orderwise = $this->request->get['filter_orderwise'];
		} else {
			$filter_orderwise = 0;
		}	
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_orderwise'])) {
			$url .= '&filter_orderwise=' . $this->request->get['filter_orderwise'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
						
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('pomgmt/suppreporting', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('pomgmt/reports');
		
		$data['suppreporting'] = array();
		
		if (isset($this->request->get['print']))  {
			$data = array(
				'filter_date_start'	     => $filter_date_start, 
				'filter_date_end'	     => $filter_date_end, 
				'filter_store_id' => $filter_store_id,
				'filter_orderwise' => $filter_orderwise,
			);
		}
		else {
			$data = array(
				'filter_date_start'	     => $filter_date_start, 
				'filter_date_end'	     => $filter_date_end, 
				'filter_store_id' => $filter_store_id,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);
		}
		
		if ($filter_orderwise) {
			$suppreporting_total = $this->model_pomgmt_reports->getSupplierReportingTotalsOrderWise($data); 
			
			$results = $this->model_pomgmt_reports->getSupplierReportingOrderWise($data);
		}
		else {
			$suppreporting_total = $this->model_pomgmt_reports->getSupplierReportingTotalsStoreWise($data); 
			
			$results = $this->model_pomgmt_reports->getSupplierReportingStoreWise($data);
		}
		$this->load->model('setting/store');
		$poTotal = 0; $poValueTotal=0;
		foreach ($results as $result) {
			if ($filter_orderwise) {
				$data['suppreporting'][] = array(				
					'order_id'       => $result['order_id'],
					'pur_value'          => $this->currency->format($result['pur_value'], $this->config->get('config_currency')),	
					'poinvoice_ref'   => $result['poinvoice_ref'],
					'inv_value'   => $this->currency->format($result['inv_value'], $this->config->get('config_currency')),	
					'sales_order_id'   => $result['sales_order_id'],
				);
			}
			else {
				$data['suppreporting'][] = array(				
					'store_name'       => $result['store_name'],
					'ordercount'          => $result['ordercount'],
					'pur_value'          => $this->currency->format($result['pur_value'], $this->config->get('config_currency')),						
					'inv_value'   => $this->currency->format($result['inv_value'], $this->config->get('config_currency')),	
				);
			}
			$poTotal += $result['pur_value'];
			$poValueTotal += $result['inv_value'];
		}
		 
		$data['poTotal'] = $this->currency->format($poTotal, $this->config->get('config_currency'));
		$data['poValueTotal'] = $this->currency->format($poValueTotal, $this->config->get('config_currency'));
		
 		$data['heading_title'] = $this->language->get('heading_title');
		 
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_all_stores'] = $this->language->get('text_all_stores');
		$data['text_store_owners'] = $this->language->get('text_store_owners');
		$data['text_store_sponsors'] = $this->language->get('text_store_sponsors');
		$data['text_sales_persons'] = $this->language->get('text_sales_persons');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_personwise'] = $this->language->get('text_personwise');
		
		$data['column_store'] = $this->language->get('column_store');
		$data['column_ponumber'] = $this->language->get('column_ponumber');
		$data['column_povalue'] = $this->language->get('column_povalue');
		$data['column_purinvno'] = $this->language->get('column_purinvno');
		$data['column_purinvvalue'] = $this->language->get('column_purinvvalue');
		$data['column_salesorder'] = $this->language->get('column_salesorder');
		$data['column_ordercount'] = $this->language->get('column_ordercount');
		
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_orderwise'] = $this->language->get('entry_orderwise');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_print'] = $this->language->get('button_print');
		
		$data['token'] = $this->session->data['token'];
		

		$data['stores'] = $this->model_pomgmt_reports->getStores();			

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_orderwise'])) {
			$url .= '&filter_orderwise=' . $this->request->get['filter_orderwise'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $suppreporting_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pomgmt/suppreporting', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		
		$data['pagination'] = $pagination->render();
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_orderwise'] = $filter_orderwise;
		
		if (isset($this->request->get['print'])) 
			$this->template = 'pomgmt/suppreporting_print.tpl';		
		else
			$this->template = 'pomgmt/suppreporting.tpl';
		//$this->children = array(
		//	'common/header',
		//	'common/footer'
		//);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pomgmt/suppreporting.tpl', $data));		
		//$this->response->setOutput($this->render());
	}
}
?>