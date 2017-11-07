<?php

class ModelExtensionIngeCouponModuleNews extends Model {

    public function addNews($data) {

        $user_id = $this->user->getId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "inge_coupon SET `category_id` = " . (int)$data['category_id'] . ", post_type = 1, post_format = 1, user_id = " . (int)$user_id . ", post_status = " . (int)$data['post_status'] . ", comment_status = " . (int)$data['comment_status'] . ", is_top = " . (int)$data['is_top'] . ", is_delete = 1" . ", recommended = " . (int)$data['recommended'] . ", post_hits = " . $data['post_hits'] . ", post_like = " . (int)$data['post_like'] . ", comment_count = 0, date_added = '" . date('Y-m-d H:i:s', time()) . "', date_modified = NULL, date_published = '" . date('Y-m-d H:i:s', time()) . "', date_delete = NULL, post_source = NULL, sort_order = 0");

        $news_id = $this->db->getLastId();

        foreach ($data['news_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "inge_coupon_description "
                . "SET news_id = '" . (int) $news_id
                . "', language_id = '" . (int) $language_id
                . "', post_title = '" . $this->db->escape($value['post_title'])
                . "', post_excerpt = '" . $this->db->escape($value['post_excerpt'])
                . "', post_content = '" . $this->db->escape($value['post_content']) . "'");
        }

