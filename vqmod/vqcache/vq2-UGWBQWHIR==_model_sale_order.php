<?php
class ModelSaleOrder extends Model {
	
			public function getOrderPurchaseOrders($order_id) {
				$query = $this->db->query("SELECT order_id,order_id as orderno,s.name as supplier,os.name as status from " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s on s.supplier_id = po.supplier_id inner join " . DB_PREFIX . "order_status os on po.order_status_id = os.order_status_id where os.language_id='".(int)$this->config->get('config_language_id')."' AND po.sales_order_id='" . (int)$order_id . "'");
				return $query->rows;
 			}
			

			public function generatepo($order_product_ids) {
				$invoice_prefix = $this->config->get('config_po_prefix');
				$bulkpo = $this->config->get('config_po_bulkpo');
				$considerstock = $this->config->get('config_po_considerstock');
				$return_po = array();
				// Find all product items from the passed order product ids which has no po's generated and has supplier
				$product_info_query = $this->db->query("SELECT p.product_id,pd.name,p.cost,op.model,order_product_id,op.quantity,p.quantity as stockinhand, op.price,op.tax,op.total,c.currency_id,code,value,order_id,s.supplier_id,s.comments,s.name as supplier_name,s.tax_rate_id,s.dropship_fee,s.exportpath 
						from " . DB_PREFIX . "order_product op
						INNER JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id 
						LEFT JOIN " . DB_PREFIX . "supplier s ON p.supplier_id = s.supplier_id 
						INNER JOIN " . DB_PREFIX . "currency c ON s.currency_id = c.currency_id 
						INNER JOIN " . DB_PREFIX . "product_description pd on pd.product_id = p.product_id
						WHERE order_product_id IN (".implode(',',$order_product_ids). ") AND po_product_id=0 AND pd.language_id = '".(int)$this->config->get('config_language_id')."' AND s.supplier_id!=0 ORDER BY s.supplier_id");

				$lastSupplierID = 0;
				$orderTotal = 0;
				foreach($product_info_query->rows as $product) {  
					
					//Find option price
					$order_option_query = $this->db->query("SELECT cost,cost_prefix FROM " . DB_PREFIX . "order_option oo 
						INNER JOIN " . DB_PREFIX . "product_option_value pov ON oo.product_option_value_id=pov.product_option_value_id
						WHERE order_product_id='". $product['order_product_id']. "'" );
					
					
					$costAdjust = 0;					
					
					foreach ( $order_option_query->rows as $costInfo) {
						//$costInfo = $order_option_query->row;
						if ($costInfo['cost_prefix'] == "+")
							$costAdjust += 1 * $costInfo['cost'];
						else
							$costAdjust += -1 * $costInfo['cost'];	

					}
					
					$productCost = ( (float) $product['cost'] )+ $costAdjust;
					
					//Find parent order details
					$order_info = $this->getOrder($product['order_id']);	

					$bulk_order_id=0;
					if (!empty($bulkpo)) {
						$open_po_query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "purchase_order 
						WHERE order_status_id<3 AND supplier_id='".(int)$product['supplier_id']."' ORDER BY order_status_id LIMIT 1");								
						if ($open_po_query->num_rows) {
							$po_bulk_info = $open_po_query->row;
							$bulk_order_id = $po_bulk_info['order_id'];
						}
					}
					
					if (!empty($considerstock)) {
						if ($product['stockinhand']>=$product['quantity'])
							$poqty = 0;
						else
							$poqty = $product['quantity'] - $product['stockinhand'];						
					}
					else
						$poqty = $product['quantity'];
					if ($poqty==0) continue;
				
					//If this item is for a new supplier
					//TODO : order_status_id is hardcoded. This must change
					if ((int)$product['supplier_id']!=$lastSupplierID){
						
						//Get the Seq no
						$order_seq_query = $this->db->query("SELECT max(po_seq_no) as maxSeq from " . DB_PREFIX . "purchase_order WHERE sales_order_id='". $product['order_id']. "'" );
						$orderSeqInfo = $order_seq_query->row;
						if (empty($orderSeqInfo['maxSeq']))
							$seqNo = 1;
						else
							$seqNo = $orderSeqInfo['maxSeq']+1;
						if (!empty($product['exportpath'])) {
							if (!file_exists($product['exportpath'])) {
								$outstream = fopen($product['exportpath'], "w");
								fputcsv($outstream,array('Order','Date','Customer First Name','Customer Last Name','Customer Email','Customer Telephone','Customer Address 1','Customer Address 2','Customer City','Customer Post Code','Customer Country','Customer Region','Product name','Model','Quantity','Price','Total'));
								fclose($outstream);
							}
						}
						$comments = $product['comments'] . "\n\n" . $order_info['comment'] ;
						//Get the tax rate
						$this->load->model('localisation/tax_rate');
						$tax_info = $this->model_localisation_tax_rate->getTaxRate($product['tax_rate_id']); 
						
						if ($bulk_order_id==0) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$order_info['store_id'] . 
							"', store_name = '" . $this->db->escape($order_info['store_name']) . "',store_url = '" . $order_info['store_url'] . 
							"', supplier_id = '" . (int)$product['supplier_id'] . "', sales_order_id = '" . (int)$order_info['order_id'] .
							"', comments='".$this->db->escape($comments). "',send_to = '0',".
							"delivery_charge='".(float)$product['dropship_fee']. "',".
							" auth_token = '".md5(mt_rand())."',".
							"order_status_id = '1', currency_id = '" . (int)$product['currency_id'] .  "', currency_code = '" . $product['code'] . 
							"', po_seq_no = '". (int) $seqNo . "', currency_value = '" . (float)$product['value'] . "', date_added = NOW(), date_modified = NOW()");				
							$order_id = $this->db->getLastId();
							$return_po[] = $order_id;
							if ($this->config->get('config_po_changeorderstatus')==1)
								$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id='2' WHERE order_id='".(int)$product['order_id']."'");
						}
						else {
							$order_id = $bulk_order_id;
						}
						
						$orderTotal = 0;	
						$this->createPONo((int)$order_id);						
					}
					if (isset($tax_info['rate']))
						$vatAmount = ((int)$product['quantity'] * $productCost) * ( (float)$tax_info['rate']/100);
					else
						$vatAmount = 0;
					
					//Added after consultation with roger
					//$vatAmount += ((float) $product['dropship_fee'] * ( (float)$tax_info['rate']/100));
					$ord_prod_id = 0;
					if ($bulk_order_id!=0) {
						$ord_prod_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_order_product WHERE order_id = '" 
							. (int)$order_id ."' AND product_id = '" . (int)$product['product_id'] . "' and price='".$productCost."'");
						if ($ord_prod_query->num_rows) {
							$ord_prod_info = $ord_prod_query->row;
							$ord_prod_id = $ord_prod_info['order_product_id'];
							$ord_prod_qty = $ord_prod_info['quantity'];
							$ord_prod_total = $ord_prod_info['total'];
						}
					}
					if ($ord_prod_id !=0) {
						$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order_product` ".
						" SET quantity = '" . ( $ord_prod_qty+(int)$poqty) . 
						"', total = '" . ($ord_prod_total + ((int)$poqty * $productCost)) . 
						"' WHERE order_product_id='".$ord_prod_id."'");
						$po_product_id = $ord_prod_id;
					}
					else {
					
						$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order_product` SET order_id = '" . (int)$order_id . 
						"', product_id = '" . (int)$product['product_id'] . "',sale_order_product_id = '" . (int)$product['order_product_id'] . 
						"', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']).
						"', quantity = '" . (int)$poqty . "', price = '" . $productCost .  "', total = '" . (int)$poqty * $productCost . 
						"'"); 
						$po_product_id = $this->db->getLastId();
					}
					
					$arCSV = array($order_id,date("d/m/y g:i a"),$order_info['shipping_firstname'],$order_info['shipping_lastname'],$order_info['email'],
					$order_info['telephone'],$order_info['shipping_address_1'],$order_info['shipping_address_2'],$order_info['shipping_city'],
					$order_info['shipping_postcode'],$order_info['shipping_country'],$order_info['shipping_zone'],
					$product['name'],$product['model'],$poqty,$productCost,$poqty * $productCost);
					if (!empty($product['exportpath'])) {
						if (file_exists($product['exportpath'])) {
							$outstream = fopen($product['exportpath'], "a");
							fputcsv($outstream,$arCSV );
							fclose($outstream);
						}
					}
					
					$this->db->query("UPDATE " . DB_PREFIX . "order_product SET po_product_id='".(int)$po_product_id. "' WHERE order_product_id='". (int)$product['order_product_id'] ."'");
					$lastSupplierID = (int)$product['supplier_id'] ;	
					$orderTotal = $orderTotal + ((int)$poqty * $productCost);
					
					$subTotal = $orderTotal ;
					if (isset($tax_info['rate'])){					
						if ($product['tax_rate_id']!=0) {
							$vatTotal =  $subTotal *   ((float)$tax_info['rate']/100);
							//Added after consultation with roger
							$vatTotal += ((float) $product['dropship_fee'] * ( (float)$tax_info['rate']/100));
						}
					}
					else
						$vatTotal = 0;
										
					
					//Set order status as processin
					//$order_pro_query = $this->db->query("SELECT COUNT(*) as rowcount FROM " . DB_PREFIX . "order_product WHERE po_product_id=0 AND order_id='". $product['order_id']. "'" );
					//$orderproInfo = $order_pro_query->row;
					//if ($orderproInfo['rowcount']==0)
					//	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id='2' WHERE order_id='".(int)$product['order_id']."'");
					 
					$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET total='". $orderTotal ."', vat='" . $vatTotal."' WHERE order_id='".(int)$order_id."'");					
					
					
					$options_info = $this->getOrderOptions($product['order_id'],$product['order_product_id']);
					
					foreach($options_info as $option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$po_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'"); 
					}
				}
				return $return_po;
			}
			
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
'parent_order_id'                => $order_query->row['parent_order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => unserialize($order_query->row['custom_field']),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => unserialize($order_query->row['payment_custom_field']),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return;
		}
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, (SELECT count(*) FROM " . DB_PREFIX . "order_product op WHERE op.order_id=o.order_id) as op_count, 			SUM((SELECT count(*) FROM " . DB_PREFIX . "purchase_order_product pop WHERE pop.order_id=po.order_id)) as pop_count, o.date_modified FROM `" . DB_PREFIX . "order` o"." LEFT JOIN " . DB_PREFIX . "purchase_order po ON po.sales_order_id=o.order_id";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
'pop_count',
			'o.total'
		);

	
			$sql .= " GROUP BY o.order_id ";
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
	
			$data['orders'] = array();
			$result['po_gen_status'] = "None";
			foreach ($query->rows as $result) {
				$result['po_gen_status'] =  $result['pop_count']==0 ? "None" : ($result['pop_count'] >= $result['op_count'] ? "All" : "Partial");
				if (!empty($data['filter_po_generated'])) {
					if ($result['po_gen_status'] != $data['filter_po_generated'])
						continue;
				}
				$data['orders'][] = $result;
			}
			
			return $data['orders'];
			
		return $query->rows;
	}

	public function getOrderProducts($order_id) {
			
			$query = $this->db->query("SELECT o.*,s.name as supplier FROM " . DB_PREFIX . "order_product o INNER JOIN " . DB_PREFIX . "product p ON  o.product_id = p.product_id LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = p.supplier_id WHERE order_id = '" . (int)$order_id 
			 . "'");

		return $query->rows;
	}

	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	
			public function createPONo($order_id) {			
				$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET invoice_no = '" . (int)$order_id . "' WHERE order_id = '" . (int)$order_id . "'");					
			}
			
	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.powertrack_trackcode, oh.powertrack_carrier, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}
        
        public function getProductOptionShapeValue($product_option_id)
        {
            $sql = "SELECT a.image FROM " . DB_PREFIX . "option_value as a inner join " . DB_PREFIX . "product_option_value as b on a.option_value_id=b.option_shape WHERE b.product_option_value_id ='" . $product_option_id . "'";
            $query = $this->db->query($sql);
            return $query->row['image'];
        }
}