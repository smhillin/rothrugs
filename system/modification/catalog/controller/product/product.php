<?php

class ControllerProductProduct extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('product/product');
        $this->document->addScript('catalog/view/javascript/rr/jquery.carouFredSel-6.2.0-packed.js');
        $this->document->addScript('catalog/view/javascript/rr/jquery.touchSwipe.min.js');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->load->model('catalog/category');

        if (isset($this->request->get['path'])) {
            $path = '';

            $parts = explode('_', (string) $this->request->get['path']);

            $category_id = (int) array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_' . $path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path)
                    );
                }
            }

            // Set the last category breadcrumb
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $url = '';
                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $data['breadcrumbs'][] = array(
                    'text' => $category_info['name'],
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
                );
            }
        }

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->get['manufacturer_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_brand'),
                'href' => $this->url->link('product/manufacturer')
            );

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

            if ($manufacturer_info) {
                $data['breadcrumbs'][] = array(
                    'text' => $manufacturer_info['name'],
                    'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
                );
            }
        }

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_search'),
                'href' => $this->url->link('product/search', $url)
            );
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            // get 3 product relation option
            $product3 = $this->model_catalog_product->getProductRelatedOptions($product_id, $product_info['model']);
            // echo "<pre>";
            // var_dump($product3); die;
            if ($product3) {
                $this->load->model('tool/image');
                foreach ($product3 as $result) {
                    if (!empty($result['image'])) {
                        $a = strstr($result['image'], 'https://s3.amazonaws.com/');
                        $aa = strstr($result['image'], 'https://cdn1-media.s3.amazonaws.com/');
                        if (!empty($a) || $aa) {
                            $image = $result['image'];
                        } else {
                            $b = strstr($result['image'], 'http://www.owimages.com/');
                            if (!empty($b)) {
                                $image = $result['image'];
                            } else {
                                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                            }
                        }
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }
                    $price_min = $this->model_catalog_product->getPriceByProductId($result['product_id'], 11, "ASC");
                    $price_max = $this->model_catalog_product->getPriceByProductId($result['product_id']);
                    $price = $price_min . " - " . $price_max;
                    if ((float) isset($result['special'])) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) isset($result['special']) ? $result['special'] : $result['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = (int) isset($result['rating']);
                    } else {
                        $rating = false;
                    }
                    // echo "<pre>";
                    // var_dump($url);
                    // $a = urldecode($this->url->link('product/product', 'product_id=' . $result['product_id']));
                    // var_dump($a); 
                    // die;

                    $data['related_products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
                        'name' => $result['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                        'price' => $price,
                        'sale_flag' => $this->model_catalog_product->getManufacturerSaleFlag($result['manufacturer_id']) ? $this->model_catalog_product->getManufacturerSaleFlag($result['manufacturer_id'])['sale_flag'] : '',
                        'lowest-price' => round($this->model_catalog_product->getLowestPrice($result['product_id']), 2),
                        'special' => $special,
                        'tax' => $tax,
                        'rating' => isset($result['rating']),
                        'href' => urldecode($this->url->link('product/product', 'product_id=' . $result['product_id']))
                    );
                }
            }
            // echo "<pre>";
            // var_dump($data['related_products']); die;
            // get 3 product  option
            $product3 = $this->model_catalog_product->getProductRugPad();
            if ($product3) {
                $this->load->model('tool/image');
                foreach ($product3 as $result) {
                    // echo "<pre>";
                    // var_dump($result);
                    // die;
                    if (!empty($result['image'])) {
                        $a = strstr($result['image'], 'https://s3.amazonaws.com/');
                        $aa = strstr($result['image'], 'https://cdn1-media.s3.amazonaws.com/');
                        if (!empty($a) || $aa) {
                            $image = $result['image'];
                        } else {
                            $b = strstr($result['image'], 'http://www.owimages.com/');
                            if (!empty($b)) {
                                $image = $result['image'];
                            } else {
                                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                            }
                        }
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }
                    $price_min = $this->model_catalog_product->getPriceByProductId($result['product_id'], 11, "ASC");
                    $price_max = $this->model_catalog_product->getPriceByProductId($result['product_id']);
                    $price = $price_min . " - " . $price_max;
                    if ((float) isset($result['special'])) {
                        $special = $this->currency->format($this->tax->calculate(isset($result['special']), $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) isset($result['special']) ? $result['special'] : $result['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = (int) isset($result['rating']);
                    } else {
                        $rating = false;
                    }

                    $data['rothpad_products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
                        // 'name' => $result['name'],
                        // 'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                        'price' => $price,
                        'starting_at' => $price_min,
                        'lowest-price' => round($this->model_catalog_product->getLowestPrice($result['product_id']), 2),
                        // 'special' => $special,
                        // 'tax' => $tax,
                        // 'rating' => $result['rating'],
                        // 'href' => urldecode($this->url->link('product/product', 'product_id=' . $result['product_id'] . $url))
                    );
                }
            }

            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }
            
            if (isset($this->request->get['filter_color_name'])) {
                $url .= '&filter_color_name=' . $this->request->get['filter_color_name'];
            }
            if (isset($this->request->get['filter_size_id'])) {
                $url .= '&filter_size_id=' . $this->request->get['filter_size_id'];
            }
            if (isset($this->request->get['filter_price_id'])) {
                $url .= '&filter_price_id=' . $this->request->get['filter_price_id'];
            }
            if (isset($this->request->get['filter_color_id'])) {
                $url .= '&filter_color_id=' . $this->request->get['filter_color_id'];
            }
            if (isset($this->request->get['filter_style_id'])) {
                $url .= '&filter_style_id=' . $this->request->get['filter_style_id'];
            }
            $data['breadcrumbs'][] = array(
                'text' => 'Search Page',
                'href' => $this->url->link('product/search',$url)
            );

            $data['breadcrumbs'][] = array(
                'text' => $product_info['name'],
                'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
            );

            $this->document->setTitle($product_info['meta_title']);
            $this->document->setDescription($product_info['meta_description']);
            $this->document->setKeywords($product_info['meta_keyword']);
            $this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
            $this->document->addStyle('catalog/view/javascript/jquery/fancy/jquery.fancybox.css');
            $this->document->addScript('catalog/view/javascript/jquery/jquery.elevateZoom-3.0.8.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/fancy/jquery.fancybox.js');

            $data['heading_title'] = $product_info['name'];
            $data['heading_sku'] = $product_info['sku'];
            $data['product_id'] = $product_id;

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

            if ($product_info['quantity'] <= 0) {
                $data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $data['stock'] = $product_info['quantity'];
            } else {
                $data['stock'] = $this->language->get('text_instock');
            }

            $this->load->model('tool/image');

            // if ($product_info['image']) {
            //     $a = strstr($product_info['image'], 'https://s3.amazonaws.com/');
            //     if (!empty($a)) {
            //         $data['popup'] = $product_info['image'];
            //     } else {
            //         $b = strstr($product_info['image'], 'http://www.owimages.com/');
            //         if (!empty($b)) {
            //             $data['popup'] = $product_info['image'];
            //         } else {
            //             $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            //         }
            //     }
            // } else {
            //     $data['popup'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            // }

            // if ($product_info['image']) {
            //     $a = strstr($product_info['image'], 'https://s3.amazonaws.com/');
            //     if (!empty($a)) {
            //         $data['thumb'] = $product_info['image'];
            //     } else {
            //         $b = strstr($product_info['image'], 'http://www.owimages.com/');
            //         if (!empty($b)) {
            //             $data['thumb'] = $product_info['image'];
            //         } else {
            //             $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            //         }
            //     }
            // } else {
            //     $data['thumb'] = $this->model_tool_image->resize('catalog/default/default.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            //     ;
            // }

            $data['images'] = array();

            

            $this->load->model('catalog/productcustom');
            $data['product_same_color'] = array();
            $data['product_same_shape'] = array();
            
            $data['product_same_color'] = $this->getProductSameOptionValue($product_id, $product_info['model'], 14);
            // echo "<pre>";
            // var_dump($data['product_same_color']);
            // die;

            //  $data['product_same_shape'] = $this->getShapeOptionValueSame($product_id,$product_info['model'],14);

            $data['sku'] = $product_info['sku'];
            $data['model'] = $product_info['model'];
            $price_min = $this->model_catalog_product->getPriceByProductId($product_id, 11, "ASC");
            // $price_max = $this->model_catalog_product->getPriceByProductId($product_id);
            $priceto = '$' . $price_min;
            $data['priceto'] = $priceto;
            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
            // echo "<pre>";
            // var_dump($results);
            // die;
            $data['popup'] = '';
            $data['thumb'] = '';
            foreach ($results as $key => $result) {

                $a = strstr($result['image'], 'https://s3.amazonaws.com/');
                $aa = strstr($result['image'], 'https://cdn1-media.s3.amazonaws.com/');
                if (!empty($a) || $aa) {
                    if (empty($data['popup']) && empty($data['thumb'])) {
                        $data['popup'] = trim($result['image']);
                        $data['thumb'] = trim($result['image']);
                    }
                    $data['images'][] = array(
                        'popup' => trim($result['image']),
                        'thumb' => trim($result['image'])
                    );
                } else {
                    $a = strstr($result['image'], 'http://www.owimages.com/');
                    if (!empty($a)) {
                        if (empty($data['popup']) && empty($data['thumb'])) {
                            $data['popup'] = trim($result['image']);
                            $data['thumb'] = trim($result['image']);
                        }
                        $data['images'][] = array(
                            'popup' => trim($result['image']),
                            'thumb' => trim($result['image'])
                        );
                    } else {
                        if (empty($data['popup']) && empty($data['thumb'])) {
                            $data['popup'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                            $data['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }
                        $data['images'][] = array(
                            'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                            'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
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
            
            // echo "<pre>";
            // var_dump($data['images']);
            // die;

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['price'] = false;
            }

            if ((float) $product_info['special']) {
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
            } else {
                $data['tax'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

            $data['discounts'] = array();

            foreach ($discounts as $discount) {
                $data['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
                );
            }

            $data['options'] = array();
            $product_option_color = array();
            $optionarraydata = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
            
            foreach ($optionarraydata as $option) {

                $product_option_value_data = array();
                if ($option['name'] == 'Color') {
                    $product_option_color['option_id'] = $option['product_option_id'];
                }

                foreach ($option['product_option_value'] as $option_value) {

                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate1($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
                            // var_dump($product_info['tax_class_id']);
                            // var_dump($this->config->get('config_tax'));
                            // var_dump($option_value['price']); 
                            // var_dump($price); die('ss');
                        } else {
                            $price = false;
                        }

                        if ($option['name'] == 'Color') {
                            $product_option_color['value_id'] = $option_value['product_option_value_id'];
                        }
                        $image_sub = '';
                        if (!empty($option_value['option_shape'])) {
                            $this->load->model('catalog/productcustom');
                            $result_sub = $this->model_catalog_productcustom->getOptionValueInfo($option_value['option_shape']);
                            if (!empty($result_sub['image'])) {
                                $image_sub = $this->model_tool_image->resize($result_sub['image'], 50, 50);
                            } else {
                                $image_sub = $this->model_tool_image->resize('placeholder.png', 50, 50);
                            }
                        }
                        if (empty($result['image'])) {
                            $image = $this->model_tool_image->resize('placeholder.png', 50, 50);
                        } else {
                            $image = $this->model_tool_image->resize($result['image'], 50, 50);
                        }
                        if (!empty($option_value['msrp'])) {
                            $msrp = $this->currency->format($option_value['msrp']);
                        } else {
                            $msrp = false;
                        }
                        $product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => $image,
                            'price' => $price,
                            'imagexl' => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                            'msrp' => $msrp,
                            'quantity' => $option_value['quantity'],
                            'price_prefix' => $option_value['price_prefix'],
                            'image_sub' => $image_sub,
                        );
                        // var_dump($product_option_value_data); die;
                    }
                }

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required'],
                    'product_option_color_id' => $product_option_color,
                );
            }
            
                
            if ($product_info['minimum']) {
                $data['minimum'] = $product_info['minimum'];
            } else {
                $data['minimum'] = 1;
            }

            $data['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $data['review_guest'] = true;
            } else {
                $data['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['reviews'] = sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']);
            $data['rating'] = (int) $product_info['rating'];
            $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
            $this->load->model('total/category_product_based_fee');
            $data['discount_flag_text'] = $this->model_total_category_product_based_fee->getSettingsByVal($product_info['manufacturer_id'],$product_id);
            $data['products'] = array();

            $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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
                    'special' => $special,
                    'tax' => $tax,
                    'rating' => $rating,
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $data['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
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
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/product/product.tpl', $data));
            }
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
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
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function review() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author' => $result['author'],
                'text' => $result['text'],
                'rating' => (int) $result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
        }
    }

    public function gettotalreview() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');
        $results = $this->model_catalog_review->getAllReviewsByProductId($this->request->get['product_id']);
        $total_quality = 0;
        $total_color = 0;
        $total_value = 0;
        $quality = 0;
        $color = 0;
        $value = 0;
        $total = 0;
        if ($results) {
            foreach ($results as $result) {
                $total_quality += (float) $result['quality_rating'];
                $total_color += (float) $result['color_rating'];
                $total_value += (float) $result['value_rating'];
            }
            $quality = (float) $total_quality / count($results);
            $color = (float) $total_color / count($results);
            $value = (float) $total_value / count($results);
            $total = ($quality + $color + $value) / 3;
        }
        $data['total'] = array(
            'quality' => (int) number_format($quality),
            'color' => (int) number_format($color),
            'value' => (int) number_format($value),
            'total' => number_format($total, 1),
            'review' => count($results),
        );
        // var_dump($data['total']); die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/reviewtotal.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/reviewtotal.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
        }
    }

    public function getreview() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');
        $data['logged'] = $this->customer->isLogged();
        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId1($this->request->get['product_id']);
        $results = $this->model_catalog_review->getReviewsByProductId1($this->request->get['product_id'], ($page - 1) * 3, 3);
        foreach ($results as $result) {
            //get all like
            $like = $this->model_catalog_review->getLikeReviewByType($result['id'], 1);
            //get all dislike
            $dislike = $this->model_catalog_review->getLikeReviewByType($result['id'], 0);
            $total = ( (float) $result['quality_rating'] + (float) $result['color_rating'] + (float) $result['value_rating']) / 3;
            $data['reviews'][] = array(
                'review_id' => $result['id'],
                'review_title' => $result['review_title'],
                'product_id' => $result['product_id'],
                'quality_rating' => (int) $result['quality_rating'],
                'color_rating' => (int) $result['color_rating'],
                'value_rating' => (int) $result['value_rating'],
                'review_summary' => $result['review_summary'],
                'your_nickname' => $result['your_nickname'],
                'your_location' => $result['your_location'],
                'like' => $like,
                'dislike' => $dislike,
                'total' => number_format($total, 1),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 3;
        $pagination->url = $this->url->link('product/product/getreview', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/reviewproduct.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/reviewproduct.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
        }
    }

    public function likereview() {
        $review_id = $this->request->post['review_id'];
        $type = $this->request->post['type'];
        $user_id = $this->customer->isLogged();
        $data = array(
            'review_id' => $review_id,
            'user_id' => $user_id,
            'type' => $type,
        );
        // var_dump($review_id.' '.$user_id.' '.$); die;
        $this->load->model('catalog/review');
        //check like with user
        $check = $this->model_catalog_review->checkUserLikeReview($data);
        if (!empty($check)) {
            $this->model_catalog_review->addLikeReview($data);
            $total = $this->model_catalog_review->getLikeReviewByType($review_id, $type);
            echo $total;
        } else {
            echo 0;
        }
    }

    public function getRecurringDescription() {
        $this->language->load('product/product');
        $this->load->model('catalog/product');

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        if (isset($this->request->post['profile_id'])) {
            $profile_id = $this->request->post['profile_id'];
        } else {
            $profile_id = 0;
        }

        if (isset($this->request->post['quantity'])) {
            $quantity = $this->request->post['quantity'];
        } else {
            $quantity = 1;
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);
        $profile_info = $this->model_catalog_product->getProfile($product_id, $profile_id);

        $json = array();

        if ($product_info && $profile_info) {

            if (!$json) {
                $frequencies = array(
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year'),
                );

                if ($profile_info['trial_status'] == 1) {
                    $price = $this->currency->format($this->tax->calculate($profile_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
                    $trial_text = sprintf($this->language->get('text_trial_description'), $price, $profile_info['trial_cycle'], $frequencies[$profile_info['trial_frequency']], $profile_info['trial_duration']) . ' ';
                } else {
                    $trial_text = '';
                }

                $price = $this->currency->format($this->tax->calculate($profile_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

                if ($profile_info['duration']) {
                    $text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
                } else {
                    $text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
                }

                $json['success'] = $text;
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function write() {
        $this->load->language('product/product');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($this->request->post['rating'])) {
                $json['error'] = $this->language->get('error_rating');
            }

            if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
                $json['error'] = $this->language->get('error_captcha');
            }

            if (!isset($json['error'])) {
                $this->load->model('catalog/review');

                $this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function writereview() {
        $this->load->language('product/product');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 100)) {
                $json['error'] = 'Review title must be between 3 and 100 characters!';
            }

            if ((utf8_strlen($this->request->post['summary']) < 25) || (utf8_strlen($this->request->post['summary']) > 255)) {
                $json['error'] = 'Review summary must be between 25 and 255 characters!';
            }

            if (empty($this->request->post['quality'])) {
                $json['error'] = 'Please select a quality rating!';
            }
            if (empty($this->request->post['color'])) {
                $json['error'] = 'Please select a color rating!';
            }
            if (empty($this->request->post['value'])) {
                $json['error'] = 'Please select a value rating!';
            }

            if ((utf8_strlen($this->request->post['nickname']) < 3) || (utf8_strlen($this->request->post['nickname']) > 100)) {
                $json['error'] = 'Review nickname must be between 3 and 100 characters!';
            }

            // if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
            //  $json['error'] = $this->language->get('error_captcha');
            // }

            if (!isset($json['error'])) {
                $this->load->model('catalog/review');

                $this->model_catalog_review->addReviewProducts($this->request->get['product_id'], $this->request->post);

                // $json['success'] = $this->language->get('text_success');
                $json['success'] = 'Thank you for your review.';
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function upload() {
        $this->load->language('product/product');

        $json = array();

        if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
            // Sanitize the filename
            $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

            // Validate the filename length
            if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                $json['error'] = $this->language->get('error_filename');
            }

            // Allowed file extension types
            $allowed = array();

            $extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_extension_allowed'));

            $filetypes = explode("\n", $extension_allowed);

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                $json['error'] = $this->language->get('error_filetype');
            }

            // Allowed file mime types
            $allowed = array();

            $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

            $filetypes = explode("\n", $mime_allowed);

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array($this->request->files['file']['type'], $allowed)) {
                $json['error'] = $this->language->get('error_filetype');
            }

            // Check to see if any PHP files are trying to be uploaded
            $content = file_get_contents($this->request->files['file']['tmp_name']);

            if (preg_match('/\<\?php/i', $content)) {
                $json['error'] = $this->language->get('error_filetype');
            }

            // Return any upload error
            if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
            }
        } else {
            $json['error'] = $this->language->get('error_upload');
        }

        if (!$json) {
            $file = $filename . '.' . md5(mt_rand());

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

            // Hide the uploaded file name so people can not link to it directly.
            $this->load->model('tool/upload');

            $json['code'] = $this->model_tool_upload->addUpload($filename, $file);

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->setOutput(json_encode($json));
    }

    protected function getShapeOptionValueSame($product_id, $model, $filter_option_id) {
        $this->load->model('catalog/productcustom');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $same_filer['filter_model'] = $model;
        $same_filer['filter_product_id'] = $product_id;
        $same_filer['filter_option_id'] = $filter_option_id; //Color;
        $product_same = $this->model_catalog_productcustom->getOptionShapeSameModel($same_filer);
        $options = array();
        $data = array();
        foreach ($product_same as $result) {
            if (empty($result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', 100, 100);
            } else {
                $image = $this->model_tool_image->resize($result['image'], 100, 100);
            }
            $data[] = array(
                'product_id' => $result['product_id'],
                'images' => $image,
                'url' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'option_id' => $result['option_id'],
                'option_shape' => isset($result['option_shape']) ? $result['option_shape'] : "",
                'option_value_id' => $result['option_shape'],
                'product_option_id' => $result['product_option_id'],
                'product_option_value_id' => $result['product_option_value_id'],
            );
        }

        return $data;
    }

    protected function getProductSameOptionValue($product_id, $model, $filter_option_id) {
        $this->load->model('catalog/productcustom');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $same_filer['filter_model'] = $model;
        $same_filer['filter_product_id'] = $product_id;
        $same_filer['filter_option_id'] = $filter_option_id; //Color;

        $product_same = $this->model_catalog_productcustom->getProductsSameModel($same_filer);

        $options = array();
        $data = array();
        // echo "<pre>";
        // var_dump($product_same);
        // die;
        foreach ($product_same as $result) {

            foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
                $options[$option['name']] = array(
                    'option_id' => $option['option_id'],
                );
            }

            $images = $this->model_catalog_product->getProductImages($result['product_id']);
            // echo "<pre>";
            // var_dump($results);
            // die;
            $image = '';
            foreach ($images as $key => $img) {

                $a = strstr($img['image'], 'https://s3.amazonaws.com/');
                $aa = strstr($img['image'], 'https://cdn1-media.s3.amazonaws.com/');
                if (!empty($a) || $aa) {
                    if (empty($image)) {
                        $image = trim($img['image']);
                    }
                } else {
                    $a = strstr($img['image'], 'http://www.owimages.com/');
                    if (!empty($a)) {
                        if (empty($image)) {
                            $image = trim($img['image']);
                        }
                    } else {
                        if (empty($image)) {
                            $image = $this->model_tool_image->resize($img['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }
                    }
                }
            }

            if (empty($image)) {
                if (empty($result['image'])) {
                    $image = $this->model_tool_image->resize('catalog/default/default.png', 100, 100);
                } else {
                    $a = strstr($result['image'], 'https://s3.amazonaws.com/');
                    $aa = strstr($result['image'], 'https://cdn1-media.s3.amazonaws.com/');
                    if (!empty($a) || $aa) {
                        $image = $result['image'];
                    } else {
                        $b = strstr($result['image'], 'http://www.owimages.com/');
                        if (!empty($b)) {
                            $image = $result['image'];
                        } else {
                            $image = $this->model_tool_image->resize($result['image'], 100, 100);
                        }
                    }
                }
            }
            
            $data[] = array(
                'images' => $image,
                'product_id' => $result['product_id'],
                'url' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'options' => $options,
                'option_value_id' => $result['option_value_id'],
                'product_option_id' => $result['product_option_id'],
                'product_option_value_id' => $result['product_option_value_id'],
            );
        }
            // echo "<pre>";
            // var_dump($data);die;

        return $data;
    }

    /** getSame Color
     *
     */
    public function sendmailtoother() {
        $this->load->language('product/product');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $yourName = $_REQUEST["yourname"];
            $personname = $_REQUEST["personname"];
            $personemail = $_REQUEST["personemail"];
            $sendingmessage = $_REQUEST["sendingmessage"];

            $email_to = "mtrawat34@gmail.com";
            $mail = new Mail();

            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($email_to);
            $mail->setFrom("somewhere@rothrug.com");
            $mail->setSender("somewhere@rothrug.com");
            $mail->setSubject("test send mail");
            $mail->setText("test message body text");

            $mail->send();
        }

        $this->response->setOutput(json_encode($json));
    }

}

/**
 * Clean, URL-safe string converter
 * @author Matteo Spinelli
 * @link http://cubiq.org/the-perfect-php-clean-url-generator
 */
setlocale(LC_ALL, 'en_US.UTF8');

function toAscii($str, $replace = array(), $delimiter = '-') {
    if (!empty($replace)) {
        $str = str_replace((array) $replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    $clean = str_replace('-amp-', '-', $clean);

    return $clean;
}
