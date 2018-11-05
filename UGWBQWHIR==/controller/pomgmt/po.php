<?php    
class ControllerPomgmtPo extends Controller { 
	private $error = array();
  
	public function addstock() {
		$this->load->language('pomgmt/po');
		$this->load->model('pomgmt/po');
		$products_info = $this->model_pomgmt_po->updateStock($this->request->get['order_product_id']);
		$this->session->data['success'] = $this->language->get('text_stockupdatesuccess');
		$this->getForm();
	}
	
	public function fillitems() {
		$json = array();
		$this->load->model('pomgmt/po');
		$products_info = $this->model_pomgmt_po->getLowstockItems();
		foreach ($products_info as $product) {
			$json[] = array(
					'name' => $product['name'],
					'product_id' => $product['product_id'],
					'quantity'   => $product['reorderqty'],
					'cost'      => $product['cost'],
					'totalcost' => $product['cost'] * ($product['reorderqty']),
					'sale_order_product_id' => 0,
					'model'      => $product['model'],
					'total'      => $product['cost'] * ($product['reorderqty']),
					'supproductshorturl' => $product['supproducturl']==null?'':$this->make_it_shorter($product['supproducturl']),
					'supproducturl' => $product['supproducturl']==null?'':$product['supproducturl']
				);	
		}
		$this->response->setOutput(json_encode($json));
	}
	
  	public function index() {
		$this->load->language('pomgmt/po');
		
		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('pomgmt/po');
		
    	$this->getList();
  	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'pomgmt/po')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function recalc() {
		$this->load->model('pomgmt/po');
		$order_info = $this->model_pomgmt_po->updatePrices();
		$this->session->data['success'] = "Prices updated successfully";
		$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function remind() {
		
	}
	
	public function sendagain() {
		$this->load->language('pomgmt/po');

		$data['title'] = $this->language->get('heading_title');		
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['language'] = $this->language->get('code');

		$data['text_po'] = $this->language->get('text_po');

		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_vat'] = $this->language->get('text_vat');
		$data['text_delivery_charge'] = $this->language->get('text_delivery_charge');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_mpn'] = $this->language->get('column_mpn');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');		
		
		$this->load->model('pomgmt/po');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}


		
		foreach ($orders as $order_id) {
			$order_info = $this->model_pomgmt_po->getOrderForPrint($order_id);
			
			if ($order_info && ($order_info['supplier_email']!="")) {
				//if ($order_info['delay']==0) continue;
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['config_name'];
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_name = $this->config->get('config_name');
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] ."". $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
				);

				$replace = array(
					'firstname' => $order_info['supplier_firstname'],
					'lastname'  => $order_info['supplier_lastname'],
					'company'   => $order_info['supplier_name'],
					'address_1' => $order_info['supplier_address_1'],
					'address_2' => $order_info['supplier_address_2'],
					'city'      => $order_info['supplier_city'],
					'postcode'  => $order_info['supplier_postcode'],
					'zone'      => $order_info['supplier_zone'],
					'zone_code' => $order_info['supplier_zone_code'],
					'country'   => $order_info['supplier_country']				);
				
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';

				$supplier_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				
				if ($order_info['send_to']=='0'){
				
					$find = array(
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}',
						'{telephone}'
					);

