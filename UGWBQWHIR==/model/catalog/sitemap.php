<?php
class ModelCatalogSitemap extends Model {	
	public function generate() {
		if($this->config->get("config_secure")) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
		
		$this->load->language('catalog/sitemap');
		$output = '';
		$on = $this->config->get('nerdherd_direct_links');
		$length = $this->getLanguages();
		$fp = fopen("../sitemap.xml", "w+");
		if(count($length) == 1) {
			fwrite($fp, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
			fwrite($fp, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" \r\n xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\" \r\n>");
			fwrite($fp, $this->getGeneral($server));
			fwrite($fp, $this->getCategories($on,$server));
			fwrite($fp, $this->getProducts($server));
			fwrite($fp, $this->getInformationPages($server));
			fwrite($fp, $this->getManufacturers($server));
		} else {
			fwrite($fp, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
			fwrite($fp, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" \r\n xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\" \r\n  xmlns:xhtml=\"http://www.w3.org/1999/xhtml\">\r\n");
			fwrite($fp, $this->getMGeneral($server));
			fwrite($fp, $this->getMCategories($length,$on,$server));
			fwrite($fp, $this->getMProducts($length,$server));
			fwrite($fp, $this->getMInformationPages($length,$server));
			fwrite($fp, $this->getMManufacturers($server));
		}
		fwrite($fp, "</urlset>");
		fclose($fp);
	}
	protected function getCategories($on,$server){
		$output = '';
		$categories = $this->getAllCategories();
		foreach ($categories as $key => $value) {
			$path = $key;
			$keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".$path."' AND lang ='".(int)$this->config->get('config_language_id')."' ");
			$lm =  $this->db->query("SELECT date_modified FROM " . DB_PREFIX . "category WHERE category_id = '".$path."'")->row['date_modified'];
			$lm = date("Y-m-d", strtotime($lm));
			if($keyword->num_rows){
				$keywordvalue = $keyword->row['keyword'];
				while ($value != 0 && (!$on)) {
				  $path .= "_".$value;
				  $keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".$value."' AND lang ='".(int)$this->config->get('config_language_id')."' ");
				  if($keyword->num_rows){
				  	$keywordvalue = $keyword->row['keyword']."/".$keywordvalue;
				  } else {
					$output .= $this->generateLinkNode($server . 'index.php?route=product/category&path=' . $path,$lm, "yearly", "0.9",$image="",$server);
				  	break;
				  }
				  $value = isset($categories[$value])?$categories[$value]:0;
				}
				$output .= $this->generateLinkNode($server.$keywordvalue,$lm, "yearly", "0.9",$image="",$server);
			} else {
				while ($value != 0) {
					$path .= "_".$value;
					$value = isset($categories[$value])?$categories[$value]:0;
				}
				$output .= $this->generateLinkNode($server . 'index.php?route=product/category&path=' . $path,$lm, "yearly", "0.9",$image="",$server);
			}
		}
		return $output;
	}

	public function getAllCategories() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		foreach ($query->rows as $key => $value) {
			$results[$value['category_id']] = $value['parent_id']; 
		}
		return $results;
	}
	
	public function getProducts($server) {
		$output = '';
		$results =  $this->db->query("SELECT DISTINCT(product_id),date_modified,image FROM " . DB_PREFIX . "product  WHERE status = 1");
		foreach ($results->rows as $key => $value) {
			$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=".$value['product_id']."' AND lang = '".(int)$this->config->get('config_language_id')."'");
			$lm = date("Y-m-d", strtotime($value['date_modified']));
			if($url->num_rows) {
				$output .= $this->generateLinkNode($server. $url->row['keyword'],$lm, "Monthly", "1.0",$value['image'],$server);
			} else {
				$output .= $this->generateLinkNode($server . 'index.php?route=product/product&product_id=' . $value['product_id'],$lm,"Monthly", "1.0",$value['image'],$server);
			}
		}
		return $output;
	}
	
	protected function getInformationPages($server) {
		$output = '';
		$this->load->model('catalog/information');
		foreach ($this->model_catalog_information->getInformations() as $result) {
			$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=".$result['information_id']."' AND lang = '".(int)$this->config->get('config_language_id')."'");
			if($url->num_rows) {
				$output .= $this->generateLinkInNode($server. $url->row['keyword'], "yearly", "0.8");
			} else {
				$output .= $this->generateLinkInNode($server .  'index.php?route=information/information&information_id=' . $result['information_id'],"yearly","0.8");
			}
		}
		return $output;
	}


	protected function getMCategories($length,$on,$server) {
		$output = '';
		$categories = $this->getMAllCategories();
		foreach ($categories as $key => $value) {
			$links = array();
			$path = $key;
			$cid = $key;
			$daya  =  $value;
			$lm =  $this->db->query("SELECT date_modified FROM " . DB_PREFIX . "category WHERE category_id = '".$path."'")->row['date_modified'];
			$lm = date("Y-m-d", strtotime($lm));
			$lang = $this->getLanguages();
			foreach ($lang as $tp => $langdetails) {
				$path = $cid;
				$value = $daya;
				$keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".$path."' AND lang ='".(int)$langdetails['language_id']."' ");
				if(!$keyword->num_rows){

					while ($value != 0) {
						$path .= "_".$value;
						$value = isset($categories[$value])?$categories[$value]:0;
					}
					$link = $server.'index.php?route=product/category&path=' . $path;
					$links[$cid][] = array('link' => $link,'code' => $langdetails['code']);
				} else {
					$keywordvalue = $keyword->row['keyword'];
					while ($value != 0 && (!$on)) {
					  $path .= "_".$value;
					  $keyword = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".$value."' AND lang ='".(int)$langdetails['language_id']."' ");
					  if($keyword->num_rows){
					  	$keywordvalue = $keyword->row['keyword']."/".$keywordvalue;
					  } else {
					  	$output .= $this->generateLinkNode($server . 'index.php?route=product/category&path=' . $path, $lm, "yearly", "0.9",$image="",$server);
					  	break 2;
					  }
					  $value = isset($categories[$value])?$categories[$value]:0;
					}
					$link = $server . $keywordvalue;
					$links[$cid][] = array('link' => $link,'code' => $langdetails['code']);
				}
			}
			$output .= $this->generateLinkMNode($links,$lm,"yearly", "1.0","",$server);
		
		}
		return $output;
	}

	public function getMAllCategories() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		foreach ($query->rows as $key => $value) {
			$results[$value['category_id']] = $value['parent_id']; 
		}
		return $results;
	}
	
