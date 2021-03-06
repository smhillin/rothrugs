<?php

class ControllerProductAjax extends Controller{

	private $error = array();

	/**
	 * GetSameShape
	 */
	public function getDetails(){
	$this->load->language('product/product');
		if($this->request->server['HTTPS']){
			$server = $this->config->get('config_ssl');
		}else{
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;

		$this->load->model('catalog/category');

		if(isset($this->request->get['path'])){
			$path = '';

			$parts = explode('_', (string) $this->request->get['path']);

			$category_id = (int) array_pop($parts);

			foreach($parts as $path_id){
				if(!$path){
					$path = $path_id;
				}else{
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if($category_info){
					$data['breadcrumbs'][] = array(
						'text'	 => $category_info['name'],
						'href'	 => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if($category_info){
				$url = '';

				if(isset($this->request->get['sort'])){
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if(isset($this->request->get['order'])){
					$url .= '&order=' . $this->request->get['order'];
				}

				if(isset($this->request->get['page'])){
					$url .= '&page=' . $this->request->get['page'];
				}

				if(isset($this->request->get['limit'])){
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text'	 => $category_info['name'],
					'href'	 => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if(isset($this->request->get['manufacturer_id'])){
			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_brand'),
				'href'	 => $this->url->link('product/manufacturer')
			);

			$url = '';

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if($manufacturer_info){
				$data['breadcrumbs'][] = array(
					'text'	 => $manufacturer_info['name'],
					'href'	 => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if(isset($this->request->get['search']) || isset($this->request->get['tag'])){
			$url = '';

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_search'),
				'href'	 => $this->url->link('product/search', $url)
			);
		}

		if(isset($this->request->get['product_id'])){
			$product_id = (int) $this->request->get['product_id'];
		}else{
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if($product_info){
			$url = '';

			if(isset($this->request->get['path'])){
				$url .= '&path=' . $this->request->get['path'];
			}

			if(isset($this->request->get['filter'])){
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if(isset($this->request->get['manufacturer_id'])){
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $product_info['name'],
				'href'	 => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');


			$data['heading_title'] = $product_info['name'];

			$u = $this->url->link('product/product', 'product_id=' . $product_id);
			$u = explode('/', $u);
			$data['keyword'] = $u[count($u) - 1];
			$data['faq_shiping'] = HTTP_SERVER . 'faq?to=shipping';
			$data['faq_tax'] = HTTP_SERVER . 'faq?to=tax';
			$data['faq_price'] = HTTP_SERVER . 'faq?to=gprice';

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['entry_captcha'] = $this->language->get('entry_captcha');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int) $this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];

			if($product_info['quantity'] <= 0){
				$data['stock'] = $product_info['stock_status'];
			}elseif($this->config->get('config_stock_display')){
				$data['stock'] = $product_info['quantity'];
			}else{
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			// if($product_info['image']){
			// 	$a= strstr($product_info['image'], 'https://s3.amazonaws.com/');
			// 	if (!empty($a)) {
			// 		$data['popup'] = $product_info['image'];
			// 	}else{
			// 		$b = strstr($product_info['image'], 'http://www.owimages.com/');
			// 		if (!empty($b)) {
			// 			$data['popup'] = $product_info['image'];
			// 		}else{
			// 			$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			// 		}
			// 	}
			// }else{
			// 	$data['popup'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			// }

			// if($product_info['image']){
			// 	$a= strstr($product_info['image'], 'https://s3.amazonaws.com/');
			// 	if (!empty($a)) {
			// 		$data['thumb'] = $product_info['image'];
			// 	}else{
			// 		$b = strstr($product_info['image'], 'http://www.owimages.com/');
			// 		if (!empty($b)) {
			// 			$data['thumb'] = $product_info['image'];
			// 		}else{
			// 			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			// 		}
			// 	}
			// }else{
			// 	$data['thumb'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			// }

			$data['images'] = array();

			

			$this->load->model('catalog/productcustom');
			$data['product_same_color'] = array();
			$data['category-filter'] = array();
			$same_filer['filter_model'] = $product_info['model'];
			$same_filer['filter_product_id'] = $product_id;
			if(!empty($this->request->get['filter_option_id'])){
				$same_filer['filter_option_id'] = $this->request->get['filter_option_id'];
			}else{
				$same_filer['filter_option_id'] = 0;
			}
			if(!empty($this->request->get['filter_option_value_id'])){
				$filter_option_value_id= $this->request->get['filter_option_value_id'];
				$same_filer['reload_option_value_id'] = $filter_option_value_id;
			}else{
				$filter_option_value_id = null;
				$same_filer['reload_option_value_id']  = null;
			}
			if(!empty($this->request->get['active_value_id'])){
				$active_value_id = $this->request->get['active_value_id'];
			}else{
				$active_value_id = null;
			}



			$data['sku'] = $product_info['sku'];
			$data['model'] = $product_info['model'];

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			$data['popup'] = '';
                        $data['thumb'] = '';
			foreach($results as $result){
				$a= strstr($result['image'], 'https://s3.amazonaws.com/');
                                $aa = strstr($result['image'], 'https://cdn1-media.s3.amazonaws.com/');
				if (!empty($a) || $aa) {
					if (empty($data['popup']) && empty($data['thumb'])) {
                                            $data['popup'] = trim($result['image']);
                                            $data['thumb'] = trim($result['image']);
                                        }
					$data['images'][] = array(
						'popup'	 => $result['image'],
						'thumb'	 => $result['image']
					);
				}else{
					$a= strstr($result['image'], 'http://www.owimages.com/');
					if (!empty($a)) {
						if (empty($data['popup']) && empty($data['thumb'])) {
	                        $data['popup'] = trim($result['image']);
	                        $data['thumb'] = trim($result['image']);
	                    }
						$data['images'][] = array(
							'popup'	 => $result['image'],
							'thumb'	 => $result['image']
						);
					}else{
						if (empty($data['popup']) && empty($data['thumb'])) {
                            $data['popup'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                            $data['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }
						$data['images'][] = array(
							'popup'	 => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
							'thumb'	 => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
						);
					}
				}

			}

			if (empty($data['images'])) {
                if ($product_info['image']) {  // PUSH MAIN THUMB TO AN ARRAY OF IMAGES
                    $a = strstr($product_info['image'], 'https://s3.amazonaws.com/');
                    $aa = strstr($product_info['image'], 'https://cdn1-media.s3.amazonaws.com/');
                    if (!empty($a) || $aa) {
                        $data['images'][] = array(
                            'popup' => $product_info['image'],
                            'thumb' => $product_info['image']
                        );
                    } else {
                        $a = strstr($product_info['image'], 'http://www.owimages.com/');
                        if (!empty($a)) {
                            $data['images'][] = array(
                                'popup' => $product_info['image'],
                                'thumb' => $product_info['image']
                            );
                        } else {
                            $data['images'][] = array(
                                'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                                'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                            );
                        }
                    }
                }

                if ($product_info['image']) {
                    $a = strstr($product_info['image'], 'https://s3.amazonaws.com/');
                    $aa = strstr($product_info['image'], 'https://cdn1-media.s3.amazonaws.com/');
                    if (!empty($a) || $aa) {
                        $data['popup'] = $product_info['image'];
                    } else {
                        $b = strstr($product_info['image'], 'http://www.owimages.com/');
                        if (!empty($b)) {
                            $data['popup'] = $product_info['image'];
                        } else {
                            $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }
                    }
                } else {
                    $data['popup'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                }

                if ($product_info['image']) {
                    $a = strstr($product_info['image'], 'https://s3.amazonaws.com/');
                    $aa = strstr($product_info['image'], 'https://cdn1-media.s3.amazonaws.com/');
                    if (!empty($a) || $aa) {
                        $data['thumb'] = $product_info['image'];
                    } else {
                        $b = strstr($product_info['image'], 'http://www.owimages.com/');
                        if (!empty($b)) {
                            $data['thumb'] = $product_info['image'];
                        } else {
                            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }
                    }
                } else {
                    $data['thumb'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                    ;
                }
            }

			if(($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')){
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			}else{
				$data['price'] = false;
			}

			if((float) $product_info['special']){
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			}else{
				$data['special'] = false;
			}

			if($this->config->get('config_tax')){
				$data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
			}else{
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach($discounts as $discount){
				$data['discounts'][] = array(
					'quantity'	 => $discount['quantity'],
					'price'		 => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			// $data['options'] = array();

			// foreach($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option){
			// 	$product_option_value_data = array();

			// 	foreach($option['product_option_value'] as $option_value){
			// 		// echo "<pre>";
			// 		// print_r($option_value['price']); die;
			// 		if(!$option_value['subtract'] || ($option_value['quantity'] > 0)){
			// 			if((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']){
			// 				$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
			// 			}else{
			// 				$price = false;
			// 			}
			// 			if(empty($result['image'])){
			// 				$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
			// 			}else{
			// 				// var_dump($result);
			// 				$image = $this->model_tool_image->resize($result['image'], 50, 50);
			// 			}
			// 			if(!empty($option_value['msrp'])){
			// 				$msrp = $this->currency->format($option_value['msrp']);

			// 			}else{
			// 				$msrp = false;
			// 			}
			// 			if(!empty($option_value['option_shape'])){
			// 				$this->load->model('catalog/productcustom');
			// 				$result_sub = $this->model_catalog_productcustom->getOptionValueInfo($option_value['option_shape']);
			// 				if(!empty($result_sub['image'])){
			// 					$image_sub = $this->model_tool_image->resize($result_sub['image'], 50, 50);
			// 				}else{
			// 					$image_sub = $this->model_tool_image->resize('placeholder.png', 50, 50);
			// 				}

			// 			}
			// 			$product_option_value_data[] = array(
			// 				'product_option_value_id'	 => $option_value['product_option_value_id'],
			// 				'option_value_id'			 => $option_value['option_value_id'],
			// 				'name'						 => $option_value['name'],
			// 				'image'						 => $image,
			// 				'price'						 => $price,
			// 				'imagexl'					 => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
			// 				'msrp'						 => $msrp,
			// 				'quantity'					 => $option_value['quantity'],
			// 				'price_prefix'				 => $option_value['price_prefix'],
			// 				'option_shape'				 => $option_value['option_shape'],
			// 				'image_sub'					 =>$image_sub
			// 			);
			// 		}
			// 	}

			// 	$data['options'][] = array(
			// 		'product_option_id'		 => $option['product_option_id'],
			// 		'product_option_value'	 => $product_option_value_data,
			// 		'option_id'				 => $option['option_id'],
			// 		'name'					 => $option['name'],
			// 		'type'					 => $option['type'],
			// 		'value'					 => $option['value'],
			// 		'required'				 => $option['required'],

			// 	);
			// }

			$data['options'] = array();
			$product_option_color = array();
			// var_dump($this->request->get['product_id']); die;
			// echo "<pre>";
			// print_r($this->model_catalog_product->getProductOptions($this->request->get['product_id']));
			// 	die;
			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();
				if($option['name'] == 'Color'){
					$product_option_color['option_id'] = $option['product_option_id'];
				}
				foreach ($option['product_option_value'] as $option_value) {

					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']){
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						}else{
							$price = false;
						}

						if($option['name'] == 'Color'){
							$product_option_color['value_id'] = $option_value['product_option_value_id'];
						}
						if(!empty($option_value['option_shape'])){
							$this->load->model('catalog/productcustom');
							$result_sub = $this->model_catalog_productcustom->getOptionValueInfo($option_value['option_shape']);
							if(!empty($result_sub['image'])){
								$image_sub = $this->model_tool_image->resize($result_sub['image'], 50, 50);
							}else{
								$image_sub = $this->model_tool_image->resize('placeholder.png', 50, 50);
							}

						}
						if(empty($result['image'])){
							$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
						}else{
							$image = $this->model_tool_image->resize($result['image'], 50, 50);
						}
						if(!empty($option_value['msrp'])){
							$msrp = $this->currency->format($option_value['msrp']);

						}else{
							$msrp = false;
						}
						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $image,
							'price'                   => $price,
							'imagexl' => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
							'msrp'                    => $msrp,
							'quantity'                => $option_value['quantity'],
							'price_prefix'            => $option_value['price_prefix'],
							'image_sub'				=> isset($image_sub) ? $image_sub : '',
						);
						// var_dump($product_option_value_data); die;
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required'],
					'product_option_color_id'=> $product_option_color,
				);
			}

			if($product_info['minimum']){
				$data['minimum'] = $product_info['minimum'];
			}else{
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if($this->config->get('config_review_guest') || $this->customer->isLogged()){
				$data['review_guest'] = true;
			}else{
				$data['review_guest'] = false;
			}

			if($this->customer->isLogged()){
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			}else{
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']);
			$data['rating'] = (int) $product_info['rating'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['heading_sku'] = $product_info['sku'];
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach($results as $result){
				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}else{
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if(($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')){
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				}else{
					$price = false;
				}

				if((float) $result['special']){
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				}else{
					$special = false;
				}

				if($this->config->get('config_tax')){
					$tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
				}else{
					$tax = false;
				}

				if($this->config->get('config_review_status')){
					$rating = (int) $result['rating'];
				}else{
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'	 => $result['product_id'],
					'thumb'			 => $image,
					'name'			 => $result['name'],
					'description'	 => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'			 => $price,
					'special'		 => $special,
					'tax'			 => $tax,
					'rating'		 => $rating,
					'href'			 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$data['tags'] = array();

			if($product_info['tag']){
				$tags = explode(',', $product_info['tag']);

				foreach($tags as $tag){
					$data['tags'][] = array(
						'tag'	 => trim($tag),
						'href'	 => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$data['profiles'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$result_html = array();
			$result_html['status'] = true;
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_name.tpl')){
				$result_html['name'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_name.tpl', $data);
			}else{
				$result_html['name'] = $this->load->view('default/template/product/_product_description.tpl', $data);
			}
			$result_html['heading_sku'] = "Sku #".$product_info['sku'];
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_review.tpl')){
				$result_html['review'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_review.tpl', $data);
			}else{
				$result_html['review'] = $this->load->view('default/template/product/_product_description.tpl', $data);
			}

			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_description.tpl')){
				$result_html['description'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_description.tpl', $data);
			}else{
				$result_html['description'] = $this->load->view('default/template/product/_product_description.tpl', $data);
			}
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_size.tpl')){
				$result_html['shape_size'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_size.tpl', $data);
			}else{
				$result_html['shape_size'] = $this->load->view('default/template/product/_product_size.tpl', $data);
			}
			if(!empty($data['product_same'])){
				$result_html['same_product']['result'] = true;
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl')){
					$result_html['same_product']['html'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl', $data);
				}else{
					$result_html['same_product']['html'] = $this->load->view('default/template/product/_product_' . $same_template . '.tpl', $data);
				}
				$result_html['same_product']['type'] = $same_template;
			}else{
				$result_html['same_product']['result'] = false;
			}

			if(!empty($product_sames_all)){
				if($same_template == 'shape'){
					$same_template = 'color';
				}else{
					$same_template = 'shape';
				}
				$result_html['same_product_all']['result'] = true;
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl')){
					$result_html['same_product_all']['html'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl', $product_sames_all);
				}else{
					$result_html['same_product_all']['html'] = $this->load->view('default/template/product/_product_' . $same_template . '.tpl', $product_sames_all);
				}


			}else{
				$result_html['same_product_all']['result'] = false;
			}
			if(!empty($product_sames_all)){
				$result_html['same_product_all']['result'] = true;
			}
			$this->response->setOutput(json_encode($result_html));
		}else{
			$url = '';

			if(isset($this->request->get['path'])){
				$url .= '&path=' . $this->request->get['path'];
			}

			if(isset($this->request->get['filter'])){
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if(isset($this->request->get['manufacturer_id'])){
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_error'),
				'href'	 => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$result_html['status'] = false;


			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/_not_found.tpl')){
				$result_html['htm'] = $this->load->view($this->config->get('config_template') . '/template/error/_not_found.tpl', $data);
			}else{
				$result_html['htm'] = $this->load->view('default/template/error/_not_found.tpl', $data);
			}
			$this->response->setOutput(json_encode($result_html));
		}
	}
	public function getDetail(){
		$this->load->language('product/product');
		if($this->request->server['HTTPS']){
			$server = $this->config->get('config_ssl');
		}else{
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;

		$this->load->model('catalog/category');

		if(isset($this->request->get['path'])){
			$path = '';

			$parts = explode('_', (string) $this->request->get['path']);

			$category_id = (int) array_pop($parts);

			foreach($parts as $path_id){
				if(!$path){
					$path = $path_id;
				}else{
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if($category_info){
					$data['breadcrumbs'][] = array(
						'text'	 => $category_info['name'],
						'href'	 => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if($category_info){
				$url = '';

				if(isset($this->request->get['sort'])){
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if(isset($this->request->get['order'])){
					$url .= '&order=' . $this->request->get['order'];
				}

				if(isset($this->request->get['page'])){
					$url .= '&page=' . $this->request->get['page'];
				}

				if(isset($this->request->get['limit'])){
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text'	 => $category_info['name'],
					'href'	 => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if(isset($this->request->get['manufacturer_id'])){
			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_brand'),
				'href'	 => $this->url->link('product/manufacturer')
			);

			$url = '';

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if($manufacturer_info){
				$data['breadcrumbs'][] = array(
					'text'	 => $manufacturer_info['name'],
					'href'	 => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if(isset($this->request->get['search']) || isset($this->request->get['tag'])){
			$url = '';

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_search'),
				'href'	 => $this->url->link('product/search', $url)
			);
		}

		if(isset($this->request->get['product_id'])){
			$product_id = (int) $this->request->get['product_id'];
		}else{
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if($product_info){
			$url = '';

			if(isset($this->request->get['path'])){
				$url .= '&path=' . $this->request->get['path'];
			}

			if(isset($this->request->get['filter'])){
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if(isset($this->request->get['manufacturer_id'])){
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $product_info['name'],
				'href'	 => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');


			$data['heading_title'] = $product_info['name'];

			$u = $this->url->link('product/product', 'product_id=' . $product_id);
			$u = explode('/', $u);
			$data['keyword'] = $u[count($u) - 1];
			$data['faq_shiping'] = HTTP_SERVER . 'faq?to=shipping';
			$data['faq_tax'] = HTTP_SERVER . 'faq?to=tax';
			$data['faq_price'] = HTTP_SERVER . 'faq?to=gprice';

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['entry_captcha'] = $this->language->get('entry_captcha');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int) $this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];

			if($product_info['quantity'] <= 0){
				$data['stock'] = $product_info['stock_status'];
			}elseif($this->config->get('config_stock_display')){
				$data['stock'] = $product_info['quantity'];
			}else{
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if($product_info['image']){
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			}else{
				$data['popup'] = '';
			}

			if($product_info['image']){
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			}else{
				$data['thumb'] = '';
			}

			$data['images'] = array();

			if($product_info['image']){  // PUSH MAIN THUMB TO AN ARRAY OF IMAGES
				$data['images'][] = array(
					'popup'	 => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb'	 => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			$this->load->model('catalog/productcustom');
			$data['product_same_color'] = array();
			$data['category-filter'] = array();
			$same_filer['filter_model'] = $product_info['model'];
			$same_filer['filter_product_id'] = $product_id;
			if(!empty($this->request->get['filter_option_id'])){
				$same_filer['filter_option_id'] = $this->request->get['filter_option_id'];
			}else{
				$same_filer['filter_option_id'] = 0;
			}
			if(!empty($this->request->get['filter_option_value_id'])){
				$filter_option_value_id= $this->request->get['filter_option_value_id'];
				$same_filer['reload_option_value_id'] = $filter_option_value_id;
			}else{
				$filter_option_value_id = null;
				$same_filer['reload_option_value_id']  = null;
			}
			if(!empty($this->request->get['active_value_id'])){
				$active_value_id = $this->request->get['active_value_id'];
			}else{
				$active_value_id = null;
			}

			$product_sames_all = 	$this->reloadOption($product_id,$same_filer);
			$options = array();
			$product_id_get = $this->request->get['loadProduct'];
			$reload =  $this->request->get['reload'];
			$shape_option_choose = $this->request->get['shape_option_choose'];
			$same_filer['filter_option_value_id'] = $filter_option_value_id;
			$data['option_sizes'] = array();
			$data['active_option_value_id'] = null;
			$product_options = $this->model_catalog_productcustom->getProductOptions($product_id, $same_filer['filter_option_id']);
			if(!empty($product_options)){

				if(isset($this->request->get['same_template'])){
					$same_template = $this->request->get['same_template'];
				}else{
					$same_template = 'color';
				}
				if($same_template != 'shape'){
					$same_optoin_value_id = $active_value_id;

					$product_sames = $this->model_catalog_productcustom->getProductsSameModel($same_filer);

				}else{
					$same_optoin_value_id = $same_filer['filter_option_value_id'];
					$product_sames = $this->model_catalog_productcustom->getOptionShapeSameModel($same_filer);

				}

			if(!empty($shape_option_choose) && !empty($product_id_get)){

					$searchSize = array(
						'product_id'		 => $product_id_get,
						'optoin_shape'		 => $shape_option_choose,
						'option_value_id'	 => $same_optoin_value_id,
					);

					$options_same_color = $this->model_catalog_productcustom->getProductOptionSameOptionValueId($searchSize, 11);
					if(isset($options_same_color['Size'])){
						$data['option_sizes'] = $options_same_color['Size'];
					}

				}
				foreach($product_sames as $result){
					foreach($this->model_catalog_product->getProductOptions($result['product_id']) as $option){
						$options[$option['name']] = array(
							'option_id' => $option['option_id'],
						);
					}
					if($reload != 'shape'){
						if($result['option_shape'] == $active_value_id)
							$data['active_option_value_id'] = $active_value_id;
					}else{
						if($result['option_value_id'] == $active_value_id){
							$data['active_option_value_id'] = $active_value_id;
						}
					}
					if(empty($result['image'])){
						$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
					}else{
						$image = $this->model_tool_image->resize($result['image'], 100, 100);
					}
					$data['product_same'][] = array(
						'images'					 => $image,
						'product_id'				 => $result['product_id'],
						'url'						 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
						'options'					 => $options,
						'option_shape'				 => isset($result['option_shape']) ? $result['option_shape'] : "",
						'option_value_id'			 => $result['option_value_id'],
						'product_option_id'			 => $result['product_option_id'],
						'product_option_value_id'	 => $result['product_option_value_id'],
					);
				}
			}else{
				$data['product_same'] = array();
			}

			$data['sku'] = $product_info['sku'];
			$data['model'] = $product_info['model'];

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach($results as $result){
				$data['images'][] = array(
					'popup'	 => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb'	 => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			if(($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')){
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			}else{
				$data['price'] = false;
			}

			if((float) $product_info['special']){
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			}else{
				$data['special'] = false;
			}

			if($this->config->get('config_tax')){
				$data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
			}else{
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach($discounts as $discount){
				$data['discounts'][] = array(
					'quantity'	 => $discount['quantity'],
					'price'		 => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			$data['options'] = array();

			foreach($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option){
				$product_option_value_data = array();

				foreach($option['product_option_value'] as $option_value){
					if(!$option_value['subtract'] || ($option_value['quantity'] > 0)){
						if((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']){
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						}else{
							$price = false;
						}
						if(empty($result['image'])){
							$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
						}else{
							$image = $this->model_tool_image->resize($result['image'], 50, 50);
						}
						$product_option_value_data[] = array(
							'product_option_value_id'	 => $option_value['product_option_value_id'],
							'option_value_id'			 => $option_value['option_value_id'],
							'name'						 => $option_value['name'],
							'image'						 => $image,
							'price'						 => $price,
							'imagexl'					 => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
							'msrp'						 => $option_value['mrsp'],
							'quantity'					 => $option_value['quantity'],
							'price_prefix'				 => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'		 => $option['product_option_id'],
					'product_option_value'	 => $product_option_value_data,
					'option_id'				 => $option['option_id'],
					'name'					 => $option['name'],
					'type'					 => $option['type'],
					'value'					 => $option['value'],
					'required'				 => $option['required']
				);
			}

			if($product_info['minimum']){
				$data['minimum'] = $product_info['minimum'];
			}else{
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if($this->config->get('config_review_guest') || $this->customer->isLogged()){
				$data['review_guest'] = true;
			}else{
				$data['review_guest'] = false;
			}

			if($this->customer->isLogged()){
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			}else{
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']);
			$data['rating'] = (int) $product_info['rating'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach($results as $result){
				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}else{
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if(($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')){
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				}else{
					$price = false;
				}

				if((float) $result['special']){
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				}else{
					$special = false;
				}

				if($this->config->get('config_tax')){
					$tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
				}else{
					$tax = false;
				}

				if($this->config->get('config_review_status')){
					$rating = (int) $result['rating'];
				}else{
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'	 => $result['product_id'],
					'thumb'			 => $image,
					'name'			 => $result['name'],
					'description'	 => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'			 => $price,
					'special'		 => $special,
					'tax'			 => $tax,
					'rating'		 => $rating,
					'href'			 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$data['tags'] = array();

			if($product_info['tag']){
				$tags = explode(',', $product_info['tag']);

				foreach($tags as $tag){
					$data['tags'][] = array(
						'tag'	 => trim($tag),
						'href'	 => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$data['profiles'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$result_html = array();
			$result_html['status'] = true;

			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_description.tpl')){
				$result_html['description'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_description.tpl', $data);
			}else{
				$result_html['description'] = $this->load->view('default/template/product/_product_description.tpl', $data);
			}
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_size.tpl')){

				$result_html['shape_size'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_size.tpl', $data);
			}else{
				$result_html['shape_size'] = $this->load->view('default/template/product/_product_size.tpl', $data);
			}
			if(!empty($data['product_same'])){
				$result_html['same_product']['result'] = true;
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl')){
					$result_html['same_product']['html'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl', $data);
				}else{
					$result_html['same_product']['html'] = $this->load->view('default/template/product/_product_' . $same_template . '.tpl', $data);
				}
				$result_html['same_product']['type'] = $same_template;
			}else{
				$result_html['same_product']['result'] = false;
			}

			if(!empty($product_sames_all)){
				if($same_template == 'shape'){
					$same_template = 'color';
				}else{
					$same_template = 'shape';
				}
				$result_html['same_product_all']['result'] = true;
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl')){
					$result_html['same_product_all']['html'] = $this->load->view($this->config->get('config_template') . '/template/product/_product_' . $same_template . '.tpl', $product_sames_all);
				}else{
					$result_html['same_product_all']['html'] = $this->load->view('default/template/product/_product_' . $same_template . '.tpl', $product_sames_all);
				}


			}else{
				$result_html['same_product_all']['result'] = false;
			}
			if(!empty($product_sames_all)){
				$result_html['same_product_all']['result'] = true;
			}
			$this->response->setOutput(json_encode($result_html));
		}else{
			$url = '';

			if(isset($this->request->get['path'])){
				$url .= '&path=' . $this->request->get['path'];
			}

			if(isset($this->request->get['filter'])){
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if(isset($this->request->get['manufacturer_id'])){
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if(isset($this->request->get['search'])){
				$url .= '&search=' . $this->request->get['search'];
			}

			if(isset($this->request->get['tag'])){
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if(isset($this->request->get['description'])){
				$url .= '&description=' . $this->request->get['description'];
			}

			if(isset($this->request->get['category_id'])){
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if(isset($this->request->get['sub_category'])){
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if(isset($this->request->get['sort'])){
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])){
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->get['limit'])){
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text'	 => $this->language->get('text_error'),
				'href'	 => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$result_html['status'] = false;


			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/_not_found.tpl')){
				$result_html['htm'] = $this->load->view($this->config->get('config_template') . '/template/error/_not_found.tpl', $data);
			}else{
				$result_html['htm'] = $this->load->view('default/template/error/_not_found.tpl', $data);
			}
			$this->response->setOutput(json_encode($result_html));
		}
	}


	protected function reloadOption($product_id,$same_filer){
		$this->load->model('catalog/productcustom');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data = array();
		$data['product_id'] = $product_id;
		$data['active_option_value_id'] = null;
		$product_options = $this->model_catalog_productcustom->getProductOptions($product_id, $same_filer['filter_option_id']);
		if(!empty($product_options)){

			if(isset($this->request->get['same_template'])){
				$type = $this->request->get['same_template'];
			}else{
				$type = 'color';
			}
			if($type != 'shape'){
				$product_sames = $this->model_catalog_productcustom->getOptionShapeSameModel($same_filer);
			}else{
				$same_filer['product_reload'] = $product_id;
				$product_sames = $this->model_catalog_productcustom->getProductsSameModel($same_filer,true);
			}
			$reload = $this->request->get['reload'];
			$options = array();
			foreach($product_sames as $result){
				foreach($this->model_catalog_product->getProductOptions($result['product_id']) as $option){
					$options[$option['name']] = array(
						'option_id' => $option['option_id'],
					);
				}
				if(isset($same_filer['reload_option_value_id'])){
					if($reload == 'shape'){
						if($result['option_shape'] == $same_filer['reload_option_value_id'])
							$data['active_option_value_id'] = $same_filer['reload_option_value_id'];
					}else{
						if($result['option_value_id'] == $same_filer['reload_option_value_id']){
							$data['active_option_value_id'] = $same_filer['reload_option_value_id'];
						}
					}
				}
				if(empty($result['image'])){
					$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
				}else{
					$image = $this->model_tool_image->resize($result['image'], 100, 100);
				}
				$data['product_same'][] = array(
					'images'					 => $image,
					'product_id'				 => $result['product_id'],
					'url'						 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					'options'					 => $options,
					'option_shape'				 => isset($result['option_shape']) ? $result['option_shape'] : "",
					'option_value_id'			 => $result['option_value_id'],
					'product_option_id'			 => $result['product_option_id'],
					'product_option_value_id'	 => $result['product_option_value_id'],
				);
			}
		}else{
			$data['product_same'] = array();
		}
		return $data;
	}

}