					$replace = array(
						'firstname' => $order_info['shipping_firstname'],
						'lastname'  => $order_info['shipping_lastname'],
						'company'   => $order_info['shipping_company'],
						'address_1' => $order_info['shipping_address_1'],
						'address_2' => $order_info['shipping_address_2'],
						'city'      => $order_info['shipping_city'],
						'postcode'  => $order_info['shipping_postcode'],
						'zone'      => $order_info['shipping_zone'],
						'zone_code' => $order_info['shipping_zone_code'],
						'country'   => $order_info['shipping_country'],
						'telephone'   => $order_info['telephone']
					);
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}'."\n\nTelephone:".'{telephone}';;
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				else
				{
					$find = array(
						'{store_name}',
						'{store_address}',
						'{store_telephone}',
						'{country}'
					);

					$replace = array(
						'store_name' => $store_name,
						'store_address' => $store_address,
						'store_telephone'  => $store_telephone
					);
					$format = '{store_name}' . "\n" . '{store_address}' . "\n Tel:" . '{store_telephone}' ;
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				
				$product_data = array();

				$products = $this->model_pomgmt_po->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_pomgmt_po->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);								
					}

					$product_data[] = array(
						'name'     => empty($product['supproducttitle'])?$product['name']:$product['supproducttitle'],
						'model'    => $product['model'],
						'description'    => $product['description'],
						'mpn'	   => isset($product['mpn'])?$product['mpn']:'',
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    =>  number_format($product['price'],2),
						'total'    =>  number_format($product['total'],2)						
					);
				}
				
				$voucher_data = array();
				
				
					

				$data['order'] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'subtotal'		   =>  number_format($order_info['total'],2),
					'delivery_charge'  =>  number_format((float)$order_info['delivery_charge'],2),
					'vat'              =>  number_format($order_info['vat'],2),					
					'shipping_address' => $shipping_address,
					'supplier_address' => $supplier_address,
					'poinvoice_ref' => $order_info['poinvoice_ref'],
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'comments'          => nl2br($order_info['comments'])
				);
				
				

				$grandTotal = $order_info['delivery_charge'] +  $order_info['vat'] + $order_info['total'];
				
				if (!empty($order_info['user_id'])) {
					$po_url = HTTP_SERVER.'index.php?route=pomgmt/po/edit&order_id='.$order_id.'&auth_id='.$order_info['auth_token'];
					$supplier_login_allowed = true;
				}
				else {
					$po_url = '';
					$supplier_login_allowed = false;					
				}
					
				$data['po_no'] = $invoice_no;
				$data['po_date'] = $order_info['date_added'];
				$data['po_url'] = $po_url;
				$data['supplier_login_allowed'] = $supplier_login_allowed;
				$data['store_name'] = $order_info['store_name'];
				$data['store_address'] = nl2br($store_address);
				$data['supplier_name'] = $order_info['supplier_name'];
				$data['shipping_address'] = $shipping_address;
				$data['comments'] = nl2br($order_info['comments']);
				$data['delivery_charge'] = number_format($order_info['delivery_charge'],2);
				$data['vat'] = number_format($order_info['vat'],2);
				$data['subtotal'] = number_format($order_info['total'],2);
				$data['grand_total'] = number_format($grandTotal,2);
								
				
				if ($this->config->get('config_po_frommail'))
					$fromID = $this->config->get('config_po_frommail');
				else
					$fromID = $this->config->get('config_email');
					
				$mail = new Mail($this->config->get('config_mail'));	
				$mail->setTo($order_info['supplier_email']);
				$mail->setFrom($fromID);
				$ccID = $this->config->get('config_po_ccmail');
				$bccID= $this->config->get('config_po_bccmail');
				if (!empty($ccID))
					$mail->setCc($ccID);
				if (!empty($bccID))
					$mail->setBcc($bccID);				
				$mail->setSender($store_name);
				$mail->setSubject("PO reminder from " .$order_info['store_name']. " - Ref : ".$invoice_no);					
				
				$mail->setHtml($this->load->view('mailtemplates/po_email.html', $data));
				if (!empty($order_info['fileattach']))
					$mail->addAttachment($order_info['fileattach']);
				$mail->send();				
				$this->model_pomgmt_po->setOrderStatusAsReSent($order_id);
				$this->session->data['success'] = "Email(s) sent successfully";
			}
		}
		$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'], 'SSL'));
		$this->getList();	
		//echo ("Email(s) sent successfully");
	}
	 	
	public function email() {
		
		$this->load->language('pomgmt/po');

		$data['title'] = $this->language->get('heading_title');		
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['language'] = $this->language->get('code');

		$data['text_po'] = $this->language->get('text_po');

		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_vat'] = $this->language->get('text_vat');
		$data['text_delivery_charge'] = $this->language->get('text_delivery_charge');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_mpn'] = $this->language->get('column_mpn');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');		
		
		$this->load->model('pomgmt/po');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}


		
		foreach ($orders as $order_id) {
			$order_info = $this->model_pomgmt_po->getOrderForPrint($order_id);
			
			if ($order_info && ($order_info['supplier_email']!="")) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['config_name'];
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_name = $this->config->get('config_name');
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] ."". $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
				);

				$replace = array(
					'firstname' => $order_info['supplier_firstname'],
					'lastname'  => $order_info['supplier_lastname'],
					'company'   => $order_info['supplier_name'],
					'address_1' => $order_info['supplier_address_1'],
					'address_2' => $order_info['supplier_address_2'],
					'city'      => $order_info['supplier_city'],
					'postcode'  => $order_info['supplier_postcode'],
					'zone'      => $order_info['supplier_zone'],
					'zone_code' => $order_info['supplier_zone_code'],
					'country'   => $order_info['supplier_country']				);
				
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';

				$supplier_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				
				if ($order_info['send_to']=='0'){
				
					$find = array(
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}',
						'{telephone}'
					);

					$replace = array(
						'firstname' => $order_info['shipping_firstname'],
						'lastname'  => $order_info['shipping_lastname'],
						'company'   => $order_info['shipping_company'],
						'address_1' => $order_info['shipping_address_1'],
						'address_2' => $order_info['shipping_address_2'],
						'city'      => $order_info['shipping_city'],
						'postcode'  => $order_info['shipping_postcode'],
						'zone'      => $order_info['shipping_zone'],
						'zone_code' => $order_info['shipping_zone_code'],
						'country'   => $order_info['shipping_country'],
						'telephone'   => $order_info['telephone']
					);
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}'."\n\nTelephone:".'{telephone}';;
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				else
				{
					$find = array(
						'{store_name}',
						'{store_address}',
						'{store_telephone}',
						'{country}'
					);

					$replace = array(
						'store_name' => $store_name,
						'store_address' => $store_address,
						'store_telephone'  => $store_telephone
					);
					$format = '{store_name}' . "\n" . '{store_address}' . "\n Tel:" . '{store_telephone}' ;
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				
				$product_data = array();

				$products = $this->model_pomgmt_po->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_pomgmt_po->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);								
					}

					$product_data[] = array(
						'name'     => empty($product['supproducttitle'])?$product['name']:$product['supproducttitle'],
						'description'     => $product['description'],
						'model'    => $product['model'],
						'mpn'	   => isset($product['mpn'])?$product['mpn']:'',
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    =>  number_format($product['price'],2),
						'total'    =>  number_format($product['total'],2)
					);
				}
				
				$voucher_data = array();
				
				
					

				$data['order'] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'subtotal'		   =>  number_format($order_info['total'],2),
					'delivery_charge'  =>  number_format((float)$order_info['delivery_charge'],2),
					'vat'              =>  number_format($order_info['vat'],2),					
					'shipping_address' => $shipping_address,
					'supplier_address' => $supplier_address,
					'poinvoice_ref' => $order_info['poinvoice_ref'],
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'comments'          => nl2br($order_info['comments'])
				);
				
				

				$grandTotal = $order_info['delivery_charge'] +  $order_info['vat'] + $order_info['total'];
				
				if (!empty($order_info['user_id'])) {
					$po_url = HTTP_SERVER.'index.php?route=pomgmt/po/edit&order_id='.$order_id.'&auth_id='.$order_info['auth_token'];
					$supplier_login_allowed = true;
				}
				else {
					$po_url = '';
					$supplier_login_allowed = false;					
				}
				
				$data['po_no'] = $invoice_no;
				$data['po_url'] = $po_url;
				$data['supplier_login_allowed'] = $supplier_login_allowed;
				$data['store_name'] = $order_info['store_name'];
				$data['store_address'] = nl2br($store_address);
				$data['supplier_name'] = $order_info['supplier_name'];
				$data['shipping_address'] = $shipping_address;
				$data['comments'] = nl2br($order_info['comments']);
				$data['delivery_charge'] = number_format($order_info['delivery_charge'],2);
				$data['vat'] = number_format($order_info['vat'],2);
				$data['subtotal'] = number_format($order_info['total'],2);
				$data['grand_total'] = number_format($grandTotal,2);
				
				

					
				if ($this->config->get('config_po_frommail'))
					$fromID = $this->config->get('config_po_frommail');
				else
					$fromID = $this->config->get('config_email');
					
				$mail = new Mail($this->config->get('config_mail'));	
				$mail->setTo($order_info['supplier_email']);
				$mail->setFrom($fromID);
				$ccID = $this->config->get('config_po_ccmail');
				$bccID= $this->config->get('config_po_bccmail');
				if (!empty($ccID))
					$mail->setCc($ccID);
				if (!empty($bccID))
					$mail->setBcc($bccID);				
				$mail->setSender($store_name);
				$mail->setSubject("Purchase Order from " .$order_info['store_name']. " - Ref : ".$invoice_no);					
				
				$mail->setHtml($this->load->view('mailtemplates/po_email.html', $data));
				
				if (!empty($order_info['fileattach']))
					$mail->addAttachment($order_info['fileattach']);
				
				$mail->send();				
				$this->model_pomgmt_po->setOrderStatusAsSent($order_id);
				$this->session->data['success'] = "Email(s) sent successfully";
			}
		}
		$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'], 'SSL'));
		$this->getList();	
		echo ("Email(s) sent successfully");
	}
	  
	public function invoice() {
		$this->load->language('pomgmt/po');

		$data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['language'] = $this->language->get('code');

		$data['text_po'] = $this->language->get('text_po');

		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_comment'] = $this->language->get('text_comment');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_vat'] = $this->language->get('text_vat');
		$data['text_delivery_charge'] = $this->language->get('text_delivery_charge');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_mpn'] = $this->language->get('column_mpn');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');

		$this->load->model('pomgmt/po');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_pomgmt_po->getOrderForPrint($order_id);
			
			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['config_name'];
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_name = $this->config->get('config_name');
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] ."". $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['supplier_firstname'],
					'lastname'  => $order_info['supplier_lastname'],
					'company'   => $order_info['supplier_name'],
					'address_1' => $order_info['supplier_address_1'],
					'address_2' => $order_info['supplier_address_2'],
					'city'      => $order_info['supplier_city'],
					'postcode'  => $order_info['supplier_postcode'],
					'zone'      => $order_info['supplier_zone'],
					'zone_code' => $order_info['supplier_zone_code'],
					'country'   => $order_info['supplier_country']
				);
				
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';

				$supplier_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				
				if ($order_info['send_to']=='0'){
				
					$find = array(
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}',
						'{telephone}'
					);

					$replace = array(
						'firstname' => $order_info['shipping_firstname'],
						'lastname'  => $order_info['shipping_lastname'],
						'company'   => $order_info['shipping_company'],
						'address_1' => $order_info['shipping_address_1'],
						'address_2' => $order_info['shipping_address_2'],
						'city'      => $order_info['shipping_city'],
						'postcode'  => $order_info['shipping_postcode'],
						'zone'      => $order_info['shipping_zone'],
						'zone_code' => $order_info['shipping_zone_code'],
						'country'   => $order_info['shipping_country'],
						'telephone'   => $order_info['telephone']
					);
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}'."\nTelephone:".'{telephone}';
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				else
				{
					$find = array(
						'{store_name}',
						'{store_address}',
						'{store_telephone}',
						'{country}'
					);

					$replace = array(
						'store_name' => $store_name,
						'store_address' => $store_address,
						'store_telephone'  => $store_telephone
					);
					$format = '{store_name}' . "\n" . '{store_address}' . "\n Tel:" . '{store_telephone}' ;
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />',  str_replace($find, $replace, $format));
				}
				
				$product_data = array();

				$products = $this->model_pomgmt_po->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_pomgmt_po->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);								
					}

					$product_data[] = array(
						'name'     => empty($product['supproducttitle'])?$product['name']:$product['supproducttitle'],
						'description'     => $product['description'],
						'model'    => $product['model'],
						'mpn'	   => isset($product['mpn'])?$product['mpn']:'',
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => number_format($product['price'],2),
						'total'    =>  number_format($product['total'],2)
					);
				}
				
				$voucher_data = array();
				
				
					
				$total_data = $this->model_pomgmt_po->getOrderTotals($order_id);

				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'subtotal'		   => $order_info['total'],
					'delivery_charge'  =>  number_format($order_info['delivery_charge'],2),
					'vat'              =>  number_format($order_info['vat'],2),
					'shipping_address' => $shipping_address,
					'poinvoice_ref'  => $order_info['poinvoice_ref'],
					'supplier_address' => $supplier_address,
					'supplier_email' => $order_info['supplier_email'],
					'supplier_telephone' => $order_info['supplier_telephone'],
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => number_format((double)$total_data,2),
					'comments'          => nl2br($order_info['comments'])
				);
				$this->model_pomgmt_po->setOrderStatusAsSent($order_id);
				
			}
			
		}
		
		$this->response->setOutput($this->load->view('mailtemplates/po_print.html', $data));
		
	}
	
	public function insert() {
		$this->load->language('pomgmt/po');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/po');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {		
			$this->model_pomgmt_po->addPO($this->request->post);

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
			
			$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	} 
   
  	public function create() {
		$this->load->language('pomgmt/po');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/po');
		
		unset($this->session->data['cookie']);

		if ($this->validate()) {
			// API
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$curl = curl_init();

				// Set SSL if required
				if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}

					curl_close($curl);
				}
			}
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_pomgmt_po->addSupplier($this->request->post);

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
			
			$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->load->language('pomgmt/po');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/po');
		
		unset($this->session->data['cookie']);

		if ($this->validate()) {
			// API
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$curl = curl_init();

				// Set SSL if required
				if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}

					curl_close($curl);
				}
			}
		}
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_pomgmt_po->editPO($this->request->get['order_id'], $this->request->post);

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
			
			$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
		
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('pomgmt/po');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('pomgmt/po');
			
    	if (isset($this->request->get['order_id']) && $this->validateDelete()) {

			$this->model_pomgmt_po->deletePO($this->request->get['order_id']);
			

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
    	}
		$this->response->redirect($this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	$this->getList();
  	}  
    
  	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_porder_id'])) {
			$filter_porder_id = $this->request->get['filter_porder_id'];
		} else {
			$filter_porder_id = null;
		}
		if (isset($this->request->get['filter_refno'])) {
			$filter_refno = $this->request->get['filter_refno'];
		} else {
			$filter_refno = null;
		}
		if (isset($this->request->get['filter_supplier_id'])) {
			$filter_supplier_id = $this->request->get['filter_supplier_id'];
		} else {
			$filter_supplier_id = null;
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		if (isset($this->request->get['filter_delay'])) {
			$filter_delay = $this->request->get['filter_delay'];
		} else {
			$filter_delay = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'po.order_id';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_porder_id'])) {
			$url .= '&filter_porder_id=' . $this->request->get['filter_porder_id'];
		}
		if (isset($this->request->get['filter_refno'])) {
			$url .= '&filter_refno=' . $this->request->get['filter_refno'];
		}
		if (isset($this->request->get['filter_supplier_id'])) {
			$url .= '&filter_supplier_id=' . $this->request->get['filter_supplier_id'];
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_delay'])) {
			$url .= '&filter_delay=' . $this->request->get['filter_delay'];
		}
		
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['token'] = $this->session->data['token'];
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
	
		
		
		$data['invoice'] = $this->url->link('pomgmt/po/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['email'] = $this->url->link('pomgmt/po/email', 'token=' . $this->session->data['token'], 'SSL');
		$data['create'] = $this->url->link('pomgmt/po/create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['remind'] = $this->url->link('pomgmt/po/sendagain', 'token=' . $this->session->data['token'] , 'SSL');
		$data['delete'] = $this->url->link('pomgmt/po/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
	
		$data['orders'] = array();

		$params = array(
			'filter_order_id'        => $filter_order_id,
			'filter_porder_id'        => $filter_porder_id,
			'filter_refno'        => $filter_refno,
			'filter_supplier_id'        => $filter_supplier_id,
			'filter_order_status_id'        => $filter_order_status_id,
			'filter_total'        => $filter_total,
			'filter_delay'        => $filter_delay,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('pomgmt/supplier');
		$suppliers = $this->model_pomgmt_supplier->getSuppliers();
		$supplierInfo = $this->model_pomgmt_supplier->getSupplierForUser($this->user->getId());
		
		if (!empty($supplierInfo)) {
			$order_total = $this->model_pomgmt_po->getTotalPurchaseOrdersForSupplier($supplierInfo['supplier_id']);				
			$results = $this->model_pomgmt_po->getOrdersForSupplier($supplierInfo['supplier_id'],$params);				
			$data['suppliers'] = null;
		}
		elseif (isset($this->request->get['supplier_id'])) {			
			$order_total = $this->model_pomgmt_po->getTotalPurchaseOrdersForSupplier($this->request->get['supplier_id']);				
			$results = $this->model_pomgmt_po->getOrdersForSupplier($this->request->get['supplier_id'],$params);			
			$data['suppliers'] = null;							
		}
		else {
			$order_total = $this->model_pomgmt_po->getTotalPurchaseOrders($data);	
			$results = $this->model_pomgmt_po->getOrders($params);				
			$data['suppliers'] = $suppliers;
		}
		
		$this->load->model('localisation/order_status');
    	$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    	foreach ($results as $result) {
			
			if ($result['delay'] == 0 )
				$result['delay']='';
			
			$data['orders'][] = array(
				'order_id'        => $result['order_id'],
				'sales_order_id'  => $result['sales_order_id']=="0"?"-NA-":$result['sales_order_id'],
				'poinvoice_ref'   => $result['poinvoice_ref'],
				'supplier'        => $result['name'],
				'total'      	  => $result['total'],
				'status'      	  => $result['status'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'delay'			=> $result['delay'],
				'update'          => $this->url->link('pomgmt/po/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'delete'          => $this->url->link('pomgmt/po/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);
		}	
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirmdelete'] = $this->language->get('text_confirmdelete');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
	
		$data['entry_orderid'] = $this->language->get('entry_orderid');
		$data['entry_salesorderid'] = $this->language->get('entry_salesorderid');
		$data['entry_poinvoice_ref'] = $this->language->get('entry_poinvoice_ref');
		$data['entry_supplier'] = $this->language->get('entry_supplier');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_delay'] = $this->language->get('entry_delay');
		
		$data['column_orderid'] = $this->language->get('column_orderid');
		$data['column_salesorderid'] = $this->language->get('column_salesorderid');
		$data['column_poinvoice_ref'] = $this->language->get('column_poinvoice_ref');
		$data['column_supplier'] = $this->language->get('column_supplier');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_status'] = $this->language->get('column_status');		
		$data['column_action'] = $this->language->get('column_action');		
		$data['column_delay'] = $this->language->get('column_delay');
		
		$data['button_remind'] = $this->language->get('button_remind');
		$data['button_create'] = $this->language->get('button_create');
		$data['button_printpo'] = $this->language->get('button_printpo');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_email'] = $this->language->get('button_email');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_continue'] = $this->language->get('button_continue');
		
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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_porder_id'])) {
			$url .= '&filter_porder_id=' . $this->request->get['filter_porder_id'];
		}
		if (isset($this->request->get['filter_refno'])) {
			$url .= '&filter_refno=' . $this->request->get['filter_refno'];
		}
		if (isset($this->request->get['filter_supplier_id'])) {
			$url .= '&filter_supplier_id=' . $this->request->get['filter_supplier_id'];
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_delay'])) {
			$url .= '&filter_delay=' . $this->request->get['filter_delay'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['supplier_id'])) {
			$url .= "&supplier_id=" . $this->request->get['supplier_id'];
		}
		
		$data['sort_orderid'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=po.order_id' . $url, 'SSL');
		$data['sort_salesorderid'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=po.sales_order_id' . $url, 'SSL');
		$data['sort_poinvoice_ref'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=po.poinvoice_ref' . $url, 'SSL');
		$data['sort_supplier'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=total' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_delay'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . '&sort=delay' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_porder_id'])) {
			$url .= '&filter_porder_id=' . $this->request->get['filter_porder_id'];
		}
		if (isset($this->request->get['filter_refno'])) {
			$url .= '&filter_refno=' . $this->request->get['filter_refno'];
		}
		if (isset($this->request->get['filter_supplier_id'])) {
			$url .= '&filter_supplier_id=' . $this->request->get['filter_supplier_id'];
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_delay'])) {
			$url .= '&filter_delay=' . $this->request->get['filter_delay'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['supplier_id'])) {
			$url .= "&supplier_id=" . $this->request->get['supplier_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		
		$pagination->url = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));	
		$data['pagination'] = $pagination->render();
		
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_porder_id'] = $filter_porder_id;
		$data['filter_refno'] = $filter_refno;
		$data['filter_supplier_id'] = $filter_supplier_id;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_total'] = $filter_total;
		$data['filter_delay'] = $filter_delay;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

	//	$this->template = 'pomgmt/po_list.tpl';
		//$this->children = array(
		//	'common/header',
		//	'common/footer',
		//);
				
		//$this->response->setOutput($this->render());
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pomgmt/po_list.tpl', $data));
	}
	  
	public function make_it_shorter($ret)
	{
		$maxlength=50;
		$thelength=strlen($ret);
		$prefix='';
		$tfrom='mid';
		if($thelength>$maxlength&&$prefix)
		{
				  $ret=str_replace("http://www.","",$ret);
				  $ret=str_replace("http://","",$ret);
				  $thelength=strlen($ret);
		}
			if($thelength>$maxlength)
			{
					if($tfrom=='mid')
					{
							$delchars=$thelength-$maxlength;
							$firsthalf=round($thelength/2-$delchars/2);
							$sechalf=round($thelength/2+$delchars/2);
							$ret=substr($ret,0,$firsthalf) . "..." . substr($ret,$sechalf,$thelength);
					}
					else{
						$ret=substr($ret,0) ."...";
					}
			}
		return $ret;
	}
  	public function getForm() {
		$this->load->model('pomgmt/po');
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_po'] = $this->language->get('text_po');
		$data['text_updatestock'] = $this->language->get('text_updatestock');  
		$data['text_no_results'] = $this->language->get('text_no_results');  
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_vat'] = $this->language->get('text_vat');
		$data['text_delivery_charge'] = $this->language->get('text_delivery_charge');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_company'] = $this->language->get('text_company');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_supplier'] = $this->language->get('entry_supplier');
		$data['entry_orderid'] = $this->language->get('entry_orderid');
		$data['entry_salesorderid'] = $this->language->get('entry_salesorderid');
		$data['entry_orderdate'] = $this->language->get('entry_orderdate');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_exrate'] = $this->language->get('entry_exrate');
		$data['entry_orderstatus'] = $this->language->get('entry_orderstatus');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_tracking_id_s2s'] = $this->language->get('entry_tracking_id_s2s');
		$data['entry_tracking_id_s2c'] = $this->language->get('entry_tracking_id_s2c');
		$data['entry_delivery_time_s2s'] = $this->language->get('entry_delivery_time_s2s');
		$data['entry_delivery_time_s2c'] = $this->language->get('entry_delivery_time_s2c');
		$data['entry_poinvoice_ref'] = $this->language->get('entry_poinvoice_ref');
		$data['entry_poinvoice_value'] = $this->language->get('entry_poinvoice_value');
		$data['entry_comments'] = $this->language->get('entry_comments');
		$data['entry_sendto'] = $this->language->get('entry_sendto');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_supproducturl'] = $this->language->get('column_supproducturl');
	
		
		$data['column_model'] = $this->language->get('column_model');
		$data['column_mpn'] = $this->language->get('column_mpn');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_back'] = $this->language->get('button_back');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_add_voucher'] = $this->language->get('button_add_voucher');
		$data['button_update_total'] = $this->language->get('button_update_total');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_email'] = $this->language->get('button_email');
		$data['button_autofill'] = $this->language->get('button_autofill');
		$data['button_addstock'] = $this->language->get('button_addstock');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['tab_order'] = $this->language->get('tab_order');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_total'] = $this->language->get('tab_total');
	
		$data['help_unitcost'] = $this->language->get('help_unitcost');
		$data['token'] = $this->session->data['token'];
		
		
		
		if (isset($this->request->get['order_id'])) {
			$data['order_id'] = $this->request->get['order_id'];
		} else {
			$data['order_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['supplier'])) {
			$data['error_supplier'] = $this->error['supplier'];
		} else {
			$data['error_supplier'] = '';
		} 		
		
				
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			//'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
			//'separator' => ' :: '
		);

		if (!isset($this->request->get['order_id'])) {
			$data['action'] = $this->url->link('pomgmt/po/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('pomgmt/po/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('pomgmt/po', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if (isset($this->request->get['order_id'])) {
			$data['email'] = $this->url->link('pomgmt/po/email', 'token=' . $this->session->data['token']. '&order_id=' . $this->request->get['order_id'] , 'SSL');
		}
    	if (isset($this->request->get['order_id']) ) {
      		$order_info = $this->model_pomgmt_po->getOrder($this->request->get['order_id']);
    	}
    	if (isset($this->request->post['store_id'])) {
      		$data['store_id'] = $this->request->post['store_id'];
    	} elseif (!empty($order_info)) { 
			$data['store_id'] = $order_info['store_id'];
		} else {
      		$data['store_id'] = '';
    	}
		
		$data['allowsave'] = true;
		if (isset($order_info)) {
			if ($order_info['order_status_id']==$this->config->get('config_po_lockstatus'))
				$data['allowsave'] = false;
		}
		
		$this->load->model('pomgmt/supplier');
		$data['suppliers'] = $this->model_pomgmt_supplier->getSuppliers();

		$loggedinSupplier = $this->model_pomgmt_supplier->getSupplierForUser($this->user->getId());
		if (!empty($loggedinSupplier))	
			$data['issupplier'] = true;
		else
			$data['issupplier'] = false;
		
		$this->load->model('localisation/tax_rate');
				
		if (isset($this->request->post['tax_rate'])) {
			$data['tax_rate'] = $this->request->post['tax_rate'];
		} elseif (!empty($order_info)) {
			$taxInfo = $this->model_localisation_tax_rate->getTaxRate($order_info['tax_rate_id']); 
			if (!empty($order_info['tax_rate_id']))
				$data['tax_rate'] = $taxInfo['rate'];
			else
				$data['tax_rate'] = '0';
		} else {
			$data['tax_rate'] = '0';
		}
		
		
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		$data['store_url'] = HTTP_CATALOG;
		
		if (isset($this->request->post['currency'])) {
			$data['currency'] = $this->request->post['currency'];
		} elseif (!empty($order_info)) {
			$data['currency'] = $order_info['currency'];
		} else {
			$data['currency'] = '';
		}
			
		if (isset($this->request->post['sales_order_id'])) {
			$data['sales_order_id'] = $this->request->post['sales_order_id'];
		} elseif (!empty($order_info)) {
			$data['sales_order_id'] = $order_info['sales_order_id'];
		} else {
			$data['sales_order_id'] = '';
		}
		
		if (isset($this->request->post['updatestock'])) {
			$data['updatestock'] = $this->request->post['updatestock'];
		} elseif (!empty($order_info)) {
			$data['updatestock'] = $order_info['updatestock'];
		} else {
			$data['updatestock'] = 0;
		}
			
		if (isset($this->request->post['supplier_id'])) {
			$data['supplier_id'] = $this->request->post['supplier_id'];
		} elseif (!empty($order_info)) {
			$data['supplier_id'] = $order_info['supplier_id'];
		} else {
			$data['supplier_id'] = '';
		}
		
		if (isset($this->request->post['supplier'])) {
			$data['supplier'] = $this->request->post['supplier'];
		} elseif (!empty($order_info)) {
			$data['supplier'] = $order_info['supplier'];
		} else {
			$data['supplier'] = '';
		}

		if (isset($this->request->post['order_status_id'])) {
      		$data['order_status_id'] = $this->request->post['order_status_id'];
    	} elseif (!empty($order_info)) { 
			$data['order_status_id'] = $order_info['order_status_id'];
		} else {
      		$data['order_status_id'] = '';
    	}
			
		if (isset($this->request->post['comments'])) {
      		$data['comments'] = $this->request->post['comments'];
    	} elseif (isset($order_info)) {
			$data['comments'] = $order_info['comments'];
		} else {
      		$data['comments'] = '';
    	}
		if (isset($this->request->post['order_date'])) {
      		$data['order_date'] = $this->request->post['order_date'];
    	} elseif (!empty($order_info)) { 
			$data['order_date'] = $order_info['order_date'];
		} else {
      		$data['order_date'] = date('Y-m-d H:i:s');
    	}
		
		if (isset($this->request->post['currency_value'])) {
      		$data['currency_value'] = $this->request->post['currency_value'];
    	} elseif (!empty($order_info)) { 
			$data['currency_value'] = $order_info['currency_value'];
		} else {
      		$data['currency_value'] = '';
    	}
		
		if (isset($this->request->post['order_status'])) {
      		$data['order_status'] = $this->request->post['order_status'];
    	} elseif (!empty($order_info)) { 
			$data['order_status'] = $order_info['order_status'];
		} else {
      		$data['order_status'] = '';
    	}
		
		if (isset($this->request->post['order_status_id'])) {
      		$data['order_status_id'] = $this->request->post['order_status_id'];
    	} elseif (!empty($order_info)) { 
			$data['order_status_id'] = $order_info['order_status_id'];
		} else {
      		$data['order_status_id'] = '';
    	}
		
		if (isset($this->request->post['poinvoice_ref'])) {
      		$data['poinvoice_ref'] = $this->request->post['poinvoice_ref'];
    	} elseif (!empty($order_info)) { 
			$data['poinvoice_ref'] = $order_info['poinvoice_ref'];
		} else {
      		$data['poinvoice_ref'] = '';
    	}
		
	if (isset($this->request->post['inv_value'])) {
      		$data['inv_value'] = $this->request->post['inv_value'];
    	} elseif (!empty($order_info)) { 
			$data['inv_value'] = $order_info['inv_value'];
		} else {
      		$data['inv_value'] = '';
    	}
		
		if (isset($this->request->post['tracking_id_s2s'])) {
      		$data['tracking_id_s2s'] = $this->request->post['tracking_id_s2s'];
    	} elseif (!empty($order_info)) { 
			$data['tracking_id_s2s'] = $order_info['tracking_id_s2s'];
		} else {
      		$data['tracking_id_s2s'] = '';
    	}
		if (isset($this->request->post['tracking_id_s2c'])) {
      		$data['tracking_id_s2c'] = $this->request->post['tracking_id_s2c'];
    	} elseif (!empty($order_info)) { 
			$data['tracking_id_s2c'] = $order_info['tracking_id_s2c'];
		} else {
      		$data['tracking_id_s2c'] = '';
    	}
		if (isset($this->request->post['delivery_time_s2s'])) {
      		$data['delivery_time_s2s'] = $this->request->post['delivery_time_s2s'];
    	} elseif (!empty($order_info)) { 
			$data['delivery_time_s2s'] = $order_info['delivery_time_s2s'];
		} else {
      		$data['delivery_time_s2s'] = '';
    	}
		if (isset($this->request->post['delivery_time_s2c'])) {
      		$data['delivery_time_s2c'] = $this->request->post['delivery_time_s2c'];
    	} elseif (!empty($order_info)) { 
			$data['delivery_time_s2c'] = $order_info['delivery_time_s2c'];
		} else {
      		$data['delivery_time_s2c'] = '';
    	}
		if (isset($this->request->post['send_to'])) {
      		$data['send_to'] = $this->request->post['send_to'];
    	} elseif (!empty($order_info)) { 
			$data['send_to'] = $order_info['send_to'];
		} else {
      		$data['send_to'] = '';
    	}
		if (isset($this->request->post['delivery_charge'])) {
      		$data['delivery_charge'] = $this->request->post['delivery_charge'];
    	} elseif (!empty($order_info)) { 
			$data['delivery_charge'] = $order_info['delivery_charge'];
		} else {
      		$data['delivery_charge'] = '';
    	}
		
		
		if (isset($this->request->post['currency_value'])) {
      		$data['currency_value'] = $this->request->post['currency_value'];
    	} elseif (!empty($order_info)) { 
			$data['currency_value'] = $order_info['currency_value'];
		} else {
      		$data['currency_value'] = '';
    	}
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
			

		if (isset($this->request->post['order_product'])) {
			$order_products = $this->request->post['order_product'];
		} elseif (isset($this->request->get['order_id'])) {
			$order_products = $this->model_pomgmt_po->getOrderProducts($this->request->get['order_id']);
		} else {
			$order_products = array();
		}

		
		
		if (isset($this->request->get['order_id'])) {
			$data['order_data'] = $order_info;
		}
		$this->load->model('catalog/product');
		
		$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$data['order_products'] = array();		
		foreach ($order_products as $order_product) {
			if (isset($order_product['order_option'])) {
				$order_option = $order_product['order_option'];
			} elseif (isset($this->request->get['order_id'])) {
				$order_option = $this->model_pomgmt_po->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
			} else {
				$order_option = array();
			}

			$order_download = array();
			
			if (isset( $order_product['sale_order_product_id']))
				$sale_prod_id = $order_product['sale_order_product_id'];
			else
				$sale_prod_id = 0;
			if (!isset($order_product['supproducturl']))
				$order_product['supproducturl'] = '';
			$data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'sale_order_product_id' => $sale_prod_id,
				'supproductshorturl' => $this->make_it_shorter($order_product['supproducturl']),
				'supproducturl' => $order_product['supproducturl'],
				'product_id'       => isset($order_product['product_id'])?$order_product['product_id']:'',
				'name'             => $order_product['name'],
				'model'            => $order_product['model'],
				'mpn'            => $order_product['mpn'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'],
				'total'            => $order_product['total'],
				'addstock' 	=> $order_product['stock_added']?'': $this->url->link('pomgmt/po/addstock', 'token=' . $this->session->data['token'] . "&order_id=".$this->request->get['order_id'] . "&order_product_id=".$order_product['order_product_id'], 'SSL'),
			);
		}
		
       
						
		if (isset($this->request->post['order_total'])) {
      		$data['order_totals'] = $this->request->post['order_total'];
    	} elseif (isset($this->request->get['order_id'])) { 
			$data['order_totals'] = $this->model_pomgmt_po->getOrderTotals($this->request->get['order_id']);
		} else {
      		$data['order_totals'] = array();
    	}	
		$this->load->model('pomgmt/supplier');
		$currentSupplier = $this->model_pomgmt_supplier->getSupplierForUser($this->user->getId());
		if (!empty($currentSupplier)) {
			if ($currentSupplier['supplier_id']!= $order_info['supplier_id']) {
				$this->error['warning'] = $this->language->get('error_permission');
				$this->getList();
				return false;
			}			
		}
		//$this->template = 'pomgmt/po_form.tpl';
		//$this->children = array(
		//	'common/header',
		//	'common/footer'
		//);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pomgmt/po_form.tpl', $data));
  	}
	
  	private function validateForm() {		
		if (empty($this->request->post['order_product'])) {
      		$this->error['warning'] = $this->language->get('error_products');
    	}
		if (isset($this->request->post['supplier_id']))
			if ($this->request->post['supplier_id'] == '' || $this->request->post['supplier_id'] == '0'  ) {
				$this->error['supplier'] = $this->language->get('error_supplier');
    	}
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}

  	}    
	
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'pomgmt/po')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		// $this->load->model('catalog/product');

		// foreach ($this->request->post['selected'] as $supplier_id) {
  			// $product_total = $this->model_catalog_product->getTotalProductsBySupplierId($supplier_id);
    
			// if ($product_total) {
	  			// $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			// }	
	  	// } 
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	}

	public function api() {
		$this->load->language('pomgmt/po');
		$json = "";
		if ($this->validate()) {			
			// Store
			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$url = $store_info['ssl'];
			} else {
				$url = HTTPS_CATALOG;
			}

			if (isset($this->session->data['cookie']) && isset($this->request->get['api'])) {
				// Include any URL perameters
				$url_data = array();

				foreach($this->request->get as $key => $value) {
					if ($key != 'route' && $key != 'token' && $key != 'store_id') {
						$url_data[$key] = $value;
					}
				}

				$curl = curl_init();

				// Set SSL if required
				if (substr($url, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=' . $this->request->get['api'] . ($url_data ? '&' . http_build_query($url_data) : ''));

				if ($this->request->post) {
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
				}

				curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

				$json = curl_exec($curl);

				curl_close($curl);
			}
		} else {
			$response = array();

			$response['error'] = $this->error;

			$json = json_encode($response);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
	}
	
}
?>