<?php
class ModelPomgmtSupplier extends Model {

	function randomString() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		for ($i = 0; $i < 32; $i++) {
			$n = rand(0, strlen($alphabet)-1); //use strlen instead of count
			$pass[$i] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

	public function addSupplier($data) {
		if (!isset($data['tax_rate_id']))
			$data['tax_rate_id'] = "0";
		
		$user_created = false;
		$cred_token="";
		if (!empty($data['username'])) {
			$this->load->model('user/user');						
			$user_info = $this->model_user_user->getUserByUsername($data['username']);
			if (empty($user_info)) {
				//Add new user		
				$this->load->model('user/user_group');
				$user_group_info = $this->model_user_user_group->getUserGroupByName('Suppliers');
				if (empty($user_group_info)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = 'Suppliers'");
					$groupID = $this->db->getLastId();
				}
				else
					$groupID = $user_group_info['user_group_id'];
					
				$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) 
				. "', password = '" . $this->db->escape(md5('a234e4f011')) 
				. "', firstname = '" . $this->db->escape($data['firstname']) 
				. "', lastname = '" . $this->db->escape($data['lastname']) 
				. "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" 
				. (int)$groupID . "', status = '" 
				. (int)$data['status'] . "', date_added = NOW()");
				$userrefid = $this->db->getLastId();
				$user_created = true;
				$cred_token = $this->randomString();
			}
			else
				$userrefid = $user_info['user_id'];
		}
		else
			$userrefid = null;
		
