<?php
class ModelPomgmtPo extends Model {
	public function getLowstockItems() {
		$query = $this->db->query("SELECT *, reorder-quantity as reorderqty FROM " . DB_PREFIX . "product p " 
		. " INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id and pd.language_id='".(int)$this->config->get('config_language_id'). "' " 
		. " WHERE quantity < reorder");
		return $query->rows;
	}
	
	public function addPO($data) {
		if(isset($data['updatestock'])) 
			$updatestock = 1;			
		else
			$updatestock = 0;
		
		
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		$invoice_prefix = $this->config->get('config_po_prefix');
		
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrency($data['currency_id']);
		//print_r($currency_info['value']);die('');
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order SET store_id = '" 
			. (int)$data['store_id'] 
			. "', order_status_id = '" .(int)$data['order_status_id']."',"
			." invoice_prefix='".$this->db->escape($invoice_prefix) . "',"
			." supplier_id = '".(int)$data['supplier_id']."',"
			." currency_id = '".(int)$data['currency_id']."',"
			." currency_code = '".$currency_info['code']."',"
			." currency_value = '".(float)$currency_info['value']."',"
			." store_name = '".$this->db->escape($store_name)."',"
			." store_url = '".$store_url."',"
			." tracking_id_s2s = '" . $this->db->escape($data['tracking_id_s2s']) . "',"
			." tracking_id_s2c = '" . $this->db->escape($data['tracking_id_s2c']) . "',"
			." delivery_time_s2s = '" . $this->db->escape($data['delivery_time_s2s']) . "',"
			." delivery_time_s2c = '" . $this->db->escape($data['delivery_time_s2c']) . "',"
			." poinvoice_ref = '" . $this->db->escape($data['poinvoice_ref']) . "',"
			." inv_value = '" . $this->db->escape($data['inv_value']) . "',"
			." comments = '" . $this->db->escape($data['comments']) . "',"
			." vat = '" . (float) $this->db->escape($data['summary_vat']) . "',"
			." total = '" . (float) $this->db->escape($data['summary_producttotal']) . "',"
			." delivery_charge = '" . (float) $this->db->escape($data['summary_delcharge']) . "',"
			." date_added = NOW(),"
			." auth_token = '".md5(mt_rand())."',"
			." updatestock = '".$updatestock."',"
			." send_to = '1'" );
		
		$order_id = $this->db->getLastId();
		
