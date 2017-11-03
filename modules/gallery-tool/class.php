<?php

/**
 * @Project NUKEVIET GALLERY TOOL 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sunday, October 29, 2017 4:40:39 AM
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

/**
 * nv_mod_gallery_tool
 *
 * @package GALLERY-TOOL
 * @author PHAN TAN DUNG (phantandung92@gmail.com)
 * @copyright 2014
 * @version 3
 * @access public
 */
class nv_mod_gallery_tool
{
    private $lang_data = '';
    private $mod_data = '';
    private $mod_name = '';
    private $mod_file = '';
    private $db = null;

    public $db_prefix = '';
    public $db_prefix_lang = "";
    public $table_prefix = "";

    public $cache_prefix = "";

    private $base_site_url = null;
    private $root_dir = null;
    private $upload_dir = null;
    private $currenttime = null;

    public $language = array();
    public $glanguage = array();

    public $setting = null;

    /**
     * nv_mod_gallery_tool::__construct()
     *
     * @param string $d
     * @param string $n
     * @param string $f
     * @param string $lang
     * @param bool $get_lang
     * @return
     */
    public function __construct($d = "", $n = "", $f = "", $lang = "", $get_lang = false)
    {
        global $module_data, $module_name, $module_file, $db_config, $db, $lang_global;

        // Ten CSDL
        if (!empty($d))
            $this->mod_data = $d;
        else
            $this->mod_data = $module_data;

        // Ten module
        if (!empty($n))
            $this->mod_name = $n;
        else
            $this->mod_name = $module_name;

        // Ten thu muc
        if (!empty($f))
            $this->mod_file = $f;
        else
            $this->mod_file = $module_file;

        // Ngon ngu
        if (!empty($lang))
            $this->lang_data = $lang;
        else
            $this->lang_data = NV_LANG_DATA;

        $this->db_prefix = $db_config['prefix'];
        $this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
        $this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
        $this->db = $db;

        $this->setting = $this->get_setting();

        $this->cache_prefix = NV_CACHE_PREFIX;
        $this->base_site_url = NV_BASE_SITEURL;
        $this->root_dir = NV_ROOTDIR;
        $this->upload_dir = NV_UPLOADS_DIR;
        $this->currenttime = NV_CURRENTTIME;

        // Ngon ngu
        if ($get_lang === false) {
            global $lang_module;
        } else {
            $file_lang_path = $this->root_dir . "/modules/" . $this->mod_file . "/language/";
            $file_lang_name = defined('NV_ADMIN') ? "admin_" . $this->lang_data . ".php" : $this->lang_data . ".php";
            if (is_file($file_lang_path . $file_lang_name)) {
                include ($file_lang_path . $file_lang_name);
            } else {
                $lang_module = array();
            }
        }

        $this->language = $lang_module;
        $this->glanguage = $lang_global;
    }

    /**
     * nv_mod_gallery_tool::get_setting()
     *
     * @return
     */
    private function get_setting()
    {
        $sql = "SELECT config_name, config_value FROM " . $this->table_prefix . "_config";
        $result = $this->db_cache($sql, '', $this->mod_name);

        $array = array();
        foreach ($result as $values) {
            $array[$values['config_name']] = $values['config_value'];
        }

        return $array;
    }

    /**
     * nv_mod_gallery_tool::handle_error()
     *
     * @param string $messgae
     * @return
     */
    private function handle_error($messgae = '')
    {
        trigger_error("Error! " . ($messgae ? (string )$messgae : "You are not allowed to access this feature now") . "!", 256);
    }

    /**
     * nv_mod_gallery_tool::check_admin()
     *
     * @return
     */
    private function check_admin()
    {
        if (!defined('NV_IS_MODADMIN'))
            $this->handle_error();
    }

    /**
     * nv_mod_gallery_tool::nl2br()
     *
     * @param mixed $string
     * @return
     */
    private function nl2br($string)
    {
        return nv_nl2br($string);
    }

    /**
     * nv_mod_gallery_tool::db_cache()
     *
     * @param mixed $sql
     * @param string $id
     * @param string $module_name
     * @return
     */
    private function db_cache($sql, $id = '', $module_name = '')
    {
        global $nv_Cache;
        return $nv_Cache->db($sql, $id, $module_name);
    }

    /**
     * nv_mod_gallery_tool::del_cache()
     *
     * @param mixed $module_name
     * @return
     */
    private function del_cache($module_name)
    {
        global $nv_Cache;
        return $nv_Cache->delMod($module_name);
    }

    /**
     * nv_mod_gallery_tool::change_alias()
     *
     * @param mixed $alias
     * @return
     */
    private function change_alias($alias)
    {
        return change_alias($alias);
    }

