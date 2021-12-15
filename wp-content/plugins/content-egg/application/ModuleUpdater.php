<?php

namespace ContentEgg\application;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\components\ContentManager;
use ContentEgg\application\admin\GeneralConfig;

/**
 * ModuleUpdater class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class ModuleUpdater {

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;

        return self::$instance;
    }

    private function __construct()
    {
        
    }

    public function init()
    {
        if (GeneralConfig::getInstance()->option('filter_bots'))
        {
            if (!class_exists('\Jaybizzle\CrawlerDetect'))
                require_once \ContentEgg\PLUGIN_PATH . 'application/vendor/CrawlerDetect.php';

            $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();
            // Check the user agent of the current 'visitor'
            if ($CrawlerDetect->isCrawler())
            {
                // true if crawler user agent detected
                return;
            }
        }

        // priority = 10 because ModuleViewer added with a priority of 12 
        // & do_shortcode() is registered as a default filter on 'the_content' with a priority of 11.
        \add_filter('the_content', array($this, 'update'), 10);
    }

    public function update($content)
    {
        if (!is_single() && !is_page())
            return $content;

        $this->updateByKeyword();
        $this->updateItems();
        return $content;
    }

    private function updateByKeyword()
    {
        global $post;

        foreach (ModuleManager::getInstance()->getModules(true) as $module)
        {
            if (!$module->isAffiliateParser())
                continue;

            $ttl = $module->config('ttl');
            if (!$ttl)
                continue;

            $keyword = \get_post_meta($post->ID, ContentManager::META_PREFIX_KEYWORD . $module->getId(), true);
            if (!$keyword)
                continue;

            $last_update = (int) \get_post_meta($post->ID, ContentManager::META_PREFIX_LAST_BYKEYWORD_UPDATE . $module->getId(), true);

            if ($last_update && time() - $last_update < $ttl)
                continue;

            // update time in any case...
            ContentManager::touchUpdateTime($post->ID, $module->getId());

            try
            {
                $data = $module->doRequest($keyword, array(), true);
                // nodata!
                if (!$data)
                {
                    //ContentManager::touchUpdateTime($post->ID, $module->getId());
                    continue;
                }
            } catch (\Exception $e)
            {
                // error
                //ContentManager::touchUpdateTime($post->ID, $module->getId());
                continue;
            }

            $data = array_map(array('self', 'object2Array'), $data);
            ContentManager::saveData($data, $module->getId(), $post->ID);
        }
    }

    private function updateItems()
    {
        global $post;

        foreach (ModuleManager::getInstance()->getModules(true) as $module)
        {
            if (!$module->isAffiliateParser() || !$module->isItemsUpdateAvailable())
                continue;

            $ttl_items = $module->config('ttl_items');
            if (!$ttl_items)
                continue;

            $last_items_update = (int) \get_post_meta($post->ID, ContentManager::META_PREFIX_LAST_ITEMS_UPDATE . $module->getId(), true);

            if (!$last_items_update || time() - $last_items_update < $ttl_items)
                continue;

            $items = \get_post_meta($post->ID, ContentManager::META_PREFIX_DATA . $module->getId(), true);
            if (!$items)
                continue;

            try
            {
                $updated_data = $module->doRequestItems($items);
            } catch (\Exception $e)
            {
                // error
                ContentManager::touchUpdateItemsTime($post->ID, $module->getId());
                continue;
            }

            // save & update time
            ContentManager::saveData($updated_data, $module->getId(), $post->ID);
            ContentManager::touchUpdateItemsTime($post->ID, $module->getId());
        }
    }

    /**
     *  Full depth recursive conversion to array
     * @param type $object
     * @return array
     */
    public static function object2Array($object)
    {
        return json_decode(json_encode($object), true);
    }

}
