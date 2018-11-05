<?php
/**
 * This file is part of FreeCart.
 *
 * @copyright  sv2109 <sv2109@gmail.com>
 * @link http://freecart.pro
*/

class ModelCatalogSearchSuggestion extends Model {
	/*
	 * Modification of standart getProducts() method, added support $data['filter_model'] and $data['filter_sku']
	 */

	public function getProducts($data = array()) {
		$this->load->model('catalog/product');
		
		$search_suggestion_options = $this->config->get('search_suggestion_options');

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" OR ", $implode) . "";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'rating',
			'p.sort_order',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}
	/*
	 * Modification of standart getTotalProducts() method, added support $data['filter_model'] and $data['filter_sku']
	 */

	public function getTotalProducts($data = array()) {
		
		$search_suggestion_options = $this->config->get('search_suggestion_options');

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";

			$implode = array();

			$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

			foreach ($words as $word) {
				$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
			}

			if ($implode) {
				$sql .= " " . implode(" OR ", $implode) . "";
			}

			$sql .= ")";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}