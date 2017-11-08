<?php
$_['inge_coupon_module_debug'] = 0;
$_['inge_coupon_module_setting'] = array(

    'config' => 'inge_coupon_module',
    'utc_datetime_format' => 'Y-m-d\TH:i:sP',
    'dir_admin' => 'admin',
    'theme' => 'default',

    //这里的配置用在哪里？  by Reson
    'coupon_thumb' => array(
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
    'coupon' => array(
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