    /**
     * nv_mod_gallery_tool::sortArrayFromArrayKeys()
     *
     * @param mixed $keys
     * @param mixed $array
     * @return
     */
    private function sortArrayFromArrayKeys($keys, $array)
    {
        $return = array();

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $return[$key] = $array[$key];
            }
        }
        return $return;
    }

    /**
     * nv_mod_gallery_tool::IdHandle()
     *
     * @param mixed $stroarr
     * @param string $defis
     * @return
     */
    private function IdHandle($stroarr, $defis = ",")
    {
        $return = array();

        if (is_array($stroarr)) {
            $return = array_filter(array_unique(array_map("intval", $stroarr)));
        } elseif (strpos($stroarr, $defis) !== false) {
            $return = array_map("intval", $this->string2array($stroarr, $defis));
        } else {
            $return = array(intval($stroarr));
        }

        return $return;
    }

    /**
     * nv_mod_gallery_tool::getCache()
     *
     * @param mixed $cacheFile
     * @return
     */
    private function getCache($cacheFile)
    {
        return $nv_Cache->getItem($module_name, $cacheFile);
    }

    /**
     * nv_mod_gallery_tool::setCache()
     *
     * @param mixed $cacheFile
     * @param mixed $cache
     * @return
     */
    private function setCache($cacheFile, $cache)
    {
        return $nv_Cache->setItem($module_name, $cacheFile, $cache);
    }

    /**
     * nv_mod_gallery_tool::lang()
     *
     * @param mixed $key
     * @return
     */
    public function lang($key)
    {
        return isset($this->language[$key]) ? $this->language[$key] : $key;
    }

    /**
     * nv_mod_gallery_tool::glang()
     *
     * @param mixed $key
     * @return
     */
    public function glang($key)
    {
        return isset($this->glanguage[$key]) ? $this->glanguage[$key] : $key;
    }

    /**
     * nv_mod_gallery_tool::string2array()
     *
     * @param mixed $str
     * @param string $defis
     * @param bool $unique
     * @param bool $empty
     * @return
     */
    public function string2array($str, $defis = ",", $unique = false, $empty = false)
    {
        if (empty($str))
            return array();

        $str = array_map("trim", explode((string )$defis, (string )$str));

        if (!$unique) {
            $str = array_unique($str);
        }

        if (!$empty) {
            $str = array_filter($str);
        }

        return $str;
    }

    /**
     * nv_mod_gallery_tool::creatThumb()
     *
     * @param mixed $file
     * @param mixed $dir
     * @param mixed $width
     * @param integer $height
     * @return
     */
    public function creatThumb($file, $dir, $width, $height = 0)
    {
        $image = new NukeViet\Files\Image($file, NV_MAX_WIDTH, NV_MAX_HEIGHT);

        if (empty($height)) {
            $image->resizeXY($width, NV_MAX_HEIGHT);
        } else {
            if (($width * $image->fileinfo['height'] / $image->fileinfo['width']) > $height) {
                $image->resizeXY($width, NV_MAX_HEIGHT);
            } else {
                $image->resizeXY(NV_MAX_WIDTH, $height);
            }

            $image->cropFromCenter($width, $height);
        }

        // Kiem tra anh ton tai
        $fileName = $width . 'x' . $height . '-' . basename($file);
        $fileName2 = $fileName;
        $i = 1;
        while (file_exists($dir . '/' . $fileName2)) {
            $fileName2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1-' . $i . '\2', $fileName);
            ++$i;
        }
        $fileName = $fileName2;

        // Luu anh
        $image->save($dir, $fileName);
        $image->close();

        return substr($image->create_Image_info['src'], strlen($dir . '/'));
    }

    /**
     * nv_mod_gallery_tool::buildSQLLids()
     *
     * @param mixed $arr
     * @return
     */
    public function buildSQLLids($arr)
    {
        if (empty($arr)) {
            return '0,0,0';
        }
        return '0,' . implode(',', $arr) . ',0';
    }

    /**
     * nv_mod_gallery_tool::getPicsByID()
     *
     * @param mixed $id
     * @param bool $sort
     * @return
     */
    public function getPicsByID($id, $sort = false)
    {
        $pics = array();

        if (is_array($id)) {
            $result = $this->db->query(" SELECT * FROM " . $this->table_prefix . "_pictures WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $result->fetch()) {
                $pics[$row['id']] = $row;
            }

            if ($sort === true)
                $pics = $this->sortArrayFromArrayKeys($id, $pics);
        } else {
            $result = $this->db->query("SELECT * FROM " . $this->table_prefix . "_pictures WHERE id=" . $id);
            $pics = $result->fetch();
        }

        return $pics;
    }

    /**
     * nv_mod_gallery_tool::getAlbumsByID()
     *
     * @param mixed $id
     * @param bool $sort
     * @return
     */
    public function getAlbumsByID($id, $sort = false)
    {
        $albums = array();

        if (is_array($id)) {
            $result = $this->db->query(" SELECT * FROM " . $this->table_prefix . "_albums WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $result->fetch()) {
                $albums[$row['id']] = $row;
            }

            if ($sort === true)
                $albums = $this->sortArrayFromArrayKeys($id, $albums);
        } else {
            $result = $this->db->query("SELECT * FROM " . $this->table_prefix . "_albums WHERE id=" . intval($id));
            $albums = $result->fetch();
        }

        return $albums;
    }

    /**
     * nv_mod_gallery_tool::fixPics()
     *
     * @param mixed $ids
     * @return
     */
    public function fixPics($ids)
    {
        $ids = $this->IdHandle($ids);
        $pictures = $this->getPicsByID($ids);

        // Cap nhat lai danh sach album cua anh
        // Xoa cac anh thumb khong dung
        // Tao anh thumb can thiet

        $albums = array();
        $thumbDir = $this->uploadDirInit($this->mod_name . '/thumb/' . date('Y_m'));

        foreach ($pictures as $picture) {
            // Lay cac album chua pic nay
            $album = $thumbSize = array();
            $sql = "SELECT * FROM " . $this->table_prefix . "_albums WHERE " . $this->sqlSearchId($picture['id'], 'datapics');
            $result = $this->db->query($sql);

            while ($row = $result->fetch()) {
                $album[] = $row['id'];

                // Kich thuoc anh lon
                $thumbSize[$row['bigw'] . '-' . $row['bigh']] = array(
                    'width' => $row['bigw'],
                    'height' => $row['bigh'],
                );

                // Kich thuoc anh nho
                $thumbSize[$row['smallw'] . '-' . $row['smallh']] = array(
                    'width' => $row['smallw'],
                    'height' => $row['smallh'],
                );
            }

            // Cap nhat lai album cho pic
            $this->db->query("UPDATE " . $this->table_prefix . "_pictures SET albums=" . $this->db->quote($this->buildSQLLids($album)) . " WHERE id=" . $picture['id']);

            $sqlWhere = array();
            foreach ($thumbSize as $size) {
                $sqlWhere[] = '(width!=' . $size['width'] . ' OR height!=' . $size['height'] . ')';
            }
            $sqlWhere = implode(' AND ', $sqlWhere);

            // Xoa het thumb ma khong thuoc kich thuoc hien co
            $sql = "SELECT * FROM " . $this->table_prefix . "_thumbs WHERE picid=" . $picture['id'] . ($sqlWhere ? " AND " . $sqlWhere : '');
            $result = $this->db->query($sql);

            while ($row = $result->fetch()) {
                $row['link'] = $row['link'];
                $row['link'] = $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];

                @nv_deletefile($row['link']);
            }

            // Xoa thumb trong CSDL
            $this->db->query("DELETE FROM " . $this->table_prefix . "_thumbs WHERE picid=" . $picture['id'] . ($sqlWhere ? " AND " . $sqlWhere : ''));

            // Lay cac kich thuoc thumb da co
            $arrayThumbExists = array();

            $sql = "SELECT * FROM " . $this->table_prefix . "_thumbs WHERE picid=" . $picture['id'];
            $result = $this->db->query($sql);

            while ($row = $result->fetch()) {
                $arrayThumbExists[$row['width'] . '-' . $row['height']] = array(
                    'width' => $row['width'],
                    'height' => $row['height'],
                    );
            }

            // Tim ra cac kich thuoc chua co thumb
            $arrayDiff = array_diff_key($thumbSize, $arrayThumbExists);

            if (!empty($arrayDiff)) {
                foreach ($arrayDiff as $row) {
                    $row['link'] = $this->creatThumb($this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/images/' . $picture['file'], $this->root_dir . '/' . $thumbDir, $row['width'], $row['height']);
                    $row['link'] = substr($this->root_dir . '/' . $thumbDir . '/' . $row['link'], strlen($this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/thumb/'));

                    $this->db->query("INSERT INTO " . $this->table_prefix . "_thumbs VALUES( " . $picture['id'] . ", " . $row['width'] . ", " . $row['height'] . ", " . $this->db->quote($row['link']) . " )");
                }
            }
        }
    }

    /**
     * nv_mod_gallery_tool::fixAlbums()
     *
     * @param mixed $ids
     * @return
     */
    public function fixAlbums($ids)
    {
        $ids = $this->IdHandle($ids);
        $albums = $this->getAlbumsByID($ids);

        // Cap nhat so anh cua album
        // Cap nhat danh sach anh cua album

        foreach ($albums as $album) {
            $album['datapics'] = $this->string2array($album['datapics']);

            if (!empty($album['datapics'])) {
                $pictures = $this->getPicsByID($album['datapics']);
                $pictures = array_keys($pictures);
                $pictures = array_values(array_intersect($album['datapics'], $pictures));

                if ($pictures != $album['datapics']) {
                    $this->db->query("UPDATE " . $this->table_prefix . "_albums SET datapics=" . $this->db->quote($this->buildSQLLids($pictures)) . ", numpics=" . sizeof($pictures) . " WHERE id=" . $album['id']);
                }
            }
        }
    }

    /**
     * nv_mod_gallery_tool::sqlSearchId()
     *
     * @param mixed $id
     * @param mixed $field
     * @param string $logic
     * @return
     */
    public function sqlSearchId($id, $field, $logic = 'OR')
    {
        $id = $this->IdHandle($id);
        if (empty($id))
            return $field . "=''";

        $query = array();
        foreach ($id as $_id) {
            $query[] = $field . " LIKE '%," . $_id . ",%'";
        }
        $query = implode(" " . $logic . " ", $query);

        return $query;
    }

    /**
     * nv_mod_gallery_tool::uploadDirInit()
     *
     * @param mixed $path
     * @return
     */
    public function uploadDirInit($path)
    {
        if (file_exists($this->root_dir . '/' . $this->upload_dir . '/' . $path)) {
            $upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $path;
        } else {
            $upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name;
            $e = explode("/", $path);

            if (!empty($e)) {
                $cp = "";
                foreach ($e as $p) {
                    if (!empty($p) and !is_dir($this->root_dir . '/' . $this->upload_dir . '/' . $cp . $p)) {
                        $mk = nv_mkdir($this->root_dir . '/' . $this->upload_dir . '/' . $cp, $p);
                        if ($mk[0] > 0) {
                            $upload_real_dir_page = $mk[2];
                            try {
                                $this->db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . $this->upload_dir . "/" . $cp . $p . "', 0)");
                            } catch (PDOException $e) {
                                trigger_error($e->getMessage());
                            }
                        }
                    } elseif (!empty($p)) {
                        $upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $cp . $p;
                    }
                    $cp .= $p . '/';
                }
            }
            $upload_real_dir_page = str_replace("\\", "/", $upload_real_dir_page);
        }

        return str_replace($this->root_dir . "/", "", $upload_real_dir_page);
    }

    /**
     * nv_mod_gallery_tool::getFullAlbum()
     *
     * @param mixed $id
     * @return
     */
    public function getFullAlbum($id)
    {
        $id = intval($id);
        $cacheFile = $this->lang_data . "_" . $this->mod_name . "_" . $id . "_" . $this->cache_prefix . ".cache";

        if (($cache = $this->getCache($cacheFile)) != false) {
            $array = unserialize($cache);
        } else {
            $array = array();
            $sql = "SELECT * FROM " . $this->table_prefix . "_albums WHERE id=" . $id;
            $result = $this->db->query($sql);

            if ($result->rowCount()) {
                $array = $result->fetch();
                $array['datapics'] = $this->string2array($array['datapics']);
                $array['pics'] = array();

                // Lay thong tin anh
                $pics = $this->getPicsByID($array['datapics'], true);

                // Lay anh da cat cho album
                $thumbs = array();
                $sql = "SELECT * FROM " . $this->table_prefix . "_thumbs WHERE picid IN(" . implode(',', array_keys($pics)) . ") AND( ( width=" . $array['bigw'] . " AND height=" . $array['bigh'] . " ) OR ( width=" . $array['smallw'] . " AND height=" . $array['smallh'] . " ) )";
                $result = $this->db->query($sql);

                while ($row = $result->fetch()) {
                    if ($row['width'] == $array['bigw'] and $row['height'] == $array['bigh']) {
                        $thumbs[$row['picid']]['big'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];
                    } else {
                        $thumbs[$row['picid']]['small'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];
                    }
                }

                foreach ($pics as $pic) {
                    $pic['file'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/images/' . $pic['file'];
                    $pic['thumb'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/sys/' . $pic['thumb'];
                    $pic['thumbBig'] = $pic['thumbSmall'] = $pic['thumb'];
                    $pic['albums'] = $this->string2array($pic['albums']);

                    if (!empty($thumbs[$pic['id']]['big'])) {
                        $pic['thumbBig'] = $thumbs[$pic['id']]['big'];
                    }

                    if (!empty($thumbs[$pic['id']]['small'])) {
                        $pic['thumbSmall'] = $thumbs[$pic['id']]['small'];
                    }

                    $array['pics'][$pic['id']] = $pic;
                }

                $cache = serialize($array);
                $this->setCache($cacheFile, $cache);
            }
        }

        return $array;
    }
}
