<?php
class ControllerApiPO extends Controller {
	public function add() {
		$this->load->language('api/po');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			// Cart
			if ((!$this->cart->hasProducts())) {
				$json['error'] = $this->language->get('error_stock');
			}			

			if (!$json) {
				$order_data = array();

				// PO Details
				$order_data['poinvoice_ref'] = $this->request->post['poinvoice_ref'];
				$order_data['inv_value'] = $this->request->post['inv_value'];
				$order_data['store_id'] =  $this->request->post['store_id'];
				$order_data['supplier_id'] =  $this->request->post['supplier_id'];
				$order_data['currency_id'] =  $this->request->post['currency_id'];
				$order_data['order_status_id'] =  $this->request->post['order_status_id'];
				$order_data['tracking_id_s2s'] =  $this->request->post['tracking_id_s2s'];
				$order_data['tracking_id_s2c'] =  $this->request->post['tracking_id_s2c'];
				$order_data['delivery_time_s2s'] =  $this->request->post['delivery_time_s2s'];
				$order_data['delivery_time_s2c'] =  $this->request->post['delivery_time_s2c'];
				$order_data['delivery_charge'] =  $this->request->post['delivery_charge'];
				$order_data['order_sendto_id'] =  $this->request->post['order_sendto_id'];
				$order_data['comments'] =  $this->request->post['comments'];
				
				
				
				
				// Products
				$order_data['products'] = array();

				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(							
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'type'                    => $option['type']
						);
					}

					$order_data['order_product'][] = array(
						'order_product_id' => '',
						'sale_order_product_id' => $product['sale_order_product_id'],
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'order_option'     => $option_data,
						'mpn'   	 => '',
						'quantity'   => $product['quantity'],
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'     => $product['reward']
					);
				}

				

				// Order Totals
				//TODO
				$order_data['summary_delcharge'] =  $this->request->post['delivery_charge'];
				$order_data['summary_producttotal'] =  $this->request->get['subtotal'];
				$order_data['summary_vat'] =  $this->request->get['vat'];
				
				$this->load->model('pomgmt/po');

