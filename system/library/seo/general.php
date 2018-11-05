<?php
class SeoGeneral extends SeoAbstract {

	public function load() {
		if ($this->_db->query("SHOW TABLES LIKE '". DB_PREFIX ."seo_data'")->num_rows == 0) {
			echo "<pre>The Seo Module is not installed completely. The last step is left incomplete.<pre>";
			echo "Go to admin > catalog > seo > seo autogenerate  to complete the installation";
			exit();
		}
		
		$query2 = $this->_db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . $this->_db->escape($this->_id) . "'");

		$this->_seo_keyword = isset($query2->row['keyword']) ? $query2->row['keyword'] : '';
		$this->_url_query = isset($query2->row['query']) ? $query2->row['query'] : '';	
		$this->_url_alias_id = isset($query2->row['url_alias_id']) ? $query2->row['url_alias_id'] : '';	
		

	}
	
	public function save() {
		
		$db = $this->_registry->get('db');

		$count = $db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $db->escape($this->_url_query) . "'")->row['count'];
		
		if($count) {
			$db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $db->escape($this->_url_query) . "'");
		}
		
		$db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = '" . $db->escape($this->_url_query) . "',
					`keyword` = '" . $db->escape($this->_seo_keyword) . "'");
		
	}

}