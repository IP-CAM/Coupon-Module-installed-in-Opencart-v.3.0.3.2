<?php
class ControllerExtensionIngeCouponModuleCoupon extends Controller {


    private $id = 'inge_coupon_module';
    private $error = array();
    private $setting = '';
    private $sub_versions = array('lite', 'light', 'free');
    private $config_file = '';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('extension/module/inge_coupon_module');
        $this->load->model('extension/ws_opencart_patch/url');
        $this->load->model('extension/ws_opencart_patch/user');

        $this->config_file = $this->model_extension_module_inge_coupon_module->getConfigFile($this->id, $this->sub_versions);
        $this->setting = $this->model_extension_module_inge_coupon_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);

    }

    public function index() {

        $this->load->model('extension/inge_coupon_module/coupon');

        $this->load->language('extension/inge_coupon_module/coupon');
        $this->document->setTitle($this->language->get('heading_title'));

//        $this->model_extension_module_inge_coupon_module->updateTables();
        $this->getList();
    }

    public function add() {
        $this->load->language('extension/inge_coupon_module/coupon');
        $this->load->model('extension/inge_coupon_module/coupon');
        //$this->document->addStyle('view/javascript/summernote/summernote.css');
        //$this->document->addScript('view/javascript/summernote/summernote.js');
        $this->document->setTitle($this->language->get('heading_title'));
/* 
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_extension_inge_coupon_module_coupon->addNews($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
        } */

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_marketing_coupon->addCoupon($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
        }
        
        $this->getForm();
    }

    public function edit() {

        $this->load->language('extension/inge_coupon_module/coupon');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/inge_coupon_module/coupon');
        $this->document->addStyle('view/javascript/summernote/summernote.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_extension_inge_coupon_module_coupon->editNews($this->request->get['news_id'], $this->request->post);


            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/inge_coupon_module/coupon');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/inge_coupon_module/coupon');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $news_id) {
                $this->model_extension_inge_coupon_module_coupon->deleteNews($news_id);
            }

            $url = $this->getUrl();

            $this->response->redirect($this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
        }

        $this->getList();
    }

//    public function copy() {
//        $this->load->language('extension/inge_coupon_module/coupon');
//
//
//        $this->document->setTitle($this->language->get('heading_title'));
//
//        $this->load->model('extension/inge_coupon_module/coupon');
//
//        if (isset($this->request->post['selected']) && $this->validateCopyPost()) {
//
//            foreach ($this->request->post['selected'] as $news_id) {
//                $this->model_extension_inge_coupon_module_post->copyPost($news_id);
//            }
//
//            $this->session->data['success'] = $this->language->get('text_success');
//
//            $url = $this->getUrl();
//
//            $this->response->redirect($this->model_extension_news_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
//        }
//
//        $this->getList();
//    }

