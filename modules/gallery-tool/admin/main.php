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

// Tim kiem va them mot anh
if ($nv_Request->isset_request('findOneAndReturn', 'get')) {
    $pictures = nv_substr($nv_Request->get_title('pictures', 'get', '', 1), 0, 255);
    $returnArea = nv_substr($nv_Request->get_title('area', 'get', '', 1), 0, 255);
    $returnInput = nv_substr($nv_Request->get_title('input', 'get', '', 1), 0, 255);

    $page_title = $GLT->lang('picFindTitle');
    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 6;
    $array = array();

    // SQL va LINK co ban
    $sql = "FROM " . $GLT->table_prefix . "_pictures WHERE id!=0";
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;pictures=" . $pictures;

    // Du lieu tim kiem
    $data_search = array("q" => nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, 255), );

    if (!empty($pictures))
        $sql .= " AND id NOT IN(" . $pictures . ")";

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
    $lang_order_2 = array("title" => $GLT->lang('albumTitle'), );

    $order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');

    foreach ($order as $key => $check) {
        if (!in_array($check['order'], $check_order)) {
            $order[$key]['order'] = "NO";
        }

        $order[$key]['data'] = array(
            "class" => "order" . strtolower($order[$key]['order']),
            "url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
            "title" => sprintf($lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]);
    }

    if ($order['title']['order'] != "NO") {
        $sql .= " ORDER BY title " . $order['title']['order'];
    } else {
        $sql .= " ORDER BY id DESC";
    }

    $sql1 = "SELECT COUNT(*) " . $sql;
    $result1 = $db->query($sql1);
    $all_page = $result1->fetchColumn();

    $sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
    $result = $db->query($sql);

    $array = array();
    while ($row = $result->fetch()) {
        $array[$row['id']] = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "width" => $row['width'],
            "height" => $row['height'],
            "thumb" => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $row['thumb'],
            "file" => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/images/' . $row['file'],
        );
    }

    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

    $xtpl = new XTemplate("pic-find-one.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('PICTURES', $pictures);
    $xtpl->assign('RETURNINPUT', $returnInput);
    $xtpl->assign('RETURNAREA', $returnArea);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?");
    $xtpl->assign('DATA_ORDER', $order);
    $xtpl->assign('SEARCH', $data_search);
    $xtpl->assign('URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&pictures=" . $pictures);

    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Tim kiem va them nhieu anh
if ($nv_Request->isset_request('findListAndReturn', 'get')) {
    $pictures = nv_substr($nv_Request->get_title('pictures', 'get', '', 1), 0, 255);

    $returnArea = nv_substr($nv_Request->get_title('area', 'get', '', 1), 0, 255);
    $returnInput = nv_substr($nv_Request->get_title('input', 'get', '', 1), 0, 255);

    if ($nv_Request->isset_request('loadname', 'get')) {
        $list_pictures = $GLT->getPicsByID($GLT->string2array($pictures), true);

        $return = "";
        foreach ($list_pictures as $pic) {
            $pic['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $pic['thumb'];
            $return .= "<li class=\"list-group-item list-group-item-success\" data-picid=\"" . $pic['id'] . "\"><div><img src=\"" . $pic['thumb'] . "\"/>" . $pic['title'] . "<span onclick=\"nv_del_item_on_list(" . $pic['id'] . ", '" . $returnArea . "', nv_is_del_confirm[0], '" . $returnInput . "')\"><i class=\"fa fa-trash fa-lg\" aria-hidden=\"true\"></i></span></div></li>";
        }

        include NV_ROOTDIR . '/includes/header.php';
        echo $return;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $pictures = $GLT->string2array($pictures);

    $sql = "FROM " . $GLT->table_prefix . "_pictures";
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findListAndReturn=1";

    $sql1 = "SELECT COUNT(*) " . $sql;
    $result1 = $db->query($sql1);
    $all_page = $result1->fetchColumn();

    $sql .= " ORDER BY id DESC";

    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 6;

    $sql2 = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
    $query2 = $db->query($sql2);

    while ($row = $query2->fetch()) {
        $array[$row['id']] = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "width" => $row['width'],
            "height" => $row['height'],
            "thumb" => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $row['thumb'],
            "file" => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/images/' . $row['file'],
            "checked" => in_array($row['id'], $pictures) ? " checked=\"checked\"" : ""
        );
    }

    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page, true, true, "nv_load_page", "data");

    $xtpl = new XTemplate("pic-find-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('PICTURES', implode(",", $pictures));
    $xtpl->assign('RETURNINPUT', $returnInput);
    $xtpl->assign('RETURNAREA', $returnArea);

    if (!empty($array)) {
        foreach ($array as $row) {
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.data.row');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }

        $xtpl->parse('main.data');
    }

    if ($nv_Request->isset_request('getdata', 'get')) {
        $contents = $xtpl->text('main.data');

        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xoa anh
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

    $array_title = array();
    $array_albums = array();
    $pictures = $GLT->getPicsByID($listid);

    foreach ($pictures as $picture) {
        $array_title[] = $picture['title'];

        // Xoa anh goc
        $file = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/images/' . $picture['file'];
        if (file_exists($file)) {
            nv_deletefile($file);
        }

        // Xoa anh thumb
        $thumb = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' . $picture['thumb'];
        if (file_exists($thumb)) {
            nv_deletefile($thumb);
        }

        // Xoa anh khoi CSLD
        $db->query("DELETE FROM " . $GLT->table_prefix . "_pictures WHERE id=" . $picture['id']);

        // Xoa anh thumb theo album
        $sql = "SELECT * FROM " . $GLT->table_prefix . "_thumbs WHERE picid=" . $picture['id'];
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $row['link'] = $row['link'];
            $row['link'] = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/' . $row['link'];

            @nv_deletefile($row['link']);
        }

        $db->query("DELETE FROM " . $GLT->table_prefix . "_thumbs WHERE picid=" . $picture['id']);

        // Them vao danh sach album
        $picture['albums'] = $GLT->string2array($picture['albums']);
        $array_albums = array_merge_recursive($array_albums, $picture['albums']);
    }

    // Cap nhat lai cac album
    $array_albums = array_filter(array_unique($array_albums));

    if (!empty($array_albums)) {
        $GLT->fixAlbums($array_albums);
    }

    // Ghi log
    nv_insert_logs(NV_LANG_DATA, $module_name, $GLT->lang('picDel'), implode(", ", $array_title), $admin_info['userid']);

    // Xoa cache
    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

$page_title = $GLT->lang('picList');

// Khoi tao bien, phan trang
$array = array();
$per_page = 30;
$page = $nv_Request->get_int('page', 'get', 1);

// SQL co ban
$sql = "FROM " . $GLT->table_prefix . "_pictures WHERE id!=0";
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
    "id" => $GLT->lang('picID'),
    "title" => $GLT->lang('picTitle'),
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
$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

// Goi xtemplate
$xtpl = new XTemplate("pic-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Xuat bai viet
while ($row = $result->fetch()) {
    $row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album-content&amp;id=" . $row['id'];
    $row['file'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/images/' . $row['file'];
    $row['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $row['thumb'];
    $row['size'] = nv_convertfromBytes($row['size']);

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
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('DATA_ORDER', $order);
$xtpl->assign('URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
$xtpl->assign('URL_EDIT', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=pic-edit&id=");

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
