<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

          
				if($url[0] == 'route') {
						$this->request->get['route'] = $url[1];
					}
				
					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}
					
					          
				if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id' && $url[0] != 'route') {
				
						$this->request->get['route'] = $query->row['query'];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
				} elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				}
			}


			$this->load->model("localisation/language");
			$results = $this->model_localisation_language->getLanguages();

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			if (isset($this->request->get['product_id']) && isset($this->request->get['path'])) {
				foreach ($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherd($server."index.php?route=product/product&path=".$this->request->get['path']."&product_id=".$this->request->get['product_id'],$value['language_id'],$server);
				}
			} elseif(isset($this->request->get['product_id'])){
				foreach ($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherd($server."index.php?route=product/product&product_id=".$this->request->get['product_id'],$value['language_id'],$server);
				}
			} elseif(isset($this->request->get['path'])){
				foreach ($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherd($server."index.php?route=product/category&path=".$this->request->get['path'],$value['language_id'],$server);
				}
			} elseif(isset($this->request->get['manufacturer_id'])){
				foreach ($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherd($server."index.php?route=product/manufacturer/info&manufacturer_id=".$this->request->get['manufacturer_id'],$value['language_id'],$server);
				}
			} elseif(isset($this->request->get['information_id'])){
				foreach ($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherd($server."index.php?route=information/information&information_id=".$this->request->get['information_id'],$value['language_id'],$server);
				}
			}elseif(isset($this->request->get['route'])){
				foreach($results as $key => $value){
					$this->session->data['hreflang'][$value['language_id']] = $this->nerdherdg($server."index.php?route=".$this->request->get['route'],$server);
			}

			}
			
			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}

 
	public function nerdherd($link,$langid,$server){
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$url = ''; 
		$data = array();
		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id')  || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND lang = '".(int)$langid."'");
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						unset($data[$key]);
					}					
				} else if(($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id'){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				}elseif($key == 'path'){
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "' AND lang = '".(int)$langid."'");
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}
					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['route']);
			$query = '';
			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . $key . '=' . $value;
				}
				if ($query) {
					$query = '?' . trim($query, '&');
				}
			}
			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
	public function nerdherdg($link,$server){
		
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$url = ''; 
		$data = array();
		parse_str($url_info['query'], $data);
		foreach ($data as $key => $value) {
				if (isset($data['route'])) {
				    if($key = 'route') {
				    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'route=" . $this->db->escape($value) . "'");
				    	if($query->num_rows) {
				    		$url .= '/' . $query->row['keyword'];
				    	}
			        }				
			    }
			}
			
		if ($url) {
			unset($data['route']);
			$query = '';
			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . $key . '=' . $value;
				}
				if ($query) {
					$query = '?' . trim($query, '&');
				}
			}
			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				
			if (($data['route'] == 'product/product' && $key == 'product_id')  || ($data['route'] == 'information/information' && $key == 'information_id')) {
			
					
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND lang = '".(int)$this->config->get('config_language_id')."'");
			

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}

		} else if(($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id'){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				
			
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'  AND lang = '".(int)$this->config->get('config_language_id')."'");
			

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}
			}
		}


		 foreach ($data as $key => $value) {
            
				if (isset($data['route'])) {
				
				    if($key = 'route') {
				    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'route=" . $this->db->escape($value) . "'");
				    	if($query->num_rows) {
				    		$url .= '/' . $query->row['keyword'];
				    	}
			        }				
			    }
			}
			
		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
