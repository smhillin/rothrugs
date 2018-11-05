<?php
class ControllerPomgmtPo extends Controller {
	public function generatepo($order_id) {
		if ($order_id) {
			$this->load->model('pomgmt/po');
			$this->session->data['order_product_ids'] = array();
			$auto_generate = $this->config->get('config_po_auto');
			$auto_generate_status = $this->config->get('config_po_autostatus');				
			if ($auto_generate == 1) {
				$order_status_id = $this->model_pomgmt_po->getOrderStatus($order_id);
				//print_r($order_status_id);die("Done");
				if ($order_status_id) {
					if ($auto_generate_status == (int)$order_status_id) {
						$po_list = $this->model_pomgmt_po->POFromOrder($order_id);
						if ($this->config->get('config_poemail_auto')==1)
							$this->email($po_list);
					}
				}
			}
		}
	}
	
	
	public function email($orders) {
				
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
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
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
						'name'     => $product['name'],
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
					$po_url = HTTP_SERVER.'index.php?route=pomgmt/po/update&order_id='.$order_id.'&auth_id='.$order_info['auth_token'];
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
				
				

				$this->template = 'default/template/mailtemplates/po_email.html';	
				
				if ($this->config->get('config_po_frommail'))
					$fromID = $this->config->get('config_po_frommail');
				else
					$fromID = $this->config->get('config_email');
					
				$mail = new Mail();	
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				//$mail->setTo("rameshk74@gmail.com");
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
				
				$mail->setHtml($this->render());
				
				if (!empty($order_info['fileattach']))
					$mail->addAttachment($order_info['fileattach']);
			
				//echo($this->render());
				$mail->send();				
				$this->model_pomgmt_po->setOrderStatusAsSent($order_id);
				$this->session->data['success'] = "Email(s) sent successfully";
			}
		}
	}
}