        return $news_id;
    }

    public function editNews($news_id, $data) {

        $user_id = $this->user->getId();
        $query = $this->db->query("UPDATE " . DB_PREFIX . "inge_coupon SET `category_id` = " . (int)$data['category_id'] . ", post_type = 1, post_format = 1, user_id = " . (int)$user_id . ", post_status = " . (int)$data['post_status'] . ", comment_status = " . (int)$data['comment_status'] . ", is_top = " . (int)$data['is_top'] . ", is_delete = 1" . ", recommended = " . (int)$data['recommended'] . ", post_hits = " . $data['post_hits'] . ", post_like = " . (int)$data['post_like'] . ", date_modified = '" . date('Y-m-d H:i:s', time()) . "' WHERE news_id = " . $news_id);

        foreach($data['news_description'] as $language_id => $value){
            $this->db->query("REPLACE INTO " . DB_PREFIX . "inge_coupon_description VALUES ('" . (int)$value['news_description_id'] . "','" . (int)$news_id . "','" . (int)$language_id . "','" . $this->db->escape($value['post_title']) . "',' ','" . $this->db->escape($value['post_excerpt']) . "','" . $this->db->escape($value['post_content']) . "')");
        }

        return $query;
    }

    public function copyPost($post_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            WHERE p.post_id = '" . (int) $post_id
            . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = $query->row;

            $data['viewed'] = '0';
            $data['keyword'] = '';
            $data['status'] = '1';


            $data['post_description'] = $this->getPostDescriptions($post_id);
            $data['post_image'] = $this->getPostImages($post_id);
            $data['post_category'] = $this->getPostCategoriesId($post_id);
            $data['related_post'] = $this->getPostRelateds($post_id);
            $data['post_video'] = $this->getPostVideos($post_id);
            $data['post_product'] = $this->getPostProducts($post_id);
            $data['post_store'] = $this->getPostStores($post_id);
            $data['post_layout'] = $this->getPostLayouts($post_id);
            $data['current_author'] = $this->getAuthorByPost($post_id);
            $this->addPost($data);
        }
    }

    public function deleteNews($news_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "inge_coupon SET is_delete = 0 , date_delete = '" . date('Y-m-d H:i:s', time()) . "' WHERE news_id = " . $news_id);
    }

    public function getNews($news_id) {

        $sql = "SELECT wn.*, wnd.news_description_id, wnd.language_id, wnd.post_title , wnd.post_keywords, wnd.post_excerpt, wnd.post_content FROM " . DB_PREFIX . "inge_coupon AS wn LEFT JOIN " . DB_PREFIX . "inge_coupon_description AS wnd ON wn.news_id = wnd.news_id WHERE wn.news_id = '" . (int) $news_id . "'";

        $query  = $this->db->query($sql);
        return $query->row;
    }

    public function getPostSeoUrls($post_id) {
        $post_seo_url_data = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE route='extension/d_blog_module/post' AND query = 'post_id=" . (int)$post_id . "'");

        foreach ($query->rows as $result) {
            $post_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $post_seo_url_data;
    }

    public function getNewsDescription($news_id, $language_id = 1) {
        $post_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "inge_coupon_description WHERE news_id = '" . (int) $news_id . "' AND language_id = " . (int) $language_id);

        foreach ($query->rows as $result) {
            $post_description_data[$result['language_id']] = array(
                'news_description_id' => $result['news_description_id'],
                'news_id' => $result['news_id'],
                'post_title' => $result['post_title'],
                'post_keywords' => $result['post_keywords'],
                'post_excerpt' => $result['post_excerpt'],
                'post_content' => $result['post_content'],
                );
        }
        return $post_description_data;
    }

    public function getPosts($data = array()) {
        var_dump($data);exit;
        $sql = "SELECT p.post_id AS post_id, p.image AS image,
        p.`status` AS `status`, p.date_added AS `date_added`, p.date_modified AS `date_modified`, p.date_published AS `date_published`,
        pd.language_id AS language_id, pd.title AS title, pd.tag AS tag
        FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_product p2p ON (p.post_id = p2p.post_id)
        WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }
		
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }
		
		if (isset($data['filter_tag']) && !is_null($data['filter_tag'])) {
            $sql .= " AND pd.tag  LIKE '" . $this->db->escape($data['filter_tag']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . (int) $data['filter_category'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_date_published'])) {
            $sql .= " AND DATE(p.date_published) = DATE('" . $this->db->escape($data['filter_date_published']) . "')";
        }

        $sql .= " GROUP BY p.post_id";

        $sort_data = array(
            'pd.title',
            'p.status',
            'pd.tag',
            'category',
            'p.date_added',
            'p.date_modified',
            'p.date_published'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if (!isset($data['start']) || $data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getPostsByCategoryId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c
            ON (p.post_id = p2c.post_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND p2c.category_id = '" . (int) $category_id . "' ORDER BY pd.title ASC");

        return $query->rows;
    }

    public function getPostsByProductId($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            LEFT JOIN " . DB_PREFIX . "bm_post_to_product p2p
            ON (p.post_id = p2p.post_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND p2p.product_id = '" . (int) $product_id . "' ORDER BY pd.title ASC");

        return $query->rows;
    }

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)";

        $sql .=" WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getPostImages($post_id) {
        $query = $this->db->query("SELECT post_id, image FROM " . DB_PREFIX . "bm_post WHERE post_id = '" . (int) $post_id . "'");

        return $query->rows;
    }

    public function getNewsCategories($language_id = 1) {
//        $sql = "SELECT category_id FROM " . DB_PREFIX . "inge_coupon WHERE news_id = " . $news_id;
//
//        $query = $this->db->query($sql);
//
//        $category_id = $query->row['category_id'];
//
//        $sql_c = "SELECT wnc.*, wncd.news_category_description_id, wncd.language_id, wncd.title,wncd.description FROM " . DB_PREFIX . "inge_coupon_category AS wnc LEFT JOIN " . DB_PREFIX . "inge_coupon_category_description AS wncd ON wnc.news_category_id = wncd.news_category_id WHERE wnc.news_category_id = " . $category_id . " GROUP BY wncd.news_category_id";
//
//        $query_c = $this->db->query($sql_c);
//
//        return $query_c->row;
        $sql = "SELECT wnc.*, wncd.news_category_description_id ,wncd.title AS title, wncd.description AS description, wncd.language_id AS language_id FROM " . DB_PREFIX . "inge_coupon_category wnc LEFT JOIN " . DB_PREFIX . "inge_coupon_category_description wncd ON wnc.news_category_id = wncd.news_category_id WHERE wncd.language_id = '" . $language_id . "'";

        $query = $this->db->query($sql);

        $results = $query->rows;

        $tree = $this->tree($results);

        return $tree;
    }

    protected function tree($list=[],$news_category_id=0,$deep=0)
    {
        static $tree = [];
        foreach($list as $row)
        {
            if($row['parent_id'] == $news_category_id)
            {
                //是子类,继续寻找该类的子类
                $row['deep'] = $deep;
                $tree[] = $row;
                $this->tree($list,$row['news_category_id'],$deep+1);
            }
        }
        return $tree;
    }

    public function getPostRelateds($post_id) {
        $query = $this->db->query("SELECT pr.post_related_id AS post_id, pd.title AS title
            FROM " . DB_PREFIX . "bm_post_related pr
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pr.post_related_id = pd.post_id)
            WHERE pr.post_id = '" . (int) $post_id . "' AND pd.language_id='".(int)$this->config->get('config_language_id')."'");

        $post_related_data = $query->rows;
        return $post_related_data;
    }

    public function getPostVideos($post_id) {
        $query = $this->db->query("SELECT pv.post_id AS post_id, pv.text AS text, pv.width as width, pv.height as  height, pv.sort_order as  sort_order, pv.video as  video
            FROM " . DB_PREFIX . "bm_post_video pv WHERE pv.post_id = '" . (int) $post_id . "'  ORDER BY pv.sort_order");

        $post_video_data =array();
        if(!empty($query->rows)){
            foreach ($query->rows as $video) {

                $post_video_data[] = array(
                    'post_id' => $video['post_id'],
                    'video' => $video['video'],
                    'text' => unserialize($video['text']),
                    'width' => $video['width'],
                    'sort_order' => $video['sort_order'],
                    'height' => $video['height']
                    );
            }
        }
        return $post_video_data;
    }

    public function getPostProducts($post_id) {

        $query = $this->db->query("SELECT p2p.product_id AS product_id, pd.name AS product_title
            FROM " . DB_PREFIX . "bm_post_to_product p2p
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2p.product_id = pd.product_id)
            WHERE p2p.post_id = '" . (int) $post_id . "' AND pd.language_id='".(int)$this->config->get('config_language_id')."'");

        $post_product_data = $query->rows;
        return $post_product_data;
    }

    public function getPostCategoriesId($post_id) {


        $query = $this->db->query("SELECT p2c.category_id AS category_id
            FROM " . DB_PREFIX . "bm_post_to_category p2c WHERE p2c.post_id = '" . (int) $post_id . "'");

        $post_category_data = array();
        foreach ($query->rows as $result) {
            $post_category_data[] = $result['category_id'];
        }
        return $post_category_data;
    }

    public function getAuthorByPost($post_id){
        $post_info = $this->getPost($post_id);
        if(!empty($post_info)){
            $this->load->model('extension/d_blog_module/author');
            $author_info = $this->model_extension_d_blog_module_author->getAuthorByUserId($post_info['user_id']);
            if(!empty($author_info)){
                return $author_info;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getTotalNews($data = array(), $language_id = 1) {

        $sql = "SELECT wn.*, wnd.news_description_id, wnd.language_id, wnd.post_title, wnd.post_keywords, wnd.post_excerpt, wnd.post_content FROM " . DB_PREFIX . "inge_coupon AS wn LEFT JOIN " . DB_PREFIX . "inge_coupon_description AS wnd ON wn.news_id = wnd.news_id WHERE wnd.language_id = '" . $language_id . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND wnd.post_title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_post_status'])) {
            $sql .= " AND wn.post_status = '" . $data['filter_post_status'] . "'";
        }

        if (isset($data['filter_now_status'])) {
            $sql .= " AND wn.is_delete = '" . $data['filter_now_status'] . "'";
        }
//        var_dump($sql);exit;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPostStores($post_id) {
        $post_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_to_store WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_store_data[] = $result['store_id'];
        }

        return $post_store_data;
    }

    public function getPostDescriptions($post_id) {
        $post_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'short_description' => $result['short_description'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
				'tag' => $result['tag']
                );
        }

        return $post_description_data;
    }

//    public function getPostLayouts($post_id) {
//        $layout_data = array();
//
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_to_layout WHERE post_id = '" . (int)$post_id . "'");
//
//        foreach ($query->rows as $result) {
//            $layout_data[$result['store_id']] = $result['layout_id'];
//        }
//
//        return $layout_data;
//    }

}
