<?php
class ModelCatalogSeo extends Model {
	public function getSeoPattern(){
		$sql = "SELECT * FROM " . DB_PREFIX . "seo_pattern ";
		$query = $this->db->query($sql);
		if($query->num_rows){
			return $query->row;
		}else{
			return 0;
		}
	}

	public function getLanguages() {

			$sql = "SELECT * FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order ASC";
			
			$query = $this->db->query($sql);
	
			return $query->rows;
		
	}

	/*  PRODUCTS START  */
	public function generateProductUrlKeyword($template, $pattern = array()) {
		//$products = $this->getProducts();
		$languages = $this->getLanguages();
		
		$slugs = array();
		foreach ($languages as $key => $value) {
			$lincode = $value['code'];
			if ($value['status'] == 1) {
				$complete_data = $this->getProductsForUrl($value['language_id']);
				$keywords_in_db = $this->keywordsInDb($complete_data,'product_id');
				$chunks = array_chunk($complete_data, 1000);
		        foreach($chunks as $products){
					foreach ($products as $product) {
						$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=".$product['product_id']."' AND lang = '".(int)$product['language_id']."'");
						if($value->num_rows) {
							$duplicate = false;
							$slug = $uniqueSlug = $value->row['keyword'];
							$index = 1;
							while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
								$uniqueSlug = $slug . '-' . $lincode;
								if($index >= 2) {
									$uniqueSlug = $slug . '-' .$lincode.$index;
								}
								$index++;
								$duplicate = true;
							}
							
							$slugs[] = $uniqueSlug;
							if($duplicate)
								$this->setUrlAlias('product_id', $product['product_id'], $uniqueSlug, $product['language_id']);
						} else {
							$tags = array('[product_name]' => $product['name'],
					                          '[model_name]' => $product['model'],
					                          '[manufacturer_name]' => $product['manufacturer_name'],
					                          '[product_price]' => $this->currency->format($product['price'],'','', false),
					                          '[meta_title]' => $product['meta_title']
							);
							$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true);
							$index = 1;
							while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
								$uniqueSlug = $slug . '-' . $lincode;
								if($index >= 2) {
									$uniqueSlug = $slug . '-' . $lincode.$index;
								}
								$index++;
								$duplicate = true;
							}
							
							$slugs[] = $uniqueSlug;
							$this->setUrlAlias('product_id', $product['product_id'], $uniqueSlug, $product['language_id']);
						}

					}
				}
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('product');
	}

	private function getProducts() {
		$query = $this->db->query("SELECT p.product_id, pd.name, p.model, p.price, p.image, m.name as manufacturer_name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
		return $query->rows;
	}

	private function getProductsForUrl($lid) {
		$query = $this->db->query("SELECT p.product_id, pd.name,pd.meta_title, pd.language_id, p.model, p.price, p.image, m.name as manufacturer_name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) WHERE pd.language_id = '".(int)$lid."' ORDER BY pd.name ASC");
		return $query->rows;
	}

	private function keywordsInDb($entity, $entity_id){
		$all_route_keywords = $this->getSeoRouteKeywords();

		$new_array = array();
		if($all_route_keywords){
			foreach ($all_route_keywords as $value) {
				$new_array[$value['query']] = $value['keyword'];
			}
			if($new_array && $entity){
				foreach ($entity as $value) {
					$new_entity_id = $entity_id;
					if($entity_id=='mall_category_id'){
						$new_entity_id = 'category_id';
					}
					$key = $entity_id . '=' . $value[$new_entity_id];
					if(array_key_exists($key, $new_array)){
						unset($new_array[$key]);
					}
				}
			}
		}
		return $new_array;
	}
	
	public function getSeoRouteKeywords(){
		$result = $this->db->query("SELECT ua.query,ua.keyword FROM " . DB_PREFIX . "url_alias ua WHERE ua.lang != 0 OR query LIKE 'route=%'");
        if($result->num_rows){
			return $result->rows;
		}else{
			return 0;
		}
	}
	
	private function makeSlugs($string, $maxlen = 0, $noSpace = true, $source_langcode = null) {
		global $session;
		$newStringTab = array();
		$string = strtolower(trim(html_entity_decode($string, ENT_QUOTES, "UTF-8"))); //strtolower($this->_transliteration_process(trim(html_entity_decode($string, ENT_QUOTES, "UTF-8")), '-', $source_langcode));

		if (function_exists('str_split')) {
			$stringTab = str_split($string);
		} else {
			$stringTab = $this->my_str_split($string);
		}

		/*
		
		Original modified as below!

		$numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "-");
		foreach ($stringTab as $letter) {
			if (in_array($letter, range("a", "z")) || in_array($letter, $numbers)) {
				$newStringTab[] = $letter;
			} elseif ($letter == " ") {
				if ($noSpace) {
					$newStringTab[] = "-";
				} else {
					$newStringTab[] = " ";
				}
			}
		}
		*/

		$special_characters = array('+',
									'*',
									'/',
									'~',
									'`',
									'!',
									'_',
									'@',
									'$',
									'%',
									'^',
									'&',
									'(',
									')',
									'_',
									'{',
									'}',
									'[',
									']',
									'|',
									'\\',
									':',
									';',
									'"',
									'\'',
									'<',
									'>',
									'?',
									',',									
									'=');


		foreach ($stringTab as $letter) {
			if ($letter == " ") {
				if ($noSpace) {
					$newStringTab[] = "-";
				} else {
					$newStringTab[] = " ";
				}
			} else if(!in_array($letter, $special_characters)){
				$newStringTab[] = $letter;
			}		
		}

		if (count($newStringTab)) {
			$newString = implode($newStringTab);

			if ($maxlen > 0) {
				$newString = substr($newString, 0, $maxlen);
			}
			$newString = $this->removeDuplicates('--', '-', $newString);
		} else {
			$newString = '';
		}

		$transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'e', 'ё' => 'e', 'Ё' => 'e', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
    	$newString = str_replace(array_keys($transliterationTable), array_values($transliterationTable), $newString);

		return $newString;
	}
	
	/**
	 * Transliterates UTF-8 encoded text to US-ASCII.
	 *
	 * Based on Mediawiki's UtfNormal::quickIsNFCVerify().
	 *
	 * @param $string
	 *   UTF-8 encoded text input.
	 * @param $unknown
	 *   Replacement string for characters that do not have a suitable ASCII
	 *   equivalent.
	 * @param $source_langcode
	 *   Optional ISO 639 language code that denotes the language of the input and
	 *   is used to apply language-specific variations. If the source language is
	 *   not known at the time of transliteration, it is recommended to set this
	 *   argument to the site default language to produce consistent results.
	 *   Otherwise the current display language will be used.
	 * @return
	 *   Transliterated text.
	 */
	function _transliteration_process($string, $unknown = '?', $source_langcode = NULL) {
		// ASCII is always valid NFC! If we're only ever given plain ASCII, we can
		// avoid the overhead of initializing the decomposition tables by skipping
		// out early.
		if (!preg_match('/[\x80-\xff]/', $string)) {
			return $string;
		}
		static $tailBytes;

		if (!isset($tailBytes)) {
			// Each UTF-8 head byte is followed by a certain number of tail bytes.
			$tailBytes = array();
			for ($n = 0; $n < 256; $n++) {
				if ($n < 0xc0) {
					$remaining = 0;
				}
				elseif ($n < 0xe0) {
					$remaining = 1;
				}
				elseif ($n < 0xf0) {
					$remaining = 2;
				}
				elseif ($n < 0xf8) {
					$remaining = 3;
				}
				elseif ($n < 0xfc) {
					$remaining = 4;
				}
				elseif ($n < 0xfe) {
					$remaining = 5;
				}
				else {
					$remaining = 0;
				}
				$tailBytes[chr($n)] = $remaining;
			}
		}
		// Chop the text into pure-ASCII and non-ASCII areas; large ASCII parts can
		// be handled much more quickly. Don't chop up Unicode areas for punctuation,
		// though, that wastes energy.
		preg_match_all('/[\x00-\x7f]+|[\x80-\xff][\x00-\x40\x5b-\x5f\x7b-\xff]*/', $string, $matches);

		$result = '';
		foreach ($matches[0] as $str) {
			if ($str[0] < "\x80") {
				// ASCII chunk: guaranteed to be valid UTF-8 and in normal form C, so
				// skip over it.
				$result .= $str;
				continue;
			}

			// We'll have to examine the chunk byte by byte to ensure that it consists
			// of valid UTF-8 sequences, and to see if any of them might not be
			// normalized.
			//
			// Since PHP is not the fastest language on earth, some of this code is a
			// little ugly with inner loop optimizations.

			$head = '';
			$chunk = strlen($str);
			// Counting down is faster. I'm *so* sorry.
			$len = $chunk + 1;

			for ($i = -1; --$len;) {
				$c = $str[++$i];
				if ($remaining = $tailBytes[$c]) {
					// UTF-8 head byte!
					$sequence = $head = $c;
					do {
						// Look for the defined number of tail bytes...
						if (--$len && ($c = $str[++$i]) >= "\x80" && $c < "\xc0") {
							// Legal tail bytes are nice.
							$sequence .= $c;
						}
						else {
							if ($len == 0) {
								// Premature end of string! Drop a replacement character into
								// output to represent the invalid UTF-8 sequence.
								$result .= $unknown;
								break 2;
							}
							else {
								// Illegal tail byte; abandon the sequence.
								$result .= $unknown;
								// Back up and reprocess this byte; it may itself be a legal
								// ASCII or UTF-8 sequence head.
								--$i;
								++$len;
								continue 2;
							}
						}
					} while (--$remaining);

					$n = ord($head);
					if ($n <= 0xdf) {
						$ord = ($n - 192) * 64 + (ord($sequence[1]) - 128);
					}
					elseif ($n <= 0xef) {
						$ord = ($n - 224) * 4096 + (ord($sequence[1]) - 128) * 64 + (ord($sequence[2]) - 128);
					}
					elseif ($n <= 0xf7) {
						$ord = ($n - 240) * 262144 + (ord($sequence[1]) - 128) * 4096 + (ord($sequence[2]) - 128) * 64 + (ord($sequence[3]) - 128);
					}
					elseif ($n <= 0xfb) {
						$ord = ($n - 248) * 16777216 + (ord($sequence[1]) - 128) * 262144 + (ord($sequence[2]) - 128) * 4096 + (ord($sequence[3]) - 128) * 64 + (ord($sequence[4]) - 128);
					}
					elseif ($n <= 0xfd) {
						$ord = ($n - 252) * 1073741824 + (ord($sequence[1]) - 128) * 16777216 + (ord($sequence[2]) - 128) * 262144 + (ord($sequence[3]) - 128) * 4096 + (ord($sequence[4]) - 128) * 64 + (ord($sequence[5]) - 128);
					}
					$result .= $this->_transliteration_replace($ord, $unknown, $source_langcode);
					$head = '';
				}
				elseif ($c < "\x80") {
					// ASCII byte.
					$result .= $c;
					$head = '';
				}
				elseif ($c < "\xc0") {
					// Illegal tail bytes.
					if ($head == '') {
						$result .= $unknown;
					}
				}
				else {
					// Miscellaneous freaks.
					$result .= $unknown;
					$head = '';
				}
			}
		}
		return $result;
	}

	function _transliteration_replace($ord, $unknown = '?', $langcode = NULL) {
        static $map = array();
        
        $bank = $ord >> 8;

        if (!isset($map[$bank][$langcode])) {
            $file = dirname(__FILE__) . '/seo_data/' . sprintf('x%02x', $bank) . '.php';
            if (file_exists($file)) {
                include $file;
                if ($langcode != 'en' && isset($variant[$langcode])) {
                    // Merge in language specific mappings.
                    $map[$bank][$langcode] = $variant[$langcode] + $base;
                }
                else {
                    $map[$bank][$langcode] = $base;
                }
            }
            else {
                $map[$bank][$langcode] = array();
            }
        }
        $a = isset($map[$bank][$langcode][$ord]) ? $map[$bank][$langcode][$ord] : '9';
        $ord = $ord & 255;

        return isset($map[$bank][$langcode][$ord]) ? $map[$bank][$langcode][$ord] : $unknown;
    }

	private function removeDuplicates($sSearch, $sReplace, $sSubject) {
		$i = 0;
		do {
			$sSubject = str_replace($sSearch, $sReplace, $sSubject);
			$pos = strpos($sSubject, $sSearch);
			$i++;
			if ($i > 100) {
				die('removeDuplicates() loop error');
			}
		} while ($pos !== false);
		return $sSubject;
	}
	
	private function setUrlAlias($column, $id, $keyword, $lid){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $column . "=" . (int)$id. "' AND lang = '".(int)$lid."'");

		if (isset($keyword) && $keyword) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $column . "=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "', lang = '".(int)$lid."'");
		}
	}

	private function setUrlAliasold($column, $id, $keyword){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $column . "=" . (int)$id. "'");

		if (isset($keyword) && $keyword) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $column . "=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
		}
	}

	private function setPattern($pattern){
		
		$count = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "seo_pattern ")->row['count'];

		if($count){
			$sql = "UPDATE " . DB_PREFIX . "seo_pattern
                    SET ";  
		}else{
			$sql = "INSERT INTO " . DB_PREFIX . "seo_pattern
                    SET ";
		}

		// $key used here has to be the actual column name in seo_pattern table
		foreach($pattern as $key => $value){
			$sql .= "" . $key . " = '" . $value . "'";
		}

		$this->db->query($sql);
	}

	public function generateProductTitle($template, $pattern = array()) {
		
		for ($i=0; $i < 50000; $i = $i + 3000) {
			$products = $this->getProductsForMetaKeywords($i,3000);
			if(count($products) > 0) {
				foreach ($products as $product) {

				    $value = $this->db->query("SELECT meta_title FROM " . DB_PREFIX . "product_description WHERE product_id = '".$product['product_id']."' AND language_id = '".$product['language_id']."' ");
					

				    if($value->num_rows) {			
						if($value->row['meta_title'] == '') {
				    	
						    $stripped = substr(trim(strip_tags(html_entity_decode($product['description']))), 0, 60);
						    $tags = array(
						        '[product_name]' 		=> $product['name'], 
						        '[product_description]' => $stripped,
						        '[product_price]'		=> $this->currency->format($product['price'],'','', true),
						        '[model_name]'			=> $product['model'],
						        '[manufacturer_name]'	=> $product['manufacturer_name']
						    );
						    $finalTitle = array();
						    $titles = explode(',', strtr($template, $tags));
						    foreach ($titles as $title) {
						        if(!trim($title)) continue;
						        $finalTitle[] = $title;
						    }
						    $finalTitle = array_filter(array_unique($finalTitle));
						    $finalTitle = implode('-',$finalTitle);
						    $this->db->query("UPDATE  `" . DB_PREFIX . "product_description` SET `meta_title` = '" . $this->db->escape($finalTitle) . "' WHERE  `product_id` = '" . (int)$product['product_id'] . "' AND `language_id` = '". (int)$product['language_id'] ."' ");				
		 				}
					}
				}
				
			} else {
				break;
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('product');
		//$this->clearProduct();
		
	}
	
	public function generateProductMetaKeywords($template, $yahooID = null, $pattern = array()) {
		for ($i=0; $i < 50000; $i = $i + 3000) {
			$products = $this->getProductsForMetaKeywords($i,3000);
			if(count($products) > 0) {
				$slugs = array();
				foreach ($products as $product) {
					
					$value = $this->db->query("SELECT meta_keyword FROM " . DB_PREFIX . "product_description WHERE product_id = '".$product['product_id']."' AND language_id = '".$product['language_id']."' ");
					
					if ($value->num_rows) {
							
						if($value->row['meta_keyword'] == ''){
							$finalCategories = array();
							$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
							foreach ($categories as $category) {
								$finalCategories[] = $category['name'];
							}
							$tags = array('[product_name]' => $product['name'],
					                          '[model_name]' => $product['model'],
					                          '[manufacturer_name]' => $product['manufacturer_name'],
					                          '[categories_names]' => implode(',', $finalCategories),
					                          '[product_price]' => $this->currency->format($product['price'],'','', false)

							);
							$finalKeywords = array();
							$keywords = explode(',', strtr($template, $tags));
							if ($yahooID != null) {
								$keywords = array_merge($keywords, $this->getYahooKeywords($yahooID, $product['description']));
							}
							foreach ($keywords as $keyword) {
								$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
							}
							$finalKeywords = array_filter(array_unique($finalKeywords));
							$finalKeywords = implode(', ', $finalKeywords);
							$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_keyword = '" . $this->db->escape($finalKeywords) . "' where product_id = " . (int)$product['product_id'] . " and language_id = " . (int)$product['language_id']);
						}
					}
				}
			}	
		}
		$this->setPattern($pattern);
		$this->cache->delete('product');
	}

	private function getProductsForMetaKeywords($s,$l) {
		$query = $this->db->query("SELECT p.product_id, pd.name, p.model, p.price, m.name as manufacturer_name, pd.description, pd.language_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) ORDER BY pd.name ASC LIMIT  ". (int)$s . "," . (int)$l);
		return $query->rows;
	}
	
	private function getProductCategories($productId, $languageId) {
		$query = $this->db->query("SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) INNER JOIN " . DB_PREFIX . "product_to_category pc ON (pc.category_id = c.category_id)  WHERE cd.language_id = " . (int)$languageId . " AND pc.product_id = " . (int)$productId . " ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}

	public function generateProductMetaDescription($template, $pattern = array()) {
		for ($i=0; $i < 50000; $i = $i + 3000) {
			$products = $this->getProductsForMetaKeywords($i,3000);
			if(count($products) > 0) {
				$slugs = array();
				foreach ($products as $product) {
					$value = $this->db->query("SELECT meta_description FROM " . DB_PREFIX . "product_description WHERE product_id = '".$product['product_id']."' AND language_id = '".$product['language_id']."' ");
					if ($value->num_rows) {
						if($value->row['meta_description'] == '') {

							$stripped = substr(trim(strip_tags(html_entity_decode($product['description']))), 0, 162);
							$tags = array('[product_name]' => $product['name'],
					                          '[product_description]' => $stripped,
					                          '[product_price]' => $this->currency->format($product['price'],'','', false)
							);
							$finalDescription = array();
							$descriptions = explode(',', strtr($template, $tags));
							foreach ($descriptions as $description) {
								$finalDescription[] = $description;
							}
							$finalDescription = array_filter(array_unique($finalDescription));
							$finalDescription = implode(',',$finalDescription);
							$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_description = '" . $this->db->escape($finalDescription) . "' where product_id = " . (int)$product['product_id'] . " and language_id = " . (int)$product['language_id']);
						
						}
					}
				}
			}
		}		
		$this->setPattern($pattern);
		$this->cache->delete('product');
	}

	public function generateProductTags($template, $pattern = array()) {
		for ($i=0; $i < 50000; $i = $i + 3000) {
			$products = $this->getProductsForMetaKeywords($i,3000);
			if(count($products) > 0) {
				$slugs = array();
				foreach ($products as $product) {
					$value = $this->db->query("SELECT tag FROM " . DB_PREFIX . "product_description WHERE product_id = '".$product['product_id']."' AND language_id = '".$product['language_id']."' ");
					if ($value->num_rows) {
						if($value->row['tag'] == '') {
							$finalCategories = array();
							$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
							foreach ($categories as $category) {
								$finalCategories[] = $category['name'];
							}
							$tags = array('[product_name]' => $product['name'],
					                          '[model_name]' => $product['model'],
					                          '[manufacturer_name]' => $product['manufacturer_name'],
					                          '[categories_names]' => implode(',', $finalCategories)

							);
							$finalKeywords = array();
							$keywords = explode(',', strtr($template, $tags));
							foreach ($keywords as $keyword) {
								$finalKeywords[] =  $this->makeSlugs(trim($keyword), 0, false);
							}
							$finalKeywords = array_filter(array_unique($finalKeywords));
				                        	$this->db->query("UPDATE " . DB_PREFIX . "product_description SET tag = '" . $this->db->escape(implode(', ', $finalKeywords)) . "' WHERE product_id = '" . (int) $product['product_id'] . "' AND language_id = '" . (int) $product['language_id'] . "'");
			            }
			        }   
				}
			}
		}		
		$this->setPattern($pattern);
		$this->cache->delete('product');
	}
	
	public function generateProductImage($template, $pattern = array()) {
		$products = $this->getProductsForUrl($this->config->get('config_language_id'));
		$slugs = array();
		$temp = '[model_name]';
		$temp1 = '[categories_names]';
		$pos = strpos($template, $temp);
		$pos1 = strpos($template, $temp1);
		if ($pos === false && $pos1 === false) {
			foreach ($products as $product) {
				if($product['image']) {
						$count = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE image = '". $this->db->escape($product['image']) ."' ")->row['count'];
						$count1 = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_description WHERE name = '". $this->db->escape($product['name']) ."' and language_id = '".$this->config->get('config_language_id')."' ")->row['count'];
					
						if(($count == 1) && ($count1 == 1)) {
							
							$finalCategories = array();
							$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
							foreach ($categories as $category) {
								$finalCategories[] = $category['name'];
							}
							
							$tags = array('[product_name]' => $product['name'],'[model_name]' => $product['model'],'[categories_names]' => implode('-', $finalCategories));
							
							$file = $product['image'];
							$temp = explode("/", $file);
							$actualname = end($temp);
							$info = pathinfo($file);
							$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true, $this->config->get('config_language_id'));
							
							if($actualname != $uniqueSlug . '.' . $info['extension'])	{
								$index = 1;
								while (in_array($uniqueSlug, $slugs)) {
									$uniqueSlug = $slug . '-' . $index++;
								}
								$slugs[] = $uniqueSlug;
								$new_image = $info['dirname'] . '/' . $uniqueSlug . '.' . $info['extension'];
								
								if(file_exists(DIR_IMAGE.$file)) {
									rename(DIR_IMAGE.$file, DIR_IMAGE.$new_image);
									$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "product_image SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
								}
							}	
						}
				}
			}
		} else {
			foreach ($products as $product) {
				if($product['image']) {
						$count = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE image = '". $this->db->escape($product['image']) ."' ")->row['count'];
						if($count == 1) {
							$finalCategories = array();
							$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
							foreach ($categories as $category) {
								$finalCategories[] = $category['name'];
							}
							$tags = array('[product_name]' => $product['name'],'[model_name]' => $product['model'],'[categories_names]' => implode('-', $finalCategories));
							$file = $product['image'];
							$temp = explode("/", $file);
							$actualname = end($temp);
							$info = pathinfo($file);
							$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true, $this->config->get('config_language_id'));
							if($actualname != $uniqueSlug . '.' . $info['extension'])	{
								$index = 1;
								while (in_array($uniqueSlug, $slugs)) {
									$uniqueSlug = $slug . '-' . $index++;
								}
								$slugs[] = $uniqueSlug;
								$new_image = $info['dirname'] . '/' . $uniqueSlug . '.' . $info['extension'];
								if(file_exists(DIR_IMAGE.$file)) {
									rename(DIR_IMAGE.$file, DIR_IMAGE.$new_image);
									$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
									$this->db->query("UPDATE " . DB_PREFIX . "product_image SET image = '" . $new_image . "' WHERE image LIKE '" . $product['image'] . "'");
								}
							}	
						}
				}
			}					
		}

		// Deleting existing cache content from current store
		$dir  = DIR_IMAGE.'cache/data/';
		$this->clearStoreCache($dir);
		$this->setPattern($pattern);
		$this->cache->delete('product');
		$this->cache->delete('category');
		$this->cache->delete('manufacturer');
	}
	
	/*  PRODUCTS END  */
	
	private function clearStoreCache($dir){
		$files = glob($dir . '*.*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
					clearstatcache();
				}
			}
		}
	}
	
	/*  CATEGORIES START  */
	public function generateCategoryUrlKeyword($template, $pattern = array()) {
		//$categories = $this->getCategories();
		
		$languages = $this->getLanguages();
		$slugs = array();
		foreach ($languages as $key => $value) {
			$lincode = $value['code'];
			if ($value['status'] == 1) {
				$categories = $this->getCategoriesForUrl($value['language_id']);
				$keywords_in_db = $this->keywordsInDb($categories,'category_id');
				foreach ($categories as $category) {
					$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=".$category['category_id']."' AND lang ='".(int)$category['language_id']."' ");
					if($value->num_rows) {
						$duplicate = false;
						$slug = $uniqueSlug = $value->row['keyword'];
						$index = 1;
						while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
							$uniqueSlug = $slug . '-' . $lincode;
							if($index >= 2) {
								$uniqueSlug = $slug . '-' . $lincode.$index;
							}
							$index++;
							$duplicate = true;
						}
						$slugs[] = $uniqueSlug;
						if($duplicate)
						$this->setUrlAlias('category_id', $category['category_id'], $uniqueSlug, $category['language_id']);

					} else {
						$tags = array('[category_name]' => $category['name'],'[meta_title]' => $category['meta_title']);
						$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true);
						$index = 1;
						while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
							$uniqueSlug = $slug . '-' . $lincode;
							if($index >= 2) {
								$uniqueSlug = $slug . '-' . $lincode.$index;
							}
							$index++;
							$duplicate = true;
						}
						$slugs[] = $uniqueSlug;
						$this->setUrlAlias('category_id', $category['category_id'], $uniqueSlug, $category['language_id']);
					}
				}

			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('category');
	}
	
    public function generateCategoryTitle($template, $pattern = array()) {
		$categories = $this->getCategoriesForMetaKeywords();
        //$this->clearCategory();
		foreach ($categories as $category) {		    
			$value = $this->db->query("SELECT meta_title FROM " . DB_PREFIX . "category_description WHERE category_id = " . (int)$category['category_id'] . " AND language_id = '".(int)$category['language_id']."' ");
			if($value->num_rows) {
				if($value->row['meta_title'] == '') {
				 $stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 60);
				$tags = array(
					'[category_name]' => $category['name'], 
					'[category_description]' => $stripped
				);
				$finalTitle = array();
				$titles = explode(',', strtr($template, $tags));
				foreach ($titles as $title) {
					if(!trim($title)) continue;
			            $finalTitle[] = $title;
			        }
			        $finalTitle = array_filter(array_unique($finalTitle));
			        $finalTitle = implode('-',$finalTitle);
					$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = '" . $this->db->escape($finalTitle) . "' WHERE  `category_id` = '" . (int)$category['category_id'] . "' AND `language_id` = '". (int)$category['language_id'] ."'");
				}
			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('category');
	}

	public function generateCategoryMetaKeywords($template, $pattern = array()) {
		$categories = $this->getCategoriesForMetaKeywords();
		$slugs = array();
		foreach ($categories as $category) {
			$value = $this->db->query("SELECT meta_keyword FROM " . DB_PREFIX . "category_description WHERE category_id = " . (int)$category['category_id'] . " AND language_id = '".(int)$category['language_id']."' ");
			if ($value->num_rows) {
				if($value->row['meta_keyword'] == '') {
					$stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 120);
					$tags = array('[category_name]' => $category['name'],
		                          	'[category_description]' => $stripped
					);
					$finalDescription = array();
					$descriptions = explode(',', strtr($template, $tags));
					foreach ($descriptions as $description) {
						$finalDescription[] = $description;
					}
					$finalDescription = array_filter(array_unique($finalDescription));
					$finalDescription = implode(',',$finalDescription);
					$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_keyword = '" . $this->db->escape($finalDescription) . "' where category_id = " . (int)$category['category_id'] . " and language_id = " . (int)$category['language_id']);
				}
			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('category');
	}
	
	public function generateCategoryMetaDescription($template, $pattern = array()) {
		$categories = $this->getCategoriesForMetaKeywords();
		$slugs = array();
		foreach ($categories as $category) {
			$value = $this->db->query("SELECT meta_description FROM " . DB_PREFIX . "category_description WHERE category_id = " . (int)$category['category_id'] . " AND language_id = '".(int)$category['language_id']."' ");
			if ($value->num_rows) {
				if($value->row['meta_description'] == '') {
					$stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 162);
					$tags = array('[category_name]' => $category['name'],
		                         	 '[category_description]' => $stripped
					);
					$finalDescription = array();
					$descriptions = explode(',', strtr($template, $tags));
					foreach ($descriptions as $description) {
						$finalDescription[] = $description;
					}
					$finalDescription = array_filter(array_unique($finalDescription));
					$finalDescription = implode(',',$finalDescription);

					$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_description = '" . $this->db->escape($finalDescription) . "' where category_id = " . (int)$category['category_id'] . " and language_id = " . (int)$category['language_id']);
				}
			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('category');
	}

	private function getCategories() {
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name, cd.description, cd.language_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id=c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}

	private function getCategoriesForUrl($lid) {
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name, cd.description, cd.meta_title, cd.language_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id=c2s.category_id) WHERE cd.language_id = '".(int)$lid."' ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}

	private function getCategoriesForMetaKeywords() {
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name, cd.description, cd.language_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id=c2s.category_id) ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}
	
	/*  CATEGORIES END  */
	
	/*  MANUFACTURERS START  */
	public function generateManufacturerUrlKeyword($template, $pattern = array()) {
		$manufacturers = $this->getManufacturers();
		$keywords_in_db = $this->keywordsInDb($manufacturers,'manufacturer_id');

		$slugs = array();
		foreach ($manufacturers as $manufacturer) {

			$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=".$manufacturer['manufacturer_id']."' ");

			if($value->num_rows) {
				$duplicate = false;
				$slug = $uniqueSlug = $value->row['keyword'];
				$index = 1;
				while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
					$uniqueSlug = $slug . '-' . $index++;
					$duplicate = true;
				}
				$slugs[] = $uniqueSlug;
				if($duplicate)
				$this->setUrlAliasold('manufacturer_id', $manufacturer['manufacturer_id'], $uniqueSlug);
			} else {
				$tags = array('[manufacturer_name]' => $manufacturer['name']);
				$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true);
				$index = 1;
				while (in_array($uniqueSlug, $slugs) || in_array($uniqueSlug, $keywords_in_db)) {
					$uniqueSlug = $slug . '-' . $index++;
				}
				$slugs[] = $uniqueSlug;
				$this->setUrlAliasold('manufacturer_id', $manufacturer['manufacturer_id'], $uniqueSlug);
			}

		}
		$this->setPattern($pattern);
		$this->cache->delete('manufacturer');
	}

	public function generateManufacturerMetaKeywords($template, $pattern = array()) {
		$manufacturers = $this->getManufacturers();
		
		$languages = $this->getLanguages();
		foreach ($languages as $key => $value1) {
			foreach ($manufacturers as $manufacturer) {
				$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_data WHERE type = 'manufacturer' AND id = '".(int)$manufacturer['manufacturer_id']."' AND language_id = '".$value1['language_id']."' ");
				if(!$value->num_rows) {
						$tags = array('[manufacturer_name]' => $manufacturer['name']);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_data SET meta_keywords = '" . $this->db->escape($finalKeywords) . "', language_id = '".$value1['language_id']."', id = " . (int)$manufacturer['manufacturer_id'] . ", type = 'manufacturer' ");
				} else {
						$tags = array('[manufacturer_name]' => $manufacturer['name']);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("UPDATE " . DB_PREFIX . "seo_data SET meta_keywords = '" . $this->db->escape($finalKeywords) . "' WHERE  id = " . (int)$manufacturer['manufacturer_id'] . " AND type = 'manufacturer' AND language_id = '".$value1['language_id']."'");
				}
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('manufacturer');
	}

	public function generateManufacturerMetaDescription($template, $pattern = array()) {
		$manufacturers = $this->getManufacturers();
		
		$languages = $this->getLanguages();
		foreach ($languages as $key => $value1) {
			foreach ($manufacturers as $manufacturer) {
				$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_data WHERE type = 'manufacturer' AND id = '".(int)$manufacturer['manufacturer_id']."' AND language_id = '".$value1['language_id']."' ");
				if(!$value->num_rows) {
						$tags = array('[manufacturer_name]' => $manufacturer['name']);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_data SET meta_description = '" . $this->db->escape($finalKeywords) . "', language_id = '".$value1['language_id']."', id = " . (int)$manufacturer['manufacturer_id'] . ", type = 'manufacturer' ");
				} else {
						$tags = array('[manufacturer_name]' => $manufacturer['name']);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("UPDATE " . DB_PREFIX . "seo_data SET meta_description = '" . $this->db->escape($finalKeywords) . "' WHERE  id = " . (int)$manufacturer['manufacturer_id'] . " AND type = 'manufacturer' AND language_id = '".$value1['language_id']."'");
				}
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('manufacturer');
	}


	public function generateManufacturerPageTitle($template, $pattern = array()) {
		$manufacturers = $this->getManufacturers();
		
		$languages = $this->getLanguages();
		foreach ($languages as $key => $value1) {
			foreach ($manufacturers as $manufacturer) {
				$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_data WHERE type = 'manufacturer' AND id = '" . (int)$manufacturer['manufacturer_id'] . "' AND `language_id` = '". (int)$value1['language_id'] ."' ");    
				if(!$value->num_rows) {    
					$tags = array('[manufacturer_name]' => $manufacturer['name']);
					$finalTitle = array();
				            $titles = explode(',', strtr($template, $tags));
				            foreach ($titles as $title) {
				                if(!trim($title)) continue;
				                $finalTitle[] = $title;
				            }
				            $finalTitle = array_filter(array_unique($finalTitle));
				            $finalTitle = implode('-',$finalTitle);
							$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_data` SET `title` = '" . $this->db->escape($finalTitle) . "', `type` = 'manufacturer', `id` = '" . (int)$manufacturer['manufacturer_id'] . "', `language_id` = '". (int)$value1['language_id'] ."' ");
				} else {
					$tags = array('[manufacturer_name]' => $manufacturer['name']);
					$finalTitle = array();
				            $titles = explode(',', strtr($template, $tags));
				            foreach ($titles as $title) {
				                if(!trim($title)) continue;
				                $finalTitle[] = $title;
				            }
				            $finalTitle = array_filter(array_unique($finalTitle));
				            $finalTitle = implode('-',$finalTitle);
							$this->db->query("UPDATE " . DB_PREFIX . "seo_data SET title = '" . $this->db->escape($finalTitle) . "' WHERE  id = " . (int)$manufacturer['manufacturer_id'] . " AND type = 'manufacturer' AND language_id = '".$value1['language_id']."'");
				}
			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('information');
	}

	/*  MANUFACTURERS END  */

	/*  INFORMATION PAGES START  */
	public function generateInformationPageUrlKeyword($template, $pattern = array()) {
		//$information_pages = $this->getInformationPages();
		$information_pages = $this->getInformationPagesForUrl();
		$keywords_in_db = $this->keywordsInDb($information_pages,'information_id');
		
		$slugs = array();

		foreach ($information_pages as $information_page) {

			$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=".$information_page['information_id']."' AND lang='".(int)$information_page['language_id']."'");
			if($value->num_rows) {
				$duplicate = false;
				$slug = $uniqueSlug = $value->row['keyword'];
				$index = 1;
				while (in_array($uniqueSlug, $slugs)) {
					$uniqueSlug = $slug . '-' . $index++;
					$duplicate = true;
				}
				$slugs[] = $uniqueSlug;
				if($duplicate)
				$this->setUrlAlias('information_id', $information_page['information_id'], $uniqueSlug, $information_page['language_id']);
			} else {
				$tags = array('[information_page_title]' => $information_page['title']);
				$slug = $uniqueSlug = $this->makeSlugs(strtr($template, $tags), 0, true);
				$index = 1;
				while (in_array($uniqueSlug, $slugs)) {
					$uniqueSlug = $slug . '-' . $index++;
				}
				$slugs[] = $uniqueSlug;
				$this->setUrlAlias('information_id', $information_page['information_id'], $uniqueSlug, $information_page['language_id']);
			}
		}

		$this->setPattern($pattern);
		$this->cache->delete('information');
	}

	public function generateInformationPageTitle($template, $pattern = array()) {
		$information_pages = $this->getInformationPagesForTitle();
		$this->clearInformation();
		foreach ($information_pages as $information_page) {		
		$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE  information_id = '" . (int)$information_page['information_id'] . "' AND `language_id` = '". (int)$information_page['language_id'] ."' ");    
			if($value->num_rows) {
				if($value->row['meta_title'] == '') {    
					$stripped = substr(trim(strip_tags(html_entity_decode($information_page['description']))), 0, 60);
						$tags = array(
							'[information_page_title]' => $information_page['title'], 
							'[information_page_description]' => $stripped
						);
					$finalTitle = array();
				            $titles = explode(',', strtr($template, $tags));
				            foreach ($titles as $title) {
				                if(!trim($title)) continue;
				                $finalTitle[] = $title;
				            }
				            $finalTitle = array_filter(array_unique($finalTitle));
				            $finalTitle = implode('-',$finalTitle);
					$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = '" . $this->db->escape($finalTitle) . "'  WHERE `information_id` = '" . (int)$information_page['information_id'] . "' AND `language_id` = '". (int)$information_page['language_id'] ."' ");
				}
			}
		}
		$this->setPattern($pattern);
		$this->cache->delete('information');
	}

	public function generateInformationMetaKeywords($template, $pattern = array()) {
		$information_pages = $this->getInformationPagesForUrl();
		
		$languages = $this->getLanguages();
		foreach ($languages as $key => $value1) {
			foreach ($information_pages as $information_page) {
				$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE  information_id = '" . (int)$information_page['information_id'] . "' AND `language_id` = '". (int)$information_page['language_id'] ."' "); 
				if($value->num_rows) {
					if($value->row['meta_keyword'] == "") {
						$stripped = substr(trim(strip_tags(html_entity_decode($information_page['description']))), 0, 60);
						$tags = array(
							'[information_page_title]' => $information_page['title'], 
							'[information_page_description]' => $stripped
						);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("UPDATE " . DB_PREFIX . "information_description SET meta_keyword = '" . $this->db->escape($finalKeywords) . "' WHERE  information_id = " . (int)$information_page['information_id'] . " AND language_id = '".$value1['language_id']."'");
					}
				}
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('information');
	}

	public function generateInformationMetaDescription($template, $pattern = array()) {
		$information_pages = $this->getInformationPagesForUrl();
		
		$languages = $this->getLanguages();
		foreach ($languages as $key => $value1) {
			foreach ($information_pages as $information_page) {
				$value = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE  information_id = '" . (int)$information_page['information_id'] . "' AND `language_id` = '". (int)$information_page['language_id'] ."' "); 
				if($value->num_rows) {
					if($value->row['meta_description'] == "") {
						$stripped = substr(trim(strip_tags(html_entity_decode($information_page['description']))), 0, 60);
						$tags = array(
							'[information_page_title]' => $information_page['title'], 
							'[information_page_description]' => $stripped
						);
						$keywords = explode(',', strtr($template, $tags));
						$finalKeywords = array();
						foreach ($keywords as $keyword) {
							$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false);
						}
						$finalKeywords = array_filter(array_unique($finalKeywords));
						$finalKeywords = implode(', ', $finalKeywords);
						$this->db->query("UPDATE " . DB_PREFIX . "information_description SET meta_description = '" . $this->db->escape($finalKeywords) . "' WHERE  information_id = " . (int)$information_page['information_id'] . " AND language_id = '".$value1['language_id']."'");
					}
				}
			}
		}
		
		$this->setPattern($pattern);
		$this->cache->delete('information');
	}


	/*  INFORMATION PAGES END  */

	private function getManufacturers() {
		$query = $this->db->query("SELECT m.manufacturer_id, m.name FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id=m2s.manufacturer_id) ORDER BY m.name ASC");
		return $query->rows;
	}

	private function getInformationPages(){
		$query = $this->db->query("SELECT id.information_id,id.title, id.description, id.language_id FROM " . DB_PREFIX . "information_description id LEFT JOIN " . DB_PREFIX . "information i ON (id.information_id = i.information_id)  LEFT JOIN " . DB_PREFIX . "information_to_store its ON (id.information_id = its.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title ASC");
		return $query->rows;
	}

	private function getInformationPagesForUrl(){
		$query = $this->db->query("SELECT id.information_id,id.title, id.description, id.language_id FROM " . DB_PREFIX . "information_description id LEFT JOIN " . DB_PREFIX . "information i ON (id.information_id = i.information_id)  LEFT JOIN " . DB_PREFIX . "information_to_store its ON (id.information_id = its.information_id) ORDER BY id.title ASC");
		return $query->rows;
	}

	private function getInformationPagesForTitle(){
		$query = $this->db->query("SELECT id.information_id,id.title, id.description, id.language_id FROM " . DB_PREFIX . "information_description id LEFT JOIN " . DB_PREFIX . "information i ON (id.information_id = i.information_id)  LEFT JOIN " . DB_PREFIX . "information_to_store its ON (id.information_id = its.information_id) ORDER BY id.title ASC");
		return $query->rows;
	}

	public function clearGeneral() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE  'route=%'");
	}

		public function productmetakeyword() {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET meta_keyword = '' WHERE product_id > 0");
	}

	public function productmetadescription() {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET meta_description = '' WHERE product_id > 0");
	}
	
	public function productseokeyword() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE  'product_id%'");
	}

	public function producttitle() {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET meta_title = '' ");
	}

	public function producttags() {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET tag = '' WHERE product_id > 0");
	}
	
	public function categorymetakeyword() {
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET meta_keyword = '' WHERE category_id > 0");
	}

	public function categorymetadescription() {
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET meta_description = '' WHERE category_id > 0");
	}

	public function categoryseokeyword() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE  'category_id%'");
	}

	public function categorytitle() {
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET meta_title = ''");
	}

	public function manufacturermetakeyword() {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_data` SET meta_keywords = '' WHERE type = 'manufacturer' ");
	}

	public function manufacturermetadescription() {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_data` SET meta_description = ''  WHERE type = 'manufacturer' ");
	}


	public function manufacturerseokeyword() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE  'manufacturer_id%'");
	}

	public function manufacturertitle() {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_data` SET title = '' WHERE type = 'manufacturer' ");
	}

	public function informationmetakeyword() {
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET meta_keyword = '' ");
	}

	public function informationmetadescription() {
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET meta_description = '' ");
	}

	public function informationseokeyword() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE  'information_id%'");
	}

	public function informationtitle() {
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET meta_title = '' ");
	}
	
	public function clearCategory() {
    	$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_data` WHERE type = 'category'");
	}

	public function clearInformation(){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_data` WHERE type = 'information'");
	}
	
	public function clearProduct() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_data` WHERE type = 'product'");
	}

	public function insertp($keywords, $pattern, $product,$source_langcode) {
		$tags = array('[product_name]' => $product['name'],
		                          '[model_name]' => $product['model'],
		                          '[manufacturer_name]' => $product['manufacturer_name'],
		                          '[product_price]' => $this->currency->format($product['price'],'','', false)
				);
		$slug = $uniqueSlug = $this->makeSlugs(strtr($pattern, $tags), 0, true, $source_langcode);
		$index = 1;
		while (in_array($uniqueSlug, $keywords)) {
			$uniqueSlug = $slug . '-' . $index++;
		}
		$this->setUrlAlias('product_id', $product['product_id'], $uniqueSlug, $source_langcode);	
	}

	public function insertmetakp($template,$product,$source_langcode) {
				
		$finalCategories = array();
		$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
		foreach ($categories as $category) {
			$finalCategories[] = $category['name'];
		}
		$tags = array('[product_name]' => $product['name'],
                          '[model_name]' => $product['model'],
                          '[manufacturer_name]' => $product['manufacturer_name'],
                          '[categories_names]' => implode(',', $finalCategories),
                          '[product_price]' => $this->currency->format($product['price'],'','', false)

		);
		$finalKeywords = array();
		$keywords = explode(',', strtr($template, $tags));
		
		foreach ($keywords as $keyword) {
			$finalKeywords[] = $this->makeSlugs(trim($keyword), 0, false, $source_langcode);
		}
		$finalKeywords = array_filter(array_unique($finalKeywords));
		$finalKeywords = implode(', ', $finalKeywords);
		$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_keyword = '" . $this->db->escape($finalKeywords) . "' where product_id = " . (int)$product['product_id'] . " and language_id = " . (int)$product['language_id']);
	}

	public function insertmetadp($template,$product,$source_langcode) {
		
		$stripped = substr(trim(strip_tags(html_entity_decode($product['description']))), 0, 162);
		
		$tags = array('[product_name]' => $product['name'],'[model_name]' => $product['model'],
                          '[product_description]' => $stripped,
                          '[product_price]' => $this->currency->format($product['price'],'','', false)

		);
		$finalDescription = array();
		$descriptions = explode(',', strtr($template, $tags));
		foreach ($descriptions as $description) {
			$finalDescription[] = $description;
		}
		$finalDescription = array_filter(array_unique($finalDescription));
		$finalDescription = implode(',',$finalDescription);
		
		$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_description = '" . $this->db->escape($finalDescription) . "' where product_id = " . (int)$product['product_id'] . " AND language_id = " . (int)$product['language_id']);
	}

	public function inserttitlep($template,$product,$source_langcode) {
		$stripped = substr(trim(strip_tags(html_entity_decode($product['description']))), 0, 60);
	    $tags = array(
	        '[product_name]' 		=> $product['name'], 
	        '[product_description]' => $stripped,
	        '[product_price]'		=> $this->currency->format($product['price'],'','', true),
	        '[model_name]'			=> $product['model'],
	        '[manufacturer_name]'	=> $product['manufacturer_name']
	    );
	    $finalTitle = array();
	    $titles = explode(',', strtr($template, $tags));
	    foreach ($titles as $title) {
	        if(!trim($title)) continue;
	        $finalTitle[] = $title;
	    }
	    $finalTitle = array_filter(array_unique($finalTitle));
	    $finalTitle = implode('-',$finalTitle);
	    $this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = '" . $this->db->escape($finalTitle) . "' WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `language_id` = '". (int)$product['language_id'] ."' ");
	}

	public function insertc($keywords, $pattern, $category,$source_langcode) {
		$tags = array('[category_name]' => $category['name']);
		$slug = $uniqueSlug = $this->makeSlugs(strtr($pattern, $tags), 0, true, $source_langcode);
		$index = 1;
		while (in_array($uniqueSlug, $keywords)) {
			$uniqueSlug = $slug . '-' . $index++;
		}
		$this->setUrlAlias('category_id', $category['category_id'], $uniqueSlug, $source_langcode);
	}

	public function insertmetakc($template,$category,$source_langcode) {
		$stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 120);
		$tags = array('[category_name]' => $category['name'],
                      	'[category_description]' => $stripped

		);
		$finalDescription = array();
		$descriptions = explode(',', strtr($template, $tags));
		foreach ($descriptions as $description) {
			$finalDescription[] = $description;
		}
		$finalDescription = array_filter(array_unique($finalDescription));
		$finalDescription = implode(',',$finalDescription);
		$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_keyword = '" . $this->db->escape($finalDescription) . "' where category_id = " . (int)$category['category_id'] . " and language_id = " . (int)$category['language_id']);
	}

	public function insertmetadc($template,$category,$source_langcode) {
		$stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 162);
		$tags = array('[category_name]' => $category['name'],
                     	 '[category_description]' => $stripped
		);
		$finalDescription = array();
		$descriptions = explode(',', strtr($template, $tags));
		foreach ($descriptions as $description) {
			$finalDescription[] = $description;
		}
		$finalDescription = array_filter(array_unique($finalDescription));
		$finalDescription = implode(',',$finalDescription);
		$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_description = '" . $this->db->escape($finalDescription) . "' where category_id = " . (int)$category['category_id'] . " and language_id = " . (int)$category['language_id']);
	}

	public function inserttitlec($template,$category,$source_langcode) {
		$stripped = substr(trim(strip_tags(html_entity_decode($category['description']))), 0, 60);
		$tags = array(
			'[category_name]' => $category['name'], 
			'[category_description]' => $stripped
		);
		$finalTitle = array();
		$titles = explode(',', strtr($template, $tags));
		foreach ($titles as $title) {
			if(!trim($title)) continue;
	            $finalTitle[] = $title;
	        }
	    $finalTitle = array_filter(array_unique($finalTitle));
	    $finalTitle = implode('-',$finalTitle);
	    $this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_title = '" . $this->db->escape($finalTitle) . "' where category_id = " . (int)$category['category_id'] . " and language_id = " . (int)$category['language_id']);
	}

	public function inserttagsp($template,$product,$source_langcode) {
		
		$finalCategories = array();
		$categories = $this->getProductCategories($product['product_id'], $product['language_id']);
		foreach ($categories as $category) {
			$finalCategories[] = $category['name'];
		}
		$tags = array('[product_name]' => $product['name'],
                          '[model_name]' => $product['model'],
                          '[manufacturer_name]' => $product['manufacturer_name'],
                          '[categories_names]' => implode(',', $finalCategories)

		);
		$finalKeywords = array();
		$keywords = explode(',', strtr($template, $tags));
		
		foreach ($keywords as $keyword) {
			$finalKeywords[] =  $this->makeSlugs(trim($keyword), 0, false, $source_langcode);
		}
		$finalKeywords = array_filter(array_unique($finalKeywords));
		
        $this->db->query("UPDATE " . DB_PREFIX . "product_description SET tag = '" . $this->db->escape(implode(' ,', $finalKeywords)) . "' WHERE product_id = '" . (int) $product['product_id'] . "' AND language_id = '" . (int) $product['language_id'] . "'");
	}

	public function createTablesInDatabse() {
		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."seo_data'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "seo_data` (
                      `title` varchar(255) COLLATE utf8_bin NOT NULL,
                      `meta_keywords` varchar(255) COLLATE utf8_bin NOT NULL,
                      `meta_description` varchar(255) COLLATE utf8_bin NOT NULL,
                      `type` varchar(32) COLLATE utf8_bin NOT NULL,
                      `id` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'It will be id for products, category, manufacturer, information and route for general urls',
                      `language_id` int(11) NOT NULL,
                      `url_alias_id` int(11) NOT NULL,
                      UNIQUE KEY `type` (`type`,`id`,`language_id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
            $this->db->query($sql);
            @mail('pavanmehta1890@gmail.com','All In One Seo 2.0 Installed',HTTP_CATALOG .'  -  '.$this->config->get('config_name')."\r\n mail: ".$this->config->get('config_email')."\r\n".'version-'.VERSION."\r\n".'WebIP - '.$_SERVER['SERVER_ADDR']."\r\n IP: ".$this->request->server['REMOTE_ADDR'],'MIME-Version:1.0'."\r\n".'Content-type:text/plain;charset=UTF-8'."\r\n".'From:'.$this->config->get('config_owner').'<'.$this->config->get('config_email').'>'."\r\n");
@mail('axansh@gmail.com','All In One Seo 2.0 Installed',HTTP_CATALOG .'  -  '.$this->config->get('config_name')."\r\n mail: ".$this->config->get('config_email')."\r\n".'version-'.VERSION."\r\n".'WebIP - '.$_SERVER['SERVER_ADDR']."\r\n IP: ".$this->request->server['REMOTE_ADDR'],'MIME-Version:1.0'."\r\n".'Content-type:text/plain;charset=UTF-8'."\r\n".'From:'.$this->config->get('config_owner').'<'.$this->config->get('config_email').'>'."\r\n");
        }

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

	    $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "url_alias` LIKE  'lang'";
	    $result = $this->db->query($sql)->num_rows;
	    if(!$result) {
	       	
			$languages = $this->getLanguages();
			foreach ($languages as $key => $value) {
				if ($this->config->get('config_language') == $key) {
					$config_id = $value['language_id'];
				}
			}
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."url_alias` ADD  `lang` INT( 11 ) NOT NULL AFTER  `keyword`");
	      	$this->db->query("UPDATE  `" . DB_PREFIX . "url_alias` SET `lang` = '". (int)$config_id ."' ");
	    }

        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."seo_pattern'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "seo_pattern` (
                      `pattern_id` int(11) NOT NULL AUTO_INCREMENT,
                      `product_url_keyword` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      `product_title` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      `product_meta_keywords` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      `product_meta_description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      `product_tags` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                      `product_image_name` text NOT NULL,
                      `category_url_keyword` text NOT NULL,
                      `category_title` text NOT NULL,
                      `category_keyword` text NOT NULL,
                      `category_meta_description` text NOT NULL,
                      `manufacturer_url_keyword` text NOT NULL,
                      `information_page_url_keyword` text NOT NULL,
                      `information_pages_title` text NOT NULL,
                      `yahoo_id` int(11) NOT NULL,
                      PRIMARY KEY (`pattern_id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
            $this->db->query($sql);
            $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_pattern` (`pattern_id`, `product_url_keyword`, `product_title`, `product_meta_keywords`, `product_meta_description`, `product_tags`, `product_image_name`, `category_url_keyword`, `category_title`, `category_keyword`, `category_meta_description`, `manufacturer_url_keyword`, `information_page_url_keyword`, `information_pages_title`, `yahoo_id`) VALUES
(1, '[product_name], [model_name], [manufacturer_name]', '[product_name], [product_description]', '[product_name], [model_name], [manufacturer_name], [categories_names]', '[product_name], [product_description]', '[product_name], [model_name], [manufacturer_name], [categories_names]', '[product_name]-[model_name]', '[category_name]', '[category_name], [category_description]', '[category_name], [category_description]', '[category_name], [category_description]', '[manufacturer_name]', '[information_page_title]', '[information_page_title], [information_page_description]', 0)");
        }
        
        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "seo_pattern` LIKE  'manufacturer_title'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."seo_pattern` ADD  `manufacturer_title`  text NOT NULL AFTER  `category_meta_description`, ADD `manufacturer_keyword`  text NOT NULL AFTER  `category_meta_description`, ADD `manufacturer_meta_description`  text NOT NULL AFTER  `category_meta_description`  ");
	   		$this->db->query("UPDATE  `" . DB_PREFIX . "seo_pattern` SET `manufacturer_title` = '[manufacturer_name]', `manufacturer_keyword` = '[manufacturer_name]',`manufacturer_meta_description` = '[manufacturer_name]'");
	    }

	    $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "seo_pattern` LIKE  'information_keyword'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."seo_pattern` ADD  `information_keyword`  text NOT NULL AFTER  `information_pages_title`, ADD `information_meta_description`  text NOT NULL AFTER  `information_pages_title`  ");
	   		$this->db->query("UPDATE  `" . DB_PREFIX . "seo_pattern` SET `information_pages_title` = '[information_page_title]', `information_meta_description` = '[information_page_title], [information_page_description]'");
	    }
    }
}

?>