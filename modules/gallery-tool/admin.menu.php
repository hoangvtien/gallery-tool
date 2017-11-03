<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['pic-content'] = $lang_module['picAdd'];
$submenu['album-list'] = $lang_module['albumList'];
$submenu['album-content'] = $lang_module['albumAdd'];
$submenu['config-master'] = $lang_module['cfg'];

$allow_func = array(
    'main',
    'pic-content',
    'album-list',
    'album-content',
    'pic-edit',
    'config-master'
);
