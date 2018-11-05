<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * API controller Product
 *
 * @author Lenovo
 */
class ControllerModuleProduct extends Controller {

    private $error = array();
    private $_api;

    public function index() {

        $this->load->language('api/manual');
        $this->load->library('api');
        $this->_api = new Api($this->registry);

        if (!$this->checkToken($this->request->get['token'])) {
            $data['status'] = false;
            $data['error'] = 'token  unvalivable!';
            echo json_encode($data);
            exit();
        }
        // if (!$this->_api->checkApiKey($this->request->get['api_key'])) {
        //     $data['status'] = false;
        //     $data['error'] = 'api key  unvalivable!';
        //     echo json_encode($data);
        //     exit();
        // } else {
            if (isset($this->request->get['action'])) {
                switch ($this->request->get['action']) {
                    case 'list':
                        $this->getList();
                        break;
                    case 'sku':
                        $this->getSku();
                        break;
                    case 'upload_sub_image':
                        $this->upload_sub_image();
                        break;
					// case 'upload_main_image':
					// 	$this->upload_main_image();
					// 	break;
                    case 'get_weight_class':
                        $this->get_weight_class();
                        break;
                    case 'get_tax_classes':
                        $this->get_tax_classes();
                        break;
                    case 'get_length_class':
                        $this->get_length_class();
                        break;
                    case 'insert':
                        $this->insert();
                        break;
                    case 'updateinsert':
                        $this->updateInsert();
                        break;
                    case 'hideproduct':
                        $this->hideProduct();
                        break;
                    case 'statusproduct':
                        $this->statusProduct();
                        break;
                    
                    case 'search':
                        $this->search();
                        break;
                    case 'delete':
                        $this->delete();
                        break;
                    default :
                        $this->getList();
                        break;
                }
            }
        // }
    }

    private function checkToken($token) {
        $data = $this->_api->getByToken($token);
        if (empty($data)) {
            return false;
        } else {
            return true;
        }
    }

    protected function getSku() {

        $this->load->model('api/catalog/product');

        $products = $this->model_api_catalog_product->getProducts();

        foreach ($products as $product) {
            $data[] = array(
                'product_id' => $product['product_id'],
                'sku' => $product['sku'],
            );
        }
        $this->response->setOutput(json_encode($data));
    }

    protected function getList() {

        $this->load->model('api/catalog/product');
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


        $data = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');


        $products = $this->model_api_catalog_product->getProducts($filter_data);

        foreach ($products as $product) {
            if (is_file(DIR_IMAGE . $product['image'])) {
                $image = $this->model_tool_image->resize($product['image'], 40, 40);
            } else {
                $image = '';
            }
            $special = false;

            $product_specials = $this->model_api_catalog_product->getProductSpecials($product['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                    $special = $product_special['price'];

                    break;
                }
            }
            $product_options = $this->model_api_catalog_product->getProductOptions($product['product_id']);

//			$product_attribute = array();
//			foreach($product_options as $product_option){
//				$product_attribute_values = array();
//				foreach($product_option['product_option_value'] as $product_option_value){
//					$product_attribute_value = array();
//					foreach($product_option_value as $key_option => $value){
//						$new_key = str_replace('option', 'attribute',$key_option);
//						$product_attribute_value[$new_key] = $value;
//					}
//					$product_attribute_values[] = $product_attribute_value;
//				}
//
//				$product_attribute[] = array(
//					'name' =>$product_option['name'],
//					'type' =>$product_option['type'],
//					'value'=>$product_option['value'],
//					'image'=>$product_option['image'],
//					'attribute_id'=>$product_option['option_id'],
//					'required'=>$product_option['required'],
//					'product_attrubute_value'=>$product_attribute_values
//				);
//
//			}
            $data[] = array(
                'product_id' => $product['product_id'],
                'image' => $image,
                'name' => $product['name'],
                'model' => $product['model'],
                'price' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'special' => $special,
                'quantity' => $product['quantity'],
                'status' => $product['status'],
                'sku' => $product['sku'],
                'upc' => $product['upc'],
                'ean' => $product['ean'],
                'jan' => $product['jan'],
                'isbn' => $product['isbn'],
                'mpn' => $product['mpn'],
                'options' => $product_options,
            );
        }
        $this->response->setOutput(json_encode($data));
    }

