<?php
class ModelPomgmtReports extends Model {
//TODO : add db  prefix every where


	public function getStores() {
		$query = $this->db->query("select 0 as store_id,\"Default\" as `name`,\"\" as url,\"\" as `ssl` UNION (SELECT * FROM " . DB_PREFIX . "store ORDER BY url) ORDER BY `name`");

		$store_data = $query->rows;
	
		return $store_data;
	}
	public function getSupplierReportingTotalsOrderWise($data = array()) { 
	

		$sql = "SELECT  po.order_id,po.total+delivery_charge+vat as pur_value,poinvoice_ref,inv_value,sales_order_id
				FROM " . DB_PREFIX . "purchase_order po
				LEFT JOIN `" . DB_PREFIX . "order` o
				ON o.order_id = po.sales_order_id
				WHERE true";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(po.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(po.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_store_id'])) {
			$sql .= " AND po.store_id = '".$data['filter_store_id']."'";
		}
	
		if (!empty($data['filter_group'])) {
			$sql .= " AND agent_type='". $data['filter_group'] . "'";
			
		} 

		//die($sql);
		$query = $this->db->query($sql);
	
		return $query->num_rows;
	}	

	public function getSupplierReportingOrderWise($data = array()) { 
		$sql = "SELECT  po.order_id,po.total+delivery_charge+vat as pur_value,poinvoice_ref,inv_value,sales_order_id
				FROM " . DB_PREFIX . "purchase_order po
				LEFT JOIN `" . DB_PREFIX . "order` o
				ON o.order_id = po.sales_order_id
				WHERE true";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(po.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(po.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_store_id'])) {
			$sql .= " AND po.store_id = '".$data['filter_store_id']."'";
		}
	
		if (!empty($data['filter_group'])) {
			$sql .= " AND agent_type='". $data['filter_group'] . "'";
			
		} 
		
		//die($sql);
		$query = $this->db->query($sql);
		
		
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
	}	

	public function getSupplierReportingTotalsStoreWise($data = array()) { 
	

		$sql = "SELECT po.store_name,count(po.order_id) as ordercount,SUM(po.total+delivery_charge+vat) as pur_value,SUM(inv_value) as inv_value 
				FROM " . DB_PREFIX . "purchase_order po
				LEFT JOIN `" . DB_PREFIX . "order` o
				ON o.order_id = po.sales_order_id
				WHERE true";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(po.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(po.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_store_id'])) {
			$sql .= " AND po.store_id = '".$data['filter_store_id']."'";
		}
	
		if (!empty($data['filter_group'])) {
			$sql .= " AND agent_type='". $data['filter_group'] . "'";
			
		} 

		//die($sql);
		$query = $this->db->query($sql);
	
		return $query->num_rows;
	}	

	public function getSupplierReportingStoreWise($data = array()) { 
		$sql = "SELECT po.store_name,count(po.order_id) as ordercount,SUM(po.total+delivery_charge+vat) as pur_value,SUM(inv_value) as inv_value 
				FROM " . DB_PREFIX . "purchase_order po
				LEFT JOIN `" . DB_PREFIX . "order` o
				ON o.order_id = po.sales_order_id
				WHERE true";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(po.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(po.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_store_id'])) {
			$sql .= " AND po.store_id = '".$data['filter_store_id']."'";
		}
	
		if (!empty($data['filter_group'])) {
			$sql .= " AND agent_type='". $data['filter_group'] . "'";
			
		} 
		$sql .= " GROUP BY po.store_name";
		
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
		
		

		
		//die($sql);
		$query = $this->db->query($sql);
	
		return $query->rows;
	}	

}
?>