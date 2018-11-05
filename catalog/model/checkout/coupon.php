<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($code) {
		$status = true;

		$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

		if ($coupon_query->num_rows) {
			if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
				$status = false;
			}

			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = false;
			}

			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = array();

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			$cpq = "SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'";

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}

			// Manufacturers
			$coupon_manufacturer_data = array();

			$coupon_manufacturer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_manufacturer` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			$cmq = "SELECT * FROM `" . DB_PREFIX . "coupon_manufacturer` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'";


			foreach ($coupon_manufacturer_query->rows as $manufacturer) {
				$coupon_manufacturer_data[] = $manufacturer['manufacturer_id'];
			}

			// Categories
			$coupon_category_data = array();

			$coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			$ccq = "SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'";

			foreach ($coupon_category_query->rows as $category) {
				$coupon_category_data[] = $category['category_id'];
			}

			$product_data = array();
			//$product_discount_data = array();

			if ($coupon_product_data || $coupon_category_data || $coupon_manufacturer_data) {
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");

						if ($coupon_category_query->row['total']) {
							$product_data[] = $product['product_id'];

							continue;
						}
					}
					
					$product_mn_query = $this->db->query("SELECT manufacturer_id FROM ".DB_PREFIX."product WHERE `product_id` = '" . (int)$product['product_id'] . "'");
					$product['manufacturer_id'] = $product_mn_query->row['manufacturer_id'];


					if (in_array($product['manufacturer_id'], $coupon_manufacturer_data)) {
						$product_data[] = $product['product_id'];

						/*if($coupon_query->row['coupon_id'] == 31){
							if($product['manufacturer_id'] == 12)
							{
								$product_discount_data[$product['product_id']] = 35;
							}
							elseif($product['manufacturer_id'] == 15)
							{
								$product_discount_data[$product['product_id']] = 30;
							}
							elseif($product['manufacturer_id'] == 13)
							{
								$product_discount_data[$product['product_id']] = 15;
							}
							elseif($product['manufacturer_id'] == 11)
							{
								$product_discount_data[$product['product_id']] = 20;
							}
						}*/
						continue;
					}

					
				}

				

				if (!$product_data) {
					$status = false;
				}
			}
		} else {
			$status = false;
		}

		if($status){
			if($coupon_query->row['coupon_id'] == 33){
				if($this->cart->getSubTotal() >= 400 && $this->cart->getSubTotal() <= 599){
					$coupon_query->row['discount'] = 75;
				}
				if($this->cart->getSubTotal() >= 600 && $this->cart->getSubTotal() <= 799){
					$coupon_query->row['discount'] = 100;
				}
				if($this->cart->getSubTotal() >= 800 && $this->cart->getSubTotal() <= 1000){
					$coupon_query->row['discount'] = 150;
				}
				if($this->cart->getSubTotal() > 1000){
					$coupon_query->row['discount'] = 200;
				}
			}
		}

		if ($status) {
			return array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $product_data,
				//'product_discount_data'       => $product_discount_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
		}
	}
}