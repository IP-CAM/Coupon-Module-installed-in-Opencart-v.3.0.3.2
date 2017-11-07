<?php
$_['inge_coupon_module_debug'] = 0;
$_['inge_coupon_module_setting'] = array(

    'config' => 'inge_coupon_module',
    'utc_datetime_format' => 'Y-m-d\TH:i:sP',
    'dir_admin' => 'admin',
    'theme' => 'default',

    'category' => array(
        'main_category_id' => 1,
        'layout' => array(0 => 2),
        'layout_type' => 'grid',
        'post_page_limit' => 7,
        'image_display' => 1,
        'image_width' => 1200,
        'image_height' => 750,
        'sub_category_display' => 1,
        'sub_category_col' => 6,
        'sub_category_image' => 1,
        'sub_category_post_count' => 1,
        'sub_category_image_width' => 120,
        'sub_category_image_height' => 75,
    ),
    'post_thumb' => array(
        'image_width' => 1200,
        'image_height' => 750,
        'title_length' => 100,
        'short_description_length' => 300,
        'description_length' => 300,
        'category_label_display' => 1,
        'author_display' => 1,
        'date_display' => 1,
        'date_format' => 'F d, Y',
        'date_format_day' => 'd',
        'date_format_month' => 'M',
        'date_format_year' => 'Y',
        'rating_display' => 1,
        'description_display' => 1,
        'tag_display' => 1,
        'views_display' => 1,
        'review_display' => 1,
        'read_more_display' => 1,
        'animate' => 'slideInUp'
    ),
    'post' => array(
        'image_display' => 1,
        'image_width' => 1200,
        'image_height' => 750,
        'popup_display' => 1,
        'popup_width' => 1400,
        'popup_height' => 875,
        'author_display' => 1,
        'date_display' => 1,
        'date_format' => 'l dS F Y',
        'review_display' => 1,
        'rating_display' => 1,
        'category_label_display' => 1,
        'short_description_length' => 150,
        'style_short_description_display' => 0,
        'nav_display' => 1,
        'nav_same_category' => 0,
    ),
    'review' => array(
        'guest' => 1,
        'social_login' => 1,
        'page_limit' => 5,
        'image_limit' => 5,
        'rating_display' => 1,
        'customer_display' => 1,
        'image_user_display' => 1,
        'moderate' => 0,
        'image_upload_width' => 500,
        'image_upload_height' => 500,
    ),
    'review_thumb' => array(
        'image_width' => 70,
        'image_height' => 70,
        'no_image' => 'catalog/inge_coupon_module/no_profile_image.png',
        'date_display' => 1,
        'image_display' => 1,
        'rating_display' => 1,
        'image_user_display' => 1,
        // 'layout' => array(0 => 1),
        'image_user_width' => 70,
        'image_user_height' => 70,
    ),
    'author' => array(
        'layout' => array(0 => 2),
        'layout_type' => 'grid',
        'post_page_limit' => 7,
        'image_width' => 400,
        'image_height' => 400,
        'category_display' => 1,
        'category_col' => 6,
        'category_image' => 1,
        'category_post_count' => 1,
        'category_image_width' => 120,
        'category_image_height' => 75,
    ),
    'design' => array(
        'custom_style' => '',
        'ssl_url' => ''
    )
    //'support' => 1,
);

$_['inge_coupon_module_animations'] = array(
    '',
    'slideInUp',
    'pulse',
    'tada',
    'jello',
    'fadeIn',
    'fadeInUp',
    'bounce',
    'bounceIn',
    'zoomIn',
    'zoomInDown',
    'zoomInUp'
);