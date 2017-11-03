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

// Autocomplete album
if ($nv_Request->isset_request('ajaxAlbum', 'post')) {
    $contents = array();
    $q = nv_substr($nv_Request->get_title("ajaxAlbum", "post", "", 1), 0, 255);

    if (!empty($q)) {
        $sql = "SELECT * FROM " . $GLT->table_prefix . "_albums WHERE title LIKE '%" . $db->dblikeescape($q) . "%' ORDER BY title ASC LIMIT 0, 20";
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $contents[] = array(
                "value" => $row['id'],
                "label" => nv_unhtmlspecialchars($row['title']),
            );
        }
    }

    nv_jsonOutput($contents);
}

// Tim kiem va them mot album
if ($nv_Request->isset_request('findOneAndReturn', 'get')) {
    $ids = nv_substr($nv_Request->get_title('ids', 'get', '', 1), 0, 255);
    $returnArea = nv_substr($nv_Request->get_title('area', 'get', '', 1), 0, 255);
    $returnInput = nv_substr($nv_Request->get_title('input', 'get', '', 1), 0, 255);
    $multi = $nv_Request->get_int('multi', 'get', 0);

    $page_title = $GLT->lang('picFindTitle');
    $page = $nv_Request->get_int('page', 'get', 0);
    $per_page = 7;
    $array = array();

    // SQL va LINK co ban
    $sql = "FROM " . $GLT->table_prefix . "_albums WHERE id!=0";
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;ids=" . $ids . "&amp;multi=" . $multi;

    // Du lieu tim kiem
    $data_search = array("q" => nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, 255), );

    if (!empty($ids))
        $sql .= " AND id NOT IN(" . $ids . ")";

    // Tim ten anh
    if (!empty($data_search['q'])) {
        $base_url .= "&amp;q=" . urlencode($data_search['q']);
        $sql .= " AND ( title LIKE '%" . $db->dblikeescape($data_search['q']) . "%' )";
    }

    // Order data
    $order = array();
    $check_order = array(
        "ASC",
        "DESC",
        "NO"
    );
    $opposite_order = array(
        "NO" => "ASC",
        "DESC" => "ASC",
        "ASC" => "DESC"
    );
    $lang_order_1 = array(
        "NO" => $GLT->lang('filter_lang_asc'),
        "DESC" => $GLT->lang('filter_lang_asc'),
        "ASC" => $GLT->lang('filter_lang_desc'),
    );
    $lang_order_2 = array("title" => $GLT->lang('albumTitle'));

    $order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');

    foreach ($order as $key => $check) {
        if (!in_array($check['order'], $check_order)) {
            $order[$key]['order'] = "NO";
        }

        $order[$key]['data'] = array(
            "class" => "order" . strtolower($order[$key]['order']),
            "url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
            "title" => sprintf($lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]
        );
    }

    if ($order['title']['order'] != "NO") {
        $sql .= " ORDER BY title " . $order['title']['order'];
    } else {
        $sql .= " ORDER BY id DESC";
    }

    $sql1 = "SELECT COUNT(*) " . $sql;
    $result1 = $db->query($sql1);
    $all_page = $result1->fetchColumn();

    $sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
    $result = $db->query($sql);

    $array = array();
    while ($row = $result->fetch()) {
        $array[$row['id']] = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "numpics" => $row['numpics'],
            "bigw" => $row['bigw'],
            "bigh" => $row['bigh'],
            "smallw" => $row['smallw'],
            "smallh" => $row['smallh'],
        );
    }

    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

    $xtpl = new XTemplate("album-find-one.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('IDS', $ids);
    $xtpl->assign('MULTI', $multi);
    $xtpl->assign('RETURNINPUT', $returnInput);
    $xtpl->assign('RETURNAREA', $returnArea);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?");
    $xtpl->assign('DATA_ORDER', $order);
    $xtpl->assign('SEARCH', $data_search);
    $xtpl->assign('URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&ids=" . $ids . "&multi=" . $multi);

    $a = 0;
    foreach ($array as $row) {
        $xtpl->assign('CLASS', ($a % 2 == 1) ? " class=\"second\"" : "");
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row');
        $a++;
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xoa album
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $id = $nv_Request->get_int('id', 'post', 0);
    $list_levelid = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($list_levelid))
        die('NO');

    $listid = array();
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $list_levelid = explode(",", $list_levelid);
        $list_levelid = array_map("trim", $list_levelid);
        $list_levelid = array_filter($list_levelid);

        $listid = $list_levelid;
        $num = sizeof($list_levelid);
    }

    $albums = $GLT->getAlbumsByID($listid);
    $array_pictures = array();
    $array_title = array();

    foreach ($albums as $album) {
        $array_title[] = $album['title'];

        // Xoa khoi CSDL
        $db->query("DELETE FROM " . $GLT->table_prefix . "_albums WHERE id=" . $album['id']);

        // Them vao danh sach anh
        $album['datapics'] = $GLT->string2array($album['datapics']);
        $array_pictures = array_merge_recursive($array_pictures, $album['datapics']);
    }

    // Cap nhat lai cac anh
    $array_pictures = array_filter(array_unique($array_pictures));

    if (!empty($array_pictures)) {
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

        $GLT->fixPics($array_pictures);
    }

    // Ghi log
    nv_insert_logs(NV_LANG_DATA, $module_name, $GLT->lang('albumDelete'), implode(", ", $array_title), $admin_info['userid']);

    // Xoa cache
    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

$page_title = $GLT->lang('albumList');

// Khoi tao bien, phan trang
$array = array();
$per_page = 30;
$page = $nv_Request->get_int('page', 'get', 0);

// SQL co ban
$sql = "FROM " . $GLT->table_prefix . "_albums WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Bien tim kiem
$data_search = array("q" => nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, 100), "disabled" => " disabled=\"disabled\"");

