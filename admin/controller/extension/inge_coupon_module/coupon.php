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
			$this->model_extension_inge_coupon_module_coupon->addCoupon($this->request->post);

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

            $this->model_extension_inge_coupon_module_coupon->editCoupon($this->request->get['coupon_id'], $this->request->post);


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
            foreach ($this->request->post['selected'] as $coupon_id) {
                $this->model_extension_inge_coupon_module_coupon->deleteCoupon($coupon_id);
            }

            $url = $this->getUrl();

            $this->response->redirect($this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_code'])) {
            $filter_code = $this->request->get['filter_code'];
        } else {
            $filter_code = 0;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = 0;
        }

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
            'filter_name' => $filter_name,
            'filter_code' => $filter_code,
            'filter_status' => $filter_status,
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

        $this->response->setOutput($this->load->view('extension/inge_coupon_module/coupon_list', $data));
        
    }

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
        $data['action'] = $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon/edit', '&coupon_id=' . $this->request->get['coupon_id'] . $url);
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
        $products = $this->model_extension_inge_coupon_module_coupon->getCouponProducts($this->request->get['coupon_id']);
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
        $categories = $this->model_extension_inge_coupon_module_coupon->getCouponCategories($this->request->get['coupon_id']);
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
    
    //取出优惠券关联客户
    if (isset($this->request->post['coupon_customer'])) {
        $customers = $this->request->post['coupon_customer'];
    } elseif (isset($this->request->get['coupon_id'])) {
        $customers = $this->model_extension_inge_coupon_module_coupon->getCouponCustomers($this->request->get['coupon_id']);
    } else {
        $customers = array();
    }

    $this->load->model('customer/customer');
    
    $data['coupon_customer'] = array();

    foreach ($customers as $customer_id) {
        $customer_info = $this->model_customer_customer->getCustomer($customer_id);

        if ($customer_info) {
            $data['coupon_customer'][] = array(
                'customer_id' => $customer_info['customer_id'],
                'name'        => ($customer_info['firstname'] ? $customer_info['firstname'] . ' ' : '') .  ($customer_info['lastname'] ? $customer_info['lastname']  : '')
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

    $this->response->setOutput($this->load->view('extension/inge_coupon_module/coupon_form', $data));
}
    
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 10)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		$coupon_info = $this->model_extension_inge_coupon_module_coupon->getCouponByCode($this->request->post['code']);

		if ($coupon_info) {
			if (!isset($this->request->get['coupon_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			} elseif ($coupon_info['coupon_id'] != $this->request->get['coupon_id']) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
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
