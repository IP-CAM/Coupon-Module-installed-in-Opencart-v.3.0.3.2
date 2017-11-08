<?php

class ModelExtensionModuleIngeCouponModule extends Model
{
    public function getGroupId()
    {
        if (VERSION == '2.0.0.0') {
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->user->getId() . "'");
            $user_group_id = (int)$user_query->row['user_group_id'];
        } else {
            $user_group_id = $this->user->getGroupId();
        }

        return $user_group_id;
    }

    public function createTables()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "inge_coupon` (
          `news_id` int(11) NOT NULL AUTO_INCREMENT,
          `category_id` int(11) NOT NULL COMMENT '分类id',
          `post_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:文章 2:页面',
          `post_format` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:html 2:md',
          `user_id` int(11) NOT NULL COMMENT '发布作者的id',
          `post_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:已发布 0:未发布',
          `comment_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 允许评论 0: 不允许',
          `is_top` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否置顶  1：置顶 0: 不置顶',
          `is_delete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否删除 1:未删除 0:删除',
          `recommended` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否推荐 1:推荐 0:不推荐',
          `post_hits` int(11) NOT NULL DEFAULT '0' COMMENT '查看数',
          `post_like` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
          `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
          `date_added` datetime NOT NULL COMMENT '创建时间',
          `date_modified` datetime DEFAULT NULL COMMENT '更新时间',
          `date_published` datetime DEFAULT NULL COMMENT '发布时间',
          `date_delete` datetime DEFAULT NULL COMMENT '删除时间',
          `post_source` varchar(255) DEFAULT NULL COMMENT '转载的文章来源',
          `sort_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
          PRIMARY KEY (`news_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "inge_coupon_customer` (
          `coupon_customer_id` int(11) NOT NULL AUTO_INCREMENT,
          `coupon_id` int(11) NOT NULL,
          `customer_id` int(11) NOT NULL,
          PRIMARY KEY (`coupon_customer_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          ");
        
    }

    public function getConfigFile($id, $sub_versions)
    {

        if (isset($this->request->post['config'])) {
            return $this->request->post['config'];
        }

        $setting = $this->config->get($id . '_setting');

        if (isset($setting['config'])) {
            return $setting['config'];
        }

        $full = DIR_SYSTEM . 'config/' . $id . '.php';
        if (file_exists($full)) {
            return $id;
        }

        foreach ($sub_versions as $lite) {
            if (file_exists(DIR_SYSTEM . 'config/' . $id . '_' . $lite . '.php')) {
                return $id . '_' . $lite;
            }
        }

        return false;
    }

    public function getConfigData($id, $config_key, $store_id, $config_file = false) {
        if (!$config_file) {
            $config_file = $this->config_file;
        }
        if ($config_file) {
            $this->config->load($config_file);
        }

        $result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

        if (!isset($this->request->post['config'])) {
            $this->load->model('setting/setting');
            if (isset($this->request->post[$config_key])) {
                $setting = $this->request->post;
            } elseif ($this->model_setting_setting->getSetting($id, $store_id)) {
                $setting = $this->model_setting_setting->getSetting($id, $store_id);
            }
            if (isset($setting[$config_key])) {
                foreach ($setting[$config_key] as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}