<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    "name" => "Gallery Tool",
    "modfuncs" => "main",
    "submenu" => "main",
    "is_sysmod" => 0,
    "virtual" => 1,
    "version" => "4.2.01",
    "date" => "Sunday, October 29, 2017 4:40:39 AM",
    "author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
    "note" => "Module Gallery Tool For Many Slideshow",
    "uploads_dir" => array(
        $module_name,
        $module_name . "/images",
        $module_name . "/thumb",
        $module_name . "/thumb/sys",
    )
);