		$this->load->model('sale/order');
		$this->model_sale_order->createPONo((int)$order_id);

		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] 
				. "',sale_order_product_id = '0', name='".$this->db->escape($product['name'])
				. "',model='".$this->db->escape($product['model'])."'," 
				. "quantity ='". (int)$product['quantity'] . "', price='".(float)$product['price']."', total='".(float)$product['total']."'");
				
				$order_product_id = $this->db->getLastId();
				if (isset($product['order_option'])) {
					foreach ($product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						if ($updatestock==1) {
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = quantity + ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."' AND product_option_id='".(int)$order_option['product_option_id']."' AND product_option_value_id='".(int)$order_option['product_option_value_id']."'" );
						}
					}
				}
				if ($updatestock==1) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity + ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."'" );
				}
			}
		}	
	}
	
	public function editPO($order_id, $data) {
		if(isset($data['updatestock'])) 
			$updatestock = 1;			
		else
			$updatestock = 0;
		
		if ($updatestock==1) {
			$result = $this->db->query("SELECT product_id,quantity FROM " . DB_PREFIX . "purchase_order_product WHERE order_id='".(int)$order_id."'" );
			$products = $result->rows;
			foreach ($products as $product) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity - ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."'" );
			}
		}

		if(isset($data['order_sendto_id']))
			$sendto = (int)($data['order_sendto_id']);
		else
			$sendto = 1;
		
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET store_id = '" 
			. (int)$data['store_id'] 
			. "', order_status_id = '" .(int)$data['order_status_id']."',"
			." tracking_id_s2s = '" . $this->db->escape($data['tracking_id_s2s']) . "',"
			." tracking_id_s2c = '" . $this->db->escape($data['tracking_id_s2c']) . "',"
			." delivery_time_s2s = '" . $this->db->escape($data['delivery_time_s2s']) . "',"
			." delivery_time_s2c = '" . $this->db->escape($data['delivery_time_s2c']) . "',"
			." poinvoice_ref = '" . $this->db->escape($data['poinvoice_ref']) . "',"
			." inv_value = '" . $this->db->escape($data['inv_value']) . "',"
			." comments = '" . $this->db->escape($data['comments']) . "',"
			." vat = '" . (float) $this->db->escape($data['summary_vat']) . "',"
			." total = '" . (float) $this->db->escape($data['summary_producttotal']) . "',"
			." delivery_charge = '" . (float) $this->db->escape($data['summary_delcharge']) . "',"
			." updatestock = '".$updatestock."',"
			." send_to = '" . $sendto . "' WHERE order_id='".(int)$order_id."'" );
		
		if ($updatestock==1) {
			$result = $this->db->query("SELECT pop.product_id,poo.product_option_value_id,poo.product_option_id,quantity FROM " . DB_PREFIX . "purchase_order_option poo, " . DB_PREFIX . "purchase_order_product pop WHERE pop.order_id='".(int)$order_id."' AND pop.order_product_id=poo.order_product_id" );
			$products = $result->rows;
			foreach ($products as $product) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = quantity - ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."' AND product_option_id='".(int)$product['product_option_id']."' AND product_option_value_id='".(int)$product['product_option_value_id']."'" );
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_option WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $product) {				
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] 
				. "',sale_order_product_id = '". (int)$product['sale_order_product_id']."', name='".$this->db->escape($product['name'])
				. "',model='".$this->db->escape($product['model'])."'," 
				. "quantity ='". (int)$product['quantity'] . "', price='".(float)$product['price']."', total='".(float)$product['total']."'");
				
				$order_product_id = $this->db->getLastId();
				if (isset($product['order_option'])) {
					foreach ($product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						if ($updatestock==1) {
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = quantity + ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."' AND product_option_id='".(int)$order_option['product_option_id']."' AND product_option_value_id='".(int)$order_option['product_option_value_id']."'" );
						}
					}
				}
				
				if ($updatestock==1) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity + ". $product['quantity'] . " WHERE product_id='".(int)$product['product_id']."'" );
				}				
			}
		}
	}
	
	public function getUserUsingAuthToken($auth_token) {
		$query = $this->db->query("SELECT s.user_id FROM " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id WHERE auth_token='".$auth_token."'");

		return $query->row;
	}
	
	public function setOrderStatusAsSent($order_id) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE name = 'PO Sent'");
		$statusInfo = $query->row;
		
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET order_status_id = '" . $statusInfo['order_status_id'] . "' WHERE order_id='".(int)$order_id."'" );
		
	}
	
	public function setOrderStatusAs($order_id,$status_text) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE name = '".$status_text."'");
		$statusInfo = $query->row;
		
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET order_status_id = '" . $statusInfo['order_status_id'] . "' WHERE order_id='".(int)$order_id."'" );
		
	}
	
	
	public function setOrderStatusAsReSent($order_id) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE name = 'PO Reminder Sent'");
		$statusInfo = $query->row;
		
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET order_status_id = '" . $statusInfo['order_status_id'] . "' WHERE order_id='".(int)$order_id."'" );
		
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT pop.*,p.supproducturl,p.supproducttitle,pd.description,p.mpn,p.ean,p.location FROM " . DB_PREFIX . "purchase_order_product pop INNER JOIN " . DB_PREFIX . "product p ON p.product_id = pop.product_id ". 
		" INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id and pd.language_id='".(int)$this->config->get('config_language_id'). "' " .
		" WHERE order_id = '" . (int)$order_id . "'");		
		return $query->rows;
	}
	
	public function getPOFromOrders($orders) {		
		$query = $this->db->query("SELECT distinct order_id FROM " . DB_PREFIX . "purchase_order pop WHERE sales_order_id IN (" . implode(",",$orders) . ")");
		$ret_order = array();
		foreach ($query->rows as $row) {
			$ret_order[] = $row['order_id'];
		}
		return $ret_order;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}
	
	public function getOrderDownloads($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function deletePO($order_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "order_product SET po_product_id=0 WHERE po_product_id IN (SELECT order_product_id FROM " 
		. DB_PREFIX . "purchase_order_product where order_id='" . (int)$order_id . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_option WHERE order_id = '" . (int)$order_id . "'");
	}	
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT  po.*,s.name as supplier,s.tax_rate_id,c.title as currency,os.name as order_status FROM " . DB_PREFIX . "purchase_order po INNER JOIN " 
		. DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " 
		. DB_PREFIX . "currency c ON c.currency_id = s.currency_id INNER JOIN " 
		. DB_PREFIX . "order_status os ON os.order_status_id=po.order_status_id WHERE po.order_id = '" . (int)$order_id . "' AND os.language_id='".(int)$this->config->get('config_language_id')."'");

		if ($order_query->num_rows) {
			return array(
				'order_id'                => $order_query->row['order_id'],
				'updatestock'                => $order_query->row['updatestock'],
				'sales_order_id'          => $order_query->row['sales_order_id'],
				'order_date'              => $order_query->row['date_added'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'supplier_id'             => $order_query->row['supplier_id'],
				'currency'                => $order_query->row['currency'],
				'supplier'       		  => $order_query->row['supplier'],
				'total'                   => $order_query->row['total'],
				'vat'                     => $order_query->row['vat'],
				'delivery_charge'         => $order_query->row['delivery_charge'],				
				'order_status'            => $order_query->row['order_status'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'tracking_id_s2s'         => $order_query->row['tracking_id_s2s'],
				'tracking_id_s2c'         => $order_query->row['tracking_id_s2c'],
				'delivery_time_s2s'       => $order_query->row['delivery_time_s2s'],
				'delivery_time_s2c'       => $order_query->row['delivery_time_s2c'],
				'poinvoice_ref'       => $order_query->row['poinvoice_ref'],
				'inv_value'       => $order_query->row['inv_value'],
				'comments'            	  => $order_query->row['comments'],
				'date_added'              => $order_query->row['date_added'],
				'tax_rate_id'              => $order_query->row['tax_rate_id'],
				'send_to'              => $order_query->row['sales_order_id']==0?1:$order_query->row['send_to']
			);
		} else {
			return false;
		}
	}
	
	public function getOrderForPrint($order_id) {
		$order_query = $this->db->query("SELECT  po.*,s.*,po.date_added as OrderDate,
			case when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 
			when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0 else DATEDIFF(NOW(),po.date_added) - maxshipdays end as delay,
		s.country_id as supplier_country_id,po.comments as comments,c.title as currency,os.name as order_status,z.name as supplier_zone,z.code as supplier_zone_code FROM " . DB_PREFIX . "purchase_order po INNER JOIN " 
		. DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " 
		. DB_PREFIX . "currency c ON c.currency_id = s.currency_id "
		. "LEFT JOIN ". DB_PREFIX ."zone z on s.zone_id = z.zone_id " 
		."INNER JOIN ". DB_PREFIX . "order_status os ON os.order_status_id=po.order_status_id WHERE po.order_id = '" . (int)$order_id . "' AND os.language_id='".(int)$this->config->get('config_language_id')."'");
	
		if ($order_query->num_rows) {
		
			if (((int) $order_query->row['sales_order_id'])!=0) {
				// Order send to customer
				$shipping_query =  $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id='". $order_query->row['sales_order_id'] . "'");	
				$shipping_zone_code = '';
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
				if ($shipping_query->num_rows) {
					$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$shipping_query->row['shipping_zone_id'] . "'");

					if ($zone_query->num_rows) 
						$shipping_zone_code = $zone_query->row['code'];		
					
					$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$shipping_query->row['shipping_country_id'] . "'");

					if ($country_query->num_rows) {
						$shipping_iso_code_2 = $country_query->row['iso_code_2'];
						$shipping_iso_code_3 = $country_query->row['iso_code_3'];
					} 
				}

				
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['supplier_country_id'] . "'");

				if ($country_query->num_rows) {
					$supplier_iso_code_2 = $country_query->row['iso_code_2'];
					$supplier_iso_code_3 = $country_query->row['iso_code_3'];
					$supplier_country = $country_query->row['name'];
				} else {
					$supplier_iso_code_2 = '';
					$supplier_iso_code_3 = '';
					$supplier_country = '';
				}
			
		
				return array(
					'send_to'				  => $order_query->row['send_to'],
					'delay'				      => $order_query->row['delay'],
					'auth_token'			  => $order_query->row['auth_token'],
					'user_id'			  => $order_query->row['user_id'],
					'order_id'                => $order_query->row['order_id'],
					'sales_order_id'          => $order_query->row['sales_order_id'],
					'order_date'              => $order_query->row['date_added'],
					'invoice_no'              => $order_query->row['invoice_no'],
					'invoice_prefix'          => $order_query->row['invoice_prefix'],
					'store_id'                => $order_query->row['store_id'],
					'store_name'              => $order_query->row['store_name'],
					'store_url'               => $order_query->row['store_url'],
					'supplier_id'             => $order_query->row['supplier_id'],
					'currency'                => $order_query->row['currency'],
					'total'                   => $order_query->row['total'],
					'vat'                     => $order_query->row['vat'],
					'delivery_charge'         => $order_query->row['delivery_charge'],				
					'order_status'            => $order_query->row['order_status'],
					'order_status_id'         => $order_query->row['order_status_id'],
					'currency_id'             => $order_query->row['currency_id'],
					'currency_code'           => $order_query->row['currency_code'],
					'currency_value'          => $order_query->row['currency_value'],
					'tracking_id_s2s'         => $order_query->row['tracking_id_s2s'],
					'tracking_id_s2c'         => $order_query->row['tracking_id_s2c'],
					'delivery_time_s2s'       => $order_query->row['delivery_time_s2s'],
					'delivery_time_s2c'       => $order_query->row['delivery_time_s2c'],
					'poinvoice_ref'       => $order_query->row['poinvoice_ref'],
					'comments'				  => $order_query->row['comments'],	
					'date_added'              => $order_query->row['OrderDate'],	
					'email'					  => $shipping_query->row['email'],	
					'telephone'				  => $shipping_query->row['telephone'],					
					'shipping_firstname'      => empty($shipping_query->row['shipping_firstname'])?$shipping_query->row['payment_firstname']:$shipping_query->row['shipping_firstname'],
					'shipping_lastname'       => empty($shipping_query->row['shipping_lastname'])?$shipping_query->row['payment_lastname']:$shipping_query->row['shipping_lastname'],
					'shipping_company'        => empty($shipping_query->row['shipping_company'])?$shipping_query->row['payment_company']:$shipping_query->row['shipping_company'],
					'shipping_address_1'      => empty($shipping_query->row['shipping_address_1'])?$shipping_query->row['payment_address_1']:$shipping_query->row['shipping_address_1'],
					'shipping_address_2'      => empty($shipping_query->row['shipping_address_2'])?$shipping_query->row['payment_address_2']:$shipping_query->row['shipping_address_2'],
					'shipping_postcode'       => empty($shipping_query->row['shipping_postcode'])?$shipping_query->row['payment_postcode']:$shipping_query->row['shipping_postcode'],
					'shipping_city'           => empty($shipping_query->row['shipping_city'])?$shipping_query->row['payment_city']:$shipping_query->row['shipping_city'],
					'shipping_zone_id'        => empty($shipping_query->row['shipping_zone_id'])?$shipping_query->row['payment_zone_id']:$shipping_query->row['shipping_zone_id'],
					'shipping_zone'           => empty($shipping_query->row['shipping_zone'])?$shipping_query->row['payment_zone']:$shipping_query->row['shipping_zone'],
					'shipping_zone_code'      => $shipping_zone_code,
					'shipping_country_id'     => empty($shipping_query->row['shipping_country_id'])?$shipping_query->row['payment_country_id']:$shipping_query->row['shipping_country_id'],
					'shipping_country'        => empty($shipping_query->row['shipping_country'])?$shipping_query->row['payment_country']:$shipping_query->row['shipping_country'],
					'shipping_iso_code_2'     => $shipping_iso_code_2,
					'shipping_iso_code_3'     => $shipping_iso_code_3,
					'shipping_method'         => $shipping_query->row['shipping_method'],
					'shipping_code'           => $shipping_query->row['shipping_code'],
					'supplier_firstname'      => $order_query->row['firstname'],
					'supplier_lastname'       => $order_query->row['lastname'],
					'supplier_name'           => $order_query->row['name'],
					'supplier_address_1'       => $order_query->row['address1'],
					'supplier_address_2'       => $order_query->row['address2'],
					'supplier_postcode'        => $order_query->row['postcode'],
					'supplier_city'            => $order_query->row['city'],
					'supplier_zone_id'         => $order_query->row['zone_id'],
					'supplier_zone'            => $order_query->row['supplier_zone'],
					'supplier_zone_code'       => $order_query->row['supplier_zone_code'],
					'supplier_country_id'      => $order_query->row['supplier_country_id'],
					'supplier_country'         => $supplier_country,
					'supplier_email'         => $order_query->row['email'],
					'supplier_telephone'         => $order_query->row['telephone'],
					'supplier_iso_code_2'      => $supplier_iso_code_2,
					'supplier_iso_code_3'      => $supplier_iso_code_3,
					'fileattach' => $order_query->row['fileattach']
				);
			}
			else {
				return array(
					'send_to'				  => $order_query->row['send_to'],
					'delay'				      => $order_query->row['delay'],
					'order_id'                => $order_query->row['order_id'],
					'sales_order_id'          => $order_query->row['sales_order_id'],
					'order_date'              => $order_query->row['date_added'],
					'invoice_no'              => $order_query->row['invoice_no'],
					'invoice_prefix'          => $order_query->row['invoice_prefix'],
					'store_id'                => $order_query->row['store_id'],
					'store_name'              => $order_query->row['store_name'],
					'store_url'               => $order_query->row['store_url'],
					'supplier_id'             => $order_query->row['supplier_id'],
					'currency'                => $order_query->row['currency'],
					'total'                   => $order_query->row['total'],
					'vat'                     => $order_query->row['vat'],
					'delivery_charge'         => $order_query->row['delivery_charge'],				
					'order_status'            => $order_query->row['order_status'],
					'order_status_id'         => $order_query->row['order_status_id'],
					'currency_id'             => $order_query->row['currency_id'],
					'currency_code'           => $order_query->row['currency_code'],
					'currency_value'          => $order_query->row['currency_value'],
					'tracking_id_s2s'         => $order_query->row['tracking_id_s2s'],
					'tracking_id_s2c'         => $order_query->row['tracking_id_s2c'],
					'delivery_time_s2s'       => $order_query->row['delivery_time_s2s'],
					'delivery_time_s2c'       => $order_query->row['delivery_time_s2c'],
					'poinvoice_ref'      	 => $order_query->row['poinvoice_ref'],
					'comments'				  => $order_query->row['comments'],	
					'date_added'              => $order_query->row['OrderDate'],	
					'email'					  => "",	
					'telephone'				  => "",					
					'shipping_firstname'      => "",
					'shipping_lastname'       => "",
					'shipping_company'        => "",
					'shipping_address_1'      => "",
					'shipping_address_2'      => "",
					'shipping_postcode'       => "",
					'shipping_city'           => "",
					'shipping_zone_id'        => "",
					'shipping_zone'           => "",
					'shipping_zone_code'      => "",
					'shipping_country_id'     => "",
					'shipping_country'        => "",
					'shipping_iso_code_2'     => "",
					'shipping_iso_code_3'     => "",
					'shipping_method'         => "",
					'shipping_code'           => "",
					'supplier_firstname'      => $order_query->row['firstname'],
					'supplier_lastname'       => $order_query->row['lastname'],
					'supplier_name'           => $order_query->row['name'],
					'supplier_address_1'       => $order_query->row['address1'],
					'supplier_address_2'       => $order_query->row['address2'],
					'supplier_postcode'        => $order_query->row['postcode'],
					'supplier_city'            => $order_query->row['city'],
					'supplier_zone_id'         => $order_query->row['zone_id'],
					'supplier_zone'            => $order_query->row['supplier_zone'],
					'supplier_zone_code'       => $order_query->row['supplier_zone_code'],
					'supplier_country_id'      => $order_query->row['supplier_country_id'],
					'supplier_country'         => "",
					'supplier_email'         => $order_query->row['email'],
					'supplier_telephone'         => $order_query->row['telephone'],
					'supplier_iso_code_2'      => "",
					'supplier_iso_code_3'      => "",
					'fileattach' => ""
				);
			}			
		} else {
			return false;
		}
	}
	
	public function getOrders($data = array()) {
		if ($data) {
			$sql = "select po.*, maxshipdays,po.order_status_id as status_id,case 
				when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 				
				when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0
				else
				DATEDIFF(NOW(),po.date_added) - maxshipdays 
				end as delay,s.name as name,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " . DB_PREFIX . "order_status os ON po.order_status_id=os.order_status_id ";
			
			$sql .= " WHERE os.language_id='".(int)$this->config->get('config_language_id')."'";
			

			if (!empty($data['filter_delay'])) {
				$sql .= " AND case when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0 else DATEDIFF(NOW(),po.date_added) - maxshipdays end>".$data['filter_delay'];
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND po.sales_order_id='" . (int)$data['filter_order_id'] . "'";
			}
			
			if (!empty($data['filter_porder_id'])) {
				$sql .= " AND po.order_id='" . (int)$data['filter_porder_id'] . "'";
			}
			
			if (!empty($data['filter_refno'])) {
				$sql .= " AND LCASE(poinvoice_ref) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_refno'])) . "%'";
			}
			
			if (!empty($data['filter_supplier_id'])) {
				$sql .= " AND po.supplier_id='" . (int)$data['filter_supplier_id'] . "'";
			}

			if (!empty($data['filter_order_status_id'])) {
				$sql .= " AND po.order_status_id='" . (int)$data['filter_order_status_id'] . "'";
			}
			
			if (!empty($data['filter_total'])) {
				$sql .= " AND po.total=" . (int)$data['filter_total'] ;
			}
			

			$sort_data = array(
				'name',
				'po.order_id',
				'sort_order',
				'total',
				'po.sales_order_id',
				'po.poinvoice_ref',
				'status',
				'delay'
			);	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY po.order_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
			//die($sql);
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			$order_data = $this->cache->get('po');
			if (!$order_data) {
			
				$sql = "select po.*, maxshipdays,po.order_status_id as status_id,case 
					when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 				
					when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0
					else
					DATEDIFF(NOW(),po.date_added) - maxshipdays 
					end as delay,s.name as name,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " . DB_PREFIX . "order_status os ON po.order_status_id=os.order_status_id WHERE os.language_id='".(int)$this->config->get('config_language_id')."'";
	
				$query = $this->db->query($sql);
	
				$order_data = $query->rows;
			
				$this->cache->set('po', $order_data);
			}
			return $order_data;
		}
	}
	
	public function getOrdersForSupplier($supplier_id,$data = array()) {
		if ($data) {
			$sql = "select po.*,case 
				when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 				
				when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0
				else
				DATEDIFF(NOW(),po.date_added) - maxshipdays 
				end as delay,s.name as name,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " . DB_PREFIX . "order_status os ON po.order_status_id=os.order_status_id WHERE po.supplier_id='".(int)$supplier_id."' AND os.language_id='".(int)$this->config->get('config_language_id')."'";
			
			$sort_data = array(
				'name',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY po.order_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			$order_data = $this->cache->get('po');
		
			if (!$order_data) {
				$query = $this->db->query("select po.*,s.name as name,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " . DB_PREFIX . "order_status os ON po.order_status_id=os.order_status_id WHERE os.language_id='".(int)$this->config->get('config_language_id')."'");
	
				$order_data = $query->rows;
			
				$this->cache->set('po', $order_data);
			}
		 
			return $order_data;
		}
	}
	
	
	public function getSupplierStores($supplier_id) {
		$supplier_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		foreach ($query->rows as $result) {
			$supplier_store_data[] = $result['store_id'];
		}
		
		return $supplier_store_data;
	}
	
	public function getTotalPurchaseOrders($data = array()) {
	
	
		if ($data) {
			$sql = "select po.*, maxshipdays,po.order_status_id as status_id,case 
				when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 				
				when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0
				else
				DATEDIFF(NOW(),po.date_added) - maxshipdays 
				end as delay,s.name as name,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=po.supplier_id INNER JOIN " . DB_PREFIX . "order_status os ON po.order_status_id=os.order_status_id ";
			
			$sql .= " WHERE os.language_id='".(int)$this->config->get('config_language_id')."'";
			

			if (!empty($data['filter_delay'])) {
				$sql .= " AND case when po.order_status_id IN(3,5,7,9,10,11,12,13,14,16) then 0 when DATEDIFF(NOW(),po.date_added) - maxshipdays <0 then 0 else DATEDIFF(NOW(),po.date_added) - maxshipdays end>".$data['filter_delay'];
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND po.sales_order_id='" . (int)$data['filter_order_id'] . "'";
			}
			
			if (!empty($data['filter_porder_id'])) {
				$sql .= " AND po.order_id='" . (int)$data['filter_porder_id'] . "'";
			}
			
			if (!empty($data['filter_refno'])) {
				$sql .= " AND LCASE(poinvoice_ref) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_refno'])) . "%'";
			}
			
			if (!empty($data['filter_supplier_id'])) {
				$sql .= " AND po.supplier_id='" . (int)$data['filter_supplier_id'] . "'";
			}

			if (!empty($data['filter_order_status_id'])) {
				$sql .= " AND po.order_status_id='" . (int)$data['filter_order_status_id'] . "'";
			}
			
			if (!empty($data['filter_total'])) {
				$sql .= " AND po.total=" . (int)$data['filter_total'] ;
			}
			

			$sort_data = array(
				'name',
				'sort_order'
			);	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY po.order_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}
							
			$query = $this->db->query($sql);

			return $query->num_rows;
		} else {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_order");
		
			return $query->row['total'];
		}		      	
	}	
	public function getTotalPurchaseOrdersForSupplier($supplier_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_order WHERE supplier_id='".(int)$supplier_id."'");
		
		return $query->row['total'];
	}	
	
	public function updatePrices() {	
		$productInfo = $this->db->query("SELECT p.product_id,p.gp_percent as product_gp_percent,s.gp_percent as supplier_gp_percent,(1/value) * cost as ExchangeValue,s.currency_id  FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id=p.supplier_id INNER JOIN " . DB_PREFIX . "currency c ON s.currency_id=c.currency_id");

		foreach ($productInfo->rows as $product) {
			$gp = 0;
			$salesPrice = 0;
			if ($product['product_gp_percent']!=0)
				$gp = (float)$product['product_gp_percent'];
			else
				if ($product['supplier_gp_percent']!=0)
					$gp = (float)$product['supplier_gp_percent'];
				else {
					$settingInfo = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key`='config_po_grossprofit' AND store_id=0");
					if ($settingInfo->num_rows>0)			
						$gp = (float)$settingInfo->row['value'];
					else
						$gp = 0;
				}
			if ($gp!=0) {
				$calc_method = $this->config->get('config_po_calculationmethod');
				if ($calc_method=="P")
					if ($gp < 100)
						$salesPrice = $product['ExchangeValue'] / ((100 - $gp)/100);
					else
						$salesPrice = 0;
				else 
					$salesPrice = $product['ExchangeValue'] * $gp;
			}

			if ($gp!=0) {
				$this->db->query("UPDATE  " . DB_PREFIX . "product SET price='". $salesPrice  . "' WHERE product_id = '" . $product['product_id'] . "'");
			}
		}
	}	

	public function updateStock($order_product_id){
		$query = $this->db->query("SELECT product_id,quantity FROM " . DB_PREFIX . "purchase_order_product WHERE order_product_id='" . $order_product_id . "'");
		$product_id =  $query->row['product_id'];
		$quantity =  $query->row['quantity'];	
			
		$query = $this->db->query("SELECT product_option_id,product_option_value_id FROM " . DB_PREFIX . "purchase_order_option WHERE order_product_id='" . $order_product_id . "'");
		if ($query->rows) {
			foreach ($query->rows as $row){
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = quantity + ". $quantity . " WHERE product_option_value_id='".$row['product_option_value_id']."'" );
			}
		}
		else {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity + ". $quantity . " WHERE product_id='".$product_id."'" );					
		}
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order_product SET stock_added = 1  WHERE order_product_id='" . $order_product_id . "'");	
	}
	
	public function EventExists($code, $trigger, $action) {
		$row = $this->db->query("SELECT `code` FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "' AND `trigger`='". $this->db->escape($trigger)."' AND `action` = '" . $this->db->escape($action) . "'");
		if ($row->num_rows) {
			return true;
		} else {
			return false;
		}
	}
}
?>