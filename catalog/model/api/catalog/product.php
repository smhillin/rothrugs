<?php
class ModelApiCatalogProduct extends Model {
	public $language_id = 1;

	public  function UploadImageFromString64($imgBase64,$path){
		$imgBase64=substr($imgBase64, strpos($imgBase64, ",")+1);
		$imgBase64 = str_replace(' ', '+', $imgBase64);
		$imgDeCode = base64_decode($imgBase64);
		$im = imagecreatefromstring($imgDeCode);
		if($im == false){
			return $im;
		}
		var_dump($im);
		$fileName = time().  uniqid() . '.jpg' ;
		$success = file_put_contents($path.'/'.$fileName, $imgDeCode);
		if($success){
			return $fileName;
		}else{
			return false;
		}
	}
	public function addProductFromOpt($data, $product_option_value_color){
		$sql_insert_product = "INSERT INTO " . DB_PREFIX . "product
                    SET model = '" . $this->db->escape($data['model']) . "',
                        sku = '" . $this->db->escape($product_option_value_color['sku']) . "',
                        upc = '" . $this->db->escape($product_option_value_color['upc']) . "',
                        ean = '" . $this->db->escape($data['ean']) . "',
                        jan = '" . $this->db->escape($data['jan']) . "',
                        isbn = '" . $this->db->escape($data['isbn']) . "',
                        mpn = '" . $this->db->escape($data['mpn']) . "',
                        location = '" . $this->db->escape($data['location']) . "',
                        quantity = '" . (int) $data['quantity'] . "',
                        minimum = '" . (int) $data['minimum'] . "',
                        subtract = '" . (int) $data['subtract'] . "',
                        stock_status_id = '" . (int) $data['stock_status_id'] . "',
                        date_available = '" . $this->db->escape($data['date_available']) . "',
                        manufacturer_id = '" . (int) $data['manufacturer_id'] . "',
                        shipping = '" . (int) $data['shipping'] . "', price = '" . (float) $product_option_value_color['price'] . "',
                        points = '" . (int) $data['points'] . "', weight = '" . (float) $data['weight'] . "',
                        weight_class_id = '" . (int) $data['weight_class_id'] . "',
                        length = '" . (float) $data['length'] . "',
                        width = '" . (float) $data['width'] . "',
                        height = '" . (float) $data['height'] . "',
                        length_class_id = '" . (int) $data['length_class_id'] . "',
                        status = '" . (int) $data['status'] . "',
                        tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
                        sort_order = '" . (int) $data['sort_order'] . "', date_added = NOW()";
		$this->db->query($sql_insert_product);
		$product_id = $this->db->getLastId();

		if(isset($product_option_value_color['image'])){
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($product_option_value_color['image']) . "' WHERE product_id = '" . (int) $product_id . "'");
		}
		if(isset($data['product_attribute'])){
			foreach($data['product_attribute'] as $product_attribute){
				if($product_attribute['attribute_id']){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "'");

					foreach($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description){
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		if(isset($data['product_store'])){
			foreach($data['product_store'] as $store_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
			}
		}
		$product_description = $data['product_description'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description
				SET product_id = '" . (int)$product_id . "',
					language_id = '1',
					name = '" . $this->db->escape($product_description['name']) . "',
					description = '" . $this->db->escape($product_description['description']) . "',
					tag = '" . $this->db->escape($product_description['tag']) . "',
					meta_title = '" . $this->db->escape($product_description['meta_title']) . "',
					meta_description = '" . $this->db->escape($product_description['meta_description']) . "',
					meta_keyword = '" . $this->db->escape($product_description['meta_keyword']) . "'");
		if(isset($data['product_store'])){
			foreach($data['product_store'] as $store_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
			}
		}
		if(isset($data['product_discount'])){
			foreach($data['product_discount'] as $product_discount){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_discount['customer_group_id'] . "', quantity = '" . (int) $product_discount['quantity'] . "', priority = '" . (int) $product_discount['priority'] . "', price = '" . (float) $product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}
		if(!empty($product_option_value_color)){
			$povc = $product_option_value_color;
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $povc['option_id'] . "', required = '" . (int) $povc['required'] . "'");
			$product_option_id = $this->db->getLastId();
			$sql_option_color = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $povc['option_id'] . "',
									option_value_id = '" . (int) $povc['option_value_id'] . "',
									quantity = '" . (int) $povc['quantity'] . "',
									subtract = '" . (int) $povc['subtract'] . "',
									price = '" . (float) $povc['price'] . "',
									price_prefix = '" . $this->db->escape($povc['price_prefix']) . "',
									points = '" . (int) $povc['points'] . "',
									points_prefix = '" . $this->db->escape($povc['points_prefix']) . "',
									weight = '" . (float) $povc['weight'] . "',
									weight_prefix = '" . $this->db->escape($povc['weight_prefix']) . "',
									msrp ='" . (float) $povc['msrp'] . "',
									upc='" . $povc['upc'] . "',
									sku='" . $povc['sku'] . "'";

			if(!empty($povc['image'])){
				$sql_option_color.= ",image ='" . $povc['image'] . "'";
			}
			if(!empty($povc['option_shape'])){
				$sql_option_color.=",option_shape='" . $povc['option_shape'] . "'";
			}
			$this->db->query($sql_option_color);

			$this->deleteProduct($povc['product_id']);
		}
		if(isset($data['product_option'])){
			foreach($data['product_option'] as $product_option){
				if($product_option['option_id'] != $povc['option_id']){
					if($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image'){
						if(isset($product_option['product_option_value'])){
							if($product_option['type'] != 'select'){
								$product_option['o_cata'] = array();
							}
							if(!isset($product_option['o_cata'])){
								$product_option['o_cata'] = null;
							}

							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', o_cata = '" . $product_option['o_cata'] . "', required = '" . (int) $product_option['required'] . "'");

							$product_option_id = $this->db->getLastId();

							foreach($product_option['product_option_value'] as $product_option_value){
								$sql_add = array();
								$resu = $this->db->query("SHOW TABLE STATUS LIKE \"" . DB_PREFIX . "product_option_value\"");
								if($resu->num_rows){
									$data_ai = array();
									$data_ai = $resu->row;
								}
								if($product_option_value['product_option_value_id'] == ''){
									$ni = $data_ai['Auto_increment'];
								}else{
									$ni = $product_option_value['product_option_value_id'];
								}
								$no_ser = '';
								if(!isset($product_option_value['o_coparent'])){
									$product_option_value['o_coparent'] = null;
								}
								if($product_option['type'] != 'select' || !is_array($product_option_value['o_coparent']) || is_null($product_option_value['o_coparent'])){
									$product_option_value['o_coparent'] = array();
								}

								foreach($product_option_value['o_coparent'] as $key => $value){
									$parent_id = '';
									if(strpos($value, 'z') !== false){
										$product_option_value['o_coparent'][$key] = str_replace('z', $ni . '-', $value);
										$parent_id = str_replace('z', '', $value);
									}else{
										$parent_id = $value;
										$product_option_value['o_coparent'][$key] = $ni . '-' . $value;
									}
									if(!empty($parent_id)){
										$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_relation SET product_option_value_id = '" . (int) $ni . "',  product_id = '" . (int) $product_id . "',parent_id ='" . $parent_id . "'");
									}
									$no_ser .= str_replace($ni, "-", $product_option_value['o_coparent'][$key]);
								}

								$no_ser .= '-';
								$sql = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $product_option['option_id'] . "',
									option_value_id = '" . (int) $product_option_value['option_value_id'] . "',
									quantity = '" . (int) $product_option_value['quantity'] . "',
									subtract = '" . (int) $product_option_value['subtract'] . "',
									price = '" . (float) $product_option_value['price'] . "',
									price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
									points = '" . (int) $product_option_value['points'] . "',
									points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
									weight = '" . (float) $product_option_value['weight'] . "',
									weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "', no_ser = '" . $no_ser . "',  o_coparent = '" . $this->db->escape(json_encode($product_option_value['o_coparent'])) . "',
									sku =  '" . $this->db->escape($product_option_value['sku']) . "',
									option_shape =  '". $this->db->escape($product_option_value['option_shape']) . "',
									msrp ='" . (float) $product_option_value['msrp'] . "'";
								if(!empty($product_option_value['image'])){
									$sql_add[] = "image ='" . $product_option_value['image'] . "'";
								}
								if(!empty($sql_add)){
									$sql .= ',' . implode(',', $sql_add);
								}
								$this->db->query($sql);
							}
						}
					}else{
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
					}
				}
			}
		}

		if(isset($data['product_special'])){
			foreach($data['product_special'] as $product_special){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_special['customer_group_id'] . "', priority = '" . (int) $product_special['priority'] . "', price = '" . (float) $product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if(isset($data['product_image'])){
			foreach($data['product_image'] as $product_image){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int) $product_image['sort_order'] . "'");
			}
		}

		if(isset($data['product_download'])){
			foreach($data['product_download'] as $download_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
			}
		}

		if(isset($data['product_category'])){
			foreach($data['product_category'] as $category_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
			}
		}

		if(isset($data['product_filter'])){
			foreach($data['product_filter'] as $filter_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
			}
		}

		if(isset($data['product_related'])){
			foreach($data['product_related'] as $related_id){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $related_id . "' AND related_id = '" . (int) $product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "'");
			}
		}

		if(isset($data['product_reward'])){
			foreach($data['product_reward'] as $customer_group_id => $product_reward){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $product_reward['points'] . "'");
			}
		}

		if(isset($data['product_layout'])){
			foreach($data['product_layout'] as $store_id => $layout){
				if($layout['layout_id']){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				}
			}
		}
        if (isset($data['keyword'])) {
        	var_dump($data['keyword']); die;
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "' ,lang = 1");
		}
		if(isset($data['product_profiles'])){
			foreach($data['product_profiles'] as $profile){
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $profile['customer_group_id'] . ", `profile_id` = " . (int) $profile['profile_id']);
			}
		}