	public function getMProducts($length,$server) {
		$output = '';
		$results =  $this->db->query("SELECT DISTINCT(product_id),date_modified,image FROM " . DB_PREFIX . "product  WHERE status = 1");
		foreach ($results->rows as $key => $value) {
			$links = array();
			$value['date_modified'] = date("Y-m-d", strtotime($value['date_modified']));
			$lang = $this->getLanguages();
			foreach ($lang as $tp => $langdetails) {
				$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=".$value['product_id']."' AND lang = '".(int)$langdetails['language_id']."'");
				if($url->num_rows) {
					$keywordvalue = $url->row['keyword'];
					$link = $server . $keywordvalue;
					$links[$value['product_id']][] = array('link' => $link,'code' => $langdetails['code']);
				} else {
					$link = $server . 'index.php?route=product/product&product_id=' . $value['product_id'];
					$links[$value['product_id']][] = array('link' => $link,'code' => $langdetails['code']);
				}
			}
			$output .= $this->generateLinkMNode($links, $value['date_modified'],"yearly", "1.0",$value['image'],$server);
		}
		return $output;
	}
	
	protected function getMInformationPages($length,$server) {
		$output = '';
		$this->load->model('catalog/information');
		foreach ($this->model_catalog_information->getInformations() as $result) {
			$links = array();
			$lang = $this->getLanguages();
			foreach ($lang as $tp => $langdetails) {
				$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=".$result['information_id']."' AND lang = '".(int)$langdetails['language_id']."'");
				if($url->num_rows) {
					$keywordvalue = $url->row['keyword'];
					$link = $server . $keywordvalue;
					$links[$result['information_id']][] = array('link' => $link,'code' => $langdetails['code']);
				} else {
					$link = $server .  'index.php?route=information/information&information_id=' . $result['information_id'];
					$links[$result['information_id']][] = array('link' => $link,'code' => $langdetails['code']);
				}
			}
			$output .= $this->generateLinkINode($links,"yearly", "0.7");
		}
		return $output;
	}



	protected function getManufacturers($server) {
		$output = '';
		$this->load->model('catalog/manufacturer');
		foreach ($this->model_catalog_manufacturer->getManufacturers() as $result) {
			$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=".$result['manufacturer_id']."'");
			if($url->num_rows) {
				$output .= $this->generateLinkMANNode($server. $url->row['keyword'], "yearly", "0.6");
			} else {
				$output .= $this->generateLinkMANNode($server .  'index.php?route=product/manufacturer/info&manufacturer_id=' . $result['manufacturer_id'], "yearly", "0.6");
			}
		}
		return $output;
	}

	protected function getMManufacturers($server) {
		$output = '';
		$this->load->model('catalog/manufacturer');
		foreach ($this->model_catalog_manufacturer->getManufacturers() as $result) {
			$links = array();
			$lang = $this->getLanguages();
			foreach ($lang as $tp => $langdetails) {
				$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=".$result['manufacturer_id']."'");
				if($url->num_rows) {
					$keywordvalue = $url->row['keyword'];
					$link = $server . $keywordvalue;
					$links[$result['manufacturer_id']][] = array('link' => $link,'code' => $langdetails['code']);
				} else {
					$link = $server.'index.php?route=product/manufacturer/info&manufacturer_id=' . $result['manufacturer_id'];
					$links[$result['manufacturer_id']][] = array('link' => $link,'code' => $langdetails['code']);
				}
			}
			$output .= $this->generateLinkINode($links,"yearly", "0.6");
		}
		return $output;
	}


