<?php

class ControllerProductSearch extends Controller {

    public function index() {

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
            $category_id = array();
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

        if (isset($this->request->get['filter_size_id'])) {
            $filter_size_id = $this->request->get['filter_size_id'];
        } else {
            $filter_size_id = array();
        }
        if (isset($this->request->get['filter_price_id'])) {
            $price_ids = $this->request->get['filter_price_id'];
        } else {
            $price_ids = array();
        }
        if (isset($this->request->get['filter_style_id'])) {
            $filter_style_id = $this->request->get['filter_style_id'];
        } else {
            $filter_style_id = array();
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
            // $sort = 'p.sort_order';
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            // $order = 'ASC';
            $order = 'DESC';
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

        if (isset($this->request->get['search'])) {
            $this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->request->get['search']);
            $this->document->setDescription('Trying to define your space with a beautiful rug? Browse indoor and outdoor area rugs for sale at Roth Rugs.');
            $this->document->setKeywords('Browse Indoor & Outdoor Area Rugs For Sale | Roth Rugs');
        } elseif (isset($this->request->get['tag'])) {
            $this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
        } else {
            $this->document->setTitle('Browse Indoor & Outdoor Area Rugs For Sale | Roth Rugs');
            $this->document->setDescription('Trying to define your space with a beautiful rug? Browse indoor and outdoor area rugs for sale at Roth Rugs.');
            $this->document->setKeywords('Browse Indoor & Outdoor Area Rugs For Sale | Roth Rugs');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['tag'])) {
            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['description'])) {
            $url .= '&description=' . $this->request->get['description'];
        }

        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }


        if (isset($this->request->get['filter_color_name'])) {
            $url .= '&filter_color_name=' . $this->request->get['filter_color_name'];
        }
        if (isset($this->request->get['filter_size_id'])) {
            $url .='&filter_size_id=' . $this->request->get['filter_size_id'];
        }

        if (isset($this->request->get['option_id'])) {
            $url .='&option_id=' . $this->request->get['option_id'];
        }

        if (isset($this->request->get['filter_size_id'])) {
            $url .='&filter_size_id=' . $this->request->get['filter_size_id'];
        }
        if (isset($this->request->get['filter_price_id'])) {
            $url .='&filter_price_id=' . $this->request->get['filter_price_id'];
        }
        if (isset($this->request->get['option_id'])) {
            $url .='&option_id=' . $this->request->get['option_id'];
        }
        if (isset($this->request->get['filter_color_id'])) {
            $url .='&filter_color_id=' . $this->request->get['filter_color_id'];
        }
        if (isset($this->request->get['filter_style_id'])) {
            $url .= '&filter_style_id=' . $this->request->get['filter_style_id'];
        }


        if (isset($this->request->get['sub_category'])) {
            $url .= '&sub_category=' . $this->request->get['sub_category'];
        }
        if (isset($this->request->get['price_id'])) {
            $url .='&price_id =' . $this->request->get['price_id'];
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


        if (isset($this->request->get['quiz'])) {
            $data['quiz'] = $this->request->get['quiz'];
        } else {
            $data['quiz'] = '';
        }
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/search', $url)
        );

        if (isset($this->request->get['search'])) {
            $data['heading_title'] = $this->language->get('heading_title') . ' - ' . $this->request->get['search'];
            $data['vendor'] = $this->request->get['search'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
            $data['vendor'] = '';
        }

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_keyword'] = $this->language->get('text_keyword');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_sub_category'] = $this->language->get('text_sub_category');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');

        $data['entry_search'] = $this->language->get('entry_search');
        $data['entry_description'] = $this->language->get('entry_description');

        $data['button_search'] = $this->language->get('button_search');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');

        $data['compare'] = $this->url->link('product/compare');

        $this->load->model('catalog/category');

        // 3 Level Category Search
        $data['categories'] = array();

        $categories_1 = $this->model_catalog_category->getCategories(0);

        foreach ($categories_1 as $category_1) {
            $level_2_data = array();

            $categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

            foreach ($categories_2 as $category_2) {
                $level_3_data = array();

                $categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

                foreach ($categories_3 as $category_3) {
                    $level_3_data[] = array(
                        'category_id' => $category_3['category_id'],
                        'name' => $category_3['name'],
                    );
                }

                $level_2_data[] = array(
                    'category_id' => $category_2['category_id'],
                    'name' => $category_2['name'],
                    'children' => $level_3_data
                );
            }

            $data['categories'][] = array(
                'category_id' => $category_1['category_id'],
                'name' => $category_1['name'],
                'children' => $level_2_data
            );
        }
        $price_datas = array();
        if (!empty($price_ids)) {
            $this->load->model('catalog/filter');
            $price_datas = $this->model_catalog_filter->getFilterDataByFilterIds($price_ids);
        }
        $data['products'] = array();
        if (isset($this->request->get['search']) || isset($this->request->get['tag']) || isset($this->request->get['option_id']) || isset($this->request->get['price_id']) || isset($this->request->get['category_id']) || isset($this->request->get['find'])) {
            $filter_data = array(
                'filter_name' => $search,
                'filter_tag' => $tag,
                'filter_description' => $description,
                'filter_category_id' => $category_id,
                'filter_option_id' => $option_id,
                'filter_style_id' => $filter_style_id,
                'filter_size_id' => $filter_size_id,
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
            // die('dsd');
            // $filter_data = array(
            // 	'sort'                => $sort,
            // 	'order'               => $order,
            // 	'start'               => ($page - 1) * $limit,
            // 	'limit'               => $limit
            // 	);
            $filter_data = array(
                'filter_name' => $search,
                'filter_tag' => $tag,
                'filter_description' => $description,
                'filter_category_id' => $category_id,
                'filter_option_id' => $option_id,
                'filter_style_id' => $filter_style_id,
                'filter_size_id' => $filter_size_id,
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
        }
        // echo "<pre>";
        // var_dump($results);
        // die;
        foreach ($results as $result) {
            // var_dump($result['product_id']);
            // var_dump(urldecode($this->url->link('product/product', 'product_id=301' . $url)));
            $images = $this->model_catalog_product->getProductImages($result['product_id']);
            // echo "<pre>";
            // var_dump($results);
            // die;
            $image = '';
            foreach ($images as $key => $img) {

                $a = strstr($img['image'], 'https://s3.amazonaws.com/');
                if (!empty($a)) {
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
                if (!empty($result['image'])) {
                    $a = strstr($result['image'], 'https://s3.amazonaws.com/');
                    if (!empty($a)) {
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
            }


            // if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            // 	$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
            // } else {
            // 	$price = false;
            // }
            // get price from min_price to max_price
            // var_dump($result['product_id']);
            $price_min = $this->model_catalog_product->getPriceByProductId($result['product_id'], 11, "ASC");
            $price_max = $this->model_catalog_product->getPriceByProductId($result['product_id']);
            // var_dump($price_min);
            // var_dump($price_max);
            // die;
            // $price = $result['price'];
            $price = $price_min . " - " . $price_max;


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
//        echo $this->request->get['filter_style_id'] . "SIZE";
      // echo "URL" . $url;
	   //exit;
		$urlarr = explode("&",$url);
		foreach($urlarr as $key => $urldata){
			if(!empty($urldata)){
				$pos = strpos($urldata,'sort');

				$pos2 = strpos($urldata,'order');
				if ($pos !== false || $pos2 !== false) {
					unset($urlarr[$key]);
				}


			}
		}
		$urlsort = implode("&",$urlarr);
        $data['sorts'] = array();

        $data['sorts'][] = array(
            'text' => $this->language->get('Sort'),
            'value' => 'p.sort_order-ASC',
            'href' => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $urlsort)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href' => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $urlsort)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href' => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $urlsort)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href' => $this->url->link('product/search', 'sort=p.price&order=ASC' . $urlsort)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href' => $this->url->link('product/search', 'sort=p.price&order=DESC' . $urlsort)
        );

        if ($this->config->get('config_review_status')) {
            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href' => $this->url->link('product/search', 'sort=rating&order=DESC' . $urlsort)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href' => $this->url->link('product/search', 'sort=rating&order=ASC' . $urlsort)
            );
        }

       /* $data['sorts'][] = array(
            'text' => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href' => $this->url->link('product/search', 'sort=p.model&order=ASC' . $urlsort)
        );

        $data['sorts'][] = array(
            'text' => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href' => $this->url->link('product/search', 'sort=p.model&order=DESC' . $urlsort)
        );*/

//        $url = '';
//
//        if (isset($this->request->get['search'])) {
//            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
//        }
//
//        if (isset($this->request->get['tag'])) {
//            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
//        }
//
//        if (isset($this->request->get['description'])) {
//            $url .= '&description=' . $this->request->get['description'];
//        }
//
//        if (isset($this->request->get['category_id'])) {
//            $url .= '&category_id=' . $this->request->get['category_id'];
//        }
//
//        if (isset($this->request->get['filter_color_name'])) {
//            $url .= '&filter_color_name=' . $this->request->get['filter_color_name'];
//        }
//        if (isset($this->request->get['filter_size_id'])) {
//            $url .='&filter_size_id=' . $this->request->get['filter_size_id'];
//        }
//        if (isset($this->request->get['option_id'])) {
//            $url .='&option_id=' . $this->request->get['option_id'];
//        }
//
//        if (isset($this->request->get['sub_category'])) {
//            $url .= '&sub_category=' . $this->request->get['sub_category'];
//        }
//
//        if (isset($this->request->get['sort'])) {
//            $url .= '&sort=' . $this->request->get['sort'];
//        }
//
//        if (isset($this->request->get['order'])) {
//            $url .= '&order=' . $this->request->get['order'];
//        }

        $data['limits'] = array();

        $limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

        sort($limits);

        foreach ($limits as $value) {
            $data['limits'][] = array(
                'text' => $value,
                'value' => $value,
                'href' => $this->url->link('product/search', $url . '&limit=' . $value)
            );
        }

//        $url = '';

//        if (isset($this->request->get['search'])) {
//            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
//        }
//
//        if (isset($this->request->get['tag'])) {
//            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
//        }
//
//        if (isset($this->request->get['description'])) {
//            $url .= '&description=' . $this->request->get['description'];
//        }
//
//        if (isset($this->request->get['category_id'])) {
//            $url .= '&category_id=' . $this->request->get['category_id'];
//        }
//
//        if (isset($this->request->get['sub_category'])) {
//            $url .= '&sub_category=' . $this->request->get['sub_category'];
//        }
//        if (isset($this->request->get['filter_color_name'])) {
//            $url .= '&filter_color_name=' . $this->request->get['filter_color_name'];
//        }
//        if (isset($this->request->get['filter_size_id'])) {
//            $url .='&filter_size_id=' . $this->request->get['filter_size_id'];
//        }
//        if (isset($this->request->get['filter_price_id'])) {
//            $url .='&filter_price_id=' . $this->request->get['filter_price_id'];
//        }
//        if (isset($this->request->get['option_id'])) {
//            $url .='&option_id=' . $this->request->get['option_id'];
//        }
//        if (isset($this->request->get['filter_color_id'])) {
//            $url .='&filter_color_id=' . $this->request->get['filter_color_id'];
//        }
//        if (isset($this->request->get['filter_style_id'])) {
//            $url .= '&filter_style_id=' . $this->request->get['filter_style_id'];
//        }
//        if (isset($this->request->get['sort'])) {
//            $url .= '&sort=' . $this->request->get['sort'];
//        }
//
//        if (isset($this->request->get['order'])) {
//            $url .= '&order=' . $this->request->get['order'];
//        }
//
//        if (isset($this->request->get['limit'])) {
//            $url .= '&limit=' . $this->request->get['limit'];
//        }
//
//
//        echo "aboveURL:-" . $url . "</br>";

        $this->load->model('catalog/filter');
        $data['finder_filter'] = $this->model_catalog_filter->getAllFilters(null);

        $data['finder_options'] = $this->model_catalog_product->getAllOptions();
        $data['finder_prices'] = $this->model_catalog_filter->getFilterDescriptions(2);
        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/search', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        $datapr1 = ($product_total) ? (($page - 1) * $limit) + 1 : 0;
        $datapr2 = ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit);

        //$data['results_mobile'] = 'Showing '.$datapr1.' to '.$datapr2.' of '.$product_total;
        $data['results_mobile'] = 'RESULTS ' . $datapr2 . ' of ' . $product_total;
        $data['results_mobile_bot'] = 'RESULTS <small> showing ' . $datapr2 . ' of ' . $product_total . '</small>';

        $data['current_page'] = $page;
        $data['total_page'] = ceil($product_total / $limit);
        $urlViewMore = array();

        if (!empty($_REQUEST)) {
            foreach ($_REQUEST as $key => $value) {
                if ($key != 'page')
                    $urlViewMore[] = '' . $key . '=' . $value . '';
            }
        }
        $urlViewMore[] = 'page=' . ($page + 1) . '';
        $data['viewMoreURL'] = implode('&', $urlViewMore);

        $data['option_id'] = array();
        $data['price_id'] = array();
        $data['price_data'] = array();


        $data['search'] = $search;
        $data['description'] = $description;
        $data['category_id'] = $category_id;
        if (!empty($option_id)) {
            $data['option_id'] = explode(",", $option_id);
        }
        if (!empty($filter_size_id)) {
            $data['filter_size_id'] = explode(',', $filter_size_id);
        }
        if (!empty($price_ids)) {
            $data['filter_price_id'] = explode(",", $price_ids);
        }
        if (!empty($filter_color_id)) {
            $data['filter_color_id'] = explode(',', $filter_color_id);
        }
        if (!empty($filter_style_id)) {
            $data['filter_style_id'] = explode(',', $filter_style_id);
        }

        $data['sub_category'] = $sub_category;

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        // promo banner
        $this->load->model('design/banner');
        $bannerImages = $this->model_design_banner->getBannerByName('Promo');

        foreach ($bannerImages as $b) {
            if (stripos($b['title'], 'rug finder') !== FALSE && is_file(DIR_IMAGE . $b['image'])) {
                $b['image'] = $data['base'] . 'image/' . $b['image'];
                $data['banner'] = $b;
                break;
            }
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/search.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/search.tpl', $data));
        }
    }

    public function updateUrlImage()
    {
        $this->load->model('catalog/image');
        $all_products = $this->model_catalog_image->get_all_product();
        foreach ($all_products as $key => $product) {
            if (strpos( $product['image'], 's3.amazonaws.com')) {
                $check = strpos( $product['image'], '-FILEminimizer');
                if (!$check) {
                    $a = strpos($product['image'], '.jpg');
                    $file = substr($product['image'], 0, $a);
                    $newfile = $file.' -FILEminimizer.jpg';
                    $image = $newfile;
                    $this->model_catalog_image->update($product['product_id'], $image);
                }
            }
        }

        $all_products_images = $this->model_catalog_image->get_all_product_image();
        foreach ($all_products_images as $key => $product_image) {
            if (strpos( $product_image['image'], 's3.amazonaws.com')) {
                $check = strpos( $product_image['image'], '-FILEminimizer');
                if (!$check) {
                    $a = strpos($product_image['image'], '.jpg');
                    $file = substr($product_image['image'], 0, $a);
                    $newfile = $file.' -FILEminimizer.jpg';
                    $image = $newfile;
                    $this->model_catalog_image->update_product_image($product_image['product_image_id'], $image);
                }
            }
        }


        $all_products_option_values = $this->model_catalog_image->get_all_product_option_value();
        foreach ($all_products_option_values as $key => $product_option_value) {
            if (strpos( $product_option_value['image'], 's3.amazonaws.com')) {
                $check = strpos( $product_option_value['image'], '-FILEminimizer');
                if (!$check) {
                    $a = strpos($product_option_value['image'], '.jpg');
                    $file = substr($product_option_value['image'], 0, $a);
                    $newfile = $file.' -FILEminimizer.jpg';
                    $image = $newfile;
                    $this->model_catalog_image->update_product_option_value($product_option_value['product_option_value_id'], $image);
                }
            }
        }
        echo "done";
    }

}
