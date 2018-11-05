<?php
class ModelPomgmtPo extends Model {
	public function getProduct($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT s.*,rate FROM " . DB_PREFIX . "supplier s LEFT JOIN " . DB_PREFIX . "tax_rate t ON s.tax_rate_id = t.tax_rate_id WHERE s.supplier_id='".$supplier_id."'");
		return $query->row;
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT pop.*,p.supproducturl,pd.description,p.mpn,p.ean FROM " . DB_PREFIX . "purchase_order_product pop INNER JOIN " . DB_PREFIX . "product p ON p.product_id = pop.product_id ". 
		" INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id and pd.language_id='".(int)$this->config->get('config_language_id'). "' " .
		" WHERE order_id = '" . (int)$order_id . "'");		
		return $query->rows;
	}

	public function POFromOrder($order_id) {
		$invoice_prefix = $this->config->get('config_po_prefix');
		$bulkpo = $this->config->get('config_po_bulkpo');
		$return_po = array();
		// Find all product items from the passed order product ids which has no po's generated and has supplier
		$product_info_query = $this->db->query("SELECT p.product_id,pd.name,p.cost,p.model,order_product_id,op.quantity,op.price,op.tax,op.total,c.currency_id,code,value,order_id,s.supplier_id,s.comments,s.name as supplier_name,s.tax_rate_id,s.dropship_fee,s.exportpath 
						from " . DB_PREFIX . "order_product op
						INNER JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id 
						LEFT JOIN " . DB_PREFIX . "supplier s ON p.supplier_id = s.supplier_id 
						INNER JOIN " . DB_PREFIX . "currency c ON s.currency_id = c.currency_id 
						INNER JOIN " . DB_PREFIX . "product_description pd on pd.product_id = p.product_id
						WHERE order_id = ".(int)$order_id. " AND po_product_id=0 AND pd.language_id = '".(int)$this->config->get('config_language_id')."' AND s.supplier_id!=0 ORDER BY s.supplier_id");
		$lastSupplierID = 0;
		$orderTotal = 0;								

		foreach($product_info_query->rows as $product) {
			
			//Find option price
			$order_option_query = $this->db->query("SELECT cost,cost_prefix FROM " . DB_PREFIX . "order_option oo 
				INNER JOIN " . DB_PREFIX . "product_option_value pov ON oo.product_option_value_id=pov.product_option_value_id
				WHERE order_product_id='". $product['order_product_id']. "'" );
			
			
			$costAdjust = 0;					
			
			if ($order_option_query->num_rows) {
				$costInfo = $order_option_query->row;
				if ($costInfo['cost_prefix'] == "+")
					$costAdjust = 1 * $costInfo['cost'];
				else
					$costAdjust = -1 * $costInfo['cost'];						
			}
			
			$productCost = ( (float) $product['cost'] )+ $costAdjust;
			
			//Find parent order details
			$order_info = $this->getOrder($product['order_id']);	
			$bulk_order_id=0;
			//die($bulkpo."------------------");
			if (!empty($bulkpo)) {
				$open_po_query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "purchase_order 
				WHERE order_status_id<3 AND supplier_id='".(int)$product['supplier_id']."' ORDER BY order_status_id LIMIT 1");								
				if ($open_po_query->num_rows) {
					$po_bulk_info = $open_po_query->row;
					$bulk_order_id = $po_bulk_info['order_id'];
				}
			}
		
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
				$comments = $this->db->escape($product['comments']) . "\n\n" . $order_info['comment'] ;
				//Get the tax rate
				$this->load->model('localisation/tax_rate');
				$tax_info = $this->model_localisation_tax_rate->getTaxRate($product['tax_rate_id']); 
				
				if ($bulk_order_id==0) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$order_info['store_id'] . 
					"', store_name = '" . $this->db->escape($order_info['store_name']) . "',store_url = '" . $order_info['store_url'] . 
					"', supplier_id = '" . (int)$product['supplier_id'] . "', sales_order_id = '" . (int)$order_info['order_id'] .
					"', comments='".$comments. "',send_to = '0',".
					"delivery_charge='".(float)$product['dropship_fee']. "',".
					" auth_token = '".md5(mt_rand())."',".
					"order_status_id = '1', currency_id = '" . (int)$product['currency_id'] .  "', currency_code = '" . $product['code'] . 
					"', po_seq_no = '". (int) $seqNo . "', currency_value = '" . (float)$product['value'] . "', date_added = NOW(), date_modified = NOW()");				
					$order_id = $this->db->getLastId();
					$return_po[] = $order_id;
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
				" SET quantity = '" . ( $ord_prod_qty+(int)$product['quantity']) . 
				"', total = '" . ($ord_prod_total + ((int)$product['quantity'] * $productCost)) . 
				"' WHERE order_product_id='".$ord_prod_id."'");
				$po_product_id = $ord_prod_id;
			}
			else {
			
				$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order_product` SET order_id = '" . (int)$order_id . 
				"', product_id = '" . (int)$product['product_id'] . "',sale_order_product_id = '" . (int)$product['order_product_id'] . 
				"', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']).
				"', quantity = '" . (int)$product['quantity'] . "', price = '" . $productCost .  "', total = '" . (int)$product['quantity'] * $productCost . 
				"'"); 
				$po_product_id = $this->db->getLastId();
			}
			
			$arCSV = array($order_id,date("d/m/y g:i a"),$order_info['shipping_firstname'],$order_info['shipping_lastname'],$order_info['email'],
			$order_info['telephone'],$order_info['shipping_address_1'],$order_info['shipping_address_2'],$order_info['shipping_city'],
			$order_info['shipping_postcode'],$order_info['shipping_country'],$order_info['shipping_zone'],
			$product['name'],$product['model'],$product['quantity'],$productCost,$product['quantity'] * $productCost);
			if (!empty($product['exportpath'])) {
				if (file_exists($product['exportpath'])) {
					$outstream = fopen($product['exportpath'], "a");
					fputcsv($outstream,$arCSV );
					fclose($outstream);
				}
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "order_product SET po_product_id='".(int)$po_product_id. "' WHERE order_product_id='". (int)$product['order_product_id'] ."'");
			$lastSupplierID = (int)$product['supplier_id'] ;	
			$orderTotal = $orderTotal + ((int)$product['quantity'] * $productCost);
			
			$subTotal = $orderTotal ;
			if (isset($tax_info['rate'])){					
				if ($product['tax_rate_id']!=0)
					$vatTotal =  $subTotal *   ((float)$tax_info['rate']/100);
			}
			else
				$vatTotal = 0;
			
			//Set order status as processin
			$order_pro_query = $this->db->query("SELECT COUNT(*) as rowcount FROM " . DB_PREFIX . "order_product WHERE po_product_id=0 AND order_id='". $product['order_id']. "'" );
			$orderproInfo = $order_pro_query->row;
			if ($orderproInfo['rowcount']==0) {
				if ($this->config->get('config_po_changeorderstatus')==1)
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id='2' WHERE order_id='".(int)$product['order_id']."'");					
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET total='". $orderTotal ."', vat='" . $vatTotal."' WHERE order_id='".(int)$order_id."'");					
			
			$this->load->model('account/order');						
			$options_info = $this->model_account_order->getOrderOptions($product['order_id'],$product['order_product_id']);
			foreach($options_info as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$po_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'"); 
			}
		}
		return $return_po;
	}
	
	public function generatepo($order_product_ids) {
		$invoice_prefix = $this->config->get('config_po_prefix');
		$bulkpo = $this->config->get('config_po_bulkpo');
		$return_po = array();
		// Find all product items from the passed order product ids which has no po's generated and has supplier
		$product_info_query = $this->db->query("SELECT p.product_id,pd.name,p.cost,p.model,order_product_id,op.quantity,op.price,op.tax,op.total,c.currency_id,code,value,order_id,s.supplier_id,s.comments,s.name as supplier_name,s.tax_rate_id,s.dropship_fee,s.exportpath 
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
			
			if ($order_option_query->num_rows) {
				$costInfo = $order_option_query->row;
				if ($costInfo['cost_prefix'] == "+")
					$costAdjust = 1 * $costInfo['cost'];
				else
					$costAdjust = -1 * $costInfo['cost'];						
			}
			
			$productCost = ( (float) $product['cost'] )+ $costAdjust;
			
			//Find parent order details
			$order_info = $this->getOrder($product['order_id']);	
			$bulk_order_id=0;
			//die($bulkpo."------------------");
			if (!empty($bulkpo)) {
				$open_po_query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "purchase_order 
				WHERE order_status_id<3 AND supplier_id='".(int)$product['supplier_id']."' ORDER BY order_status_id LIMIT 1");								
				if ($open_po_query->num_rows) {
					$po_bulk_info = $open_po_query->row;
					$bulk_order_id = $po_bulk_info['order_id'];
				}
			}
		
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
				$comments = $this->db->escape($product['comments']) . "\n\n" . $order_info['comment'] ;
				//Get the tax rate
				$this->load->model('localisation/tax_rate');
				$tax_info = $this->model_localisation_tax_rate->getTaxRate($product['tax_rate_id']); 
				
				if ($bulk_order_id==0) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$order_info['store_id'] . 
					"', store_name = '" . $this->db->escape($order_info['store_name']) . "',store_url = '" . $order_info['store_url'] . 
					"', supplier_id = '" . (int)$product['supplier_id'] . "', sales_order_id = '" . (int)$order_info['order_id'] .
					"', comments='".$comments. "',send_to = '0',".
					"delivery_charge='".(float)$product['dropship_fee']. "',".
					" auth_token = '".md5(mt_rand())."',".
					"order_status_id = '1', currency_id = '" . (int)$product['currency_id'] .  "', currency_code = '" . $product['code'] . 
					"', po_seq_no = '". (int) $seqNo . "', currency_value = '" . (float)$product['value'] . "', date_added = NOW(), date_modified = NOW()");				
					$order_id = $this->db->getLastId();
					$return_po[] = $order_id;
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
				" SET quantity = '" . ( $ord_prod_qty+(int)$product['quantity']) . 
				"', total = '" . ($ord_prod_total + ((int)$product['quantity'] * $productCost)) . 
				"' WHERE order_product_id='".$ord_prod_id."'");
				$po_product_id = $ord_prod_id;
			}
			else {
			
				$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_order_product` SET order_id = '" . (int)$order_id . 
				"', product_id = '" . (int)$product['product_id'] . "',sale_order_product_id = '" . (int)$product['order_product_id'] . 
				"', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']).
				"', quantity = '" . (int)$product['quantity'] . "', price = '" . $productCost .  "', total = '" . (int)$product['quantity'] * $productCost . 
				"'"); 
				$po_product_id = $this->db->getLastId();
			}
			
			$arCSV = array($order_id,date("d/m/y g:i a"),$order_info['shipping_firstname'],$order_info['shipping_lastname'],$order_info['email'],
			$order_info['telephone'],$order_info['shipping_address_1'],$order_info['shipping_address_2'],$order_info['shipping_city'],
			$order_info['shipping_postcode'],$order_info['shipping_country'],$order_info['shipping_zone'],
			$product['name'],$product['model'],$product['quantity'],$productCost,$product['quantity'] * $productCost);
			if (!empty($product['exportpath'])) {
				if (file_exists($product['exportpath'])) {
					$outstream = fopen($product['exportpath'], "a");
					fputcsv($outstream,$arCSV );
					fclose($outstream);
				}
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "order_product SET po_product_id='".(int)$po_product_id. "' WHERE order_product_id='". (int)$product['order_product_id'] ."'");
			$lastSupplierID = (int)$product['supplier_id'] ;	
			$orderTotal = $orderTotal + ((int)$product['quantity'] * $productCost);
			
			$subTotal = $orderTotal ;
			if (isset($tax_info['rate'])){					
				if ($product['tax_rate_id']!=0)
					$vatTotal =  $subTotal *   ((float)$tax_info['rate']/100);
			}
			else
				$vatTotal = 0;
			
			//Set order status as processin
			$order_pro_query = $this->db->query("SELECT COUNT(*) as rowcount FROM " . DB_PREFIX . "order_product WHERE po_product_id=0 AND order_id='". $product['order_id']. "'" );
			$orderproInfo = $order_pro_query->row;
			if ($orderproInfo['rowcount']==0) {
				if ($this->config->get('config_po_changeorderstatus')==1)
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id='2' WHERE order_id='".(int)$product['order_id']."'");					
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET total='". $orderTotal ."', vat='" . $vatTotal."' WHERE order_id='".(int)$order_id."'");					
			
			$this->load->model('account/order');						
			$options_info = $this->model_account_order->getOrderOptions($product['order_id'],$product['order_product_id']);
			foreach($options_info as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$po_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'"); 
			}
		}
		return $return_po;
	}

	public function createPONo($order_id) {			
		$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET invoice_no = '" . (int)$order_id . "' WHERE order_id = '" . (int)$order_id . "'");					
	}	
	public function addPO($data) {
		//print_r($data);die('');
		
		$store_info = $this->getStore($data['store_id']);
		
		$invoice_prefix = $this->config->get('config_po_prefix');
		
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_SERVER;
		}
		
		$this->load->model('localisation/currency');

		$currency_info = $this->getCurrency($data['currency_id']);
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
			." send_to = '1'" );
		
		$order_id = $this->db->getLastId();

		$this->createPONo((int)$order_id);

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
					}
				}
			}				
		}	
		return $order_id;
	}
	
	public function editPO($order_id, $data) {
	
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
			." send_to = '" . $sendto . "' WHERE order_id='".(int)$order_id."'" );
		
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
					}
				}
			}
		}
	}
	
	public function getUserUsingAuthToken($auth_token) {
		$query = $this->db->query("SELECT s.user_id FROM " . DB_PREFIX . "purchase_order po INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id WHERE auth_token='".$auth_token."'");

		return $query->row;
	}
	
	public function getOrderStatus($order_id) {
		$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order_history ooh WHERE ooh.order_id='".(int)$order_id."' ORDER BY ooh.order_history_id DESC LIMIT 0,1");
		if ($query->row)
			return $query->row['order_status_id'];
		else
			return 0;
		
	}
	
	public function setOrderStatusAsSent($order_id) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE name = 'PO Sent'");
		$statusInfo = $query->row;
		
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET order_status_id = '" . $statusInfo['order_status_id'] . "' WHERE order_id='".(int)$order_id."'" );
		
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
								
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$shipping_query->row['shipping_zone_id'] . "'");

				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
				} else {
					$shipping_zone_code = '';
				}

				
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$shipping_query->row['shipping_country_id'] . "'");

				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
				} else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';
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
					'shipping_firstname'      => $shipping_query->row['shipping_firstname'],
					'shipping_lastname'       => $shipping_query->row['shipping_lastname'],
					'shipping_company'        => $shipping_query->row['shipping_company'],
					'shipping_address_1'      => $shipping_query->row['shipping_address_1'],
					'shipping_address_2'      => $shipping_query->row['shipping_address_2'],
					'shipping_postcode'       => $shipping_query->row['shipping_postcode'],
					'shipping_city'           => $shipping_query->row['shipping_city'],
					'shipping_zone_id'        => $shipping_query->row['shipping_zone_id'],
					'shipping_zone'           => $shipping_query->row['shipping_zone'],
					'shipping_zone_code'      => $shipping_zone_code,
					'shipping_country_id'     => $shipping_query->row['shipping_country_id'],
					'shipping_country'        => $shipping_query->row['shipping_country'],
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
					'supplier_iso_code_2'      => $supplier_iso_code_2,
					'supplier_iso_code_3'      => $supplier_iso_code_3
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
					'supplier_iso_code_2'      => "",
					'supplier_iso_code_3'      => ""
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
			if ($gp!=0)
				$salesPrice = $product['ExchangeValue'] / ((100 - $gp)/100);
					

			if ($gp!=0) {
				$this->db->query("UPDATE  " . DB_PREFIX . "product SET price='". $salesPrice  . "' WHERE product_id = '" . $product['product_id'] . "'");
			}
		}
	}
	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");

		return $query->row;
	}
	
	public function getCurrency($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		return $query->row;
	}
}
?>