	protected function getGeneral($server) {
		$output = '';
		$output .= $this->generateLinkMANNode($server ,  "yearly", "1.0");		
		return $output;
	}

	protected function getMGeneral($server) {
		$output = '';
		$url =  $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'route=common/home'");
		$lang = $this->getLanguages();
		foreach ($lang as $tp => $langdetails) {
			if($url->num_rows) {
				$keywordvalue = $url->row['keyword'];
				$link = $server . $keywordvalue;
				$links['general'][] = array('link' => $link,'code' => $langdetails['code']);
			} else {
				$link = $server.'/index.php?route=common/home';
				$links['general'][] = array('link' => $link,'code' => $langdetails['code']);
			}
		}
		$output .= $this->generateLinkINode($links,"yearly", "1.0");
		
		return $output;
	}

	protected function generateLinkNode($link, $lastmod,$changefreq = 'yearly',$priority = '0.9',$image = "",$server) {
		$link = str_replace("&","&amp;", $link);
		$output = "";	
		$output .= "    <url>\r\n";
		$output .= "        <loc>" .$link. "</loc>\r\n";
		$output .= "        <priority>" . $priority . "</priority>\r\n";
		if($image != ""  && file_exists(DIR_IMAGE.$image)){
			$output .= "        <image:image><image:loc>". $server ."image/" .str_replace(" ","%20", $image) . "</image:loc></image:image>\r\n";
		}
		$output .= "        <lastmod>" . $lastmod . "</lastmod>\r\n";
		$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
		$output .= "    </url>\r\n";
		return $output;
	}

	protected function generateLinkMANNode($link,$changefreq = 'yearly',$priority = '0.9') {
		$link = str_replace("&","&amp;", $link);
		$output = "";	
		$output .= "    <url>\r\n";
		$output .= "        <loc>" .$link. "</loc>\r\n";
		$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
		$output .= "        <priority>" . $priority . "</priority>\r\n";
		$output .= "    </url>\r\n";
		return $output;
	}

	protected function generateLinkInNode($link,$changefreq = 'yearly',$priority = '0.6') {
		$link = str_replace("&","&amp;", $link);
		$output = "";	
		$output .= "    <url>\r\n";
		$output .= "        <loc>" .$link. "</loc>\r\n";
		$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
		$output .= "        <priority>" . $priority . "</priority>\r\n";
		$output .= "    </url>\r\n";
		return $output;
	}

	protected function generateLinkMNode($links,$lastmod,$changefreq = 'yearly',$priority = '0.9',$image="",$server) {
		$return = "";
		foreach ($links as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$value1['link'] = str_replace("&","&amp;", $value1['link']);
				$output = "";	
				$output .= "    <url>\r\n";
				$output .= "        <loc>" . $value1['link'] . "</loc>\r\n";
				foreach ($value as $key1 => $value1) {
					$temp = $this->multilang($value1['link'],$value1['code']);
					$output .= $temp; 
				}
				if($image != ""  && file_exists(DIR_IMAGE.$image)){
					$output .= "        <image:image><image:loc>". $server ."image/" .str_replace(" ","%20", $image). "</image:loc></image:image>\r\n";
				}
				$output .= "        <lastmod>" . $lastmod . "</lastmod>\r\n";
				$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
				$output .= "        <priority>" . $priority . "</priority>\r\n";
				$output .= "    </url>\r\n";
				$return .= $output;
			}
		}
		return $return;
	}

	protected function generateLinkINode($links,$changefreq = 'yearly',$priority = '0.6') {
		$return = "";
		foreach ($links as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$value1['link'] = str_replace("&","&amp;", $value1['link']);
				$output = "";	
				$output .= "    <url>\r\n";
				$output .= "        <loc>" . $value1['link'] . "</loc>\r\n";
				foreach ($value as $key1 => $value1) {
					$temp = $this->multilang($value1['link'],$value1['code']);
					$output .= $temp; 
				}
				$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
				$output .= "        <priority>" . $priority . "</priority>\r\n";
				$output .= "    </url>\r\n";
				$return .= $output;
			}
		}
		return $return;
	}

	protected function multilang($link, $code) {
		$link = str_replace("&","&amp;", $link);
		$output = "	<xhtml:link \r\n";
		$output .= "		rel=\"alternate\" \r\n";
		$output .= "		hreflang=\"". $code ."\" \r\n";
		$output .= "		href=\"". $link ."\"\r\n ";
		$output .= "		/>\r\n";
		return $output;
	}

	public function getLanguages() {
			$sql = "SELECT * FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}
}
?>