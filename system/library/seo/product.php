<?php
class SeoProduct extends SeoAbstract {

	private $_image;

	private $_name;

	private $_model;
	
	public function getModel() {
		return $this->_model;
	}


	public function getImage() {
		return $this->_image;
	}

	public function getName() {
		return $this->_name;
	}

	public function load() {

		$sql = "SELECT  pd.meta_keyword, pd.meta_description, pd.meta_title, pd.tag, pd.language_id FROM `" . DB_PREFIX . "product_description` pd
		WHERE pd.product_id = '" . (int) $this->_id . "'";
		
		$query = $this->_db->query($sql);
		foreach($query->rows as $row) {
				
			$this->_title[$row['language_id']] = $row['meta_title'];
			$this->_meta_keywords[$row['language_id']] = $row['meta_keyword'];
			$this->_meta_description[$row['language_id']] = $row['meta_description'];
			$this->_tags[$row['language_id']] = $row['tag'];
			$newQ = $this->_db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$this->_id . "' AND lang = '".(int)$row['language_id']."'");		
			$this->_seo_keyword['language_id'][$row['language_id']] = isset($newQ->row['keyword']) ? $newQ->row['keyword'] : '';
		}

		$config = $this->_registry->get('config');
		
		$query = $this->_db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd
		ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$this->_id . "'
		AND pd.language_id = '" . (int)$config->get('config_language_id') . "'");

		$this->_name = isset($query->row['name']) ? $query->row['name'] : '';
		$this->_model = isset($query->row['model']) ? $query->row['model'] : '';
		$this->_image = isset($query->row['image']) ? $query->row['image'] : '';
		

	}
	
	public function save() {
		
		$db = $this->_registry->get('db');
		
		$query = $db->query("SELECT url_alias_id FROM `" . DB_PREFIX . "seo_data` 
							WHERE id = '" . $db->escape($this->_id) . "' AND type = 'product' ");
		
		if($query->num_rows) {
			$db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . (int) $query->row['url_alias_id'] . "'");
			$db->query("DELETE FROM `" . DB_PREFIX . "seo_data` WHERE id = '" . $db->escape($this->_id) . "' AND type= 'product' ");
		}
		
		$url_alias_id = 0;
		
		if($this->_seo_keyword){

			$sql = "SELECT COUNT(*) as count FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $db->escape($this->_url_query) . "'";
			$already_existing = $db->query($sql)->row['count'];
			
			if($already_existing) {
				$db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $db->escape($this->_url_query) . "'");
			}

			foreach ($this->_seo_keyword as $key => $value) {
				if ($value != "") {
				 $db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = '" . $db->escape($this->_url_query) . "',
						`keyword` = '" . $db->escape($value) . "', lang ='".(int)$key."'");
				}
			}
			
			$url_alias_id = $db->getLastId();
		}
		
		foreach($this->_title as $language_id => $row ){			
			$db->query("UPDATE `" . DB_PREFIX . "product_description` SET
						`meta_keyword` = '" . $db->escape($this->_meta_keywords[$language_id]) . "',
						`meta_title` = '" . $db->escape($this->_title[$language_id]) . "',
						`tag` = '" . $db->escape($this->_tags[$language_id]) . "',
						`meta_description` = '" . $db->escape($this->_meta_description[$language_id]) . "'
						WHERE product_id = '" . $db->escape($this->_id) . "' AND language_id = '". (int) $language_id ."'
						");
		} 
		
		
		//delete product cache
		$cache = $this->_registry->get('cache');
		$cache->delete('product');
		
	}

}
?>