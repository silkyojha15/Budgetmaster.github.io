<?php

namespace ContentEgg\application\components;

use ContentEgg\application\helpers\ImageHelper;
use ContentEgg\application\helpers\ArrayHelper;

/**
 * ContentManager class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class ContentManager {

    const META_PREFIX_DATA = '_cegg_data_';
    const META_PREFIX_LAST_ITEMS_UPDATE = '_cegg_last_update_';
    const META_PREFIX_KEYWORD = '_cegg_keyword';
    const META_PREFIX_LAST_BYKEYWORD_UPDATE = '_cegg_last_bykeyword_update';

    public static function saveData(array $data, $module_id, $post_id)
    {
        if (!$data)
        {
            self::deleteData($module_id, $post_id);
            return;
        }

        foreach ($data as $i => $d)
        {
            if (is_object($d))
                $data[$i] = ArrayHelper::object2Array($d);
        }

        $data = self::setIds($data);

        $old_data = \get_post_meta($post_id, self::META_PREFIX_DATA . $module_id, true);
        if (!$old_data)
            $old_data = array();
        $outdated = array();
        $data_changed = true;

        if ($old_data)
        {
            $outdated = array_diff_key($old_data, $data);
            $new = array_diff_key($data, $old_data);

            if (!$outdated && !$new)
                $data_changed = false;

            /*
             * we need force data update because title or description can be edited manually or items price update
              if (!$data_changed)
              return;
             * 
             */
        }
        // Sanitize content for allowed HTML tags and more. 
        array_walk_recursive($data, array('self', 'sanitizeData'));
        $module = ModuleManager::getInstance()->factory($module_id);
        $data = $module->presavePrepare($data, $post_id);

        // save data
        \update_post_meta($post_id, self::META_PREFIX_DATA . $module_id, $data);

        self::clearData($outdated);

        // touch last update time only if data changed?
        if ($data_changed)
        {
            self::touchUpdateTime($post_id, $module_id);
        }

        \do_action('content_egg_save_data', $data, $module_id, $post_id);
    }

    public static function deleteData($module_id, $post_id)
    {
        $data = \get_post_meta($post_id, self::META_PREFIX_DATA . $module_id, true);
        if (!$data)
            return;

        \delete_post_meta($post_id, self::META_PREFIX_DATA . $module_id);
        \delete_post_meta($post_id, self::META_PREFIX_LAST_BYKEYWORD_UPDATE . $module_id);
        \delete_post_meta($post_id, self::META_PREFIX_LAST_ITEMS_UPDATE . $module_id);

        self::clearData($data);

        \do_action('content_egg_save_data', array(), $module_id, $post_id);
    }

    private static function clearData($data)
    {
        // delete old img files if needed
        foreach ($data as $d)
        {
            if (empty($d['img_file']))
                continue;
            $img_file = ImageHelper::getFullImgPath($d['img_file']);
            if (is_file($img_file))
                @unlink($img_file);
        }
    }

    private static function setIds($data)
    {
        $results = array();
        foreach ($data as $d)
        {
            $results[$d['unique_id']] = $d;
        }
        return $results;
    }

    public static function touchUpdateTime($post_id, $module_id)
    {
        $time = time();
        \update_post_meta($post_id, self::META_PREFIX_LAST_BYKEYWORD_UPDATE . $module_id, $time);
        self::touchUpdateItemsTime($post_id, $module_id, $time);
    }

    public static function touchUpdateItemsTime($post_id, $module_id, $time = null)
    {
        if (!$time)
            $time = time();
        \update_post_meta($post_id, self::META_PREFIX_LAST_ITEMS_UPDATE . $module_id, $time);
    }

    private static function sanitizeData(&$data, $key)
    {
        if (in_array((string) $key, array('img', 'url', 'IFrameURL', 'orig_url')))
        {
            //$data = \esc_url_raw($data);
            //@todo... This filter allows all letters, digits and $-_.+!*'(),{}|\\^~[]`"><#%;/?:@&=
            $data = filter_var($data, FILTER_SANITIZE_URL);
        } elseif ($key === 'description')
        {
            $data = \wp_kses_post($data);
        } elseif ($key === 'linkHtml')
        {
            $data; //cj link
        } elseif ($key === 'title')
        {
            $data = \sanitize_text_field($data);
        } elseif ($key === 'last_update' && !$data)
        {
            $data = time();
        } else
            $data = \strip_tags($data);
    }

    public static function isDataExists($post_id, $module_id)
    {
        return (bool) \get_post_meta($post_id, self::META_PREFIX_LAST_BYKEYWORD_UPDATE . $module_id, true);
    }

}
