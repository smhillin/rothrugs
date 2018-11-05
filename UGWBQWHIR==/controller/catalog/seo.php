<?php
class ControllerCatalogSeo extends Controller {

    protected $error = array();

    public function customize() {
        require_once(DIR_SYSTEM . 'library/seo/seo.php');
        $this->load->model('catalog/seo');
        $this->model_catalog_seo->createTablesInDatabse();
        //$this->load->helper('seo_validator');

        $helper = 'seo_validator';

        $file = DIR_SYSTEM . 'helper/' . $helper . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load helper ' . $helper . '!');
            exit();
        }

        $this->validate = new SeoValidator($this->registry);

        $this->language->load('catalog/seo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/jquery/jquery.tipTip.minified.js');
        $this->document->addLink("view/stylesheet/allseo.css","stylesheet");

        if (isset($this->request->get['tab'])) {
            $tab = $this->request->get['tab'];
        } else if(isset($this->request->post['tab'])) {
            $tab = $this->request->post['tab'];
        } else {
            $tab = 'tab_products';
        }
       
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($this->request->post))) {

            $post_data = $this->request->post;
            
            switch ($post_data['tab']) {
                case 'tab_general' :
                    $this->model_catalog_seo->clearGeneral();

                    if(isset($post_data['custom_url_store'])) {
                        foreach($post_data['custom_url_store'] as $row) {
                           
                            $object = new SeoGeneral($this->registry);
                            $object->setSeoKeyword($row['id']['keyword']);
                            $object->setQuery('route='.$row['id']['query']);
                            $object->setId($row['id']['query']);
                            $object->save();
                        }
                    }
                    break;
                case 'tab_products' :
               
                    foreach($post_data['product']['keyword'] as $product_id => $keyword) {
                       
                        $object = new SeoProduct($this->registry);
                        $object->setSeoKeyword($keyword);
                        $object->setQuery('product_id='.$product_id);

                        $title = $meta_description = $meta_keywords = array();

                        foreach($post_data['product']['product_description'][$product_id] as $language_id => $r) {
                            $title[$language_id] = $r['title'];
                            $meta_keywords[$language_id] = $r['meta_keywords'];
                            $meta_description[$language_id] = $r['meta_description'];
                            $tags[$language_id] = $r['tags'];
                        }

                        $object->setTitle($title);
                        $object->setId($product_id);
                        $object->setMetaKeywords($meta_keywords);
                        $object->setMetaDescription($meta_description);
                        $object->setTags($tags);
                        $object->save();
                    }
                    break;
                case 'tab_categories' :

                    foreach($post_data['category']['keyword'] as $category_id => $keyword) {
                        $object = new SeoCategory($this->registry);
                        $object->setSeoKeyword($keyword);
                        $object->setQuery('category_id='.$category_id);

                        $title = $meta_description = $meta_keywords = array();

                        foreach($post_data['category']['category_description'][$category_id] as $language_id => $r) {
                            $title[$language_id] = $r['title'];
                            $meta_keywords[$language_id] = $r['meta_keywords'];
                            $meta_description[$language_id] = $r['meta_description'];
                        }

                        $object->setTitle($title);
                        $object->setId($category_id);
                        $object->setMetaKeywords($meta_keywords);
                        $object->setMetaDescription($meta_description);
                        $object->save();
                    }
                    break;

                case 'tab_manufacturers' :

                        foreach($post_data['manufacturer']['manufacturer_id'] as $manufacturer_id => $keyword) {

                        $object = new SeoManufacturer($this->registry);
                        $object->setSeoKeyword($keyword);
                        $object->setQuery('manufacturer_id='.$manufacturer_id);

                        $title = $meta_description = $meta_keywords = array();

                        foreach($post_data['manufacturer']['manufacturer_description'][$manufacturer_id] as $language_id => $r) {
                            $title[$language_id] = $r['title'];
                            $meta_keywords[$language_id] = $r['meta_keywords'];
                            $meta_description[$language_id] = $r['meta_description'];
                        }

                        $object->setTitle($title);
                        $object->setId($manufacturer_id);
                        $object->setMetaKeywords($meta_keywords);
                        $object->setMetaDescription($meta_description);
                        $object->save();
                        }

                    break;

                case 'tab_information_pages' :

                    foreach($post_data['information']['keyword'] as $information_id => $keyword) {

                        $object = new SeoInformation($this->registry);
                        $object->setSeoKeyword($keyword);
                        $object->setQuery('information_id='.$information_id);

                        $title = $meta_description = $meta_keywords = array();

                        foreach($post_data['information']['information_description'][$information_id] as $language_id => $r) {
                            $title[$language_id] = $r['title'];
                            $meta_keywords[$language_id] = $r['meta_keywords'];
                            $meta_description[$language_id] = $r['meta_description'];
                        }

                        $object->setTitle($title);
                        $object->setId($information_id);
                        $object->setMetaKeywords($meta_keywords);
                        $object->setMetaDescription($meta_description);
                        $object->save();

                    }

                    break;

            }

            $data['tab'] = $post_data['tab'];
            $this->session->data['success'] = $this->language->get('text_success_'.$post_data['tab']);

            $this->response->redirect($this->url->link("catalog/seo/customize","token=" . $this->session->data['token'] . '&tab=' . $data['tab'],"SSL"));

        }

        $data['success'] = '';
        if(isset($this->session->data['success'])){
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }    

        if (isset($this->request->get['filter_keyword'])) {
            $filter_keyword = $this->request->get['filter_keyword'];
        } else {
            $filter_keyword = '';
        }

         if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = '';
        }
        
        $url = '';
                        
        if (isset($this->request->get['filter_keyword'])) {
            $url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
        }  

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }       

        $data['tab'] = isset($data['tab']) ? $data['tab'] : $tab;
       
        $data['heading_title'] = $this->language->get('heading_title');
        $data['description_st'] = $this->language->get('description');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_keyword'] = $this->language->get('entry_keyword');

        $data['column_url'] = $this->language->get('column_url');
        $data['column_keyword'] = $this->language->get('column_keyword');
        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_keyword'] = $this->language->get('column_keyword');
        $data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
        $data['column_meta_description'] = $this->language->get('column_meta_description');
        $data['column_tags'] = $this->language->get('column_tags');
        $data['custom_url_help'] = $this->language->get('custom_url_help');

        $data['tab_general']  = $this->language->get('tab_general');
        $data['tab_products'] = $this->language->get('tab_products');
        $data['tab_categories'] = $this->language->get('tab_categories');
        $data['tab_manufacturers'] = $this->language->get('tab_manufacturers');
        $data['tab_information_pages'] = $this->language->get('tab_information_pages');

        $data['domain'] = $this->config->get('config_url');

        $data['button_autofill'] = $this->language->get('button_autofill');
        $data['button_save'] = $this->language->get('button_save');
        $data['helpedit'] = $this->language->get('helpedit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_reset'] = $this->language->get('button_reset');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_add_custom_url_store'] = $this->language->get('button_add_custom_url_store');
        $data['button_save_general'] = $this->language->get('button_save_general');
        $data['button_save_products'] = $this->language->get('button_save_products');
        $data['button_save_categories'] = $this->language->get('button_save_categories');
        $data['button_save_manufacturers'] = $this->language->get('button_save_manufacturers');
        $data['button_save_information_pages'] = $this->language->get('button_save_information_pages');

        $data['title_help'] = $this->language->get('title_help');
        $data['keywords_help'] = $this->language->get('keywords_help');
        $data['description_help'] = $this->language->get('description_help');
        $data['tags_help'] = $this->language->get('tags_help');

        $data['links'][] = array(
                'text'      => "Auto Generate Tool",
                'href'      => $this->url->link('catalog/seo/autogenerate', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/product.png'
        );

         $data['links'][] = array(
                'text'      => "Seo Advanced Editor",
                'href'      => $this->url->link('catalog/seo/customize', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/log.png'
        );

        $data['links'][] = array(
                'text'      => "Dynamic Seo Report",
                'href'      => $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/report.png'
        );

        $data['links'][] = array(
                'text'      => "Complete Rich Snippet",
                'href'      => $this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/feed.png'
        );

        $data['links'][] = array(
                'text'      => "Sitemap Generator Pro",
                'href'      => $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/review.png'
        );

        $data['links'][] = array(
                'text'      => "Clear Seo Tool",
                'href'      => $this->url->link('catalog/clearseo', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/error.png'
        );

        $data['links'][] = array(
                    'text'      => "Seo Redirect Manager",
                    'href'      => $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/error.png'
        );

        $data['links'][] = array(
                    'text'      => "General Setting",
                    'href'      => $this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/setting.png'
        );

        $this->languages = $this->model_catalog_seo->getLanguages();

        foreach($this->languages as $language){
            $data['currency'] = $language['language_id'];
            break;
        }

        if (isset($this->error['title'])) {
            foreach($this->error['title'] as $key => $value){
                $data['error_'.$key] = $this->error['title'][$key];
            }
        }

        if(isset($this->error['already_exists'])){
            $data['error_already_exists'] = $this->error['already_exists'];
        }else{
            $data['error_already_exists'] = '';
        }

        $data['languages'] = $this->languages;

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }       

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link("common/home","token=" . $this->session->data['token'],"SSL"),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link("catalog/seo/customize","token=" . $this->session->data['token'],"SSL"),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $data['autofill'] = $this->url->link("catalog/seo/loadgeneralauto","token=" . $this->session->data['token'],"SSL");
        $data['action'] = $this->url->link("catalog/seo/customize","token=" . $this->session->data['token'],"SSL");
        $data['cancel'] = $this->url->link("catalog/seo/customize","token=" . $this->session->data['token'],"SSL");
        $data['filter'] =  $this->url->link("catalog/seo/customize","token=" . $this->session->data['token']."&tab=".$tab,"SSL");
        $data['token'] = $this->session->data['token'];
        $this->load->model('tool/image');

        $this->config_limit_admin = $this->config->get('config_limit_admin');

        $data['custom_url_store_data'] = array();
        $data['products'] = array();
        $data['categories'] = array();
        $data['manufacturers'] = array();
        $data['informations'] = array();

        switch ($data['tab']) {
            case 'tab_products':
                $data['products'] = $this->loadProducts($filter_keyword);
                break;
            case 'tab_categories':
                $data['categories'] = $this->loadCategories($filter_category);
                break;
            case 'tab_manufacturers':
                $data['manufacturers'] = $this->loadManufactures();
                break;
            case 'tab_information_pages':
                $data['informations'] = $this->loadInformationPages();
                break;
            case 'tab_general':
            default:
                $data['custom_url_store_data'] = $this->loadGeneral();

        }

        /*------------------------- Product ------------------*/

        /*------------------------- Categories ------------------*/

        /*------------------------- Manufacturer ------------------*/

        /*------------------------- Information ------------------*/


        if (isset($this->request->post['custom_url_store_status'])) {
            $data['custom_url_store_status'] = $this->request->post['custom_url_store_status'];
        } else {
            $data['custom_url_store_status'] = $this->config->get('custom_url_store_status');
        }
        
        $data['filter_keyword'] = $filter_keyword;
        $data['filter_category'] = $filter_category;

        $data['pagination_product'] = $this->multiPagination('pagination_product',$this->config_limit_admin, $this->product_total, $this->page_product, '&tab=tab_products');
         $data['pagination_category'] =  $this->multiPagination('pagination_category',$this->config_limit_admin, $this->category_total, $this->page_category, '&tab=tab_categories');
/*        $this->multiPagination('pagination_general',$this->config_limit_admin, $custom_url_total, $page_general, '&tab=tab_general');
        $this->multiPagination('pagination_category',$this->config_limit_admin, $category_total, $page_category, '&tab=tab_categories');
        $this->multiPagination('pagination_manufacturer',$this->config_limit_admin, $manufacturer_total, $page_manufacturer, '&tab=tab_manufacturers');
        $this->multiPagination('pagination_information',$this->config_limit_admin, $information_total, $page_information, '&tab=tab_information_pages');
*/
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('catalog/seo_customize.tpl', $data));
    }
    
    public function loadGeneralAuto(){
    
        $this->load->model('catalog/seo');
        
        function seo_map($file){
                               
                                $ignore_list = array(
                                    'common/column_left', 'common/column_right', 'common/content_bottom', 'common/content_top', 
                                    'common/footer', 'common/header', 'common/landing', 'common/maintenance', 'common/cart','common/search',
                                    'common/seo_content', 'common/seo_url','common/currency','common/language', 'checkout/confirm','checkout/voucher','checkout/guest','checkout/reward', 
                                    'checkout/guest_shipping', 'checkout/login', 'checkout/manual', 'checkout/payment_address', 
                                    'checkout/payment_method', 'checkout/register', 'checkout/shipping_address', 'checkout/shipping_method',
                                    'product/product', 'product/category','information/information', 
                                );

                                if( !preg_match('/^(module|feed|payment)\/.+$/', $file) && !in_array($file, $ignore_list) ) {
                                    return $file;
                                } else {
                                    return '';
                                }
                        }
                        
          function file_map($file){
                        $ignore_list = array("api","amazonus","amazon","ebay","openbay","tool","journal","journal2","kuler","quickcheckout");
                            if (!in_array(basename(dirname($file)), $ignore_list)) {
                               return basename(dirname($file)). '/' . basename($file, '.php');
                            }        
                        }
                        
        $languages = $this->model_catalog_seo->getLanguages();
        $general_urls = array_filter(array_map('file_map', glob(DIR_CATALOG . 'controller/*/*.php')), 'seo_map');

        $this->model_catalog_seo->clearGeneral();

        foreach($general_urls as $general_url){
           
            $object = new SeoGeneral($this->registry);
            
            if(dirname($general_url)== "affiliate"){
                $object->setSeoKeyword("affliate-".basename($general_url));
            } elseif ($general_url=="checkout/success") {
                $object->setSeoKeyword("checkout-".basename($general_url));
            } elseif (dirname($general_url)== "amazon") {
                $object->setSeoKeyword("amazon-".basename($general_url));
            } elseif (dirname($general_url)== "amazonus") {
                $object->setSeoKeyword("amazonus-".basename($general_url));
            }  elseif (dirname($general_url)== "play") {
                $object->setSeoKeyword("play-".basename($general_url));
            } else {
                $object->setSeoKeyword(basename($general_url));
            }
            
            $object->setQuery('route='.$general_url);

            $object->save();
            
        }
    }

    public function loadGeneral() {
    

        $page_general = 1;

        if(isset($tab) && $tab=='tab_general'){
            $page_general = $page;
        }

        $data = array(
            'start'           => ($page_general - 1) * $this->config_limit_admin,
            'limit'           => $this->config_limit_admin
        );
       
        
        $data['custom_url_store_data'] = array();
        if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->request->post['tab']=='tab_general') {
            if(isset($this->request->post['custom_url_store'])){
                foreach ($this->request->post['custom_url_store'] as $index => $info) {
                    $keyword_array = array(
                        'query'     =>  'route='.$info['id']['query'],
                        'keyword'   =>  $info['id']['keyword']
                    );
                    $keyword_query[] = $keyword_array;

                }

                $count = isset($keyword_query) ? COUNT($keyword_query) : 0;

                $custom_url_store_data = array();

                for($i=0; $i<$count; $i++){
                    $custom_url_store_data[$i]['keyword_query'] = $keyword_query[$i];
                }

                $custom_url_total = isset($custom_url_store_data) ? COUNT($custom_url_store_data) : 0;
                
                if($data){
                    $custom_url_store_data = array_slice($custom_url_store_data, $data['start'] , $data['limit']);
                }

                $data['custom_url_store_data'] = $custom_url_store_data;
            }

        } else {

            $custom_url_data = Seo::findGeneral($this->registry);
            $custom_url_total = Seo::findGeneralTotal($this->registry);

            foreach($custom_url_data as $object) {

                $custom_url_store_description = array();

                $data['custom_url_store_data'][$object->getUrlAliasId()] = array(
                    'keyword_query'         => array('query' => $object->getQuery(), 'keyword' => $object->getSeoKeyword()),
                    'custom_url_store_description'  =>  $custom_url_store_description
                );
            }

        }
        return $data['custom_url_store_data'];
        
    }


    public function loadProducts($keyword = '') {

        $this->load->model('catalog/product');

        $this->page_product = 1;

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->page_product = $page;

        $data = array(
            'start'           => ($this->page_product - 1) * $this->config_limit_admin,
            'limit'           => $this->config_limit_admin,
            'filter_keyword'  => $keyword
        );
       
        $products = Seo::findProducts($this->registry, $data);
        $this->product_total = Seo::findProductsTotal($this->registry, $data);
       
        foreach ($products as $product) {

            $product_image = $product->getImage();

            if ($product_image && file_exists(DIR_IMAGE . $product_image)) {
                $image = $this->model_tool_image->resize($product_image, 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
            }

            $current_tab = (isset($this->request->post['tab']) && $this->request->post['tab']=='tab_products') ? 1 : 0;

            if(isset($this->request->post['product']['product_description']) && $current_tab){
                $product_description = $this->request->post['product']['product_description'][$product->getId()];
            }else{
                $titles = $product->getTitle();
                $meta_keywords = $product->getMetaKeywords();
                $meta_description = $product->getMetaDescription();
                $tags = $product->getTags();

                $product_description = array();

                foreach($this->languages as $language) {
                    if(array_key_exists($language['language_id'], $titles)) {
                        $product_description[$language['language_id']] = array(
                            'title' => $titles[$language['language_id']],
                            'meta_keywords' => $meta_keywords[$language['language_id']],
                            'meta_description' => $meta_description[$language['language_id']],
                            'tags' => $tags[$language['language_id']]
                        );
                    } else {
                        $product_description[$language['language_id']] = array(
                            'title' => '',
                            'meta_keywords' => '',
                            'meta_description' => '',
                            'tags' => ''
                        );
                    }
                }
            }
            
            if(isset($this->request->post['product']['keyword']) && $current_tab){
                $keyword['language_id'] = $this->request->post['product']['keyword'][$product->getId()];
            }else{
                $keyword = $product->getSeoKeyword();
            }
           
            $data['products'][] = array(
                'product_id' => $product->getId(),
                'name'       => $product->getName(),
                'model'      => $product->getModel(),
                'keyword'    => $keyword,
                'product_description'   => $product_description,
                'image'      => $image
            );
        }
       return $data['products'];
    }


    public function loadCategories($category = "") {

        $this->load->model('catalog/category');

        $this->page_category = 1;

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->page_category = $page;

        $data = array(
            'start'           => ($this->page_category - 1) * $this->config_limit_admin,
            'limit'           => $this->config_limit_admin,
            'filter_keyword'  => $category
        );

        $categories = Seo::findCategories($this->registry, $data);
        $this->category_total = Seo::findCategoriesTotal($this->registry, $data);

        foreach ($categories as $category) {

            $current_tab = (isset($this->request->post['tab']) && $this->request->post['tab']=='tab_categories') ? 1 : 0;

            if(isset($this->request->post['category']['category_description']) && $current_tab){
                $category_description = $this->request->post['category']['category_description'][$category->getId()];
            } else {

                $titles = $category->getTitle();
                $meta_keywords = $category->getMetaKeywords();
                $meta_description = $category->getMetaDescription();

                $category_description = array();

                foreach($this->languages as $language) {
                    if(array_key_exists($language['language_id'], $titles)) {
                        $category_description[$language['language_id']] = array(
                            'title' => $titles[$language['language_id']],
                            'meta_keywords' => $meta_keywords[$language['language_id']],
                            'meta_description' => $meta_description[$language['language_id']]
                        );
                    } else {
                        $category_description[$language['language_id']] = array(
                            'title' => '',
                            'meta_keywords' => '',
                            'meta_description' => ''
                        );
                    }
                }
            }

            if(isset($this->request->post['category']['keyword']) && $current_tab){
                $keyword['language_id'] = $this->request->post['category']['keyword'][$category->getId()];
            }else{
                $keyword = $category->getSeoKeyword();
            }

            $data['categories'][] = array(
                'category_id' => $category->getId(),
                'name'       => $category->getName(),
                'keyword'    => $keyword,
                'category_description'  => $category_description
            );
        }
        return $data['categories'];
    }


    public function loadManufactures() {

        $this->load->model('catalog/manufacturer');

        $page_manufacturer = 1;

        if(isset($tab) && $tab=='tab_manufacturers'){
            $page_manufacturer = $page;
        }

        $data = array(
            'start'           => ($page_manufacturer - 1) * $this->config_limit_admin,
            'limit'           => $this->config_limit_admin
        );

        $manufacturers = Seo::findManufacturers($this->registry);
        $manufacturer_total = Seo::findManufacturersTotal($this->registry);

        foreach($manufacturers as $manufacturer){

            $current_tab = (isset($this->request->post['tab']) && $this->request->post['tab']=='tab_manufacturers') ? 1 : 0;

            if(isset($this->request->post['manufacturer']['manufacturer_description']) && $current_tab){
                $manufacturer_description = $this->request->post['manufacturer']['manufacturer_description'][$manufacturer->getId()];
            }else{

                $titles = $manufacturer->getTitle();
                $meta_keywords = $manufacturer->getMetaKeywords();
                $meta_description = $manufacturer->getMetaDescription();

                $manufacturer_description = array();

                foreach($this->languages as $language) {
                    $manufacturer_description[$language['language_id']] = array(
                        'title' => array_key_exists($language['language_id'], $titles) ? ($titles[$language['language_id']]):'',
                        'meta_keywords' => array_key_exists($language['language_id'], $meta_keywords) ? $meta_keywords[$language['language_id']] : '' ,
                        'meta_description' => array_key_exists($language['language_id'], $meta_description) ? $meta_description[$language['language_id']] : ''
                    );
                }

            }

            if(isset($this->request->post['manufacturer']['manufacturer_id']) && $current_tab){
                $keyword = $this->request->post['manufacturer']['manufacturer_id'][$manufacturer->getId()];
            }else{
                $keyword = $manufacturer->getSeoKeyword();
            }

            $data['manufacturers'][] = array(
                'manufacturer_id' => $manufacturer->getId(),
                'name'       => $manufacturer->getName(),
                'keyword'    => $keyword,
                'manufacturer_description'  => $manufacturer_description
            );
        }
        return $data['manufacturers'];
    }


    public function loadInformationPages() {

        $this->load->model('catalog/information');

        $page_information = 1;

        if(isset($tab) && $tab=='tab_information_pages'){
            $page_information = $page;
        }

        $data = array(
            'start'           => ($page_information - 1) * $this->config_limit_admin,
            'limit'           => $this->config_limit_admin
        );

        $data = array();

        $informations = Seo::findInformations($this->registry);
        $information_total = Seo::findInformationsTotal($this->registry);

        foreach($informations as $information){

            $current_tab = (isset($this->request->post['tab']) && $this->request->post['tab']=='tab_information_pages') ? 1 : 0;

            if(isset($this->request->post['information']['information_description']) && $current_tab){
                $information_description = $this->request->post['information']['information_description'][$information->getId()];
            }else{
                $titles = $information->getTitle();
                $meta_keywords = $information->getMetaKeywords();
                $meta_description = $information->getMetaDescription();

                $information_description = array();

                foreach($this->languages as $language) {
                    if(array_key_exists($language['language_id'], $titles)) {
                        $information_description[$language['language_id']] = array(
                            'title' => $titles[$language['language_id']],
                            'meta_keywords' => $meta_keywords[$language['language_id']],
                            'meta_description' => $meta_description[$language['language_id']]
                        );
                    } else {
                        $information_description[$language['language_id']] = array(
                            'title' => '',
                            'meta_keywords' => '',
                            'meta_description' => ''
                        );
                    }
                }
            }

            if(isset($this->request->post['information']['keyword']) && $current_tab){
                $keyword['language_id'] = $this->request->post['information']['keyword'][$information->getId()];
            }else{
                $keyword = $information->getSeoKeyword();
            }

            $data['informations'][] = array(
                'name'  =>  $information->getName(),
                'information_id' => $information->getId(),
                'keyword'    => $keyword,
                'information_description'   => $information_description
            );
        }
        return $data['informations'];
    }

    public function autogenerate() {
        require_once(DIR_SYSTEM . 'library/seo/seo.php');
        $this->load->model('catalog/seo');
        $this->model_catalog_seo->createTablesInDatabse();
        $this->language->load('catalog/seo_autogenerate');
        $this->document->addLink("view/stylesheet/allseo.css","stylesheet");
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateAutogenerate())) {
            // Remember that KEY of $pattern array has to be the actual column name of xstore_seo_pattern table

            $dynamic_success = '';
            $success = $this->language->get('text_success');
            /*  PRODUCTS  */
            if (isset($this->request->post['products_url'])) {
                $pattern = array(
                    'product_url_keyword'       =>  $this->request->post['products_url_template']
                );
                $this->model_catalog_seo->generateProductUrlKeyword($this->request->post['products_url_template'], $pattern);
                $dynamic_success = $this->language->get('product_url_keyword');
            }
            if(isset($this->request->post['products_title'])){
                $pattern = array(
                    'product_title'       =>  $this->request->post['products_title_template']
                );
                $this->model_catalog_seo->generateProductTitle($this->request->post['products_title_template'], $pattern);
                $dynamic_success = $this->language->get('product_title');
            }
            if (isset($this->request->post['product_keywords'])) {
                $pattern = array(
                    'product_meta_keywords' =>  $this->request->post['product_keywords_template']
                );
                if (!isset($this->request->post['yahoo_checkbox'])) {
                    $this->model_catalog_seo->generateProductMetaKeywords($this->request->post['product_keywords_template'], null, $pattern);
                } else if (trim($this->request->post['yahoo_id']) != '') {
                    $this->model_catalog_seo->generateProductMetaKeywords($this->request->post['product_keywords_template'], trim($this->request->post['yahoo_id']), $pattern);
                } else {
                    $this->error['warning'] = $this->language->get('enter_yahoo_id');
                }
                $dynamic_success = $this->language->get('product_meta_keywords');
            }
            if (isset($this->request->post['product_description'])) {
                $pattern = array(
                    'product_meta_description'  =>  $this->request->post['product_description_template']
                );
                $this->model_catalog_seo->generateProductMetaDescription($this->request->post['product_description_template'], $pattern);
                $dynamic_success = $this->language->get('product_meta_description');
            }
            if (isset($this->request->post['product_tags'])) {
                $pattern = array(
                    'product_tags'  =>  $this->request->post['product_tags_template']
                );
                $this->model_catalog_seo->generateProductTags($this->request->post['product_tags_template'], $pattern);
                $dynamic_success = $this->language->get('product_tags');
            }
            if (isset($this->request->post['product_image'])) {
                $pattern = array(
                    'product_image_name'    =>  $this->request->post['product_image_template']
                );
                $this->model_catalog_seo->generateProductImage($this->request->post['product_image_template'], $pattern);
                $dynamic_success = $this->language->get('product_image_name');
            }

            /*  CATEGORIES  */
            if (isset($this->request->post['categories_url'])) {
                $pattern = array(
                    'category_url_keyword'  =>  $this->request->post['categories_url_template']
                );
                $this->model_catalog_seo->generateCategoryUrlKeyword($this->request->post['categories_url_template'], $pattern);
                $dynamic_success = $this->language->get('category_url_keyword');
            }

            if (isset($this->request->post['categories_title'])) {
                $pattern = array(
                    'category_title' =>  $this->request->post['categories_title_template']
                );
                $this->model_catalog_seo->generateCategoryTitle($this->request->post['categories_title_template'], $pattern);
                $dynamic_success = $this->language->get('category_title');
            }

            if (isset($this->request->post['categories_keyword'])) {
                $pattern = array(
                    'category_keyword' =>  $this->request->post['categories_keyword_template']
                );
                $this->model_catalog_seo->generateCategoryMetaKeywords($this->request->post['categories_keyword_template'], $pattern);
                $dynamic_success = $this->language->get('category_meta_keywords');
            }

            if (isset($this->request->post['category_description'])) {
                $pattern = array(
                    'category_meta_description' =>  $this->request->post['category_description_template']
                );
                $this->model_catalog_seo->generateCategoryMetaDescription($this->request->post['category_description_template'], $pattern);
                $dynamic_success = $this->language->get('category_meta_description');
            }

            /*  MANUFACTURERS  */
            if (isset($this->request->post['manufacturers_url'])) {
                $pattern = array(
                    'manufacturer_url_keyword'  =>  $this->request->post['manufacturers_url_template']
                );
                $this->model_catalog_seo->generateManufacturerUrlKeyword($this->request->post['manufacturers_url_template'], $pattern);
                $dynamic_success = $this->language->get('manufacturer_url_keyword');
            }

            if (isset($this->request->post['manufacturer_title'])) {
                $pattern = array(
                  'manufacturer_title' =>  $this->request->post['manufacturer_title_template']
                );
                $this->model_catalog_seo->generateManufacturerPageTitle($this->request->post['manufacturer_title_template'], $pattern);
                $dynamic_success = $this->language->get('manufacturer_title');
            }

            if (isset($this->request->post['manufacturer_keyword'])) {
                $pattern = array(
                    'manufacturer_keyword' =>  $this->request->post['manufacturer_keyword_template']
                );
                $this->model_catalog_seo->generateManufacturerMetaKeywords($this->request->post['manufacturer_keyword_template'], $pattern);
                $dynamic_success = $this->language->get('manufacturer_meta_keywords');
            }

            if (isset($this->request->post['manufacturer_description'])) {
                $pattern = array(
                    'manufacturer_meta_description' =>  $this->request->post['manufacturer_description_template']
                );
                $this->model_catalog_seo->generateManufacturerMetaDescription($this->request->post['manufacturer_description_template'], $pattern);
                $dynamic_success = $this->language->get('manufacturer_meta_description');
            }

            /*  INFORMATION PAGES  */
            if (isset($this->request->post['information_pages'])) {
                $pattern = array(
                    'information_page_url_keyword'  =>  $this->request->post['information_pages_template']
                );
                $this->model_catalog_seo->generateInformationPageUrlKeyword($this->request->post['information_pages_template'], $pattern);
                $dynamic_success = $this->language->get('information_page_url_keyword');
            }

            if (isset($this->request->post['information_pages_title'])) {
                $pattern = array(
                    'information_pages_title'  =>  $this->request->post['information_pages_title_template']
                );
                $this->model_catalog_seo->generateInformationPageTitle($this->request->post['information_pages_title_template'], $pattern);
                $dynamic_success = $this->language->get('information_page_title');
            }

            if (isset($this->request->post['information_keyword'])) {
                $pattern = array(
                    'information_keyword' =>  $this->request->post['information_keyword_template']
                );
                $this->model_catalog_seo->generateInformationMetaKeywords($this->request->post['information_keyword_template'], $pattern);
                $dynamic_success = $this->language->get('information_meta_keywords');
            }

            if (isset($this->request->post['information_description'])) {
                $pattern = array(
                    'information_meta_description' =>  $this->request->post['information_description_template']
                );
                $this->model_catalog_seo->generateInformationMetaDescription($this->request->post['information_description_template'], $pattern);
                $dynamic_success = $this->language->get('information_meta_description');
            }

            if (isset($this->request->post['general_pages'])) {
                $this->loadGeneralAuto();
                 $dynamic_success = $this->language->get('general_pages');
            }
            
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
              $data['success'] = $dynamic_success . ' ' . $success;
               $dynamic_success = '';
            }

        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link("common/home","token=" . $this->session->data['token'],"SSL"),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link("catalog/seo/autogenerate","token=" . $this->session->data['token'],"SSL"),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
            );

            $data['warning_clear'] = $this->language->get('warning_clear');
            $data['warning_clear_tags'] = $this->language->get('warning_clear_tags');

            $data['tags'] = $this->language->get('tags');
            $data['generate'] = $this->language->get('generate');

            $data['available_category_tags'] = $this->language->get('available_category_tags');
            $data['available_information_pages_tags'] = $this->language->get('available_information_pages_tags');
            $data['autogenerate_help'] = $this->language->get('autogenerate_help');
            $data['text_description'] = $this->language->get('text_description');
            $data['text_pattern'] = $this->language->get('text_pattern');
            $data['text_action'] = $this->language->get('text_action');

            $data['text_products'] = $this->language->get('text_products');
            $data['text_categories'] = $this->language->get('text_categories');
            $data['text_manufacturers'] = $this->language->get('text_manufacturers');
            $data['text_information_pages'] = $this->language->get('text_information_pages');
            $data['clear_seo'] = $this->language->get('clear_seo');
            $data['clear_seo_info'] = $this->language->get('clear_seo_info');
            $data['clear_seo_header'] = $this->language->get('clear_seo_header');            
            $data['helpauto'] = $this->language->get('helpauto');
            $data['text_url_keyword'] = $this->language->get('text_url_keyword');
            $data['text_title'] = $this->language->get('text_title');
            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');

            $data['text_general_data'] = $this->language->get('text_general_data');
            $data['help_general1_data'] = $this->language->get('help_general1_data');
            $data['help_general_data'] = $this->language->get('help_general_data');

            $data['links'][] = array(
                'text'      => "Auto Generate Tool",
                'href'      => $this->url->link('catalog/seo/autogenerate', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/product.png'
            );

             $data['links'][] = array(
                    'text'      => "Seo Advanced Editor",
                    'href'      => $this->url->link('catalog/seo/customize', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/log.png'
            );

            $data['links'][] = array(
                    'text'      => "Dynamic Seo Report",
                    'href'      => $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/report.png'
            );

            $data['links'][] = array(
                    'text'      => "Complete Rich Snippet",
                    'href'      => $this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/feed.png'
            );

            $data['links'][] = array(
                    'text'      => "Sitemap Generator Pro",
                    'href'      => $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/review.png'
            );

            $data['links'][] = array(
                    'text'      => "Clear Seo Tool",
                    'href'      => $this->url->link('catalog/clearseo', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/error.png'
            );

            $data['links'][] = array(
                    'text'      => "Seo Redirect Manager",
                    'href'      => $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/error.png'
            );

            $data['links'][] = array(
                    'text'      => "General Setting",
                    'href'      => $this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/setting.png'
            );

            $data['token'] = $this->session->data['token'];
            $data['button_cancel'] = $this->language->get('button_cancel');
            
            if($this->request->server['REQUEST_METHOD'] != 'POST'){
                $this->load->model('catalog/seo');
                $pattern = $this->model_catalog_seo->getSeoPattern();
            }

            $user_defined_text = $this->language->get('user_defined_text');
            $user_defined_text_meta = $this->language->get('user_defined_text_meta');

            // Products

            if (isset($this->request->post['products_url_template'])) {
                $data['products_url_template'] = $this->request->post['products_url_template'];
            } elseif(isset($pattern['product_url_keyword']) && $pattern['product_url_keyword']) {
                $data['products_url_template'] = $pattern['product_url_keyword'];
            } else{
                $data['products_url_template'] = '[product_name], [model_name], [manufacturer_name], [meta_title]';
            }
            $pattern_available = '[product_name], [model_name], [manufacturer_name], [product_price], [meta_title]';
            $data['help_product_seo_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available);
            $data['text_product_url_keyword'] = sprintf($this->language->get('dynamic_main_description'), 'Product', $data['text_url_keyword'],$this->language->get('text_set_pattern'));

            if (isset($this->request->post['products_title_template'])) {
                $data['products_title_template'] = $this->request->post['products_title_template'];
            } elseif(isset($pattern['product_title']) && $pattern['product_title']) {
                $data['products_title_template'] = $pattern['product_title'];
            } else{
                $data['products_title_template'] = '[product_name], [product_description]';
            }
            $pattern_available = '[product_name], [product_description], [product_price], [model_name], [manufacturer_name]';
            $data['help_product_title'] = sprintf($this->language->get('dymanic_text'), $pattern_available);
            $data['text_product_title'] = sprintf($this->language->get('dynamic_main_description'), 'Product', $this->language->get('text_title'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['product_keywords_template'])) {
                $data['product_keywords_template'] = $this->request->post['product_keywords_template'];
            } elseif(isset($pattern['product_meta_keywords']) && $pattern['product_meta_keywords']) {
                $data['product_keywords_template'] = $pattern['product_meta_keywords'];
            } else{
                $data['product_keywords_template'] = '[product_name], [model_name], [manufacturer_name], [categories_names]';
            }
            $pattern_available = '[product_name], [model_name], [manufacturer_name], [categories_names], [product_price]';
            $data['help_meta_keywords'] = sprintf($this->language->get('dymanic_text'), $pattern_available);
            $data['text_product_meta_keywords'] = sprintf($this->language->get('dynamic_main_description'), 'Product', $this->language->get('text_meta_keywords'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['product_description_template'])) {
                $data['product_description_template'] = $this->request->post['product_description_template'];
            } elseif(isset($pattern['product_meta_description']) && $pattern['product_meta_description']) {
                $data['product_description_template'] = $pattern['product_meta_description'];
            } else{
                $data['product_description_template'] = '[product_name], [product_description]';
            }
            $pattern_available = '[product_name], [product_description], [product_price]';
            $data['help_product_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available);
            $data['text_product_meta_description'] =  sprintf($this->language->get('dynamic_main_description'), 'Product', $this->language->get('text_meta_description'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['product_tags_template'])) {
                $data['product_tags_template'] = $this->request->post['product_tags_template'];
            } elseif(isset($pattern['product_tags']) && $pattern['product_tags']) {
                $data['product_tags_template'] = $pattern['product_tags'];
            } else{
                $data['product_tags_template'] = '[product_name], [model_name], [manufacturer_name], [categories_names]';
            }
            $pattern_available = '[product_name], [model_name], [manufacturer_name], [categories_names]';
            $data['help_product_tags'] = sprintf($this->language->get('dymanic_text'), $pattern_available);
            $data['text_tags'] = sprintf($this->language->get('dynamic_main_description'), 'Product', $this->language->get('text_tags'),$this->language->get('text_set_pattern'));
            

            if (isset($this->request->post['product_image_template'])) {
                $data['product_image_template'] = $this->request->post['product_image_template'];
            } elseif(isset($pattern['product_image_name']) && $pattern['product_image_name']) {
                $data['product_image_template'] = $pattern['product_image_name'];
            } else{
                $data['product_image_template'] = '[product_name]-[model_name]-[categories_names]';
            }
            $pattern_available = '[product_name]-[model_name]-[categories_names]';
            $data['help_product_image_description'] = sprintf($this->language->get('dymanic_text_image'), $pattern_available);
            $data['text_image_name'] = sprintf($this->language->get('dynamic_main_description'), 'Product', $this->language->get('text_image_name'),$this->language->get('text_set_pattern'));

            // Categories

            if (isset($this->request->post['categories_url_template'])) {
                $data['categories_url_template'] = $this->request->post['categories_url_template'];
            } elseif(isset($pattern['category_url_keyword']) && $pattern['category_url_keyword']) {
                $data['categories_url_template'] = $pattern['category_url_keyword'];
            } else{
                $data['categories_url_template'] = '[category_name], [meta_title]';
            }
            $pattern_available = '[category_name], [meta_title]';
            $data['help_category_seo_description'] =  sprintf($this->language->get('dymanic_text_image'), $pattern_available,'[category_name], .html');
            $data['text_category_url_keyword'] = sprintf($this->language->get('dynamic_main_description'), 'Category', $data['text_url_keyword'],$this->language->get('text_set_pattern'));

            if (isset($this->request->post['categories_title_template'])) {
                $data['categories_title_template'] = $this->request->post['categories_title_template'];
            } elseif(isset($pattern['category_title']) && $pattern['category_title']) {
                $data['categories_title_template'] = $pattern['category_title'];
            } else{
                $data['categories_title_template'] = '[category_name], [category_description]';
            }
            $pattern_available = '[category_name], [category_description]';
            $data['help_category_title'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[category_name], Nerdherd Store');
            $data['text_category_title'] = sprintf($this->language->get('dynamic_main_description'), 'Category', $this->language->get('text_title'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['categories_keyword_template'])) {
                $data['categories_keyword_template'] = $this->request->post['categories_keyword_template'];
            } elseif(isset($pattern['category_keyword']) && $pattern['category_keyword']) {
                $data['categories_keyword_template'] = $pattern['category_keyword'];
            } else{
                $data['categories_keyword_template'] = '[category_name], [category_description]';
            }
            $pattern_available = '[category_name], [category_description]';
            $data['help_category_meta_keyword'] = sprintf($this->language->get('dymanic_text_image'), $pattern_available,'[category_name], Nerdherd Store');
            $data['text_category_meta_keywords'] = sprintf($this->language->get('dynamic_main_description'), 'Category', $this->language->get('text_meta_keywords'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['category_description_template'])) {
                $data['category_description_template'] = $this->request->post['category_description_template'];
            } elseif(isset($pattern['category_meta_description']) && $pattern['category_meta_description']) {
                $data['category_description_template'] = $pattern['category_meta_description'];
            } else{
                $data['category_description_template'] = '[category_name], [category_description]';
            }
            $pattern_available = '[category_name], [category_description]';
            $data['help_category_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[category_description]');
              $data['text_category_meta_description'] = sprintf($this->language->get('dynamic_main_description'), 'Category', $this->language->get('text_meta_description'),$this->language->get('text_set_pattern'));
            
            //Manufacturers
            if (isset($this->request->post['manufacturers_url_template'])) {
                $data['manufacturers_url_template'] = $this->request->post['manufacturers_url_template'];
            } elseif(isset($pattern['manufacturer_url_keyword']) && $pattern['manufacturer_url_keyword']) {
                $data['manufacturers_url_template'] = $pattern['manufacturer_url_keyword'];
            } else{
                $data['manufacturers_url_template'] = '[manufacturer_name]';
            }
            $pattern_available = '[manufacturer_name]';
            $data['help_manufacturer_seo_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[manufacturer_name], .html');
            $data['text_manufacturer_url_keyword'] = sprintf($this->language->get('dynamic_main_description'), 'Manufacturer', $data['text_url_keyword'],$this->language->get('text_set_pattern'));
            
             if (isset($this->request->post['manufacturer_title_template'])) {
                $data['manufacturer_title_template'] = $this->request->post['manufacturer_title_template'];
            } elseif(isset($pattern['manufacturer_title']) && $pattern['manufacturer_title']) {
                $data['manufacturer_title_template'] = $pattern['manufacturer_title'];
            } else{
                $data['manufacturer_title_template'] = '[manufacturer_name]';
            }
            $pattern_available = '[manufacturer_name]';
            $data['help_manufacturer_title'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[manufacturer_name], Best Brand');
            $data['text_manufacturer_title'] = sprintf($this->language->get('dynamic_main_description'), 'Manufacturer', $this->language->get('text_title'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['manufacturer_keyword_template'])) {
                $data['manufacturer_keyword_template'] = $this->request->post['manufacturer_keyword_template'];
            } elseif(isset($pattern['manufacturer_keyword']) && $pattern['manufacturer_keyword']) {
                $data['manufacturer_keyword_template'] = $pattern['manufacturer_keyword'];
            } else{
                $data['manufacturer_keyword_template'] = '[manufacturer_name]';
            }
            $pattern_available = '[manufacturer_name]';
            $data['help_manufacturer_meta_keyword'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[manufacturer_name]');
            $data['text_manufacturer_meta_keywords'] = sprintf($this->language->get('dynamic_main_description'), 'Manufacturer', $this->language->get('text_meta_keywords'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['manufacturer_description_template'])) {
                $data['manufacturer_description_template'] = $this->request->post['manufacturer_description_template'];
            } elseif(isset($pattern['manufacturer_meta_description']) && $pattern['manufacturer_meta_description']) {
                $data['manufacturer_description_template'] = $pattern['manufacturer_meta_description'];
            } else{
                $data['manufacturer_description_template'] = '[manufacturer_name]';
            }
            $pattern_available = '[manufacturer_name]';
            $data['help_manufacturer_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[manufacturer_name]');
            $data['text_manufacturer_meta_description'] = sprintf($this->language->get('dynamic_main_description'), 'Manufacturer', $this->language->get('text_meta_description'),$this->language->get('text_set_pattern'));

            // Information Pages

            if (isset($this->request->post['information_pages_template'])) {
                $data['information_pages_template'] = $this->request->post['information_pages_template'];
            } elseif(isset($pattern['information_page_url_keyword']) && $pattern['information_page_url_keyword']) {
                $data['information_pages_template'] = $pattern['information_page_url_keyword'];
            }  else{
                $data['information_pages_template'] = '[information_page_title]';
            }
            $pattern_available = '[information_page_title]';
            $data['help_information_seo_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[information_page_title]');
            $data['text_information_url_keyword'] = sprintf($this->language->get('dynamic_main_description'), 'Information', $data['text_url_keyword'],$this->language->get('text_set_pattern'));

            if (isset($this->request->post['information_pages_title_template'])) {
                $data['information_pages_title_template'] = $this->request->post['information_pages_title_template'];
            } elseif(isset($pattern['information_pages_title']) && $pattern['information_pages_title']) {
                $data['information_pages_title_template'] = $pattern['information_pages_title'];
            }  else{
                $data['information_pages_title_template'] = '[information_page_title], [information_page_description]';
            }
            $pattern_available = '[information_page_title], [information_page_description]';
            $data['help_information_title'] =  sprintf($this->language->get('dymanic_text'), $pattern_available,'[information_page_title], Nerdherd Store');
            $data['text_information_title'] = sprintf($this->language->get('dynamic_main_description'), 'Information', $this->language->get('text_title'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['information_keyword_template'])) {
                $data['information_keyword_template'] = $this->request->post['information_keyword_template'];
            } elseif(isset($pattern['information_keyword']) && $pattern['information_keyword']) {
                $data['information_keyword_template'] = $pattern['information_keyword'];
            } else{
                $data['information_keyword_template'] = '[information_page_title]';
            }
            $pattern_available = '[information_page_title]';
            $data['help_information_meta_keyword'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[information_page_title]');
            $data['text_information_meta_keywords'] = sprintf($this->language->get('dynamic_main_description'), 'Information', $this->language->get('text_meta_keywords'),$this->language->get('text_set_pattern'));

            if (isset($this->request->post['information_description_template'])) {
                $data['information_description_template'] = $this->request->post['information_description_template'];
            } elseif(isset($pattern['information_meta_description']) && $pattern['information_meta_description']) {
                $data['information_description_template'] = $pattern['information_meta_description'];
            } else{
                $data['information_description_template'] = '[information_page_description]';
            }
            $pattern_available = '[information_page_title], [information_page_description]';
            $data['help_information_description'] = sprintf($this->language->get('dymanic_text'), $pattern_available,'[information_page_description]');
            $data['text_information_meta_description'] = sprintf($this->language->get('dynamic_main_description'), 'Information', $this->language->get('text_meta_description'),$this->language->get('text_set_pattern'));

            $data['action'] = $this->url->link("catalog/seo/autogenerate","token=" . $this->session->data['token'],"SSL");
            $data['cancel'] = $this->url->link("common/home","token=" . $this->session->data['token'],"SSL");
            $data['heading_title'] = $this->language->get('heading_title');
            $this->load->model('catalog/seoReport');
            if ($this->request->server['HTTPS']) {
                $data['catalog'] = HTTPS_CATALOG;
            } else {
                $data['catalog'] = HTTPS_CATALOG;
            }
            $data['productreport'] = $this->model_catalog_seoReport->getreport1();
            $data['catreport'] = $this->model_catalog_seoReport->getreport2();
            $data['inforeport'] = $this->model_catalog_seoReport->getreport3();
            $data['manreport'] = $this->model_catalog_seoReport->getreport4();
            $data['genreport'] = $this->model_catalog_seoReport->getreport5();
            
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('catalog/seo_autogenerate.tpl', $data));
    }

    protected function multiPagination($pagination, $config_limit_admin, $total, $page, $url){
        $temp = $pagination;
        $pagination = $pagination;
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $config_limit_admin;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link("catalog/seo/customize","token=" . $this->session->data['token'] . $url . '&page={page}',"SSL");

        return $pagination->render();
        
    }

    protected function validate($data) {

        if (!$this->user->hasPermission('modify', 'catalog/seo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $members = array('custom_url_store','product','category','manufacturer','information');
        $mapping_tab = array(
            'custom_url_store' => 'General',
            'product' => 'Products',
            'category' => 'Categories',
            'manufacturer' => 'Manufacturers',
            'information' => 'Information Pages');
        
        foreach($members as $member){
            if(isset($data[$member]) && $member != 'custom_url_store'){
                if(isset($data[$member][$member.'_description'])){
                    foreach($data[$member][$member.'_description'] as $id => $language){
                        foreach ($language as $language_id => $value) {
                            $name = 'name';
                            if($member=='information'){
                                $name = 'title';
                            }
                            if(isset($value[$name])){
                                if ((strlen(utf8_decode($value[$name])) < 3)) {
                                    // $this->error['title']['name_'.$member.'_'.$id.'_'.$language_id] = $this->language->get('error_name');
                                }
                            }
                        }
                    }
                }
                if(isset($data[$member]['keyword'])){
                   foreach ($data[$member]['keyword'] as $key => $value) {
                      
                       foreach ($value as $key1 => $value1) {
                            $keyword_validate = $this->validate->keyword_validate(array($key=>$value1),$member.'_id');
                            if($keyword_validate){
                                if($keyword_validate['existing_keyword']){
                                    $data['existing_keyword'] = $keyword_validate['existing_keyword'];
                                    $this->error['already_exists'] = sprintf($this->language->get($keyword_validate['error']),$data['existing_keyword'],$mapping_tab[$member]);
                                }
                                $data['tab'] = $data['tab'];
                            } 
                        }
                   }
                }
            }elseif(isset($data[$member]) && $member == 'custom_url_store'){
                $new_member_id = array();
                if($data[$member]){
                    foreach ($data[$member] as $key => $value) {
                        if($value['id']['query']  && $value['id']['query'] != 'common/home') {
                            if(trim($value['id']['query']) == 'product/category'){
                                $this->error['warning'] = $this->language->get('error_restrict');
                            } elseif(trim($value['id']['keyword']) == '') {
                                $this->error['warning'] = $this->language->get('error_empty');
                            }
                            $new_member_id[] = array(
                                'query' => $value['id']['query'],
                                'keyword' => $value['id']['keyword']
                            );
                        }
                    }
                    if($new_member_id) {
                        $keyword_validate = $this->validate->keyword_validate($new_member_id,$member);
                        if($keyword_validate){
                            if($keyword_validate['existing_keyword']){
                                $data['existing_keyword'] = $keyword_validate['existing_keyword'];
                                $this->error['already_exists'] = sprintf($this->language->get($keyword_validate['error']),$data['existing_keyword'],$mapping_tab[$member]);
                            }
                            $data['tab'] = $data['tab'];
                        }
                    }
                }
            }
        }
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function validateAutogenerate() {
        if (!$this->user->hasPermission('modify', 'catalog/seo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function multisetting() {
        $this->load->model('setting/setting');;
        $array = array('config_multilang_on'=>$this->request->get['config_multilang_on']);
        $this->model_setting_setting->editSetting('multilang', $array);
        $results['success'] = 'Settings Saved';
        $this->response->setOutput(json_encode($results));
    }
}
?>