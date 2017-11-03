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

$page_title = $GLT->lang('albumManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int('id', 'get, post', 0);

// Xu ly
if ($id) {
    $sql = "SELECT * FROM " . $GLT->table_prefix . "_albums WHERE id=" . $id;
    $result = $db->query($sql);

    if ($result->rowCount() != 1) {
        nv_info_die($GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content'));
    }

    $row = $result->fetch();

    $array_old = $array = array(
        "title" => $row['title'],
        "description" => $row['description'],
        "bigw" => $row['bigw'],
        "bigh" => $row['bigh'],
        "smallw" => $row['smallw'],
        "smallh" => $row['smallh'],
        "pictures" => array_values($GLT->string2array($row['datapics'])),
    );

    $form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
    $table_caption = $GLT->lang('albumEdit');
} else {
    $form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
    $table_caption = $GLT->lang('albumAdd');

    $array = array(
        "title" => '',
        "description" => '',
        "bigw" => 490,
        "bigh" => 300,
        "smallw" => 200,
        "smallh" => 160,
        "pictures" => array(),
        );
}

if ($nv_Request->isset_request('submit', 'post')) {
    $array['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $array['description'] = nv_substr($nv_Request->get_title('description', 'post', '', 1), 0, 255);
    $array['bigw'] = abs($nv_Request->get_int('bigw', 'post', 0));
    $array['bigh'] = abs($nv_Request->get_int('bigh', 'post', 0));
    $array['smallw'] = abs($nv_Request->get_int('smallw', 'post', 0));
    $array['smallh'] = abs($nv_Request->get_int('smallh', 'post', 0));
    $array['pictures'] = $GLT->string2array($nv_Request->get_title('pictures', 'post', ''));

    if (empty($array['title'])) {
        $error = $GLT->lang('albumErrTitle');
    } elseif (!$array['bigw'] or !$array['bigh'] or !$array['smallw'] or !$array['smallh']) {
        $error = $GLT->lang('albumErrorPicSize');
    }

    // Kiem tra lien ket tinh ton tai va tao lien ket tinh khac neu la luu ban nhap
    $sql = "SELECT * FROM " . $GLT->table_prefix . "_albums WHERE title=" . $db->quote($array['title']) . (!empty($id) ? " AND id!=" . $id : "");
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $error = $GLT->lang('albumErrExists');
    }

    if (empty($error)) {
        if (empty($id)) {
            $sql = "INSERT INTO " . $GLT->table_prefix . "_albums VALUES(
				NULL,
				" . $db->quote($array['title']) . ",
				" . $db->quote($array['description']) . ",
				" . sizeof($array['pictures']) . ",
				" . $db->quote($GLT->buildSQLLids($array['pictures'])) . ",
				" . $array['bigw'] . ",
				" . $array['bigh'] . ",
				" . $array['smallw'] . ",
				" . $array['smallh'] . "
			)";

            $id = $db->insert_id($sql);

            if ($id) {
                if (!empty($array['pictures'])) {
                    // Tang thoi gian thuc hien va bo nho
                    if ($sys_info['allowed_set_time_limit']) {
                        set_time_limit(0);
                    }

                    if ($sys_info['ini_set_support']) {
                        $memoryLimitMB = (integer)ini_get('memory_limit');
                        if ($memoryLimitMB < 1024) {
                            ini_set("memory_limit", "1024M");
                        }
                    }

                    $GLT->fixPics($array['pictures']);
                }

                $nv_Cache->delMod($module_name);
                $complete = true;
            } else {
                $error = $GLT->lang('errorSaveUnknow');
            }
        } else {
            $sql = "UPDATE " . $GLT->table_prefix . "_albums SET
				title=" . $db->quote($array['title']) . ",
				description=" . $db->quote($array['description']) . ",
				numpics=" . sizeof($array['pictures']) . ",
				datapics=" . $db->quote($GLT->buildSQLLids($array['pictures'])) . ",
				bigw=" . $array['bigw'] . ",
				bigh=" . $array['bigh'] . ",
				smallw=" . $array['smallw'] . ",
				smallh=" . $array['smallh'] . "
			WHERE id=" . $id;

            if ($db->query($sql)) {
                // Tang thoi gian thuc hien va bo nho
                if ($sys_info['allowed_set_time_limit']) {
                    set_time_limit(0);
                }

                if ($sys_info['ini_set_support']) {
                    $memoryLimitMB = (integer)ini_get('memory_limit');
                    if ($memoryLimitMB < 1024) {
                        ini_set("memory_limit", "1024M");
                    }
                }

                $GLT->fixPics(array_merge_recursive($array['pictures'], $array_old['pictures']));

                $nv_Cache->delMod($module_name);
                $complete = true;
            } else {
                $error = $GLT->lang('errorUpdateUnknow');
            }
        }
    }
}

// Chuyen so thanh chuoi
if (empty($array['bigw']))
    $array['bigw'] = "";
if (empty($array['bigh']))
    $array['bigh'] = "";
if (empty($array['smallw']))
    $array['smallw'] = "";
if (empty($array['smallh']))
    $array['smallh'] = "";

// Lay danh sach anh album
if (!empty($array['pictures'])) {
    $pics = $GLT->getPicsByID($array['pictures'], true);

    $array['pictures'] = array();
    foreach ($pics as $pic) {
        $array['pictures'][$pic['id']] = array(
            'id' => $pic['id'],
            'title' => $pic['title'],
            'thumb' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $pic['thumb'],
            'file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/images/' . $pic['file'],
            );
    }
} else {
    $array['pictures'] = array();
}

$xtpl = new XTemplate("album-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

$xtpl->assign('ID', $id);
$xtpl->assign('DATA', $array);
$xtpl->assign('TABLE_CAPTION', $table_caption);
$xtpl->assign('FORM_ACTION', $form_action);

$xtpl->assign('PICTURES', implode(",", array_keys($array['pictures'])));

// Xuat cac anh
if (!empty($array['pictures'])) {
    foreach ($array['pictures'] as $picture) {
        $xtpl->assign('PICTURE', $picture);
        $xtpl->parse('main.picture');
    }
}

// Neu la xuat ban thanh cong
if ($complete) {
    $my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album-list\" />";
    $xtpl->assign('MESSAGE', $GLT->lang('albumSaveOk'));

    $xtpl->parse('complete');
    $contents = $xtpl->text('complete');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
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
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
