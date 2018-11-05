<?php
class ModelCatalogSeoReport extends Model {
	
	public function getreport1() {
		$languages = $this->getLanguages();
		$array  = array();
		foreach ($languages as $people => $driver) {

			$products = $this->getProducts($driver['language_id']);
			
			$countsk  = 0;
			$countpd  = 0;
			$countpk  = 0;
			$countpl  = 0;
			
			$countm = count($products);

			foreach ($products as $key => $value) {

				if($this->checkKeyword("product_id",$value['product_id'],$driver['language_id'])) {
					$countsk += 1; 
				}
				if($value['meta_title'] != '') {
					$countpl += 1; 
				}
				if($value['meta_description'] != '') {
					$countpd += 1; 
				}
				if($value['meta_keyword'] != '') {
					$countpk += 1; 
				}
			}
			$array[$driver['language_id']] = array('count' => $countm,'metal' => $countpl,'metad' => $countpd,'metak' => $countpk,'seok' => $countsk,'lname'=>$driver['name'],'image' => $driver['image']);
			
		}
		
		return $array;
	}

	public function getreport2() {
		$languages = $this->getLanguages();
		$array  = array();
		foreach ($languages as $people => $driver) {

			$categories = $this->getCategories($driver['language_id']);
			
			$countsk  = 0;
			$countpd  = 0;
			$countpl  = 0;
			$countpk  = 0;
			
			$countm = count($categories);

			foreach ($categories as $key => $value) {

				if($this->checkKeyword("category_id",$value['category_id'],$driver['language_id'])) {
					$countsk += 1; 
				}
				if($value['meta_title'] != '') {
					$countpl += 1; 
				}
				if($value['meta_description'] != '') {
					$countpd += 1; 
				}
				if($value['meta_keyword'] != '') {
					$countpk += 1; 
				}
			}
			$array[$driver['language_id']] = array('count' => $countm,'metal' => $countpl,'metad' => $countpd,'metak' => $countpk,'seok' => $countsk,'lname'=>$driver['name'],'image' => $driver['image']);
			
		}
	
		return $array;

	}

	public function getreport3() {
		$languages = $this->getLanguages();
		$array  = array();
		foreach ($languages as $people => $driver) {

			$informations = $this->getInformationPages($driver['language_id']);
		
			$countsk  = 0;
			$countpd  = 0;
			$countpk  = 0;
			$countpl  = 0;
			
			$countm = count($informations);

			foreach ($informations as $key => $value) {

				if($this->checkKeyword("information_id",$value['information_id'],$driver['language_id'])) {
					$countsk += 1; 
				}

				$value1 = $this->getInfo($value['information_id'],$driver['language_id']);
				
				if($value1->row['title'] != '') {
					$countpl += 1; 
				}
				if($value1->row['meta_description'] != '') {
					$countpd += 1; 
				}
				if($value1->row['meta_keyword'] != '') {
					$countpk += 1; 
				}
			}
			$array[$driver['language_id']] = array('count' => $countm,'metal' => $countpl,'metad' => $countpd,'metak' => $countpk,'seok' => $countsk,'lname'=>$driver['name'],'image' => $driver['image']);
			
		}
		
		return $array;

	}

	public function getreport4() {

		$languages = $this->getLanguages();
		$array  = array();
		foreach ($languages as $people => $driver) {

			$manufacturers = $this->getManufacturers($driver['language_id']);
		
			$countsk  = 0;
			$countpd  = 0;
			$countpk  = 0;
			$countpl  = 0;
			
			$countm = count($manufacturers);

			foreach ($manufacturers as $key => $value) {

				if($this->checkKeywordm("manufacturer_id",$value['manufacturer_id'])) {
					$countsk += 1; 
				}

				$value1 = $this->getManu($value['manufacturer_id'],$driver['language_id']);
				
				if($value1->num_rows && $value1->row['meta_description'] != '') {
					$countpd += 1; 
				}
				if($value1->num_rows && $value1->row['title'] != '') {
					$countpl += 1; 
				}
				if($value1->num_rows && $value1->row['meta_keywords'] != '') {
					$countpk += 1; 
				}
			}
			$array[$driver['language_id']] = array('count' => $countm,'metal' => $countpl,'metad' => $countpd,'metak' => $countpk,'seok' => $countsk,'lname'=>$driver['name'],'image' => $driver['image']);
			
		}
		
		return $array;

	}

	public function getreport5() {
		$languages = $this->getLanguages();
		$array  = array();
		foreach ($languages as $people => $driver) {
			$number = $this->getGeneral($driver['language_id']);
			$array[$driver['language_id']] = array('count' => $number,'lname'=>$driver['name'],'image' => $driver['image']);
		}
		
		return $array;

	}