/*     protected function getList() {

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_post_status'])) {
            $filter_post_status = $this->request->get['filter_post_status'];
        } else {
            $filter_post_status = 0;
        }

        if (isset($this->request->get['filter_now_status'])) {
            $filter_now_status = $this->request->get['filter_now_status'];
        } else {
            $filter_now_status = 0;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'wncd.title';
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

// $url = $this->getUrl();
        $url    =   '';

        if  (isset($this->request->get['sort']))    {
            $url    .=  '&sort='    .   $this->request->get['sort'];
        }

        if  (isset($this->request->get['order']))   {
            $url    .=  '&order='   .   $this->request->get['order'];
        }

        if  (isset($this->request->get['page']))    {
            $url    .=  '&page='    .   $this->request->get['page'];
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('common/dashboard')
            );

//        $data['breadcrumbs'][]  =   array(
//            'text'  =>  $this->language->get('text_blog_module'),
//            'href'  =>  $this->model_extension_ws_opencart_patch_url->link('extension/module/inge_coupon_module')
//            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon')
            );

        $data['add'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/add', $url);
        $data['copy'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/copy', $url);
        $data['delete'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/delete', $url);

        $data['posts'] = array();
        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_post_status' => $filter_post_status,
            'filter_now_status' => $filter_now_status,
            'sort' => $sort,
            'order' => $order,
            'limit' => $this->config->get('config_limit_admin'),
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
            );

        $this->load->model('tool/image');
        $this->load->model('extension/inge_coupon_module/category');

        $category_total = $this->model_extension_inge_coupon_module_category->getCategories($filter_data, $this->config->get('config_language_id'));

        $results = $this->model_extension_inge_coupon_module_coupon->getTotalNews($filter_data, $this->config->get('config_language_id'));

        foreach ($results as $result) {
            foreach ($category_total as $category){
                if($category['news_category_id'] == $result['category_id']){
                    $data['all_news'][] = array(
                        'news_id' => $result['news_id'],
                        'category_id' => $result['category_id'],
                        'post_type' => $result['post_type'],
                        'post_format' => $result['post_format'],
                        'user_id' => $result['user_id'],
                        'post_status' => $result['post_status'],
                        'comment_status' => $result['comment_status'],
                        'is_top' => $result['is_top'],
                        'is_delete' => $result['is_delete'],
                        'recommended' => $result['recommended'],
                        'post_hits' => $result['post_hits'],
                        'post_like' => $result['post_like'],
                        'comment_count' => $result['comment_count'],
                        'date_added' => $result['date_added'],
                        'date_modified' => $result['date_modified'],
                        'date_published' => $result['date_published'],
                        'date_delete' => $result['date_delete'],
                        'post_title' => $result['post_title'],
                        'post_keywords' => $result['post_keywords'],
                        'post_excerpt' => $result['post_excerpt'],
                        'post_source' => $result['post_source'],
                        'post_content' => $result['post_content'],
                        'sort_order' => $result['sort_order'],
                        'category_msg' => $category,
                        'edit' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/edit','&news_id=' . $result['news_id'] . $url)
                    );
                }
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_short_description'] = $this->language->get('column_short_description');
        $data['column_tag'] = $this->language->get('column_tag');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_categores'] = $this->language->get('column_categores');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_published'] = $this->language->get('column_date_published');
        $data['column_date_modified'] = $this->language->get('column_date_modified');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_short_description'] = $this->language->get('entry_short_description');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_published'] = $this->language->get('entry_date_published');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');
        $data['entry_tag'] = $this->language->get('entry_tag');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');

        $data['without_categorty'] = $this->language->get('without_categorty');

        $data['token'] = $this->model_extension_ws_opencart_patch_user->getToken();

        $data['news_search'] = $this->model_extension_ws_opencart_patch_url->ajax('extension/inge_coupon_module/coupon');
        $data['news_autocomplete'] = $this->model_extension_ws_opencart_patch_url->ajax('extension/inge_coupon_module/coupon/autocomplete');
        $data['category_autocomplete'] = $this->model_extension_ws_opencart_patch_url->ajax('extension/inge_coupon_module/category/autocomplete');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = $this->getUrl();

        $this->load->model('extension/inge_coupon_module/category');
        $data['sort_title'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=pd.title' . $url);
        $data['sort_status'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=p.status' . $url);
// $url = $this->getUrl();

        $pagination = new Pagination();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/inge_coupon_module/coupon', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

//        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_post_status'] = $filter_post_status;
        $data['filter_now_status'] = $filter_now_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/inge_coupon_module/news_list', $data));
    } */

    protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('common/dashboard')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon')
            );

        $data['add'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/add', $url);
        $data['delete'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/delete', $url);


		$data['coupons'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$coupon_total = $this->model_extension_inge_coupon_module_coupon->getTotalCoupons();

		$results = $this->model_extension_inge_coupon_module_coupon->getCoupons($filter_data);

		foreach ($results as $result) {
			$data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/edit','&coupon_id=' . $result['coupon_id'] . $url)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

        $data['sort_name'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=name' . $url);
        $data['sort_code'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=code' . $url);
        $data['sort_discount'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=discount' . $url);
        $data['sort_date_start'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=date_start' . $url);
        $data['sort_date_end'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=date_end' . $url);
        $data['sort_status'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon','&sort=status' . $url);
        
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/inge_coupon_module/coupon', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($coupon_total - $this->config->get('config_limit_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/inge_coupon_module/news_list', $data));
        
    }
    
/*     protected function getForm() {

        $this->document->addScript('../admin/view/javascript/ws_bootstrap_tagsinput/bootstrap-tagsinput.js');
        $this->document->addStyle('../admin/view/javascript/ws_bootstrap_tagsinput/bootstrap-tagsinput.css');

        if(VERSION >= '2.2.0.0'){
            if(file_exists(DIR_APPLICATION.'view/javascript/summernote/opencart.js')){
                $this->document->addScript('view/javascript/summernote/opencart.js');
            }
            $data['store_2302'] = true;
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_form'] = !isset($this->request->get['news_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['text_youtube_url'] = $this->language->get('text_youtube_url');
        $data['text_youtube_title'] = $this->language->get('text_youtube_title');
        $data['text_youtube_width'] = $this->language->get('text_youtube_width');
        $data['text_youtube_height'] = $this->language->get('text_youtube_height');
        $data['text_youtube_sort_order'] = $this->language->get('text_youtube_sort_order');
        $data['text_youtube_action'] = $this->language->get('text_youtube_action');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_short_description'] = $this->language->get('entry_short_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_option_points'] = $this->language->get('entry_option_points');
        $data['entry_subtract'] = $this->language->get('entry_subtract');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_post'] = $this->language->get('entry_post');
        $data['entry_date_published'] = $this->language->get('entry_date_published');
        $data['entry_review_display'] = $this->language->get('entry_review_display');
        $data['entry_images_review'] = $this->language->get('entry_images_review');
        $data['entry_author'] = $this->language->get('entry_author');
        
        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_tag'] = $this->language->get('help_tag');
        $data['help_date_published'] = $this->language->get('help_date_published');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');
        $data['button_option_add'] = $this->language->get('button_option_add');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_special_add'] = $this->language->get('button_special_add');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_related'] = $this->language->get('tab_related');
        $data['tab_youtube'] = $this->language->get('tab_youtube');
        $data['tab_attribute'] = $this->language->get('tab_attribute');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_recurring'] = $this->language->get('tab_recurring');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_design'] = $this->language->get('tab_design');
        $data['tab_openbay'] = $this->language->get('tab_openbay');


        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');

        $data['style_short_description_display'] = $this->setting['post']['style_short_description_display'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['news_category'])) {
            $data['error_news_category'] = $this->error['news_category'];
        } else {
            $data['error_news_category'] = array();
        }

        $url = $this->getUrl();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('common/dashboard')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon', $url)
            );

        if (!isset($this->request->get['news_id'])) {
            $data['action'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/add', $url);
        } else {
            $data['action'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/edit', '&news_id=' . $this->request->get['news_id'] . $url);
        }

        $data['cancel'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon', $url);

        if (isset($this->request->get['news_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

            $news_info = $this->model_extension_inge_coupon_module_coupon->getNews($this->request->get['news_id']);
            $data['news_info'] = $news_info;
        }

        $data['token'] = $this->model_extension_ws_opencart_patch_user->getToken();

        $data['category_autocomplete'] = $this->model_extension_ws_opencart_patch_url->ajax('extension/inge_coupon_module/category/autocomplete');
        $data['product_autocomplete'] = $this->model_extension_ws_opencart_patch_url->ajax('catalog/product/autocomplete');
        $data['news_autocomplete'] = $this->model_extension_ws_opencart_patch_url->ajax('extension/inge_coupon_module/coupon/autocomplete');

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();
        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }

        if (isset($this->request->post['news_description'])) {
            $data['news_description'] = $this->request->post['news_description'];
        } elseif (isset($this->request->get['news_id'])) {

            $data['news_description'] = $this->model_extension_inge_coupon_module_coupon->getNewsDescription($this->request->get['news_id'], $this->config->get('config_language_id'));
        } else {
            $data['news_description'] = array();
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($news_info['sort_order'])) {
            $data['sort_order'] = $news_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if (isset($this->request->post['post_status'])) {
            $data['post_status'] = $this->request->post['post_status'];
        } elseif (!empty($news_info)) {
            $data['post_status'] = $news_info['post_status'];
        } else {
            $data['post_status'] = true;
        }

// Categories
        $this->load->model('extension/inge_coupon_module/category');


        $categories = $this->model_extension_inge_coupon_module_coupon->getNewsCategories($this->config->get('config_language_id'));

        $data['news_categories'] = $categories;
        foreach($data['news_categories'] as &$category){
            $category['deep'] = str_repeat('----', $category['deep']);
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
//        var_dump($data['news_categories']);exit;
        $this->response->setOutput($this->load->view('extension/inge_coupon_module/news_form', $data));
    }
 */
protected function getForm() {
    $data['text_form'] = !isset($this->request->get['coupon_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

    $data['user_token'] = $this->session->data['user_token'];

    if (isset($this->request->get['coupon_id'])) {
        $data['coupon_id'] = $this->request->get['coupon_id'];
    } else {
        $data['coupon_id'] = 0;
    }

    if (isset($this->error['warning'])) {
        $data['error_warning'] = $this->error['warning'];
    } else {
        $data['error_warning'] = '';
    }

    if (isset($this->error['name'])) {
        $data['error_name'] = $this->error['name'];
    } else {
        $data['error_name'] = '';
    }

    if (isset($this->error['code'])) {
        $data['error_code'] = $this->error['code'];
    } else {
        $data['error_code'] = '';
    }

    if (isset($this->error['date_start'])) {
        $data['error_date_start'] = $this->error['date_start'];
    } else {
        $data['error_date_start'] = '';
    }

    if (isset($this->error['date_end'])) {
        $data['error_date_end'] = $this->error['date_end'];
    } else {
        $data['error_date_end'] = '';
    }

    $url = '';

    if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
    }

    if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->model_extension_ws_opencart_patch_url->link('common/dashboard')
        );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon', $url)
        );

    if (!isset($this->request->get['coupon_id'])) {
        $data['action'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/add', $url);
    } else {
        $data['action'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/edit', '&news_id=' . $this->request->get['news_id'] . $url);
    }

    $data['cancel'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon', $url);
    
    if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
        $coupon_info = $this->model_extension_inge_coupon_module_coupon->getCoupon($this->request->get['coupon_id']);
    }

    if (isset($this->request->post['name'])) {
        $data['name'] = $this->request->post['name'];
    } elseif (!empty($coupon_info)) {
        $data['name'] = $coupon_info['name'];
    } else {
        $data['name'] = '';
    }

    if (isset($this->request->post['code'])) {
        $data['code'] = $this->request->post['code'];
    } elseif (!empty($coupon_info)) {
        $data['code'] = $coupon_info['code'];
    } else {
        $data['code'] = '';
    }

    if (isset($this->request->post['type'])) {
        $data['type'] = $this->request->post['type'];
    } elseif (!empty($coupon_info)) {
        $data['type'] = $coupon_info['type'];
    } else {
        $data['type'] = '';
    }

    if (isset($this->request->post['discount'])) {
        $data['discount'] = $this->request->post['discount'];
    } elseif (!empty($coupon_info)) {
        $data['discount'] = $coupon_info['discount'];
    } else {
        $data['discount'] = '';
    }

    if (isset($this->request->post['logged'])) {
        $data['logged'] = $this->request->post['logged'];
    } elseif (!empty($coupon_info)) {
        $data['logged'] = $coupon_info['logged'];
    } else {
        $data['logged'] = '';
    }

    if (isset($this->request->post['shipping'])) {
        $data['shipping'] = $this->request->post['shipping'];
    } elseif (!empty($coupon_info)) {
        $data['shipping'] = $coupon_info['shipping'];
    } else {
        $data['shipping'] = '';
    }

    if (isset($this->request->post['total'])) {
        $data['total'] = $this->request->post['total'];
    } elseif (!empty($coupon_info)) {
        $data['total'] = $coupon_info['total'];
    } else {
        $data['total'] = '';
    }

    if (isset($this->request->post['coupon_product'])) {
        $products = $this->request->post['coupon_product'];
    } elseif (isset($this->request->get['coupon_id'])) {
        $products = $this->model_marketing_coupon->getCouponProducts($this->request->get['coupon_id']);
    } else {
        $products = array();
    }

    $this->load->model('catalog/product');

    $data['coupon_product'] = array();

    foreach ($products as $product_id) {
        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $data['coupon_product'][] = array(
                'product_id' => $product_info['product_id'],
                'name'       => $product_info['name']
            );
        }
    }

    if (isset($this->request->post['coupon_category'])) {
        $categories = $this->request->post['coupon_category'];
    } elseif (isset($this->request->get['coupon_id'])) {
        $categories = $this->model_marketing_coupon->getCouponCategories($this->request->get['coupon_id']);
    } else {
        $categories = array();
    }

    $this->load->model('catalog/category');

    $data['coupon_category'] = array();

    foreach ($categories as $category_id) {
        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $data['coupon_category'][] = array(
                'category_id' => $category_info['category_id'],
                'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
            );
        }
    }

    if (isset($this->request->post['date_start'])) {
        $data['date_start'] = $this->request->post['date_start'];
    } elseif (!empty($coupon_info)) {
        $data['date_start'] = ($coupon_info['date_start'] != '0000-00-00' ? $coupon_info['date_start'] : '');
    } else {
        $data['date_start'] = date('Y-m-d', time());
    }

    if (isset($this->request->post['date_end'])) {
        $data['date_end'] = $this->request->post['date_end'];
    } elseif (!empty($coupon_info)) {
        $data['date_end'] = ($coupon_info['date_end'] != '0000-00-00' ? $coupon_info['date_end'] : '');
    } else {
        $data['date_end'] = date('Y-m-d', strtotime('+1 month'));
    }

    if (isset($this->request->post['uses_total'])) {
        $data['uses_total'] = $this->request->post['uses_total'];
    } elseif (!empty($coupon_info)) {
        $data['uses_total'] = $coupon_info['uses_total'];
    } else {
        $data['uses_total'] = 1;
    }

    if (isset($this->request->post['uses_customer'])) {
        $data['uses_customer'] = $this->request->post['uses_customer'];
    } elseif (!empty($coupon_info)) {
        $data['uses_customer'] = $coupon_info['uses_customer'];
    } else {
        $data['uses_customer'] = 1;
    }

    if (isset($this->request->post['status'])) {
        $data['status'] = $this->request->post['status'];
    } elseif (!empty($coupon_info)) {
        $data['status'] = $coupon_info['status'];
    } else {
        $data['status'] = true;
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/inge_coupon_module/news_form', $data));
}

    protected function validateForm() {

        if (!$this->user->hasPermission('modify', 'extension/inge_coupon_module/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['news_description'] as $language_id => $value) {

            if (utf8_strlen($value['post_title']) > 255) {
                $this->error['post_title'][$language_id] = $this->language->get('error_title');
            }

        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }
        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/inge_coupon_module/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopyPost() {
        if (!$this->user->hasPermission('modify', 'extension/inge_coupon_module/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('extension/inge_coupon_module/author');

        if (!$this->model_extension_inge_coupon_module_author->hasPermission('add_posts')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_tag'])) {
            $this->load->model('extension/inge_coupon_module/coupon');
            if (isset($this->request->get['filter_title'])) {
                $filter_title = $this->request->get['filter_title'];
            } else {
                $filter_title = '';
            }

            if (isset($this->request->get['filter_tag'])) {
                $filter_tag = $this->request->get['filter_tag'];
            } else {
                $filter_tag = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 10;
            }

            $filter_data = array(
                'filter_title' => $filter_title,
                'filter_tag' => $filter_tag,
                'start' => 0,
                'limit' => $limit
                );

            $results = $this->model_extension_inge_coupon_module_post->getPosts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'news_id' => $result['news_id'],
                    'title' => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
                    'tag' => strip_tags(html_entity_decode($result['tag'], ENT_QUOTES, 'UTF-8')),
                    );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getUrl() {

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_tag'])) {
            $url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['filter_date_published'])) {
            $url .= '&filter_date_published=' . $this->request->get['filter_date_published'];
        }

        if (isset($this->request->get['order']) && $this->request->get['order'] == 'DESC') {
            if($this->request->get['route'] == 'extension/inge_coupon_module/coupon'){
                $url .= '&order=ASC';
            }else{
                $url .= '&order=DESC';
            }
        } else {
            if($this->request->get['route'] == 'extension/inge_coupon_module/coupon'){
                $url .= '&order=DESC';
            }else{
                $url .= '&order=ASC';
            }
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        return $url;
    }
}
