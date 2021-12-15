<?php

namespace ContentEgg\application;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\components\ModuleTemplateManager;
use ContentEgg\application\components\Shortcoded;
use ContentEgg\application\helpers\TextHelper;

/**
 * EggShortcode class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
class EggShortcode {

    const shortcode = 'content-egg';

    private static $instance = null;

    //private $items = array();
    //private $item_pointer = array();

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;
        return self::$instance;
    }

    private function __construct()
    {
        \add_shortcode(self::shortcode, array($this, 'viewData'));
        \add_filter('term_description', 'shortcode_unautop');
        \add_filter('term_description', 'do_shortcode');
    }

    private function prepareAttr($atts)
    {
        $a = shortcode_atts(array(
            'module' => null,
            'limit' => 0,
            'offset' => 0,
            'next' => 0,
            'template' => '',
            'locale' => '',
                //'order' => 'asc',
                ), $atts);
        //$order_allowed = array('rand', 'title', 'price', 'manufacturer', 'in_stock', 'create_date', 'last_update', 'last_in_stock', 'shop_id');

        $a['next'] = (int) $a['next'];
        $a['limit'] = (int) $a['limit'];
        $a['offset'] = (int) $a['offset'];
        $a['module'] = TextHelper::clear($a['module']);
        $a['locale'] = TextHelper::clear($a['locale']);

        if ($a['template'] && $a['module'])
        {
            $a['template'] = ModuleTemplateManager::getInstance($a['module'])->prepareShortcodeTempate($a['template']);
        } else
            $a['template'] = '';
        return $a;
    }

    public function viewData($atts, $content = "")
    {
        global $post;

        $a = $this->prepareAttr($atts);

        if (empty($a['module']))
            return;
        $module_id = $a['module'];
        if (!ModuleManager::getInstance()->isModuleActive($module_id))
            return;

        Shortcoded::getInstance($post->ID)->setShortcodedModule($module_id);
        return ModuleViewer::getInstance()->viewModuleData($module_id, null, $a);
    }

    public static function arraySortByColumn(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row)
        {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

}
