<?php    
class ControllerPomgmtSupplier extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('pomgmt/supplier');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		 
		$this->load->model('pomgmt/supplier');
		
    	$this->getList();
  	}
  
	public function sendAccountMail($userID) {
		
		$userInfo = $this->model_user_user->getUser($userID);
		$supplierInfo = $this->model_pomgmt_supplier->getSupplierForUser($userID);
		
		$this->load->model('setting/setting');
		$store_name = $this->config->get('config_name');
		$store_address = $this->config->get('config_address');
		$store_email = $this->config->get('config_email');
		$store_telephone = $this->config->get('config_telephone');
		$store_fax = $this->config->get('config_fax');
		$tokens = array(
			'{user_name}',
			'{password}',
			'{supplier}',
			'{store_name}',
			'{store_address}',
			'{store_url}',
			'{store_email}',
			'{store_phone}',			
		);
		$tokenValues = array(
				$userInfo['username'],
				'a234e4f011',
				$supplierInfo['name'],
				$store_name,
				nl2br($store_address),
				HTTP_CATALOG,
				$store_email,
				$store_telephone
			);
		
		$templateHTML = file_get_contents(DIR_TEMPLATE.'pomgmt/account.html');
		$templateHTML = str_replace($tokens,$tokenValues,$templateHTML);
		
		if ($supplierInfo['email']) {
			$fromID = $this->config->get('config_email');
			$mail = new Mail();	
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($supplierInfo['email']);
			$mail->setFrom($fromID);
			$mail->setSender($store_name);
			$mail->setSubject("Your account is created at " .$store_name);
			
			$mail->setHtml($templateHTML);
			$mail->send();
		}		
	}
  	public function insert() {
		$this->load->language('pomgmt/supplier');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/supplier');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$userResult = $this->model_pomgmt_supplier->addSupplier($this->request->post);
			if ($userResult!=0) {
				$this->sendAccountMail($userResult);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->load->language('pomgmt/supplier');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/supplier');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$userResult = $this->model_pomgmt_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);
			if ($userResult!=0) {
				$this->sendAccountMail($userResult);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('pomgmt/supplier');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/supplier');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_pomgmt_supplier->deleteSupplier($supplier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getList();
  	}  
    
  	private function getList() {
	
		$data['entry_id'] = $this->language->get('entry_id');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_email'] = $this->language->get('entry_email');
	    $data['text_confirm'] = $this->language->get('text_confirm');	
		if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} else {
			$filter_id = null;
		}
		
		if (isset($this->request->get['filter_supplier'])) {
			$filter_supplier = $this->request->get['filter_supplier'];
		} else {
			$filter_supplier = null;
		}
		if (isset($this->request->get['filter_currency_id'])) {
			$filter_currency_id = $this->request->get['filter_currency_id'];
		} else {
			$filter_currency_id = null;
		}
		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
		
		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
		
		if (isset($this->request->get['filter_supplier'])) {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_currency_id'])) {
			$url .= '&filter_currency_id=' . $this->request->get['filter_currency_id'];
		}
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['token'] = $this->session->data['token'];
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$data['insert'] = $this->url->link('pomgmt/supplier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('pomgmt/supplier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['suppliers'] = array();

		$params = array(
			'filter_id'        => $filter_id,
			'filter_supplier'        => $filter_supplier,
			'filter_currency_id'        => $filter_currency_id,
			'filter_email'        => $filter_email,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$supplier_total = $this->model_pomgmt_supplier->getTotalSuppliers();
	
		$results = $this->model_pomgmt_supplier->getSuppliers($params);
  
		$this->load->model('localisation/currency');
		
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
    	foreach ($results as $result) {
			$action = array();
			
			$action[0] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('pomgmt/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL')
			);
			$action[1] = array(
				'text' => $this->language->get('text_viewpo'),
				'href' => $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL')
			);			
			$data['suppliers'][] = array(
				'supplier_id' => $result['supplier_id'],
				'name'            => $result['name'],
				'currency'        => $result['title'],
				'email'      	  => $result['email'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['supplier_id'], $this->request->post['selected']),
				'edit'           => $this->url->link('pomgmt/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL'),
				'viewpo'		 => $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL')
			);
		}	
	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_form'] = $this->language->get('text_form');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_id'] = $this->language->get('column_id');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_currency'] = $this->language->get('column_currency');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
 		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['text_viewpo'] = $this->language->get('text_viewpo');	
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
		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
		if (isset($this->request->get['filter_supplier'])) {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_currency_id'])) {
			$url .= '&filter_currency_id=' . $this->request->get['filter_currency_id'];
		}
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_id'] = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . '&sort=supplier_id' . $url, 'SSL');
		$data['sort_name'] = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_currency'] = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . '&sort=currency' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . '&sort=email' . $url, 'SSL');
		
		$url = '';
		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
		if (isset($this->request->get['filter_supplier'])) {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_currency_id'])) {
			$url .= '&filter_currency_id=' . $this->request->get['filter_currency_id'];
		}
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $supplier_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($supplier_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($supplier_total - 10)) ? $supplier_total : ((($page - 1) * 10) + 10), $supplier_total, ceil($supplier_total / 10));

		$data['filter_id'] = $filter_id;		
		$data['filter_supplier'] = $filter_supplier;
		$data['filter_currency_id'] = $filter_currency_id;
		$data['filter_email'] = $filter_email;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		//$this->template = 'pomgmt/supplier_list.tpl';
		//$this->children = array(
		//	'common/header',
		//	'common/footer',
		//);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		//$this->response->setOutput($this->render());
		$this->response->setOutput($this->load->view('pomgmt/supplier_list.tpl', $data));
	}
  
  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_form'] = $this->language->get('text_form');
    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
    	$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$data['text_no'] = $this->language->get('text_no');
		
		$data['entry_id'] = $this->language->get('entry_id');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_comments'] = $this->language->get('entry_comments');
		$data['entry_notes'] = $this->language->get('entry_notes');
		$data['entry_address1'] = $this->language->get('entry_address1');
		$data['entry_address2'] = $this->language->get('entry_address2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
  		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_maxshipdays'] = $this->language->get('entry_maxshipdays');
		$data['entry_dropshipfee'] = $this->language->get('entry_dropshipfee');
		$data['entry_itemdel_fee'] = $this->language->get('entry_itemdel_fee');
		$data['entry_gp_percent'] = $this->language->get('entry_gp_percent');
		$data['entry_exportpath'] = $this->language->get('entry_exportpath');
		$data['entry_fileattach'] = $this->language->get('entry_fileattach');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_taxrate'] = $this->language->get('entry_taxrate');
	    $data['entry_supplierurl'] = $this->language->get('entry_supplierurl');
		$data['entry_orderurl'] = $this->language->get('entry_orderurl');
		$data['entry_im'] = $this->language->get('entry_im');
		
		
		$data['help_dropshipfee'] = $this->language->get('help_dropshipfee');
		$data['help_username'] = $this->language->get('help_username');
		$data['help_grossprofit'] = $this->language->get('help_grossprofit');
		$data['help_exportpath'] = $this->language->get('help_exportpath');
		$data['help_email'] = $this->language->get('help_email');
		$data['help_comments'] = $this->language->get('help_comments');
		$data['help_itemdel_fee'] = $this->language->get('help_itemdel_fee');
		
			
		$data['button_save'] = $this->language->get('button_save');
	    $data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
			  
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		
			
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}
		
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}
		
		if (isset($this->error['address1'])) {
			$data['error_address1'] = $this->error['address1'];
		} else {
			$data['error_address1'] = '';
		}
		
		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['currency'])) {
			$data['error_currency'] = $this->error['currency'];
		} else {
			$data['error_currency'] = '';
		}
		
		if (isset($this->error['taxrate'])) {
			$data['error_taxrate'] = $this->error['taxrate'];
		} else {
			$data['error_taxrate'] = '';
		}
		
		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		
		
		$data['breadcrumbs'] = array();
		
		
		$data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
	//	'separator' => ' :: '
		);
		
		
		$data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
	//	'separator' => false
		);

		
							
		if (!isset($this->request->get['supplier_id'])) {
			$data['action'] = $this->url->link('pomgmt/supplier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('pomgmt/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('pomgmt/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];
		
	    if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      	$supplier_info = $this->model_pomgmt_supplier->getSupplier($this->request->get['supplier_id']);
	    }

		if (isset($supplier_info)) {
			$data['supplier_id'] = $supplier_info['supplier_id'];
		} else {	
	    	$data['supplier_id'] = '-NA-';
	    }
		
	    if (isset($this->request->post['name'])) {
	      	$data['name'] = $this->request->post['name'];
	    } elseif (isset($supplier_info)) {
			$data['name'] = $supplier_info['name'];
		} else {	
	    	$data['name'] = '';
	    }
		
		if (isset($this->request->post['supplierurl'])) {
	    	$data['supplierurl'] = $this->request->post['supplierurl'];
	    } elseif (isset($supplier_info)) {
			$data['supplierurl'] = $supplier_info['supplierurl'];
		} else {	
	    	$data['supplierurl'] = '';
	    }
		
		if (isset($this->request->post['orderurl'])) {
	    	$data['orderurl'] = $this->request->post['orderurl'];
	    } elseif (isset($supplier_info)) {
			$data['orderurl'] = $supplier_info['orderurl'];
		} else {	
	    	$data['orderurl'] = '';
	    }

		if (isset($this->request->post['im'])) {
	    	$data['im'] = $this->request->post['im'];
	    } elseif (isset($supplier_info)) {
			$data['im'] = $supplier_info['im'];
		} else {	
	    	$data['im'] = '';
	    }
		
		if (isset($this->request->post['firstname'])) {
	    	$data['firstname'] = $this->request->post['firstname'];
	    } elseif (isset($supplier_info)) {
			$data['firstname'] = $supplier_info['firstname'];
		} else {	
	    	$data['firstname'] = '';
	    }

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($supplier_info)) {
			$data['lastname'] = $supplier_info['lastname'];
		} else {	
			$data['lastname'] = '';
		}
		
		$this->load->model('localisation/tax_rate');
		
		$data['tax_rates'] = $this->model_localisation_tax_rate->getTaxRates();

		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['supplier_store'])) {
			$data['supplier_store'] = $this->request->post['supplier_store'];
		} elseif (isset($supplier_info)) {
			$data['supplier_store'] = $this->model_pomgmt_supplier->getSupplierStores($this->request->get['supplier_id']);
		} else {
			$data['supplier_store'] = array(0);
		}
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($supplier_info)) {
			$data['keyword'] = $supplier_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		
		
		if (isset($this->request->post['tax_rate_id'])) {
			$data['tax_rate_id'] = $this->request->post['tax_rate_id'];
		} elseif (isset($supplier_info)) {
			$data['tax_rate_id'] = $supplier_info['tax_rate_id'];
		} else {
			$data['tax_rate_id'] = '0';
		}
		
		
		//TODO: this is not correct
		if (isset($this->request->post['currency_id'])) {
			$data['currency_id'] = $this->request->post['currency_id'];
		} elseif (isset($supplier_info)) {
			$data['currency_id'] = $supplier_info['currency_id'];
		} else {
			$data['currency_id'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (isset($supplier_info)) {
			$data['email'] = $supplier_info['email'];
		} else {
	      	$data['email'] = '';
		}
		
		if (isset($this->request->post['comments'])) {
	      		$data['comments'] = $this->request->post['comments'];
	    } elseif (isset($supplier_info)) {
			$data['comments'] = $supplier_info['comments'];
		} else {
	      		$data['comments'] = '';
		}
		
		if (isset($this->request->post['notes'])) {
	      		$data['notes'] = $this->request->post['notes'];
	    } elseif (isset($supplier_info)) {
			$data['notes'] = $supplier_info['notes'];
		} else {
	      		$data['notes'] = '';
		}
	
		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($supplier_info)) { 
			$data['telephone'] = $supplier_info['telephone'];
		} else {
	      		$data['telephone'] = '';
		}

		if (isset($this->request->post['maxshipdays'])) {
			$data['maxshipdays'] = $this->request->post['maxshipdays'];
		} elseif (isset($supplier_info)) { 
			$data['maxshipdays'] = $supplier_info['maxshipdays']==0?'':$supplier_info['maxshipdays'];
		} else {
	      		$data['maxshipdays'] = '';
		}

		if (isset($this->request->post['dropshipfee'])) {
	    	$data['dropshipfee'] = $this->request->post['dropshipfee'];
	    } elseif (isset($supplier_info)) { 
			$data['dropshipfee'] = $supplier_info['dropship_fee'];
		} else {
	      		$data['dropshipfee'] = '';
	    }

		if (isset($this->request->post['itemdel_fee'])) {
	    	$data['itemdel_fee'] = $this->request->post['itemdel_fee'];
	    } elseif (isset($supplier_info)) { 
			$data['itemdel_fee'] = $supplier_info['itemdel_fee'];
		} else {
	      	$data['itemdel_fee'] = '';
	    }
		
		if (isset($this->request->post['exportpath'])) {
	    	$data['exportpath'] = $this->request->post['exportpath'];
	    } elseif (isset($supplier_info)) { 
			$data['exportpath'] = $supplier_info['exportpath'];
		} else {
	     	$data['exportpath'] = '';
	    }
		
		if (isset($this->request->post['fileattach'])) {
	    	$data['fileattach'] = $this->request->post['fileattach'];
	    } elseif (isset($supplier_info)) { 
			$data['fileattach'] = $supplier_info['fileattach'];
		} else {
	      	$data['fileattach'] = '';
	    }

		if (isset($this->request->post['gp_percent'])) {
	      		$data['gp_percent'] = $this->request->post['gp_percent'];
	    	} elseif (isset($supplier_info)) { 
			$data['gp_percent'] = $supplier_info['gp_percent'];
		} else {
	      		$data['gp_percent'] = $this->config->get('config_po_grossprofit');
	    	}
		
		if (isset($this->request->post['username'])) {
	      		$data['username'] = $this->request->post['username'];
	    	} elseif (isset($supplier_info)) { 
			$data['username'] = $supplier_info['username'];
		} else {
	      		$data['username'] = '';
	    	}
		
		if (isset($this->request->post['address1'])) {
	      		$data['address1'] = $this->request->post['address1'];
	    	} elseif (isset($supplier_info)) {
			$data['address1'] = $supplier_info['address1'];
		} else {
	      		$data['address1'] = '';
	    	}
		
		if (isset($this->request->post['address2'])) {
	      		$data['address2'] = $this->request->post['address2'];
	    	} elseif (isset($supplier_info)) {
			$data['address2'] = $supplier_info['address2'];
		} else {
	      		$data['address2'] = '';
	    	}
		
		if (isset($this->request->post['city'])) {
	      		$data['city'] = $this->request->post['city'];
	    	} elseif (isset($supplier_info)) {
			$data['city'] = $supplier_info['city'];
		} else {
	      		$data['city'] = '';
	    	}
		
		if (isset($this->request->post['postcode'])) {
	      		$data['postcode'] = $this->request->post['postcode'];
	    	} elseif (isset($supplier_info)) {
			$data['postcode'] = $supplier_info['postcode'];
		} else {
	      		$data['postcode'] = '';
	    	}
		
		if (isset($this->request->post['country_id'])) {
	      		$data['country_id'] = $this->request->post['country_id'];
	    	} elseif (isset($supplier_info)) { 
			$data['country_id'] = $supplier_info['country_id'];
		} else {
	      		$data['country_id'] = '';
	    	}
		
		$this->load->model('localisation/country');
		
		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->load->model('localisation/currency');
		
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		
		if (isset($this->request->post['zone_id'])) {
	      		$data['zone_id'] = $this->request->post['zone_id'];
	    	} elseif (isset($supplier_info)) { 
			$data['zone_id'] = $supplier_info['zone_id'];
		} else {
	      		$data['zone_id'] = '';
	    	}

	    	if (isset($this->request->post['status'])) {
	      		$data['status'] = $this->request->post['status'];
	    	} elseif (isset($supplier_info)) { 
			$data['status'] = $supplier_info['status'];
		} else {
	      		$data['status'] = 1;
	    	}
		
		if (isset($this->request->post['tax'])) {
	      		$data['tax'] = $this->request->post['tax'];
	    	} elseif (isset($supplier_info)) {
			$data['tax'] = $supplier_info['tax'];
		} else {
	      		$data['tax'] = '';
	    	}
			
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
	
				
		$this->response->setOutput($this->load->view('pomgmt/supplier_form.tpl', $data));		

	}  
	 
  	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'pomgmt/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if (strlen(utf8_decode($this->request->post['firstname'])) > 32) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		if (strlen(utf8_decode($this->request->post['lastname'])) > 32){
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
	
		if ($this->request->post['email']!="") {
			if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $this->request->post['email']))) {
				$this->error['email'] = $this->language->get('error_email');
			}
		}

		if (strlen(utf8_decode($this->request->post['telephone'])) > 32) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
	
		if (strlen(utf8_decode($this->request->post['address1'])) > 128) {
			$this->error['address1'] = $this->language->get('error_address1');
		}

		if (strlen(utf8_decode($this->request->post['city'])) > 128) {
			$this->error['city'] = $this->language->get('error_city');
		}
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if (strlen(utf8_decode($this->request->post['postcode'])) > 10) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
		if ($this->request->post['currency_id'] == 'false') {
      		$this->error['currency'] = $this->language->get('error_currency');
    	}
		if ($this->request->post['tax']!=0 && $this->request->post['tax_rate_id'] == 'false' ) {
      		$this->error['taxrate'] = $this->language->get('error_taxrate');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'pomgmt/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $supplier_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsBySupplierId($supplier_id);
    
			if ($product_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	}
	
	public function zone() {
		$output = '<option value="0">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output);
	}	
	
	public function suppliercurrency()
	{
		$this->load->model('pomgmt/supplier');
		$output = '';
		$results = $this->model_pomgmt_supplier->getSupplierCurrency($this->request->get['supplier_id']);
		foreach ($results as $result) {
			$output = $result;
		}		
		$this->response->setOutput($output);
	}
	
	public function getsuppliergp()
	{
		$this->load->model('pomgmt/supplier');
		$output = '';
		$results = $this->model_pomgmt_supplier->getSupplierGP($this->request->get['supplier_id']);
		foreach ($results as $result) {
			$output = $result;
		}		

		$this->response->setOutput($output);
	}
	
	public function gettaxrate()
	{
		$this->load->model('pomgmt/supplier');
		$output = '';
		$results = $this->model_pomgmt_supplier->getSupplierTaxRate($this->request->get['supplier_id']);
		foreach ($results as $result) {
			$output = $result;
		}		
		$this->response->setOutput($output);
	}
	
	public function getsuppliercomments()
	{
		$this->load->model('pomgmt/supplier');
		$output = '';
		$results = $this->model_pomgmt_supplier->getSupplier($this->request->get['supplier_id']);
		$this->response->setOutput($results ['comments']);
	}
	
	public function getsupplierdeliverycharges()
	{
		$this->load->model('pomgmt/supplier');
		$output = '';
		$results = $this->model_pomgmt_supplier->getSupplier($this->request->get['supplier_id']);
		$this->response->setOutput($results ['dropship_fee']);
	}
	
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('pomgmt/supplier');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_supplier'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_pomgmt_supplier->getSuppliers($data);
			
			foreach ($results as $result) {									
				$json[] = array(
					'supplier_id' => $result['supplier_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>