<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_BLOG_ADMIN'))
    die('Stop!!!');

$page_title = $GLT->lang('picManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int('id', 'get, post', 0);

// Xu ly
if ($id) {
    $sql = "SELECT * FROM " . $GLT->table_prefix . "_pictures WHERE id=" . $id;
    $result = $db->query($sql);

    if ($result->rowCount() != 1) {
        nv_info_die($GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content'));
    }

    $row = $result->fetch();

    $array_old = $array = array(
        "title" => $row['title'],
        "info1" => $row['info1'],
        "info2" => $row['info2'],
        "info3" => $row['info3'],
        "info4" => $row['info4'],
        "info5" => $row['info5'],
        "link" => $row['link'],
        "description" => $row['description'],
        "albums" => array(),
    );

    $form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
    $table_caption = $GLT->lang('picEdit');
} else {
    nv_info_die($GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content'));
}

if ($nv_Request->isset_request('submit', 'post')) {
    $array['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $array['info1'] = nv_substr($nv_Request->get_title('info1', 'post', '', 1), 0, 255);
    $array['info2'] = nv_substr($nv_Request->get_title('info2', 'post', '', 1), 0, 255);
    $array['info3'] = nv_substr($nv_Request->get_title('info3', 'post', '', 1), 0, 255);
    $array['info4'] = nv_substr($nv_Request->get_title('info4', 'post', '', 1), 0, 255);
    $array['info5'] = nv_substr($nv_Request->get_title('info5', 'post', '', 1), 0, 255);
    $array['link'] = nv_substr($nv_Request->get_title('link', 'post', '', 1), 0, 255);
    $array['description'] = nv_substr($nv_Request->get_title('description', 'post', '', 1), 0, 255);
    $array['albums'] = $nv_Request->get_typed_array('albums', 'post', 'int', array());

    if (empty($array['title'])) {
        $error = $GLT->lang('picErrorTitle');
    }

    if (empty($error)) {
        $sql = "UPDATE " . $GLT->table_prefix . "_pictures SET
			title=" . $db->quote($array['title']) . ",
			info1=" . $db->quote($array['info1']) . ",
			info2=" . $db->quote($array['info2']) . ",
			info3=" . $db->quote($array['info3']) . ",
			info4=" . $db->quote($array['info4']) . ",
			info5=" . $db->quote($array['info5']) . ",
			link=" . $db->quote($array['link']) . ",
			description=" . $db->quote($array['description']) . ",
			albums=" . $db->quote($GLT->buildSQLLids($array['albums'])) . "
		WHERE id=" . $id;

        if ($db->query($sql)) {
            $nv_Cache->delMod($module_name);
            $complete = true;
        } else {
            $error = $GLT->lang('errorUpdateUnknow');
        }
    }
}

$xtpl = new XTemplate("pic-edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

$xtpl->assign('ID', $id);
$xtpl->assign('DATA', $array);
$xtpl->assign('TABLE_CAPTION', $table_caption);
$xtpl->assign('FORM_ACTION', $form_action);

// Neu la xuat ban thanh cong
if ($complete) {
    $xtpl->assign('MESSAGE', $GLT->lang('albumSaveOk'));

    $xtpl->parse('complete');
    $contents = $xtpl->text('complete');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

// Xuat loi
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';
