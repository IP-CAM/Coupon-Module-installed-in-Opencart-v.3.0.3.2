<?php
class ModelExtensionIngeCouponModuleCategory extends Model {

    public function addCategory($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX
            . "inge_coupon_category SET parent_id = '" . (int) $data['parent_id']
            . "', sort_order = '0"
            . "', status = '" . (int) $data['status']
            . "', is_nav = '" . (int) $data['is_nav'] . "'");
//            . "', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();

//        if (isset($data['image'])) {
//            $this->db->query("UPDATE " . DB_PREFIX . "ws_category "
//                . "SET image = '" . $this->db->escape($data['image'])
//                . "' WHERE category_id = '" . (int) $category_id . "'");
//        }

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "inge_coupon_category_description "
                . "SET news_category_id = '" . (int) $category_id
                . "', language_id = '" . (int) $language_id
                . "', title = '" . $this->db->escape($value['title'])
                . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        return $category_id;
    }

    public function copyCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ws_category c "
            . "LEFT JOIN " . DB_PREFIX . "ws_category_description cd "
            . "ON (c.category_id = cd.category_id) "
            . "WHERE c.category_id = '" . (int) $category_id . "' "
            . "AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = $query->row;

            $data['viewed'] = '0';
            $data['keyword'] = '';
            $data['status'] = '0';
            $data['category_layout'] = $this->getCategoryLayouts($category_id);


            $data['category_description'] = $this->getCategoryDescriptions($category_id);


            $this->addCategory($data);
        }
    }

    public function editCategory($category_id, $data ) {

        $query = $this->db->query("UPDATE " . DB_PREFIX . "inge_coupon_category SET parent_id = '" . $data['parent_id'] . "', is_nav = '" . $data['is_nav'] . "', status = '" . $data['status'] . "' WHERE news_category_id = '" . $category_id . "'");

//        if (isset($data['image'])) {
//            $this->db->query("UPDATE " . DB_PREFIX . "ws_category "
//                . "SET image = '" . $this->db->escape($data['image']) . "' "
//                . "WHERE category_id = '" . (int) $category_id . "'");
//        }

       foreach ($data['category_description'] as $language_id => $value) {
           $this->db->query("REPLACE INTO " . DB_PREFIX . "inge_coupon_category_description VALUES ('" . $value['category_description_id'] . "','" . $category_id ."','" . $language_id . "','" . $value['title'] . "','" . $value['description'] . "')");
       }

        return $query;
    }

    public function repairCategories($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ws_category 
            WHERE parent_id = '" . (int)$parent_id . "'");

        foreach ($query->rows as $category) {
// Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "ws_category_path` 
                WHERE category_id = '" . (int)$category['category_id'] . "'");

// Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ws_category_path` 
                WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "ws_category_path` SET 
                    `category_id` = '" . (int)$category['category_id'] . "', 
                    `path_id` = '" . (int)$result['path_id'] . "', 
                    `level` = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "ws_category_path` SET 
                `category_id` = '" . (int)$category['category_id'] . "', 
                `path_id` = '" . (int)$category['category_id'] . "', 
                `level` = '" . (int)$level . "'");

            $this->repairCategories($category['category_id']);
        }
    }

    public function deleteCategory($category_id) {
        $child_categories = $this->findChildCategories($category_id);

        foreach($child_categories as $id){
            $this->db->query("DELETE FROM " . DB_PREFIX . "inge_coupon_category WHERE news_category_id = '" . $id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "inge_coupon_category_description WHERE news_category_id = '" . $id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "inge_coupon_category WHERE news_category_id = '" .  $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "inge_coupon_category_description WHERE news_category_id = '" . $category_id . "'");
    }

    protected function findChildCategories($category_id)
    {
        static $removeChild = array();
        $categories = $this->db->query("SELECT * FROM " . DB_PREFIX . "inge_coupon_category");

        foreach($categories->rows as $key => $category){
            if($category['parent_id'] == $category_id){

                $removeChild[] = $category['news_category_id'];

                $this->findChildCategories($category['news_category_id']);
            }
        }

        return $removeChild;
    }

    public function getCategoryDescriptions($category_id) {
        $category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "inge_coupon_category_description WHERE news_category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_description_data[$result['language_id']] = array(
                'category_description_id' => $result['news_category_description_id'],
                'title' => $result['title'],
//                'meta_description' => $result['meta_description'],
//                'meta_keyword' => $result['meta_keyword'],
//                'short_description' => $result['short_description'],
                'description' => $result['description']
            );
        }

        return $category_description_data;
    }

    // public function getKeywordForCategory($post_id){
    //     $query = $this->db->query("SELECT keyword, query FROM " . DB_PREFIX . "url_alias WHERE query = 'ws_category_id=" . (int) $post_id . "'");

    //     $keyword_data = array();
    //     if($query->num_rows > 0)
    //     {
    //         $keyword_data = $query->row['keyword'];
    //     } else {
    //         $keyword_data = '';
    //     }
    //     return $keyword_data;
    // }

    public function getCategory($category_id, $language_id = 1) {
        $sql = "SELECT wnc.*, wncd.news_category_description_id,wncd.title AS title, wncd.description AS description, wncd.language_id AS language_id FROM " . DB_PREFIX . "inge_coupon_category AS wnc LEFT JOIN " . DB_PREFIX . "inge_coupon_category_description AS wncd ON wnc.news_category_id = wncd.news_category_id WHERE wnc.news_category_id = " . $category_id . " AND wncd.language_id = '" . $language_id . "'";

        $query = $this->db->query($sql);
        $result = $query->row;
        return $result;
    }

    public function getCategories($data = array(), $language_id) {

        $sql = "SELECT wnc.*, wncd.news_category_description_id ,wncd.title AS title, wncd.description AS description, wncd.language_id AS language_id FROM " . DB_PREFIX . "inge_coupon_category wnc LEFT JOIN " . DB_PREFIX . "inge_coupon_category_description wncd ON wnc.news_category_id = wncd.news_category_id WHERE wncd.language_id = '" . $language_id . "'";

        $sort_data = array(
            'title',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY wncd." . $data['sort'];
        } else {
            $sql .= " ORDER BY wnc.sort_order";
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        $results = $query->rows;

        $tree = $this->tree($results);

        return $tree;
    }

    public function getTotalCategories() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "inge_coupon_category");

        return $query->row['total'];
    }

//    public function getCategoryStores($category_id) {
//        $category_store_data = array();
//
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ws_category_to_store WHERE category_id = '" . (int) $category_id . "'");
//
//        foreach ($query->rows as $result) {
//            $category_store_data[] = $result['store_id'];
//        }
//
//        return $category_store_data;
//    }

    public function getCategorySeoUrls($category_id) {
        $category_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE route='extension/d_blog_module/category' AND query = 'category_id=" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $category_seo_url_data;
    }

    public function getCategoryList($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "ws_category_description WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sql .= " ORDER BY title";

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $category_data = $this->cache->get('category.' . (int) $this->config->get('config_language_id'));

            if (!$category_data) {
                $query = $this->db->query("SELECT category_id, title FROM " . DB_PREFIX . "ws_category_description WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY title");

                $category_data = $query->rows;

                $this->cache->set('category.' . (int) $this->config->get('config_language_id'), $category_data);
            }
            return $category_data;
        }
    }

//    public function getCategoryLayouts($category_id) {
//        $layout_data = array();
//
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ws_category_to_layout WHERE category_id = '" . (int)$category_id . "'");
//
//        foreach ($query->rows as $result) {
//            $layout_data[$result['store_id']] = $result['layout_id'];
//        }
//
//        return $layout_data;
//    }

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
}
