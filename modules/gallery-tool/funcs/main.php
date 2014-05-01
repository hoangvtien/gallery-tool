<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_GLTOOL' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

header( "Location:" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true ) );
die();

?>