      	$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "', email = '" .$data['email']."',"
			." firstname = '" . $this->db->escape($data['firstname']) . "',"
			." lastname = '" . $this->db->escape($data['lastname']) . "',"
			." comments = '" . $this->db->escape($data['comments']) . "',"
			." notes = '" . $this->db->escape($data['notes']) . "',"
			." address1 = '" . $this->db->escape($data['address1']) . "',"
			." address2 = '" . $this->db->escape($data['address2']) . "',"
			." city = '" . $this->db->escape($data['city']) . "',"			
			." postcode = '" . $this->db->escape($data['postcode']) . "',"
			." country_id = " . $this->db->escape($data['country_id']) . ","
			." zone_id = " . $this->db->escape($data['zone_id']) . ","
			." telephone = '" . $this->db->escape($data['telephone']) . "',"
			." tax = " . $this->db->escape($data['tax']) . ","
			." status = " . $this->db->escape($data['status']) . ","			
			." currency_id = " . $this->db->escape($data['currency_id']) . ","
			." maxshipdays = " . (int) $this->db->escape($data['maxshipdays']) . ","
			." cred_token = '" . $cred_token . "',"
			. "exportpath = '" . $this->db->escape($data['exportpath']) . "'," 
			. "fileattach = '" . $this->db->escape($data['fileattach']) . "'," 
			. "supplierurl = '" . $this->db->escape($data['supplierurl']) . "'," 
			. "orderurl = '" . $this->db->escape($data['orderurl']) . "'," 
			. "im = '" . $this->db->escape($data['im']) . "'," 
			." gp_percent = " . (float) $data['gp_percent'] . ","
			." user_id = '" . (int) $userrefid . "',"
			." dropship_fee = " . (float) $this->db->escape($data['dropshipfee']) . ","
			." itemdel_fee = " . (float) $this->db->escape($data['itemdel_fee']) . ","
			."date_added = NOW()");
		
		$supplier_id = $this->db->getLastId();
		
		if (isset($data['supplier_store'])) {
			foreach ($data['supplier_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_to_store SET supplier_id = '" . (int)$supplier_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
				
		if($user_created )
			return $userrefid;
		else
			return 0;
		$this->cache->delete('supplier');
		
	}
	
	public function editSupplier($supplier_id, $data) {
		if (!isset($data['tax_rate_id']))
			$data['tax_rate_id'] = "0";
		$user_created = false;
		$cred_token="";
		if (!empty($data['username'])) {
			$this->load->model('user/user');						
			$user_info = $this->model_user_user->getUserByUsername($data['username']);
			if (empty($user_info)) {
				//Add new user		
				$this->load->model('user/user_group');
				$user_group_info = $this->model_user_user_group->getUserGroupByName('Suppliers');
				if (empty($user_group_info)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = 'Suppliers'");
					$groupID = $this->db->getLastId();
				}
				else
					$groupID = $user_group_info['user_group_id'];
					
				$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) 
				. "', password = '" . $this->db->escape(md5('a234e4f011')) 
				. "', firstname = '" . $this->db->escape($data['firstname']) 
				. "', lastname = '" . $this->db->escape($data['lastname']) 
				. "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" 
				. (int)$groupID . "', status = '" 
				. (int)$data['status'] . "', date_added = NOW()");
				$userrefid = $this->db->getLastId();
				$user_created = true;
				$cred_token = $this->randomString();
			}
			else
				$userrefid = $user_info['user_id'];
		}
		else
			$userrefid = null;
			
		$this->db->query("UPDATE " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "', email = '" .$data['email']."',"
			." firstname = '" . $this->db->escape($data['firstname']) . "',"
			." lastname = '" . $this->db->escape($data['lastname']) . "',"
			." comments = '" . $this->db->escape($data['comments']) . "',"
			." notes = '" . $this->db->escape($data['notes']) . "',"
			." address1 = '" . $this->db->escape($data['address1']) . "',"
			." address2 = '" . $this->db->escape($data['address2']) . "',"
			." city = '" . $this->db->escape($data['city']) . "',"			
			." postcode = '" . $this->db->escape($data['postcode']) . "',"
			." country_id = " . $this->db->escape($data['country_id']) . ","
			." zone_id = " . $this->db->escape($data['zone_id']) . ","
			." telephone = '" . $this->db->escape($data['telephone']) . "',"
			." tax = " . $this->db->escape($data['tax']) . ","
			." tax_rate_id = " . $this->db->escape($data['tax_rate_id']) . ","
			." status = " . $this->db->escape($data['status']) . ","
			." maxshipdays = " . (int) $this->db->escape($data['maxshipdays']) . ","
			." user_id = '" . (int) $userrefid . "',"
			." gp_percent = " . (float) $data['gp_percent'] . ","
			." dropship_fee = " . (float) $this->db->escape($data['dropshipfee']) . ","
			." itemdel_fee = " . (float) $this->db->escape($data['itemdel_fee']) . ","
			." cred_token = '" . $cred_token . "',"
			. "exportpath = '" . $this->db->escape($data['exportpath']) . "'," 
			. "fileattach = '" . $this->db->escape($data['fileattach']) . "'," 
			. "supplierurl = '" . $this->db->escape($data['supplierurl']) . "'," 
			. "orderurl = '" . $this->db->escape($data['orderurl']) . "'," 
			. "im = '" . $this->db->escape($data['im']) . "'," 
			." currency_id = " . $this->db->escape($data['currency_id']) 
			." WHERE supplier_id = '" . (int)$supplier_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		if (isset($data['supplier_store'])) {
			foreach ($data['supplier_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_to_store SET supplier_id = '" . (int)$supplier_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'supplier_id=" . (int)$supplier_id. "'");
		
		$this->cache->delete('supplier');
		
		if($user_created )
			return $userrefid;
		else
			return 0;
	}
	
	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'supplier_id=" . (int)$supplier_id . "'");
			
		$this->cache->delete('supplier');
	}	
	public function getSupplierCurrency($supplier_id)
	{
		$query = $this->db->query("SELECT concat(title ,'|' , value,'|' , code,'|' , c.currency_id) FROM " . DB_PREFIX . "supplier s INNER JOIN  " . DB_PREFIX . "currency c ON s.currency_id = c.currency_id WHERE supplier_id='" . (int)$supplier_id . "'");
		
		return $query->row;
	}
	
	public function getSupplierGP($supplier_id)
	{
		$query = $this->db->query("SELECT gp_percent FROM " . DB_PREFIX . "supplier  WHERE supplier_id='" . (int)$supplier_id . "'");
		return $query->row;
	}
	
	public function getSupplierTaxRate($supplier_id)
	{
		$query = $this->db->query("SELECT rate FROM " . DB_PREFIX . "supplier s INNER JOIN " . DB_PREFIX . "tax_rate t ON s.tax_rate_id = t.tax_rate_id WHERE s.supplier_id='".$supplier_id."'");
		
		return $query->row;
	}
	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT DISTINCT supplier.*, username, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'supplier_id=" . (int)$supplier_id . "') AS keyword FROM " . DB_PREFIX . "supplier as supplier LEFT JOIN " . DB_PREFIX . "user as user ON supplier.user_id = user.user_id WHERE supplier_id = '" . (int)$supplier_id . "'");
		return $query->row;
	}
	
	public function getSupplierForUser($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier WHERE user_id = '" . (int)$user_id . "'");
		return $query->row;
	}
	
	public function resetSupplierCredToken($supplier_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET cred_token = '' WHERE supplier_id='".$supplier_id."'");
	}
	
	
	public function getSuppliers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "supplier a inner join ".DB_PREFIX."currency b ON a.currency_id=b.currency_id";
		
		
		$sql .= " WHERE true";
		
		if (!empty($data['filter_id'])) {
			$sql .= " AND supplier_id = '" . $this->db->escape($data['filter_id']) . "'";
		}
		
		if (!empty($data['filter_supplier'])) {
			$sql .= " AND LCASE(name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_supplier'])) . "%'";
		}

		if (!empty($data['filter_currency_id'])) {
			$sql .= " AND a.currency_id='" . (int)$data['filter_currency_id'] . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND LCASE(email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
		}
		
		$sort_data = array(
			'name',
			'supplier_id',
			'title',
			'email',
			'sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	}
	
	public function getSupplierStores($supplier_id) {
		$supplier_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_to_store WHERE supplier_id = '" . (int)$supplier_id . "'");

		foreach ($query->rows as $result) {
			$supplier_store_data[] = $result['store_id'];
		}
		
		return $supplier_store_data;
	}
	
	public function getTotalSuppliers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier");
		
		return $query->row['total'];
	}	
	
	public function isUserSupplier() {
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "supplier WHERE user_id=" . $this->user->getId());
		if ($query->num_rows>0)
			return true;
		else
			return false;
	}
}
?>