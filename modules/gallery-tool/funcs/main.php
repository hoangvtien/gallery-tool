<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_IS_MOD_GLTOOL'))
    die('Stop!!!');

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
