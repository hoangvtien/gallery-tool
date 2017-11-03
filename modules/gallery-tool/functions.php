<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_GLTOOL', true);

// Class cua module
require_once (NV_ROOTDIR . "/modules/" . $module_file . "/class.php");
$GLT = new nv_mod_gallery_tool();

/**
 * gltJsonResponse()
 *
 * @param mixed $error
 * @param mixed $data
 * @return void
 */
function gltJsonResponse($error = array(), $data = array())
{
    $contents = array(
        "jsonrpc" => "2.0",
        "error" => $error,
        "data" => $data,
    );

    include NV_ROOTDIR . '/includes/header.php';
    echo json_encode($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}