    public function get() {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $json = array('success' => true);

        # -- $_GET params ------------------------------

        if (isset($this->request->get['id'])) {
            $product_id = $this->request->get['id'];
        } else {
            $product_id = 0;
        }

        # -- End $_GET params --------------------------

        $product = $this->model_catalog_product->getProduct($product_id);

        # product image
        if ($product['image']) {
            $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
        } else {
            $image = '';
        }

        #additional images
        $additional_images = $this->model_catalog_product->getProductImages($product['product_id']);
        $images = array();

        foreach ($additional_images as $additional_image) {
            $images[] = $this->model_tool_image->resize($additional_image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
        }

        #specal
        if ((float) $product['special']) {
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $special = false;
        }

        #discounts
        $discounts = array();
        $data_discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

        foreach ($data_discounts as $discount) {
            $discounts[] = array(
                'quantity' => $discount['quantity'],
                'price' => $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')))
            );
        }

        #options
        $options = array();

        foreach ($this->model_catalog_product->getProductOptions($product['product_id']) as $option) {
            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                $option_value_data = array();

                foreach ($option['option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                        } else {
                            $price = false;
                        }

                        $option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix']
                        );
                    }
                }

                $options[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option_value_data,
                    'required' => $option['required']
                );
            } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                $options[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option['option_value'],
                    'required' => $option['required']
                );
            }
        }

        #minimum
        if ($product['minimum']) {
            $minimum = $product['minimum'];
        } else {
            $minimum = 1;
        }

        $json['product'] = array(
            'id' => $product['product_id'],
            'name' => $product['name'],
            'description' => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
            'meta_description' => $product['meta_description'],
            'meta_keyword' => $product['meta_keyword'],
            'tag' => $product['tag'],
            'model' => $product['model'],
            'sku' => $product['sku'],
            'upc' => $product['upc'],
            'ean' => $product['ean'],
            'jan' => $product['jan'],
            'isbn' => $product['isbn'],
            'mpn' => $product['mpn'],
            'location' => $product['location'],
            'quantity' => $product['quantity'],
            'stock_status' => $product['stock_status'],
            'image' => $image,
            'images' => $images,
            'manufacturer_id' => $product['manufacturer_id'],
            'manufacturer' => $product['manufacturer'],
            // $product['price'];
            'price' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
            // $product['special'];
            'special' => $special,
            'reward' => $product['reward'],
            'points' => $product['points'],
            'tax_class_id' => $product['tax_class_id'],
            'date_available' => $product['date_available'],
            'weight' => $product['weight'],
            'weight_class_id' => $product['weight_class_id'],
            'length' => $product['length'],
            'width' => $product['width'],
            'height' => $product['height'],
            'length_class_id' => $product['length_class_id'],
            'subtract' => $product['subtract'],
            'rating' => (int) $product['rating'],
            'reviews' => (int) $product['reviews'],
            'minimum' => $minimum,
            'sort_order' => $product['sort_order'],
            'status' => $product['status'],
            'date_added' => $product['date_added'],
            'date_modified' => $product['date_modified'],
            'viewed' => $product['viewed'],
            'discounts' => $discounts,
            'options' => $options,
            'attribute_groups' => $this->model_catalog_product->getProductAttributes($product['product_id'])
        );


        if ($this->debug) {
            echo '<pre>';
            print_r($json);
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }

    //put your code here

    protected function insert() {
        $this->load->language('api/product');
        $this->load->model('api/catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $produc_insert = $this->request->post;
            // echo "<pre>";
            // var_dump($produc_insert['sku']); die;
            $produc_insert['product_store'] = array(0);
            $product_ids = $this->model_api_catalog_product->addProduct($this->request->post);
            $products = array();
            foreach ($product_ids as $product_id) {
                $product = $this->model_api_catalog_product->getProduct($product_id);
                if (!empty($product)) {
                    $product['option'] = $this->model_api_catalog_product->getProductOptions($product_id);
                    $products[] = $product;
                }
            }
            $data = array('status' => true, 'data' => $products);

            return $this->response->setOutput(json_encode($data));
            // exit();
        }

        $this->getError();
    }

    protected function delete() {
        $this->load->language('api/product');
        $this->load->model('api/catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $data = $this->request->post;
            $sku = $data['sku'];
            $product = $this->model_api_catalog_product->getProductsBySku($sku);
            if ($product) {
                $product_id = $product['product_id'];
                // var_dump($product_id); die;
                $this->model_api_catalog_product->deleteProduct($product_id);
                $data = array('status' => true, 'data' => $product_id);
                return $this->response->setOutput(json_encode($data));
            }
        }

        $this->getError();
    }

    protected function updateInsert() {
        $this->load->language('api/product');
        $this->load->model('api/catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $produc_insert = $this->request->post;
            // var_dump($produc_insert);die;
            $check = $this->model_api_catalog_product->getProductsBySku($produc_insert['sku']);
            // var_dump($productid = $check); die;
            
            if (empty($check)) {
                $produc_insert['product_store'] = array(0);
                $product_ids = $this->model_api_catalog_product->addProduct($this->request->post);
                $products = array();
                foreach ($product_ids as $product_id) {
                    $product = $this->model_api_catalog_product->getProduct($product_id);
                    if (!empty($product)) {
                        $product['option'] = $this->model_api_catalog_product->getProductOptions($product_id);
                        $products[] = $product;
                    }
                }
                // $data = array('status'=>true,'data'=>$products);
                $data = array('status' => true, 'data' => 'insert');
                return $this->response->setOutput(json_encode($data));
            } else {
                // die('dsdsdsd');
                $product_id = $check['product_id'];
                $produc_insert['product_store'] = array(0);
                $product_ids = $this->model_api_catalog_product->editProduct($product_id, $this->request->post);
                // var_dump($product_ids); die('vvv');
                // $products = array();
                // foreach ($product_ids as $product_id) {
                //     $product = $this->model_api_catalog_product->getProduct($product_id);
                //     if (!empty($product)) {
                //         $product['option'] = $this->model_api_catalog_product->getProductOptions($product_id);
                //         $products[] = $product;
                //     }
                // }
                // $data = array('status'=>true,'data'=>$products);
                $data = array('status' => true, 'data' => 'update');
                // var_dump($data); die;
                return $this->response->setOutput(json_encode($data));
            }
        }

        $this->getError();
    }

    protected function hideProduct() {
        $this->load->language('api/product');
        $this->load->model('api/catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $produc_insert = $this->request->post;
            $check = $this->model_api_catalog_product->getProductsBySku($produc_insert['sku']);
            if ( ! empty($check)) {
                $product_id = $check['product_id'];
                $status = $produc_insert['status'];
                // echo "<pre>";
                // var_dump($check);
                // die;
                // updata status = 0

                $this->model_api_catalog_product->updateStatusProduct($product_id, $status);
                $data = array('status' => true, 'data' => 'hideproduct');
                return $this->response->setOutput(json_encode($data));
            }
        }

        $this->getError();
    }

    protected function statusProduct() {
        $this->load->language('api/product');
        $this->load->model('api/catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $produc_insert = $this->request->post;
            $check = $this->model_api_catalog_product->getProductsBySku($produc_insert['sku']);
            if ( ! empty($check)) {
                $product_id = $check['product_id'];
                $data = array('code' => 200 ,'status' => $check['status'], 'data' => 'statusProduct');
                return $this->response->setOutput(json_encode($data));
            }
        }

        $this->getError();
    }

    protected function getError() {
        $data['error'] = array();
        $data['status'] = true;
        if (isset($this->error['warning'])) {

            $data['error']['error_warning'] = $this->error['warning'];
        }
        if (isset($this->error['name'])) {
            $data['error']['error_name'] = $this->error['name'];
        }
        if (isset($this->error['meta_title'])) {
            $data['error']['error_meta_title'] = $this->error['meta_title'];
        }

        if (isset($this->error['model'])) {
            $data['error']['error_model'] = $this->error['model'];
        }

        if (isset($this->error['date_available'])) {
            $data['error']['error_date_available'] = $this->error['date_available'];
        }
        if (!empty($data['error'])) {
            $data['status'] = false;
        }
        $this->response->setOutput(json_encode($data));
    }

    protected function validateForm() {

        $product_description = $this->request->post['product_description'];
        // var_dump($product_description);die('dd');

        if ((utf8_strlen($product_description['name']) < 3) || (utf8_strlen($product_description['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($product_description['meta_title']) < 3) || (utf8_strlen($product_description['meta_title']) > 255)) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        /*if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $this->error['model'] = $this->language->get('error_model');
        }*/

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

//	public function upload_main_image() {
//		$this->load->language('common/filemanager');
//
//		$json = array();
//
//		// Check user has permission
//
//
//		// Make sure we have the correct directory
//
//			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', 'products'), '/');
//
//
//		// Check its a directory
//		if (!is_dir($directory)) {
//			$json['error']['error_directory'] = $this->language->get('error_directory');
//		}
//
//		if (!$json) {
//			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
//				// Sanitize the filename
//				$image_name = explode('.', $this->request->files['file']['name']);
//				$filename = basename(html_entity_decode($image_name[0], ENT_QUOTES, 'UTF-8').'_'.time().  uniqid());
//				$filename .= '.'.$image_name[1];
//
//				// Validate the filename length
//				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
//					$json['error']['error_filename'] = $this->language->get('error_filename');
//				}
//
//				// Allowed file extension types
//				$allowed = array(
//					'jpg',
//					'jpeg',
//					'gif',
//					'png'
//				);
//
//				if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
//					$json['error']['error_filetype'] = $this->language->get('error_filetype');
//				}
//
//				// Allowed file mime types
//				$allowed = array(
//					'image/jpeg',
//					'image/pjpeg',
//					'image/png',
//					'image/x-png',
//					'image/gif'
//				);
//
//				if (!in_array($this->request->files['file']['type'], $allowed)) {
//					$json['error']['error_filetype'] = $this->language->get('error_filetype');
//				}
//
//				// Check to see if any PHP files are trying to be uploaded
//				$content = file_get_contents($this->request->files['file']['tmp_name']);
//
//				if (preg_match('/\<\?php/i', $content)) {
//					$json['error']['error_filetype'] = $this->language->get('error_filetype');
//				}
//
//				// Return any upload error
//				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
//					$json['error']['error_upload'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
//				}
//			} else {
//				$json['error']['error_upload'] = $this->language->get('error_upload');
//			}
//		}
//
//		if (!$json) {
//			move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename);
//			$json['status'] = true;
//			$json['success'] = $this->language->get('text_uploaded');
//			$json['image'] = rtrim('catalog/' . str_replace('../', '','products'), '/') . '/' . $filename;
//
//		}
//		if(!empty($json['error'])){
//			$json['status'] = false;
//		}
//		$this->response->setOutput(json_encode($json));
//	}
    public function upload_sub_image() {
        $this->load->language('common/filemanager');

        $json = array();


        if (isset($this->request->files['image']) && $this->request->files['image']['tmp_name']) {

            for ($idx = 0; $idx < count($this->request->files['image']['name']); $idx++) {
                $image_name = explode('.', $this->request->files['image']['name'][$idx]);

                $filename = basename(html_entity_decode($image_name[0], ENT_QUOTES, 'UTF-8') . '_' . time() . uniqid());
                $filename .= '.' . $image_name[1];
                if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                $directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace('../', '', 'products/'), '/');
                if (!is_dir($directory)) {
                    $json['error']['error_directory'] = $this->language->get('error_directory');
                }

                $allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif',
                    'application/x-shockwave-flash'
                );
                if (!in_array($this->request->files['image']['type'][$idx], $allowed)) {
                    $json['error']['error_file_type'] = $this->language->get('error_file_type');
                }

                $allowed = array(
                    '.jpg',
                    '.jpeg',
                    '.gif',
                    '.png',
                    '.flv'
                );

                if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
                    $json['error']['error_file_type'] = $this->language->get('error_file_type');
                }

// Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['image']['tmp_name'][$idx]);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error']['error_file_type'] = $this->language->get('error_file_type');
                }

                if ($this->request->files['image']['error'][$idx] != UPLOAD_ERR_OK) {
                    $json['error']['error_upload'] = 'error_upload_' . $this->request->files['image']['error'][$idx];
                }

                if (!isset($json['error'])) {
// ZZZ
                    $new_filename = $directory . '/' . $filename;
                    if (@move_uploaded_file($this->request->files['image']['tmp_name'][$idx], $new_filename)) {
                        $json['success'] = $this->language->get('text_uploaded');
                        $json['status'] = true;
                        $json['product_image'][] = rtrim('catalog/' . str_replace('../', '', 'products'), '/') . '/' . $filename;
                    } else {
                        $json['error']['error_uploaded'] = $this->language->get('error_uploaded');
                    }
                }
            }//foreach
        }
        if (!empty($json['error'])) {
            $json['status'] = false;
        }



        $this->response->setOutput(json_encode($json));
    }

    protected function get_weight_class() {
        $this->load->model('api/localisation/weight_class');
        $data = $this->model_api_localisation_weight_class->getWeightClasses();
        $this->response->setOutput(json_encode($data));
    }

    protected function get_tax_classes() {
        $this->load->model('api/localisation/tax_class');
        $data = $this->model_api_localisation_tax_class->getTaxClasses();
        $this->response->setOutput(json_encode($data));
    }

    protected function get_length_class() {
        $this->load->model('api/localisation/length_class');
        $data = $this->model_api_localisation_length_class->getLengthClasses();
        $this->response->setOutput(json_encode($data));
    }

    public function search() {

        $this->load->language('product/search');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
        } else {
            $tag = '';
        }

        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
        }

        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = '';
        }

        if (isset($this->request->get['option_id'])) {
            $option_id = $this->request->get['option_id'];
        } else {
            $option_id = array();
        }

        if (isset($this->request->get['filter_color_name'])) {
            $filter_color_name = $this->request->get['filter_color_name'];
        } else {
            $filter_color_name = array();
        }
        if (isset($this->request->get['option_parent'])) {
            $option_parent = $this->request->get['option_parent'];
        } else {
            $option_parent = array();
        }

        if (isset($this->request->get['filter_price_id'])) {
            $price_ids = $this->request->get['filter_price_id'];
        } else {
            $price_ids = array();
        }
        if (isset($this->request->get['filter_color_id'])) {
            $filter_color_id = $this->request->get['filter_color_id'];
        } else {
            $filter_color_id = array();
        }


        if (isset($this->request->get['sub_category'])) {
            $sub_category = $this->request->get['sub_category'];
        } else {
            $sub_category = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_product_limit');
        }
        $price_datas = array();
        if (!empty($price_ids)) {
            $this->load->model('catalog/filter');
            $price_datas = $this->model_catalog_filter->getFilterDataByFilterIds($price_ids);
        }
        $this->load->model('catalog/category');



        $data['products'] = array();
        if (isset($this->request->get['search']) || isset($this->request->get['tag']) || isset($this->request->get['option_id']) || isset($this->request->get['price_id']) || isset($this->request->get['category_id']) || isset($this->request->get['find'])) {
            $filter_data = array(
                'filter_name' => $search,
                'filter_tag' => $tag,
                'filter_description' => $description,
                'filter_category_id' => $category_id,
                'filter_option_id' => $option_id,
                'filter_option_parent' => $option_parent,
                'filter_color_name' => $filter_color_name,
                'filter_price_id' => $price_ids,
                'filter_color_id' => $filter_color_id,
                'filter_price_data' => $price_datas,
                'filter_sub_category' => $sub_category,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );
            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

            $results = $this->model_catalog_product->getProducts($filter_data);
        } else {
            $filter_data = array(
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );

            $product_total = $this->model_catalog_product->getTotalProducts();

            $results = $this->model_catalog_product->getProducts();
        }

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if ((float) $result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                'price' => $price,
                'lowest-price' => round($this->model_catalog_product->getLowestPrice($result['product_id']), 2),
                'special' => $special,
                'tax' => $tax,
                'rating' => $result['rating'],
                'href' => urldecode($this->url->link('product/product', 'product_id=' . $result['product_id'] . $url))
            );
        }



        $data['option_id'] = array();
        $data['price_id'] = array();

        $data['search'] = $search;
        $data['description'] = $description;
        $data['category_id'] = $category_id;
        if (!empty($option_id)) {
            $data['option_id'] = explode(",", $option_id);
        }
        if (!empty($option_parent)) {
            $data['option_parent'] = explode(',', $option_parent);
        }
        if (!empty($price_ids)) {
            $data['filter_price_id'] = explode(",", $price_ids);
        }
        if (!empty($filter_color_id)) {
            $data['filter_color_id'] = explode(',', $filter_color_id);
        }

        $data['product_total'] = $product_total;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;
        $this->response->setOutput(json_encode($data));
    }

}

?>