	public function getreport6() {
		$returnarray = array();
		$array = $this->db->query("SELECT keyword,count(keyword) as count FROM " . DB_PREFIX . "url_alias group by keyword having count(*) >= 2");
		$returnarray['totalcount'] = $array->num_rows;
		$returnarray['details'] = array();
		foreach ($array->rows as $key => $value) {
			$returnarray['details'][$key]['keyword'] = $value['keyword'];
			$returnarray['details'][$key]['count'] = $value['count'];
			$links = $this->db->query("SELECT *  FROM " . DB_PREFIX . "url_alias WHERE keyword = '".$value['keyword']."'")->rows;
			foreach ($links as $key1 => $value1) {
				$returnarray['details'][$key]['links'][] = $value1['query'];
			}
		}
		
		return $returnarray;
	}

	public function getreport7($table) {
		$returnarray = array();
		$array = $this->db->query("SELECT meta_title,count(meta_title) as count FROM " . DB_PREFIX . "".$table." group by meta_title having count(*) >= 2 AND meta_title != ''");
		$returnarray['totalcount'] = $array->num_rows;
		$returnarray['details'] = array();
		foreach ($array->rows as $key => $value) {
			$returnarray['details'][$key]['meta_title'] = $value['meta_title'];
			$returnarray['details'][$key]['count'] = $value['count'];
		}
		return $returnarray;
	}

	public function getreport8($table) {
		$returnarray = array();
		$array = $this->db->query("SELECT meta_description,count(meta_description) as count FROM " . DB_PREFIX . "".$table." group by meta_description having count(*) >= 2 AND meta_description != ''");
		$returnarray['totalcount'] = $array->num_rows;
		$returnarray['details'] = array();

		foreach ($array->rows as $key => $value) {
			$returnarray['details'][$key]['meta_description'] = $value['meta_description'];
			$returnarray['details'][$key]['count'] = $value['count'];
		}
		return $returnarray;
	}

	public function getreport9($table) {
		$returnarray = array();
		$array = $this->db->query("SELECT meta_keyword,count(meta_keyword) as count FROM " . DB_PREFIX . "".$table." group by meta_keyword having count(*) >= 2 AND meta_keyword != ''");
		$returnarray['totalcount'] = $array->num_rows;
		$returnarray['details'] = array();
		foreach ($array->rows as $key => $value) {
			$returnarray['details'][$key]['meta_keyword'] = $value['meta_keyword'];
			$returnarray['details'][$key]['count'] = $value['count'];
		}
		return $returnarray;
	}

	

	private function getProducts($lid) {
		$query = $this->db->query("SELECT p.product_id,pd.meta_description,pd.meta_title,pd.meta_keyword,pd.language_id  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) WHERE pd.language_id = '".(int)$lid."' ORDER BY pd.name ASC");
		return $query->rows;
	}

	private function getCategories($lid) {
		$query = $this->db->query("SELECT c.category_id, cd.meta_title, cd.meta_description, cd.meta_keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id=c2s.category_id) WHERE cd.language_id = '" . (int)$lid . "' ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}

	private function getInfo($id,$lid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$id . "' AND `language_id` = '". (int)$lid ."' ");    
		return $query;
	}

	private function getManu($id,$lid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_data WHERE type = 'manufacturer' AND id = '" . (int)$id . "' AND `language_id` = '". (int)$lid ."' ");    
		return $query;
	}

	private function getGeneral($lid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'route=%' ");    
		return $query->num_rows;
	}

	private function checkKeyword($name, $id,$lid) {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = '".$name."=".$id."' AND `lang` = '". (int)$lid ."' ");    
		if($query->num_rows){
			return 1;
		} else {
			return 0;
		}
	}

	private function checkKeywordm($name, $id) {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = '".$name."=".$id."'");    
		if($query->num_rows){
			return 1;
		} else {
			return 0;
		}
	}

	private function getInformationPages(){
		$query = $this->db->query("SELECT id.information_id FROM " . DB_PREFIX . "information_description id LEFT JOIN " . DB_PREFIX . "information i ON (id.information_id = i.information_id)  LEFT JOIN " . DB_PREFIX . "information_to_store its ON (id.information_id = its.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title ASC");
		return $query->rows;
	}

	private function getManufacturers() {
		$query = $this->db->query("SELECT m.manufacturer_id, m.name FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id=m2s.manufacturer_id) ORDER BY m.name ASC");
		return $query->rows;
	}

	public function getLanguages() {

			$sql = "SELECT name, image, language_id FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order ASC";
			$query = $this->db->query($sql);
			return $query->rows;
		
	}

}
?>