// Cam an nut huy tim kiem
if (!empty($data_search['q'])) {
    $data_search['disabled'] = "";
}

// Query tim kiem
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $sql .= " AND ( title LIKE '%" . $db->dblikeescape($data_search['q']) . "%' OR description LIKE '%" . $db->dblikeescape($data_search['q']) . "%' )";
}

// Du lieu sap xep
$order = array();
$check_order = array(
    "ASC",
    "DESC",
    "NO"
);
$opposite_order = array(
    "NO" => "ASC",
    "DESC" => "ASC",
    "ASC" => "DESC"
);
$lang_order_1 = array(
    "NO" => $GLT->lang('filter_lang_asc'),
    "DESC" => $GLT->lang('filter_lang_asc'),
    "ASC" => $GLT->lang('filter_lang_desc')
);
$lang_order_2 = array(
    "id" => $GLT->lang('albumID'),
    "title" => $GLT->lang('albumTitle'),
);

$order['id']['order'] = $nv_Request->get_title('order_id', 'get', 'NO');
$order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');

foreach ($order as $key => $check) {
    $order[$key]['data'] = array(
        "class" => "order" . strtolower($order[$key]['order']),
        "url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
        "title" => sprintf($lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]
    );

    if (!in_array($check['order'], $check_order)) {
        $order[$key]['order'] = "NO";
    } else {
        $base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
    }
}

if ($order['id']['order'] != "NO") {
    $sql .= " ORDER BY id " . $order['id']['order'];
} elseif ($order['title']['order'] != "NO") {
    $sql .= " ORDER BY title " . $order['title']['order'];
} else {
    $sql .= " ORDER BY id DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->query($sql);

// Goi xtemplate
$xtpl = new XTemplate("album-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Xuat bai viet
while ($row = $result->fetch()) {
    $row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album-content&amp;id=" . $row['id'];
    $row['class'] = ($i++ % 2 == 0) ? " class=\"second\"" : "";

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

// Cac thao tac
$list_action = array(
    0 => array(
        "key" => 1,
        "class" => "delete",
        "title" => $GLT->glang('delete')
    )
);

foreach ($list_action as $action) {
    $xtpl->assign('ACTION', $action);
    $xtpl->parse('main.action');
}

// Xuat du lieu phuc vu tim kiem
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA_SEARCH', $data_search);
$xtpl->assign('DATA_ORDER', $order);
$xtpl->assign('URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

// Phan trang
$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