				$json['order_id'] = $this->model_pomgmt_po->addPO($order_data);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function edit() {
		$this->load->language('api/po');
		$this->load->model('pomgmt/po');
		
		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			$order_info = $this->model_pomgmt_po->getOrder($order_id);
			if ($order_info) {
				
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);

				// Cart
				if ((!$this->cart->hasProducts())) {
					$json['error'] = $this->language->get('error_stock');
				}			

				if (!$json) {
					$order_data = array();

					// PO Details
					$order_data['poinvoice_ref'] = $this->request->post['poinvoice_ref'];
					$order_data['inv_value'] = $this->request->post['inv_value'];
					$order_data['store_id'] =  $this->request->post['store_id'];
					$order_data['supplier_id'] =  $this->request->post['supplier_id'];
					$order_data['currency_id'] =  $this->request->post['currency_id'];
					$order_data['order_status_id'] =  $this->request->post['order_status_id'];
					$order_data['tracking_id_s2s'] =  $this->request->post['tracking_id_s2s'];
					$order_data['tracking_id_s2c'] =  $this->request->post['tracking_id_s2c'];
					$order_data['delivery_time_s2s'] =  $this->request->post['delivery_time_s2s'];
					$order_data['delivery_time_s2c'] =  $this->request->post['delivery_time_s2c'];
					$order_data['delivery_charge'] =  $this->request->post['delivery_charge'];
					$order_data['order_sendto_id'] =  $this->request->post['order_sendto_id'];
					$order_data['comments'] =  $this->request->post['comments'];
					
					
					
					
					// Products
					$order_data['products'] = array();

					foreach ($this->cart->getProducts() as $product) {
						$option_data = array();

						foreach ($product['option'] as $option) {
							$option_data[] = array(							
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}

						$order_data['order_product'][] = array(
							'order_product_id' => '',
							'sale_order_product_id' => $product['sale_order_product_id'],
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'order_option'     => $option_data,
							'mpn'   	 => '',
							'quantity'   => $product['quantity'],
							'price'      => $product['price'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					

					// Order Totals
					//TODO
					$order_data['summary_delcharge'] =  $this->request->post['delivery_charge'];
					$order_data['summary_producttotal'] =  $this->request->get['subtotal'];
					$order_data['summary_vat'] =  $this->request->get['vat'];
					
					

					$json['order_id'] = $this->model_pomgmt_po->editPO($order_id, $order_data);

					$json['success'] = $this->language->get('text_success');
				}				
			}
			else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function index() {
		$this->load->language('api/po');

		// Delete past customer in case there is an error
		unset($this->session->data['po']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'supplier_id',
				'poinvoice_ref',
				'store_id',
				'currency_id',
				'order_status_id',
				'tracking_id_s2s',
				'tracking_id_s2c',
				'delivery_time_s2s',
				'delivery_time_s2c',
				'delivery_charge',
				'order_sendto_id',
				'comments'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if (isset($this->request->post['supplier_id'])) {
				if ($this->request->post['supplier_id'] == '' || $this->request->post['supplier_id'] == '0'  ) {
					$json['error']['supplier'] = $this->language->get('error_supplier');
				}
			}

			if (!$json) {
				$this->session->data['po'] = array(
					'supplier_id'       => $this->request->post['supplier_id'],
					'poinvoice_ref'         => $this->request->post['poinvoice_ref'],
					'store_id'          => $this->request->post['store_id'],
					'currency_id'             => $this->request->post['currency_id'],
					'order_status_id'         => $this->request->post['order_status_id'],
					'tracking_id_s2s'               => $this->request->post['tracking_id_s2s'],
					'delivery_time_s2c'               => $this->request->post['delivery_time_s2c'],
					'delivery_charge'               => $this->request->post['delivery_charge'],
					'order_sendto_id'               => $this->request->post['order_sendto_id'],
					'comments'               => $this->request->post['comments']
				);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
		
	public function products() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}

			// Products
			$json['products'] = array();

			$products = $this->cart->getProducts();			
			$sub_total = 0;
			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$json['products'][] = array(
					'key'        => $product['key'],
					'product_id' => $product['product_id'],
					'sale_order_product_id' => $product['sale_order_product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'stock'      => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'shipping'   => $product['shipping'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'reward'     => $product['reward']
				);
				$sub_total =  $sub_total + ($product['price'] * $product['quantity']);
			}

			// Voucher
			$json['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$json['vouchers'][] = array(
						'code'             => $voucher['code'],
						'description'      => $voucher['description'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $this->currency->format($voucher['amount'])
					);
				}
			}

			// Totals
			
			$tax_rate = 0;
			if (isset($this->request->get['delcharge']))
				$delivery_charge = $this->request->get['delcharge'];
			else
				$delivery_charge = 0;
			
			if (isset($this->request->get['supplier_id'])) {
				$this->load->model('pomgmt/po');
				$results = $this->model_pomgmt_po->getSupplier($this->request->get['supplier_id']);						
				if ($results) {
					if (!isset($this->request->get['delcharge']))
						$delivery_charge = $results ['dropship_fee'];
					$tax_rate = $results ['rate'];
				}
			}			
			$TotalwithoutTax = $sub_total + $delivery_charge;
			$vat = $TotalwithoutTax* ( $tax_rate /100  );
			
			$json['totals'] = array();
			$json['totals'][]  = array(
					'title' => 'Sub-Total',
					'text'  => $this->currency->format($sub_total),
					'value' => $sub_total
				);
			$json['totals'][]  = array(
					'title' => 'Delivery Charges',
					'text'  => $this->currency->format($delivery_charge),
					'value' => $delivery_charge
				);
			$json['totals'][]  = array(
					'title' => 'VAT',
					'text'  => $this->currency->format($vat),
					'value' => $vat
				);
			$json['totals'][]  = array(
					'title' => 'Total',
					'text'  => $this->currency->format($TotalwithoutTax + $vat),
					'value'  => $TotalwithoutTax + $vat
				);
			
			/* foreach ($total_data as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'])
				);
			} */
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}