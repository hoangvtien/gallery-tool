<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

// Class cua module
require_once (NV_ROOTDIR . "/modules/" . $module_file . "/class.php");
$GLT = new nv_mod_gallery_tool();

define('NV_BLOG_ADMIN', true);
