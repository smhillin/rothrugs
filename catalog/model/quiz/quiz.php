<?php
class ModelQuizQuiz extends Model {
	public function getCategory($combination) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_to_combination` WHERE combination='".$combination."'");

		return $query->row['category_id'];
	}

	public function getkeyword($category_id){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE query='category_id=".$category_id."'");

		return $query->row['keyword'];
	}
}