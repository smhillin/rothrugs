<?php
class ModelCatalogFilter extends Model {

	public function getFilterDescriptions($filter_group_id){
		$filter_data = array();

		$filter_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int) $filter_group_id . "'");

		foreach($filter_query->rows as $filter){
			$filter_description_data = array();

			$filter_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int) $filter['filter_id'] . "'AND language_id ='". (int)$this->config->get('config_language_id')."'");
			$filter_description_data = $filter_description_query->row;
		

			$filter_data[] = array(
				'filter_id'			 => $filter['filter_id'],
				'to_value'			 => $filter['to_value'],
				'from_value'		 => $filter['from_value'],
				'filter_description' => $filter_description_data['name'],
				'sort_order'		 => $filter['sort_order']
			);
		}

		return $filter_data;
	}
	public function getFilterDataByFilterIds($filter_ids){

		$filter_data = array();
		$filter_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter WHERE filter_id IN('" . (int) $filter_ids . "')");
		foreach($filter_query->rows as $filter){
			$filter_data[] = array(
				'filter_id'	 => $filter['filter_id'],
				'to_value'	 => $filter['to_value'],
				'from_value' => $filter['from_value'],
				'sort_order' => $filter['sort_order']
			);
		}
		return $filter_data;
	}
	public function getAllFilters($filterName){
		$filter_data = array();
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.is_delete <> 1 AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name = '" . $this->db->escape($data['filter_name']) . "'";
		}
		
		$sql .= " ORDER BY f.sort_order ASC";
		$query = $this->db->query($sql);
		foreach ($query->rows as $fts) {
			if(!empty($fts['filter_id'])){
				/*$sqlin = "select count(*) as counter from " . DB_PREFIX . "product as p left join " . DB_PREFIX . "product_filter  as pf on p.product_id = pf.product_id where pf.filter_id = '".$fts['filter_id']."' and p.status = 1";
				$filter_query = $this->db->query($sqlin);
				foreach($filter_query->rows as $filter){
					$fts['product_count'] = $filter['counter'];
				}*/

			}
			$array[$fts['group']][] = $fts;
		}
		return $array;

	}
	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

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
	}
	
}