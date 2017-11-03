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

// Xu ly thu muc uploads
$currentpath = $GLT->uploadDirInit($module_name . '/images/' . date('Y_m'));

// Xuat giao dien
$xtpl = new XTemplate("pic-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);

$step = $nv_Request->get_int('step', 'get,post', 1);

// Buoc 4 - Hoan thanh
if ($step == 4) {
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

    $my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";

    $xtpl->parse('step4');
    $contents = $xtpl->text('step4');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Buoc 3
if ($step == 3) {
    $ids = $GLT->string2array($nv_Request->get_title('ids', 'get', ''));

    if (empty($ids)) {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    }

    $error = '';
    $array = array(
        'id' => $nv_Request->get_int('albumId', 'post', 0),
        'title' => nv_substr($nv_Request->get_title('albumTitle', 'post', '', 1), 0, 255),
    );

    if ($nv_Request->isset_request('submit', 'post')) {
        if (empty($array['id']) or empty($array['title'])) {
            $error = $GLT->lang('picULErrorChooseEmpty');
        } else {
            $sql = "SELECT * FROM " . $GLT->table_prefix . "_albums WHERE title=" . $db->quote($array['title']) . " AND id=" . $array['id'];
            $result = $db->query($sql);

            if ($result->rowCount() != 1) {
                $error = sprintf($GLT->lang('picULErrorChooseNoExists'), $array['title']);
            }

            $album = $result->fetch();
        }

        if (empty($error)) {
            $sql = "UPDATE " . $GLT->table_prefix . "_pictures SET albums=" . $db->quote($GLT->buildSQLLids(array($array['id']))) . " WHERE id IN(" . implode(',', $ids) . ")";
            $db->query($sql);

            // Lay thong tin
            $album['datapics'] .= ',' . implode(',', $ids);
            $album['datapics'] = $GLT->string2array($album['datapics']);

            $sql = "UPDATE " . $GLT->table_prefix . "_albums SET datapics=" . $db->quote($GLT->buildSQLLids($album['datapics'])) . ", numpics=" . sizeof($album['datapics']) . " WHERE id=" . $album['id'];
            $db->query($sql);

            $GLT->fixPics($ids);

            $nv_Cache->delMod($module_name);

            nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&step=4');
        }
    }

    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=3&ids=" . implode(',', $ids));
    $xtpl->assign('NEXT_STEP', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=4");
    $xtpl->assign('DATA', $array);

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('step3.error');
    }

    $xtpl->parse('step3');
    $contents = $xtpl->text('step3');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Buoc 2 nhap thong tin
if ($step == 2) {
    $error = '';
    $array = array();

    $totalFile = $nv_Request->get_int('uploader_count', 'post', 0);
    if ($totalFile < 1) {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    }

    $mime = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);

    for ($i = 0; $i < $totalFile; $i++) {
        $array[$i] = array(
            'stt' => $i,
            'title' => $nv_Request->get_title('title_' . $i, 'post', ''),
            'info1' => $nv_Request->get_title('info1_' . $i, 'post', ''),
            'info2' => $nv_Request->get_title('info2_' . $i, 'post', ''),
            'info3' => $nv_Request->get_title('info3_' . $i, 'post', ''),
            'info4' => $nv_Request->get_title('info4_' . $i, 'post', ''),
            'info5' => $nv_Request->get_title('info5_' . $i, 'post', ''),
            'link' => $nv_Request->get_title('link_' . $i, 'post', ''),
            'description' => $nv_Request->get_title('description_' . $i, 'post', ''),
            'file' => $nv_Request->get_title('uploader_' . $i . '_name', 'post', ''),
            'status' => $nv_Request->get_title('uploader_' . $i . '_status', 'post', ''),
            'thumb' => $nv_Request->get_title('thumb_' . $i, 'post', ''),
            'width' => 0,
            'height' => 0,
            'size' => 0,
            'format' => '',
        );

        if (empty($array[$i]['title'])) {
            $array[$i]['title'] = $GLT->lang('picDefaultTitle') . ' ' . ($i + 1);
        }

        // Kiem tra file ton tai
        if ($array[$i]['status'] != 'done' or empty($array[$i]['file']) or !file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file'])) {
            $error .= $GLT->lang('picULErrorExists') . ' ' . $array[$i]['file'];
            unset($array[$i]);
        }

        // Kiem tra anh hop le
        if (isset($array[$i])) {
            $image_info = nv_is_image(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file']);

            if (empty($image_info) or !isset($mime['images'][$image_info['ext']])) {
                $error .= $GLT->lang('picULErrorMime') . ' ' . $array[$i]['file'];
                @nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file']);
                unset($array[$i]);
            } else {
                $array[$i]['width'] = $image_info['width'];
                $array[$i]['height'] = $image_info['height'];
                $array[$i]['format'] = $image_info['mime'];
                $array[$i]['size'] = filesize($image_info['src']);
            }
        }

        // Tao anh thumb
        if (isset($array[$i]) and empty($array[$i]['thumb'])) {
            $array[$i]['thumb'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $GLT->creatThumb(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, 90, 72);
        }
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        foreach ($array as $row) {
            if (empty($row['title'])) {
                $error .= $GLT->lang('picULErrorTitle') . ' ' . $row['file'] . '. ';
            }
        }

        if (empty($error)) {
            $added_ids = array();

            foreach ($array as $row) {
                // Copy file anh goc
                $fileName = $row['file'];
                $fileName2 = $fileName;
                $i = 1;
                while (file_exists(NV_ROOTDIR . '/' . $currentpath . '/' . $fileName2)) {
                    $fileName2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $fileName);
                    ++$i;
                }
                $fileName = $fileName2;
                $filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $row['file'];
                $newFilePath = NV_ROOTDIR . '/' . $currentpath . '/' . $fileName;

                $rename = nv_copyfile($filePath, $newFilePath);

                if (!$rename) {
                    $error .= $GLT->lang('picULErrorCopy') . basename($filePath) . ' ';
                    unset($array[$row['stt']]);
                } else {
                    // Xoa anh tam
                    @nv_deletefile($filePath);
                    $row['file'] = substr($newFilePath, strlen(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/images/'));

                    // Copy file thumb
                    $thumbName = $fileName = substr($row['thumb'], strlen(NV_BASE_SITEURL . NV_TEMP_DIR . '/'));
                    $fileName2 = $fileName;
                    $i = 1;
                    while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' . $fileName2)) {
                        $fileName2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $fileName);
                        ++$i;
                    }
                    $fileName = $fileName2;
                    $filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $thumbName;
                    $newFilePath = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' . $fileName;

                    $rename = nv_copyfile($filePath, $newFilePath);

                    if (!$rename) {
                        $error .= $GLT->lang('picULErrorCopy') . basename($filePath) . ' ';
                        unset($array[$row['stt']]);
                    } else {
                        // Xoa anh tam
                        @nv_deletefile($filePath);
                        $row['thumb'] = substr($newFilePath, strlen(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/'));

                        $sql = "INSERT INTO " . $GLT->table_prefix . "_pictures VALUES(
							NULL,
							" . $db->quote($row['title']) . ",
							" . $db->quote($row['info1']) . ",
							" . $db->quote($row['info2']) . ",
							" . $db->quote($row['info3']) . ",
							" . $db->quote($row['info4']) . ",
							" . $db->quote($row['info5']) . ",
							" . $db->quote($row['link']) . ",
							" . $db->quote($row['description']) . ",
							" . $db->quote($row['file']) . ",
							" . $db->quote($row['thumb']) . ",
							" . $row['width'] . ",
							" . $row['height'] . ",
							" . $row['size'] . ",
							" . $db->quote($row['format']) . ",
							''
						)";

                        $added_ids[] = $db->insert_id($sql);
                    }
                }
            }

            if (empty($error)) {
                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=3&ids=" . implode(',', $added_ids));
            }
        }
    }

    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=2");
    $xtpl->assign('TOTALFILE', $totalFile);

    foreach ($array as $row) {
        $row['class'] = $row['stt'] % 2 == 0 ? '' : ' class="second"';
        $row['filePath'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $row['file'];

        if (empty($row['thumb'])) {
            $row['thumb'] = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . '/d-thumb.png';
        }

        $xtpl->assign('ROW', $row);
        $xtpl->parse('step2.loop');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('step2.error');
    }

    $xtpl->parse('step2');
    $contents = $xtpl->text('step2');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

// Goi ra js
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=2");
$xtpl->assign('SETTING', $GLT->setting);

// Frameworks dir
$xtpl->assign('FRAMEWORKS_DIR', NV_BASE_SITEURL . 'themes/default/images/' . $module_file . '/frameworks/plupload');
$xtpl->assign('UPLOAD_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload&checksess=' . md5($nv_Request->session_id . $global_config['sitekey']));

$xtpl->parse('step1');
$contents = $xtpl->text('step1');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