		$this->cache->delete('product');
		return $product_id;
	}
	public function addProduct($data) {
		$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', 'products'), '/');
		$directory_imge = rtrim('catalog/' . str_replace('../', '','products'), '/') . '/' ;
		
		if (isset($data['upc'])) {
			$upc = $this->db->escape($data['upc']);
		}else{
			$upc = time();
		}

		if(isset($data['manufacturer_name']) && $data['manufacturer_name'] != ''){
			
			$sql = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer 
                        WHERE name = '" . $data['manufacturer_name']."'");

			$manufacturer_id = (int) $sql->row['manufacturer_id'];

			if($manufacturer_id > 0){

				$sql2 = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer_to_store 
                        WHERE manufacturer_id = '" . $manufacturer_id."'");

				$manufacturer_to_store_id = (int) $sql2->row['manufacturer_id'];

				if($manufacturer_to_store_id > 0){

				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $manufacturer_id . "',
						store_id = '0'
						");
				}
				$data['manufacturer_id'] = $manufacturer_id;
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer
			SET name = '" . $this->db->escape($data['manufacturer_name']) . "',
				sort_order = '0'
				");

				$data['manufacturer_id'] = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $data['manufacturer_id'] . "',
						store_id = '0'
						");
			}
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "product
			SET model = '" . $this->db->escape($data['model']) . "',
				sku = '" . $this->db->escape($data['sku']) . "',
				upc = '" . $upc . "',
				ean = '" . $this->db->escape($data['ean']) . "',
				jan = '" . $this->db->escape($data['jan']) . "',
				isbn = '" . $this->db->escape($data['isbn']) . "',
				mpn = '" . $this->db->escape($data['mpn']) . "',
				location = '" . $this->db->escape($data['location']) . "',
				quantity = '" . (int)$data['quantity'] . "',
				minimum = '" . (int)$data['minimum'] . "',
				subtract = '" . (int)$data['subtract'] . "',
				stock_status_id = '" . (int)$data['stock_status_id'] . "',
				date_available = '" . $this->db->escape($data['date_available']) . "',
				manufacturer_id = '" . (int) (isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0) . "',
				shipping = '" . (int)$data['shipping'] . "',
				price = '" . $data['price'] . "',
				points = '" . (int) (isset($data['points']) ? $data['points'] : 0) . "',
				weight = '" . (int) (isset($data['weight']) ? $data['weight'] : 0) . "',
				weight_class_id = '" . (int) (isset($data['weight_class_id']) ? $data['weight_class_id'] : 0) . "',
				length = '" . (float)(isset($data['length']) ? $data['length'] : 0) . "',
				width = '" . (float)$data['width'] . "',
				height = '" . (float)$data['height'] . "',
				length_class_id = '" . (int)$data['length_class_id'] . "',
				status = '" . (int)$data['status'] . "',
				tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
				sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "', date_added = NOW()");

		$product_id = $this->db->getLastId();
		$product_ids[] = $product_id;
		if(isset($data['image'])){
			// $imge_product = $this->UploadImageFromString64($data['image'], $directory);
				// SET image = '" . $this->db->escape($directory_imge . $imge_product) . "'
			// if($imge_product){
				$this->db->query("UPDATE " . DB_PREFIX . "product
				SET image = '" . $this->db->escape($data['image']) . "'
					WHERE product_id = '" . (int) $product_id . "'");
			// }
		}
		$product_description = $data['product_description'];
		if (isset($product_description['meta_description'])) {
			$meta_description = $this->db->escape($product_description['meta_description']);
		}else{
			$meta_description = $this->db->escape($product_description['description']);
		}
		if (isset($product_description['meta_keyword'])) {
			$meta_keyword = $this->db->escape($product_description['meta_keyword']);
		}else{
			$meta_keyword = $this->db->escape($product_description['name']);
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description
				SET product_id = '" . (int)$product_id . "',
					language_id = '1',
					name = '" . $this->db->escape($product_description['name']) . "',
					description = '" . $this->db->escape($product_description['description']) . "',
					tag = '" . $this->db->escape($product_description['tag']) . "',
					meta_title = '" . $this->db->escape($product_description['meta_title']) . "',
					meta_description = '" .  $meta_description  . "',
					meta_keyword = '" . $meta_keyword  . "'");
		if(isset($data['product_store'])){
			foreach($data['product_store'] as $store_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as   $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$this->language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		if(isset($data['product_option'])){
			foreach($data['product_option'] as $product_option){
				if($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image'){
					if(isset($product_option['product_option_value'])){

						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', required = '" . (int) $product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

					foreach($product_option['product_option_value'] as $key=>$product_option_value){
							$sql_add = array();
							if (isset($product_option_value['sku'])) {
								$sku = $this->db->escape($product_option_value['sku']);
							}else{
								$sku = time();
							}
							if (isset($product_option_value['option_shape'])) {
								$option_shape = $this->db->escape($product_option_value['option_shape']);
							}else{
								$option_shape = time();
							}
							$product_option_value['price_prefix'] = "+";
							$sql = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $product_option['option_id'] . "',
									option_value_id = '" . (int) (isset($product_option_value['option_value_id']) ? $product_option_value['option_value_id'] : 0) . "',
									quantity = '" . (int) $product_option_value['quantity'] . "',
									subtract = '" . (int) $product_option_value['subtract'] . "',
									price = '" . (float) $product_option_value['price'] . "',
									price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
									points = '" . (int) $product_option_value['points'] . "',
									points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
									weight = '" . (float) $product_option_value['weight'] . "',
									weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "',
                                    sku =  '" . $sku . "',
                                    option_shape =  '".  $option_shape . "',
									msrp ='" . (float) (isset($product_option_value['msrp']) ? $product_option_value['msrp'] : 0) . "'";
							if(!empty($product_option_value['image'])){
								// $result_image = $this->UploadImageFromString64($product_option_value['image'], $directory);
								$result_image = $product_option_value['image'];
								if($result_image){
									// $result_image = $directory_imge  . $result_image;
									$result_image = $result_image;
									$sql_add[] = "image ='" . $result_image . "'";
									$product_option_value['image'] = $result_image;
								}else{
									unset($product_option_value['image']);
								}
							}



							$related_options = array();
							if(isset($data['related_option'])){
								if(isset($data['related_option'][$key])){
									$related_options = $data['related_option'][$key];
									$product_option_value['option_id'] = $product_option['option_id'];
									$product_option_value['product_id'] = $product_id;
									$product_option_value['required'] = $product_option['required'];
									$product_id_new	= $this->addProductRealtedOption($data, $product_option_value,$related_options);
									if(!empty($product_id_new)){
										if(($key = array_search($product_id, $product_ids)) !== false){
											unset($product_ids[$key]);
										}
										$product_ids[]= $product_id_new;
									}

								}
							}
							if(!empty($sql_add)){
								$sql .= ',' . implode(',', $sql_add);
							}
							$this->db->query($sql);
						}

					}
				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
				}

			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)(isset($product_image['sort_order']) ? $product_image['sort_order'] : 0) . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_profiles'])) {
			foreach ($data['product_profiles'] as $profile) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);
			}
		}

		return $product_ids;
	}
	public function addProductRealtedOption($data, $product_option_value_color,$relatedOptions){
		$options_module = $this->config->get('option_module');
		if($product_option_value_color['option_id'] != $options_module['op_color_id']){
			return array();
		}
		$data['quantity'] = !empty($product_option_value_color['quantity'])?$product_option_value_color['quantity']:$data['quantity'];
		$sku = "RR_" . uniqid() . time();
		$sql_insert_product = "INSERT INTO " . DB_PREFIX . "product
									SET model = '" . $this->db->escape($data['model']) . "',
										sku = '" . $sku . "',
										upc = '" . $this->db->escape($data['upc']) . "',
										ean = '" . $this->db->escape($data['ean']) . "',
										jan = '" . $this->db->escape($data['jan']) . "',
										isbn = '" . $this->db->escape($data['isbn']) . "',
										mpn = '" . $this->db->escape($data['mpn']) . "',
										location = '" . $this->db->escape($data['location']) . "',
										quantity = '" . (int) $data['quantity'] . "',
										minimum = '" . (int) $data['minimum'] . "',
										subtract = '" . (int) $data['subtract'] . "',
										stock_status_id = '" . (int) $data['stock_status_id'] . "',
										date_available = '" . $this->db->escape($data['date_available']) . "',
										manufacturer_id = '" . (int) $data['manufacturer_id'] . "',
										shipping = '" . (int) $data['shipping'] . "', price = '" . (float) $product_option_value_color['price'] . "',
										points = '" . (int) $data['points'] . "', weight = '" . (float) $data['weight'] . "',
										weight_class_id = '" . (int) $data['weight_class_id'] . "',
										length = '" . (float) $data['length'] . "',
										width = '" . (float) $data['width'] . "',
										height = '" . (float) $data['height'] . "',
										length_class_id = '" . (int) $data['length_class_id'] . "',
										status = '" . (int) $data['status'] . "',
										tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
										sort_order = '" . (int) $data['sort_order'] . "', date_added = NOW()";
		$this->db->query($sql_insert_product);
		$product_id = $this->db->getLastId();

		if(isset($product_option_value_color['image'])){
				$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $product_option_value_color['image'] . "' WHERE product_id = '" . (int) $product_id . "'");
		}
		if(isset($data['product_attribute'])){
			foreach($data['product_attribute'] as $product_attribute){
				if($product_attribute['attribute_id']){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "'");

					foreach($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description){
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		if(isset($data['product_store'])){
			foreach($data['product_store'] as $store_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
			}
		}
		$product_description = $data['product_description'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description
				SET product_id = '" . (int)$product_id . "',
					language_id = '".$this->language_id."',
					name = '" . $this->db->escape($product_description['name']) . "',
					description = '" . $this->db->escape($product_description['description']) . "',
					tag = '" . $this->db->escape($product_description['tag']) . "',
					meta_title = '" . $this->db->escape($product_description['meta_title']) . "',
					meta_description = '" . $this->db->escape($product_description['meta_description']) . "',
					meta_keyword = '" . $this->db->escape($product_description['meta_keyword']) . "'");
		if(isset($data['product_discount'])){
			foreach($data['product_discount'] as $product_discount){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_discount['customer_group_id'] . "', quantity = '" . (int) $product_discount['quantity'] . "', priority = '" . (int) $product_discount['priority'] . "', price = '" . (float) $product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if(!empty($product_option_value_color) && !empty($options_module) ){

			$povc = $product_option_value_color;
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $povc['option_id'] . "', required = '" . (int) $povc['required'] . "'");
			$product_option_id = $this->db->getLastId();
			$sql_option_color = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $povc['option_id'] . "',
									option_value_id = '" . (int) $povc['option_value_id'] . "',
									quantity = '" . (int) $data['quantity'] . "',
									subtract = '" . (int) $povc['subtract'] . "',
									price = '" . (float) $povc['price'] . "',
									price_prefix = '" . $this->db->escape($povc['price_prefix']) . "',
									points = '" . (int) $povc['points'] . "',
									points_prefix = '" . $this->db->escape($povc['points_prefix']) . "',
									weight = '" . (float) $povc['weight'] . "',
									weight_prefix = '" . $this->db->escape($povc['weight_prefix']) . "'";


			$this->db->query($sql_option_color);

			if(!empty($relatedOptions) && isset($relatedOptions['product_option_value'])){

				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $options_module['op_size_id'] . "', required = '1'");
				$product_size_option_id = $this->db->getLastId();
			//	var_dump($product_size_option_id);die;
				foreach($relatedOptions['product_option_value'] as $relatedOption){

					if(!empty($relatedOption['option_size'])){
						$sku = "RR_" . $product_option_id . "_"  . uniqid() . time();


						$sql_option_size = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_size_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $options_module['op_size_id']  . "',
									option_value_id = '" . (int) $relatedOption['option_size'] . "',
									quantity = '" . (int) $povc['quantity'] . "',
									subtract = '" . (int) $povc['subtract'] . "',
									price = '" . (float) $povc['price'] . "',
									price_prefix = '" . $this->db->escape($povc['price_prefix']) . "',
									points = '" . (int) $povc['points'] . "',
									points_prefix = '" . $this->db->escape($povc['points_prefix']) . "',
									weight = '" . (float) $povc['weight'] . "',
									weight_prefix = '" . $this->db->escape($povc['weight_prefix']) . "',
									msrp ='" . (float) $povc['msrp'] . "',
									sku ='".$sku."',
									option_shape='".(int)$relatedOption['option_shape']."'" ;

						$this->db->query($sql_option_size);

					}
				}
			}


			$this->deleteProduct($povc['product_id']);
		}
		if(isset($data['product_option'])){
			foreach($data['product_option'] as $product_option){
				if($product_option['option_id'] != $povc['option_id']){
					if($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image'){
						if(isset($product_option['product_option_value'])){


							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', required = '" . (int) $product_option['required'] . "'");

							$product_option_id = $this->db->getLastId();

							foreach($product_option['product_option_value'] as $product_option_value){
								$sql_add = array();
								if(!isset($product_option_value['quantity'])){
									$product_option_value['quantity'] = 1;
								}
								$sql = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $product_option['option_id'] . "',
									option_value_id = '" . (int) $product_option_value['option_value_id'] . "',
									quantity = '" . (int) $product_option_value['quantity'] . "',
									subtract = '" . (int) $product_option_value['subtract'] . "',
									price = '" . (float) $product_option_value['price'] . "',
									price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
									points = '" . (int) $product_option_value['points'] . "',
									points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
									weight = '" . (float) $product_option_value['weight'] . "',
									weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "', no_ser = '" . $no_ser . "',  o_coparent = '" . $this->db->escape(json_encode($product_option_value['o_coparent'])) . "',
									msrp ='" . (float) $product_option_value['msrp'] . "'";
								if(!empty($product_option_value['image'])){
									$sql_add[] = "image ='" . $product_option_value['image'] . "'";
								}
								if(!empty($sql_add)){
									$sql .= ',' . implode(',', $sql_add);
								}
								$this->db->query($sql);
							}
						}
					}else{
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
					}
				}
			}
		}

		if(isset($data['product_special'])){
			foreach($data['product_special'] as $product_special){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_special['customer_group_id'] . "', priority = '" . (int) $product_special['priority'] . "', price = '" . (float) $product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if(isset($data['product_image'])){
			foreach($data['product_image'] as $product_image){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int) $product_image['sort_order'] . "'");
			}
		}

		if(isset($data['product_download'])){
			foreach($data['product_download'] as $download_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
			}
		}

		if(isset($data['product_category'])){
			foreach($data['product_category'] as $category_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
			}
		}

		if(isset($data['product_filter'])){
			foreach($data['product_filter'] as $filter_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
			}
		}

		if(isset($data['product_related'])){
			foreach($data['product_related'] as $related_id){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $related_id . "' AND related_id = '" . (int) $product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "'");
			}
		}

		if(isset($data['product_reward'])){
			foreach($data['product_reward'] as $customer_group_id => $product_reward){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $product_reward['points'] . "'");
			}
		}

		if(isset($data['product_layout'])){
			foreach($data['product_layout'] as $store_id => $layout){
				if($layout['layout_id']){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				}
			}
		}

		if(isset($data['product_profiles'])){
			foreach($data['product_profiles'] as $profile){
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $profile['customer_group_id'] . ", `profile_id` = " . (int) $profile['profile_id']);
			}
		}

		$this->cache->delete('product');
		return $product_id;
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== null) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductsBySku($sku) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product   WHERE sku = ".$sku." ");

		return $query->row;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix'],
					'msrp'					  => $product_option_value['msrp'],
					'option_shape'			  => $product_option_value['option_shape'],

				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getProfiles($product_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_profile` WHERE product_id = " . (int)$product_id)->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== null) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsOutOfStock() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE status <= 0");

		return $query->row['total'];
	}
	public function deleteProduct($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_profile WHERE product_id = " . (int) $product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $product_id . "'");

		$this->cache->delete('product');
	}
	public function editProduct($product_id, $data){
		// echo "<pre>";
		// var_dump($data); die('ok');
		if(isset($data['manufacturer_name']) && $data['manufacturer_name'] != ''){
			
			$sql = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer 
                        WHERE name = '" . $data['manufacturer_name']."'");

			$manufacturer_id = (int) $sql->row['manufacturer_id'];

			if($manufacturer_id > 0){

				$sql2 = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer_to_store 
                        WHERE manufacturer_id = '" . $manufacturer_id."'");

				$manufacturer_to_store_id = (int) $sql2->row['manufacturer_id'];

				
				if($manufacturer_to_store_id > 0){

				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $manufacturer_id . "',
						store_id = '0'
						");
				}
				$data['manufacturer_id'] = $manufacturer_id;
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer
			SET name = '" . $this->db->escape($data['manufacturer_name']) . "',
				sort_order = '0'
				");

				$data['manufacturer_id'] = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $data['manufacturer_id'] . "',
						store_id = '0'
						");
			}
		}


		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape(isset($data['upc']) ? $data['upc'] : '') . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int) $data['quantity'] . "', minimum = '" . (int) $data['minimum'] . "', subtract = '" . (int) $data['subtract'] . "', stock_status_id = '" . (int) $data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int) (isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0) . "', shipping = '" . (int) $data['shipping'] . "', price = '" .  $data['price'] . "', points = '" . (int) (isset($data['points']) ? $data['points'] : 0) . "', weight = '" . (float) $data['weight'] . "', weight_class_id = '" . (int) (isset($data['weight_class_id']) ? $data['weight_class_id'] : 0) . "', length = '" . (float) (isset($data['length']) ? $data['length'] : 0) . "', width = '" . (float) $data['width'] . "', height = '" . (float) $data['height'] . "', length_class_id = '" . (int) $data['length_class_id'] . "', status = '" . (int) $data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int) (isset($data['sort_order']) ? $data['sort_order'] :0) . "', date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");
		// var_dump($data); die;
		if(isset($data['image'])){
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int) $product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");
		// var_dump($data['product_description']); die;

		// foreach($data['product_description'] as  $value){
			// var_dump($value['name']); die;
			// $a = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '1', name = '" . $data['product_description']['name'] . "', description = '" . $this->db->escape($data['product_description']['description']) . "', tag = '" . $this->db->escape($data['product_description']['tag']) . "', meta_title = '" . $this->db->escape($data['product_description']['meta_title']) . "', meta_description = '" . $this->db->escape($data['product_description']['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['product_description']['meta_keyword']) . "'";
			// var_dump($a); die;
		if (isset($data['product_description'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '1', name = '" . $data['product_description']['name'] . "', description = '" . $this->db->escape($data['product_description']['description']) . "', tag = '" . $this->db->escape($data['product_description']['tag']) . "', meta_title = '" . $this->db->escape($data['product_description']['meta_title']) . "', meta_description = '" . $this->db->escape(isset($data['product_description']['meta_description']) ? $data['product_description']['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($data['product_description']['meta_keyword']) ? $data['product_description']['meta_keyword'] : '') . "'");
		}
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_store'])){
			foreach($data['product_store'] as $store_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "'");

		if(!empty($data['product_attribute'])){
			foreach($data['product_attribute'] as $product_attribute){
				if($product_attribute['attribute_id']){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "'");

					foreach($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description){
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_relation WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combine WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_option'])){
			// echo "<pre>";
			// var_dump($data['product_option']); die;
			$dataArrCombine = array();
			foreach($data['product_option'] as $product_option){
				if($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image'){

					if(isset($product_option['product_option_value'])){
						if($product_option['type'] != 'select'){
							$product_option['o_cata'] = array();
						}

						$sql_option = "INSERT INTO " . DB_PREFIX . "product_option
							SET product_id = '" . (int) $product_id . "',
								option_id = '" . (int) $product_option['option_id'] . "',
								required = '" . (int) $product_option['required'] . "'";

						$this->db->query($sql_option);
						$product_option_id = $this->db->getLastId();
						// var_dump($product_option_id);
						// echo "<pre>";
						// var_dump($product_option); die;
						$array_combine = array();
						foreach($product_option['product_option_value'] as $product_option_value){
							if(isset($data['re_combine'])){
								$product_option_value_info = $this->getOptionValue($product_option_value['option_value_id']);
								$array_combine[] = $product_option_value_info['name'];
							}
							$product_option_value['price_prefix'] = "+";
							$sql_update = "INSERT INTO " . DB_PREFIX . "product_option_value
								SET product_option_id = '" . (int) $product_option_id . "',
									product_id = '" . (int) $product_id . "',
									option_id = '" . (int) $product_option['option_id'] . "',
									option_value_id = '" . (int) (isset($product_option_value['option_value_id']) ? $product_option_value['option_value_id'] : 0) . "',
									quantity = '" . (int) $product_option_value['quantity'] . "',
									subtract = '" . (int) $product_option_value['subtract'] . "',
									price = '" . (float) $product_option_value['price'] . "',
									price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
									points = '" . (int) $product_option_value['points'] . "',
									points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
									weight = '" . (float) $product_option_value['weight'] . "',
									weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "',
									msrp ='" . (float) (isset($product_option_value['msrp']) ? $product_option_value['msrp'] : 0) . "'";
							if(isset($product_option_value['option_shape'])){

								$sql_update .= ",option_shape ='" . $product_option_value['option_shape'] . "'";
								if(!empty($data['image'])){
									$product_option_value['image'] = $data['image'];
								}

								if(isset($data['sku'])){
									$sql_update .= ",sku ='" . $data['sku'] . "'";
								}
								if(isset($data['upc'])){
									$sql_update .= ",upc ='" . $data['upc'] . "'";
								}
							}
							$sql_update .=",image ='" . $product_option_value['image'] . "'";
							$this->db->query($sql_update);
						}
					}
				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int) $product_option['product_option_id'] . "', product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
				}
				if(!empty($array_combine) && isset($data['re_combine'])){
					array_push($dataArrCombine, $array_combine);
				}
			}




			if(!isset($data['re_combine'])){
				if(isset($data['product_combines'])){
					foreach($data['product_combines'] as $key => $product_combine){
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_combine SET id = '" . (int) $key . "', data = '" . $product_combine['data'] . "', sku ='" . $product_combine['sku'] . "',product_id='" . $product_id . "'");
					}
				}
			}else{
				$combineOptions = $this->combos($dataArrCombine);
				foreach($combineOptions as $cboption){
					$name_combine = implode(' ', $cboption);
					$sku_combine = 'RR_' . uniqid() . time();
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_combine SET product_id = '" . (int) $product_id . "',data='" . $name_combine . "',sku ='" . $sku_combine . "'");
				}
			}
		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_discount'])){
			foreach($data['product_discount'] as $product_discount){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_discount['customer_group_id'] . "', quantity = '" . (int) $product_discount['quantity'] . "', priority = '" . (int) $product_discount['priority'] . "', price = '" . (float) $product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_special'])){
			foreach($data['product_special'] as $product_special){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_special['customer_group_id'] . "', priority = '" . (int) $product_special['priority'] . "', price = '" . (float) $product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_image'])){
			foreach($data['product_image'] as $product_image){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int) (isset($product_image['sort_order']) ? $product_image['sort_order'] : 0) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_download'])){
			foreach($data['product_download'] as $download_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
			}
		}

		// $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");

		// if(isset($data['product_category'])){
		// 	foreach($data['product_category'] as $category_id){
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
		// 	}
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_filter'])){
			foreach($data['product_filter'] as $filter_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int) $product_id . "'");

		if(isset($data['product_related'])){
			foreach($data['product_related'] as $related_id){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $related_id . "' AND related_id = '" . (int) $product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_reward'])){
			foreach($data['product_reward'] as $customer_group_id => $value){
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $value['points'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int) $product_id . "'");

		if(isset($data['product_layout'])){
			foreach($data['product_layout'] as $store_id => $layout){
				if($layout['layout_id']){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $product_id . "'");

		if($data['keyword']){
			// var_dump($data['keyword']);
			// die('sddd');
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', lang =1 ");
		}
		// die('sss');
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_profile` WHERE product_id = " . (int) $product_id);

		if(isset($data['product_profiles'])){
			foreach($data['product_profiles'] as $profile){
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $profile['customer_group_id'] . ", `profile_id` = " . (int) $profile['profile_id']);
			}
		}

		$this->cache->delete('product');
	}

	public function editProductManufacturer($product_id, $data){
		// echo "<pre>";
		// var_dump($data); die('ok');
		if(isset($data['manufacturer_name']) && $data['manufacturer_name'] != ''){
			
			$sql = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer 
                        WHERE name = '" . $data['manufacturer_name']."'");

			$manufacturer_id = (int) $sql->row['manufacturer_id'];

			if($manufacturer_id > 0){

				$sql2 = $this->db->query("SELECT manufacturer_id 
                        FROM ".DB_PREFIX."manufacturer_to_store 
                        WHERE manufacturer_id = '" . $manufacturer_id."'");

				$manufacturer_to_store_id = (int) $sql2->row['manufacturer_id'];

				
				if($manufacturer_to_store_id > 0){

				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $manufacturer_id . "',
						store_id = '0'
						");
				}
				$data['manufacturer_id'] = $manufacturer_id;
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer
			SET name = '" . $this->db->escape($data['manufacturer_name']) . "',
				sort_order = '0'
				");

				$data['manufacturer_id'] = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store
					SET manufacturer_id = '" . $data['manufacturer_id'] . "',
						store_id = '0'
						");
			}
		}


		$this->db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = '" . (int) (isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0) . "', date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");
		// var_dump($data); die;
	}

	public function updateStatusProduct($product_id, $status)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '".$status."', date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");
		$this->cache->delete('product');
	}
        /*public function editProductSize($product_id, $data){
           $t =  $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int) $product_id . "' and filter_id in (SELECT filter_id FROM ".DB_PREFIX."filter WHERE filter_group_id =3)" );
           if(isset($data['product_filter'])){
                foreach($data['product_filter'] as $filter_id){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
                }
            }
            $this->cache->delete('product');
	}*/
        public function editProductSizeOption($product_id, $size_name){
            $query = $this->db->query("SELECT option_id FROM " .DB_PREFIX . "option_description WHERE name = 'Size'");
            $option_id = $query->row['option_id'];
            $query = $this->db->query("SELECT * FROM  " . DB_PREFIX . "product_option_value WHERE product_id =  $product_id and option_id = $option_id
                                        AND option_value_id
                                        IN (
                                        SELECT option_value_id
                                        FROM " . DB_PREFIX . "option_value_description
                                        WHERE name =  ".'"'.$size_name.'"'."
                                        )");
            if($query->row)
            {
                $product_option_value_id = $query->row['product_option_value_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int) $product_option_value_id . "'" );
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_relation WHERE product_option_value_id = '" . (int) $product_option_value_id . "' and product_id=$product_id" );
                $this->cache->delete('product');
                return 1;
            }
	}
        
        public function editProductSpecs($product_id, $data){
            if(isset($data['product_attribute'])){
                foreach($data['product_attribute'] as $product_attribute){
                    if(isset($product_attribute['attribute_id']))
                    {
                        $attribute_id = $product_attribute['attribute_id'];
                    }
                    elseif(isset($product_attribute['attribute_name']))
                    {
                        $sql = $this->db->query("SELECT attribute_id 
                            FROM ".DB_PREFIX."attribute_description 
                            WHERE name = '".trim($product_attribute['attribute_name'])."'");
                        $attribute_id = (int) $sql->row['attribute_id'];
                   }

                    if ($attribute_id) {
                        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$attribute_id . "'");
                        foreach ($product_attribute['product_attribute_description'] as   $product_attribute_description) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$this->language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
                        }
                    }
                }
            }
	}
}