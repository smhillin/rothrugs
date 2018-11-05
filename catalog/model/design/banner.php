<?php
class ModelDesignBanner extends Model {
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image bi LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) WHERE bi.banner_id = '" . (int)$banner_id . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");

		return $query->rows;
	}

	public function getBannerByName($banner_name) {
		$query = $this->db->query("
			SELECT * 
			FROM ".DB_PREFIX."banner_image bi 
			LEFT JOIN ".DB_PREFIX."banner_image_description bid 
				ON (bi.banner_image_id  = bid.banner_image_id) 
			INNER JOIN ".DB_PREFIX."banner b
  				ON (bi.banner_id = b.banner_id)
			WHERE b.name = '".$banner_name."' 
			AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC
		");

		return $query->rows;
	}
}