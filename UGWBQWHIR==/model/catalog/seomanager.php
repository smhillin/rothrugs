<?php
class ModelCatalogSeoManager extends Model {
	
	public function getList($data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "redirect_manager ";

		$sort_data = array(
				'index',
				'times_used'
		);

		$sql .= " ORDER BY `index` ";


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
		
		$urlentry = $this->db->query($sql);

		return $urlentry->rows;
	}

    public function getTotalList($data){
		$urlentry = $this->db->query("SELECT COUNT(fromUrl) AS count FROM " . DB_PREFIX . "redirect_manager ");
		return $urlentry->row['count'];
	}

	public function addUrl($data){
		$this->db->query("TRUNCATE table " . DB_PREFIX . "redirect_manager");
		foreach ($data as $key => $value) {
				$value['fromUrl'] = str_replace('amp;', '', $value['fromUrl']);
				$value['toUrl'] = str_replace('amp;', '', $value['toUrl']);
				$this->db->query("INSERT INTO " . DB_PREFIX . "redirect_manager SET fromUrl = '" .$this->db->escape($value['fromUrl']). "',toUrl = '" .$value['toUrl']. "',status = '" . (int)$value['status'] . "',times_used = 0");
	    	
	    }
	}

	public function getListt($data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "redirectManagerTable ";

		$sort_data = array(
				'date',
				'failedUrl',
				'count'
		);

		$sql .= " ORDER BY `index` ";

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
		
		$urlentry = $this->db->query($sql);
		return $urlentry->rows;
	}

    public function getTotalListt($data){
		$urlentry = $this->db->query("SELECT COUNT(failedUrl) AS count FROM " . DB_PREFIX . "redirectManagerTable ");
		
		return $urlentry->row['count'];
	}

	public function clear() {
		$this->db->query("TRUNCATE table " . DB_PREFIX . "redirectManagerTable ");
	}

	public function updateUrl($data){
		$this->db->query("UPDATE " . DB_PREFIX . "redirect_manager SET fromUrl = '" .$this->db->escape($data['fromUrl']). "',toUrl = '" .$this->db->escape($data['toUrl']). "',status = '" . (int)$data['status'] . "' WHERE `index` = '".(int)$data['id']."'");
	}

	public function reset($data){
		$this->db->query("UPDATE " . DB_PREFIX . "redirect_manager SET times_used = 0 WHERE `index` = '".(int)$data['id']."'");
	}

	public function deleteUrl($data){
		$this->db->query("DELETE FROM " . DB_PREFIX . "redirect_manager  WHERE `index` = '".(int)$data['id']."'");
	}

	public function addUrl1($data){
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "redirect_manager SET fromUrl = '" .$this->db->escape($data['fromUrl']). "',toUrl = '" .$this->db->escape($data['toUrl']). "',status = '" . (int)$data['status'] . "',times_used = 0 ");
	}

	public function createTablesInDatabse() {
		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."redirect_manager'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "redirect_manager` (
                      `index` int(10) COLLATE utf8_bin AUTO_INCREMENT,
                      `status` int(10) COLLATE utf8_bin,
                      `fromUrl` text,
                      `toUrl` text,
                      `times_used` int(10),
                      PRIMARY KEY (`index`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
            $this->db->query($sql);
        }

        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."redirectManagerTable'")->num_rows == 0) {
           $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "redirectManagerTable` (
                      `index` int(10) COLLATE utf8_bin AUTO_INCREMENT,
                      `failedUrl` text,
                      `count` int(10) COLLATE utf8_bin,
                      `add` int(10),
                      `date` date,
                      PRIMARY KEY (`index`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
            $this->db->query($sql);
        }
    }

}
?>